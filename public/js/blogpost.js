

$(window).on('scroll', function () {
  const scrollPos = $(window).scrollTop();

  if (scrollPos > 10) {
    $('.sticky-desktop').addClass('scrolled');
  } else {
    $('.sticky-desktop').removeClass('scrolled');
  }
});

function abpReloadListByActiveTab() {
  const currentFilter = $('.bp-tab.active:visible').text().trim().toLowerCase();

  let activeABPtab = currentFilter === 'all posts'
    ? 'all'
    : currentFilter === 'drafts'
    ? 'draft'
    : currentFilter;

  console.log(activeABPtab);
  loadBlogPostList?.(activeABPtab);
}

// ✅ AJAX tab filter
$(document).on('click', '.bp-tab', function (e) {
  e.preventDefault();
  $('.bp-tab').removeClass('active');
  $(this).addClass('active');

  const status = $(this).text().trim().toLowerCase();
  const filterStatus = status === 'all posts' ? 'all' : status === 'drafts' ? 'draft' : status;

  loadBlogPostList(filterStatus);
});


function initBlogImageUpload() {
  const dropZone = document.getElementById('dropZone');
  const fileInput = document.getElementById('featuredImageInput');
  const previewContainer = document.getElementById('previewContainer');
  const previewImage = document.getElementById('previewImage');
  const removeImageBtn = document.getElementById('removeImageBtn');

  if (!dropZone || !fileInput || !previewContainer || !previewImage || !removeImageBtn) {
    return; // Not on the blog post page
  }

  // Prevent double binding
  dropZone.replaceWith(dropZone.cloneNode(true));
  fileInput.replaceWith(fileInput.cloneNode(true));
  removeImageBtn.replaceWith(removeImageBtn.cloneNode(true));

  // Re-grab new elements
  const newDropZone = document.getElementById('dropZone');
  const newFileInput = document.getElementById('featuredImageInput');
  const newRemoveImageBtn = document.getElementById('removeImageBtn');

  newDropZone.addEventListener('click', () => newFileInput.click());
  newFileInput.addEventListener('change', handleFile);
  newDropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    newDropZone.classList.add('dragover');
  });
  newDropZone.addEventListener('dragleave', () => {
    newDropZone.classList.remove('dragover');
  });
  newDropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    newDropZone.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
      newFileInput.files = e.dataTransfer.files;
      handleFile();
    }
  });

  newRemoveImageBtn.addEventListener('click', () => {
    newFileInput.value = '';
    previewContainer.classList.add('d-none');
    previewImage.src = '#';
    newDropZone.classList.remove('d-none');
  });

  function handleFile() {
    const file = newFileInput.files[0];
    if (file && file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = (e) => {
        previewImage.src = e.target.result;
        previewContainer.classList.remove('d-none');
        newDropZone.classList.add('d-none');
      };
      reader.readAsDataURL(file);
    }
  }
}




