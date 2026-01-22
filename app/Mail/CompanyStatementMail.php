<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Company;

class CompanyStatementMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Company $company,
        public $creditLimits,
        public float $totalBalance,
        public string $statementPeriod
    ) {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Statement of Account - ' . $this->company->name . ' - ' . $this->statementPeriod,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.company-statement',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.company-statement', [
            'company' => $this->company,
            'creditLimits' => $this->creditLimits,
            'totalBalance' => $this->totalBalance,
            'statementPeriod' => $this->statementPeriod,
        ]);

        return [
            Attachment::fromData(fn() => $pdf->output(), 'Statement_' . $this->statementPeriod . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
