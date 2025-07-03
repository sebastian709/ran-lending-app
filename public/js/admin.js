$(document).ready(function () {
  const currentPath = window.location.pathname;

  $('.sidebar .nav-link').each(function () {
    const url = $(this).data('url');

    // Set active if currentPath starts with data-url
    if (currentPath.startsWith(url)) {
      $(this).addClass('active');
    } else {
      $(this).removeClass('active');
    }
  });

  initCKEditor();
  initBlogImageUpload();
  createBPTagify();

  // ✅ CSRF setup - FIXED closing
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
});


$(document).on('click', '[data-url]', function (e) {
  e.preventDefault();
  const url = $(this).data('url');

  $.get(url, function (data) {
    const content = $(data).find('#content').html(); // ito yung @yield('content')
    $('#content').html(content); // i-inject sa layout
    window.history.pushState({}, '', url);

    $('.sidebar .nav-link').removeClass('active');
    $(`[data-url="${url}"]`).addClass('active');

    updateBreadcrumb(url);

    // Call plugins safely
    initCKEditor?.();
    initBlogImageUpload?.();
    createBPTagify?.();

    // 🟢 Call loadBlogPostList IF present
    if ($('#blogPostList').length) {
      loadBlogPostList(); // default: all
    }
  });
});

function loadBlogPostList(status = 'all') {
  $.get('/admin/blogpost', { status }, function (data) {
    $('#blogPostList').html(data);
  });
}

let editorInstance; // Define globally at the top

function initCKEditor() {
  const editorElement = document.querySelector('#blogContent');
  if (editorElement) {
    ClassicEditor
      .create(editorElement)
      .then(editor => {
        editorInstance = editor; // Store globally so you can access later
      })
      .catch(error => {
        console.error(error);
      });
  }
}


function createNewEditor(target) {
  ClassicEditor
    .create(target)
    .then(editor => {
      editorInstance = editor;
    })
    .catch(error => {
      console.error(error);
    });
}

function createBPTagify() {
  const cbpInput = document.querySelector('#tagsInput');

  if (!cbpInput) {
    return;
  }
  new Tagify(cbpInput);
}


function updateBreadcrumb(url) {
  const segments = url.replace(/^\/+|\/+$/g, '').split('/');
  let breadcrumbHTML = '';
  let path = '/admin';

  // Filter out 'admin' and empty segments
  const filteredSegments = segments.filter(segment => segment !== 'admin' && segment !== '');

  filteredSegments.forEach((segment, index) => {
    path += '/' + segment;

    // Convert camelCase or PascalCase to 'Title Case'
    const label = segment
      .replace(/([A-Z])/g, ' $1')         // insert space before capital letters
      .replace(/^./, str => str.toUpperCase()) // capitalize first character
      .trim();

    if (index === filteredSegments.length - 1) {
      breadcrumbHTML += `<li class="breadcrumb-item active" aria-current="page">${label}</li>`;
    } else {
      breadcrumbHTML += `<li class="breadcrumb-item"><a href="${path}">${label}</a></li>`;
    }
  });

  $('#breadcrumbs').html(breadcrumbHTML);
}

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



