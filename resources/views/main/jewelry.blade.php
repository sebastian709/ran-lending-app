<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RAN Jewelry</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32-ran.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16-ran.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <link rel="stylesheet" href="{{ asset('css/main/landing-shared.css') }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#76689a",
                        secondary: "#76689a"
                    },
                    borderRadius: {
                        none: "0px",
                        sm: "4px",
                        DEFAULT: "8px",
                        md: "12px",
                        lg: "16px",
                        xl: "20px",
                        "2xl": "24px",
                        "3xl": "32px",
                        full: "9999px",
                        button: "8px"
                    }
                },
            }
        };
    </script>
    <style>
        :where([class^="ri-"])::before {
            content: "\f3c2";
        }

        body {
            font-family: "Inter", sans-serif;
        }

        .social-sidebar {
            position: fixed;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            z-index: 100;
        }

        #heroGrid>div {
            transition: all 0.5s ease-in-out;
        }

        #heroGrid>div:hover {
            flex-grow: 2;
        }

        @media (max-width: 768px) {
            #heroGrid {
                height: calc(100vh - 5rem);
            }

            #heroGrid>div {
                height: 25%;
            }

            #heroGrid>div:hover {
                height: 40%;
            }
        }

        .navbar-toggler:focus {
            outline: none;
            box-shadow: none;
        }

        input:focus {
            outline: none;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
        }

        .jewelry-card {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .jewelry-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .jewelry-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            color: white;
            padding: 2rem;
        }

        .sparkle-animation {
            animation: sparkle 2s ease-in-out infinite;
        }

        @keyframes sparkle {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Social Sidebar -->
    <div class="social-sidebar hidden lg:flex flex-col gap-4 bg-primary p-3 rounded-r-lg">
        <a href="#"
            class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-white hover:bg-white/20 transition-all">
            <i class="ri-facebook-fill ri-lg"></i>
        </a>
        <a href="#"
            class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-white hover:bg-white/20 transition-all">
            <i class="ri-instagram-fill ri-lg"></i>
        </a>
        <a href="#"
            class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-white hover:bg-white/20 transition-all">
            <i class="ri-twitter-x-fill ri-lg"></i>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-gradient-to-r from-primary to-secondary text-white shadow-md">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <a href="/" class="text-2xl font-['Pacifico'] text-white">RAN Jewelry</a>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#"
                        class="text-white hover:text-white/80 font-medium relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-white after:transition-all">Home</a>
                    <a href="#about"
                        class="text-white hover:text-white/80 font-medium relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-white after:transition-all">About</a>
                    <a href="#blog"
                        class="text-white hover:text-white/80 font-medium relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-white after:transition-all">Blog</a>
                    <x-landing-auth-control guest-label="Shop Now" />
                </div>
                <button id="menuToggle" class="md:hidden text-white focus:outline-none">
                    <i class="ri-menu-line ri-2x"></i>
                </button>
            </div>
            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden md:hidden mt-4 pb-4">
                <div class="flex flex-col space-y-4">
                    <a href="#" class="text-white hover:text-white/80 font-medium">Home</a>
                    <a href="#about" class="text-white hover:text-white/80 font-medium">About</a>
                    <a href="#Blog" class="text-white hover:text-white/80 font-medium">Blog</a>
                    <x-landing-auth-control guest-label="Shop Now" :mobile="true" />
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-primary to-secondary pt-32 pb-20 overflow-hidden">
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="text-white">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">Adorn yourself with treasures that last a lifetime.
                    </h1>
                    <p class="text-xl mb-8">Discover our stunning collection of handcrafted jewelry pieces that
                        celebrate life's most precious moments with timeless elegance.</p>
                    <div class="flex flex-wrap gap-4">
                        <a href="https://www.facebook.com/profile.php?id=61555856647935&sk=photos_albums"
                            target="_blank" rel="noopener noreferrer"
                            class="inline-block bg-white text-primary px-8 py-3 rounded-button font-medium hover:bg-white/90 transition-all whitespace-nowrap">
                            Browse Collection
                        </a>
                        <!-- <button class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-button font-medium hover:bg-white/10 transition-all whitespace-nowrap">Custom Design</button> -->
                    </div>
                </div>
                <div class="relative">
                    <div class="bg-[url('/placeholder.svg?height=400&width=600')] bg-cover bg-center w-full h-[400px] rounded-lg shadow-xl"
                        style="background-image: url('https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80')">
                    </div>
                    <div class="absolute top-4 right-4 sparkle-animation">
                        <i class="ri-star-fill text-yellow-300 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </section>

    <!-- Jewelry Collections Section -->
    <section class="py-20" id="collections">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Our Collections</h2>
                <p class="text-gray-600 max-w-3xl mx-auto">
                    Explore our carefully curated collections featuring the finest materials and exceptional
                    craftsmanship.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Gold Rings -->
                <div class="jewelry-card bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="relative h-64">
                        <img src="https://images.unsplash.com/photo-1605100804763-247f67b3557e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                            alt="Gold Rings" class="w-full h-full object-cover">
                        <div class="jewelry-overlay">
                            <h3 class="text-xl font-bold mb-2">Gold Rings</h3>
                            <!-- <p class="text-sm opacity-90">Starting from $1,299</p> -->
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">
                            Handcrafted gold rings with exquisite detailing — ideal for engagements, weddings, or
                            personal indulgence.
                        </p>
                        <button
                            class="bg-primary text-white px-6 py-2 rounded-button font-medium hover:bg-primary/90 transition-all whitespace-nowrap w-full">
                            More Details
                        </button>
                    </div>
                </div>

                <!-- Gold Necklaces -->
                <div class="jewelry-card bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="relative h-64">
                        <img src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                            alt="Gold Necklaces" class="w-full h-full object-cover">
                        <div class="jewelry-overlay">
                            <h3 class="text-xl font-bold mb-2">Gold Necklaces</h3>
                            <!-- <p class="text-sm opacity-90">Starting from $899</p> -->
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">
                            Radiant gold necklaces that blend timeless charm with contemporary sophistication — perfect
                            for any occasion.
                        </p>
                        <button
                            class="bg-primary text-white px-6 py-2 rounded-button font-medium hover:bg-primary/90 transition-all whitespace-nowrap w-full">
                            More Details
                        </button>
                    </div>
                </div>

                <!-- Gold Bracelets -->
                <div class="jewelry-card bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="relative h-64">
                        <img src="https://images.unsplash.com/photo-1611652022419-a9419f74343d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                            alt="Gold Bracelets" class="w-full h-full object-cover">
                        <div class="jewelry-overlay">
                            <h3 class="text-xl font-bold mb-2">Gold Bracelets</h3>
                            <!-- <p class="text-sm opacity-90">Starting from $599</p> -->
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">
                            Stylish gold bracelets designed for elegance and everyday wear — a perfect blend of beauty
                            and craftsmanship.
                        </p>
                        <button
                            class="bg-primary text-white px-6 py-2 rounded-button font-medium hover:bg-primary/90 transition-all whitespace-nowrap w-full">
                            More Details
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-20 bg-[#f5efef]" id="about">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Why Choose RAN Jewelry</h2>
                <p class="text-gray-600 max-w-3xl mx-auto">Experience the difference of working with master jewelers who
                    are passionate about creating exceptional pieces.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <div
                        class="w-14 h-14 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6">
                        <i class="ri-award-line ri-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Expertly Curated Jewelry</h3>
                    <p class="text-gray-600">
                        We offer a carefully selected range of high-quality pieces, chosen for their elegance,
                        authenticity, and timeless appeal.
                    </p>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <div
                        class="w-14 h-14 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6">
                        <i class="ri-shield-check-line ri-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Certified Quality</h3>
                    <p class="text-gray-600">All our diamonds and precious stones are certified, ensuring authenticity
                        and the highest quality standards.</p>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <div
                        class="w-14 h-14 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6">
                        <i class="ri-customer-service-2-line ri-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Friendly Customer Support</h3>
                    <p class="text-gray-600">
                        We’re here to assist you with product inquiries, order support, and guidance to help you find
                        the perfect piece.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- Blog Section -->
    <section class="py-20 bg-[#e7e2e2]" id="blog">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Jewelry Stories & Care Tips</h2>
                <p class="text-gray-600 max-w-3xl mx-auto">Discover the latest trends, care tips, and inspiring stories
                    from the world of fine jewelry.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Blog Post  -->
                @forelse ($posts as $post)
                    <div
                        class="bg-gray-50 rounded-lg overflow-hidden shadow-md transition-transform duration-300 hover:-translate-y-1">
                        <div class="overflow-hidden">
                           <img src="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : 'https://via.placeholder.com/800x600?text=No+Image' }}"
                                class="blog-image cursor-pointer w-full h-48 object-cover rounded-lg hover:opacity-90 transition">
                        <div class="p-6">
                            <span
                                class="text-sm text-purple-600 font-semibold uppercase">{{ $post->category ?? 'Uncategorized' }}</span>
                            <span class="text-sm text-gray-500 ml-2">|
                                {{ \Carbon\Carbon::parse($post->created_at)->format('F d, Y') }}</span>
                            <h3 class="text-xl font-bold text-gray-800 mt-2">{{ $post->title }}</h3>
                            <p class="text-gray-600 mt-3">{{ Str::limit($post->excerpt, 120) }}</p>
                            <a href="#" data-type="jewelry" data-id="{{ $post->id }}" class="inline-block mt-4 text-purple-600 font-semibold hover:underline hpReadmoreBP">Read More
                                →</a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4">
                        <p class="text-center text-gray-500">No blog posts available.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-16 pb-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <div>
                    <a href="/" class="text-2xl font-['Pacifico'] text-white mb-4 inline-block">RAN Serenity</a>
                    <p class="text-gray-400 mb-6">Empowering your success through diverse business solutions in lending,
                        jewelry, travel, and charitable initiatives.</p>
                    <div class="flex space-x-4 lg:hidden">
                        <a href="#"
                            class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-white hover:bg-white/20 transition-all">
                            <i class="ri-facebook-fill"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-white hover:bg-white/20 transition-all">
                            <i class="ri-instagram-fill"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-white hover:bg-white/20 transition-all">
                            <i class="ri-twitter-x-fill"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-white hover:bg-white/20 transition-all">
                            <i class="ri-linkedin-fill"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-6">Our Businesses</h3>
                    <ul class="space-y-3">
                        <li><a data-url="/lending" class="text-gray-400 hover:text-white transition-colors cursor-pointer">Ran Serenity
                                Lending</a></li>
                        <li><a data-url="/jewelry" class="text-gray-400 hover:text-white transition-colors cursor-pointer">Ran Serenity
                                Jewelry</a></li>
                        <li><a data-url="/travel-and-tours" class="text-gray-400 hover:text-white transition-colors cursor-pointer">Ran
                                Serenity Travel & Tours</a></li>
                        <li><a data-url="/hub" class="text-gray-400 hover:text-white transition-colors cursor-pointer">Ran Serenity
                                Hub</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-6">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="#About" class="text-gray-400 hover:text-white transition-colors">About Us</a></li>
                        <li><a href="/" class="text-gray-400 hover:text-white transition-colors">Business</a></li>
                        <li><a href="#Social" class="text-gray-400 hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-6">Contact Us</h3>

                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="ri-map-pin-line mr-3 mt-1"></i>
                            <span class="text-gray-400">
                                Olongapo, Philippines
                            </span>
                        </li>
                        <li class="flex items-center">
                            <i class="ri-phone-line mr-3"></i>
                            <span class="text-gray-400">+639691898835</span>
                        </li>
                        <li class="flex items-center">
                            <i class="ri-mail-line mr-3"></i>
                            <span class="text-gray-400">ranserenity77@gmail.com</span>
                        </li>

                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm mb-4 md:mb-0">© 2025 Ran Serenity. All rights reserved.</p>
                    <a href="https://asltechnology.online/" target="_blank"
                        class="text-gray-400 hover:text-white text-sm transition-colors">
                        Website developed by ASL Technology
                    </a>
                    <!-- <div class="flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Privacy Policy</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Terms of Service</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Cookie Policy</a>
                    </div> -->
                </div>
            </div>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
    <script src="{{ asset('js/index.js') }}"></script>
    <script id="navbarScroll">
        document.addEventListener("DOMContentLoaded", function () {
            const navbar = document.querySelector("nav");
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
        });
    </script>
</body>

</html>
