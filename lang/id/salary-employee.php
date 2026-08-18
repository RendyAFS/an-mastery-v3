<?php

return [
    'description' => 'Rekap fee karyawan per minggu',
    'filter' => [
        'date_range' => 'Rentang Tanggal',
        'reset' => 'Reset',
        'all' => 'Semua',
    ],
    'sync' => [
        'button'              => 'Sync Data',
        'loading'             => 'Syncing...',
        'select_week_warning' => 'Pilih rentang minggu terlebih dahulu',
        'synced_success'      => 'Berhasil menyinkronkan :count data gaji karyawan.',
        'confirm_title'       => 'Sinkronkan Data Gaji?',
        'confirm_message'     => 'Ini akan menghitung ulang & mengunci data sablon, bon, dan memo yang sudah memenuhi syarat ke gaji karyawan untuk minggu yang dipilih. Data gaji yang sudah berstatus "Sudah Dibayar" akan otomatis dibuka kembali menjadi "Belum Dibayar" jika ada data baru yang masuk.',
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
        'locked_hint' => 'Tidak dapat mengedit gaji  yang sudah dibayar ubah ke Belum Dibayar terlebih dahulu',
    ],
    'card' => [
        'no_sablon_data' => 'Belum ada data sablon',
        'additional_fee' => 'Biaya Tambahan',
        'presence' => 'Absensi',
        'total' => 'Total',
        'manage' => 'Kelola',
        'bon_label' => 'Bon',
        'bon_advance_label' => 'Bon Diambil',
        'bon_settlement_label' => 'Sisa Bon',
    ],
];
