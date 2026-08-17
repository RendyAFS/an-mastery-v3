<?php

return [
    'description' => 'Kelola data memo pengeluaran karyawan',
    'filter' => [
        'date_range' => 'Rentang Tanggal',
        'reset'      => 'Reset',
    ],
    'fields' => [
        'employee'     => 'Karyawan',
        'items_count'  => 'Jumlah Item',
        'total'        => 'Total Nominal',
        'status'       => 'Status',
        'date'         => 'Tanggal',
        'item_details' => 'Detail Item',
        'item_name'    => 'Nama Item',
        'nominal'      => 'Nominal',
        'add_item'     => 'Tambah Item',
        'is_paid'      => 'Sudah Dibayar',
    ],
    'placeholders' => [
        'employee'        => 'Pilih Karyawan...',
        'search_employee' => 'Cari karyawan...',
        'item_name'       => 'Nama pengeluaran...',
    ],
    'status' => [
        'paid'   => 'Sudah Dibayar',
        'unpaid' => 'Belum Dibayar',
    ],
    'validation' => [
        'employee_id' => [
            'required' => 'Karyawan wajib dipilih.',
            'exists'   => 'Karyawan yang dipilih tidak valid.',
        ],
        'data' => [
            'required' => 'Detail item memo wajib diisi.',
            'min'      => 'Minimal satu item memo wajib diisi.',
        ],
        'data_name' => [
            'required' => 'Nama item wajib diisi.',
        ],
        'data_date' => [
            'required' => 'Tanggal item wajib diisi.',
        ],
    ],

    'quick_items' => [
        'Kirim Kain',
        'Ambil Kain',
    ],
];
