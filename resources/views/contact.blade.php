<x-layouts.app :title="__('Contact')">
    @push('styles')
    <style>
        .contact-card {
            transform-style: preserve-3d;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            perspective: 1000px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-radius: 1rem;
        }

        .contact-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .contact-card:hover::before {
            left: 100%;
        }

        .contact-card:hover {
            transform: translateY(-8px) rotateX(5deg) rotateY(5deg);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15), 0 0 0 1px rgba(255,255,255,0.1), inset 0 1px 0 rgba(255,255,255,0.2);
        }

        .contact-icon {
            transition: all 0.3s ease;
        }

        .contact-card:hover .contact-icon {
            transform: scale(1.2) rotate(10deg);
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #00a884, #25d366);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #008f73, #1ebc5d);
        }

        /* Dark mode scrollbar */
        .dark ::-webkit-scrollbar-track {
            background: #2d3748;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #00a884, #25d366);
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #008f73, #1ebc5d);
        }
    </style>
    @endpush

    <div class="max-w-screen-xl mx-auto px-4 py-6">
        <!-- Enhanced Header -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 p-8 text-white card-3d fade-in mb-8 shadow-xl">
            <div class="absolute inset-0 bg-black/20"></div>
            <div class="relative z-10 text-center">
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">Contact</h1>
                <p class="mt-2 text-blue-100">Get in touch with us through various channels</p>
            </div>
        </div>

        <!-- Contact Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Gmail Card -->
            <a href="mailto:aanugerahahmad27@gmail.com" class="contact-card group bg-gradient-to-br from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 backdrop-blur-sm border-2 border-red-400/50 hover:border-red-300 rounded-2xl p-6 text-center fade-in shadow-lg hover:shadow-red-500/25 transition-all duration-300 cursor-pointer h-full">
                <div class="flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-4 mx-auto group-hover:bg-white/30 transition-colors group-hover:scale-110">
                    <x-bxs-envelope class="contact-icon text-3xl text-white w-8 h-8" />
                </div>
                <h3 class="text-xl font-bold mb-2 text-white">Gmail</h3>
                <p class="text-red-100 text-sm mb-3">aanugerahahmad27@gmail.com</p>
                <div class="text-xs text-white/80 opacity-0 group-hover:opacity-100 transition-opacity">Click to send email</div>
            </a>
            
            <!-- Mobile Contact Cards Responsive -->
            <style>
                @media (max-width: 768px) {
                    .contact-cards-grid {
                        grid-template-columns: 1fr !important;
                        gap: 1rem !important;
                    }
                    .contact-card {
                        padding: 1rem !important;
                        border-radius: 1rem !important;
                    }
                    .contact-card h3 {
                        font-size: 1.125rem !important;
                    }
                    .contact-card p {
                        font-size: 0.75rem !important;
                    }
                }
                @media (max-width: 480px) {
                    .contact-cards-grid {
                        gap: 0.75rem !important;
                    }
                    .contact-card {
                        padding: 0.75rem !important;
                        border-radius: 0.75rem !important;
                    }
                    .contact-card h3 {
                        font-size: 1rem !important;
                    }
                    .contact-card p {
                        font-size: 0.625rem !important;
                    }
                }
            </style>

            <!-- Phone Card -->
            <a href="tel:+62" class="contact-card group bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 backdrop-blur-sm border-2 border-blue-400/50 hover:border-blue-300 rounded-2xl p-6 text-center fade-in shadow-lg hover:shadow-blue-500/25 transition-all duration-300 cursor-pointer h-full">
                <div class="flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-4 mx-auto group-hover:bg-white/30 transition-colors group-hover:scale-110">
                    <x-bxs-phone class="contact-icon text-3xl text-white w-8 h-8" />
                </div>
                <h3 class="text-xl font-bold mb-2 text-white">Phone</h3>
                <p class="text-blue-100 text-sm mb-3">+62 XXX XXX XXXX</p>
                <div class="text-xs text-white/80 opacity-0 group-hover:opacity-100 transition-opacity">Click to call</div>
            </a>

            <!-- Address Card -->
            <div class="contact-card group bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 backdrop-blur-sm border-2 border-green-400/50 hover:border-green-300 rounded-2xl p-6 text-center fade-in shadow-lg hover:shadow-green-500/25 transition-all duration-300 cursor-pointer h-full">
                <div class="flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-4 mx-auto group-hover:bg-white/30 transition-colors group-hover:scale-110">
                    <x-bxs-map class="contact-icon text-3xl text-white w-8 h-8" />
                </div>
                <h3 class="text-xl font-bold mb-2 text-white">Address</h3>
                <p class="text-green-100 text-sm mb-3">Refinery Unit VI Balongan<br>Indramayu, Jawa Barat</p>
                <div class="text-xs text-white/80 opacity-0 group-hover:opacity-100 transition-opacity">Location Information</div>
            </div>

            <!-- WhatsApp Card -->
            <a href="https://wa.me/" target="_blank" class="contact-card group bg-gradient-to-br from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 backdrop-blur-sm border-2 border-emerald-400/50 hover:border-emerald-300 rounded-2xl p-6 text-center fade-in shadow-lg hover:shadow-emerald-500/25 transition-all duration-300 cursor-pointer h-full">
                <div class="flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-4 mx-auto group-hover:bg-white/30 transition-colors group-hover:scale-110">
                    <x-bxl-whatsapp class="contact-icon text-3xl text-white w-8 h-8" />
                </div>
                <h3 class="text-xl font-bold mb-2 text-white">WhatsApp</h3>
                <p class="text-emerald-100 text-sm mb-3">Chat with us</p>
                <div class="text-xs text-white/80 opacity-0 group-hover:opacity-100 transition-opacity">Click to open WhatsApp</div>
            </a>

            <!-- Instagram Card -->
            <a href="https://instagram.com/" target="_blank" class="contact-card group bg-gradient-to-br from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 backdrop-blur-sm border-2 border-pink-400/50 hover:border-pink-300 rounded-2xl p-6 text-center fade-in shadow-lg hover:shadow-pink-500/25 transition-all duration-300 cursor-pointer h-full">
                <div class="flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-4 mx-auto group-hover:bg-white/30 transition-colors group-hover:scale-110">
                    <x-bxl-instagram class="contact-icon text-3xl text-white w-8 h-8" />
                </div>
                <h3 class="text-xl font-bold mb-2 text-white">Instagram</h3>
                <p class="text-pink-100 text-sm mb-3">Follow us</p>
                <div class="text-xs text-white/80 opacity-0 group-hover:opacity-100 transition-opacity">Click to open Instagram</div>
            </a>

            <!-- Emergency Card -->
            <div class="contact-card group bg-gradient-to-br from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700 backdrop-blur-sm border-2 border-yellow-400/50 hover:border-yellow-300 rounded-2xl p-6 text-center fade-in shadow-lg hover:shadow-yellow-500/25 transition-all duration-300 cursor-pointer h-full">
                <div class="flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-4 mx-auto group-hover:bg-white/30 transition-colors group-hover:scale-110">
                    <x-bxs-shield-alt-2 class="contact-icon text-3xl text-white w-8 h-8" />
                </div>
                <h3 class="text-xl font-bold mb-2 text-white">Emergency</h3>
                <p class="text-yellow-100 text-sm mb-3">24/7 Support</p>
                <div class="text-xs text-white/80 opacity-0 group-hover:opacity-100 transition-opacity">Always Available</div>
            </div>
        </div>
    </div>
</x-layouts.app>
