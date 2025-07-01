const input = document.querySelector('#tagsInput');
new Tagify(input);

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

const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('featuredImageInput');
const previewContainer = document.getElementById('previewContainer');
const previewImage = document.getElementById('previewImage');
const removeImageBtn = document.getElementById('removeImageBtn');

// Open file dialog
dropZone.addEventListener('click', () => fileInput.click());

// Handle file selection
fileInput.addEventListener('change', handleFile);

// Handle drag events
dropZone.addEventListener('dragover', (e) => {
  e.preventDefault();
  dropZone.classList.add('dragover');
});

dropZone.addEventListener('dragleave', () => {
  dropZone.classList.remove('dragover');
});

dropZone.addEventListener('drop', (e) => {
  e.preventDefault();
  dropZone.classList.remove('dragover');
  const file = e.dataTransfer.files[0];
  if (file && file.type.startsWith('image/')) {
    fileInput.files = e.dataTransfer.files;
    handleFile();
  }
});

function handleFile() {
  const file = fileInput.files[0];
  if (file && file.type.startsWith('image/')) {
    const reader = new FileReader();
    reader.onload = (e) => {
      previewImage.src = e.target.result;
      previewContainer.classList.remove('d-none');
      dropZone.classList.add('d-none'); // hide drop zone after preview
    };
    reader.readAsDataURL(file);
  }
}

removeImageBtn.addEventListener('click', () => {
  fileInput.value = '';
  previewContainer.classList.add('d-none');
  previewImage.src = '#';
  dropZone.classList.remove('d-none'); // show drop zone again
});


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



