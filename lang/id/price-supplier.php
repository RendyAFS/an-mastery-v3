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
];
