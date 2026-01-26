@component('mail::message')
# Statement of Account

Dear Accounts Team,

Please find attached the latest Statement of Account for **{{ $company->name }}**.

**Total Outstanding Balance: £{{ number_format($totalBalance, 2) }}**

@component('mail::button', ['url' => url('invoice-payment')])
Pay Now
@endcomponent

If you have any questions, please simply reply to this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent