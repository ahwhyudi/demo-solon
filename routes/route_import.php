<?php

use App\Http\Controllers\Import\MasterData\ImportBankController;
use App\Http\Controllers\Import\MasterData\ImportBrokerController;
use App\Http\Controllers\Import\MasterData\ImportDeveloperController;
use App\Http\Controllers\Import\MasterData\ImportHargaPekerjaanController;
use Illuminate\Support\Facades\Route;

Route::prefix("import")
    ->name("import.")
    ->middleware("auth")
    ->group(function () {

        Route::prefix("master-data")
            ->name("master-data.")
            ->group(function () {
                Route::post("bank", [ImportBankController::class, "store"])->name("bank.store");
                Route::post("developer", [ImportDeveloperController::class, "store"])->name("developer.store");
                Route::post("broker", [ImportBrokerController::class, "store"])->name("broker.store");
                Route::post("harga-pekerjaan", [ImportHargaPekerjaanController::class, "store"])->name("harga-pekerjaan.store");
            });
    });
