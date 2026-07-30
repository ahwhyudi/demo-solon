 @php
     $name = auth()->user()->name;
     $initials = collect(explode(' ', $name))->map(fn($word) => strtoupper(substr($word, 0, 1)))->take(2)->implode('');

     $colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#F9C74F', '#577590', '#9B5DE5', '#F15BB5'];

     $bgColor = $colors[crc32($name) % count($colors)];
 @endphp

 <header class="navbar navbar-expand-md">
     <div class="container-fluid">
         <div class="nav-item mr-auto" style="text-transform: uppercase">
             {{-- @stack('page-title') --}}
             Super Notaris
         </div>
         <div class="navbar-nav flex-row order-md-last">
             <div class="d-none d-md-flex">

                 <div class="nav-item">

                 </div>
                 {{-- <div class="nav-item dropdown d-none d-md-flex">
                    <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
                        aria-label="Show notifications" data-bs-auto-close="outside" aria-expanded="false">
                        <!-- Download SVG icon from http://tabler.io/icons/icon/bell -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-1">
                            <path
                                d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6">
                            </path>
                            <path d="M9 17v1a3 3 0 0 0 6 0v-1"></path>
                        </svg>
                        <span class="badge bg-red text-white">{{ $totalNotifNavbar }}</span>
                    </a>

                </div> --}}

                 <div class="nav-item dropdown d-none d-md-flex">

                     <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
                         aria-label="Show notifications" data-bs-auto-close="outside" aria-expanded="false">

                         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="icon icon-1">

                             <path
                                 d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6">
                             </path>

                             <path d="M9 17v1a3 3 0 0 0 6 0v-1"></path>
                         </svg>

                         @if ($totalNotifNavbar > 0)
                             <span class="badge bg-red text-white">
                                 {{ $totalNotifNavbar > 99 ? '99+' : $totalNotifNavbar }}
                             </span>
                         @endif

                     </a>

                     {{-- DROPDOWN --}}
                     <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card"
                         style="width:350px; max-height:400px; overflow-y:auto;">

                         <div class="card">

                             <div class="card-header bg-warning">
                                 <h3 class="card-title text-white"><i class="bi bi-info-circle"></i> Notifikasi</h3>
                             </div>

                             <div class="list-group list-group-flush list-group-hoverable  ">

                                 @forelse ($notifikasiNavbar as $notif)
                                     <a href="{{ route('notifikasi.read', $notif->id) }}"
                                         class="list-group-item list-group-item-action
   {{ $notif->is_read == 0 ? 'bg-light fw-bold' : 'text-muted' }}">

                                         <div class="row align-items-center ">

                                             <div class="col text-truncate">
                                                 <div class="text-body {{ $notif->is_read == 0 ? 'fw-bold' : '' }}">
                                                     {{ $notif->title }} @if ($notif->jobDivisi)
                                                         -
                                                         <span class="badge bg-blue-lt">
                                                             {{ $notif->jobDivisi?->kode }}
                                                         </span>
                                                     @endif
                                                 </div>
                                                 <div
                                                     class="d-block mt-1 {{ $notif->is_read == 0 ? '' : 'text-muted' }}">
                                                     {{ $notif->formOrder?->nama }}
                                                 </div>
                                                 {{-- <div
                                                    class="d-block mt-1 {{ $notif->is_read == 0 ? '' : 'text-muted' }}">
                                                    {{ $notif->user->name }}
                                                </div> --}}

                                                 <div
                                                     class="d-block mt-1 {{ $notif->is_read == 0 ? 'text-dark' : 'text-secondary' }}">
                                                     {{ $notif->deskripsi }}
                                                 </div>

                                                 <small class="text-muted">
                                                     {{ $notif->created_at->diffForHumans() }}
                                                 </small>

                                             </div>

                                         </div>

                                     </a>

                                 @empty

                                     <div class="list-group-item text-center text-muted py-4">
                                         Tidak ada notifikasi
                                     </div>
                                 @endforelse

                             </div>

                         </div>

                     </div>

                 </div>



                 <div class="nav-item dropdown d-none d-md-flex me-3">
                     <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
                         aria-label="Show app menu" data-bs-auto-close="outside" aria-expanded="false">
                         <!-- Download SVG icon from http://tabler.io/icons/icon/apps -->
                         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="icon icon-1">
                             <path d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z">
                             </path>
                             <path d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z">
                             </path>
                             <path d="M14 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z">
                             </path>
                             <path d="M14 7l6 0"></path>
                             <path d="M17 4l0 6"></path>
                         </svg>
                     </a>

                 </div>
             </div>

             <div class="nav-item dropdown d-none d-lg-flex d-print-none">

                 <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                     aria-label="Open user menu">

                     @if (auth()->user()->profile_photo_url)
                         <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ $name }}"
                             style="
                width:40px;
                height:40px;
                border-radius:50%;
                object-fit:cover;
                border:2px solid #fff;
            ">
                     @else
                         <div
                             style="
                width:40px;
                height:40px;
                border-radius:50%;
                background:{{ $bgColor }};
                color:white;
                display:flex;
                align-items:center;
                justify-content:center;
                font-weight:600;
                font-size:14px;
                text-transform:uppercase;
            ">
                             {{ $initials }}
                         </div>
                     @endif

                     <div class="d-none d-xl-block ps-2">
                         <div>
                             {{ $name }}

                             @if (auth()->user()->cabang_id)
                                 <br>
                                 <small style="font-size:50%;">
                                     {{ auth()->user()->cabang->nama }}
                                 </small>
                             @endif
                         </div>
                     </div>

                 </a>
                 <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                     <a href="{{ route('profile.edit') }}" class="dropdown-item">Akun Saya</a>
                     <a href="{{ route('logoutProses') }}" class="dropdown-item">Logout</a>
                 </div>
             </div>
         </div>
         <div class="collapse navbar-collapse" id="navbar-menu">

         </div>
     </div>
 </header>

 @push('addScript')
 @endpush
