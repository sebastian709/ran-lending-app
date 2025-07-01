$(window).on('scroll', function () {
  const scrollPos = $(window).scrollTop();

  if (scrollPos > 10) {
    $('.sticky-desktop').addClass('scrolled');
  } else {
    $('.sticky-desktop').removeClass('scrolled');
  }
});

$(document).on('click', '.bp-tab', function(e) {
    e.preventDefault(); // prevent default link behavior if needed
    $('.bp-tab').removeClass('active');  // remove active from all
    $(this).addClass('active');          // add active to clicked one
});