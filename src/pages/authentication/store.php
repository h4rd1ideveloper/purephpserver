<?php

use App\lib\Helpers;
use App\lib\Components;

$baseUrl = Helpers::baseURL();
[
    'error' => $isError,
    'fields' => $target
] = (isset($context) && isset($context['error']) && isset($context['fields'])) ?
    $context :
    ['error' => false, 'fields' => ['-1']];
$classFeedBack = ($isError) ? 'is-invalid' : 'is-valid';
$classResolver = fn (string $username): string => in_array($username, $target) ? $classFeedBack : 'border';
const login_input = [
    [
        'username',
        true,
        null,
        '* 4-20 length',
        'text',
        'username',
        'col-12  mx-auto',
        4,
        20
    ], [
        'password',
        true,
        null,
        '* 8-20 length',
        'password',
        'password',
        'col-12  mx-auto ',
        8,
        20
    ]
];
const sign_input = [
    [
        'username',
        true,
        null,
        '* 4-20 length',
        'text',
        'username',
        'col-12 col-md-6 col-lg-6',
        4,
        20
    ], [
        'first_name',
        false,
        null,
        'Real first name',
        'text',
        'first name',
        'col-12 col-md-6 col-lg-6',
        0,
        30
    ], [
        'last_name',
        false,
        null,
        'Real last name',
        'text',
        'last name',
        'col-12 col-md-6 col-lg-6',
        0,
        30
    ], [
        'email',
        false,
        null,
        'example@email.com',
        'email',
        'E-mail',
        'col-12 col-md-6 col-lg-6',
        7,
        100
    ], [
        'tel',
        false,
        null,
        'only numbers',
        'tel',
        'Cellphone number',
        'col-12 col-md-6 col-lg-6',
        7,
        15
    ], [
        'password',
        true,
        null,
        '* 8-20 length',
        'password',
        'password',
        'col-12 col-md-6 col-lg-6',
        10,
        20
    ]
];
$login_fields = Helpers::Reducer(
    login_input,
    fn ($initialValue, $login_input_config, $key) => $initialValue . Components::input(
        $login_input_config[0],
        $login_input_config[1] ?? false,
        $login_input_config[2] ?? null,
        $login_input_config[3] ?? null,
        $login_input_config[4] ?? null,
        $login_input_config[5] ?? null,
        $classResolver($login_input_config[0]),
        $login_input_config[6] ?? null,
        $login_input_config[7] ?? null,
        $login_input_config[8] ?? null
    ),
    PHP_EOL
);
$sign_fields = Helpers::Reducer(
    sign_input,
    fn ($initialValue, $sign_input_config, $key) => $initialValue . Components::input(
        $sign_input_config[0],
        $sign_input_config[1] ?? false,
        $sign_input_config[2] ?? null,
        $sign_input_config[3] ?? null,
        $sign_input_config[4] ?? null,
        $sign_input_config[5] ?? null,
        $classResolver($sign_input_config[0]),
        $sign_input_config[6] ?? null,
        $sign_input_config[7] ?? null,
        $sign_input_config[8] ?? null
    ),
    PHP_EOL
);
