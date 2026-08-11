<?php
return [
    'description'                    => 'Kelola data konveksi',
    'fields'                         => [
        'name'    => 'Nama',
        'address' => 'Alamat',
        'contact' => 'Kontak',
        'notes'   => 'Catatan',
    ],
    'toggle_active_confirm_title'    => 'Konfirmasi',
    'toggle_active_confirm_message'  => 'Apakah kamu yakin ingin mengubah status konveksi ini?',
    'toggle_active_success'          => 'Status konveksi berhasil diperbarui',
    'toggle_active_error'            => 'Gagal memperbarui status konveksi',
    'cover_style_saved'              => 'Gaya sampul berhasil disimpan',
    'fetch_error'                    => 'Gagal mengambil data konveksi',
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
    'cover_style' => [
        'color_from' => [
            'required' => 'Warna awal wajib diisi.',
            'regex'    => 'Format warna harus berupa kode HEX, misalnya #6366F1.',
        ],
        'color_to' => [
            'required' => 'Warna akhir wajib diisi.',
            'regex'    => 'Format warna harus berupa kode HEX, misalnya #4338CA.',
        ],
        'icon' => [
            'required' => 'Ikon wajib dipilih.',
            'in'       => 'Ikon yang dipilih tidak tersedia.',
        ],
        'pattern' => [
            'required' => 'Pattern wajib dipilih.',
            'in'       => 'Pattern yang dipilih tidak tersedia.',
        ],
    ],
];