$(document).on('click', '.btn-cbp-preview', function (e) {
  e.preventDefault();

  const title = $('#blogTitle').val() || 'Untitled Blog';
  const excerpt = $('#blogExcerpt').val() || '';
  const content = editorInstance?.getData?.() || $('#blogContent').val();
  let tagList = '';
  const rawTags = $('#tagsInput').val();

  try {
    const parsedTags = JSON.parse(rawTags);
    tagList = parsedTags
      .map(tag => `<span class="badge bg-primary text-white me-1">${tag.value}</span>`)
      .join('');
  } catch (e) {
    tagList = (rawTags || '')
      .split(',')
      .map(tag => `<span class="badge bg-primary text-white me-1">${tag.trim()}</span>`)
      .join('');
  }


  const selectedCategory = $('input[name="category"]:checked').closest('label');
  const categoryIcon = selectedCategory.find('i').attr('class') || '';
  const categoryLabel = selectedCategory.find('span').text() || 'Uncategorized';

  const featuredImage = $('#previewImage').attr('src');
  const hasImage = featuredImage && featuredImage !== '#';

  const categoryColorMap = {
    'Jewelry': '#ffe5ec',
    'Travel and Tours': '#e0f7fa',
    'Charity': '#f3e5f5',
    'Shops': '#fff3cd',
  };

  const bgColor = categoryColorMap[categoryLabel] || '#f8f9fa';

  const previewHTML = `<div class="cbp-preview-wrapper">
                      <!-- Header -->
                      <div class="cbp-preview-header text-center mb-4">
                        <h2 class="cbp-preview-title text-primary">${title}</h2>
                        <p class="cbp-preview-excerpt text-muted">${excerpt}</p>
                      </div>

                      <!-- Featured Image -->
                      ${hasImage ? `
                        <div class="cbp-preview-image mb-4 text-center">
                          <img src="${featuredImage}" class="img-fluid rounded shadow-sm border" style="max-height: 250px; object-fit: cover;">
                        </div>
                      ` : ''}

                      <!-- Category -->
                      <div class="cbp-preview-category card shadow-sm mb-4 border-0" style="background-color: ${bgColor};">
                        <div class="card-body d-flex align-items-center">
                          <i class="${categoryIcon} text-primary fs-4 me-3"></i>
                          <div>
                            <div class="text-uppercase small text-muted fw-bold">Category</div>
                            <div class="fw-semibold fs-5 text-dark">${categoryLabel}</div>
                          </div>
                        </div>
                      </div>

                      <!-- Tags -->
                      <div class="cbp-preview-tags mb-4">
                        <div class="text-uppercase small text-muted fw-bold mb-2"><i class="bi bi-tags-fill me-2 text-primary"></i>Tags</div>
                        <div class="d-flex flex-wrap gap-2">
                          ${tagList || '<span class="text-muted fst-italic">No tags added</span>'}
                        </div>
                      </div>

                      <!-- Content -->
                      <hr class="my-4">
                      <div class="cbp-preview-content">
                        ${content || '<p class="text-muted fst-italic">No content written.</p>'}
                      </div>
                    </div>
                  `;


  $.confirm({
    title: `<i class="bi bi-eye-fill me-2"></i> Blog Preview`,
    content: previewHTML,
    columnClass: 'lg',
    boxWidth: '90%',
    useBootstrap: false,
    buttons: {
      close: {
        text: 'Close',
        btnClass: 'btn btn-secondary'
      }
    }
  });
});

$(document).on('click', '.btn-cbp-publish', function (e) {
  e.preventDefault();

  const status = $('input[name="status"]:checked').val();
  const title = $('#blogTitle').val();
  const excerpt = $('#blogExcerpt').val() || '';
  const content = editorInstance?.getData?.() || $('#blogContent').val();
  const rawTags = $('#tagsInput').val();

  let tags = [];
  try {
    const parsedTags = JSON.parse(rawTags);
    tags = parsedTags.map(tag => tag.value);
  } catch (e) {
    tags = (rawTags || '').split(',').map(tag => tag.trim());
  }

  const selectedCategory = $('input[name="category"]:checked').closest('label');
  const category = selectedCategory.find('span').text();

  const fileInput = document.getElementById('featuredImageInput');
  const file = fileInput?.files[0] || null;

  // Use FormData to send file
  const formData = new FormData();
  formData.append('status', status);
  formData.append('title', title);
  formData.append('excerpt', excerpt);
  formData.append('content', content);
  formData.append('tags', JSON.stringify(tags));
  formData.append('category', category);

  if (file) {
    formData.append('featured_image', file);
  }

  $.ajax({
    url: '/admin/blogpost/store',
    method: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function (response) {
      Swal.fire({
        icon: 'success',
        title: 'Published!',
        text: 'Your blog post has been saved successfully.',
        timer: 2000,
        showConfirmButton: false,
        timerProgressBar: true,
        didClose: () => {
          $(`[data-url="/admin/blogpost"]`).trigger('click');
        }
      });
    },
    error: function (xhr) {
      let msg = 'Something went wrong.';
      if (xhr.responseJSON && xhr.responseJSON.message) {
        msg = xhr.responseJSON.message;
      }
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: msg,
        timer: 1500,
        showConfirmButton: false,
        timerProgressBar: true
      });
    }
  });
});


