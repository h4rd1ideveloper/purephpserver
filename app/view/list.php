<?php
include_once __DIR__ . './Header.php';
require_once __DIR__ . './components/Components.php';

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
        <section style="overflow-x: scroll;max-width: 100%!important;">
            <?php
            Components::table($vars['xlsx'] ?? [['...', '...'], ['..', '..'], ['..', '..'], ['..', '..']]);
            ?>
        </section>
    </div>
</div>

<?php
include_once __DIR__ . './Scripts.php';
include_once __DIR__ . './Footer.php';
?>
