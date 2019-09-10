<?php

use App\assets\lib\Helpers;

include_once __DIR__ . '/Header.php';
require_once __DIR__ . '/components/Components.php';
Components::centredTitle('Index');
?>
<div class="row">
    aqui
    <div class="col-8 mx-auto">
       <pre><?= Helpers::toJson((isset($vars) ? $vars : array())); ?></pre>
    </div>
</div>

<?php
include_once __DIR__ . '/Scripts.php';
include_once __DIR__ . '/Footer.php';
?>
