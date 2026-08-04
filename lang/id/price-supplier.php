<?php
return [
    'description'          => 'Kelola data harga supplier',
    'fields'               => [
        'supplier'    => 'Konveksi',
        'type_fabric' => 'Jenis Kain',
        'type_color'  => 'Jenis Warna',
        'price'       => 'Harga',
        'notes'       => 'Catatan',
    ],
    'placeholders'         => [
        'supplier'    => 'Pilih Konveksi',
        'type_fabric' => 'Pilih Jenis Kain',
        'type_color'  => 'Pilih Jenis Warna',
    ],
    'search_placeholders'  => [
        'supplier'    => 'Cari supplier...',
        'type_fabric' => 'Cari jenis kain...',
        'type_color'  => 'Cari jenis warna...',
    ],
    'type_color_suffix'    => 'Warna',
    'fetch_error'          => 'Gagal mengambil data harga supplier',
    'validation'           => [
        'supplier_required'    => 'Konveksi wajib diisi.',
        'supplier_exists'      => 'Pilih Konveksi yang valid.',
        'combination_unique'   => 'Kombinasi Konveksi, Jenis Kain, dan Jenis Warna sudah ada.',
        'type_fabric_required' => 'Jenis Kain wajib diisi.',
        'type_fabric_exists'   => 'Pilih Jenis Kain yang valid.',
        'type_color_required'  => 'Jenis Warna wajib diisi.',
        'type_color_exists'    => 'Pilih Jenis Warna yang valid.',
        'price_required'       => 'Harga wajib diisi.',
        'price_numeric'        => 'Harga harus berupa angka.',
        'notes_string'         => 'Catatan harus berupa teks.',
    ],
];
