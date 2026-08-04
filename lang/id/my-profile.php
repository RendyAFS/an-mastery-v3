<?php

return [

    'title' => [
        'profile_information' => 'Informasi Profil',
        'change_password'     => 'Ubah Password',
    ],

    'form' => [
        'avatar'                => 'Foto Profil',
        'name'                  => 'Nama',
        'email'                 => 'Email',
        'password'              => 'Password Baru',
        'password_confirmation' => 'Konfirmasi Password',

        'placeholder_name'      => 'Masukkan nama',
        'placeholder_email'     => 'Masukkan email',
        'placeholder_password'  => 'Masukkan password',
        'placeholder_password_confirmation'
        => 'Konfirmasi password',
    ],

    'button' => [
        'save_profile'      => 'Simpan Profil',
        'saving_profile'    => 'Menyimpan...',
        'update_password'   => 'Ubah Password',
        'updating_password' => 'Mengubah...',
    ],

    'message' => [
        'profile_updated'  => 'Profil berhasil diperbarui.',
        'password_updated' => 'Password berhasil diperbarui.',
    ],

    'validation' => [
        'name' => [
            'required' => 'Nama wajib diisi.',
            'string'   => 'Nama harus berupa teks.',
            'max'      => 'Nama maksimal 255 karakter.',
        ],

        'email' => [
            'required' => 'Email wajib diisi.',
            'email'    => 'Format email tidak valid.',
            'unique'   => 'Email sudah digunakan.',
        ],
    ],

];
