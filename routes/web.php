<?php

use App\Http\Controllers\Arsip\ArsipWarkahController;
use App\Http\Controllers\Arsip\BundleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Export\ExportJobDivisiController;
use App\Http\Controllers\Export\ExportQuotationJobDivisiController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\Finance\FinanceJobDivisiController;
use App\Http\Controllers\Finance\FinanceReportController;
use App\Http\Controllers\HRIS\CutiController;
use App\Http\Controllers\HRIS\LemburController;
use App\Http\Controllers\Job\Akta\DataAktaController;
use App\Http\Controllers\Job\Bermasalah\DispoController;
use App\Http\Controllers\Job\Bermasalah\PendingController;
use App\Http\Controllers\Job\DataPendukunJobDivisiController;
use App\Http\Controllers\Job\DetailJobFinanceController;
use App\Http\Controllers\Job\DetailJobFormOrderController;
use App\Http\Controllers\Job\DetailJobOrderLuarInvController;
use App\Http\Controllers\Job\FreezeController;
use App\Http\Controllers\Job\JobDivisiController;
use App\Http\Controllers\Job\Notaris\NotarisController;
use App\Http\Controllers\Job\Ops\OperasionalController;
use App\Http\Controllers\Job\Pajak\DataPajakController;
use App\Http\Controllers\Job\PembatalanItemController;
use App\Http\Controllers\Job\PembatalanJobController;
use App\Http\Controllers\Job\PenambahanItemController;
use App\Http\Controllers\Job\SearchWilayahController;
use App\Http\Controllers\Job\Step2JobDivisiController;
use App\Http\Controllers\Job\SuratKeluarController;
use App\Http\Controllers\Job\WaarmerkingController;
use App\Http\Controllers\Job\WasiatController;
use App\Http\Controllers\Lokasi\KecamatanController;
use App\Http\Controllers\Lokasi\KotaController;
use App\Http\Controllers\Lokasi\ProvinsiController;
use App\Http\Controllers\MasterData\BankController;
use App\Http\Controllers\MasterData\BrokerController;
use App\Http\Controllers\MasterData\DebiturController;
use App\Http\Controllers\MasterData\DeveloperController;
use App\Http\Controllers\MasterData\DivisiController;
use App\Http\Controllers\MasterData\FormOrderController;
use App\Http\Controllers\MasterData\NotarisController as MasterNotarisController;
use App\Http\Controllers\MasterData\PekerjaanController;
use App\Http\Controllers\MasterData\StatusController;
use App\Http\Controllers\Pdf\InvoiceController;
use App\Http\Controllers\PnbpController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SearchWilayahController\Job;
use App\Http\Controllers\Setting\PerusahaanController;
use App\Http\Controllers\Setting\StepOpsController;
use App\Http\Controllers\Setting\WaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\File;

// 1. Halaman Utama / Landing Page
Route::get('/', function () {
    return view('frontend.index'); // atau view landing page Anda
});

// 2. Halaman Login yang Sebenarnya
Route::get('/login', function () {
    $keyPath = storage_path('app/keys/public.pem');
    $publicKey = File::exists($keyPath) ? File::get($keyPath) : null;

    return view('pages.login', compact('publicKey'));
})->middleware('guest')->name('login');

// Route proses login & logout
Route::post("proses-login", [AuthController::class, "prosesLogin"])->name("prosesLogin");
Route::post("logout", [AuthController::class, "logout"])->name("logout");
Route::get("logoutProses", [AuthController::class, "logout"])->name("logoutProses");

