$(document).ready(function () {
  // Set active state on sidebar links
  const currentPath = window.location.pathname;

  $('.sidebar .nav-link').each(function () {
    const linkPath = $(this).data('url');
    if (linkPath === currentPath) {
      $('.sidebar .nav-link').removeClass('active');
      $(this).addClass('active');
    }
  });

  // Optional: Set active tab (e.g. All Posts, Drafts, etc.)
  $('.bp-tab').each(function () {
    const tabText = $(this).text().trim().toLowerCase();
    const urlPath = window.location.pathname.toLowerCase();

    if (urlPath.includes('blogpost')) {
      $('.bp-tab').removeClass('active');
      // You could optionally add logic here to set which tab is active based on query or route
      if (tabText === 'all posts') {
        $(this).addClass('active');
      }
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