<?php

namespace App\view\components;

use InvalidArgumentException;
use Lib\Helpers;

/**
 * Class Components
 * @package App\view\components
 */
class Components
{

    /**
     * @param array $config
     * @return Components
     */
public static function headerHTML(array $config)
{
    $title = Helpers::orEmpty(
        $config['title'],
        false,
        'Boletão'
    ); ?>
    <!doctype html>
    <html lang='pt-br'>
    <head>
        <meta charset='UTF-8'/>
        <meta name='viewport'
              content='width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0'/>
        <meta http-equiv='X-UA-Compatible' content='ie=edge'/>
        <title><?= $title ?></title>
        <link rel='stylesheet' href='<?= Helpers::baseURL() ?>src/view/assets/css/bootstrap.css'/>
        <script src="<?= Helpers::baseURL() ?>src/view/assets/js/jquery-3.4.1.min.js"></script>
        <script src='<?= Helpers::baseURL() ?>src/view/assets/js/popper.min.js'></script>
        <script src='<?= Helpers::baseURL() ?>src/view/assets/js/bootstrap.js'></script>
        <link rel='stylesheet' href='<?= Helpers::baseURL() ?>src/view/assets/css/index.css'/>
        <link href='<?= Helpers::baseURL() ?>src/view/assets/fonts/fontawesome/css/all.min.css' rel='stylesheet'/>
        <script src='<?= Helpers::baseURL() ?>src/view/assets/fonts/fontawesome/js/all.min.js'></script>
        <link href='<?= Helpers::baseURL() ?>src/view/assets/css/jquery.dataTables.min.css' rel='stylesheet'/>
        <script src='<?= Helpers::baseURL() ?>src/view/assets/js/jquery.dataTables.min.js'></script>
        <script src='<?= Helpers::baseURL() ?>src/view/assets/js/sweetalert2.min.js'></script>
        <link rel='stylesheet' href='<?= Helpers::baseURL() ?>src/view/assets/css/sweetalert2.min.css'/>
        <script src='<?= Helpers::baseURL() ?>src/view/assets/js/axios.min.js'></script>
        <script src='<?= Helpers::baseURL() ?>src/view/assets/js/moment-with-locales.js'></script>
    </head>
    <body>
    <?php
    return new static();
    }
    /**
     * @TODO param array $config
     * @return Components
     */
    public static function footerHTML():Components
    {
    ?>
    <script>
        $("document").ready(function () {
            window.Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000
            });
        });
    </script>
    </body>
    </html>
    <?php return new static();
}

    /**
     * @param array $data
     * @param $id
     */
    public static function tableHTML(array $data, $id)
    {
        if (count($data) === 0) {
            throw new InvalidArgumentException("O array não pode ser Vazio");
        }
        if (!isset($data['headers']) || !is_array($data['headers'])) {
            throw new InvalidArgumentException("Atributo headers é obrigatorio e deve ser um array de valores escalar");
        }
        if (!isset($data['body']) || !is_array($data['body'])) {
            throw new InvalidArgumentException("Atributo headers é obrigatorio e deve ser um array de valores escalar");
        }
        $html = '';
        $html .= Helpers::Reducer($data['headers'], function ($initial, $currentValue) {
                return $initial . "<th scope='col'>$currentValue</th>";
            }, "<table id='$id' class='table table-hover'><thead><tr>") . '</tr></thead>';
        $html .= Helpers::Reducer($data['body'], function ($initial, $currentValue) {
                if (!is_array($currentValue)) {
                    throw new InvalidArgumentException("Atributo body é obrigatorio e deve ser um array de array valores escalar");
                }
                return $initial . Helpers::Reducer($currentValue, function ($init, $v) {
                        return $init . "<th class='text-black-50' scope='row'>$v</th>";
                    }, '<tr>') . '</tr>';
            }, '<tbody>') . '</tbody></table>';
        echo $html;
    }

    /**
     * @param array $infos
     * @param string $mode
     */
    public static function navbarHTML(array $infos, string $mode = 'dark')
    {
        ?>
        <nav class="navbar navbar-expand-md navbar-<?= $mode ?> bg-<?= $mode ?> mb-5">
            <?
            self::link($infos['page_href'] ?? '', $infos['page_title'] ?? ''); ?>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsedContent"
                    aria-controls="collapsedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsedContent"><?php
                self
                    ::navHTML($infos['page_href'] ?? '', $infos['page_title'] ?? '', $infos['nav_items'])
                    ::searchHTML('...', 'Buscar', true);
                ?>
            </div>
        </nav>
        <?php
    }

    public static function link(string $target, string $text, $classes = false)
    {
        ?>
        <a class="<?= ($classes ? $classes : 'navbar-brand') ?>"
           href="<?= Helpers::baseURL($target) ?>"><?= $text ?></a>
        <?php
        return new static();
    }

    /**
     * @param string $placeholder
     * @param $buttonText
     * @param bool $formWrap
     * @return mixed
     */
    public static function searchHTML(string $placeholder, $buttonText, bool $formWrap)
    {
        if ($formWrap): ?>
            <form class="form-inline my-2 my-lg-0">
        <?php endif; ?>
        <input class="form-control mr-sm-2" type="search" placeholder="<?= $placeholder ?>" aria-label="Search">
        <?php if (Helpers::stringIsOk($buttonText)): ?>
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">
            <?= $buttonText ?>
        </button>
    <?php endif; ?>
        <?php if ($formWrap): ?>
        </form>
    <?php endif;
        return new static();
    }

    /**
     * @param string $active_href
     * @param string $active_text
     * @param array $nav_items
     * @param array $nav_dropdown
     * @param array $nav_disabled
     * @return Components
     */
    public static function navHTML(string $active_href, string $active_text, array $nav_items = [], array $nav_dropdown = [], array $nav_disabled = [])
    {
        ?>
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="<?= Helpers::baseURL($active_href) ?>"><?= trim($active_text) ?>
                    <span class="sr-only">(current)</span></a>
            </li>
            <?php
            foreach ($nav_items as $label => $link):
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= Helpers::baseURL($link) ?>"><?= $label ?></a>
                </li>
            <?php
            endforeach;
            foreach ($nav_dropdown as $content):?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= $content['title'] ?>
                    </a>
                    <?php if (count($content['page_drop_links'])): ?>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?php foreach ($content['page_drop_links'] as $label => $link): ?>
                                <?php if ($link === 'D'): ?>
                                    <div class="dropdown-divider"></div>
                                <?php else: ?>
                                    <a class="dropdown-item" href="<?= $link ?>"><?= $label ?></a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </li>
            <?php
            endforeach;
            ?>
            <?php
            foreach ($nav_disabled as $link):
                ?>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true"><?= $link ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
        return new static();
    }

    /**
     * @param $text
     * @param string $size
     */
    public static function centredTitle($text, $size = 'h3')
    {
        echo sprintf('
            <div class="row my-5">
                <div class="col-12">
                    <h1 class="%s text-center text-black-50">%s </h1>
                </div>
            </div>
        ', $size, $text);
    }

    /**
     * @param $text
     * @param string $text_class
     */
    public static function leftTitle($text, $text_class = '')
    {
        if ($text_class === '') {
            echo sprintf('
            <div class="row my-5">
                <div class="col-12">
                    <h1 class="h3 text-black-50 ">%s </h1>
                </div>
            </div>
        ', $text);
        } else {
            echo sprintf('
            <div class="row my-5">
                <div class="col-12">
                    <h1 class="%s">%s </h1>
                </div>
            </div>
        ', $text_class, $text);
        }
    }

    public static function captureError(bool $error): Components
    {
        if (isset($error) && !!$error):?>
            <script>
                Toast.fire({
                    type: 'error',
                    title: 'Algo deu errado, contate o ADM do sistema'
                });
            </script>
        <?php endif;
        return new static();
    }
}
