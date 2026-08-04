<?php

return [
    'description' => 'Rekap fee karyawan per minggu',
    'filter' => [
        'week_start' => 'Dari Minggu',
        'week_end' => 'Sampai Minggu',
        'reset' => 'Reset ke Minggu Ini',
        'all' => 'Semua',
    ],
    'sync' => [
        'button' => 'Sync Data',
        'loading' => 'Syncing...',
        'select_week_warning' => 'Pilih rentang minggu terlebih dahulu',
    ],
    'status' => [
        'PENDING' => 'Belum Dibayar',
        'PAID' => 'Sudah Dibayar',
    ],
    'modal' => [
        'title' => 'Gaji Karyawan',
        'status_label' => 'Status',
        'status_placeholder' => 'Pilih Status',
        'additional_fee_label' => 'Biaya Tambahan',
        'add_fee' => 'Tambah Biaya',
        'nominal' => 'Nominal',
        'notes' => 'Catatan',
        'notes_placeholder' => 'Masukkan Catatan',
    ],
    'card' => [
        'no_sablon_data' => 'Belum ada data sablon',
        'additional_fee' => 'Biaya Tambahan',
        'presence' => 'Absensi',
        'total' => 'Total',
        'manage' => 'Kelola',
    ],
];
