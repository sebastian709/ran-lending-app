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

    if (parseInt(isSidebar) == 1) {
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


// Enable edit
$(document).on('click', '#edit-profile-btn', function () {
  let $form = $('#profile-form');
  $form.find('input').prop('disabled', false);
  $form.find('select').prop('disabled', false);
  $('#update-controls').removeClass('d-none');
  $('#change-picture-btn').removeAttr('hidden');
});

// Cancel edit
$(document).on('click', '#cancel-edit-btn', function () {
  let $form = $('#profile-form');
  $form.find('input').each(function () {
    this.value = this.defaultValue;
  }).prop('disabled', true);

  $form.find('select').each(function () {
    const $el = $(this);
    $el.find('option').each(function () {
      if (this.defaultSelected) {
        $el.val(this.value);
      }
    });
  }).prop('disabled', true);

  $('#change-picture-btn').attr('hidden', ' ');
  $('#update-controls').addClass('d-none');
});

// Form submit
$(document).on('submit', '#profile-form', function (e) {
  e.preventDefault();
  let $form = $(this);

  $.confirm({
    title: 'Confirm Update',
    content: 'Are you sure you want to save these changes? They will be reflected on your account.',
    buttons: {
      cancel: function () { },
      confirm: {
        text: 'Save',
        btnClass: 'btn-primary',
        action: function () {
          const formData = new FormData($form[0]);

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

// Click on "Change Picture"
$(document).on('click', '#change-picture-btn', function () {
  $('#profile-picture-input').click();
});

// Handle picture input change
$(document).on('change', '#profile-picture-input', function () {
  const reader = new FileReader();
  reader.onload = function (e) {
    $('#profile-picture-preview').attr('src', e.target.result);
  };
  reader.readAsDataURL(this.files[0]);

  $('#profile-picture-preview').removeAttr('hidden');
  $('.user-avatar-profile-view').attr('hidden', ' ');
});

// Close profile notifications
$(document).on('click', '.close-profile-notif', function () {
  $(this).closest('.row').fadeOut(300, function () {
    $(this).remove();
  });
});


$(function () {
  // Toggle password visibility
  $(document).on('click', '.toggle-password', function () {
    const targetInput = $($(this).data('target'));
    const icon = $(this).find('i');
    const type = targetInput.attr('type') === 'password' ? 'text' : 'password';
    targetInput.attr('type', type);
    icon.toggleClass('ri-eye-line ri-eye-off-line');
  });

  // Live password validation
  $(document).on('input', '#new_password', function () {
    const val = $(this).val();

    const criteria = {
      length: val.length >= 8,
      upperlower: /[a-z]/.test(val) && /[A-Z]/.test(val),
      number: /\d/.test(val),
      special: /[\W_]/.test(val),
    };

    $('#password-criteria li').each(function () {
      const key = $(this).data('criteria');
      if (criteria[key]) {
        $(this).addClass('valid').find('i')
          .removeClass('ri-checkbox-blank-circle-line')
          .addClass('ri-checkbox-circle-fill');
      } else {
        $(this).removeClass('valid').find('i')
          .removeClass('ri-checkbox-circle-fill')
          .addClass('ri-checkbox-blank-circle-line');
      }
    });
  });

  // Submit handler
  $(document).on('submit', '#change-password-form', function (e) {
    e.preventDefault();

    // Check if all criteria are marked as valid
    const allValid = $('#password-criteria li').length === $('#password-criteria li.valid').length;

    if (!allValid) {
      Swal.fire({
        icon: 'error',
        title: 'Weak Password',
        text: 'Please meet all the password requirements before submitting.',
      });
      return; // ⛔ prevent submit
    }

    const form = $(this);
    const actionUrl = form.data('action');
    const logoutUrl = form.data('logout');

    $.ajax({
      url: actionUrl,
      method: "POST",
      data: form.serialize(),
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      },
      success: function (response) {
        Swal.fire({
          icon: 'success',
          title: 'Password Changed',
          text: response.message,
          confirmButtonText: 'OK'
        }).then(() => {
          if (response.logout) {
            $('#logout-form').submit();
          }
        });
      },
      error: function (xhr) {
        let errorMessage = "Something went wrong.";
        if (xhr.responseJSON && xhr.responseJSON.message) {
          errorMessage = xhr.responseJSON.message;
        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
          const errors = xhr.responseJSON.errors;
          errorMessage = Object.values(errors).map(arr => arr.join(', ')).join('\n');
        }

        Swal.fire({
          icon: 'error',
          title: 'Failed',
          text: errorMessage
        });
      }
    });
  });
});



