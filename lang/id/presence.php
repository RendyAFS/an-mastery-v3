<?php
return [
    'description'              => 'Kelola data kehadiran karyawan mingguan',
    'filter_week_label'        => 'Minggu Ke',
    'generate_button'          => 'Generate Kehadiran',
    'modal_title_prefix'       => 'Kehadiran',
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
        'title'              => 'Generate Kehadiran',
        'week_of'            => 'Minggu Ke',
        'nominal_per_day'     => 'Nominal per hari',
        'employees'          => 'Karyawan',
        'select_all'         => 'Pilih semua',
        'no_employees_found' => 'Karyawan tidak ditemukan',
        'select_week_error'  => 'Silakan pilih minggu',
        'select_employee_error' => 'Silakan pilih minimal satu karyawan',
        'generated_success'  => 'Kehadiran berhasil di-generate',
    ],
    'updated_success'          => 'Kehadiran berhasil diperbarui',
    'fetch_error'              => 'Gagal mengambil data kehadiran',
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
            'integer' => 'Jumlah kehadiran harus berupa angka.',
            'min'     => 'Jumlah kehadiran minimal 0.',
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
