<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.4.0
* @link https://tabler.io
* Copyright 2018-2025 The Tabler Authors
* Copyright 2018-2025 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Sign in - SUPER NOTARIS</title>
    <script defer data-api="/stats/event" data-domain="preview.tabler.io" src="/stats/js/script.js"></script>
    <meta name="msapplication-TileColor" content="#066fd1" />
    <meta name="theme-color" content="#066fd1" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="HandheldFriendly" content="True" />
    <meta name="MobileOptimized" content="320" />
    <link rel="icon" href="./favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" />
    <meta name="description"
        content="Tabler is packed with beautifully crafted components and powerful features. Jump in and start building a stunning dashboard — all for free!" />
    <meta name="canonical" content="https://preview.tabler.io/sign-in.html" />
    <meta name="twitter:image:src" content="https://preview.tabler.io/static/og.png" />
    <meta name="twitter:site" content="@tabler_ui" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title"
        content="Tabler: Premium and Open Source dashboard template with responsive and high quality UI." />
    <meta name="twitter:description"
        content="Tabler is packed with beautifully crafted components and powerful features. Jump in and start building a stunning dashboard — all for free!" />
    <meta property="og:image" content="https://preview.tabler.io/static/og.png" />
    <meta property="og:image:width" content="1280" />
    <meta property="og:image:height" content="640" />
    <meta property="og:site_name" content="Tabler" />
    <meta property="og:type" content="object" />
    <meta property="og:title"
        content="Tabler: Premium and Open Source dashboard template with responsive and high quality UI." />
    <meta property="og:url" content="https://preview.tabler.io/static/og.png" />
    <meta property="og:description"
        content="Tabler is packed with beautifully crafted components and powerful features. Jump in and start building a stunning dashboard — all for free!" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->

    <link href="/tabler-admin/demo/dist/css/tabler.min.css?1684106062" rel="stylesheet" />

    <style>
        @import url("https://rsms.me/inter/inter.css");
    </style>
    <!-- END CUSTOM FONT -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsencrypt/3.3.2/jsencrypt.min.js"></script>
</head>

<body>
    <script src="/tabler-admin/demo/dist/js/tabler.min.js?1684106062" defer></script>
    <script src="/tabler-admin/demo/dist/js/demo.min.js?1684106062" defer></script>

    <div class="page page-center">
        <div class="container container-tight py-4">

            <div class="card card-md">
                <div class="card-body">
                    <h2 class="h2 text-center mb-4"> SUPER NOTARIS</h2>

                    <form action="{{ route('prosesLogin') }}" method="post" id="formLogin" autocomplete="off"
                        novalidate>
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email address</label>
                            <input type="email" class="form-control" id="inputEmail" placeholder="your@email.com"
                                autocomplete="off" />
                        </div>

                        <div class="mb-2">
                            <label class="form-label">
                                Password
                                {{-- <span class="form-label-description">
                                    <a href="./forgot-password.html">I forgot password</a>
                                </span> --}}
                            </label>
                            <div class="input-group input-group-flat">
                                <input type="password" class="form-control" placeholder="Your password"
                                    autocomplete="off" id="password" />
                                <span class="input-group-text">
                                    <a href="#" class="link-secondary" id="togglePassword" title="Show password"
                                        data-bs-toggle="tooltip">
                                        <span id="iconEye">
                                            <!-- icon default: eye -->
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24px"
                                                viewBox="0 -960 960 960" width="24px" fill="#666666">
                                                <path
                                                    d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z" />
                                            </svg>
                                        </span>
                                    </a>
                                </span>
                            </div>
                        </div>
                        {{-- <div class="mb-2">
                            <label class="form-check">
                                <input type="checkbox" class="form-check-input" />
                                <span class="form-check-label">Remember me on this device</span>
                            </label>
                        </div> --}}
                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary w-100" id="btnLogin">Login</button>
                        </div>
                        <input type="hidden" name="email" id="hiddenEmail">
                        <input type="hidden" name="password" id="hiddenPassword">
                    </form>
                </div>

            </div>
            {{-- <div class="text-center text-secondary mt-3">Don't have account yet? <a href="./sign-up.html"
                    tabindex="-1">Sign up</a></div> --}}
        </div>
    </div>
    <!-- HANYA PAKAI SATE SCRIPT INI SAJA -->
    <script>
        // Tangkap input email (berdasarkan name="email")
        const inputEmail = document.getElementById('inputEmail');
        const password = document.getElementById('password');
        const hiddenEmail = document.getElementById('hiddenEmail');
        const hiddenPassword = document.getElementById('hiddenPassword');

        const togglePassword = document.getElementById('togglePassword');
        const icon = document.getElementById('iconEye');
        const formLogin = document.getElementById('formLogin');
        const btnLogin = document.getElementById('btnLogin');

        // Ambil public key dari Laravel secara aman
        const publicKey = {!! json_encode($publicKey) !!};

        // 1. EVENT SUBMIT FORM (Khusus enkripsi & kirim data)
        formLogin.addEventListener('submit', function(e) {
            e.preventDefault();

            btnLogin.disabled = true;
            btnLogin.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Loading...';

            const encryptor = new JSEncrypt();
            encryptor.setPublicKey(publicKey);

            // Enkripsi dari input asli yang keliatan di layar
            const encryptedEmail = encryptor.encrypt(inputEmail.value);
            const encryptedPassword = encryptor.encrypt(password.value);

            if (!encryptedEmail || !encryptedPassword) {
                alert('Gagal memproses enkripsi keamanan, silakan coba lagi.');
                btnLogin.disabled = false;
                btnLogin.innerHTML = 'Login';
                return;
            }

            // Masukkan hasil enkripsi ke INPUT HIDDEN
            hiddenEmail.value = encryptedEmail;
            hiddenPassword.value = encryptedPassword;

            // Submit form
            formLogin.submit();
        });

        // 2. EVENT KLIK ICON MATA (Khusus show/hide password)
        togglePassword.addEventListener('click', function(e) {
            e.preventDefault();

            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Ganti icon (eye ↔ eye-off)
            if (type === 'text') {
                icon.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#666666"><path d="m644-428-58-58q9-47-27-88t-93-32l-58-58q17-8 34.5-12t37.5-4q75 0 127.5 52.5T660-500q0 20-4 37.5T644-428Zm128 126-58-56q38-29 67.5-63.5T832-500q-50-101-143.5-160.5T480-720q-29 0-57 4t-55 12l-62-62q41-17 84-25.5t90-8.5q151 0 269 83.5T920-500q-23 59-60.5 109.5T772-302Zm20 246L624-222q-35 11-70.5 16.5T480-200q-151 0-269-83.5T40-500q21-53 53-98.5t73-81.5L56-792l56-56 736 736-56 56ZM222-624q-29 26-53 57t-41 67q50 101 143.5 160.5T480-280q20 0 39-2.5t39-5.5l-36-38q-11 3-21 4.5t-21 1.5q-75 0-127.5-52.5T300-500q0-11 1.5-21t4.5-21l-84-82Zm319 93Zm-151 75Z"/></svg>
            `;
            } else {
                icon.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#666666"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/></svg>
            `;
            }
        });
    </script>

    <script src="/tabler-admin/demo/dist/js/demo-theme.min.js?1684106062"></script>
</body>

</html>
