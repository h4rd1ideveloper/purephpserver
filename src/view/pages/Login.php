<?php

use Lib\Helpers;

;
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
    <!---

    <div class="container">
        <div class="row">
            <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                <div class="card card-signin my-5">
                    <div class="card-body">
                        <h5 class="card-title text-center">Sign In</h5>
                        <form class="form-signin">
                            <div class="form-label-group">
                                <input type="email" id="inputEmail" class="form-control" placeholder="Email address"
                                       required autofocus>
                                <label for="inputEmail">Email address</label>
                            </div>

                            <div class="form-label-group">
                                <input type="password" id="inputPassword" class="form-control" placeholder="Password"
                                       required>
                                <label for="inputPassword">Password</label>
                            </div>

                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                <label class="custom-control-label" for="customCheck1">Remember password</label>
                            </div>
                            <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Sign in
                            </button>
                            <hr class="my-4">
                            <button class="btn btn-lg btn-google btn-block text-uppercase" type="submit"><i
                                        class="fab fa-google mr-2"></i> Sign in with Google
                            </button>
                            <button class="btn btn-lg btn-facebook btn-block text-uppercase" type="submit"><i
                                        class="fab fa-facebook-f mr-2"></i> Sign in with Facebook
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    -->
</main>
