let currentView = window.innerWidth <= 768 ? 'card' : 'list';
$(document).ready(function () {
  let currentPath = window.location.pathname;


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
  updateBreadcrumb(currentPath);

  // ✅ CSRF setup - FIXED closing
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });


  if (currentPath === '/admin/referral-management') {
    updateView(currentView);
    fetchReferralTable(currentPage);
  }
});

$(document).on('click', '[data-url]', function (e) {
  e.preventDefault();
  const url = $(this).data('url');
  const isSidebar = $(this).data('is-sidebar');
  currentPath = window.location.pathname;



  $.get(url, function (data) {
    const content = $(data).find('#content').html(); // ito yung @yield('content')
    $('#content').html(content); // i-inject sa layout
    window.history.pushState({}, '', url);

    $('.sidebar .nav-link').removeClass('active');

    if (parseInt(isSidebar) == 1) {
      $(`[data-url="${url}"]`).addClass('active');
    }

    if (url == "/home") {
      window.location.reload();
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

    if (url === '/admin/referral-management') {
      updateView(currentView);
      fetchReferralTable(currentPage);
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

    // Convert kebab-case, camelCase, PascalCase to Title Case
    const label = segment
      .replace(/-/g, ' ')                     // replace dash with space
      .replace(/([A-Z])/g, ' $1')              // insert space before capital letters
      .replace(/^./, str => str.toUpperCase()) // capitalize first letter
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

$(document).on('keyup', '#admin-search-input', function () {
  const query = $(this).val().toLowerCase();
  const $dropdown = $('#admin-search-suggestions');
  $dropdown.empty().addClass('d-none');

  if (!query) return;

  const matches = [];

  $('.admin_settings_container label, .admin_settings_container small').each(function () {
    const text = $(this).text().trim();
    if (text.toLowerCase().includes(query)) {
      matches.push({
        text,
        element: $(this)
      });
    }
  });

  if (matches.length > 0) {
    matches.forEach(item => {
      const $option = $('<button type="button">') // ← ADD THIS TYPE
        .addClass('dropdown-item text-truncate')
        .text(item.text)
        .on('click', function (e) {
          e.preventDefault(); // ← OPTIONAL SAFE GUARD
          $('html, body, .my-scroll-hidden').animate({
            scrollTop: item.element.offset().top - 100
          }, 500);
          $dropdown.addClass('d-none');
        });

      $dropdown.append($option);
    });
    $dropdown.removeClass('d-none');
  }
});

// On search button click – direct search
$(document).on('click', '#admin-search-btn', function () {
  const query = $('#admin-search-input').val().toLowerCase();

  if (!query) return;

  let found = false;

  $('#admin_settings_form label, #admin_settings_form small').each(function () {
    const $el = $(this);
    if ($el.text().trim().toLowerCase().includes(query)) {
      $('.my-scroll-hidden').animate({
        scrollTop: $el.offset().top - 100
      }, 500);

      found = true;


      return false; // break loop
    }
  });

  if (!found) {
    alert('No matching section found.');
  }
});

// Close suggestions if clicked outside
$(document).on('click', function (e) {
  if (!$(e.target).closest('#admin-search-input, #admin-search-suggestions').length) {
    $('#admin-search-suggestions').addClass('d-none');
  }
});

$(document).on('click', '.a-btn-cancel', function () {
  $(this).attr('hidden', true);
  $(this).closest('.g-btn-container').find('.a-btn-update').removeAttr('hidden');
  $(this).closest('.g-btn-container').find('.a-btn-save').attr('hidden', true);

  let form_id = $(this).closest('form').attr('id');
  let form_obj = $(this).closest('form');

  switch (form_id) {
    case "as-loan-settings":
      form_obj.find('.a-loan-interest').attr('disabled', true);
      break;
    default:
  }
});

$(document).on('click', '.a-btn-update', function () {
  $(this).attr('hidden', true);
  $(this).closest('.g-btn-container').find('.a-btn-save').removeAttr('hidden');
  $(this).closest('.g-btn-container').find('.a-btn-cancel').removeAttr('hidden');

  let form_id = $(this).closest('form').attr('id');
  let form_obj = $(this).closest('form');

  switch (form_id) {
    case "as-loan-settings":
      form_obj.find('.a-loan-interest').removeAttr('disabled');
      break;
    default:
  }
});


$(document).on('click', '.a-btn-save', function () {
  const $btnSave = $(this);
  $btnSave.attr('hidden', true);

  const $container = $btnSave.closest('.g-btn-container');
  $container.find('.a-btn-update').removeAttr('hidden');
  $container.find('.a-btn-cancel').attr('hidden', true);

  const $form = $btnSave.closest('form');
  const form_id = $form.attr('id');

  switch (form_id) {
    case "as-loan-settings":
      const formData = new FormData($form[0]);

      console.log(formData)

      $.ajax({
        url: '/admin/update-loan-settings',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
          if (res.success) {
            toastr.success(res.message || 'Updated successfully!');

            $form.find('.a-loan-interest').attr('disabled', true);
          } else {
            toastr.error(res.message || 'Update failed.');
          }
        },
        error: function (xhr) {
          const response = xhr.responseJSON;
          if (response && response.errors) {
            Object.values(response.errors).forEach(msg => toastr.error(msg));
          } else {
            toastr.error('Something went wrong.');
          }
        }
      });
      break;
  }
});

// referral management

$(document).on('click', '.create-referral-code', function () {

  $.confirm({
    title: '<h4 class="mb-0">Create Referral Code</h4>',
    content: `
            <form id="referralForm" class="form-modern">
                <div class="form-group mb-3">
                    <label class="fw-bold d-flex justify-content-between align-items-center">
                        <span>Code Name <span class="text-danger">*</span></span>
                        <a href="javascript:void(0);" class="generate-referral-code text-primary small">Generate Code</a>
                    </label>
                    <input type="text" name="referral_code" class="form-control" placeholder="Enter code">
                    <div class="invalid-feedback">Enter a code minimum of 3 letters or numbers</div>
                </div>
                <div class="form-group mb-3">
                    <label class="fw-bold">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Optional, max 1000 characters"></textarea>
                    <div class="invalid-feedback">Only letters and numbers allowed (max 1000 characters)</div>
                </div>
                <div class="form-group mb-3">
                    <label class="fw-bold">Availability <span class="text-danger">*</span></label>
                    <input type="text" name="availability" class="form-control datetimepicker" placeholder="mm/dd/yyyy | hh:mm AM/PM">
                    <div class="invalid-feedback">Please select a valid date and time</div>
                </div>
            </form>
        `,
    columnClass: 'medium',
    onContentReady: function () {
      // Date + Time Picker initialization
      $('.datetimepicker').flatpickr({
        enableTime: true,
        dateFormat: "m/d/Y h:i K", // Example: 08/09/2025 03:30 PM
        minuteIncrement: 1,
        time_24hr: false
      });

      // Realtime validation
      $('#referralForm').on('input change', 'input, textarea', function () {
        validateField($(this));
      });
    },
    buttons: {
      cancel: {
        text: 'Cancel',
        btnClass: 'btn-secondary'
      },
      save: {
        text: 'Save',
        btnClass: 'btn-primary',
        action: function () {
          let jc = this;
          let form = $('#referralForm');
          let valid = validateForm(form);

          if (!valid) {
            return false; // stop $.confirm close
          }

          let data = form.serializeArray();
          let availability = form.find('[name="availability"]').val();

          Swal.fire({
            title: "Are you sure?",
            text: `Are you sure you want to save this code until ${availability}?`,
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No"
          }).then((result) => {
            if (result.isConfirmed) {
              $.post('/admin/referral-code/save', data, function (res) {
                Swal.fire({
                  title: "Success!",
                  text: "Referral code saved successfully.",
                  icon: "success",
                  timer: 1000,
                  timerProgressBar: true,
                  showConfirmButton: false,
                  didOpen: () => {
                    Swal.showLoading();
                  }
                });

                fetchReferralTable();
                jc.close();
              }).fail(function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.field) {
                  let fieldName = xhr.responseJSON.field;
                  let fieldMessage = xhr.responseJSON.message;
                  let $field = $(`[name="${fieldName}"]`);

                  $field.addClass('is-invalid').removeClass('is-valid');
                  $field.siblings('.invalid-feedback').text(fieldMessage).show();
                } else {
                  Swal.fire({
                    title: "Error!",
                    text: "Something went wrong while saving.",
                    icon: "error"
                  });
                }
              });
            }
          });

          return false;
        }
      }
    },
    onContentReady: function () {
      $('.datetimepicker').flatpickr({
        enableTime: true,
        dateFormat: "m/d/Y h:i K"
      });

      // Realtime validation
      $('#referralForm').on('input change', 'input, textarea', function () {
        validateField($(this));
      });
      fetchReferralTable();
    }
  });

  function validateForm(form) {
    let allValid = true;
    form.find('input, textarea').each(function () {
      if (!validateField($(this))) {
        allValid = false;
      }
    });
    return allValid;
  }

  function validateField($field) {
    let name = $field.attr('name');
    let value = $field.val().trim();
    let valid = true;
    let message = '';

    // Remove special success-on-error class so validation controls the visual state
    $field.removeClass('input-success-error');

    if (name === 'referral_code') {
      // Empty
      if (value.length === 0) {
        valid = false;
        message = 'Enter a code minimum of 3 letters or numbers';
      }
      // Too short
      else if (value.length < 3) {
        valid = false;
        message = 'Enter a code minimum of 3 letters or numbers';
      }
      // Too long
      else if (value.length > 20) {
        valid = false;
        message = 'Maximum of 20 characters allowed';
      }
      // Contains invalid chars (only allow letters and digits)
      else if (!/^[A-Za-z0-9]+$/.test(value)) {
        valid = false;
        message = 'Only letters and numbers are allowed';
      }
      // Must contain at least one letter AND at least one digit
      else if (!/(?=.*[A-Za-z])/.test(value) || !/(?=.*\d)/.test(value)) {
        valid = false;
        message = 'Code must contain at least one letter and one number';
      } else {
        valid = true;
      }
    }

    if (name === 'description') {
      if (value.length > 1000) {
        valid = false;
        message = 'Maximum of 1000 characters allowed';
      } else if (value.length > 0 && !/^[a-zA-Z0-9\s]*$/.test(value)) {
        valid = false;
        message = 'Description may only contain letters, numbers and spaces';
      } else {
        valid = true;
      }
    }

    if (name === 'availability') {
      if (value.length === 0) {
        valid = false;
        message = 'Please select a valid date and time';
      } else {
        valid = true;
      }
    }

    // Find the correct invalid-feedback element in the same .form-group
    let $feedback = $field.closest('.form-group').find('.invalid-feedback');

    if (!valid) {
      $field.addClass('is-invalid').removeClass('is-valid');
      if ($feedback.length) {
        $feedback.text(message).show();
      }
    } else {
      $field.removeClass('is-invalid').addClass('is-valid');
      if ($feedback.length) {
        $feedback.hide();
      }
    }

    return valid;
  }

});


// Trigger events for search and filter
$(document).on('input', '#searchReferral', function () {
  fetchReferralTable();
});

$(document).on('change', '#filterReferral', function () {
  fetchReferralTable();
});

// Click events for is_active toggle
$(document).on('click', '.code-status', function () {
  let id = $(this).data('id');
  let isActive = $(this).data('active');
  let availability = $(this).data('availability');

  if (isActive == 0) {
    // Inactive confirmation
    Swal.fire({
      title: "This code is expired.",
      text: "Please edit the code details to activate.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Activate",
      cancelButtonText: "Cancel"
    }).then((result) => {
      if (result.isConfirmed) {
        updateCodeStatus(id, 1); // activate
      }
    });
  } else {
    // Active confirmation
    Swal.fire({
      title: "Disable this code?",
      text: `This code is available until ${availability}. Are you sure you want to disable this code?`,
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Deactivate",
      cancelButtonText: "Cancel"
    }).then((result) => {
      if (result.isConfirmed) {
        updateCodeStatus(id, 0); // deactivate
      }
    });
  }
});

// Click event for delete
$(document).on('click', '.delete-code', function () {
  let id = $(this).data('id');
  Swal.fire({
    title: "Are you sure?",
    text: "Are you sure you want to delete this code?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Delete",
    cancelButtonText: "Cancel"
  }).then((result) => {
    if (result.isConfirmed) {
      deleteReferralCode(id);
    }
  });
});

// Fetch table data
let currentPage = 1;
let perPage = 10;

function fetchReferralTable(page = 1) {
  let search = $('#searchReferral').val();
  let filter = $('#filterReferral').val();

  $.get('/admin/referral-code/list', { search, filter, page, per_page: perPage }, function (res) {
    currentPage = res.current_page; // ✅ i-sync sa response
    renderReferralTable(res.data);
    renderPagination(res.total, res.current_page, res.per_page);
  });
}

function renderPagination(total, current, perPage) {
  let totalPages = Math.ceil(total / perPage);
  let $pagination = $('#pagination');
  let $info = $('#tableInfo');

  // Table info
  let start = (current - 1) * perPage + 1;
  let end = Math.min(start + perPage - 1, total);
  $info.text(`${start} - ${end} of ${total} entries`);

  $pagination.empty();

  // Previous
  $pagination.append(`
        <li class="page-item ${current === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${current - 1}">Previous</a>
        </li>
    `);

  // Page numbers logic
  let pageNumbers = [];

  if (totalPages <= 5) {
    for (let i = 1; i <= totalPages; i++) pageNumbers.push(i);
  } else {
    if (current <= 3) {
      pageNumbers = [1, 2, 3, 4, 5, '...', totalPages];
    } else if (current >= totalPages - 2) {
      pageNumbers = [1, '...', totalPages - 4, totalPages - 3, totalPages - 2, totalPages - 1, totalPages];
    } else {
      pageNumbers = [1, '...', current - 1, current, current + 1, '...', totalPages];
    }
  }

  pageNumbers.forEach(num => {
    if (num === '...') {
      $pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
    } else {
      $pagination.append(`
                <li class="page-item ${parseInt(num) === parseInt(current) ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${num}">${num}</a>
                </li>
            `);
    }
  });

  // Next
  $pagination.append(`
        <li class="page-item ${current === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${current + 1}">Next</a>
        </li>
    `);
}

// Pagination click event
$(document).on('click', '#pagination a.page-link', function (e) {
  e.preventDefault();
  let page = parseInt($(this).data('page'));
  if (!isNaN(page) && page > 0) {
    currentPage = page;
    fetchReferralTable(page);
  }
});


// Initial view setup on page load
$(document).ready(function () {
  updateView(currentView);
  fetchReferralTable(currentPage);
});

// View toggle button click handlers
$(document).on('click', '#listViewBtn', function () {
  if (currentView !== 'list') {
    currentView = 'list';
    updateView(currentView);
    fetchReferralTable(currentPage);
  }
});

$(document).on('click', '#cardViewBtn', function () {
  if (currentView !== 'card') {
    currentView = 'card';
    updateView(currentView);
    fetchReferralTable(currentPage);
  }
});

function updateView(view) {
  if (view === 'list') {
    $('#referralTable').show();
    $('#referralCardContainer').hide();
    $('#listViewBtn').addClass('active');
    $('#cardViewBtn').removeClass('active');
  } else {
    $('#referralTable').hide();
    $('#referralCardContainer').show();
    $('#listViewBtn').removeClass('active');
    $('#cardViewBtn').addClass('active');
  }
}


// Modify your renderReferralTable to render cards if card view is active
function renderReferralTable(data) {
  let $tbody = $('#referralTable tbody');
  let $cardContainer = $('#referralCardContainer');

  $tbody.empty();
  $cardContainer.empty();

  if (data.length === 0) {
    let emptyHtml = `
      <tr>
        <td colspan="7" class="text-center py-5">
          <label class="fw-bold">No referral code available</label>
          <p class="mb-4">There is currently no code to display. Click the button below to create.</p>
          <button class="btn btn-sm btn-primary create-referral-code"><i class="ri-coupon-3-line"></i> Create Referral Code</button>
        </td>
      </tr>`;
    $tbody.append(emptyHtml);
    $cardContainer.append(`
      <div class="text-center py-5 w-100">
        <h4>No referral code available</h4>
        <p>There is currently no code to display. Click the button below to create.</p>
        <button class="btn btn-primary create-referral-code"><i class="ri-coupon-3-line"></i> Create Referral Code</button>
      </div>
    `);
    return;
  }

  if (currentView === 'list') {
    data.forEach(row => {
      let statusLabel = row.is_active == 1
        ? `<span class="badge bg-success code-status" style="cursor:pointer" data-id="${row.id}" data-active="1" data-availability="${row.availability}">Active</span>`
        : `<span class="badge bg-secondary code-status" style="cursor:pointer" data-id="${row.id}" data-active="0" data-availability="${row.availability}">Inactive</span>`;

      let tr = `
        <tr>
          <td>${row.referral_code}</td>
          <td>${row.description || ''}</td>
          <td>${row.availability || ''}</td>
          <td>${row.created_at}</td>
          <td>${row.firstname || ''} ${row.lastname || ''}</td>
          <td>${statusLabel}</td>
          <td>
            <button class="btn btn-danger btn-sm delete-code" data-id="${row.id}"><i class="ri-delete-bin-line"></i> Delete</button>
          </td>
        </tr>`;


      $tbody.append(tr);
    });
  } else if (currentView === 'card') {
    // Card view rendering
    data.forEach(row => {
      let statusLabel = row.is_active == 1
        ? `<span class="badge bg-success code-status" style="cursor:pointer" data-id="${row.id}" data-active="1" data-availability="${row.availability}">Active</span>`
        : `<span class="badge bg-secondary code-status" style="cursor:pointer" data-id="${row.id}" data-active="0" data-availability="${row.availability}">Inactive</span>`;

      let card = `<div class="col-12 col-lg-4">
                    <div class="card" data-id="${row.id}">
                      <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                          <h5 class="card-title text-truncate">${row.referral_code}</h5>
                          <div>${statusLabel}</div>
                        </div>
                        <p class="card-text"><strong>Description:</strong> ${row.description || 'N/A'}</p>
                        <p class="card-text"><strong>Availability:</strong> ${row.availability || 'N/A'}</p>
                        <p class="card-text"><strong>Date Created:</strong> ${row.created_at}</p>
                        <p class="card-text mb-3"><strong>Created by:</strong> ${row.firstname || ''} ${row.lastname || ''}</p>
                        <button class="btn btn-outline-danger btn-sm delete-code" data-id="${row.id}">
                          <i class="ri-delete-bin-line me-1"></i> Delete
                        </button>
                      </div>
                    </div>
                  </div>`;

      $('#referralCardContainer').removeClass('d-none').append(card);
    });
  }
}



// Update active/inactive
function updateCodeStatus(id, status) {
  $.post('/admin/referral-code/update-status', { id, status }, function () {
    fetchReferralTable();
  });
}

// Delete referral code
function deleteReferralCode(id) {
  $.post('/admin/referral-code/delete', { id }, function () {
    fetchReferralTable();
  });
}

function generateReadableCode() {
  const uid = new ShortUniqueId({ length: 6, dictionary: 'alpha_upper' });
  let number = Math.floor(10 + Math.random() * 90);
  return uid.rnd() + number; // e.g. 'QWERTY99'
}

$(document).on('click', '.generate-referral-code', function () {
  const code = generateReadableCode();
  $('input[name="referral_code"]').val(code).trigger('input'); // set value + trigger validation
});

