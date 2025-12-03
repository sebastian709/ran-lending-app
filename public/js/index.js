$(document).ready(function () {
    const currentUrl = window.location.pathname;

    $('.sidebar-nav a').each(function () {
        const url = $(this).data('url');
        if (url && url === currentUrl) {
            $(this).addClass('active');
        }
    });
});

$(document).on('click', '[data-url]', function (e) {
    e.preventDefault();
    const url = $(this).data('url');

    if (url === '/home') {
        $(this).addClass('active')
    }
    window.location.href = url;

    if (url === '/admin/dashboard') {
        window.location.reload();

        window.location.href = url;
    }
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

const slides = document.querySelectorAll('.carousel-slide');
const dots = document.querySelectorAll('.carousel-dot');

function showSlide(index) {
    slides.forEach((slide, i) => {
        if (i === index) {
            slide.classList.remove('opacity-0', 'pointer-events-none');
            slide.classList.add('opacity-100', 'pointer-events-auto', 'z-10');
        } else {
            slide.classList.remove('opacity-100', 'pointer-events-auto', 'z-10');
            slide.classList.add('opacity-0', 'pointer-events-none');
        }
    });

    dots.forEach((dot, i) => {
        dot.classList.toggle('bg-white', i === index);
        dot.classList.toggle('bg-white/50', i !== index);
    });
}

dots.forEach((dot) => {
    dot.addEventListener('click', () => {
        const index = parseInt(dot.getAttribute('data-index'));
        showSlide(index);
    });
});
showSlide(0);

$(document).on('click', '.hpReadmoreBP', function (e) {
    e.preventDefault();

    let type = $(this).attr('data-type');
    let id = $(this).attr('data-id');

    // Redirect with GET parameters
    window.location.href = `/blog/view?type=${type}&id=${id}`;
});

const backToTop = document.getElementById("backToTop");

window.addEventListener("scroll", () => {
    if (window.scrollY > 300) {
        backToTop.classList.remove("hidden");
        backToTop.classList.add("opacity-100");
    } else {
        backToTop.classList.add("hidden");
        backToTop.classList.remove("opacity-100");
    }
});

backToTop.addEventListener("click", () => {
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
});