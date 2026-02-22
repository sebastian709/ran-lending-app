(function ($) {
  function initAppealTable() {
    const $table = $('#appealTable');
    if (!$table.length || !$.fn.DataTable) {
      return;
    }

    if ($.fn.DataTable.isDataTable($table)) {
      return;
    }

    $table.DataTable({
      dom: '<"appeal-table-toolbar row g-2 align-items-center mb-3"<"col-md-6 d-flex align-items-center"l><"col-md-6 d-flex justify-content-md-end"f>>rt<"appeal-table-footer row g-2 align-items-center mt-3"<"col-md-6"i><"col-md-6 d-flex justify-content-md-end"p>>',
      order: [[3, 'desc']],
      pageLength: 10,
      lengthMenu: [10, 25, 50, 100],
      responsive: true,
      autoWidth: false,
      pagingType: 'simple_numbers',
      language: {
        search: '',
        searchPlaceholder: 'Search borrower, loan id, status...'
      },
      columnDefs: [
        { orderable: false, targets: [2, 5] }
      ]
    });
  }

  $(initAppealTable);
  $(document).on('admin:content-loaded', initAppealTable);
})(window.jQuery);
