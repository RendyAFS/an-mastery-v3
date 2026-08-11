<?php
return [
    'description'              => 'Kelola data absensi karyawan mingguan',
    'filter_week_label'        => 'Minggu Ke',
    'generate_button'          => 'Generate Absensi',
    'modal_title_prefix'       => 'Absensi',
    'nominal_per_day'          => 'Nominal per hari',
    'generate'                 => 'Generate',
    'notes'                    => 'Catatan',
    'total'                    => 'Total',
    'employee'                 => 'Karyawan',
    'days'                     => [
        'monday'    => 'Senin',
        'tuesday'   => 'Selasa',
        'wednesday' => 'Rabu',
        'thursday'  => 'Kamis',
        'friday'    => 'Jumat',
        'saturday'  => 'Sabtu',
        'sunday'    => 'Minggu',
    ],
    'bulk'                     => [
        'title'                  => 'Generate Absensi',
        'week_of'                => 'Minggu Ke',
        'nominal_per_day'        => 'Nominal per hari',
        'days'                   => 'Hari',
        'select_all_days'        => 'Pilih semua (Senin-Sabtu)',
        'employees'              => 'Karyawan',
        'select_all'             => 'Pilih semua',
        'no_employees_found'     => 'Karyawan tidak ditemukan',
        'select_week_error'      => 'Silakan pilih minggu',
        'select_day_error'       => 'Silakan pilih minimal satu hari',
        'select_employee_error'  => 'Silakan pilih minimal satu karyawan',
        'generated_success'      => 'Absensi berhasil di-generate',
    ],
    'generated_success_count' => 'Absensi berhasil di-generate untuk :count karyawan',
    'updated_success'         => 'Absensi berhasil diperbarui',
    'created_success'         => 'Absensi berhasil dibuat',
    'deleted_employee_error'  => 'Tidak dapat memperbarui absensi karyawan yang terhapus',
    'fetch_error'             => 'Gagal mengambil data absensi',
    'form'                    => [
        'employee'  => 'Karyawan',
        'week_of'   => 'Minggu',
        'monday'    => 'Senin',
        'tuesday'   => 'Selasa',
        'wednesday' => 'Rabu',
        'thursday'  => 'Kamis',
        'friday'    => 'Jumat',
        'saturday'  => 'Sabtu',
        'sunday'    => 'Minggu',
        'notes'     => 'Catatan',
        'amount'    => 'Nominal',
        'employees' => 'Karyawan',
    ],
    'validation' => [
        'employee_id' => [
            'required' => 'Karyawan wajib dipilih.',
            'integer'  => 'Karyawan tidak valid.',
            'exists'   => 'Karyawan tidak ditemukan.',
        ],

        'week_of' => [
            'required' => 'Minggu wajib dipilih.',
            'date'     => 'Format minggu tidak valid.',
        ],

        'days' => [
            'required' => 'Pilih minimal satu hari.',
            'min'      => 'Pilih minimal satu hari.',
            'integer'  => 'Jumlah absensi harus berupa angka.',
            'in'       => 'Hari yang dipilih tidak valid.',
        ],

        'amount' => [
            'required' => 'Nominal per hari wajib diisi.',
            'integer'  => 'Nominal per hari harus berupa angka.',
            'min'      => 'Nominal per hari minimal 0.',
        ],

        'employee_ids' => [
            'required' => 'Pilih minimal satu karyawan.',
            'min'      => 'Pilih minimal satu karyawan.',
            'exists'   => 'Salah satu karyawan yang dipilih tidak ditemukan.',
        ],

        'notes' => [
            'string' => 'Catatan harus berupa teks.',
            'max'    => 'Catatan maksimal 255 karakter.',
        ],
    ],
];