$(document).on('click', '.btn-bpl-view', function (e) {
  e.preventDefault();
  const postId = $(this).data('bp_id');

  $.get(`/admin/blogpost/view/${postId}`, function (data) {
    const {
      title = 'Untitled',
      excerpt = '',
      content = '',
      tags = [],
      category = 'Uncategorized',
      featured_image = null,
    } = data;

    const tagList = (Array.isArray(tags) ? tags : []).map(tag => `
      <span class="badge vbp-badge-tag">${tag.value}</span>
    `).join('') || '<span class="text-muted fst-italic">No tags added</span>';

    const categoryIconMap = {
      'jewelry': ['bi-gem', '#ffe5ec'],
      'travel and tours': ['bi-airplane-engines', '#e0f7fa'],
      'charity': ['bi-heart-fill', '#f3e5f5'],
      'shops': ['bi-shop', '#fff3cd'],
    };

    const key = category.toLowerCase();
    const [icon, bgColor] = categoryIconMap[key] || ['bi-folder-fill', '#f8f9fa'];

    const previewHTML = `
      <div class="vbp-preview-wrapper">
        <!-- Header -->
        <div class="vbp-header text-center mb-4">
          <h2 class="vbp-title text-gradient">${title}</h2>
          <p class="vbp-subtitle text-muted">${excerpt}</p>
        </div>

        <!-- Featured Image -->
        ${featured_image ? `
          <div class="vbp-image text-center mb-4">
            <img src="${featured_image}" class="img-fluid rounded border shadow-sm" style="max-height: 250px; object-fit: cover;">
          </div>
        ` : ''}

        <!-- Category -->
        <div class="vbp-category card border-0 shadow-sm mb-4" style="background-color: ${bgColor};">
          <div class="card-body d-flex align-items-center">
            <i class="bi ${icon} fs-4 text-primary me-3"></i>
            <div>
              <div class="text-uppercase small fw-bold text-muted">Category</div>
              <div class="fw-semibold fs-5 text-dark">${category}</div>
            </div>
          </div>
        </div>

        <!-- Tags -->
        <div class="vbp-tags mb-4">
          <div class="text-uppercase small fw-bold text-muted mb-2">
            <i class="bi bi-tags-fill me-2 text-primary"></i> Tags
          </div>
          <div class="d-flex flex-wrap gap-2">
            ${tagList}
          </div>
        </div>

        <hr class="my-4">

        <!-- Content -->
        <div class="vbp-content">
          ${content || '<p class="text-muted fst-italic">No content written.</p>'}
        </div>
      </div>
    `;

    $.confirm({
      title: `<i class="bi bi-eye-fill me-2"></i> <span class="text-gradient">Blog Preview</span>`,
      content: previewHTML,
      columnClass: 'lg',
      boxWidth: '90%',
      useBootstrap: false,
      buttons: {
        close: {
          text: 'Close',
          btnClass: 'btn btn-secondary'
        }
      }
    });
  });
});