Route::middleware('auth')->group(function () {

    Route::post("uploadFile", [FileController::class, "uploadFile"])->name("uploadFile");
    Route::post("file/destroy/{id}", [FileController::class, "destroy"])->name("file.destroy");

    // Route::get("/", [DashboardController::class, "index"])->name("home");
    Route::get("/dashboard", [DashboardController::class, "index"])->name("welcome");
    Route::get("profile", [ProfileController::class, "edit"])->name("profile.edit");
    Route::put("profile", [ProfileController::class, "update"])->name("profile.update");
    Route::get("admin/profile", [ProfileController::class, "edit"])->name("admin.profile.edit");

    Route::prefix("akses")
        ->name("akses.")
        ->group(function () {
            Route::resource("user", UserController::class);
            Route::resource("role", RolePermissionController::class);
        });

    // masterdata
    Route::prefix('master-data')
        ->name("master-data.")
        ->group(function () {
            Route::prefix('lokasi')->group(function () {
                Route::resource('provinsi', ProvinsiController::class);
                Route::resource('kota', KotaController::class);
                Route::resource('kecamatan', KecamatanController::class);
            });

            Route::resource('divisi', DivisiController::class);
            Route::resource('pekerjaan', PekerjaanController::class);
            Route::resource('bank', BankController::class);
            Route::resource('status', StatusController::class);
            Route::resource('developer', DeveloperController::class);
            Route::resource('broker', BrokerController::class);
            Route::resource('notaris', MasterNotarisController::class);
            Route::resource('form-order', FormOrderController::class);
            // Route::resource('paketpekerjaan', FormOrderController::class);
        });

    Route::prefix("berkas-bermasalah")
        ->name("berkas-bermasalah.")
        ->group(function () {
            Route::resource("freeze", FreezeController::class);
            Route::resource("pending", PendingController::class);
            Route::resource("dispo", DispoController::class);
        });

    Route::prefix("job")
        ->name("job.")
        ->group(function () {
            Route::resource("divisi", JobDivisiController::class);
            Route::post("divisi-updateStatusSelesai/{job_id}", [JobDivisiController::class, "updateStatusSelesai"])->name("divisi.updateStatusSelesai");


            Route::get("export-job-divisi", [ExportJobDivisiController::class, "index"])
                ->name("export-job-divisi");



            Route::get("export-quotation-job-divisi/{id}", [ExportJobDivisiController::class, "printQuotation"])
                ->name("export-quotation-job-divisi");

            Route::get("export-debitur-job-divisi/{id}", [ExportJobDivisiController::class, "debitru"])->name("export-debitur-job-divisi");


            Route::post("divisi-data-pendukung", [DataPendukunJobDivisiController::class, 'store'])->name("divisi-data-pendukung.store");

            Route::post('divisi/{id}/assign', [JobDivisiController::class, 'assignJob'])
                ->name('divisi.assign');

            Route::get(
                'divisi-data-pendukung-debitur/{id}/edit',
                [DataPendukunJobDivisiController::class, 'editDebitur']
            )->name('divisi-data-pendukung-debitur.edit');

            Route::post(
                'divisi-data-pendukung-debitur/update/{debitur}',
                [DataPendukunJobDivisiController::class, 'updateDebitur']
            )->name('divisi-data-pendukung-debitur.update');

            Route::get(
                "divisi-data-pendukung-objek/{id}/edit",
                [DataPendukunJobDivisiController::class, 'editObjek']
            )->name("divisi-data-pendukung-objek.edit");

            Route::post(
                "divisi-data-pendukung-objek/update",
                [DataPendukunJobDivisiController::class, 'updateObjek']
            )->name("divisi-data-pendukung-objek.update");

            Route::get(
                "divisi-data-pendukung-bank/{id}/edit",
                [DataPendukunJobDivisiController::class, 'editBank']
            )->name("divisi-data-pendukung-bank.edit");

            Route::post(
                "divisi-data-pendukung-bank/update",
                [DataPendukunJobDivisiController::class, 'updateBank']
            )->name("divisi-data-pendukung-bank.update");

            Route::get(
                'divisi-data-pendukung-broker/{id}/edit',
                [DataPendukunJobDivisiController::class, 'editBroker']
            )->name('divisi-data-pendukung-broker.edit');

            Route::post(
                'divisi-data-pendukung-broker/update',
                [DataPendukunJobDivisiController::class, 'updateBroker']
            )->name('divisi-data-pendukung-broker.update');

            Route::get(
                'divisi-data-pendukung-developer/{id}/edit',
                [DataPendukunJobDivisiController::class, 'editDeveloper']
            )->name('divisi-data-pendukung-developer.edit');

            Route::post(
                'divisi-data-pendukung-developer/update',
                [DataPendukunJobDivisiController::class, 'updateDeveloper']
            )->name('divisi-data-pendukung-developer.update');

            Route::get(
                'divisi-data-pendukung-badan-usaha-pembeli/{id}/edit',
                [DataPendukunJobDivisiController::class, 'editBadanUsahaPembeli']
            )->name('divisi-data-pendukung-badan-usaha-pembeli.edit');

            Route::post(
                'divisi-data-pendukung-badan-usaha-pembeli/update',
                [DataPendukunJobDivisiController::class, 'updateBadanUsahaPembeli']
            )->name('divisi-data-pendukung-badan-usaha-pembeli.update');

            Route::get(
                'divisi-data-pendukung-badan-usaha-penjual/{id}/edit',
                [DataPendukunJobDivisiController::class, 'editBadanUsahaPenjual']
            )->name('divisi-data-pendukung-badan-usaha-penjual.edit');

            Route::post(
                'divisi-data-pendukung-badan-usaha-penjual/update',
                [DataPendukunJobDivisiController::class, 'updateBadanUsahaPenjual']
            )->name('divisi-data-pendukung-badan-usaha-penjual.update');

            Route::get(
                'divisi-data-pendukung-badan-usaha-debitur/{id}/edit',
                [DataPendukunJobDivisiController::class, 'editBadanUsahaDebitur']
            )->name('divisi-data-pendukung-badan-usaha-debitur.edit');

            Route::post(
                'divisi-data-pendukung-badan-usaha-debitur/update',
                [DataPendukunJobDivisiController::class, 'updateBadanUsahaDebitur']
            )->name('divisi-data-pendukung-badan-usaha-debitur.update');

            Route::get(
                'divisi-data-pendukung-penjual/{id}/edit',
                [DataPendukunJobDivisiController::class, 'editPenjual']
            )->name('divisi-data-pendukung-penjual.edit');

            Route::post(
                'divisi-data-pendukung-penjual/update',
                [DataPendukunJobDivisiController::class, 'updatePenjual']
            )->name('divisi-data-pendukung-penjual.update');

            Route::get(
                'divisi-data-pendukung-pembeli/{id}/edit',
                [DataPendukunJobDivisiController::class, 'editPembeli']
            )->name('divisi-data-pendukung-pembeli.edit');

            Route::post(
                'divisi-data-pendukung-pembeli/update',
                [DataPendukunJobDivisiController::class, 'updatePembeli']
            )->name('divisi-data-pendukung-pembeli.update');

            Route::get(
                'divisi-data-pendukung-pendirian-lembaga/{id}/edit',
                [DataPendukunJobDivisiController::class, 'editPendirianLembaga']
            )->name('divisi-data-pendukung-pendirian-lembaga.edit');

            Route::post(
                'divisi-data-pendukung-pendirian-lembaga/update',
                [DataPendukunJobDivisiController::class, 'updatePendirianLembaga']
            )->name('divisi-data-pendukung-pendirian-lembaga.update');

            Route::get(
                'divisi-data-pendukung-badan-hukum/{id}/edit',
                [DataPendukunJobDivisiController::class, 'editBadanHukum']
            )->name('divisi-data-pendukung-badan-hukum.edit');

            Route::post(
                'divisi-data-pendukung-badan-hukum/update',
                [DataPendukunJobDivisiController::class, 'updateBadanHukum']
            )->name('divisi-data-pendukung-badan-hukum.update');


            // delete route

            Route::delete(
                'divisi-data-pendukung/debitur/{id}',
                [DataPendukunJobDivisiController::class, 'deleteDebitur']
            )->name('divisi-data-pendukung-debitur.delete');

            Route::delete(
                'divisi-data-pendukung/penjual/{id}',
                [DataPendukunJobDivisiController::class, 'deletePenjual']
            )->name('divisi-data-pendukung-penjual.delete');

            Route::delete(
                'divisi-data-pendukung/pembeli/{id}',
                [DataPendukunJobDivisiController::class, 'deletePembeli']
            )->name('divisi-data-pendukung-pembeli.delete');

            Route::delete(
                'divisi-data-pendukung/objek/{id}',
                [DataPendukunJobDivisiController::class, 'deleteObjek']
            )->name('divisi-data-pendukung-objek.delete');

            Route::delete(
                'divisi-data-pendukung/bank/{id}',
                [DataPendukunJobDivisiController::class, 'deleteBank']
            )->name('divisi-data-pendukung-bank.delete');

            Route::delete(
                'divisi-data-pendukung/broker/{id}',
                [DataPendukunJobDivisiController::class, 'deleteBroker']
            )->name('divisi-data-pendukung-broker.delete');

            Route::delete(
                'divisi-data-pendukung/developer/{id}',
                [DataPendukunJobDivisiController::class, 'deleteDeveloper']
            )->name('divisi-data-pendukung-developer.delete');

            Route::delete(
                'divisi-data-pendukung/badan-usaha-pembeli/{id}',
                [DataPendukunJobDivisiController::class, 'deleteBadanUsahaPembeli']
            )->name('divisi-data-pendukung-badan-usaha-pembeli.delete');

            Route::delete(
                'divisi-data-pendukung/badan-usaha-penjual/{id}',
                [DataPendukunJobDivisiController::class, 'deleteBadanUsahaPenjual']
            )->name('divisi-data-pendukung-badan-usaha-penjual.delete');

            Route::delete(
                'divisi-data-pendukung/badan-usaha-debitur/{id}',
                [DataPendukunJobDivisiController::class, 'deleteBadanUsahaDebitur']
            )->name('divisi-data-pendukung-badan-usaha-debitur.delete');

            Route::delete(
                'divisi-data-pendukung/pendirian-lembaga/{id}',
                [DataPendukunJobDivisiController::class, 'deletePendirianLembaga']
            )->name('divisi-data-pendukung-pendirian-lembaga.delete');

            Route::delete(
                'divisi-data-pendukung/badan-hukum/{id}',
                [DataPendukunJobDivisiController::class, 'deleteBadanHukum']
            )->name('divisi-data-pendukung-badan-hukum.delete');

            Route::delete('divisi-data-pendukung/debitur/file/{id}', [DataPendukunJobDivisiController::class, 'destroyFileDebitur'])->name('divisi-data-pendukung-debitur.file.destroy');

            Route::delete('data-pendukung/badan-hukum/file/{id}', [DataPendukunJobDivisiController::class, 'destroyBadanHukumFile'])
                ->name('divisi-data-pendukung-badan-hukum.file.destroy');

            Route::delete('data-pendukung/bank/file/{id}', [DataPendukunJobDivisiController::class, 'destroyFileBank'])
                ->name('divisi-data-pendukung-bank.file.destroy');

            Route::delete('data-pendukung/badan-usaha-pembeli/file/{id}', [DataPendukunJobDivisiController::class, 'destroyFileBadanUsahaPembeli'])
                ->name('divisi-data-pendukung-badan-usaha-pembeli.file.destroy');

            Route::delete('data-pendukung/badan-usaha-penjual/file/{id}', [DataPendukunJobDivisiController::class, 'destroyFileBadanUsahaPenjual'])
                ->name('divisi-data-pendukung-badan-usaha-penjual.file.destroy');

            Route::delete('data-pendukung/badan-usaha-debitur/file/{id}', [DataPendukunJobDivisiController::class, 'destroyFileBadanUsahaDebitur'])
                ->name('divisi-data-pendukung-badan-usaha-debitur.file.destroy');

            Route::delete('data-pendukung/penjual/file/{id}', [DataPendukunJobDivisiController::class, 'destroyFilePenjual'])
                ->name('divisi-data-pendukung-penjual.file.destroy');

            Route::delete('data-pendukung/pembeli/file/{id}', [DataPendukunJobDivisiController::class, 'destroyFilePembeli'])
                ->name('divisi-data-pendukung-pembeli.file.destroy');

            Route::delete('data-pendukung/objek/file/{id}', [DataPendukunJobDivisiController::class, 'destroyFileObjek'])
                ->name('divisi-data-pendukung-objek.file.destroy');

            // end delete route

            Route::post("storeFormAkad-divisi", [JobDivisiController::class, "storeFormAkad"])->name("storeFormAkad");
            Route::get("divisi-step2", [Step2JobDivisiController::class, "step2"])->name("divisi-step2");
            Route::post("divisi-step2-store", [Step2JobDivisiController::class, "store"])->name("divisi-step2-store");
            Route::get("divisi-konfirmasi", [Step2JobDivisiController::class, "konfirmasi"])->name("divisi-konfirmasi");

            Route::resource("pembatalan-job-divisi", PembatalanJobController::class);
            Route::resource("pembatalan-items", PembatalanItemController::class);

            Route::post("addItemJobDivisi", [DetailJobFormOrderController::class, "addItemJobDivisi"])->name("addItemJobDivisi");
            Route::post("approvePerubahanHarga/{job_id}", [DetailJobFormOrderController::class, "approvePerubahanHarga"])->name("approvePerubahanHarga");

            Route::post("changeStatusAkad-divisi", [JobDivisiController::class, "changeStatusAkad"])->name("changeStatusAkad");
            Route::resource("detail-divisi-finance", DetailJobFinanceController::class);
            Route::get("divisi-count-data{id}", [JobDivisiController::class, "countData"])->name("count-data-divisi");
            Route::post('divisi/{id}/batal-akad', [JobDivisiController::class, 'batalAkad'])
                ->name('divisi.batal-akad');
            Route::post('divisi/{id}/buka-batal', [JobDivisiController::class, 'bukaBatal'])
                ->name('divisi.buka-batal');

            Route::resource("detail-divisi-order-luar-inv", DetailJobOrderLuarInvController::class);
            Route::prefix("form-order-divisi-detail")
                ->name("form-order-job-divisi.")
                ->group(function () {
                    Route::get("/", [DetailJobFormOrderController::class, "index"])->name("index");
                    Route::post("store", [DetailJobFormOrderController::class, "store"])->name("store");
                    Route::post("update-status", [DetailJobFormOrderController::class, "updateStatus"])->name("updateStatus");
                    Route::post("updateHarga", [DetailJobFormOrderController::class, "updateHarga"])->name("updateHarga");
                });

            Route::prefix("ops")
                ->name("ops.")
                ->group(function () {
                    Route::resource("data", OperasionalController::class);
                });

            Route::prefix("notaris")
                ->name("notaris.")
                ->group(function () {
                    Route::resource("data", NotarisController::class);
                    Route::post("simpanNomorPPAT", [NotarisController::class, 'simpanNomorPPAT'])->name("simpanNomorPPAT");
                });

            Route::prefix("pajak")
                ->name("pajak.")
                ->group(function () {
                    Route::resource("data", DataPajakController::class);
                });

            Route::prefix("akta")
                ->name("akta.")
                ->group(function () {
                    Route::resource("data", DataAktaController::class);
                    Route::get("filter-data/{tipe}", [DataAktaController::class, 'index'])->name("data.filter");
                });

            Route::resource("penambahan-item", PenambahanItemController::class);
            Route::resource("pnbp", PnbpController::class);
            Route::put(
                'pnbp/{id}/assign',
                [PnbpController::class, 'assign']
            )->name('pnbp.assign');

            Route::put(
                'pnbp/{id}/input-va',
                [PnbpController::class, 'inputVa']
            )->name('pnbp.input-va');

            Route::put(
                'pnbp/{id}/payment',
                [PnbpController::class, 'payment']
            )->name('pnbp.payment');
            Route::post("penambahan-item-updateStatus", [PenambahanItemController::class, "updateStatus"])->name("penambahan-item-updateStatus");



            Route::prefix("waarmerking")
                ->name("waarmerking.")
                ->group(function () {
                    Route::resource("data", WaarmerkingController::class);
                });
            Route::prefix("surat-keluar")
                ->name("surat-keluar.")
                ->group(function () {
                    Route::resource("data", SuratKeluarController::class);
                });
            Route::prefix("wasiat")
                ->name("wasiat.")
                ->group(function () {
                    Route::resource("data", WasiatController::class);
                });
        });
    Route::prefix("finance")
        ->name("finance.")
        ->group(function () {
            Route::get("reports/jurnal", [FinanceReportController::class, "jurnal"])->name("reports.jurnal");
            Route::get("reports/neraca", [FinanceReportController::class, "neraca"])->name("reports.neraca");
            Route::get("reports/laba-kotor", [FinanceReportController::class, "labaKotor"])->name("reports.laba-kotor");
            Route::get("reports/laba-bersih", [FinanceReportController::class, "labaBersih"])->name("reports.laba-bersih");
            Route::resource("job-divisi", FinanceJobDivisiController::class);
        });





    Route::resource("debitur", DebiturController::class);

    Route::prefix("pdf")
        ->name("pdf.")
        ->group(function () {
            Route::get("invoice/preview/{id}", [InvoiceController::class, "preview"])->name("invoice");
            Route::get("invoice/print/{id}/{kategori}", [InvoiceController::class, "invPenjual"])->name("invoice.print");
            Route::get("invoice/print-bank/{id}/{kategori}", [InvoiceController::class, "printInvoiceBank"])->name("invoice-bank.print");
            Route::get("invoice/print-umum/{id}/{kategori}", [InvoiceController::class, "printInvUmum"])->name("invoice-umum.print");
        });

    Route::prefix("hris")
        ->name("hris.")
        ->group(function () {
            Route::resource("cuti", CutiController::class);
            Route::resource("lembur", LemburController::class);
        });

    Route::prefix("arsip")
        ->name("arsip.")
        ->group(function () {
            Route::resource("bundle", BundleController::class);
            Route::resource("warkah", ArsipWarkahController::class)->only(["index"]);
        });

    Route::prefix("setting")
        ->name("setting.")
        ->group(function () {
            Route::resource("perusahaan", PerusahaanController::class);
            Route::resource("step-ops", StepOpsController::class);
            Route::get("wa", [WaController::class, "index"])->name("wa.index");
        });
});
Route::prefix('wilayah')->group(function () {
    // Route::get('/provinsi', [SearchWilayahController::class, 'cariProvinsi']);
    // Route::get('/kota', [SearchWilayahController::class, 'cariKota']);
    // Route::get('/kecamatan', [SearchWilayahController::class, 'cariKecamatan']);
    // Route::get('/desa', [SearchWilayahController::class, 'cariDesa']);
    Route::post('/desa', [SearchWilayahController::class, 'cariDesa'])->name('desa.cari');
});

Route::get('/notifikasi/{id}/read', function ($id) {

    $notif = \App\Models\Notifikasi::findOrFail($id);

    if ($notif->user_id == auth()->id()) {
        $notif->update(['is_read' => 1]);
    }

    return redirect($notif->link);
})->name('notifikasi.read');



// Route::get("logout", [AuthController::class, "logout"])->name("logout");

require __DIR__ . "/laporan.php";
require __DIR__ . "/route_import.php";
