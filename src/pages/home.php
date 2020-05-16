<?php

use App\lib\Components;
use App\lib\Helpers;

$baseUrl = Helpers::baseURL();
?>
<?= Components::headerHTML([
    'title' => 'Yan Policarpo',
    'stylesheet' => [
        $baseUrl . 'src/pages/css/home.css',
        'https://bootswatch.com/4/litera/bootstrap.min.css'
    ], 'bodyClass' => 'demo content'
]);
?>

    <div id="large-header">
        <canvas id="demo-canvas"></canvas>
    </div>


<?= Components::scripts([
    'https://s3-us-west-2.amazonaws.com/s.cdpn.io/499416/TweenLite.min.js',
    'https://s3-us-west-2.amazonaws.com/s.cdpn.io/499416/EasePack.min.js',
    'https://s3-us-west-2.amazonaws.com/s.cdpn.io/499416/demo.js'

]); ?>
<?= Components::closeView(); ?>