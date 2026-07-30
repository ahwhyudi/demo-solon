<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Laporan\InvoiceHistoryController;
use App\Http\Controllers\Laporan\PekerjaanStaff\Detail\detailLaporanPekerjaanStaffController;
use App\Http\Controllers\Laporan\PekerjaanStaff\Home\homeLaporanPekerjaanStaffController;
use App\Http\Controllers\Laporan\PenomoranController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {

    Route::get('laporan/nomor-notaris/{kategori?}', [PenomoranController::class, 'index'])
        ->name('laporan.nomor-notaris');

    Route::post("inputNomorRekanan", [PenomoranController::class, 'inputNomorRekanan'])
        ->name("laporan.inputNomorRekanan");

    Route::get('laporan/nomor-notaris/export/{kategori?}', [PenomoranController::class, 'export'])
        ->name('laporan.nomor-notaris.export');
    Route::post(
        'laporan/nomor-notaris/update',
        [PenomoranController::class, 'update']
    )->name('laporan.nomor-notaris.update');

    Route::resource("laporan/invoice", InvoiceController::class);

    Route::get('laporan/pekerjaan/{kategori?}', [PenomoranController::class, 'index'])
        ->name('laporan.pekerjaan');


    // START Laporan Pekerjaan (Halaman List Home & Detail Staff)

    Route::get('laporan/list-pekerjaan-staff', [homeLaporanPekerjaanStaffController::class, 'index'])
        ->name('laporan.list-pekerjaan-staff');

    Route::get('laporan/detail-pekerjaan-staff/{encryptedId}', [detailLaporanPekerjaanStaffController::class, 'index'])
        ->name('laporan.detail-pekerjaan-staff');

    Route::get('/laporan/detail-pekerjaan-staff/{encryptedId}/export', [detailLaporanPekerjaanStaffController::class, 'exportDetailLaporanPekerjaanToExcel'])
        ->name('laporan.detail-pekerjaan-staff.export');

    Route::get(
        'laporan/list-job-divisi-history',
        [InvoiceHistoryController::class, 'index']
    )->name('laporan.list-job-divisi-history');

    // END Laporan Pekerjaan (Halaman List Home & Detail Staff)
});
