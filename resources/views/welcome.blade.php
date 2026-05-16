<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiMonTani - Sistem Monitoring Pertanian Terpadu</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['DM Sans', 'sans-serif'],
                        serif: ['DM Serif Display', 'serif'],
                    },
                    colors: {
                        primary: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            900: '#1a5c38', /* Nature Green */
                        },
                        earthy: '#8B5A2B', /* Earthy Brown */
                    }
                }
            }
        }
    </script>
    <!-- Iconify -->
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <style>
        .hero-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="font-sans text-slate-800 antialiased bg-slate-50 selection:bg-primary-500 selection:text-white">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-200 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-primary-700 rounded-full flex items-center justify-center shadow-lg shadow-primary-700/30">
                        <span class="iconify text-white w-6 h-6" data-icon="ph:plant"></span>
                    </div>
                    <span class="font-serif text-2xl font-bold text-primary-900 tracking-tight">SiMonTani</span>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#fitur" class="text-slate-600 hover:text-primary-700 font-medium transition-colors">Fitur Utama</a>
                    <a href="#statistik" class="text-slate-600 hover:text-primary-700 font-medium transition-colors">Statistik</a>
                    <div class="h-6 w-px bg-slate-300"></div>
                    <a href="{{ route('login') }}" class="text-slate-600 hover:text-primary-700 font-semibold transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-primary-700 hover:bg-primary-600 text-white px-6 py-2.5 rounded-xl font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                        Daftar Akun
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden hero-pattern">
        <!-- Decorative blobs -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-primary-100 blur-3xl opacity-50 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-emerald-100 blur-3xl opacity-50 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                <!-- Text Content -->
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-50 border border-primary-100 text-primary-700 text-sm font-semibold mb-6">
                        <span class="iconify w-4 h-4" data-icon="heroicons:sparkles"></span>
                        Platform Pertanian Digital #1
                    </div>
                    <h1 class="font-serif text-5xl lg:text-6xl font-bold leading-tight text-slate-900 mb-6">
                        Masa Depan <span class="text-primary-700">Pertanian</span> Ada di Genggaman Anda.
                    </h1>
                    <p class="text-lg text-slate-600 mb-8 leading-relaxed">
                        SiMonTani mengintegrasikan manajemen lahan, operasional penggilingan padi, hingga distribusi beras dalam satu platform cerdas. Tingkatkan efisiensi dan transparansi sekarang juga.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}" class="bg-primary-700 hover:bg-primary-600 text-white text-center px-8 py-3.5 rounded-xl font-semibold text-lg shadow-lg shadow-primary-700/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-200 flex items-center justify-center gap-2">
                            Mulai Sekarang <span class="iconify" data-icon="heroicons:arrow-right"></span>
                        </a>
                        <a href="#fitur" class="bg-white hover:bg-slate-50 text-slate-700 text-center px-8 py-3.5 rounded-xl font-semibold text-lg border border-slate-200 shadow-sm hover:shadow hover:-translate-y-1 transition-all duration-200">
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>

                <!-- Hero Image -->
                <div class="relative lg:ml-auto w-full max-w-lg">
                    <div class="absolute inset-0 bg-gradient-to-tr from-primary-600 to-emerald-400 rounded-[2rem] transform rotate-3 scale-105 opacity-20 blur-xl"></div>
                    <div class="relative rounded-[2rem] overflow-hidden shadow-2xl border-4 border-white">
                        <img src="{{ asset('images/hero.png') }}" alt="SiMonTani Hero" class="w-full h-auto object-cover transform hover:scale-105 transition-transform duration-700">
                    </div>
                    
                    <!-- Floating Badge -->
                    <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-2xl shadow-xl border border-slate-100 flex items-center gap-4 animate-bounce" style="animation-duration: 3s;">
                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                            <span class="iconify text-primary-600 w-6 h-6" data-icon="heroicons:chart-bar"></span>
                        </div>
                        <div>
                            <div class="text-sm text-slate-500 font-medium">Efisiensi Naik</div>
                            <div class="text-xl font-bold text-slate-800">+45%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="font-serif text-3xl md:text-4xl font-bold text-slate-900 mb-4">Satu Platform, Tiga Peran Utama</h2>
                <p class="text-lg text-slate-600">Sistem kami didesain khusus untuk menyatukan ekosistem pertanian dari hulu ke hilir dengan alur kerja yang terstruktur.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Petani Card -->
                <div class="group bg-slate-50 rounded-3xl p-8 border border-slate-100 hover:bg-white hover:border-primary-100 hover:shadow-xl hover:shadow-primary-900/5 transition-all duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary-100 rounded-bl-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center justify-center mb-6 relative z-10 text-primary-600 group-hover:bg-primary-600 group-hover:text-white transition-colors duration-300">
                        <span class="iconify w-8 h-8" data-icon="heroicons:hand-raised"></span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 relative z-10">Petani</h3>
                    <p class="text-slate-600 relative z-10">Input data profil lahan, pantau jadwal tanam, dan catat hasil panen dengan mudah secara terintegrasi.</p>
                    <ul class="mt-6 space-y-3 relative z-10">
                        <li class="flex items-center gap-3 text-sm text-slate-700 font-medium"><span class="iconify text-primary-500" data-icon="heroicons:check-circle"></span> Manajemen Lahan</li>
                        <li class="flex items-center gap-3 text-sm text-slate-700 font-medium"><span class="iconify text-primary-500" data-icon="heroicons:check-circle"></span> Pencatatan Panen</li>
                    </ul>
                </div>

                <!-- Rice Mill Card -->
                <div class="group bg-slate-50 rounded-3xl p-8 border border-slate-100 hover:bg-white hover:border-earthy hover:shadow-xl hover:shadow-earthy/5 transition-all duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4IiBoZWlnaHQ9IjgiPgo8cmVjdCB3aWR0aD0iOCIgaGVpZ2h0PSI4IiBmaWxsPSIjZmZmIj48L3JlY3Q+CjxwYXRoIGQ9Ik0wIDBMOCA4Wk04IDBMMCA4WiIgc3Ryb2tlPSIjZmNkNzc3IiBzdHJva2Utd2lkdGg9IjEiPjwvcGF0aD4KPC9zdmc+')] opacity-20 -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center justify-center mb-6 relative z-10 text-[#8B5A2B] group-hover:bg-[#8B5A2B] group-hover:text-white transition-colors duration-300">
                        <span class="iconify w-8 h-8" data-icon="heroicons:building-office-2"></span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 relative z-10">Rice Mill</h3>
                    <p class="text-slate-600 relative z-10">Monitoring tingkat rendemen produksi, kelola penerimaan gabah, dan jalankan operasional penggilingan otomatis.</p>
                    <ul class="mt-6 space-y-3 relative z-10">
                        <li class="flex items-center gap-3 text-sm text-slate-700 font-medium"><span class="iconify text-[#8B5A2B]" data-icon="heroicons:check-circle"></span> Operasional Giling</li>
                        <li class="flex items-center gap-3 text-sm text-slate-700 font-medium"><span class="iconify text-[#8B5A2B]" data-icon="heroicons:check-circle"></span> Monitoring Rendemen</li>
                    </ul>
                </div>

                <!-- Packager Card -->
                <div class="group bg-slate-50 rounded-3xl p-8 border border-slate-100 hover:bg-white hover:border-blue-200 hover:shadow-xl hover:shadow-blue-900/5 transition-all duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center justify-center mb-6 relative z-10 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                        <span class="iconify w-8 h-8" data-icon="heroicons:archive-box"></span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 relative z-10">Packager</h3>
                    <p class="text-slate-600 relative z-10">Manajemen stok kemasan (5kg, 10kg, 25kg), kontrol kualitas beras putih, dan urus pesanan distribusi.</p>
                    <ul class="mt-6 space-y-3 relative z-10">
                        <li class="flex items-center gap-3 text-sm text-slate-700 font-medium"><span class="iconify text-blue-500" data-icon="heroicons:check-circle"></span> Stok Kemasan</li>
                        <li class="flex items-center gap-3 text-sm text-slate-700 font-medium"><span class="iconify text-blue-500" data-icon="heroicons:check-circle"></span> Order & Distribusi</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section id="statistik" class="py-20 bg-primary-900 text-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-white/10">
                <div class="px-4">
                    <div class="text-4xl md:text-5xl font-bold text-primary-400 mb-2 font-serif">10K+</div>
                    <div class="text-primary-100 font-medium">Petani Tergabung</div>
                </div>
                <div class="px-4">
                    <div class="text-4xl md:text-5xl font-bold text-primary-400 mb-2 font-serif">98%</div>
                    <div class="text-primary-100 font-medium">Efisiensi Produksi</div>
                </div>
                <div class="px-4">
                    <div class="text-4xl md:text-5xl font-bold text-primary-400 mb-2 font-serif">50+</div>
                    <div class="text-primary-100 font-medium">Rice Mill Aktif</div>
                </div>
                <div class="px-4">
                    <div class="text-4xl md:text-5xl font-bold text-primary-400 mb-2 font-serif">24 Jam</div>
                    <div class="text-primary-100 font-medium">Monitoring Real-time</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-primary-700 rounded-full flex items-center justify-center">
                    <span class="iconify text-white w-5 h-5" data-icon="ph:plant"></span>
                </div>
                <span class="font-serif text-xl font-bold text-white tracking-tight">SiMonTani</span>
            </div>
            <p class="text-sm text-slate-400">&copy; {{ date('Y') }} SiMonTani by Rice Mill App. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
