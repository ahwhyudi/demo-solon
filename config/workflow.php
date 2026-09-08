<?php

return [
    'akta' => [
        'default' => [
            'notaris' => [
                ['name' => 'Penugasan'],
                ['name' => 'Draft'],
                ['name' => 'Salinan'],
                ['name' => 'Minuta'],
                ['name' => 'Menunggu ttd Notaris'],
            ],

            'legalisasi' => [
                ['name' => 'Penugasan'],
                ['name' => 'Draft'],
            ],
        ],

        'debra' => [
            'notaris' => [
                [
                    'name' => 'Penugasan Draft',
                    'penugasan' => true,
                ],
                ['name' => 'Draft'],

                // [
                //     'name' => 'Penugasan Renvoi Minuta Akta',
                //     'penugasan' => true,
                // ],
                // ['name' => 'Renvoi Minuta Akta'],

                [
                    'name' => 'Penugasan Salinan',
                    'penugasan' => true,
                ],
                ['name' => 'Salinan'],
                [
                    'name' => 'Penugasan Minuta',
                    'penugasan' => true,
                ],

                ['name' => 'Minuta'],
                ['name' => 'Menunggu ttd Notaris'],
                // ['name' => 'Selesai Menunggu ttd Notaris'],
                ['name' => 'Selesai'],
            ],

            'legalisasi' => [
                ['name' => 'Penugasan Draft'],
                ['name' => 'Draft'],
            ],
        ],
    ],
];