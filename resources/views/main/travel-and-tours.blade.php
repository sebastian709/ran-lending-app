<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RAN Travel & Tours</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
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

        .destination-card {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .destination-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .destination-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            color: white;
            padding: 2rem;
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
                <a href="/" class="text-2xl font-['Pacifico'] text-white">RAN Travel and Tours</a>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#"
                        class="text-white hover:text-white/80 font-medium relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-white after:transition-all">Home</a>
                    <a href="#about"
                        class="text-white hover:text-white/80 font-medium relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-white after:transition-all">About</a>
                    <a href="#blog"
                        class="text-white hover:text-white/80 font-medium relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-white after:transition-all">Blog</a>
                    <button
                        class="bg-white text-primary px-5 py-2 rounded-button font-medium hover:bg-white/90 transition-all whitespace-nowrap">Book
                        Now</button>
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
                    <button
                        class="bg-white text-primary px-5 py-2 rounded-button font-medium hover:bg-white/90 transition-all whitespace-nowrap w-full">Book
                        Now</button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-primary to-secondary pt-32 pb-20 overflow-hidden">
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="text-white">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">Discover new destinations, Create lasting memories.
                    </h1>
                    <p class="text-xl mb-8">Experience the world's most beautiful places with our expertly crafted
                        travel packages and personalized service.</p>
                    <div class="flex flex-wrap gap-4">
                        <button data-url="https://www.facebook.com/nclicious"
                            class="bg-white text-primary px-8 py-3 rounded-button font-medium hover:bg-white/90 transition-all whitespace-nowrap">Explore
                            Packages</button>
                        <button
                            class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-button font-medium hover:bg-white/10 transition-all whitespace-nowrap hidden">Plan
                            Your Trip</button>
                    </div>
                </div>
                <div class="relative">
                    <div class="bg-[url('/placeholder.svg?height=400&width=600')] bg-cover bg-center w-full h-[400px] rounded-lg shadow-xl"
                        style="background-image: url('https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80')">
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </section>

    <!-- Booking Process -->
    <!-- Booking Process -->
    <section class="py-20 bg-white relative overflow-hidden" id="about">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Simple Booking Process</h2>
                <p class="text-gray-600 max-w-3xl mx-auto">
                    Book your dream vacation in just a few easy steps and start your adventure with confidence.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div
                        class="w-16 h-16 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6 mx-auto">
                        <i class="ri-search-line ri-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">1. Choose Destination</h3>
                    <p class="text-gray-600">Browse our curated destinations and select the perfect trip for your
                        preferences.</p>
                </div>
                <div class="text-center">
                    <div
                        class="w-16 h-16 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6 mx-auto">
                        <i class="ri-calendar-line ri-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">2. Select Dates</h3>
                    <p class="text-gray-600">Pick your preferred travel dates and customize your itinerary.</p>
                </div>
                <div class="text-center">
                    <div
                        class="w-16 h-16 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6 mx-auto">
                        <i class="ri-secure-payment-line ri-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">3. Secure Payment</h3>
                    <p class="text-gray-600">Complete your booking with our secure payment system and flexible options.
                    </p>
                </div>
                <div class="text-center">
                    <div
                        class="w-16 h-16 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6 mx-auto">
                        <i class="ri-flight-takeoff-line ri-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">4. Start Your Journey</h3>
                    <p class="text-gray-600">Receive your travel documents and embark on your unforgettable adventure.
                    </p>
                </div>
            </div>
            <div class="text-center mt-12">
                <button
                    class="bg-gray-400 text-white px-8 py-3 rounded-button font-medium opacity-70 cursor-not-allowed"
                    title="Coming Soon" disabled>
                    Coming Soon
                </button>
            </div>
        </div>

        <!-- Overlay Stamp -->
        <div
            class="absolute inset-0 bg-white/0 z-10 flex flex-col items-center justify-center text-center px-4" style="backdrop-filter: blur(1.5px);">
            <!-- STAMP -->
            <div class="relative inline-block mb-10">
                <!-- text-[#76689A] -->
                <span
                    class="text-5xl md:text-6xl lg:text-7xl font-black text-gray-700 uppercase tracking-widest opacity-90 rotate-[-10deg] block">
                    Coming Soon
                </span>
                <span
                    class="absolute inset-0 border-4 border-dashed border-[#76689A] rounded-full transform scale-110 opacity-20"></span>
            </div>
        </div>
    </section>


    <!-- Why Choose Us -->
    <section class="py-20 bg-[#e7e2e2]">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Why Choose RAN Travel & Tours</h2>
                <p class="text-gray-600 max-w-3xl mx-auto">Experience the difference of traveling with a company that
                    puts your adventure and satisfaction first.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <div
                        class="w-14 h-14 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6">
                        <i class="ri-award-line ri-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Expert Planning</h3>
                    <p class="text-gray-600">Our experienced travel specialists craft personalized itineraries tailored
                        to your interests and budget.</p>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <div
                        class="w-14 h-14 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6">
                        <i class="ri-shield-check-line ri-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Safe & Secure</h3>
                    <p class="text-gray-600">Travel with confidence knowing we prioritize your safety with trusted
                        partners and comprehensive insurance.</p>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <div
                        class="w-14 h-14 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6">
                        <i class="ri-customer-service-2-line ri-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">24/7 Support</h3>
                    <p class="text-gray-600">Our dedicated support team is available around the clock to assist you
                        throughout your journey.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Section -->
    <section class="py-20 bg-[#f5efef]" id="blog">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Travel Stories & Tips</h2>
                <p class="text-gray-600 max-w-3xl mx-auto">Discover inspiring travel stories, helpful tips, and the
                    latest updates from our adventures around the world.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Blog Post  -->
                @forelse ($posts as $post)
                    <div
                        class="bg-gray-50 rounded-lg overflow-hidden shadow-md transition-transform duration-300 hover:-translate-y-1">
                        <div class="overflow-hidden">
                            <img src="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : 'https://via.placeholder.com/800x600?text=No+Image' }}"
                                alt="Blog Image"
                                class="w-full h-48 object-cover transform transition-transform duration-300 hover:scale-110">
                        </div>
                        <div class="p-6">
                            <span
                                class="text-sm text-purple-600 font-semibold uppercase">{{ $post->category ?? 'Uncategorized' }}</span>
                            <span class="text-sm text-gray-500 ml-2">|
                                {{ \Carbon\Carbon::parse($post->created_at)->format('F d, Y') }}</span>
                            <h3 class="text-xl font-bold text-gray-800 mt-2">{{ $post->title }}</h3>
                            <p class="text-gray-600 mt-3">{{ Str::limit($post->excerpt, 120) }}</p>
                            <a href="#" class="inline-block mt-4 text-purple-600 font-semibold hover:underline">Read More
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
                        <li><a data-url="/lending"
                                class="text-gray-400 hover:text-white transition-colors cursor-pointer">Ran Serenity
                                Lending</a></li>
                        <li><a data-url="/jewelry"
                                class="text-gray-400 hover:text-white transition-colors cursor-pointer">Ran Serenity
                                Jewelry</a></li>
                        <li><a data-url="/travel-and-tours"
                                class="text-gray-400 hover:text-white transition-colors cursor-pointer">Ran
                                Serenity Travel & Tours</a></li>
                        <li><a data-url="/hub"
                                class="text-gray-400 hover:text-white transition-colors cursor-pointer">Ran Serenity
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
    </script>
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