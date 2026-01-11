<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class CustomerRegistrationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            // Customer Details
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'job_title' => ['required', 'string', 'max:255'],

            // Company Details
            'name' => ['required', 'string', 'max:255'],
            'registration_number' => ['nullable', 'string', 'max:255'],
            'trading_name' => ['nullable', 'string', 'max:255'],
            'vat_number' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'trading_address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:255'],
            'fax' => ['nullable', 'string', 'max:255'],
            'activities_description' => ['nullable', 'string'],

            // Contacts
            'purchasing_contact_name' => ['nullable', 'string', 'max:255'],
            'purchasing_contact_phone' => ['nullable', 'string', 'max:255'],
            'purchasing_contact_email' => ['nullable', 'string', 'email', 'max:255'],
            'accounts_contact_name' => ['nullable', 'string', 'max:255'],
            'accounts_contact_phone' => ['nullable', 'string', 'max:255'],
            'accounts_contact_email' => ['nullable', 'string', 'email', 'max:255'],

            // Financial
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_address' => ['nullable', 'string'],
            'bank_sort_code' => ['nullable', 'string', 'max:255'],
            'bank_account_number' => ['nullable', 'string', 'max:255'],
            'trade_ref_1_details' => ['nullable', 'string'],
            'trade_ref_1_phone' => ['nullable', 'string', 'max:255'],
            'trade_ref_1_email' => ['nullable', 'string', 'email', 'max:255'],
            'trade_ref_2_details' => ['nullable', 'string'],
            'trade_ref_2_phone' => ['nullable', 'string', 'max:255'],
            'trade_ref_2_email' => ['nullable', 'string', 'email', 'max:255'],
            'requested_credit_limit' => ['nullable', 'numeric'],
        ]);

        return DB::transaction(function () use ($request) {
            // Role Determination Logic
            $role = $request->requested_credit_limit ? 'credit facilities account' : 'trade account';

            // Create Company first
            $company = Company::create([
                'name' => $request->name,
                'registration_number' => $request->registration_number,
                'trading_name' => $request->trading_name,
                'vat_number' => $request->vat_number,
                'address' => $request->address,
                'trading_address' => $request->trading_address,
                'phone' => $request->phone,
                'fax' => $request->fax,
                'activities_description' => $request->activities_description,
                'purchasing_contact_name' => $request->purchasing_contact_name,
                'purchasing_contact_phone' => $request->purchasing_contact_phone,
                'purchasing_contact_email' => $request->purchasing_contact_email,
                'accounts_contact_name' => $request->accounts_contact_name,
                'accounts_contact_phone' => $request->accounts_contact_phone,
                'accounts_contact_email' => $request->accounts_contact_email,
                'bank_name' => $request->bank_name,
                'bank_address' => $request->bank_address,
                'bank_sort_code' => $request->bank_sort_code,
                'bank_account_number' => $request->bank_account_number,
                'trade_ref_1_details' => $request->trade_ref_1_details,
                'trade_ref_1_phone' => $request->trade_ref_1_phone,
                'trade_ref_1_email' => $request->trade_ref_1_email,
                'trade_ref_2_details' => $request->trade_ref_2_details,
                'trade_ref_2_phone' => $request->trade_ref_2_phone,
                'trade_ref_2_email' => $request->trade_ref_2_email,
                'requested_credit_limit' => $request->requested_credit_limit,
            ]);

            // Create Customer with pending status linked to company
            $customer = Customer::create([
                'company_id' => $company->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'role_applied' => $role,
                'account_number' => $request->account ?? 'PENDING-' . uniqid(), // Fallback if not provided or generated
                'job_title' => $request->job_title,
                'status' => 'active', // System status active, but review status pending
                'status_review' => 'pending',
            ]);

            // Notify Admin
            try {
                \Illuminate\Support\Facades\Notification::route('mail', 'gemaantikahr@gmail.com')
                    ->notify(new \App\Notifications\NewCustomerSubmissionNotification($customer));
            } catch (\Exception $e) {
                // Log error or ignore if mail fails in local dev
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Registration submitted successfully. Please wait for admin approval.',
                    'customer' => $customer->load('company'),
                ], 201);
            }

            return redirect()->back()->with('success', 'Registration submitted successfully. Please wait for admin approval.');
        });
    }
}
