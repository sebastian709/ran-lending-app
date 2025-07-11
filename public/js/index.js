$(document).on('click', '[data-url]', function (e) {
  e.preventDefault();
  const url = $(this).data('url');

  
  window.location.href = url;
});

 document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menuToggle");
    const mobileMenu = document.getElementById("mobileMenu");

    if (!menuToggle || !mobileMenu) return; // ⛔ Skip kung wala sa DOM
    
    menuToggle.addEventListener("click", function () {
        mobileMenu.classList.toggle("hidden");
        const icon = menuToggle.querySelector("i");
        if (icon.classList.contains("ri-menu-line")) {
            icon.classList.remove("ri-menu-line");
            icon.classList.add("ri-close-line");
        } else {
            icon.classList.remove("ri-close-line");
            icon.classList.add("ri-menu-line");
        }
    });
});
