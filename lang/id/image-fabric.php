<?php
return [
    'description' => 'Kelola data gambar kain',
    'fields'      => [
        'image' => 'Gambar',
        'name'  => 'Nama',
        'notes' => 'Catatan',
    ],
    'name_placeholder'  => 'Masukkan nama',
    'notes_placeholder' => 'Masukkan catatan',
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
