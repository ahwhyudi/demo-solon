    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">

                @foreach ($dataPendukung as $index => $item)
                    @if ($item === 'debitur')
                        <li class="nav-item " role="presentation">
                            <a href="#tabs-debitur" class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Debitur</a>
                        </li>
                    @endif
                    @if ($item === 'usaha debitur')
                        <li class="nav-item " role="presentation">
                            <a href="#tabs-usaha-debitur" class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Badan Usaha Sebagai
                                Debitur</a>
                        </li>
                    @endif
                    @if ($item === 'badan hukum')
                        <li class="nav-item " role="presentation">
                            <a href="#tabs-badan-hukum" class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Badan Hukum</a>
                        </li>
                    @endif
                    @if ($item === 'badan usaha pembeli')
                        <li class="nav-item " role="presentation">
                            <a href="#tabs-badan-usaha-pembeli" class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Badan Usaha Sebagai
                                Pembeli</a>
                        </li>
                    @endif
                    @if ($item === 'badan usaha penjual')
                        <li class="nav-item " role="presentation">
                            <a href="#tabs-badan-usaha-penjual" class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Badan Usaha Sebagai
                                Penjual</a>
                        </li>
                    @endif
                    @if ($item === 'pendirian lembaga')
                        <li class="nav-item " role="presentation">
                            <a href="#tabs-pendirian-lembaga" class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Pendirian
                                Lembaga</a>
                        </li>
                    @endif
                    @if ($item === 'penjual')
                        <li class="nav-item " role="presentation">
                            <a href="#tabs-penjual" class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Penjual</a>
                        </li>
                    @endif
                    @if ($item === 'pembeli')
                        <li class="nav-item " role="presentation">
                            <a href="#tabs-pembeli" class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Pembeli</a>
                        </li>
                    @endif
                    @if ($item === 'developer')
                        <li class="nav-item " role="presentation">
                            <a href="#tabs-developer" class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Developer</a>
                        </li>
                    @endif
                    @if ($item === 'broker')
                        <li class="nav-item " role="presentation">
                            <a href="#tabs-broker" class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Broker</a>
                        </li>
                    @endif
                    @if ($item === 'bank')
                        <li class="nav-item " role="presentation">
                            <a href="#tabs-bank" class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Bank</a>
                        </li>
                    @endif
                    @if ($item === 'objek')
                        <li class="nav-item " role="presentation">
                            <a href="#tabs-objek" class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Objek</a>
                        </li>
                    @endif
                @endforeach

            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                @foreach ($dataPendukung as $index => $item)
                    @if ($item === 'debitur')
                        <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}" id="tabs-debitur"
                            role="tabs-debitur">
                            @include('pages.Job.Divisi._form_debitur')
                        </div>
                    @endif
                    @if ($item === 'usaha debitur')
                        <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}" id="tabs-usaha-debitur"
                            role="tabs-usaha-debitur">
                            @include('pages.Job.Divisi._form_usaha_debitur')
                        </div>
                    @endif
                    @if ($item === 'badan hukum')
                        <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}" id="tabs-badan-hukum"
                            role="tabs-badan-hukum">
                            @include('pages.Job.Divisi._form_badan_hukum')
                        </div>
                    @endif
                    @if ($item === 'badan usaha pembeli')
                        <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}"
                            id="tabs-badan-usaha-pembeli" role="tabs-badan-usaha-pembeli">
                            @include('pages.Job.Divisi._form_badan_usaha_pembeli')
                        </div>
                    @endif
                    @if ($item === 'badan usaha penjual')
                        <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}"
                            id="tabs-badan-usaha-penjual" role="tabs-badan-usaha-penjual">
                            @include('pages.Job.Divisi._form_badan_usaha_penjual')
                        </div>
                    @endif
                    @if ($item === 'pendirian lembaga')
                        <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}"
                            id="tabs-pendirian-lembaga" role="tabs-pendirian-lembaga">
                            @include('pages.Job.Divisi._form_pendirian_lembaga')
                        </div>
                    @endif
                    @if ($item === 'developer')
                        <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}" id="tabs-developer"
                            role="tabs-developer">
                            @include('pages.Job.Divisi._form_developer')
                        </div>
                    @endif
                    @if ($item === 'penjual')
                        <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}" id="tabs-penjual"
                            role="tabs-penjual">
                            @include('pages.Job.Divisi._form_penjual')
                        </div>
                    @endif
                    @if ($item === 'pembeli')
                        <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}" id="tabs-pembeli"
                            role="tabs-pembeli">
                            @include('pages.Job.Divisi._form_pembeli')
                        </div>
                    @endif
                    @if ($item === 'broker')
                        <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}" id="tabs-broker"
                            role="tabs-broker">
                            @include('pages.Job.Divisi._form_broker')
                        </div>
                    @endif
                    @if ($item === 'bank')
                        <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}" id="tabs-bank"
                            role="tabs-bank">
                            @include('pages.Job.Divisi._form_bank')
                        </div>
                    @endif
                    @if ($item === 'objek')
                        <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}" id="tabs-objek"
                            role="tabs-objek">
                            @include('pages.Job.Divisi._form_objek')
                        </div>
                    @endif
                @endforeach


                {{-- <div class="tab-pane fade" id="tabs-profile-8" role="tabpanel">
                    <h4>Profile tab</h4>
                    <div>
                        Fringilla egestas nunc quis tellus diam rhoncus ultricies tristique enim at diam, sem nunc amet,
                        pellentesque id egestas velit sed
                    </div>
                </div> --}}

            </div>
        </div>
    </div>
