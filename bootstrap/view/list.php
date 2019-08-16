<?php
include_once __DIR__ . '/Header.php';
require_once __DIR__ . '/components/Components.php';

?>
<div class="row mb-5">
    <?php

    Components::formXSLXtoTable(
        $vars['fields'] ?? ['...'],
        $vars['xlsx'][0] ?? [['...', '...'], ['..', '..'], ['..', '..'], ['..', '..']],
        $vars['xlsx'] ?? [['...'], ['...']]
    );
    ?>

</div>
<div class="row">
    <div class="col-12 mx-auto">
        <section style="overflow-x: scroll;max-width: 100%!important;max-height: 400px!important">
            <?php
            Components::table($vars['xlsx'] ?? [['...', '...'], ['..', '..'], ['..', '..'], ['..', '..']]);
            ?>
        </section>
    </div>
</div>

<?php
include_once __DIR__ . '/Scripts.php';
?>
<script>
    $('document').ready(function () {
        let xlsx = JSON.parse(`<?=json_encode($vars['source'] ?? [], JSON_UNESCAPED_UNICODE);?>`);

        $("#toCheck").on("submit", (e) => {
            e.preventDefault();
            if (xlsx.length) {
                let keyAndValues = [];
                $("#toCheck select").each((i, e) => {
                    keyAndValues = (e.value !== "Não utilizado") ? [...keyAndValues, {[`${e.name}`]: `${e.value}`}] : keyAndValues;
                });

            }
        );


        axios.post('check', {source: xlsx, toCheck: keyAndValues})
    }
    })
    })
</script>
<?
include_once __DIR__ . '/Footer.php';
?>
