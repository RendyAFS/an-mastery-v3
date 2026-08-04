<?php
return [
    'description'  => 'Kelola data warna kain',
    'fields'       => [
        'name'       => 'Nama',
        'code_color' => 'Kode Warna',
        'notes'      => 'Catatan',
    ],
    'fetch_error'  => 'Gagal mengambil data warna kain',
    'validation' => [
        'name' => [
            'required' => 'Nama wajib diisi.',
        ],

        'code_color' => [
            'required' => 'Kode warna wajib diisi.',
        ],

        'notes' => [
            'string' => 'Catatan harus berupa teks.',
        ],
    ],
];
