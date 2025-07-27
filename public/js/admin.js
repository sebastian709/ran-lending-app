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
  const isSidebar = $(this).data('is-sidebar');

  $.get(url, function (data) {
    const content = $(data).find('#content').html(); // ito yung @yield('content')
    $('#content').html(content); // i-inject sa layout
    window.history.pushState({}, '', url);

    $('.sidebar .nav-link').removeClass('active');

    if(parseInt(isSidebar) == 1){
      $(`[data-url="${url}"]`).addClass('active');
    }
    

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

let editorInstance; // Global CKEditor instance

function initCKEditor() {
  const editorElement = document.querySelector('#blogContent');
  if (editorElement) {
    createNewEditor(editorElement);
  }
}

function createNewEditor(target) {
  ClassicEditor
    .create(target, {
      extraPlugins: [MyCustomUploadAdapterPlugin]
    })
    .then(editor => {
      editorInstance = editor;
    })
    .catch(error => {
      console.error(error);
    });
}

// Plugin to hook into FileRepository
function MyCustomUploadAdapterPlugin(editor) {
  editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
    return new MyUploadAdapter(loader);
  };
}

// Upload adapter with image compression
class MyUploadAdapter {
  constructor(loader) {
    this.loader = loader;
  }

  async upload() {
    return this.loader.file
      .then(async file => {
        // Compress the image before upload
        const options = {
          maxSizeMB: 1,
          maxWidthOrHeight: 1024,
          useWebWorker: true
        };

        try {
          const compressedFile = await imageCompression(file, options);

          const data = new FormData();
          data.append('upload', compressedFile);

          return fetch('/upload', {
            method: 'POST',
            body: data,
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          })
            .then(response => response.json())
            .then(result => {
              return {
                default: result.url // adjust based on your server's response
              };
            });
        } catch (err) {
          console.error('Compression failed:', err);
          throw err;
        }
      });
  }

  abort() {
    // Optional: handle abort if needed
  }
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


$(document).ready(function () { // profile page
  let $form = $('#profile-form');
  let $inputs = $form.find('input');
  let $select = $form.find('select');
  let $changePhoto = $('#change-picture-btn');
  let $editBtn = $('#edit-profile-btn');
  let $updateControls = $('#update-controls');
  let $cancelBtn = $('#cancel-edit-btn');

  // Enable edit
  $editBtn.on('click', function () {
    $inputs.prop('disabled', false);
    $select.prop('disabled', false);
    $updateControls.removeClass('d-none');
    $changePhoto.removeAttr('hidden');
  });

  // Cancel edit
  $cancelBtn.on('click', function () {
    $inputs.each(function () {
      this.value = this.defaultValue;
    }).prop('disabled', true);
    $select.each(function () {
      const $el = $(this);
      $el.find('option').each(function () {
        if (this.defaultSelected) {
          $el.val(this.value);
        }
      });
    }).prop('disabled', true);
    $changePhoto.attr('hidden', ' ');
    $updateControls.addClass('d-none');
  });

  // handle submit
  $form.on('submit', function (e) {
    e.preventDefault();

    $.confirm({
      title: 'Confirm Update',
      content: 'Are you sure you want to save these changes? They will be reflected on your account.',
      buttons: {
        cancel: function () { },
        confirm: {
          text: 'Save',
          btnClass: 'btn-primary',
          action: function () {
            const formData = new FormData($form[0]); // include all form fields and file

            $.ajax({
              url: '/admin/update-profile',
              method: 'POST',
              data: formData,
              processData: false,
              contentType: false,
              success: function (res) {
                if (res.success) {
                  Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: 'Profile updated successfully!',
                    timer: 2000,
                    showConfirmButton: false,
                    timerProgressBar: true,
                    didClose: () => {
                      location.reload();
                    }
                  });
                } else {
                  Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Error updating profile.',
                    timer: 2000,
                    showConfirmButton: false,
                    timerProgressBar: true
                  });
                }
              },
              error: function () {
                Swal.fire({
                  icon: 'error',
                  title: 'Server Error!',
                  text: 'Something went wrong while saving.',
                  timer: 2000,
                  showConfirmButton: false,
                  timerProgressBar: true
                });
              }
            });
          }
        }
      }
    });
  });


  // Profile picture preview
  $('#change-picture-btn').on('click', () => {
    $('#profile-picture-input').click();
  });

  $('#profile-picture-input').on('change', function () {
    const reader = new FileReader();
    reader.onload = function (e) {
      $('#profile-picture-preview').attr('src', e.target.result);
    };
    reader.readAsDataURL(this.files[0]);

    $("#profile-picture-preview").removeAttr('hidden')
    $(".user-avatar-profile-view").attr('hidden', ' ');
  });

  $('.close-profile-notif').on('click', function () {
    $(this).closest('.row').fadeOut(300, function () {
      $(this).remove();
    });
  });
});



