{{-- Loan Status Empty States --}}
{{-- No Active Loan --}}
<div class="text-center py-5">
    <div class="mb-4 text-primary" style="font-size: 80px;">
        <i class="bi bi-wallet2"></i>
    </div>
    <h2 class="h3 fw-bold mb-3 text-primary">Looks like you don’t have a loan yet</h2>
    <p class="text-muted fs-5 mb-4">
        Once you apply and your loan is approved, all your details will show up here.<br>
        Need help getting started?
    </p>
    <a href="" class="btn btn-primary btn-lg">
        Apply a Loan
    </a>
</div>

{{-- Pending Loan --}}
<div class="text-center py-5 d-none">
    <div class="mb-4 text-warning" style="font-size: 80px;">
        <i class="bi bi-hourglass-split"></i>
    </div>
    <h2 class="h3 fw-bold mb-3 text-warning">Your loan application is under review</h2>
    <p class="text-muted fs-5">
        We’re reviewing your application. You’ll be notified once it's approved.<br>
        You can check the status in your <a href="">My Loan</a> section page.
    </p>
</div>

{{-- Rejected Loan --}}
<div class="text-center py-5 d-none">
    <div class="mb-4 text-danger" style="font-size: 80px;">
        <i class="bi bi-x-circle"></i>
    </div>
    <h2 class="h3 fw-bold mb-3 text-danger">No Active Loans</h2>
    <p class="text-muted fs-5">
        You have no current loans. If you've applied recently and haven't seen an update,<br>
        please check your loan history in <a href="">My Loan</a> page.
    </p>
</div>
