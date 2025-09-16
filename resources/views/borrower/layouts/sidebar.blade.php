 <!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="p-3">
        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('borrower.pages.home') }}" class="{{ request()->routeIs('borrower.pages.home') ? 'active' : '' }}">
                    <i class="ri-home-line"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('my-loan.repayment-schedule') }}" class="{{ request()->routeIs('my-loan.*') ? 'active' : '' }}">
                    <i class="ri-money-dollar-circle-line"></i> My Loans
                </a>
            </li>
            <li>
                <a href="{{ route('loan.payment') }}" class="{{ request()->routeIs('loan.*') ? 'active' : '' }}">
                    <i class="ri-calendar-line"></i> Payments
                </a>
            </li>
            <!-- <li>
                <a href="" class="">
                    <i class="ri-file-text-line"></i> Applications
                </a>
            </li> -->
        </ul>
    </div>
</div>

@section('scripts')
<script>
    document.getElementById('sidebarToggle')?.addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('show');
    });

    document.addEventListener('click', function (event) {
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebarToggle');
        if (window.innerWidth <= 991.98 && !sidebar.contains(event.target) && !toggle?.contains(event.target)) {
            sidebar.classList.remove('show');
        }
    });

    window.addEventListener('resize', function () {
        const sidebar = document.getElementById('sidebar');
        if (window.innerWidth > 991.98) {
            sidebar.classList.remove('show');
        }
    });

</script>
@endsection