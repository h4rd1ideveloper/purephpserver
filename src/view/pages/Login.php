<?php

use App\view\components\Components;
use Lib\Helpers;

Components
    ::headerHTML(['title' => 'Developer Web Page'])
    ::navbarHTML(
        [
            'page_href' => 'login',
            'page_title' => 'Login/Cadastrar-se',
            'nav_items' => [
                'Inicio' => 'home'
            ]
        ],
        'dark'
    );
?>
    <main class="container-fluid h-100">
        <div class="container rounded py-5">
            <div class="d-none">
                <form class="container mt-5">
                    <fieldset class="form-group row">
                        <legend class="col-form-legend col-sm-1-12">Dados</legend>
                        <div class="col-sm-1-12">
                            <div class="form-group">
                                <label for=""></label>
                                <input type="email" class="form-control" name="" id="" aria-describedby="emailHelpId"
                                       placeholder="">
                                <small id="emailHelpId" class="form-text text-muted">Usuario ou Nome</small>
                            </div>
                        </div>
                    </fieldset>
                    <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Logar</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <form action="<?= Helpers::baseURL() ?>user/login" method="post" enctype="multipart/form-data"
                      class="col-12 col-md-6 mx-auto my-5  text-black-50 bg-dark rounded p-5">
                    <fieldset class="form-group row">
                        <legend class="col-form-legend col-sm-1-12 text-muted mb-5">Confirmar dados</legend>
                        <div class="col-12 mb-5">
                            <div class="form-group">
                                <input type="email"
                                       class="border-bottom form-control  border-top-0 border-left-0 border-right-0"
                                       name="login" id="login" aria-describedby="emailHelpId" placeholder="Login">
                                <small id="emailHelpId" class="form-text text-muted">Usuario</small>
                            </div>
                            <div class="form-group">
                                <input type="password"
                                       class="border-bottom form-control  border-top-0 border-left-0 border-right-0"
                                       name="pass" id="pass" aria-describedby="passwordHelpId" placeholder="Senha">
                                <small id="passwordHelpId" class="form-text text-muted">(a-Z|0-9|!@#$%¨&*()_+=-)</small>
                            </div>
                        </div>
                    </fieldset>
                    <div class="form-group row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-block btn-primary ">Entrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
<?php
Components
    ::captureError($vars['error'] ?? false)
    ::footerHTML();
