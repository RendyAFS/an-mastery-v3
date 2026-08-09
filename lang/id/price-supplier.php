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
    'validation' => [
        'supplier_id' => [
            'required' => 'Konveksi wajib dipilih.',
            'exists'   => 'Konveksi yang dipilih tidak valid.',
            'unique'   => 'Kombinasi supplier, jenis kain, dan jenis warna sudah digunakan.',
        ],

        'type_fabric_id' => [
            'required' => 'Jenis kain wajib dipilih.',
            'exists'   => 'Jenis kain yang dipilih tidak valid.',
        ],

        'type_color_id' => [
            'required' => 'Jenis warna wajib dipilih.',
            'exists'   => 'Jenis warna yang dipilih tidak valid.',
        ],

        'price' => [
            'required' => 'Harga wajib diisi.',
            'numeric'  => 'Harga harus berupa angka.',
        ],

        'notes' => [
            'string' => 'Catatan harus berupa teks.',
        ],
    ],
];
