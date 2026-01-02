<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RAN Serenity Hub</title>
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
                extend: {
                    colors: {
                        primary: "#76689a",
                        secondary: "#76689a"
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
    <!-- Floating Back-to-Top Button -->
    <button id="backToTop"
        class="hidden opacity-0 fixed bottom-6 right-6 bg-primary text-white p-3 rounded-full shadow-lg hover:bg-primary/80 transition-all duration-300">
        <i class="ri-arrow-up-line text-2xl"></i>
    </button>

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
                <a href="/" class="text-2xl font-['Pacifico'] text-white">RAN Serenity HUB</a>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/hub"
                        class="text-white hover:text-white/80 font-medium relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-white after:transition-all">Home</a>
                    <a href="/hub#about"
                        class="text-white hover:text-white/80 font-medium relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-white after:transition-all">About</a>
                    <a href="/hub#blog"
                        class="text-white hover:text-white/80 font-medium relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-white after:transition-all">Blog</a>
                    <button onclick="document.getElementById('donate-modal').classList.remove('hidden')" type="button"
                        class="bg-white text-primary px-5 py-2 rounded-button font-medium hover:bg-white/90 transition-all whitespace-nowrap">Donate</button>
                </div>
                <button id="menuToggle" class="md:hidden text-white focus:outline-none">
                    <i class="ri-menu-line ri-2x"></i>
                </button>
            </div>
            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden md:hidden mt-4 pb-4">
                <div class="flex flex-col space-y-4">
                    <a href="/hub" class="text-white hover:text-white/80 font-medium">Home</a>
                    <a href="/hub#about" class="text-white hover:text-white/80 font-medium">About</a>
                    <a href="/hub#Blog" class="text-white hover:text-white/80 font-medium">Blog</a>
                    <button onclick="document.getElementById('donate-modal').classList.remove('hidden')" type="button"
                        class="bg-white text-primary px-5 py-2 rounded-button font-medium hover:bg-white/90 transition-all whitespace-nowrap w-full">Donate</button>
                </div>
            </div>
        </div>
    </nav>
    @php
        // Convert JSON tags to array
        $tags = [];

        if (!empty($post->tags_json)) {
            $decoded = json_decode($post->tags_json, true);
            if (is_array($decoded)) {
                // Map to string if stored as objects with 'value', otherwise leave as string
                $tags = array_map(fn($t) => is_array($t) && isset($t['value']) ? $t['value'] : $t, $decoded);
            }
        }

        // Category icon map
        $map = [
            'jewelry' => ['bi-gem', '#ffe5ec'],
            'travel and tours' => ['bi-airplane-engines', '#e0f7fa'],
            'hub' => ['bi-heart-fill', '#f3e5f5'],
            'shops' => ['bi-shop', '#fff3cd'],
        ];

        $key = strtolower($post->category ?? '');
        $icon = $map[$key][0] ?? 'bi-folder-fill';
        $bgColor = $map[$key][1] ?? '#f8f9fa';
    @endphp

    <div class="max-w-3xl mx-auto px-6 py-10 my-10">

        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-900 leading-tight">
                {{ $post->title ?? 'Untitled' }}
            </h1>

            @if($post->excerpt)
                <p class="mt-3 text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ $post->excerpt }}
                </p>
            @endif
        </div>

        <!-- Featured Image -->
        @if(!empty($post->featured_image))
            <div class="w-full mb-10">
                <img src="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : 'https://via.placeholder.com/800x600?text=No+Image' }}"
                    class="w-full h-[350px] object-cover rounded-xl shadow-md">
            </div>
        @endif

        <!-- Category -->
        <div class="mb-8">
            <div class="flex items-center gap-3 p-4 rounded-xl shadow-sm border"
                style="background-color: {{ $bgColor }};">

                <i class="bi {{ $icon }} text-2xl text-purple-600"></i>

                <div>
                    <p class="uppercase text-xs font-semibold text-gray-500">Category</p>
                    <p class="text-lg font-semibold text-gray-900">{{ ucfirst($post->category) }}</p>
                </div>
            </div>
        </div>

        <!-- Tags -->
        <div class="mb-8">
            <p class="uppercase text-xs font-semibold text-gray-500 mb-3 flex items-center gap-2">
                <i class="bi bi-tags-fill text-purple-600"></i>
                Tags
            </p>

            <div class="flex flex-wrap gap-2">
                @if(count($tags) > 0)
                    @foreach($tags as $tag)
                        <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm font-medium rounded-full shadow-sm">
                            {{ $tag }}
                        </span>
                    @endforeach
                @else
                    <span class="italic text-gray-500">No tags added</span>
                @endif
            </div>
        </div>

        <hr class="my-10 border-gray-200">

        <!-- Content -->
        <div
            class="prose prose-lg max-w-none prose-headings:text-gray-900 prose-p:text-gray-700 prose-a:text-purple-600">
            {!! $post->content ?: '<p class="italic text-gray-500">No content written.</p>' !!}
        </div>

    </div>

    <!-- Blog Section -->

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
    <!-- Donate Modal -->
    <div id="donate-modal" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center hidden">
        <div class="bg-white max-w-lg w-full mx-4 rounded-lg shadow-lg p-8 relative">
            <!-- Close Button -->
            <button onclick="document.getElementById('donate-modal').classList.add('hidden')"
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-2xl">
                &times;
            </button>

            <h3 class="text-2xl font-bold text-primary mb-4 text-center">
                Thank you for wanting to share your blessing!
            </h3>
            <p class="text-gray-700 text-center mb-6">
                You can support us by scanning the QR code below or using our bank details.
            </p>

            <!-- QR Code -->
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/donate_qr.jpg') }}" alt="Donation QR Code" class="w-[250px] h-[250px] " />
            </div>

            <!-- Bank Details -->
            <div class="bg-gray-100 rounded-lg p-4 text-left text-gray-800 space-y-2 text-sm md:text-base">
                <div><strong>Bank Name:</strong> Metrobank</div>
                <div><strong>Account Number:</strong> 466-3-466-28180-9</div>
                <div><strong>Account Name:</strong> Almira Avendano</div>
            </div>
            <p class="text-muted small text-center mt-3">
                <i class="ri-phone-line text-primary"></i>
                Need assistance? <br>Please reach out to our admin:
                <a href="tel:09691899935" class="text-primary-custom fw-bold">0969-189-9935</a>
            </p>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
    <script src="{{ asset('js/index.js') }}"></script>
</body>

</html>