$(document).on('click', '.btn-bpl-edit', function (e) {
  e.preventDefault();
  const postId = $(this).data('bp_id');

  $.get(`/admin/blogpost/fetch/${postId}`, function (data) {
    const {
      id, title, excerpt, content,
      status, category, tags,
      featured_image
    } = data;

    const tagValue = JSON.stringify(tags || []);

    const formHTML = `
      <form id="editBlogForm" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="${id}">
        <div class="ubp-container container-fluid">
          <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-8">
              <div class="card ubp-card shadow">
                <div class="card-body">
                  <div class="mb-4">
                    <label class="form-label fw-bold text-primary">Blog Title</label>
                    <input type="text" id="blogTitle" name="title" class="form-control ubp-input" value="${title}">
                  </div>

                  <div class="mb-4">
                    <label class="form-label fw-bold text-primary">Short Description</label>
                    <textarea id="blogExcerpt" name="excerpt" rows="2" class="form-control ubp-input">${excerpt}</textarea>
                  </div>

                  <div class="mb-3">
                    <label class="form-label fw-bold text-primary">Content</label>
                    <textarea id="blogContent" name="content" rows="10" class="form-control ubp-editor-area">${content}</textarea>
                  </div>
                </div>
              </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
              <div class="card ubp-card shadow">
                <div class="card-body">
                  <!-- Status -->
                  <div class="mb-4">
                    <label class="form-label fw-bold text-primary">Status</label>
                    <div class="d-flex flex-wrap gap-3">
                      ${['draft', 'published'].map(s => `
                        <label class="form-check d-flex align-items-center gap-2 ubp-radio">
                          <input class="form-check-input" type="radio" name="status" value="${s}" ${s === status ? 'checked' : ''}>
                          <span>${s.charAt(0).toUpperCase() + s.slice(1)}</span>
                        </label>
                      `).join('')}
                    </div>
                  </div>

                  <!-- Category -->
                  <div class="mb-4">
                    <label class="form-label fw-bold text-primary">Category</label>
                    <div class="d-flex flex-column gap-2">
                      ${['jewelry', 'travel and tours', 'charity', 'shops'].map(cat => `
                        <label class="form-check d-flex align-items-center gap-2 ubp-radio">
                          <input class="form-check-input" type="radio" name="category" value="${cat}" ${cat === (category?.toLowerCase?.() || '') ? 'checked' : ''}>
                          <span>${cat.charAt(0).toUpperCase() + cat.slice(1)}</span>
                        </label>
                      `).join('')}
                    </div>
                  </div>

                  <!-- Tags -->
                  <div class="mb-4">
                    <label class="form-label fw-bold text-primary">Tags</label>
                    <input id="tagsInput" name="tags" class="form-control" value='${tagValue}'>
                  </div>

                  <!-- Featured Image -->
                  <div>
                    <label class="form-label fw-bold text-primary">Featured Image</label>
                    <div class="featured-drop-area text-center border border-dashed rounded p-4 mb-2 ${featured_image ? 'd-none' : ''}" id="dropZone">
                      <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
                      <p class="text-muted mb-1">Drag & drop image or click to upload</p>
                      <p class="text-muted small mb-0">Accepted: JPG, PNG, WEBP (Max: 2MB)</p>
                      <input type="file" id="featuredImageInput" name="featured_image" accept="image/*" hidden>
                    </div>

                    <div id="previewContainer" class="position-relative ${featured_image ? '' : 'd-none'}">
                      <img id="previewImage" src="${featured_image || '#'}" class="img-fluid rounded border shadow-sm w-100 object-fit-cover" style="height: 200px;">
                      <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" id="removeImageBtn" title="Remove Image">
                        <i class="bi bi-x-lg"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    `;

    $.confirm({
      title: `<i class="bi bi-pencil-square me-2"></i> <span class="text-gradient">Edit Blogpost</span>`,
      content: formHTML,
      columnClass: 'xl',
      boxWidth: '90%',
      useBootstrap: false,
      buttons: {
        save: {
          text: '💾 Save Changes',
          btnClass: 'btn ubp-btn-gradient text-white',
          action: function () {
            const $confirmBox = this.$el.closest('.jconfirm');
            const originalZIndex = $confirmBox.css('z-index');
            $confirmBox.css('z-index', 1050);

            Swal.fire({
              title: 'Are you sure?',
              text: 'This will update the blog post.',
              icon: 'question',
              showCancelButton: true,
              confirmButtonText: 'Yes, update it!',
              cancelButtonText: 'Cancel',
              reverseButtons: true,
              customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-secondary'
              },
              buttonsStyling: false
            }).then(result => {

              if (result.isConfirmed) {
                const formEl = document.getElementById('editBlogForm');
                const formData = new FormData(formEl);
                formData.set('content', editorInstance?.getData?.() || '');
                formData.set('tags', $('#tagsInput').val());

                $.ajax({
                  url: '/admin/blogpost/update',
                  method: 'POST',
                  data: formData,
                  processData: false,
                  contentType: false,
                  success: function (res) {
                    Swal.fire({
                      icon: 'success',
                      title: 'Updated!',
                      text: res.message,
                      timer: 1500,
                      showConfirmButton: false
                    });
                    $('.jconfirm').remove();
                    // Optional: Reload blog list
                    // reloadBlogList();
                    $confirmBox.css('z-index', originalZIndex);
                    abpReloadListByActiveTab();
                  },
                  error: function (xhr) {
                    Swal.fire({
                      icon: 'error',
                      title: 'Update failed!',
                      text: xhr.responseJSON?.message || 'Something went wrong. Please check your inputs.',
                      timer: 2000,
                      showConfirmButton: false
                    });
                    $confirmBox.css('z-index', originalZIndex);
                  }
                });
              }
            });

            return false;
          }
        },
        cancel: {
          text: 'Cancel',
          btnClass: 'btn btn-secondary'
        }
      },
      onContentReady: function () {
        initCKEditor();
        createBPTagify();
        initBlogImageUpload();
      }
    });
  });
});

$(document).on('click', '.btn-bp-status', function (e) {
  e.preventDefault();
  const $btn = $(this);
  const postId = $btn.data('bp_id');
  const newStatus = $btn.data('status');
  const capitalized = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
  console.log('test new branch');
  Swal.fire({ 
    title: `Move to ${capitalized}?`,
    text: `Do you want to move this post to ${newStatus}?`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: `Yes, move to ${capitalized}`,
    cancelButtonText: 'Cancel',
    customClass: {
      confirmButton: 'btn btn-primary',
      cancelButton: 'btn btn-secondary'
    },
    buttonsStyling: false
  }).then((result) => {
    if (result.isConfirmed) {
      $.post('/admin/blogpost/update-status', {
        id: postId,
        status: newStatus,
        _token: $('meta[name="csrf-token"]').attr('content')
      }, function (res) {
        Swal.fire({
          icon: 'success',
          title: `Moved to ${capitalized}!`,
          text: res.message,
          timer: 2000,
          timerProgressBar: true,
          showConfirmButton: false,
          didClose: () => {
            abpReloadListByActiveTab();
          }
        });
      }).fail(err => {
        Swal.fire('Error', 'Failed to update status', 'error');
      });
    }
  });
});









