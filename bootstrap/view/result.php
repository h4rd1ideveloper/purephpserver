<?php
include_once __DIR__ . './Header.php';
require_once __DIR__ . './components/Components.php';
Components::centredTitle('Simulação de conciliação', 'h1');
?>
<div class="row">
    <div class="col-12 mx-auto">
        <?php
        Components::leftTitle('Linhas não encontradas', 'text-success h3');
        ?>
        <section style="overflow-x: scroll;max-width: 100%!important;max-height: 400px!important">
            <?php
            Components::table($vars['find'] ?? [['...', '...'], ['..', '..'], ['..', '..'], ['..', '..']]);
            ?>
        </section>
    </div>
</div>
<?php
if (isset($vars['notFind']) && count($vars['notFind']) > 1):
    ?>
    <div class="row">
        <div class="col-12 mx-auto">
            <?php
            Components::leftTitle('Linhas não encontradas', 'text-danger h3');
            ?>
            <section style="overflow-x: scroll;max-width: 100%!important;max-height: 400px!important">
                <?php
                Components::table($vars['notFind'] ?? [['...', '...'], ['..', '..'], ['..', '..'], ['..', '..']]);
                ?>
            </section>
        </div>
    </div>
<?php
endif;
include_once __DIR__ . './Scripts.php';
include_once __DIR__ . './Footer.php';
?>
