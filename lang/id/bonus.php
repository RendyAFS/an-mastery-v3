<?php

return [
    'description' => 'Kelola data tingkatan bonus gaji karyawan',
    'fields'      => [
        'min'   => 'Minimal Total Sablon',
        'bonus' => 'Nominal Bonus',
        'notes' => 'Catatan',
    ],
    'placeholders' => [
        'min'   => 'Masukkan minimal total sablon',
        'bonus' => 'Masukkan nominal bonus',
        'notes' => 'Masukkan catatan',
    ],
    'fetch_error' => 'Gagal mengambil data bonus',
    'validation'  => [
        'min' => [
            'required' => 'Minimal total sablon wajib diisi.',
            'numeric'  => 'Minimal total sablon harus berupa angka.',
            'min'      => 'Minimal total sablon tidak boleh kurang dari 0.',
        ],
        'bonus' => [
            'required' => 'Nominal bonus wajib diisi.',
            'numeric'  => 'Nominal bonus harus berupa angka.',
            'min'      => 'Nominal bonus tidak boleh kurang dari 0.',
        ],
        'notes' => [
            'string' => 'Catatan harus berupa teks.',
        ],
    ],
];
