<?php


use App\lib\Components;
use App\lib\Helpers;

$baseUrl = Helpers::baseURL();
?>
<?= Components::headerHTML(
    [
        'stylesheet' =>
            [
                "$baseUrl/src/pages/plugins/fontawesome-free/css/all.min.css",
                "https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css",
                "$baseUrl/src/pages/dist/css/adminlte.min.css",
                "https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700",
                "$baseUrl/src/pages/css/style.css"
            ],
        'bodyClass' => 'vh-100 w-100 d-flex flex-direction-row justify-content-center align-items-center'
    ]
); ?>
<div class="error-page">
    <h2 class="headline text-warning"> 404</h2>
    <div class="error-content">
        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found.</h3>
        <p>
            Não foi possível encontrar a página que você estava procurando.
            Enquanto isso, você pode <a href="<?= $baseUrl; ?>">retornar ao painel</a>
        </p>
    </div>
</div>

<!-- ./wrapper -->
<!-- jQuery -->
<script src="<?= $baseUrl; ?>src/pages/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?= $baseUrl; ?>src/pages/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= $baseUrl; ?>src/pages/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= $baseUrl; ?>src/pages/dist/js/demo.js"></script>


