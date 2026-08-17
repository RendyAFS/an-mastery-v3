<?php

return [
    'title'       => 'Dashboard',
    'description' => 'Ringkasan data bisnis kamu',
    'filter'      => [
        'date_range' => 'Rentang Tanggal',
        'reset'      => 'Reset',
    ],
    'stats' => [
        'employees'      => 'Total Karyawan',
        'suppliers'      => 'Total Konveksi',
        'fabric_stock'   => 'Stok Kain',
        'bill_unpaid'    => 'Tagihan Belum Bayar',
        'salary_pending' => 'Gaji Pending',
    ],
    'chart' => [
        'status_sablon'  => 'Status Sablon',
        'sablon_per_day' => 'Sablon per Hari',
        'top_supplier'   => 'Konveksi Teratas',
        'total_sablon'   => 'Total Sablon',
    ],
    'presence' => [
        'title'       => 'Absensi Karyawan Minggu Ini',
        'no_presence' => 'Belum ada data absensi',
    ],
    'table' => [
        'title'        => 'Sablon Terbaru',
        'supplier'     => 'Konveksi',
        'image_fabric' => 'Gambar Kain',
        'date'         => 'Tanggal',
        'status'       => 'Status',
        'total'        => 'Total',
        'type_fabric' => 'Jenis Kain',
        'type_color'  => 'Jenis Warna',
    ],
    'fabric_table' => [
        'title' => 'Ringkasan Stok Kain',
    ],
    'clock' => [
        'today' => 'Hari ini',
    ],
    'no_data' => 'Belum ada data',
];
