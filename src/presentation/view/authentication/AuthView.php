<?php

namespace App\presentation\view\authentication;

use App\infra\lib\Helpers;
use App\infra\service\render\Components;

class AuthView extends Components
{
    public const LOGIN_INPUTS_CONFIG = [
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
        ],
        [
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
    public const SIGN_INPUTS = [
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
        ],
        [
            'first_name',
            false,
            null,
            'Real first name',
            'text',
            'first name',
            'col-12 col-md-6 col-lg-6',
            0,
            30
        ],
        [
            'last_name',
            false,
            null,
            'Real last name',
            'text',
            'last name',
            'col-12 col-md-6 col-lg-6',
            0,
            30
        ],
        [
            'email',
            false,
            null,
            'example@email.com',
            'email',
            'E-mail',
            'col-12 col-md-6 col-lg-6',
            7,
            100
        ],
        [
            'tel',
            false,
            null,
            'only numbers',
            'tel',
            'Cellphone number',
            'col-12 col-md-6 col-lg-6',
            7,
            15
        ],
        [
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

    private string $target;
    private string $classFeedBack;
    private array $header_config;

    public function __construct($context, ?string $VIEW_PATH = '')
    {
        parent::__construct($VIEW_PATH);
        $this->target = $context['fields'] ?? '-1';
        $this->classFeedBack = ($context['error'] ?? false) ? 'is-invalid' : 'is-valid';
        $this->header_config = ['title' => 'Login Page', 'stylesheet' => [
            self::baseURL() . environments('path_viewers') . '/css/style.css',
            self::baseURL() . environments('path_viewers') . '/css/background.svg.css',
            'https://bootswatch.com/4/litera/bootstrap.min.css'
        ]];
    }

    public function __toString()
    {
        return Components::headerHTML($this->header_config) .
            Helpers::createStreamFromFile(__DIR__ . '\..\img\background.svg') .
            <<<HTML
        <div id="Authenticate" class="container">
            <div class="row">
                <div id="login" class="col-12 col-sm-10 col-md-8 col-lg-6 mx-auto bg-white p-5 shadow">
                    <fieldset class="mb-5">
                        <legend class="text-capitalize text-center mb-4">
                            <h1>authentication</h1>
                        </legend>
                        <div class="container-fluid p-0">
                            <div class="row">
                            {$this->loginFields()}
                            </div>
                        </div>
                    </fieldset>
                    <div class="d-flex flex-row align-items-center justify-content-between  mx-auto text-white-50">
                        <a class="btn  btn-block btn-primary m-0 mx-1 w-25">Login</a>
                        <a id="toSign" type="button" role="button" class="btn btn-link  btn-block btn-secondary m-0 mx-1  w-25">Sign</a>
                    </div>
                </div>
                <div id="sign" class="col-12 col-sm-10 col-md-8 col-lg-6 mx-auto bg-white p-5 shadow d-none">
                    <fieldset class="mb-5">
                        <legend class="text-capitalize text-center mb-4">
                            <h1>authentication</h1>
                        </legend>
                        <div class="container-fluid p-0">
                            <div class="row">
                            {$this->signFields()}
                            </div>
                        </div>
                    </fieldset>
                    <div class="d-flex flex-row align-items-center justify-content-between mx-auto text-white-50 ">
                        <a class="btn btn-block btn-primary m-0 mx-1 w-25">Sign</a>
                        <a id="toLogin" type="button" role="button" class="btn btn-link  btn-block btn-secondary m-0 mx-1 w-25">Login</a>
                    </div>
                </div>
            </div>
        </div>
        <a class="back-to-top" href="#"><i class="fa fa-chevron-up"></i></a>
    HTML .
            Components::scripts() .
            <<<HTML
        <script type='text/javascript'>
            $('document').ready(() => {
                $('#toLogin,#toSign').on('click', function(e) {
                    e.preventDefault();
                    $('#login,#sign').toggleClass('d-none');
                    let load = document.createElement('div');
                    load.id = 'preloader';
                    document.body.append(load);
                    const preloader = $('#preloader')
                    preloader.length && preloader.delay(80).fadeOut('slow', function() {
                        $(this).remove();
                    });
                });
            });
        </script>
    HTML .
            Components::closeView();
    }

    public function loginFields()
    {
        return self::Reducer(
            self::LOGIN_INPUTS_CONFIG,
            fn($initialValue, $login_input_config, $key) => $initialValue . $this->inputByConfig($login_input_config),
            PHP_EOL
        );
    }

    private function inputByConfig(array $inputConfig): string
    {
        [$name, $required, $label, $labelHelper, $type, $placeholder, $wrapClass, $min, $max] = $inputConfig;
        return self::input($name, $required, $label, $labelHelper, $type, $placeholder, $this->classResolver($name), $wrapClass, $min, $max);
    }

    private function classResolver(string $username): string
    {
        return in_array($username, $this->target, true) ? $this->classFeedBack : 'border';
    }

    private function signFields(): string
    {
        return Helpers::Reducer(
            self::SIGN_INPUTS,
            fn($initialValue, $sign_input_config, $key) => $initialValue . $this->inputByConfig($sign_input_config),
            PHP_EOL
        );
    }
}
