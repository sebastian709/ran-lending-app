$(document).ready(function () {
    const currentUrl = window.location.pathname;

    $('.sidebar-nav a').each(function () {
        const url = $(this).data('url');
        if (url && url === currentUrl) {
            $(this).addClass('active');
        }
    });
});

const LANDING_THEME_KEY = 'landing_theme';
const SITE_THEME_KEY = 'site_theme';

function ensureThemeFabButton() {
    const hasInlineToggle = document.getElementById('landingThemeToggle') || document.getElementById('landingThemeToggleMobile');
    if (hasInlineToggle || document.getElementById('siteThemeFab')) {
        return;
    }

    const btn = document.createElement('button');
    btn.id = 'siteThemeFab';
    btn.className = 'site-theme-fab';
    btn.type = 'button';
    btn.innerHTML = '<i class="ri-moon-line"></i><span>Dark</span>';
    document.body.appendChild(btn);
}

function applyLandingTheme(theme) {
    const isDark = theme === 'dark';
    document.body.classList.toggle('landing-dark', isDark);
    document.body.classList.toggle('dark-mode', isDark);
    document.body.classList.toggle('site-dark', isDark);

    document.querySelectorAll('#landingThemeToggle, #landingThemeToggleMobile, #siteThemeFab').forEach((btn) => {
        const icon = btn.querySelector('i');
        const label = btn.querySelector('span');
        if (icon) {
            icon.className = isDark ? 'ri-sun-line' : 'ri-moon-line';
        }
        if (label) {
            label.textContent = isDark ? 'Light Mode' : 'Dark Mode';
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const themeEnabled = document.body?.dataset?.themeEnabled === '1';
    if (!themeEnabled) {
        document.querySelectorAll('#landingThemeToggle, #landingThemeToggleMobile, #siteThemeFab').forEach((btn) => btn.remove());
        document.body.classList.remove('landing-dark', 'dark-mode', 'site-dark');
        return;
    }

    ensureThemeFabButton();
    const savedTheme = localStorage.getItem(SITE_THEME_KEY) || localStorage.getItem(LANDING_THEME_KEY) || 'light';
    applyLandingTheme(savedTheme);

    document.querySelectorAll('#landingThemeToggle, #landingThemeToggleMobile, #siteThemeFab').forEach((btn) => {
        btn.addEventListener('click', function () {
            const nextTheme = document.body.classList.contains('site-dark') ? 'light' : 'dark';
            localStorage.setItem(LANDING_THEME_KEY, nextTheme);
            localStorage.setItem(SITE_THEME_KEY, nextTheme);
            applyLandingTheme(nextTheme);
        });
    });
});

document.addEventListener('click', function (event) {
    document.querySelectorAll('.landing-profile-dropdown[open]').forEach((dropdown) => {
        if (!dropdown.contains(event.target)) {
            dropdown.removeAttribute('open');
        }
    });
});

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        document.querySelectorAll('.landing-profile-dropdown[open]').forEach((dropdown) => {
            dropdown.removeAttribute('open');
        });
    }
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
let currentSlide = 0;
let autoplayInterval = null;

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

    currentSlide = index;
}

function stopAutoplay() {
    if (autoplayInterval) {
        clearInterval(autoplayInterval);
        autoplayInterval = null;
    }
}

function startAutoplay() {
    if (!slides.length) {
        return;
    }
    stopAutoplay();
    autoplayInterval = setInterval(function () {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }, 5000);
}

if (dots.length) {
    dots.forEach((dot) => {
        dot.addEventListener('click', () => {
            const index = parseInt(dot.getAttribute('data-index'), 10);
            showSlide(index);
            startAutoplay();
        });
    });
}

if (slides.length && dots.length) {
    showSlide(0);
    startAutoplay();

    const carousel = document.querySelector('.carousel-container');
    if (carousel) {
        let touchStartX = 0;
        let touchEndX = 0;

        carousel.addEventListener('touchstart', function (e) {
            touchStartX = e.touches[0].clientX;
            stopAutoplay();
        }, false);

        carousel.addEventListener('touchend', function (e) {
            touchEndX = e.changedTouches[0].clientX;
            const swipeThreshold = 50;
            const difference = touchStartX - touchEndX;

            if (Math.abs(difference) > swipeThreshold) {
                if (difference > 0) {
                    currentSlide = (currentSlide + 1) % slides.length;
                } else {
                    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                }
                showSlide(currentSlide);
            }

            startAutoplay();
        }, false);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const navbar = document.querySelector("nav");
    if (navbar) {
        window.addEventListener("scroll", function () {
            if (window.scrollY > 50) {
                navbar.classList.add("shadow-lg");
                navbar.classList.add("bg-primary");
                navbar.classList.remove("bg-gradient-to-r");
            } else {
                navbar.classList.remove("shadow-lg");
                navbar.classList.remove("bg-primary");
                navbar.classList.add("bg-gradient-to-r");
            }
        });
    }

    const loanAmount = document.getElementById('loanAmount');
    const loanTerm = document.getElementById('loanTerm');
    const interestRate = document.getElementById('interestRate');
    const calculateBtn = document.getElementById('calculateLoan');
    const monthlyPaymentEl = document.getElementById('monthlyPayment');
    const totalInterestEl = document.getElementById('totalInterest');
    const totalPaymentEl = document.getElementById('totalPayment');

    if (!loanAmount || !loanTerm || !interestRate || !calculateBtn || !monthlyPaymentEl || !totalInterestEl || !totalPaymentEl) {
        return;
    }

    calculateBtn.addEventListener('click', function () {
        const principal = parseFloat(loanAmount.value);
        const term = parseFloat(loanTerm.value) * 12;
        const rate = parseFloat(interestRate.value) / 100 / 12;

        if (isNaN(principal) || isNaN(term) || isNaN(rate)) {
            monthlyPaymentEl.textContent = '$0.00';
            totalInterestEl.textContent = '$0.00';
            totalPaymentEl.textContent = '$0.00';
            return;
        }

        const monthlyPayment = (principal * rate * Math.pow(1 + rate, term)) / (Math.pow(1 + rate, term) - 1);
        const totalPayment = monthlyPayment * term;
        const totalInterest = totalPayment - principal;

        monthlyPaymentEl.textContent = `$${monthlyPayment.toFixed(2)}`;
        totalInterestEl.textContent = `$${totalInterest.toFixed(2)}`;
        totalPaymentEl.textContent = `$${totalPayment.toFixed(2)}`;
    });
});

$(document).on('click', '.hpReadmoreBP', function (e) {
    e.preventDefault();

    let type = $(this).attr('data-type');
    let id = $(this).attr('data-id');

    // Redirect with GET parameters
    window.location.href = `/blog/view?type=${type}&id=${id}`;
});

const backToTop = document.getElementById("backToTop");

if (backToTop) {

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

}

$(document).on('click', '.blog-image', function () {
    const src = $(this).attr('src');

    // build overlay viewer
    const viewer = `
        <div id="imageViewer" class="fixed inset-0 bg-black/90 z-[99999] flex items-center justify-center p-4 cursor-zoom-out">
            <img src="${src}" class="max-w-full max-h-full rounded-lg shadow-xl animate-viewer-in">
        </div>
    `;

    $('body').append(viewer);

    // close when clicking anywhere
    $('#imageViewer').on('click', function () {
        $(this).remove();
    });

    // close when pressing ESC
    $(document).on('keyup.imageViewer', function (e) {
        if (e.key === "Escape") {
            $('#imageViewer').remove();
            $(document).off('keyup.imageViewer');
        }
    });
});
