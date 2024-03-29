<?php

namespace App\presentation\view;

use App\infra\lib\Helpers;
use App\infra\service\render\Components;

class NotFoundView extends Components
{


    private array $context;

    public function __construct($context, string $path = '')
    {
        parent::__construct($path);
        $this->context = $context;
    }

    public function __toString()
    {
        $baseUrl = Helpers::baseURL();
        return self::headerHTML([
                'title' => 'Não encontrado',
                'stylesheet' => [
                    "$baseUrl/src/pages/plugins/fontawesome-free/css/all.min.css",
                    "https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css",
                    "$baseUrl/src/pages/dist/css/adminlte.min.css",
                    "https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700",
                    "$baseUrl/src/pages/css/style.css",
                    $baseUrl . 'src/pages/css/home.css',
                    'https://bootswatch.com/4/litera/bootstrap.min.css'
                ],
                'bodyClass' => 'vh-100 w-100 d-flex flex-direction-row justify-content-center align-items-center demo content',
                'raw' => '<style>body{max-height: 100vh;overflow: hidden;}</style>'
            ])
            . <<<HTML
    <div id="large-header">
        <canvas id="demo-canvas"></canvas>
        <div id="error" class="error-page">
            <h2 class="headline text-warning"> 404</h2>
            <div class="error-content">
                <h2>
                    <?= json_encode({$this->context}??'missing'); ?>
                </h2>
                <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found.</h3>
                <p>
                    Não foi possível encontrar a página que você estava procurando.
                    Enquanto isso, você pode <a href="<?= $baseUrl; ?>">retornar ao painel</a>
                </p>
            </div>
        </div>
    </div>
HTML . self::scripts([
                'https://s3-us-west-2.amazonaws.com/s.cdpn.io/499416/TweenLite.min.js',
                'https://s3-us-west-2.amazonaws.com/s.cdpn.io/499416/EasePack.min.js',
                'https://s3-us-west-2.amazonaws.com/s.cdpn.io/499416/demo.js'
            ])
            . self::closeView();
    }
}