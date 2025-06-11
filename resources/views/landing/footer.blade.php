<footer class="gradient-primary text-white">
    <div class="section-padding">
        <div class="container-main">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-12 max-w-6xl mx-auto">
                <!-- Brand Section -->
                <div class="lg:col-span-2">
                    <img src="{{ asset('images/logo.png') }}" class="h-12 mb-6 hover-scale" alt="AchieveUp Logo">
                    <p class="text-white/80 text-lg leading-relaxed mb-6 max-w-md">
                        Empowering education through innovative achievement tracking and modern learning solutions that
                        transform the way we learn and grow.
                    </p>
                    <div class="flex gap-4">
                        @php
                            $socialLinks = [
                                ['icon' => 'twitter', 'url' => '#'],
                                ['icon' => 'facebook', 'url' => '#'],
                                ['icon' => 'linkedin', 'url' => '#'],
                                ['icon' => 'instagram', 'url' => '#'],
                            ];
                        @endphp

                        @foreach ($socialLinks as $social)
                            <a href="{{ $social['url'] }}"
                                class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center text-white hover:bg-white hover:text-purple-600 transition-all duration-300 hover:scale-110">
                                @switch($social['icon'])
                                    @case('twitter')
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                                        </svg>
                                    @break

                                    @case('facebook')
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                        </svg>
                                    @break

                                    @case('linkedin')
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                        </svg>
                                    @break

                                    @case('instagram')
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.621 5.367 11.988 11.988 11.988s11.987-5.367 11.987-11.988C24.004 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.596-3.205-1.529l1.452-1.452c.48.48 1.141.777 1.868.777 1.467 0 2.654-1.188 2.654-2.654 0-.727-.297-1.388-.777-1.868l1.452-1.452c.933.757 1.529 1.908 1.529 3.205 0 2.295-1.859 4.154-4.154 4.154zm7.540 0c-1.297 0-2.448-.596-3.205-1.529l1.452-1.452c.48.48 1.141.777 1.868.777 1.467 0 2.654-1.188 2.654-2.654 0-.727-.297-1.388-.777-1.868l1.452-1.452c.933.757 1.529 1.908 1.529 3.205 0 2.295-1.859 4.154-4.154 4.154z" />
                                        </svg>
                                    @break
                                @endswitch
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-xl font-bold mb-6">Quick Links</h4>
                    <div class="space-y-4">
                        <a href="#home"
                            class="block text-white/80 hover:text-white transition-colors duration-300">Home</a>
                        <a href="#about"
                            class="block text-white/80 hover:text-white transition-colors duration-300">About Us</a>
                        <a href="#categories"
                            class="block text-white/80 hover:text-white transition-colors duration-300">Categories</a>
                        <a href="#leaderboard"
                            class="block text-white/80 hover:text-white transition-colors duration-300">Leaderboard</a>
                    </div>
                </div>

                <!-- Support -->
                <div>
                    <h4 class="text-xl font-bold mb-6">Support</h4>
                    <div class="space-y-4">
                        <a href="#"
                            class="block text-white/80 hover:text-white transition-colors duration-300">Help Center</a>
                        <a href="#"
                            class="block text-white/80 hover:text-white transition-colors duration-300">Contact Us</a>
                        <a href="#"
                            class="block text-white/80 hover:text-white transition-colors duration-300">Privacy
                            Policy</a>
                        <a href="#"
                            class="block text-white/80 hover:text-white transition-colors duration-300">Terms of
                            Service</a>
                    </div>
                </div>
            </div>

            <!-- Bottom Section -->
            <div class="border-t border-white/20 pt-8 max-w-6xl mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-white/80 text-center md:text-left">
                        © 2025 AchieveUp. All rights reserved. Made with ❤️ for better education.
                    </p>
                    <div class="flex gap-6">
                        <a href="#"
                            class="text-white/80 hover:text-white transition-colors duration-300 text-sm">Privacy</a>
                        <a href="#"
                            class="text-white/80 hover:text-white transition-colors duration-300 text-sm">Terms</a>
                        <a href="#"
                            class="text-white/80 hover:text-white transition-colors duration-300 text-sm">Cookies</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
