<!DOCTYPE html >
<html lang="pt-br">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http - equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Portal ADM BOLETOS EMPRESTA</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/libs/font-awesome.css"/>
    <link rel="stylesheet" type="text/css" href="css/libs/nanoscroller.css"/>
    <link rel="stylesheet" type="text/css" href="css/compiled/theme_styles.css"/>
    <link rel="stylesheet" type="text/css" href="css/libs/ns-default.css"/>
    <link rel="stylesheet" type="text/css" href="css/libs/ns-style-bar.css"/>
    <link rel="stylesheet" type="text/css" href="css/libs/ns-style-attached.css"/>
    <link rel="stylesheet" type="text/css" href="css/libs/ns-style-other.css"/>
    <link rel="stylesheet" type="text/css" href="css/libs/ns-style-theme.css"/>
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet'
          type='text/css'>
    <link rel="stylesheet" type="text/css" href="css/css.css"/>
    <link type="image/x-icon" href="favicon.png" rel="shortcut icon"/>
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->

</head>


<body id="login-page">
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div id="login-box">
                <div id="login-box-holder">
                    <div class="row">
                        <div class="col-xs-12">
                            <header id="login-header">
                                <div id="login-logo">
                                    <img src="img/logo-login.png" alt=""/>
                                </div>
                            </header>
                            <div id="login-box-inner">
                                <div class="input-group pt-50 pb-50" id="dvaguarde" style="width:100%; display: none;">
                                    <div class="row">
                                        <div class="col-xs-12 text-center">
                                            <span class="loader"> carregando</span><br><br><br><br>
                                            <span id="msglogin"> Efetuando login </span>
                                        </div>
                                    </div>
                                </div>
                                <div id="dvlogin">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        <input class="form-control" type="text" autocomplete="false"
                                               placeholder="Usuario" id="login" value="">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                        <input type="password" class="form-control" placeholder="Senha" id="senha">
                                    </div>
                                    <div id="remember-me-wrapper">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="checkbox-nice">
                                                    <input type="checkbox" id="lembrar" value="1" checked="checked"/>
                                                    <label for="lembrar"> Lembrar</label>
                                                </div>
                                            </div>
                                            <a href="#" id="login-forget-link" class="col-xs-6">
                                                Esqueceu a senha ?
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <button type="button" class="btn btn-success col-xs-12 btlogin"> Login
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


            </div>


        </div>
    </div>
</div>


<script src="js/jquery.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/jquery.nanoscroller.min.js"></script>
<script src="js/js.js"></script>

<script src="js/modernizr.custom.js"></script>
<script src="js/classie.js"></script>
<script src="js/notificationFx.js"></script>


<script src="js/scripts.js"></script>
<script>
    var hashv = '15714049825da9bcb62d4bb' </script>
</body>
</html>