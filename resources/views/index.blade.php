<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RAN Lending</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32-ran.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16-ran.png') }}">
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
    <link rel="stylesheet" href="{{ asset('css/main/landing-shared.css') }}">
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
                <a href="/" class="text-2xl font-['Pacifico'] text-white">RAN Lending</a>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#"
                        class="text-white hover:text-white/80 font-medium relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-white after:transition-all">Home</a>
                    <a href="#about"
                        class="text-white hover:text-white/80 font-medium relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-white after:transition-all">About</a>
                    <!-- <a href="#Blog" class="text-white hover:text-white/80 font-medium relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-white after:transition-all">Blog</a> -->
                    <button data-url="/login" type="button"
                        class="bg-white text-primary px-5 py-2 rounded-button font-medium hover:bg-white/90 transition-all whitespace-nowrap">Login</button>
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
                    <!-- <a href="#Blog" class="text-white hover:text-white/80 font-medium">Blog</a> -->
                    <button data-url="/login" type="button" 
                        class="bg-white text-primary px-5 py-2 rounded-button font-medium hover:bg-white/90 transition-all whitespace-nowrap w-full">Login</button>
                </div>
            </div>
        </div>
    </nav>
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-primary to-secondary pt-32 pb-20 overflow-hidden">
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="text-white">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">Experience a Smoother Way to Borrow — With
                        Confidence</h1>
                    <p class="text-xl mb-8">Experience hassle-free loans with peace of mind.</p>
                    <div class="flex flex-wrap gap-4">
                        <button data-url="/login"
                            class="bg-white text-primary px-8 py-3 rounded-button font-medium hover:bg-white/90 transition-all whitespace-nowrap">Apply
                            Now</button>
                        <button data-url="/login"
                            class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-button font-medium hover:bg-white/10 transition-all whitespace-nowrap">Get
                            Started</button>
                    </div>
                </div>
                <div class="relative">
                    <div class="bg-[url('{{ asset('storage/stock_images/phMoney.jpg') }}')] bg-cover bg-center w-full h-[400px] rounded-lg shadow-xl"></div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </section>

    <!-- Loan Types Section -->
    <!-- <section class="py-20">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Explore Our Loan Types</h2>
                    <p class="text-gray-600 max-w-3xl mx-auto">Find the perfect financing solution that matches your specific needs and goals.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition-all">
                        <div class="w-16 h-16 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6">
                            <i class="ri-home-4-line ri-2x"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Business Loans</h3>
                        <p class="text-gray-600 mb-6">Fuel your business growth with our flexible business financing options, competitive rates, and quick approval process.</p>
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-center text-gray-600">
                                <i class="ri-checkbox-circle-line text-primary mr-2"></i>
                                Up to $500,000 funding
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="ri-checkbox-circle-line text-primary mr-2"></i>
                                Competitive interest rates
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="ri-checkbox-circle-line text-primary mr-2"></i>
                                Flexible repayment terms
                            </li>
                        </ul>
                        <button class="bg-primary text-white px-6 py-2 rounded-button font-medium hover:bg-primary/90 transition-all whitespace-nowrap">Learn More</button>
                    </div>
                    <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition-all">
                        <div class="w-16 h-16 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6">
                            <i class="ri-building-2-line ri-2x"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Commercial Real Estate</h3>
                        <p class="text-gray-600 mb-6">Secure your commercial property with our specialized real estate financing solutions designed for property investors.</p>
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-center text-gray-600">
                                <i class="ri-checkbox-circle-line text-primary mr-2"></i>
                                Long-term financing
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="ri-checkbox-circle-line text-primary mr-2"></i>
                                Competitive LTV ratios
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="ri-checkbox-circle-line text-primary mr-2"></i>
                                Customized payment plans
                            </li>
                        </ul>
                        <button class="bg-primary text-white px-6 py-2 rounded-button font-medium hover:bg-primary/90 transition-all whitespace-nowrap">Learn More</button>
                    </div>
                    <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition-all">
                        <div class="w-16 h-16 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6">
                            <i class="ri-funds-line ri-2x"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Equipment Financing</h3>
                        <p class="text-gray-600 mb-6">Get the equipment your business needs with our specialized equipment financing and leasing options.</p>
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-center text-gray-600">
                                <i class="ri-checkbox-circle-line text-primary mr-2"></i>
                                Quick approval process
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="ri-checkbox-circle-line text-primary mr-2"></i>
                                Flexible terms available
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="ri-checkbox-circle-line text-primary mr-2"></i>
                                Competitive rates
                            </li>
                        </ul>
                        <button class="bg-primary text-white px-6 py-2 rounded-button font-medium hover:bg-primary/90 transition-all whitespace-nowrap">Learn More</button>
                    </div>
                </div>
            </div>
        </section> -->
    <!-- Loan Calculator Section -->
    <!-- <section class="py-20 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold text-gray-800 mb-4">Loan Calculator</h2>
                        <p class="text-gray-600">Estimate your monthly payments and total cost of borrowing</p>
                    </div>
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Loan Amount ($)</label>
                                <input type="number" id="loanAmount" class="w-full px-4 py-2 rounded-button border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" placeholder="Enter loan amount" />
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Loan Term (Years)</label>
                                <input type="number" id="loanTerm" class="w-full px-4 py-2 rounded-button border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" placeholder="Enter loan term" />
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Interest Rate (%)</label>
                                <input
                                    type="number"
                                    id="interestRate"
                                    class="w-full px-4 py-2 rounded-button border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                    placeholder="Enter interest rate"
                                />
                            </div>
                            <button id="calculateLoan" class="w-full bg-primary text-white px-6 py-3 rounded-button font-medium hover:bg-primary/90 transition-all whitespace-nowrap">Calculate</button>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-xl font-bold text-gray-800 mb-6">Loan Summary</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Monthly Payment:</span>
                                    <span id="monthlyPayment" class="text-xl font-bold text-gray-800">$0.00</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Total Interest:</span>
                                    <span id="totalInterest" class="text-xl font-bold text-gray-800">$0.00</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Total Payment:</span>
                                    <span id="totalPayment" class="text-xl font-bold text-gray-800">$0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->
    <!-- Application Process -->
    <section class="py-20 bg-white" id="about">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Simple Application Process</h2>
                <p class="text-gray-600 max-w-3xl mx-auto">Get started with our straightforward application process and
                    receive a decision quickly.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div
                        class="w-16 h-16 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6 mx-auto">
                        <i class="ri-file-list-3-line ri-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">1. Apply Online</h3>
                    <p class="text-gray-600">Complete our simple online application form with your basic information.
                    </p>
                </div>
                <div class="text-center">
                    <div
                        class="w-16 h-16 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6 mx-auto">
                        <i class="ri-file-paper-2-line ri-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">2. Submit Documents</h3>
                    <p class="text-gray-600">Provide the required documentation to support your application.</p>
                </div>
                <div class="text-center">
                    <div
                        class="w-16 h-16 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6 mx-auto">
                        <i class="ri-search-line ri-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">3. Review</h3>
                    <p class="text-gray-600">Our team will review your application and provide a quick decision.</p>
                </div>
                <div class="text-center">
                    <div
                        class="w-16 h-16 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6 mx-auto">
                        <i class="ri-bank-card-line ri-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">4. Get Funded</h3>
                    <p class="text-gray-600">Once approved, receive your funds quickly and securely.</p>
                </div>
            </div>
            <div class="text-center mt-12">
                <button data-url="/login"
                    class="bg-primary text-white px-8 py-3 rounded-button font-medium hover:bg-primary/90 transition-all whitespace-nowrap">Start
                    Application</button>
            </div>
        </div>
    </section>
    <!-- Why Choose Us -->
    <section class="py-20 bg-[#e7e2e2]">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Why Choose Our Lending Services</h2>
                <p class="text-gray-600 max-w-3xl mx-auto">Experience the difference of working with a lender who puts
                    your needs first.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <div
                        class="w-14 h-14 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6">
                        <i class="ri-timer-flash-line ri-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Quick Approval</h3>
                    <p class="text-gray-600">Get a decision on your loan application within 24-48 hours with our
                        streamlined process.</p>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <div
                        class="w-14 h-14 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6">
                        <i class="ri-shield-check-line ri-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Competitive Rates</h3>
                    <p class="text-gray-600">Access some of the most competitive interest rates in the market, helping
                        you save money.</p>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <div
                        class="w-14 h-14 flex items-center justify-center bg-primary/10 text-primary rounded-full mb-6">
                        <i class="ri-customer-service-2-line ri-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Expert Support</h3>
                    <p class="text-gray-600">Our experienced lending specialists are here to guide you through every
                        step of the process.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- <section class="py-20 bg-white" id="Blog">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Latest Blog Posts</h2>
                    <p class="text-gray-600 max-w-3xl mx-auto">Discover helpful tips, inspiring stories, and the latest updates on hassle-free lending.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-gray-50 rounded-lg overflow-hidden shadow-md transition-transform duration-300 hover:-translate-y-1">
                        <div class="overflow-hidden">
                            <img src="https://readdy.ai/api/search-image?query=modern%20financial%20district%20with%20skyscrapers%20and%20business%20people%2C%20professional%20corporate%20environment%2C%20purple%20lighting%20accents%2C%20elegant%20and%20luxurious%20atmosphere&width=800&height=1200&seq=lending1&orientation=portrait" alt="Blog Image 1"
                                class="w-full h-48 object-cover transform transition-transform duration-300 hover:scale-110">
                        </div>
                        <div class="p-6">
                            <span class="text-sm text-purple-600 font-semibold uppercase">Lending</span>
                            <span class="text-sm text-gray-500 ml-2">| June 15, 2025</span>
                            <h3 class="text-xl font-bold text-gray-800 mt-2">How Flexible Lending Transformed Local Startups</h3>
                            <p class="text-gray-600 mt-3">Discover how our tailored lending solutions helped small businesses scale with confidence and reduced financial pressure.</p>
                            <a href="#" class="inline-block mt-4 text-purple-600 font-semibold hover:underline">Read More →</a>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->
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

</body>

</html>
