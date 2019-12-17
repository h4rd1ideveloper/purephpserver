<?php

use App\view\components\Components;
use Lib\Helpers;

['error' => $isError, 'fields' => $target] = isset($context) && count($context) ? $context : ['error' => false, 'fields' => ['-1']];
$classFeedBack = ($isError) ? 'is-invalid' : 'is-valid';
$classResolver = function (string $username) use ($classFeedBack, $target) {
    return in_array($username, $target) ? $classFeedBack : 'border';
};
?>
    <div id="Authenticate" class="container">
        <div class="row">
            <form id="login" method="post" action="<?= Helpers::baseURL('login') ?>"
                  class="col-12 col-sm-10 col-md-8 col-lg-6 mx-auto bg-white p-5 shadow">
                <fieldset class="mb-5">
                    <legend class="text-capitalize text-center mb-4">
                        <img id="logo"
                             class="mb-2"
                             src="<?= Helpers::baseURL('src/view/assets/img/logo-via-logohub.png'); ?>"
                             alt="" title=""/>
                    </legend>
                    <div class="container-fluid p-0">
                        <div class="row">
                            <?php
                            Components
                                ::input('username', true, null,
                                    '* 4-20 length', 'text', 'username',
                                    $classResolver('username'), 'col-12  mx-auto',
                                    4, 20
                                )
                                ::input('password', true, null,
                                    '* 8-20 length', 'password', 'password',
                                    $classResolver('password'), 'col-12  mx-auto ',
                                    8, 20
                                );
                            ?>
                        </div>
                    </div>
                </fieldset>

                <div class="d-flex flex-row align-items-center justify-content-between  mx-auto text-white-50">
                    <button type="submit" class="btn  btn-block btn-primary m-0 mx-1 w-25">Login</button>
                    <a id="toSign" type="button" role="button"
                       class="btn btn-link  btn-block btn-dark  m-0 mx-1  w-25">Sign</a>
                </div>
            </form>
            <form id="sign" method="post" action="<?= Helpers::baseURL('sign') ?>"
                  class="col-12 col-sm-10 col-md-8 col-lg-6 mx-auto bg-white p-5 shadow d-none">
                <fieldset class="mb-5">
                    <legend class="text-capitalize text-center mb-4">
                        <img id="logo"
                             class="mb-2"
                             src="<?= Helpers::baseURL('src/view/assets/img/logo-via-logohub.png'); ?>"
                             alt="" title=""/>
                    </legend>
                    <div class="container-fluid p-0">
                        <div class="row">
                            <?php
                            Components
                                ::input('username', true, null,
                                    '* 4-20 length', 'text', 'username',
                                    $classResolver('username'), 'col-12 col-md-6 col-lg-6',
                                    4, 20
                                )
                                ::input('first_name', false, null,
                                    'Real first name', 'text', 'first name',
                                    $classResolver('first_name'), 'col-12 col-md-6 col-lg-6',
                                    0, 30
                                )
                                ::input('last_name', false, null,
                                    'Real last name', 'text', 'last name',
                                    $classResolver('last_name'), 'col-12 col-md-6 col-lg-6',
                                    0, 30
                                )
                                ::input('email', false, null,
                                    'example@email.com', 'email', 'E-mail',
                                    $classResolver('email'), 'col-12 col-md-6 col-lg-6',
                                    7, 100
                                )
                                ::input('tel', false, null,
                                    'only numbers', 'tel', 'Cellphone number',
                                    $classResolver('tel'), 'col-12 col-md-6 col-lg-6',
                                    7, 15
                                )
                                ::input('password', true, null,
                                    '* 8-20 length', 'password', 'password',
                                    $classResolver('password'), 'col-12 col-md-6 col-lg-6',
                                    10, 20
                                );
                            ?>
                        </div>
                    </div>
                </fieldset>
                <div class="d-flex flex-row align-items-center justify-content-between mx-auto text-white-50 ">
                    <button type="submit" class="btn btn-block btn-primary m-0 mx-1 w-25">Sign</button>
                    <a id="toLogin" type="button" role="button"
                       class="btn btn-dark btn-block m-0 mx-1 w-25">Login</a>
                </div>
            </form>
        </div>
    </div>
    <div id="preloader"></div>
    <a class="back-to-top" href="#"><i class="fa fa-chevron-up"></i></a>
    <script type="text/javascript">
        $('#toLogin,#toSign').on('click', function (e) {
            e.preventDefault();
            $('#login,#sign').toggleClass('d-none');
            let load = document.createElement('div');
            load.id = 'preloader';
            document.body.append(load);
            $('#preloader').length && $('#preloader').delay(80).fadeOut('slow', function () {
                $(this).remove();
            });
        });
        VANTA.NET({
            el: "body",
            color: 0x575052,
            backgroundColor: 0x343434,
            maxDistance: 11.00,
            spacing: 20.00,
            showDots: false
        })
    </script>
<?php
Components::handlerError($context);
?>