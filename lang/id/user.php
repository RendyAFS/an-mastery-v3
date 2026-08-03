<?php
return [
    'description'                   => 'Kelola data pengguna',
    'fields'                        => [
        'name'                   => 'Nama',
        'email'                  => 'Email',
        'password'               => 'Password',
        'password_confirmation'  => 'Konfirmasi Password',
        'role'                   => 'Role',
        'roles'                  => 'Role',
    ],
    'password_placeholder'              => 'Masukkan password',
    'password_confirmation_placeholder' => 'Konfirmasi password',
    'password_hint'                     => 'Kosongkan jika tidak ingin mengubah password',
    'role_placeholder'                  => 'Pilih Role',
    'role_search_placeholder'           => 'Cari role...',
    'toggle_active_confirm_title'       => 'Konfirmasi',
    'toggle_active_confirm_message'     => 'Apakah kamu yakin ingin mengubah status pengguna ini?',
    'toggle_active_success'             => 'Status pengguna berhasil diperbarui',
    'toggle_active_error'               => 'Gagal memperbarui status pengguna',
];
