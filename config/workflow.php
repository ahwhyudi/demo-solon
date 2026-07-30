<?php

return [
    'akta' => [
        'default' => [
            'notaris' => [
                ['name' => 'Penugasan'],
                ['name' => 'Draft'],
                ['name' => 'Salinan'],
                ['name' => 'Menunggu ttd Notaris'],
                ['name' => 'Minuta'],
            ],

            'legalisasi' => [
                ['name' => 'Penugasan'],
                ['name' => 'Draft'],
            ],
        ],

        'debra' => [
            'notaris' => [
                [
                    'name' => 'Penugasan Draft Minuta',
                    "penugasan" => true
                ],

                ['name' => 'Draft Minuta'],

                [
                    'name' => 'QC Draft Minuta',
                    'approval' => [
                        'enabled' => true,
                        'label' => 'Lolos QC Draft',
                    ],
                ],
                [
                    "name" => "Penugasan Renvoi Minuta Akta",
                    "penugasan" => true
                ],
                ['name' => 'Renvoi Minuta Akta'],
                [
                    'name' => 'QC Renvoi Minuta Akta',
                    'approval' => [
                        'enabled' => true,
                        'label' => 'QC Renvoi Minuta Akta',
                    ],
                ],

                ['name' => 'Penugasan Salinan', "penugasan" => true],
                ['name' => 'Salinan'],
                [
                    'name' => 'QC Salinan',
                    'approval' => [
                        'enabled' => true,
                        'label' => 'Lolos QC Salinan',
                    ],
                ],

                ['name' => 'Menunggu ttd Notaris'],
                ['name' => 'Minuta'],
                ['name' => 'Selesai'],
            ],

            'legalisasi' => [
                ['name' => 'Penugasan Draft'],

                ['name' => 'Draft'],

                [
                    'name' => 'QC Draft',
                    'approval' => [
                        'enabled' => true,
                        'label' => 'Lolos QC Draft',
                    ],
                ],
            ],
        ],


    ],
];
