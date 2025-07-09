 <!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="p-3">
        <ul class="sidebar-nav">
            <li><a href="#" class="active"><i class="ri-home-line"></i> Dashboard</a></li>
            <li><a href="#"><i class="ri-money-dollar-circle-line"></i> My Loans</a></li>
            <li><a href="#"><i class="ri-file-text-line"></i> Applications</a></li>
            <li><a href="#"><i class="ri-calendar-line"></i> Payment Schedule</a></li>
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