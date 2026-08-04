<?php
return [
    'description'          => 'Kelola data harga karyawan',
    'fields'               => [
        'type_fabric' => 'Jenis Kain',
        'type_color'  => 'Jenis Warna',
        'price'       => 'Harga',
        'notes'       => 'Catatan',
    ],
    'placeholders'         => [
        'type_fabric' => 'Pilih Jenis Kain',
        'type_color'  => 'Pilih Jenis Warna',
    ],
    'search_placeholders'  => [
        'type_fabric' => 'Cari jenis kain...',
        'type_color'  => 'Cari jenis warna...',
    ],
    'type_color_suffix'    => 'Warna',
    'fetch_error'          => 'Gagal mengambil data harga karyawan',
    'validation' => [
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
