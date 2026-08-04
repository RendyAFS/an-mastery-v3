<?php
return [
    'description'   => 'Kelola data sablon',
    'create_title'  => 'Tambah Sablon',
    'edit_title'    => 'Ubah Sablon',

    'filter'        => [
        'week_start' => 'Dari Minggu',
        'week_end'   => 'Sampai Minggu',
        'reset'      => 'Reset ke Minggu Ini',
    ],

    'statuses'      => [
        'ON_PROGRESS' => 'Sedang Proses',
        'DONE'        => 'Selesai',
        'DELIVERED'   => 'Terkirim',
        'RETURNED'    => 'Dikembalikan',
    ],

    'main_info'     => [
        'title'  => 'Info Utama',
        'fields' => [
            'supplier'             => 'Supplier',
            'fabric'               => 'Kain',
            'image_fabric'         => 'Gambar Kain',
            'type_color'           => 'Jenis Warna',
            'type_fabric'          => 'Jenis Kain',
            'price_employee'       => 'Harga Karyawan',
            'total_long_fabric'    => 'Total Panjang Kain',
            'total_sablon'         => 'Total Sablon',
            'date_sablon'          => 'Tanggal Sablon',
            'status'               => 'Status',
            'is_billed_in_advance' => 'Ditagih Dulu',
            'notes'                => 'Catatan',
        ],
        'placeholders' => [
            'supplier'       => 'Pilih Supplier',
            'fabric'         => 'Pilih Kain',
            'image_fabric'   => 'Pilih Gambar Kain',
            'type_color'     => 'Pilih Jenis Warna',
            'type_fabric'    => 'Pilih Jenis Kain',
            'price_employee' => 'Pilih Harga Karyawan',
            'status'         => 'Pilih Status',
        ],
        'search_placeholders' => [
            'supplier'       => 'Cari supplier...',
            'fabric'         => 'Cari kain...',
            'image_fabric'   => 'Cari gambar kain...',
            'type_color'     => 'Cari jenis warna...',
            'type_fabric'    => 'Cari jenis kain...',
            'price_employee' => 'Cari...',
        ],
        'hint_choose_supplier_first' => 'Pilih Supplier dulu untuk menampilkan Kain',
        'hint_total_long_fabric'     => 'Otomatis dari total Fabric Detail',
    ],

    'fabric_detail' => [
        'title'                    => 'Detail Kain (Warna - Stok)',
        'no'                       => 'No',
        'fabric_detail_label'      => 'Detail Kain (Warna - Stok)',
        'long_fabric'              => 'Panjang Kain',
        'action'                   => 'Aksi',
        'choose_fabric_detail'     => 'Pilih Detail Kain',
        'search_color_placeholder' => 'Cari warna...',
        'no_fabric_detail_found'   => 'Detail kain tidak ditemukan untuk Kain yang dipilih',
        'add_detail'               => 'Tambah Detail',
        'no_fabric_detail_yet'     => 'Belum ada detail kain',
    ],

    'employee_detail' => [
        'title'                    => 'Detail Karyawan (Karyawan & Fee)',
        'employee_number'          => 'Karyawan #:number',
        'employee'                 => 'Karyawan',
        'layers'                   => 'Layer',
        'fee_auto'                 => 'Fee (otomatis)',
        'change_employee'          => 'Ganti Karyawan?',
        'employee_change'          => 'Karyawan Pengganti',
        'is_bon'                   => 'Bon',
        'is_paid'                  => 'Sudah Dibayar',
        'choose_employee'          => 'Pilih Karyawan',
        'search_employee_placeholder' => 'Cari karyawan...',
        'no_employee_found'        => 'Karyawan tidak ditemukan',
        'no_employee_detail_yet'   => 'Belum ada detail karyawan',
        'add_employee'             => 'Tambah Karyawan',
    ],

    'status_modal'  => [
        'title'  => 'Ubah Status',
        'cancel' => 'Batal',
        'save'   => 'Simpan',
    ],

    'card'          => [
        'date'                => 'Tanggal',
        'total_sablon'        => 'Total Sablon',
        'long_fabric'         => 'Panjang Kain',
        'type_color'          => 'Jenis Warna',
        'type_color_suffix'   => 'Warna',
        'fabric_details'      => 'Detail Kain',
        'employee_details'    => 'Detail Karyawan',
        'layer_suffix'        => 'Layer',
        'bon'                 => 'Bon',
        'paid'                => 'Lunas',
        'change_to'           => 'Ganti ke :name',
        'deleted'             => 'Terhapus',
        'restore'             => 'Pulihkan',
        'delete'              => 'Hapus',
    ],

    'delete_confirm_message'       => 'Apakah kamu yakin ingin menghapus Sablon ini?',
    'restore_confirm_message'      => 'Pulihkan Sablon ini?',
    'force_delete_confirm_message' => 'Ini akan menghapus Sablon secara permanen. Lanjutkan?',
    'status_updated_success'       => 'Status berhasil diperbarui',
    'validation' => [
        'supplier_id' => [
            'required' => 'Supplier wajib dipilih.',
            'exists'   => 'Supplier yang dipilih tidak valid.',
        ],

        'fabric_id' => [
            'required' => 'Kain wajib dipilih.',
            'exists'   => 'Kain yang dipilih tidak valid.',
        ],

        'image_fabric_id' => [
            'required' => 'Gambar kain wajib dipilih.',
            'exists'   => 'Gambar kain yang dipilih tidak valid.',
        ],

        'type_color_id' => [
            'required' => 'Jenis warna wajib dipilih.',
            'exists'   => 'Jenis warna yang dipilih tidak valid.',
        ],

        'type_fabric_id' => [
            'required' => 'Jenis kain wajib dipilih.',
            'exists'   => 'Jenis kain yang dipilih tidak valid.',
        ],

        'price_employee_id' => [
            'required' => 'Harga karyawan wajib dipilih.',
            'exists'   => 'Harga karyawan yang dipilih tidak valid.',
        ],

        'total_long_fabric' => [
            'numeric' => 'Total panjang kain harus berupa angka.',
            'min'     => 'Total panjang kain minimal 0.',
        ],

        'total_sablon' => [
            'numeric' => 'Total sablon harus berupa angka.',
            'min'     => 'Total sablon minimal 0.',
        ],

        'date_sablon' => [
            'required' => 'Tanggal sablon wajib diisi.',
            'date'     => 'Format tanggal sablon tidak valid.',
        ],

        'status' => [
            'required' => 'Status wajib dipilih.',
            'in'       => 'Status yang dipilih tidak valid.',
        ],

        'notes' => [
            'string' => 'Catatan harus berupa teks.',
            'max'    => 'Catatan maksimal 255 karakter.',
        ],

        'fabric_details' => [
            'required' => 'Minimal satu detail kain harus ditambahkan.',
            'array'    => 'Format detail kain tidak valid.',
            'min'      => 'Minimal satu detail kain harus ditambahkan.',

            'fabric_detail_id' => [
                'required' => 'Detail kain wajib dipilih.',
                'exists'   => 'Detail kain yang dipilih tidak valid.',
            ],

            'color_fabric_id' => [
                'required' => 'Warna kain wajib dipilih.',
                'exists'   => 'Warna kain yang dipilih tidak valid.',
            ],

            'long_fabric' => [
                'numeric' => 'Panjang kain harus berupa angka.',
                'min'     => 'Panjang kain minimal 0.',
            ],
        ],

        'employee_details' => [
            'array' => 'Format detail karyawan tidak valid.',

            'employee_id' => [
                'required_with' => 'Karyawan wajib dipilih.',
                'exists'        => 'Karyawan yang dipilih tidak valid.',
            ],

            'layers' => [
                'integer' => 'Jumlah layer harus berupa angka.',
                'min'     => 'Jumlah layer minimal 0.',
            ],

            'fee' => [
                'numeric' => 'Fee harus berupa angka.',
                'min'     => 'Fee minimal 0.',
            ],

            'employee_change_id' => [
                'exists'    => 'Karyawan pengganti yang dipilih tidak valid.',
                'different' => 'Karyawan pengganti harus berbeda dengan karyawan utama.',
            ],

            'notes' => [
                'string' => 'Catatan harus berupa teks.',
                'max'    => 'Catatan maksimal 255 karakter.',
            ],
        ],
    ],
];
