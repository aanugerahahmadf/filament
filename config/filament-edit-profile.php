<?php

return [
    'show_custom_fields' => true,
    'locale_column' => 'locale',
    'theme_color_column' => 'theme_color',
    'avatar_column' => 'avatar_url',
    'disk' => env('FILESYSTEM_DISK', 'public'),
    'visibility' => 'public', // or replace by filesystem disk visibility with fallback value
    'custom_fields' => [
        'username' => [
            'type' => 'text',
            'label' => 'Username',
            'placeholder' => 'Enter your username',
            'required' => true,
            'rules' => ['required', 'string', 'max:255'],
            'column_span' => 'full',
        ],
        'phone_number' => [
            'type' => 'text',
            'label' => 'No. Handphone / WhatsApp',
            'placeholder' => 'Enter your phone number',
            'required' => false,
            'rules' => ['nullable', 'string', 'max:20'],
            'column_span' => 'full',
        ],
        'city' => [
            'type' => 'text',
            'label' => 'Kota',
            'placeholder' => 'Enter your city',
            'required' => false,
            'rules' => ['nullable', 'string', 'max:100'],
            'column_span' => 'full',
        ],
        'address' => [
            'type' => 'textarea',
            'label' => 'Alamat',
            'placeholder' => 'Enter your complete address',
            'required' => false,
            'rules' => ['nullable', 'string', 'max:500'],
            'column_span' => 'full',
            'rows' => 3,
        ],
        'date_of_birth' => [
            'type' => 'datetime',
            'label' => 'Tanggal Lahir',
            'placeholder' => 'Select your date of birth',
            'required' => false,
            'rules' => ['nullable', 'date'],
            'column_span' => 'full',
            'time' => false,
            'format' => 'Y-m-d',
        ],
    ]
];
