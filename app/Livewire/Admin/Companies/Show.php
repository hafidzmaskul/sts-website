<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use Livewire\Component;

class Show extends Component
{
    public Company $company;

    public $monthlyCreditLimits;
    public $showAddLimitModal = false;
    public $newLimitAmount;
    public $newLimitDescription;

    protected $rules = [
        'newLimitAmount' => 'required|numeric|min:0',
        'newLimitDescription' => 'nullable|string|max:255',
    ];

    public $statementMonth;
    public $statementYear;
    public $showStatementModal = false;

    public function mount(Company $company)
    {
        $this->company = $company->load(['customers', 'creditLimits' => fn($q) => $q->latest()]);
        $this->refreshMonthlyLimits();

        $this->statementMonth = now()->month;
        $this->statementYear = now()->year;
    }

    public function getMonthsProperty()
    {
        return collect(range(1, 12))->mapWithKeys(fn($m) => [$m => \Carbon\Carbon::create(null, $m)->format('F')]);
    }

    public function getYearsProperty()
    {
        return range(now()->year, now()->subYears(5)->year);
    }

    public function refreshMonthlyLimits()
    {
        $this->monthlyCreditLimits = \App\Models\MonthlyCreditLimit::with('user')
            ->where('company_id', $this->company->id)
            ->latest()
            ->get();
    }

    public function confirmAddLimit()
    {
        $this->reset(['newLimitAmount', 'newLimitDescription']);
        $this->showAddLimitModal = true;
    }

    public function saveLimit()
    {
        $this->validate();

        \App\Models\MonthlyCreditLimit::create([
            'company_id' => $this->company->id,
            'user_id' => auth()->id(),
            'amount' => $this->newLimitAmount,
            'description' => $this->newLimitDescription ?? 'Manual entry',
        ]);

        $this->showAddLimitModal = false;
        $this->refreshMonthlyLimits();

        $this->dispatch('notify', type: 'success', message: 'Monthly credit limit added successfully.');
    }

    public function confirmSendStatement()
    {
        $this->showStatementModal = true;
    }

    public function sendInvoice()
    {
        $creditLimits = $this->company->creditLimits()
            ->whereMonth('created_at', $this->statementMonth)
            ->whereYear('created_at', $this->statementYear)
            ->where('debit', '>', 0)
            ->oldest() // Statements usually chronological
            ->get();

        if ($creditLimits->isEmpty()) {
            $this->dispatch('notify', type: 'error', message: 'No debit transactions found for the selected period.');
            return;
        }

        $totalBalance = $creditLimits->sum('debit');

        $emails = collect([
            $this->company->purchasing_contact_email,
            $this->company->accounts_contact_email,
        ])->filter()->unique();

        if ($emails->isEmpty()) {
            $this->dispatch('notify', type: 'error', message: 'No contact emails found for this company.');
            return;
        }

        // Generate PDF Content
        $statementPeriod = \Carbon\Carbon::create($this->statementYear, $this->statementMonth, 1)->format('F Y');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.company-statement', [
            'company' => $this->company,
            'creditLimits' => $creditLimits,
            'totalBalance' => $totalBalance,
            'statementPeriod' => $statementPeriod,
        ]);
        $pdfContent = $pdf->output();

        // 1. Store PDF
        $filename = 'statements/' . $this->company->id . '/' . time() . '_Statement_' . str_replace(' ', '_', $statementPeriod) . '.pdf';
        \Illuminate\Support\Facades\Storage::put($filename, $pdfContent);

        // 2. Log History
        \App\Models\StatementHistory::create([
            'company_id' => $this->company->id,
            'user_id' => auth()->id(),
            'period' => $statementPeriod,
            'recipients' => $emails->implode(', '),
            'file_path' => $filename,
        ]);

        foreach ($emails as $email) {
            \Illuminate\Support\Facades\Mail::to($email)->queue(
                new \App\Mail\CompanyStatementMail(
                    $this->company,
                    $creditLimits,
                    $totalBalance,
                    $statementPeriod
                )
            );
        }

        $this->showStatementModal = false;
        $this->dispatch('notify', type: 'success', message: 'Statement for ' . $statementPeriod . ' sent and logged successfully.');
    }

    public function downloadStatement(\App\Models\StatementHistory $statement)
    {
        if (\Illuminate\Support\Facades\Storage::exists($statement->file_path)) {
            return \Illuminate\Support\Facades\Storage::download($statement->file_path);
        }

        $this->dispatch('notify', type: 'error', message: 'File not found.');
    }

    public function render()
    {
        $this->authorize('customers.view');

        return view('livewire.admin.companies.show')->title('Company Details');
    }
}
