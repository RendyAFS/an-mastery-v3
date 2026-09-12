<?php
return [
    'description'  => 'Kelola inventaris Kain',
    'create_title' => 'Tambah inventaris Kain',
    'edit_title'   => 'Ubah inventaris Kain',
    'filter'       => [
        'supplier'         => 'Konveksi',
        'all_suppliers'    => 'Semua Konveksi',
        'type_fabric'      => 'Jenis Kain',
        'all_type_fabrics' => 'Semua Jenis Kain',
        'date_range'       => 'Rentang Tanggal',
        'reset'            => 'Reset',
    ],
    'card'         => [
        'incoming' => 'Masuk',
        'deleted'  => 'Terhapus',
        'restore'  => 'Pulihkan',
        'delete'   => 'Hapus',
        'edit'     => 'Ubah',
    ],
    'fields'             => [
        'supplier'    => 'Konveksi',
        'code'        => 'Kode',
        'type_seri'   => 'Jenis Seri',
        'stock_total' => 'Total Stok',
        'notes'       => 'Catatan',
        'type_fabric' => 'Jenis Kain',
        'total_stock' => 'Total Stok',
        'date_coming' => 'Tanggal Masuk',
    ],
    'placeholders'        => [
        'supplier'    => 'Pilih Konveksi',
        'type_fabric' => 'Pilih Jenis Kain',
        'color'       => 'Pilih Warna',
    ],
    'search_placeholders' => [
        'supplier'    => 'Cari supplier...',
        'type_fabric' => 'Cari jenis kain...',
        'color'       => 'Cari warna...',
    ],
    'hints'              => [
        'total_stock' => 'Otomatis terhitung dari total stock Fabric Detail',
        'code'        => 'Code dibuat otomatis dari Konveksi dan timestamp',
    ],
    'clear_selection'        => 'Hapus pilihan',
    'color_already_selected' => 'Warna sudah dipilih',
    'no_color_found'         => 'Warna tidak ditemukan',
    'detail'             => [
        'title'       => 'Detail Kain (Warna & Stok)',
        'add'         => 'Tambah Detail',
        'no'          => 'No',
        'color'       => 'Warna Kain',
        'stock'       => 'Stok',
        'notes'       => 'Catatan',
        'action'      => 'Aksi',
    ],
    'incoming_label'               => 'Masuk',
    'no_sablon_yet'                => 'Belum ada sablon',
    'delete_confirm_message'       => 'Apakah kamu yakin ingin menghapus kain ini? Tindakan ini tidak dapat dibatalkan.',
    'restore_confirm_message'      => 'Pulihkan kain ini?',
    'force_delete_confirm_message' => 'Ini akan menghapus kain secara permanen. Lanjutkan?',
    'validation' => [
        'supplier_id' => [
            'required' => 'Konveksi wajib dipilih.',
            'exists'   => 'Konveksi tidak ditemukan.',
        ],

        'type_fabric_id' => [
            'required' => 'Jenis kain wajib dipilih.',
            'exists'   => 'Jenis kain tidak ditemukan.',
        ],

        'date_coming' => [
            'required' => 'Tanggal datang wajib diisi.',
            'date'     => 'Format tanggal datang tidak valid.',
        ],

        'seri' => [
            'required' => 'Nomor seri wajib diisi.',
            'integer'  => 'Nomor seri harus berupa angka.',
            'min'      => 'Nomor seri minimal 1.',
        ],

        'notes' => [
            'max' => 'Catatan maksimal 255 karakter.',
        ],

        'fabric_details' => [
            'required' => 'Minimal satu detail kain harus ditambahkan.',

            'color_fabric_id' => [
                'required' => 'Warna kain wajib dipilih.',
                'exists'   => 'Warna kain tidak ditemukan.',
                'distinct' => 'Warna kain tidak boleh sama antar detail.',
            ],

            'stock' => [
                'required' => 'Stok wajib diisi.',
                'numeric'  => 'Stok harus berupa angka.',
                'min'      => 'Stok minimal 0.',
            ],
        ],
    ],
];
