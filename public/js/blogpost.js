

$(window).on('scroll', function () {
  const scrollPos = $(window).scrollTop();

  if (scrollPos > 10) {
    $('.sticky-desktop').addClass('scrolled');
  } else {
    $('.sticky-desktop').removeClass('scrolled');
  }
});

$(document).on('click', '.bp-tab', function (e) {
  e.preventDefault(); // prevent default link behavior if needed
  $('.bp-tab').removeClass('active');  // remove active from all
  $(this).addClass('active');          // add active to clicked one
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

function createBPTagify(){
  const cbpInput = document.querySelector('#tagsInput');

  if(!cbpInput){
    return;
  }
  new Tagify(cbpInput);
}


$(document).on('click', '.btn-cbp-preview', function (e) {
  e.preventDefault();

  const title = $('#blogTitle').val() || 'Untitled Blog';
  const excerpt = $('#blogExcerpt').val() || '';
  const content = editorInstance?.getData?.() || $('#blogContent').val();
  const tags = $('#tagsInput').val() || '';
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



