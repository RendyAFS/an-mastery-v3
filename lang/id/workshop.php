<?php

return [
    'description' => 'Kelola data workshop / cabang produksi',
    'fields'      => [
        'image'       => 'Foto Workshop',
        'name'        => 'Nama Workshop',
        'location'    => 'Lokasi / Alamat',
        'is_active'   => 'Status Aktif',
        'users_count' => 'Jumlah Pengguna',
    ],
    'form' => [
        'name'         => 'Nama Workshop',
        'location'     => 'Lokasi / Alamat',
        'is_active'    => 'Status Aktif',
        'image'        => 'Foto',
        'remove_image' => 'Hapus Foto',
    ],
    'name_placeholder'      => 'Masukkan nama workshop',
    'location_placeholder'  => 'Masukkan lokasi atau alamat workshop',
    'switch_workshop'       => 'Ganti Workshop',
    'select_workshop'       => 'Pilih Workshop',
    'no_workshop'           => 'Belum Pilih Workshop',
    'current_workshop'      => 'Workshop Saat Ini',
    'without_workshop'      => 'Tanpa Workshop',
    'switched_successfully' => 'Workshop berhasil diganti.',
    'choose_workshop_desc'  => 'Pilih cabang workshop untuk bekerja.',
    'must_select_workshop'  => 'Silakan pilih workshop terlebih dahulu untuk melanjutkan.',
    'cancel'                => 'Batal',
    'validation' => [
        'name' => [
            'required' => 'Nama workshop wajib diisi.',
            'string'   => 'Nama workshop harus berupa teks.',
            'max'      => 'Nama workshop maksimal 255 karakter.',
        ],
        'location' => [
            'string' => 'Lokasi harus berupa teks.',
        ],
    ],
];
