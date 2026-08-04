<?php
return [
    'description'                    => 'Kelola data karyawan',
    'fields'                         => [
        'name'    => 'Nama',
        'address' => 'Alamat',
        'contact' => 'Kontak',
        'notes'   => 'Catatan',
    ],
    'toggle_active_confirm_title'    => 'Konfirmasi',
    'toggle_active_confirm_message'  => 'Apakah kamu yakin ingin mengubah status karyawan ini?',
    'toggle_active_success'          => 'Status karyawan berhasil diperbarui',
    'toggle_active_error'            => 'Gagal memperbarui status karyawan',
    'fetch_error'                    => 'Gagal mengambil data karyawan',
    'validation' => [
        'name' => [
            'required' => 'Nama wajib diisi.',
        ],

        'address' => [
            'required' => 'Alamat wajib diisi.',
        ],

        'contact' => [
            'string' => 'Kontak harus berupa teks.',
        ],

        'notes' => [
            'string' => 'Catatan harus berupa teks.',
        ],
    ],
];
