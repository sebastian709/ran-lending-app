<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>RAN Charity</title>
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
            primary: "#6a1b9a",
            secondary: "#9c27b0"
          },
          borderRadius: {
            button: "8px"
          }
        }
      }
    };
  </script>
  <style>
    body {
      font-family: "Inter", sans-serif;
    }
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
        #heroGrid > div {
            transition: all 0.5s ease-in-out;
        }
        #heroGrid > div:hover {
            flex-grow: 2;
        }
        @media (max-width: 768px) {
            #heroGrid {
                height: calc(100vh - 5rem);
            }
            #heroGrid > div {
                height: 25%;
            }
            #heroGrid > div:hover {
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
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .jewelry-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            color: white;
            padding: 2rem;
        }
        .sparkle-animation {
            animation: sparkle 2s ease-in-out infinite;
        }
        @keyframes sparkle {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
  </style>
</head>
<body class="bg-gray-50">

  <!-- Social Sidebar -->
    <div class="social-sidebar hidden lg:flex flex-col gap-4 bg-primary p-3 rounded-r-lg">
        <a href="#" class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-white hover:bg-white/20 transition-all">
            <i class="ri-facebook-fill ri-lg"></i>
        </a>
        <a href="#" class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-white hover:bg-white/20 transition-all">
            <i class="ri-instagram-fill ri-lg"></i>
        </a>
        <a href="#" class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-white hover:bg-white/20 transition-all">
            <i class="ri-twitter-x-fill ri-lg"></i>
        </a>
    </div>

     <!-- Navigation -->
     <nav class="fixed top-0 left-0 right-0 z-50 bg-gradient-to-r from-primary to-secondary text-white shadow-md">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <a href="#" class="text-2xl font-['Pacifico'] text-white">RAN Serenity HUB</a>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#" class="text-white hover:text-white/80 font-medium relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-white after:transition-all">Home</a>
                    <a href="#about" class="text-white hover:text-white/80 font-medium relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-white after:transition-all">About</a>
                    <a href="#blog" class="text-white hover:text-white/80 font-medium relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-white after:transition-all">Blog</a>
                    <button class="bg-white text-primary px-5 py-2 rounded-button font-medium hover:bg-white/90 transition-all whitespace-nowrap">Support Us</button>
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
                    <button class="bg-white text-primary px-5 py-2 rounded-button font-medium hover:bg-white/90 transition-all whitespace-nowrap w-full">Support Us</button>
                </div>
            </div>
        </div>
    </nav>

 <!-- Hero with Video Background -->
  <section class="relative h-[80vh] flex items-center justify-center text-white overflow-hidden">
    <!-- Video Background -->
    <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover z-0 scale-[1.5]">
      <source src="img/charity.mp4" type="video/mp4" />
      Your browser does not support the video tag.
    </video>

    <!-- Overlay Gradient -->
    <div class="absolute inset-0 bg-gradient-to-r from-primary/80 to-secondary/80 z-10"></div>

    <!-- Hero Content -->
    <div class="relative z-20 text-center px-4">
      <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
        Empowering Communities Through Compassion
      </h1>
      <p class="text-xl md:text-2xl mb-8 max-w-2xl mx-auto">
        Join us in making a difference — through feeding programs, education, and outreach initiatives across the Philippines.
      </p>
      <a href="#initiatives" class="bg-white text-primary px-8 py-3 rounded-button font-medium hover:bg-white/90 transition-all">
        Explore Initiatives
      </a>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="py-20 bg-white">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Why Support RAN Charity?</h2>
        <p class="text-gray-600 max-w-3xl mx-auto">We believe in sustainable impact, transparent giving, and long-term community transformation.</p>
      </div>
      <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-lg shadow-md text-center">
          <i class="ri-community-line text-4xl text-primary mb-4"></i>
          <h3 class="text-xl font-bold text-gray-800 mb-3">Grassroots Focus</h3>
          <p class="text-gray-600">Our work starts where it’s needed most — in the barangays, with real people and local leaders.</p>
        </div>
        <div class="bg-white p-8 rounded-lg shadow-md text-center">
          <i class="ri-hand-heart-line text-4xl text-primary mb-4"></i>
          <h3 class="text-xl font-bold text-gray-800 mb-3">Volunteer-Driven</h3>
          <p class="text-gray-600">From packing goods to organizing events, our passionate volunteers are the heartbeat of every mission.</p>
        </div>
        <div class="bg-white p-8 rounded-lg shadow-md text-center">
          <i class="ri-gift-line text-4xl text-primary mb-4"></i>
          <h3 class="text-xl font-bold text-gray-800 mb-3">Direct Donations</h3>
          <p class="text-gray-600">100% of donations go straight to those in need. No hidden admin fees — just real impact.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- RAN Serenity HUB - Core Values Section -->
  <section class="py-20 bg-gradient-to-r from-pink-100 to-purple-100">
    <div class="container mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
          CONNECTING COMMUNITIES, CONNECTING GOD
        </h2>
        <p class="text-gray-700 max-w-2xl mx-auto">
          At RAN Serenity HUB, we are committed to making a meaningful difference through our values:
        </p>
      </div>

      <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <!-- Card: Compassion -->
        <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-pink-400 hover:shadow-lg transition-all">
          <h3 class="text-lg font-bold text-pink-600 mb-2">Compassion</h3>
          <p class="text-gray-700">
            Embracing empathy and kindness in all our actions.
          </p>
        </div>

        <!-- Card: Empowerment -->
        <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-purple-400 hover:shadow-lg transition-all">
          <h3 class="text-lg font-bold text-purple-600 mb-2">Empowerment</h3>
          <p class="text-gray-700">
            Uplifting individuals to achieve their full potential.
          </p>
        </div>

        <!-- Card: Integrity -->
        <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-yellow-400 hover:shadow-lg transition-all">
          <h3 class="text-lg font-bold text-yellow-600 mb-2">Integrity</h3>
          <p class="text-gray-700">
            Maintaining honesty and transparency in our efforts.
          </p>
        </div>

        <!-- Card: Community -->
        <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-indigo-400 hover:shadow-lg transition-all">
          <h3 class="text-lg font-bold text-indigo-600 mb-2">Community</h3>
          <p class="text-gray-700">
            Fostering a supportive and inclusive environment.
          </p>
        </div>

        <!-- Card: Growth -->
        <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-green-400 hover:shadow-lg transition-all">
          <h3 class="text-lg font-bold text-green-600 mb-2">Growth</h3>
          <p class="text-gray-700">
            Encouraging continuous learning and personal development.
          </p>
        </div>
      </div>
    </div>
  </section>

   <!-- Blog Section -->
  <section class="py-20 bg-white" id="Blog">
      <div class="container mx-auto px-4">
          <div class="text-center mb-16">
              <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Latest Blog Posts</h2>
              <p class="text-gray-600 max-w-3xl mx-auto">Explore insights, stories, and updates from our businesses and initiatives.</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
              <!-- Blog Post 1 -->
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

              <!-- Blog Post 2 -->
              <div class="bg-gray-50 rounded-lg overflow-hidden shadow-md transition-transform duration-300 hover:-translate-y-1">
                  <div class="overflow-hidden">
                      <img src="https://readdy.ai/api/search-image?query=luxury%20jewelry%20display%20with%20elegant%20rings%20and%20necklaces%2C%20soft%20purple%20lighting%2C%20premium%20jewelry%20showcase%2C%20high-end%20retail%20environment&width=800&height=1200&seq=jewelry1&orientation=portrait" alt="Blog Image 2"
                          class="w-full h-48 object-cover transform transition-transform duration-300 hover:scale-110">
                  </div>
                  <div class="p-6">
                      <span class="text-sm text-purple-600 font-semibold uppercase">Jewelry</span>
                      <span class="text-sm text-gray-500 ml-2">| May 28, 2025</span>
                      <h3 class="text-xl font-bold text-gray-800 mt-2">The Art of Crafting Meaningful Jewelry Pieces</h3>
                      <p class="text-gray-600 mt-3">A behind-the-scenes look at how our expert artisans create stunning, meaningful jewelry for every occasion.</p>
                      <a href="#" class="inline-block mt-4 text-purple-600 font-semibold hover:underline">Read More →</a>
                  </div>
              </div>

              <!-- Blog Post 3 -->
              <div class="bg-gray-50 rounded-lg overflow-hidden shadow-md transition-transform duration-300 hover:-translate-y-1">
                  <div class="overflow-hidden">
                      <img src="https://readdy.ai/api/search-image?query=luxury%20travel%20destination%20with%20scenic%20beach%20resort%2C%20purple%20sunset%2C%20exclusive%20vacation%20experience%2C%20high-end%20travel%20photography&width=800&height=1200&seq=travel1&orientation=portrait" alt="Blog Image 3"
                          class="w-full h-48 object-cover transform transition-transform duration-300 hover:scale-110">
                  </div>
                  <div class="p-6">
                      <span class="text-sm text-purple-600 font-semibold uppercase">Travel & Tours</span>
                      <span class="text-sm text-gray-500 ml-2">| April 10, 2025</span>
                      <h3 class="text-xl font-bold text-gray-800 mt-2">Top 5 Destinations for a Family Getaway</h3>
                      <p class="text-gray-600 mt-3">From tropical beaches to cultural landmarks, explore the top places to make unforgettable family memories.</p>
                      <a href="#" class="inline-block mt-4 text-purple-600 font-semibold hover:underline">Read More →</a>
                  </div>
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
                    <p class="text-gray-400 mb-6">Empowering your success through diverse business solutions in lending, jewelry, travel, and charitable initiatives.</p>
                    <div class="flex space-x-4 lg:hidden">
                        <a href="#" class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-white hover:bg-white/20 transition-all">
                            <i class="ri-facebook-fill"></i>
                        </a>
                        <a href="#" class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-white hover:bg-white/20 transition-all">
                            <i class="ri-instagram-fill"></i>
                        </a>
                        <a href="#" class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-white hover:bg-white/20 transition-all">
                            <i class="ri-twitter-x-fill"></i>
                        </a>
                        <a href="#" class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-white hover:bg-white/20 transition-all">
                            <i class="ri-linkedin-fill"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-6">Our Businesses</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Financial Lending</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Luxury Jewelry</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Travel & Tours</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Charitable Initiatives</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-6">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="#About" class="text-gray-400 hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#business" class="text-gray-400 hover:text-white transition-colors">Business</a></li>
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
                    <a href="https://asltechnology.online/" target="_blank" class="text-gray-400 hover:text-white text-sm transition-colors">
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
