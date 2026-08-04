<?php
return [
    'description'  => 'Kelola data jenis kain',
    'fields'       => [
        'name'  => 'Nama',
        'notes' => 'Catatan',
    ],
    'fetch_error'  => 'Gagal mengambil data jenis kain',
    'validation' => [
        'name' => [
            'required' => 'Nama wajib diisi.',
            'string'   => 'Nama harus berupa teks.',
            'max'      => 'Nama maksimal 255 karakter.',
        ],

        'notes' => [
            'string' => 'Catatan harus berupa teks.',
        ],
    ],
];
