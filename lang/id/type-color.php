<?php
return [
    'description' => 'Kelola data jenis warna',
    'fields'      => [
        'name'  => 'Nama',
        'notes' => 'Catatan',
    ],
    'name_suffix' => 'Warna',
    'fetch_error' => 'Gagal mengambil data jenis warna',
    'validation' => [
        'name' => [
            'required' => 'Jumlah warna wajib diisi.',
            'integer'  => 'Jumlah warna harus berupa angka.',
            'min'      => 'Jumlah warna minimal 1.',
        ],

        'notes' => [
            'string' => 'Catatan harus berupa teks.',
        ],
    ],
];
