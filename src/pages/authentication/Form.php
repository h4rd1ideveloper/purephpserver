<?php

use App\lib\Components;
use App\lib\Helpers;

include 'store.php';
$stylesheet = [
    $baseUrl . 'src/pages/css/style.css',
    $baseUrl . 'src/pages/css/background.svg.css',
    'https://bootswatch.com/4/litera/bootstrap.min.css'
];
$header_config = ['title' => 'Login Page','stylesheet' => $stylesheet];
?>
<?= Components::headerHTML($header_config) .
    Helpers::createStreamFromFile(dirname(__FILE__) . '\..\img\background.svg') .
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
                            $login_fields
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
                            $sign_fields
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
    Components::scripts().
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
    Components::closeView(); ?>