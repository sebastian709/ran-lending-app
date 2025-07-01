$(document).ready(function () {
  // Set active nav-link on page load
  const currentPath = window.location.pathname;

  $('.sidebar .nav-link').each(function () {
    const url = $(this).data('url');
    if (url === currentPath) {
      $(this).addClass('active');
    } else {
      $(this).removeClass('active');
    }
  });
});


function updateBreadcrumb(url) {
  const segments = url.replace(/^\/+|\/+$/g, '').split('/');
  let breadcrumbHTML = '';
  let path = '';

  // Filter out "admin" and "dashboard"
  const filteredSegments = segments.filter(segment => segment !== 'admin' && segment !== '');

  filteredSegments.forEach((segment, index) => {
    path += '/' + segment;
    const label = segment.charAt(0).toUpperCase() + segment.slice(1);

    if (index === filteredSegments.length - 1) {
      breadcrumbHTML += `<li class="breadcrumb-item active" aria-current="page">${label}</li>`;
    } else {
      breadcrumbHTML += `<li class="breadcrumb-item"><a href="${path}">${label}</a></li>`;
    }
  });

  $('#breadcrumbs').html(breadcrumbHTML);
}


$(document).on('click', '[data-url]', function (e) {
  e.preventDefault();
  const url = $(this).data('url');

  $.get(url, function (data) {
    const content = $(data).find('#content').html();
    $('#content').html(content);
    window.history.pushState({}, '', url);

    $('.sidebar .nav-link').removeClass('active');
    $(`[data-url="${url}"]`).addClass('active');

    updateBreadcrumb(url);
  });
});





window.onpopstate = function () {
  $.get(location.pathname, function (data) {
    const content = $(data).find('#content').html();
    $('#content').html(content);

    $('.sidebar .nav-link').removeClass('active');
    $(`[data-url="${location.pathname}"]`).addClass('active');
  });
};

const sidebar = document.getElementById('sidebar');
const toggleBtn = document.getElementById('toggleBtn');
const main = document.getElementById('mainContent');
const overlay = document.getElementById('overlay');

toggleBtn.addEventListener('click', () => {
  sidebar.classList.add('active');
  main.classList.add('overlay-active');
  overlay.classList.add('show');
});

overlay.addEventListener('click', () => {
  sidebar.classList.remove('active');
  main.classList.remove('overlay-active');
  overlay.classList.remove('show');
});

window.addEventListener('scroll', function () {
  const bellIcon = document.getElementById('notifIcon');

  if (window.scrollY > 10) {
    bellIcon.classList.add('scrolled');
  } else {
    bellIcon.classList.remove('scrolled');
  }
});

$(window).on('scroll', function () {
  const scrollPos = $(window).scrollTop();

  if (scrollPos > 10) {
    $('.topbar').addClass('scrolled');
    $('.top-bar-icon').addClass('scrolled');
    $('.toggle-btn').addClass('scrolled');
  } else {
    $('.topbar').removeClass('scrolled');
    $('.top-bar-icon').removeClass('scrolled');
    $('.toggle-btn').removeClass('scrolled');
  }
});