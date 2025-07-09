$(document).on('click', '[data-url]', function (e) {
  e.preventDefault();
  const url = $(this).data('url');

  
  window.location.href = url;
});