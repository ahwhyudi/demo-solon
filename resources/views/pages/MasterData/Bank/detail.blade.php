@extends('layouts.admin')


@section('title')
    Detail Bank {{ $bank->nama }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="#tab_bank" class="nav-link active" data-bs-toggle="tab" aria-selected="true"
                        role="tab"><!-- Download SVG icon from http://tabler.io/icons/icon/home -->

                        BANK
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#tabs-profile-3" class="nav-link" data-bs-toggle="tab" aria-selected="false" tabindex="-1"
                        role="tab"><!-- Download SVG icon from http://tabler.io/icons/icon/user -->
                        Legal
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#tabs-profile-3" class="nav-link" data-bs-toggle="tab" aria-selected="false" tabindex="-1"
                        role="tab"><!-- Download SVG icon from http://tabler.io/icons/icon/user -->
                        Marketing
                    </a>
                </li>

            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active show" id="tab_bank" role="tabpanel">
                    @include('pages.MasterData.Bank.tab_details._tab_bank')

                </div>
                <div class="tab-pane" id="tabs-profile-3" role="tabpanel">
                    <h4>Profile tab</h4>

                </div>
            </div>
        </div>
    </div>
@endsection
