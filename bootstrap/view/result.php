<?php

include_once __DIR__ . '/Header.php';
require_once __DIR__ . '/components/Components.php';

Components::centredTitle('Simulação de conciliação', 'h1');
$dateAndCountFinds = Helpers::countDates($vars['find'] ?? [], $vars['headers'] ?? [], $vars['source'] ?? []);
$dateAndCountNotFinds = Helpers::countDates($vars['notFind'] ?? [], $vars['headers'] ?? [], $vars['source'] ?? []);
$chartLabels = Helpers::toJson(Helpers::objectKeys(array_merge($dateAndCountFinds, $dateAndCountNotFinds)));
?>
<section class="my-5">
    <div class="row">
        <div class="col-12 mx-auto">
            <canvas id="myChart"></canvas>
        </div>
    </div>
</section>
<?php
if (isset($vars['find']) && count($dateAndCountFinds) > 0):
    ?>
    <div class="row">
        <div class="col-12 mx-auto">
            <?php
            Components::leftTitle('Linhas encontradas', 'text-success h3');
            ?>
            <section style="overflow-x: scroll;max-width: 100%!important;max-height: 400px!important">
                <?php
                Components::table(Helpers::getRowsById($vars['find'] ?? [], $vars['source'] ?? []), true, 'find');
                ?>

            </section>
        </div>
        <button class="my-5 ml-auto btn btn-lg btn-dark" onclick="exportTableToExcel('find', 'encontrado_<?= date('m_d_Y_h_i_s_a', time()); ?>')">Salvar
            Relatorio
        </button>
    </div>
<?php
endif;
?>

<?php
if (isset($vars['notFind']) && count($dateAndCountNotFinds) > 0):
    ?>
    <div class="row">
        <div class="col-12 mx-auto">
            <?php
            Components::leftTitle('Linhas não encontradas', 'text-danger h3');
            ?>
            <section style="overflow-x: scroll;max-width: 100%!important;max-height: 400px!important">
                <?php
                Components::table(Helpers::getRowsById($vars['notFind'] ?? [], $vars['source'] ?? []), true, 'notFind');
                ?>
            </section>
        </div>
        <button class="my-5 ml-auto btn btn-lg btn-dark" onclick="exportTableToExcel('notFind', 'nao_encontrado_<?= date('m_d_Y_h_i_s', time()); ?>')">
            Salvar Relatorio
        </button>
    </div>
<?php
endif;

include_once __DIR__ . '/Scripts.php';
include_once __DIR__ . '/Footer.php';
?>
