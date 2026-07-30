<?php



function getAllPermission()
{
    return [
        "lainnya" => ["qc-berkas"],
        "job/divisi" => [
            "job/divisi/list",
            "job/divisi/detail",
            "job/divisi/create",
            "job/divisi/edit",
            "job/divisi/delete",
            "job/divisi/buka-batal",
            "job/divisi/form-order/tambah-item",
            "job/divisi/form-order/edit-item",
            "job/divisi/data-pendukung",
            "job/divisi/dokumen-penyelesaian/create",
            "job/divisi/dokumen-penyelesaian/delete",
        ],
        "job/ops" => [
            "job/ops/list",
            "job/ops/detail",
            "job/ops/create",
            "job/ops/edit",
            "job/ops/delete",
            "job/ops/biaya proses",
            "job/ops/dispo",
            "Berkas Diterima ops",
            "Selesai ops",
            "job/ops/penugasan"
        ],
        "job/notaris" => [
            "job/notaris/list",
            "job/notaris/detail",
            "job/notaris/create",
            "job/notaris/edit",
            "job/notaris/delete",
            "job/akta/penugasan"
        ],
        "job/ppat" => [
            "job/ppat/list",
            "job/ppat/detail",
            "job/ppat/create",
            "job/ppat/edit",
            "job/ppat/delete",
            "job/akta/penugasan"
        ],
        "job/legalisasi" => [
            "job/legalisasi/list",
            "job/legalisasi/detail",
            "job/legalisasi/create",
            "job/legalisasi/edit",
            "job/legalisasi/delete"
        ],
        "job/waarmerking" => [
            "job/waarmerking/list",
            "job/waarmerking/detail",
            "job/waarmerking/create",
            "job/waarmerking/edit",
            "job/waarmerking/delete"
        ],
        "job/surat-keluar" => [
            "job/surat-keluar/list",
            "job/surat-keluar/detail",
            "job/surat-keluar/create",
            "job/surat-keluar/edit",
            "job/surat-keluar/delete"
        ],
        "job/wasiat" => [
            "job/wasiat/list",
            "job/wasiat/detail",
            "job/wasiat/create",
            "job/wasiat/edit",
            "job/wasiat/delete"
        ],
        "job/pajak" => [
            "job/pajak/list",
            "job/pajak/detail",
            "job/pajak/create",
            "job/pajak/edit",
            "job/pajak/delete"
        ],
        "job/pnbp" => [
            "job/pnbp/list",
            "job/pnbp/detail",
            "job/pnbp/approve",
            "job/pnbp/penugasan",
            "job/pnbp/input-va",
            "job/pnbp/payment"
        ],
        "job/penambahan-item" => [
            "job/penambahan-item/list",
            "job/penambahan-item/detail",
            "job/penambahan-item/create",
            "job/penambahan-item/edit",
            "job/penambahan-item/delete"
        ],
        "job/pembatalan-item" => [
            "job/pembatalan-item/list",
            "job/pembatalan-item/detail",
            "job/pembatalan-item/create",
            "job/pembatalan-item/edit",
            "job/pembatalan-item/delete"
        ],
        "finance" => [
            'finance/list',
            'finance/create'
        ],
        "finance/in" => [
            "finance/in/list",
            "finance/in/detail",
            "finance/in/create",
            "finance/in/edit",
            "finance/in/delete"
        ],
        "finance/out" => [
            "finance/out/list",
            "finance/out/detail",
            "finance/out/create",
            "finance/out/edit",
            "finance/out/delete"
        ],
        "finance/jurnal" => [
            "finance/jurnal/list",
            "finance/jurnal/detail",
            "finance/jurnal/create",
            "finance/jurnal/edit",
            "finance/jurnal/delete"
        ],
        "finance/neraca" => [
            "finance/neraca/list",
        ],
        "finance/laba-kotor" => [
            "finance/laba-kotor/list",
        ],
        "finance/laba-bersih" => [
            "finance/laba-bersih/list",
        ],

        "Menu Arsip" => [
            "arsip/list"
        ],
        "arsip/akta" => [
            "arsip/akta/list",
            "arsip/akta/create",
            "arsip/akta/edit",
            "arsip/akta/delete",
        ],
        "arsip/ppat" => [
            "arsip/ppat/list",
            "arsip/ppat/create",
            "arsip/ppat/edit",
            "arsip/ppat/delete",
        ],
        "arsip/warkah" => [
            "arsip/warkah/list",
            "arsip/warkah/create",
            "arsip/warkah/edit",
            "arsip/warkah/delete",
        ],

        "akses" => [
            'akses/list'
        ],
        "akses/role" => [
            "akses/role/list",
            "akses/role/detail",
            "akses/role/create",
            "akses/role/edit",
            "akses/role/delete"
        ],
        "akses/user" => [
            "akses/user/list",
            "akses/user/detail",
            "akses/user/create",
            "akses/user/edit",
            "akses/user/delete"
        ],
        "master-data" => [
            "master-data/list",
        ],
        "berkas-bermasalah" => [
            'berkas-bermasalah/list'
        ],
        "berkas-bermasalah/freeze" => [
            'berkas-bermasalah/freeze/list',
            'berkas-bermasalah/freeze/detail',
            'berkas-bermasalah/freeze/setuju',
            'berkas-bermasalah/freeze/tolak',
            'berkas-bermasalah/freeze/buka',
        ],
        "berkas-bermasalah/dispo" => [
            'berkas-bermasalah/dispo/list',
            'berkas-bermasalah/dispo/buka',
        ],
        "berkas-bermasalah/pending" => [
            'berkas-bermasalah/pending/list',
            'berkas-bermasalah/pending/detail',
            'berkas-bermasalah/pending/buka',
        ],
        "hris" => [
            "hris/list",
            "hris/semua-karyawan"
        ],
        "hris/cuti" => [
            "hris/cuti/list",
            "hris/cuti/detail",
            "hris/cuti/create",
            "hris/cuti/edit",
            "hris/cuti/delete",
        ],
        "hris/lembur" => [
            "hris/lembur/list",
            "hris/lembur/detail",
            "hris/lembur/create",
            "hris/lembur/edit",
            "hris/lembur/delete",
        ],
        'laporan' => [
            'laporan/list'
        ],
        'laporan/nomor' => [
            'laporan/nomor/list',
        ],
        'laporan/notaris' => [
            'laporan/notaris/list',
        ],
        'laporan/legalisasi' => [
            'laporan/legalisasi/list',
        ],
        'laporan/ppat' => [
            'laporan/ppat/list',
        ],
        'laporan/waarmerking' => [
            'laporan/waarmerking/list',
        ],
        'laporan/surat-keluar' => [
            'laporan/surat-keluar/list',
        ],
        'laporan/wasiat' => [
            'laporan/wasiat/list',
        ],
        'data-pendukung/debitur' => [
            'data-pendukung/debitur/create',
            'data-pendukung/debitur/view',
            'data-pendukung/debitur/edit',
            'data-pendukung/debitur/add',
            'data-pendukung/debitur/delete',
        ],
        'data-pendukung/objek' => [
            'data-pendukung/objek/create',
            'data-pendukung/objek/view',
            'data-pendukung/objek/edit',
            'data-pendukung/objek/add',
            'data-pendukung/objek/delete',
        ],
        'data-pendukung/badan-hukum' => [
            'data-pendukung/badan-hukum/create',
            'data-pendukung/badan-hukum/view',
            'data-pendukung/badan-hukum/edit',
            'data-pendukung/badan-hukum/delete',
        ],
        'data-pendukung/badan-usaha-pembeli' => [
            'data-pendukung/badan-usaha-pembeli/create',
            'data-pendukung/badan-usaha-pembeli/view',
            'data-pendukung/badan-usaha-pembeli/edit',
            'data-pendukung/badan-usaha-pembeli/delete',
        ],
        'data-pendukung/badan-usaha-penjual' => [
            'data-pendukung/badan-usaha-penjual/create',
            'data-pendukung/badan-usaha-penjual/view',
            'data-pendukung/badan-usaha-penjual/edit',
            'data-pendukung/badan-usaha-penjual/delete',
        ],
        'data-pendukung/bank' => [
            'data-pendukung/bank/create',
            'data-pendukung/bank/view',
            'data-pendukung/bank/edit',
            'data-pendukung/bank/delete',
        ],
        'data-pendukung/developer' => [
            'data-pendukung/developer/create',
            'data-pendukung/developer/view',
            'data-pendukung/developer/edit',
            'data-pendukung/developer/delete',
        ],
        'data-pendukung/broker' => [
            'data-pendukung/broker/create',
            'data-pendukung/broker/view',
            'data-pendukung/broker/edit',
            'data-pendukung/broker/delete',
        ],
        'data-pendukung/pendirian-lembaga' => [
            'data-pendukung/pendirian-lembaga/create',
            'data-pendukung/pendirian-lembaga/view',
            'data-pendukung/pendirian-lembaga/edit',
            'data-pendukung/pendirian-lembaga/delete',
        ],
        'data-pendukung/penjual' => [
            'data-pendukung/penjual/create',
            'data-pendukung/penjual/view',
            'data-pendukung/penjual/edit',
            'data-pendukung/penjual/delete',
        ],
        'data-pendukung/pembeli' => [
            'data-pendukung/pembeli/create',
            'data-pendukung/pembeli/view',
            'data-pendukung/pembeli/edit',
            'data-pendukung/pembeli/delete',
        ],
        'data-pendukung/badan-usaha-debitur' => [
            'data-pendukung/badan-usaha-debitur/create',
            'data-pendukung/badan-usaha-debitur/view',
            'data-pendukung/badan-usaha-debitur/edit',
            'data-pendukung/badan-usaha-debitur/delete',
        ],

    ];
}
