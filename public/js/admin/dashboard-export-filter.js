(function ($) {
  function initDashboardExportFilter() {
    const buttons = document.querySelectorAll('.ran_dashboard .btn-date');
    if (!buttons.length) {
      return;
    }

    let currentFilter = 'month';

    function applyFilter(filter) {
      currentFilter = filter;

      document.querySelectorAll('.ran_dashboard .btn-date').forEach((btn) => {
        btn.classList.toggle('active', btn.dataset.filter === currentFilter);
      });

      document.querySelectorAll('.ran_dashboard .btn-export').forEach((link) => {
        try {
          const url = new URL(link.href);
          url.searchParams.set('filter', currentFilter);
          link.href = url.toString();
        } catch (_) {
          // Ignore malformed URLs.
        }
      });
    }

    buttons.forEach((btn) => {
      if (btn.dataset.boundExportFilter === '1') {
        return;
      }

      btn.dataset.boundExportFilter = '1';
      btn.addEventListener('click', function () {
        applyFilter(this.dataset.filter || 'month');
      });
    });

    applyFilter(currentFilter);
  }

  document.addEventListener('DOMContentLoaded', initDashboardExportFilter);
  $(document).on('admin:content-loaded', initDashboardExportFilter);
})(window.jQuery);
