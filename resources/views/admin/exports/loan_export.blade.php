<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Report</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 20px;
        background-color: #f5f6fa;
        color: #2f3640;
    }
    h1 {
        text-align: center;
        color: #40739e;
        margin-bottom: 30px;
    }
    h3 {
        color: #40739e;
        margin-bottom: 10px;
        border-bottom: 2px solid #40739e;
        display: inline-block;
        padding-bottom: 5px;
    }
    .card-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 30px;
    }
    .card {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        flex: 1 1 220px;
        min-width: 220px;
    }
    .card h4 {
        margin: 0 0 10px 0;
        color: #718093;
        font-weight: normal;
    }
    ul {
        list-style: none;
        padding-left: 0;
    }
    ul li {
        margin-bottom: 5px;
        padding-left: 10px;
        position: relative;
    }
    ul li::before {
        content: "•";
        color: #40739e;
        position: absolute;
        left: 0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        background-color: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 3px 6px rgba(0,0,0,0.05);
    }
    table th, table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
    }
    table th {
        background-color: #40739e;
        color: #fff;
    }
    table tr:nth-child(even) {
        background-color: #f1f2f6;
    }
    .financial-overview p {
        font-weight: bold;
        margin: 5px 0;
    }
</style>
</head>
<body>

<h1>Dashboard Report</h1>

<!-- Quick Stats -->
<div class="card-container">
    <div class="card">
        <h3>Quick Stats</h3>
        <ul>
            <li>Available Money: Php {{ number_format($quick_stats['available_money'], 2) }}</li>
            <li>Balance: Php {{ number_format($quick_stats['balance'], 2) }}</li>
            <li>Total Tithes: Php {{ number_format($quick_stats['tithes'], 2) }}</li>
            <li>Miscellaneous: Php {{ number_format($quick_stats['misc'], 2) }}</li>
        </ul>
    </div>
    <div class="card">
        <h3>Loan Applications</h3>
        <h4>Total Applications: {{ $grand_total }}</h4>
        <ul>
            @foreach($status_totals as $status => $count)
                <li>{{ ucfirst($status) }}: {{ $count }}</li>
            @endforeach
        </ul>
    </div>
    <div class="card">
        <h3>Loan Insight</h3>
        <ul>
            <li>Total Disbursed: Php {{ number_format($loan_insight['total_disburse'], 2) }}</li>
            <li>Outstanding Balance: Php {{ number_format($loan_insight['total_balance'], 2) }}</li>
            <li>Total Amount Repaid: Php {{ number_format($loan_insight['verified_payments'], 2) }}</li>
            <li>Upcoming Balance (Next 30 Days): Php {{ number_format($loan_insight['upcoming_balance'], 2) }}</li>
        </ul>
    </div>
    <div class="card">
        <h3>Borrower Insight</h3>
        <ul>
            <li>Total Borrowers: {{ $borrower_insight['total_borrowers'] }}</li>
            <li>Good Payers: {{ $borrower_insight['good_payer'] }}</li>
            <li>With Penalty: {{ $borrower_insight['with_penalty'] }}</li>
            <li>with Violation: {{ $borrower_insight['with_violation'] }}</li>
        </ul>
    </div>
</div>
<!-- Quick Stats -->

<!-- Scheduled Loans -->
<h3>Scheduled Loans</h3>
<table>
<tr><th>Borrower</th><th>Tenure Date</th><th>Referral</th></tr>
@foreach($scheduled_loans as $loan)
<tr>
<td>{{ $loan->full_name }}</td>
<td>{{ $loan->tenure_date }}</td>
<td>{{ $loan->referral }}</td>
</tr>
@endforeach
</table>
 
<!-- Recent Applications -->
<!-- Recent Applications -->
<h3>Recent Applications</h3>
<table>
    <tr>
        <th>Borrower</th>
        <th>Date</th>
        <th>Referral</th>
        <th>Amount</th>
        <th>Status</th>
    </tr>
    @foreach($recent_applications as $app)
        @php
            switch($app->loan_status) {
                case 1:
                    $statusText = 'Pending for Approval';
                    break;
                case 2:
                    $statusText = 'For Interview';
                    break;
                case 3:
                    $statusText = 'For Revision';
                    break;
                case 4:
                    $statusText = 'Waiting for Disbursement';
                    break;
                case 5:
                    $statusText = 'Transferred and Processed';
                    break;
                case 6:
                    $statusText = 'Rejected';
                    break;
                case 7:
                    $statusText = 'Closed';

                    break;
                case 8:
                    $statusText = 'Scheduled';
                    break;
                case 9:
                    $statusText = 'Cancelled';
                    break;
                default:
                    $statusText = 'Unknown';
                    break;
            }
        @endphp
        <tr>
            <td>{{ $app->full_name }}</td>
            <td>{{ $app->created_at }}</td>
            <td>{{ $app->referral }}</td>
            <td>{{ number_format($app->loan_amount, 2) }}</td>
            <td><span>{{ $statusText }}</span></td>
        </tr>
    @endforeach
</table>


<!-- Recent Payments -->
<h3>Recent Payments</h3>
<table>
<tr><th>Added By</th><th>Amount</th><th>Reference</th><th>Date</th><th>Coverage</th><th>Status</th></tr>
@foreach($recent_payments as $pay)
<tr>
<td>{{ $pay->full_name }}</td>
<td>{{ $pay->amount_sent }}</td>
<td>{{ $pay->reference_code }}</td>
<td>{{ $pay->created_at }}</td>
<td>{{ $pay->coverage }}</td>
<td>{{ $pay->status }}</td>
</tr>
@endforeach
</table>

<!-- Top 5 Borrowers -->
<h3>Top 5 Borrowers</h3>
<table>
<tr><th>Borrower</th><th>Amount</th></tr>
@foreach($top_borrowers as $borrower)
<tr>
<td>{{ $borrower->full_name }}</td>
<td>{{ $borrower->loan_amount }}</td>
</tr>
@endforeach
</table>

<!-- Financial Overview -->
<h3>Financial Overview</h3>
<div class="financial-overview">
<p>Total Interest Earned: Php {{ number_format($total_interest,2) }}</p>
<p>Total Penalty Collected: Php {{ number_format($total_penalty,2) }}</p>
<p>Total Revenue: Php {{ number_format($total_interest + $total_penalty,2) }}</p>
</div>

</body>
</html>
