(function ($) {
  if (!$ || !$.ajaxSetup) {
    return;
  }

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
})(window.jQuery);
