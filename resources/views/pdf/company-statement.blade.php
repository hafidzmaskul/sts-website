<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Statement of Account</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
            color: #333;
        }

        .header {
            width: 100%;
            margin-bottom: 30px;
        }

        .company-info {
            float: left;
        }

        .statement-info {
            float: right;
            text-align: right;
        }

        .clear {
            clear: both;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border-bottom: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .total-row td {
            font-weight: bold;
            border-top: 2px solid #333;
            font-size: 16px;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="company-info">
            <h1 style="margin: 0; color: #4338ca;">GlobalFire Equipment</h1> <!-- Indigo-700 approx -->
            <p>
                123 Business Park<br>
                London, UK<br>
                Phone: +44 123 456 7890<br>
                Email: accounts@globalfire.co.uk
            </p>
        </div>
        <div class="statement-info">
            <h2>Statement of Account</h2>
            <p>
                <strong>Date:</strong> {{ now()->format('d M Y') }}<br>
                <strong>Period:</strong> {{ $statementPeriod }}<br>
                <strong>Company:</strong> {{ $company->name }}<br>
                <strong>Account No:</strong> {{ $company->id }}
            </p>
            <p>
                <strong>To:</strong><br>
                {{ $company->trading_name ?? $company->name }}<br>
                {!! nl2br(e($company->address)) !!}
            </p>
        </div>
        <div class="clear"></div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th class="text-right">Amount (Debit)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($creditLimits as $limit)
                <tr>
                    <td>{{ $limit->created_at->format('d/m/Y') }}</td>
                    <td>{{ $limit->description }}</td>
                    <td class="text-right">
                        {{ '£' . number_format($limit->debit, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2" class="text-right">Total for Period:</td>
                <td class="text-right">£{{ number_format($totalBalance, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Thank you for your business.</p>
        <p>If you have any questions concerning this statement, please contact us immediately.</p>
    </div>

</body>

</html>