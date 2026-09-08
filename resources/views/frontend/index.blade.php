<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Primary Meta Tags -->
    <title>Notaris & PPAT Debra T.C. Schram, S.H. | Kepastian Hukum & Integritas</title>
    <meta name="title" content="Notaris & PPAT Debra T.C. Schram, S.H. | Kepastian Hukum & Integritas">
    <meta name="description"
        content="Layanan Notaris & PPAT Debra T.C. Schram, S.H. Melayani masyarakat dan bisnis dengan integritas, kepastian hukum mutlak, dan kepatuhan penuh peraturan RI.">
    <meta name="keywords"
        content="Notaris, PPAT, Debra T.C. Schram, Pejabat Pembuat Akta Tanah, Akta Notaris, Layanan Hukum, Jual Beli Tanah, Pendirian PT, CV, Notaris Terpercaya, Hukum Perusahaan Indonesia">
    <meta name="author" content="Debra T.C. Schram, S.H.">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Kantor Notaris & PPAT Debra T.C. Schram, S.H.">
    <meta property="og:description"
        content="Diangkat oleh Negara untuk melayani masyarakat dan entitas bisnis dengan integritas dan kepastian hukum mutlak.">
    <meta property="og:image" content="{{ asset('front/Debrapas.webp') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('') }}">
    <meta property="twitter:title" content="Kantor Notaris & PPAT Debra T.C. Schram, S.H.">
    <meta property="twitter:description"
        content="Diangkat oleh Negara untuk melayani masyarakat dan entitas bisnis dengan integritas dan kepastian hukum mutlak.">
    <meta property="twitter:image" content="{{ asset('front/Debrapas.webp') }}">

    <!-- Additional/Verification Tags (Opsional) -->
    <meta name="language" content="id">
    <meta name="geo.region" content="ID">
    <meta name="geo.placename" content="Indonesia">

    <title>Kantor Notaris & PPAT | DEBRA T.C. SCHRAM, SH.</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        official: ['"Times New Roman"', 'Times', 'serif'],
                    },
                    colors: {
                        brand: {
                            black: '#111827',
                            white: '#ffffff',
                            gray: '#4b5563',
                            light: '#f9fafb',
                            gold: '#B8860B',
                            goldlight: '#D4AF37'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .delay-100 {
            transition-delay: 100ms;
        }

        .delay-200 {
            transition-delay: 200ms;
        }

        .border-kop-surat {
            border-bottom: 4px solid #111827;
            position: relative;
            margin-bottom: 4px;
        }

        .border-kop-surat::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: -6px;
            border-bottom: 1px solid #111827;
        }

        .navbar-hidden {
            transform: translateY(-100%);
            opacity: 0;
        }

        .navbar-visible {
            transform: translateY(0);
            opacity: 1;
        }
    </style>
</head>

<body
    class="bg-brand-white text-brand-black font-sans antialiased overflow-x-hidden selection:bg-brand-gold selection:text-brand-white">

    <!-- Collapsing Navbar -->
    <nav class="fixed w-full z-50 bg-brand-white/95 backdrop-blur-sm shadow-md transition-all duration-500 border-b border-brand-gold/20"
        id="navbar">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="font-official text-sm md:text-base font-bold tracking-widest uppercase flex items-center gap-3">
                <span class="text-brand-black">Kantor Notaris & PPAT</span>
                <span class="text-brand-gold hidden md:inline">|</span>
                <span class="hidden md:inline text-brand-black">DEBRA T.C. SCHRAM, SH.</span>
            </div>
            <div class="hidden md:flex gap-8 text-sm font-semibold tracking-wide uppercase">
                <a href="#beranda" class="hover:text-brand-gold transition-colors">Beranda</a>
                <a href="#legalitas" class="hover:text-brand-gold transition-colors">Profil & Legalitas</a>
                <a href="#layanan" class="hover:text-brand-gold transition-colors">Layanan</a>
                <a href="#kontak" class="hover:text-brand-gold transition-colors">Akses</a>
                <a href="/login" class="hover:text-brand-gold transition-colors">Login</a>
            </div>

            <button class="md:hidden p-2 text-brand-black" id="mobile-menu-btn">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <line x1="4" x2="20" y1="12" y2="12" />
                    <line x1="4" x2="20" y1="6" y2="6" />
                    <line x1="4" x2="20" y1="18" y2="18" />
                </svg>
            </button>
        </div>

        <div class="md:hidden hidden bg-brand-white border-b border-brand-gold/20 absolute w-full shadow-lg"
            id="mobile-menu">
            <div class="px-6 py-4 flex flex-col gap-4 text-sm font-bold uppercase tracking-wider text-brand-black">
                <a href="#beranda" class="block py-2 border-b border-gray-100 hover:text-brand-gold">Beranda</a>
                <a href="#profil" class="block py-2 border-b border-gray-100 hover:text-brand-gold">Profil Pejabat</a>
                <a href="#layanan" class="block py-2 border-b border-gray-100 hover:text-brand-gold">Layanan</a>
                <a href="#kontak" class="block py-2 hover:text-brand-gold">Akses</a>
                <a href="{{ route('login') }}" class="block py-2 hover:text-brand-gold">Login</a>
            </div>
        </div>
    </nav>

    <!-- 1. Formal Institutional Hero Section -->
    <header id="beranda"
        class="relative pt-32 pb-24 px-6 max-w-7xl mx-auto flex flex-col lg:flex-row items-center justify-between gap-16 min-h-[90vh]">

        <!-- Left: Official State Authority Text -->
        <div class="w-full lg:w-1/2 flex flex-col items-center lg:items-start text-center lg:text-left reveal">
            <!-- Garuda Pancasila - Symbol of State Authority -->
            <img src="/front/garuda.webp" alt="Garuda Pancasila" class="w-20 md:w-24 h-auto mb-8 drop-shadow-sm">

            <h1 class="font-official text-xl md:text-2xl font-bold tracking-[0.2em] text-brand-black uppercase mb-3">
                NOTARIS & PPAT
            </h1>

            <h2
                class="font-official text-4xl md:text-5xl lg:text-6xl font-bold text-brand-black mb-6 uppercase leading-tight">
                Debra T.C. Schram, S.H.
            </h2>

            <!-- SK Decrees -->
            <div
                class="font-sans text-[10px] md:text-xs text-brand-gray font-bold space-y-2 mb-8 uppercase tracking-widest bg-gray-50 p-4 border border-gray-200 w-full lg:max-w-md">
                <p>SK. Menteri Hukum dan HAM RI No: C-526.HT.03.01-TH.1998</p>
                <p>SK. Kepala BPN RI No: 25-IX-2001</p>
            </div>

            <div class="w-full max-w-md h-[3px] bg-brand-gold mb-8"></div>

            <p class="font-sans text-brand-gray text-base md:text-lg leading-relaxed mb-10 max-w-lg font-medium">
                Diangkat oleh Negara untuk melayani masyarakat dan entitas bisnis dengan integritas, kepastian hukum
                mutlak, dan kepatuhan penuh terhadap peraturan perundang-undangan Republik Indonesia.
            </p>

            <a href="https://wa.me/6287775232059" target="_blank"
                class="px-8 py-4 bg-[#111827] text-white font-sans text-sm font-bold tracking-widest uppercase hover:bg-brand-gold transition-colors duration-300 shadow-lg border border-transparent hover:border-brand-gold">
                Jadwalkan Layanan Resmi
            </a>
        </div>

        <!-- Right: Formal Official Portrait -->
        <div class="w-full lg:w-1/2 flex justify-center lg:justify-end reveal delay-100">
            <div class="relative w-full max-w-[400px] bg-white p-4 shadow-2xl border border-gray-300">
                <!-- Traditional framing (Double Border) like a government office portrait -->
                <div class="border-4 border-brand-gold p-1 bg-white">
                    <img src="/front/Debrapas.webp" alt="Debra T.C. Schram, S.H."
                        class="w-full aspect-[3/4] object-cover object-top">
                </div>

                <!-- Official Plaque Tag -->
                <div
                    class="absolute -bottom-6 left-1/2 transform -translate-x-1/2 bg-[#111827] text-white px-8 py-4 min-w-[85%] text-center shadow-xl border-t-2 border-brand-gold">
                    <p class="font-official font-bold text-sm md:text-base tracking-[0.2em] uppercase mb-1">Daerah
                        Kerja
                    </p>
                    <p class="font-sans text-xs tracking-wider text-brand-gold font-medium uppercase">Kabupaten
                        Tangerang, Banten</p>
                </div>
            </div>
        </div>

    </header>

    <!-- 2. Vibrant Office Image Break -->
    <section class="w-full h-[40vh] md:h-[65vh] reveal bg-brand-black">
        <img src="/front/office.webp" alt="Interior Kantor Notaris" class="w-full h-full object-cover opacity-95">
    </section>

    <!-- 3. Profil Pejabat -->
    <section id="profil" class="py-24 px-6 bg-brand-light border-y border-gray-200">
        <div class="max-w-4xl mx-auto text-center reveal">
            <div class="font-official text-sm uppercase tracking-[0.3em] text-brand-gold mb-4 font-bold">Profil Pejabat
            </div>
            <h2 class="text-3xl md:text-4xl font-official font-bold text-brand-black mb-10">Dedikasi & Integritas Hukum
            </h2>

            <div class="font-sans text-brand-gray text-base md:text-lg leading-relaxed space-y-6">
                <p>
                    Sebagai Pejabat Umum yang diangkat oleh Negara, kami menjamin setiap dokumen dan akta otentik yang
                    diterbitkan disusun dengan ketelitian mutlak, memenuhi seluruh syarat formil dan materiil sesuai
                    dengan hukum positif Republik Indonesia.
                </p>
                <p>
                    Dengan pengalaman ekstensif selama lebih dari dua dekade, <strong>Debra T.C. Schram, S.H.</strong>
                    memiliki keahlian mendalam dalam memfasilitasi legalitas korporasi, perbankan komersial, perikatan
                    keperdataan, serta peralihan hak atas tanah guna memberikan kepastian hukum yang mutlak bagi setiap
                    klien.
                </p>
            </div>
        </div>
    </section>

    <!-- 4. Layanan Section (Preserved) -->
    <section id="layanan" class="bg-brand-white">
        <div class="max-w-6xl mx-auto px-6 py-20 text-center reveal">
            <h2 class="text-3xl md:text-4xl font-official font-bold text-brand-black mb-4">Spektrum Layanan Hukum</h2>
            <div class="w-24 h-[3px] bg-brand-gold mx-auto"></div>
        </div>

        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 border-t border-gray-200 bg-brand-white">

            <div
                class="p-10 md:p-14 border-b md:border-r border-gray-200 hover:shadow-xl transition-all duration-300 group reveal">
                <div
                    class="font-official text-5xl font-bold text-brand-gold/20 group-hover:text-brand-gold transition-colors mb-6">
                    I.</div>
                <h3 class="font-official text-2xl font-bold text-brand-black uppercase tracking-wide mb-6">Legalitas
                    Korporasi</h3>
                <ul class="text-sm md:text-base text-brand-gray space-y-4">
                    <li class="flex items-start gap-3"><span class="text-brand-gold mt-0.5">✦</span> Pendirian PT, CV,
                        Yayasan, dan Perkumpulan</li>
                    <li class="flex items-start gap-3"><span class="text-brand-gold mt-0.5">✦</span> Pembuatan Risalah
                        Rapat Umum Pemegang Saham (RUPS)</li>
                    <li class="flex items-start gap-3"><span class="text-brand-gold mt-0.5">✦</span> Perubahan
                        Anggaran Dasar dan Data Perseroan</li>
                    <li class="flex items-start gap-3"><span class="text-brand-gold mt-0.5">✦</span> Penyusunan
                        Perjanjian Kerjasama Bisnis</li>
                </ul>
            </div>

            <div
                class="p-10 md:p-14 border-b border-gray-200 hover:shadow-xl transition-all duration-300 group reveal delay-100">
                <div
                    class="font-official text-5xl font-bold text-brand-gold/20 group-hover:text-brand-gold transition-colors mb-6">
                    II.</div>
                <h3 class="font-official text-2xl font-bold text-brand-black uppercase tracking-wide mb-6">Layanan
                    Pertanahan (PPAT)</h3>
                <ul class="text-sm md:text-base text-brand-gray space-y-4">
                    <li class="flex items-start gap-3"><span class="text-brand-gold mt-0.5">✦</span> Akta Jual Beli
                        (AJB) dan Akta Hibah</li>
                    <li class="flex items-start gap-3"><span class="text-brand-gold mt-0.5">✦</span> Akta Tukar
                        Menukar & Pembagian Hak Bersama</li>
                    <li class="flex items-start gap-3"><span class="text-brand-gold mt-0.5">✦</span> Akta Pemberian
                        Hak Tanggungan (APHT)</li>
                    <li class="flex items-start gap-3"><span class="text-brand-gold mt-0.5">✦</span> Surat Kuasa
                        Membebankan Hak Tanggungan (SKMHT)</li>
                </ul>
            </div>

            <div
                class="p-10 md:p-14 border-b md:border-b-0 md:border-r border-gray-200 hover:shadow-xl transition-all duration-300 group reveal">
                <div
                    class="font-official text-5xl font-bold text-brand-gold/20 group-hover:text-brand-gold transition-colors mb-6">
                    III.</div>
                <h3 class="font-official text-2xl font-bold text-brand-black uppercase tracking-wide mb-6">Legalitas
                    Perbankan</h3>
                <ul class="text-sm md:text-base text-brand-gray space-y-4">
                    <li class="flex items-start gap-3"><span class="text-brand-gold mt-0.5">✦</span> Akta Perjanjian
                        Kredit Komersial</li>
                    <li class="flex items-start gap-3"><span class="text-brand-gold mt-0.5">✦</span> Pengikatan
                        Jaminan Fidusia</li>
                    <li class="flex items-start gap-3"><span class="text-brand-gold mt-0.5">✦</span> Pengikatan
                        Jaminan Hak Tanggungan</li>
                    <li class="flex items-start gap-3"><span class="text-brand-gold mt-0.5">✦</span> Akta Sindikasi
                        dan Restrukturisasi Kredit</li>
                </ul>
            </div>

            <div class="p-10 md:p-14 hover:shadow-xl transition-all duration-300 group reveal delay-100">
                <div
                    class="font-official text-5xl font-bold text-brand-gold/20 group-hover:text-brand-gold transition-colors mb-6">
                    IV.</div>
                <h3 class="font-official text-2xl font-bold text-brand-black uppercase tracking-wide mb-6">Keperdataan
                    & Koperasi</h3>
                <ul class="text-sm md:text-base text-brand-gray space-y-4">
                    <li class="flex items-start gap-3"><span class="text-brand-gold mt-0.5">✦</span> Legalisasi Tanda
                        Tangan & Waarmerking</li>
                    <li class="flex items-start gap-3"><span class="text-brand-gold mt-0.5">✦</span> Pencocokan
                        Fotokopi (Copy Collationne)</li>
                    <li class="flex items-start gap-3"><span class="text-brand-gold mt-0.5">✦</span> Perjanjian
                        Pra-Nikah dan Surat Wasiat</li>
                    <li class="flex items-start gap-3"><span class="text-brand-gold mt-0.5">✦</span> Pendirian,
                        Perubahan, dan Pembubaran Koperasi</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- 5. Kontak Section -->
    <section id="kontak" class="py-24 px-6 max-w-6xl mx-auto border-t border-gray-200">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            <div class="reveal">
                <div class="font-official text-sm uppercase tracking-[0.3em] text-brand-gold mb-3 font-bold">Akses &
                    Informasi</div>
                <h2 class="text-3xl md:text-4xl font-official font-bold text-brand-black mb-8">Hubungi Kantor Kami</h2>
                <p class="text-brand-gray mb-12 text-base leading-relaxed">
                    Untuk menjadwalkan konsultasi, permintaan pembuatan akta, atau permohonan informasi, silakan
                    menghubungi kami atau kunjungi kantor resmi kami pada jam kerja.
                </p>

                <div class="space-y-8 text-base">
                    <div class="flex items-start gap-5">
                        <div
                            class="w-12 h-12 rounded-full bg-brand-gold/10 flex items-center justify-center shrink-0 text-brand-gold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="leading-relaxed pt-1">
                            <div class="font-bold uppercase text-xs tracking-widest text-brand-gold mb-1">Alamat Resmi
                            </div>
                            <strong class="text-brand-black">Kantor Notaris Debra T.C. Schram, SH</strong><br>
                            <span class="text-brand-gray">Ruko Foresta Business Loft Thp 1, Unit 07<br>
                                Jl. BSD Raya Utama, Pagedangan<br>
                                BSD City, Kab. Tangerang, Banten</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-5">
                        <div
                            class="w-12 h-12 rounded-full bg-brand-gold/10 flex items-center justify-center shrink-0 text-brand-gold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                        </div>
                        <div class="pt-1">
                            <div class="font-bold uppercase text-xs tracking-widest text-brand-gold mb-1">Telepon &
                                WhatsApp</div>
                            <div class="space-y-1 text-brand-gray font-medium">
                                <a href="tel:087775232059"
                                    class="block hover:text-brand-gold transition-colors">0877-7523-2059</a>
                                <a href="tel:087880303539"
                                    class="block hover:text-brand-gold transition-colors">0878-8030-3539</a>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-5">
                        <div
                            class="w-12 h-12 rounded-full bg-brand-gold/10 flex items-center justify-center shrink-0 text-brand-gold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="pt-1">
                            <div class="font-bold uppercase text-xs tracking-widest text-brand-gold mb-1">Email
                                Korespodensi</div>
                            <a href="mailto:notarisdebra2568@gmail.com"
                                class="text-brand-gray font-medium hover:text-brand-gold transition-colors break-all">notarisdebra2568@gmail.com</a>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="reveal delay-100 w-full h-[400px] lg:h-full min-h-[400px] bg-gray-100 shadow-xl border border-gray-200 p-2 relative">
                <iframe
                    src="https://maps.google.com/maps?q=Kantor+Notaris+Debra+T.C.+Schram,+SH&t=&z=17&ie=UTF8&iwloc=&output=embed"
                    class="absolute inset-0 p-2 w-full h-full" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

        </div>
    </section>

    <footer class="py-8 px-6 text-center text-xs text-brand-gray bg-brand-light border-t border-gray-200">
        <div class="font-official font-bold text-sm text-brand-black mb-1 uppercase tracking-widest">Kantor Notaris &
            PPAT Debra T.C. Schram, SH.</div>
        <div>&copy; 2026 Seluruh hak cipta dilindungi undang-undang.</div>
    </footer>

    <!-- button wa -->
    <a href="https://wa.me/6287775232059" target="_blank"
        class="fixed bottom-6 right-6 md:bottom-10 md:right-10 bg-[#25D366] text-white p-4 rounded-full shadow-2xl hover:scale-110 transition-transform duration-300 z-50 flex items-center justify-center group">
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
        </svg>
        <span
            class="absolute right-full mr-4 bg-brand-black text-white text-xs font-sans font-medium py-2 px-3 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
            Hubungi via WhatsApp
        </span>
    </a>

    <script>
        document.addEventListener("DOMContentLoaded", () => {

            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.10
            };
            const revealObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    }
                });
            }, observerOptions);
            document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));


            const navbar = document.getElementById('navbar');
            const heroSection = document.getElementById('beranda');

            const navObserverOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            // const navObserver = new IntersectionObserver((entries) => {
            //     entries.forEach(entry => {
            //         if (!entry.isIntersecting) {
            //             navbar.classList.remove('navbar-hidden');
            //             navbar.classList.add('navbar-visible');
            //         } else {
            //             navbar.classList.add('navbar-hidden');
            //             navbar.classList.remove('navbar-visible');
            //         }
            //     });
            // }, navObserverOptions);

            // if (heroSection) {
            //     navObserver.observe(heroSection);
            // }

            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        });
    </script>
</body>

</html>
