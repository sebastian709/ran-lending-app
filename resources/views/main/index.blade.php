<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RAN Serenity</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: { colors: { primary: "#76689a", secondary: "#76689a" }, borderRadius: { none: "0px", sm: "4px", DEFAULT: "8px", md: "12px", lg: "16px", xl: "20px", "2xl": "24px", "3xl": "32px", full: "9999px", button: "8px" } },
            },
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
        <a href="#"
            class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-white hover:bg-white/20 transition-all">
            <i class="ri-linkedin-fill ri-lg"></i>
        </a>
    </div>
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-gradient-to-r from-primary to-secondary text-white shadow-md">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <a href="#" class="text-2xl font-['Pacifico'] text-white">RAN Serenity</a>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#"
                        class="text-white hover:text-white/80 font-medium relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-white after:transition-all">Home</a>
                    <a href="#business"
                        class="text-white hover:text-white/80 font-medium relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-white after:transition-all">Business</a>
                    <a href="#About"
                        class="text-white hover:text-white/80 font-medium relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-white after:transition-all">About</a>
                    <a href="#Blog"
                        class="text-white hover:text-white/80 font-medium relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-white after:transition-all">Blog</a>
                    <button
                        class="bg-white text-primary px-5 py-2 rounded-button font-medium hover:bg-white/90 transition-all whitespace-nowrap">Get
                        Started</button>
                </div>
                <button id="menuToggle" class="md:hidden text-white focus:outline-none">
                    <i class="ri-menu-line ri-2x"></i>
                </button>
            </div>
            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden md:hidden mt-4 pb-4">
                <div class="flex flex-col space-y-4">
                    <a href="#" class="text-white hover:text-white/80 font-medium">Home</a>
                    <a href="#business" class="text-white hover:text-white/80 font-medium">Business</a>
                    <a href="#About" class="text-white hover:text-white/80 font-medium">About</a>
                    <a href="#Blog" class="text-white hover:text-white/80 font-medium">Blog</a>
                    <button
                        class="bg-white text-primary px-5 py-2 rounded-button font-medium hover:bg-white/90 transition-all whitespace-nowrap w-full">Get
                        Started</button>
                </div>
            </div>
        </div>
    </nav>
    <!-- Hero Section -->
    <section class="relative min-h-screen">
        <div class="hidden md:grid md:grid-cols-5 h-[calc(100vh)]" id="heroGrid">
            <!-- Lending -->
            <div data-url="/lending" class="group relative overflow-hidden cursor-pointer transition-all duration-500"
                id="lendingHero">
                <div
                    class="absolute inset-0 bg-[url('https://readdy.ai/api/search-image?query=modern%20financial%20district%20with%20skyscrapers%20and%20business%20people%2C%20professional%20corporate%20environment%2C%20purple%20lighting%20accents%2C%20elegant%20and%20luxurious%20atmosphere&width=800&height=1200&seq=lending1&orientation=portrait')] bg-cover bg-center transform group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="absolute inset-0 bg-primary/30 group-hover:bg-primary/20 transition-all duration-500"></div>
                <div class="relative h-full flex flex-col justify-end p-8 text-white">
                    <h3 class="text-xl font-bold mb-2">RAN Serenity Lending</h3>
                    <p class="text-sm mb-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500">Flexible
                        financial solutions tailored to your needs</p>
                    <button data-url="/lending"
                        class="bg-white text-primary px-6 py-3 rounded-button font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-500 whitespace-nowrap">Learn
                        More</button>
                </div>
            </div>
            <!-- Jewelry -->
            <div data-url="/jewelry" class="group relative overflow-hidden cursor-pointer transition-all duration-500"
                id="jewelryHero">
                <div
                    class="absolute inset-0 bg-[url('https://readdy.ai/api/search-image?query=luxury%20jewelry%20display%20with%20elegant%20rings%20and%20necklaces%2C%20soft%20purple%20lighting%2C%20premium%20jewelry%20showcase%2C%20high-end%20retail%20environment&width=800&height=1200&seq=jewelry1&orientation=portrait')] bg-cover bg-center transform group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="absolute inset-0 bg-primary/30 group-hover:bg-primary/20 transition-all duration-500"></div>
                <div class="relative h-full flex flex-col justify-end p-8 text-white">
                    <h3 class="text-xl font-bold mb-2">RAN Serenity Jewelry</h3>
                    <p class="text-sm mb-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500">Adorn
                        yourself with treasures that last a lifetime.</p>
                    <button data-url="/jewelry"
                        class="bg-white text-primary px-6 py-3 rounded-button font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-500 whitespace-nowrap">Learn
                        More</button>
                </div>
            </div>
            <!-- Travel -->
            <div data-url="/travel-and-tours"
                class="group relative overflow-hidden cursor-pointer transition-all duration-500" id="travelHero">
                <div
                    class="absolute inset-0 bg-[url('https://readdy.ai/api/search-image?query=luxury%20travel%20destination%20with%20scenic%20beach%20resort%2C%20purple%20sunset%2C%20exclusive%20vacation%20experience%2C%20high-end%20travel%20photography&width=800&height=1200&seq=travel1&orientation=portrait')] bg-cover bg-center transform group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="absolute inset-0 bg-primary/30 group-hover:bg-primary/20 transition-all duration-500"></div>
                <div class="relative h-full flex flex-col justify-end p-8 text-white">
                    <h3 class="text-xl font-bold mb-2">RAN Serenity Travel & Tours</h3>
                    <p class="text-sm mb-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        Unforgettable journeys to dream destinations</p>
                    <button data-url="/travel-and-tours"
                        class="bg-white text-primary px-6 py-3 rounded-button font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-500 whitespace-nowrap">Learn
                        More</button>
                </div>
            </div>
            <!-- Hub -->
            <div data-url="/hub" class="group relative overflow-hidden cursor-pointer transition-all duration-500"
                id="charityHero">
                <div
                    class="absolute inset-0 bg-[url('https://readdy.ai/api/search-image?query=charitable%20community%20service%20event%2C%20diverse%20group%20of%20volunteers%20helping%20others%2C%20warm%20purple%20lighting%2C%20inspiring%20humanitarian%20photography&width=800&height=1200&seq=charity1&orientation=portrait')] bg-cover bg-center transform group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="absolute inset-0 bg-primary/30 group-hover:bg-primary/20 transition-all duration-500"></div>
                <div class="relative h-full flex flex-col justify-end p-8 text-white">
                    <h3 class="text-xl font-bold mb-2">RAN Serenity Hub</h3>
                    <p class="text-sm mb-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500">Making a
                        difference in communities worldwide</p>
                    <button data-url="/hub"
                        class="bg-white text-primary px-6 py-3 rounded-button font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-500 whitespace-nowrap">Learn
                        More</button>
                </div>
            </div>
            <!-- Shop -->
            <div class="group relative overflow-hidden cursor-pointer transition-all duration-500" id="charityHero">
                <div
                    class="absolute inset-0 bg-[url('https://media.istockphoto.com/id/1361840527/photo/clothing-store-with-clothes-shoes-other-personal-accessories-and-neon-lights.jpg?s=612x612&w=0&k=20&c=oUg05zuEHG8eVFx4VtyX8Chhf90Ua1cWi6C1U46oluM=')] bg-cover bg-center transform group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="absolute inset-0 bg-primary/30 group-hover:bg-primary/20 transition-all duration-500"></div>
                <div class="relative h-full flex flex-col justify-end p-8 text-white">
                    <h3 class="text-xl font-bold mb-2">RAN Serenity Shop</h3>
                    <p class="text-sm mb-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500">Connecting
                        Merchants. Uplifting Communities</p>
                    <button onclick="window.location.href='charity.html'"
                        class="bg-white text-primary px-6 py-3 rounded-button font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-500 whitespace-nowrap">Learn
                        More</button>
                </div>
            </div>
        </div>

        <!-- Mobile Carousel -->
        <div class="md:hidden h-[calc(100vh)]" id="mobileCarousel">
            <div class="relative h-full">
                <div class="carousel-container h-full">
                    <!-- Lending Slide -->
                    <div class="carousel-slide absolute inset-0 opacity-0 transition-opacity duration-500">
                        <div class="relative h-full">
                            <div
                                class="absolute inset-0 bg-[url('https://readdy.ai/api/search-image?query=modern%20financial%20district%20with%20skyscrapers%20and%20business%20people%2C%20professional%20corporate%20environment%2C%20purple%20lighting%20accents%2C%20elegant%20and%20luxurious%20atmosphere&width=800&height=1200&seq=lending1&orientation=portrait')] bg-cover bg-center">
                            </div>
                            <div class="absolute inset-0 bg-primary/40"></div>
                            <div class="relative h-full flex flex-col justify-end p-8 text-white">
                                <h3 class="text-2xl font-bold mb-2">RAN Serenity Lending</h3>
                                <p class="text-sm mb-4">Flexible financial solutions tailored to your needs</p>
                                <button
                                    class="bg-white text-primary px-6 py-3 rounded-button font-medium whitespace-nowrap">Learn
                                    More</button>
                            </div>
                        </div>
                    </div>
                    <!-- Jewelry Slide -->
                    <div class="carousel-slide absolute inset-0 opacity-0 transition-opacity duration-500">
                        <div class="relative h-full">
                            <div
                                class="absolute inset-0 bg-[url('https://readdy.ai/api/search-image?query=luxury%20jewelry%20display%20with%20elegant%20rings%20and%20necklaces%2C%20soft%20purple%20lighting%2C%20premium%20jewelry%20showcase%2C%20high-end%20retail%20environment&width=800&height=1200&seq=jewelry1&orientation=portrait')] bg-cover bg-center">
                            </div>
                            <div class="absolute inset-0 bg-primary/40"></div>
                            <div class="relative h-full flex flex-col justify-end p-8 text-white">
                                <h3 class="text-2xl font-bold mb-2">RAN Serenity Jewelry</h3>
                                <p class="text-sm mb-4">Exquisite pieces for life's special moments</p>
                                <button
                                    class="bg-white text-primary px-6 py-3 rounded-button font-medium whitespace-nowrap">Learn
                                    More</button>
                            </div>
                        </div>
                    </div>
                    <!-- Travel Slide -->
                    <div class="carousel-slide absolute inset-0 opacity-0 transition-opacity duration-500">
                        <div class="relative h-full">
                            <div
                                class="absolute inset-0 bg-[url('https://readdy.ai/api/search-image?query=luxury%20travel%20destination%20with%20scenic%20beach%20resort%2C%20purple%20sunset%2C%20exclusive%20vacation%20experience%2C%20high-end%20travel%20photography&width=800&height=1200&seq=travel1&orientation=portrait')] bg-cover bg-center">
                            </div>
                            <div class="absolute inset-0 bg-primary/40"></div>
                            <div class="relative h-full flex flex-col justify-end p-8 text-white">
                                <h3 class="text-2xl font-bold mb-2">RAN Serenity Travel & Tours</h3>
                                <p class="text-sm mb-4">Unforgettable journeys to dream destinations</p>
                                <button
                                    class="bg-white text-primary px-6 py-3 rounded-button font-medium whitespace-nowrap">Learn
                                    More</button>
                            </div>
                        </div>
                    </div>
                    <!-- Hub Slide -->
                    <div class="carousel-slide absolute inset-0 opacity-0 transition-opacity duration-500">
                        <div class="relative h-full">
                            <div
                                class="absolute inset-0 bg-[url('https://readdy.ai/api/search-image?query=charitable%20community%20service%20event%2C%20diverse%20group%20of%20volunteers%20helping%20others%2C%20warm%20purple%20lighting%2C%20inspiring%20humanitarian%20photography&width=800&height=1200&seq=charity1&orientation=portrait')] bg-cover bg-center">
                            </div>
                            <div class="absolute inset-0 bg-primary/40"></div>
                            <div class="relative h-full flex flex-col justify-end p-8 text-white">
                                <h3 class="text-2xl font-bold mb-2">RAN Serenity Hub</h3>
                                <p class="text-sm mb-4">Making a difference in communities worldwide</p>
                                <button
                                    class="bg-white text-primary px-6 py-3 rounded-button font-medium whitespace-nowrap">Learn
                                    More</button>
                            </div>
                        </div>
                    </div>
                    <!-- Shop Slide -->
                    <div class="carousel-slide absolute inset-0 opacity-0 transition-opacity duration-500">
                        <div class="relative h-full">
                            <div
                                class="absolute inset-0 bg-[url('https://media.istockphoto.com/id/1361840527/photo/clothing-store-with-clothes-shoes-other-personal-accessories-and-neon-lights.jpg?s=612x612&w=0&k=20&c=oUg05zuEHG8eVFx4VtyX8Chhf90Ua1cWi6C1U46oluM=')] bg-cover bg-center">
                            </div>
                            <div class="absolute inset-0 bg-primary/40"></div>
                            <div class="relative h-full flex flex-col justify-end p-8 text-white">
                                <h3 class="text-2xl font-bold mb-2">RAN Serenity Shop</h3>
                                <p class="text-sm mb-4">Connecting Merchants. Uplifting Communities</p>
                                <button
                                    class="bg-white text-primary px-6 py-3 rounded-button font-medium whitespace-nowrap">Learn
                                    More</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Carousel Navigation -->
                <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 z-10">
                    <button class="carousel-dot w-2 h-2 rounded-full bg-white/50" data-index="0"></button>
                    <button class="carousel-dot w-2 h-2 rounded-full bg-white/50" data-index="1"></button>
                    <button class="carousel-dot w-2 h-2 rounded-full bg-white/50" data-index="2"></button>
                    <button class="carousel-dot w-2 h-2 rounded-full bg-white/50" data-index="3"></button>
                    <button class="carousel-dot w-2 h-2 rounded-full bg-white/50" data-index="4"></button>
                </div>
            </div>
        </div>
    </section>
    <!-- Business Sections -->
    <section class="py-20 bg-[#f5efef]" id="business">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Our Business</h2>
                <p class="text-gray-600 max-w-3xl mx-auto">Explore our diverse range of businesses designed to meet your
                    various needs from financial services to travel experiences.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Lending Business -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden card-hover">
                    <div class="p-8">
                        <div
                            class="w-16 h-16 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6">
                            <i class="ri-bank-line ri-2x"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">RAN Serenity Lending</h3>
                        <p class="text-gray-600 mb-6">Access flexible lending solutions tailored to your financial
                            needs. Our competitive rates and personalized service make borrowing simple and accessible.
                        </p>
                        <div class="flex items-center">
                            <button
                                class="bg-primary text-white px-5 py-2 rounded-button font-medium hover:bg-primary/90 transition-all whitespace-nowrap">Learn
                                More</button>
                            <span class="ml-4 text-primary font-medium cursor-pointer flex items-center"> Visit Page <i
                                    class="ri-arrow-right-line ml-1"></i> </span>
                        </div>
                    </div>
                </div>
                <!-- Jewelry Business -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden card-hover">
                    <div class="p-8">
                        <div
                            class="w-16 h-16 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6">
                            <i class="ri-gem-line ri-2x"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">RAN Serenity Jewelry</h3>
                        <p class="text-gray-600 mb-6">Discover our exquisite collection of handcrafted jewelry pieces.
                            From timeless classics to contemporary designs, find the perfect expression of your style.
                        </p>
                        <div class="flex items-center">
                            <button
                                class="bg-primary text-white px-5 py-2 rounded-button font-medium hover:bg-primary/90 transition-all whitespace-nowrap">Browse
                                Collection</button>
                            <span class="ml-4 text-primary font-medium cursor-pointer flex items-center"> Visit Page <i
                                    class="ri-arrow-right-line ml-1"></i> </span>
                        </div>
                    </div>
                </div>
                <!-- Travel & Tours -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden card-hover">
                    <div class="p-8">
                        <div
                            class="w-16 h-16 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6">
                            <i class="ri-plane-line ri-2x"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">RAN Serenity Travel & Tours</h3>
                        <p class="text-gray-600 mb-6">Embark on unforgettable journeys with our curated travel
                            experiences. From exotic destinations to cultural adventures, we create memories that last a
                            lifetime.</p>
                        <div class="flex items-center">
                            <button
                                class="bg-primary text-white px-5 py-2 rounded-button font-medium hover:bg-primary/90 transition-all whitespace-nowrap">Explore
                                Packages</button>
                            <span class="ml-4 text-primary font-medium cursor-pointer flex items-center"> Visit Page <i
                                    class="ri-arrow-right-line ml-1"></i> </span>
                        </div>
                    </div>
                </div>
                <!-- Hub -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden card-hover">
                    <div class="p-8">
                        <div
                            class="w-16 h-16 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6">
                            <i class="ri-heart-line ri-2x"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">RAN Serenity Hub</h3>
                        <p class="text-gray-600 mb-6">Join our mission to make a positive impact on communities in need.
                            Through sustainable programs and direct assistance, we're creating meaningful change
                            together.</p>
                        <div class="flex items-center">
                            <button
                                class="bg-primary text-white px-5 py-2 rounded-button font-medium hover:bg-primary/90 transition-all whitespace-nowrap">Learn
                                More</button>
                            <span class="ml-4 text-primary font-medium cursor-pointer flex items-center"> Visit Page <i
                                    class="ri-arrow-right-line ml-1"></i> </span>
                        </div>
                    </div>
                </div>
                <!-- Shop -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden card-hover">
                    <div class="p-8">
                        <div
                            class="w-16 h-16 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6">
                            <i class="ri-store-line ri-2x"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">RAN Serenity Shop</h3>
                        <p class="text-gray-600 mb-6">Join our mission to make a positive impact on communities in need.
                            Through sustainable programs and direct assistance, we're creating meaningful change
                            together.</p>
                        <div class="flex items-center">
                            <button
                                class="bg-primary text-white px-5 py-2 rounded-button font-medium hover:bg-primary/90 transition-all whitespace-nowrap">Learn
                                More</button>
                            <span class="ml-4 text-primary font-medium cursor-pointer flex items-center"> Shop Now <i
                                    class="ri-arrow-right-line ml-1"></i> </span>
                        </div>
                    </div>
                </div>
                <!-- Quote -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden card-hover">
                    <div class="p-8 flex flex-col justify-center items-center text-center h-full">
                        <div
                            class="w-16 h-16 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6">
                            <i class="ri-double-quotes-l ri-2x"></i>
                        </div>
                        <blockquote class="text-xl italic text-gray-700 mb-4">
                            "Do Everything in Love."
                        </blockquote>
                        <p class="text-sm text-gray-500">Corinthians 16 : 14</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Features Section -->
    <section class="py-20 bg-[#e7e2e2]" id="About">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Why Choose Us</h2>
                <p class="text-gray-600 max-w-3xl mx-auto">Our integrated approach to business allows us to provide
                    comprehensive solutions that enhance your lifestyle and financial well-being.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <div
                        class="w-14 h-14 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6">
                        <i class="ri-shield-check-line ri-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Trusted Experience</h3>
                    <p class="text-gray-600">Our experience has earned the trust of clients across industries—delivering
                        solutions that drive success.</p>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <div
                        class="w-14 h-14 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6">
                        <i class="ri-customer-service-2-line ri-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Dedicated Support</h3>
                    <p class="text-gray-600">Our customer-first approach means you'll always receive personalized
                        attention and support tailored to your specific needs.</p>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <div
                        class="w-14 h-14 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6">
                        <i class="ri-hand-heart-line ri-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Community Impact</h3>
                    <p class="text-gray-600">We believe in giving back to the communities we serve, with a portion of
                        all profits directed toward meaningful charitable initiatives.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Section -->
    <section class="py-20 bg-[#f5efef]" id="Blog">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Latest Blog Posts</h2>
                <p class="text-gray-600 max-w-3xl mx-auto">Explore insights, stories, and updates from our businesses
                    and initiatives.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($posts as $post)
                    <div
                        class="bg-white rounded-lg overflow-hidden shadow-md transition-transform duration-300 hover:-translate-y-1">
                        <div class="overflow-hidden">
                            <img src="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : 'https://via.placeholder.com/800x600?text=No+Image' }}"
                                alt="Blog Image"
                                class="w-full h-48 object-cover transform transition-transform duration-300 hover:scale-110">
                        </div>
                        <div class="p-6">
                            <span
                                class="text-sm text-purple-600 font-semibold uppercase">{{ $post->category ?? 'Uncategorized' }}</span>
                            <span class="text-sm text-gray-500 ml-2">|
                                {{ \Carbon\Carbon::parse($post->created_at)->format('F d, Y') }}
                            </span>
                            <h3 class="text-xl font-bold text-gray-800 mt-2">{{ $post->title }}</h3>
                            <p class="text-gray-600 mt-3">{{ Str::limit($post->excerpt, 120) }}</p>
                            <a href="#" class="inline-block mt-4 text-purple-600 font-semibold hover:underline">Read More
                                →</a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <p class="text-center text-gray-500">No blog posts available.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Social Media Section -->
    <section class="py-20 bg-gradient-to-r from-primary to-secondary text-white" id="Social">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Follow Us on Social Media</h2>
                <p class="text-lg mb-8">Stay connected and updated with our latest news, offerings, and community
                    stories from across our brands.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-2xl mx-auto">
                    <a href="https://www.facebook.com/RANLending" target="_blank"
                        class="bg-white text-primary px-6 py-4 rounded-button font-medium hover:bg-white/90 transition-all block text-center">
                        RAN Lending
                    </a>
                    <a href="https://www.facebook.com/profile.php?id=61555856647935" target="_blank"
                        class="bg-white text-primary px-6 py-4 rounded-button font-medium hover:bg-white/90 transition-all block text-center">
                        RAN Jewelry
                    </a>
                    <a href="https://www.facebook.com/nclicious" target="_blank"
                        class="bg-white text-primary px-6 py-4 rounded-button font-medium hover:bg-white/90 transition-all block text-center">
                        RAN Travel & Tours
                    </a>
                    <a href="https://www.facebook.com/profile.php?id=61567345372877" target="_blank"
                        class="bg-white text-primary px-6 py-4 rounded-button font-medium hover:bg-white/90 transition-all block text-center">
                        RAN Serenity HUB
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-16 pb-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <div>
                    <a href="#" class="text-2xl font-['Pacifico'] text-white mb-4 inline-block">RAN Serenity</a>
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
                        <li><a href="#business" class="text-gray-400 hover:text-white transition-colors">Business</a>
                        </li>
                        <li><a href="#Blog" class="text-gray-400 hover:text-white transition-colors">Blog</a></li>
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
    <script id="carouselControl">
        document.addEventListener("DOMContentLoaded", function () {
            const slides = document.querySelectorAll(".carousel-slide");
            const dots = document.querySelectorAll(".carousel-dot");
            let currentSlide = 0;
            let autoplayInterval;
            function showSlide(index) {
                slides.forEach((slide) => (slide.style.opacity = "0"));
                dots.forEach((dot) => dot.classList.remove("bg-white"));
                dots.forEach((dot) => dot.classList.add("bg-white/50"));

                slides[index].style.opacity = "1";
                dots[index].classList.remove("bg-white/50");
                dots[index].classList.add("bg-white");

                currentSlide = index;
            }
            function nextSlide() {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            }
            function startAutoplay() {
                autoplayInterval = setInterval(nextSlide, 5000);
            }
            function stopAutoplay() {
                clearInterval(autoplayInterval);
            }
            dots.forEach((dot, index) => {
                dot.addEventListener("click", () => {
                    stopAutoplay();
                    showSlide(index);
                    startAutoplay();
                });
            });
            // Touch events for swipe
            let touchStartX = 0;
            let touchEndX = 0;

            const carousel = document.querySelector(".carousel-container");

            carousel.addEventListener(
                "touchstart",
                (e) => {
                    touchStartX = e.touches[0].clientX;
                    stopAutoplay();
                },
                false
            );

            carousel.addEventListener(
                "touchend",
                (e) => {
                    touchEndX = e.changedTouches[0].clientX;
                    handleSwipe();
                    startAutoplay();
                },
                false
            );

            function handleSwipe() {
                const swipeThreshold = 50;
                const difference = touchStartX - touchEndX;

                if (Math.abs(difference) > swipeThreshold) {
                    if (difference > 0) {
                        // Swipe left
                        currentSlide = (currentSlide + 1) % slides.length;
                    } else {
                        // Swipe right
                        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                    }
                    showSlide(currentSlide);
                }
            }
            // Initialize carousel
            showSlide(0);
            startAutoplay();
        });
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