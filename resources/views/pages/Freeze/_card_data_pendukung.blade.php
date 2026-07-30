<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
            {{-- {{ dd($dataPendukung) }} --}}
            @foreach ($dataPendukung as $index => $item)
                @if ($item === 'debitur')
                    <li class="nav-item " role="presentation">
                        <a href="#tabs-debitur" class="nav-link {{ $index === 0 ? 'active' : '' }}" data-bs-toggle="tab"
                            aria-selected="false" tabindex="-1" role="tab">Debitur</a>
                    </li>
                @endif
                @if ($item === 'usaha debitur')
                    <li class="nav-item " role="presentation">
                        <a href="#tabs-usaha-debitur" class="nav-link {{ $index === 0 ? 'active' : '' }}"
                            data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Badan Usaha
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
                            data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Badan Usaha
                            Pembeli</a>
                    </li>
                @endif
                @if ($item === 'badan usaha penjual')
                    <li class="nav-item " role="presentation">
                        <a href="#tabs-badan-usaha-penjual" class="nav-link {{ $index === 0 ? 'active' : '' }}"
                            data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Badan Usaha
                            Penjual</a>
                    </li>
                @endif
                @if ($item === 'penjual')
                    <li class="nav-item " role="presentation">
                        <a href="#tabs-penjual" class="nav-link {{ $index === 0 ? 'active' : '' }}" data-bs-toggle="tab"
                            aria-selected="false" tabindex="-1" role="tab">Penjual</a>
                    </li>
                @endif
                @if ($item === 'pembeli')
                    <li class="nav-item " role="presentation">
                        <a href="#tabs-pembeli" class="nav-link {{ $index === 0 ? 'active' : '' }}" data-bs-toggle="tab"
                            aria-selected="false" tabindex="-1" role="tab">Pembeli</a>
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
                        <a href="#tabs-broker" class="nav-link {{ $index === 0 ? 'active' : '' }}" data-bs-toggle="tab"
                            aria-selected="false" tabindex="-1" role="tab">Broker</a>
                    </li>
                @endif
                @if ($item === 'bank')
                    <li class="nav-item " role="presentation">
                        <a href="#tabs-bank" class="nav-link {{ $index === 0 ? 'active' : '' }}" data-bs-toggle="tab"
                            aria-selected="false" tabindex="-1" role="tab">Bank</a>
                    </li>
                @endif
                @if ($item === 'objek')
                    <li class="nav-item " role="presentation">
                        <a href="#tabs-objek" class="nav-link {{ $index === 0 ? 'active' : '' }}" data-bs-toggle="tab"
                            aria-selected="false" tabindex="-1" role="tab">Bank</a>
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
                        <x-job.detail.list-debitur-component :list-debitur="$jobDivisi->listDebitur" />
                    </div>
                @endif
                @if ($item === 'usaha debitur')
                    <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}" id="tabs-usaha-debitur"
                        role="tabs-usaha-debitur">
                        <x-job.detail.list-badan-usaha-debitur-component :list-badan-usaha-debitur="$jobDivisi->listBadanUsahaDebitur" />

                    </div>
                @endif
                {{-- @if ($item === 'badan hukum')
                    <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}" id="tabs-badan-hukum"
                        role="tabs-badan-hukum">
                        @include('pages.Job.Divisi._form_badan_hukum')
                    </div>
                @endif --}}
                @if ($item === 'badan usaha pembeli')
                    <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}" id="tabs-badan-usaha-pembeli"
                        role="tabs-badan-usaha-pembeli">
                        <x-job.detail.list-badan-usaha-pembeli-component :listBadanUsahaPembeli="$jobDivisi->listBadanUsahaPembeli" />

                    </div>
                @endif
                @if ($item === 'badan usaha penjual')
                    <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}" id="tabs-badan-usaha-penjual"
                        role="tabs-badan-usaha-penjual">
                        <x-job.detail.list-badan-usaha-penjual-component :listBadanUsahaPenjual="$jobDivisi->listBadanUsahaPenjual" />

                    </div>
                @endif
                @if ($item === 'developer')
                    <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}" id="tabs-developer"
                        role="tabs-developer">
                        <x-job.detail.list-developer-component :list-developer="$jobDivisi->developer" />

                    </div>
                @endif
                @if ($item === 'penjual')
                    <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}" id="tabs-penjual"
                        role="tabs-penjual">
                        <x-job.detail.list-penjual-component :listPenjual="$jobDivisi->penjual" />

                    </div>
                @endif
                @if ($item === 'pembeli')
                    <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}" id="tabs-pembeli"
                        role="tabs-pembeli">
                        <x-job.detail.list-pembeli-component :listPembeli="$jobDivisi->pembeli" />

                    </div>
                @endif
                @if ($item === 'broker')
                    <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}" id="tabs-broker"
                        role="tabs-broker">
                        <x-job.detail.list-broker-component :list-broker="$jobDivisi->listBroker" />
                    </div>
                @endif
                @if ($item === 'bank')
                    <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}" id="tabs-bank"
                        role="tabs-bank">
                        <x-job.detail.list-bank-component :listBank="$jobDivisi->listBank" />

                    </div>
                @endif
                @if ($item === 'objek')
                    <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}" id="tabs-objek"
                        role="tabs-objek">
                        <x-job.detail.list-objek-component :listObjek="$jobDivisi->objek" />
                    </div>
                @endif
            @endforeach

        </div>
    </div>
</div>
