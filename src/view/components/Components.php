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
     *
     */
    const buttonCollapse = '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsedContent" aria-controls="collapsedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>';
    /**
     * @var string
     */
    private static $HTML_CONTENT;

    /**
     * @param array $config
     * @return Components
     */
    public static function headerHTML(array $config): Components
    {
        self::$HTML_CONTENT = '';
        //
        $baseUrl = Helpers::baseURL();
        //$bootstrap = "<script src='$baseUrl/src/view/assets/js/bootstrap.js'></script>";
        self::$HTML_CONTENT .= sprintf(
            "
    <!doctype html>
    <html lang='pt-br'>
    <head>
        <meta charset='UTF-8'/>
        <meta name='viewport'
              content='width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0'/>
        <meta http-equiv='X-UA-Compatible' content='ie=edge'/>
        <meta content='' name='keywords'>
        <meta content='' name='description'>
        <link rel='manifest' href='$baseUrl/src/view/assets/site.webmanifest.json'>
        <link href='$baseUrl/src/view/assets/img/favicon.ico' rel='icon'>
        <link href='$baseUrl/src/view/assets/img/apple-touch-icon.png' rel='apple-touch-icon'>
        <!-- Google Fonts -->
        <link href='https://fonts.googleapis.com/css?family=Anton|Montserrat:300,400,700&display=swap&subset=latin-ext' rel='stylesheet'>
        <!-- Bootstrap CSS File -->
       <link rel='stylesheet' href='//bootswatch.com/4/litera/bootstrap.min.css'/>
        <script src='$baseUrl/src/view/assets/js/jquery-3.4.1.min.js'></script>
        <script src='$baseUrl/src/view/assets/js/popper.min.js'></script>
        <!-- Libraries CSS Files -->
        <link href='https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet' integrity='sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN' crossorigin='anonymous'>
        <link href='$baseUrl/src/view/assets/js/lib/animate/animate.min.css' rel='stylesheet'>
        <link href='$baseUrl/src/view/assets/js/lib/ionicons/css/ionicons.min.css' rel='stylesheet'>
        <link href='$baseUrl/src/view/assets/js/lib/owlcarousel/assets/owl.carousel.min.css' rel='stylesheet'>
        <link href='$baseUrl/src/view/assets/js/lib/lightbox/css/lightbox.min.css' rel='stylesheet'>
        <!-- Main Stylesheet File -->
        <link href='$baseUrl/src/view/assets/css/style.css' rel='stylesheet'>
        <title>%s</title>
        <link rel='stylesheet' href='$baseUrl/src/view/assets/css/index.css'/>
        <link href='$baseUrl/src/view/assets/css/jquery.dataTables.min.css' rel='stylesheet'/>
        <script src='$baseUrl/src/view/assets/js/jquery.dataTables.min.js'></script>
        <script src='$baseUrl/src/view/assets/js/sweetalert2.min.js'></script>
        <link rel='stylesheet' href='$baseUrl/src/view/assets/css/sweetalert2.min.css'/>
        <script src='$baseUrl/src/view/assets/js/axios.min.js'></script>
        <script src='$baseUrl/src/view/assets/js/moment-with-locales.js'></script>
        <script src='%ssrc/view/assets/js/lib/background/three.r92.min.js'></script>
        <script src='%ssrc/view/assets/js/lib/background/vanta.net.min.js'></script>
    </head>
    <body>", $config['title'] ?? 'SWS', $baseUrl, $baseUrl);
        return new static();
    }

    /**
     * @TODO param array $config
     * @param string $additionalScrips
     * @return string
     */
    public static function footerHTML(string $additionalScrips = ''): string
    {//<script src='$baseUrl/src/view/assets/js/translate.google.js'></script>
        $baseUrl = Helpers::baseURL();
        return self::$HTML_CONTENT . <<<HTML

         <!-- JavaScript Libraries -->
        <script src='$baseUrl/src/view/assets/js/lib/jquery/jquery-migrate.min.js'></script>
        <script src='$baseUrl/src/view/assets/js/lib/easing/easing.min.js'></script>
        <script src='$baseUrl/src/view/assets/js/lib/mobile-nav/mobile-nav.js'></script>
        <script src='$baseUrl/src/view/assets/js/lib/wow/wow.min.js'></script>
        <script src='$baseUrl/src/view/assets/js/lib/waypoints/waypoints.min.js'></script>
        <script src='$baseUrl/src/view/assets/js/lib/counterup/counterup.min.js'></script>
        <script src='$baseUrl/src/view/assets/js/lib/owlcarousel/owl.carousel.min.js'></script>
        <script src='$baseUrl/src/view/assets/js/lib/isotope/isotope.pkgd.min.js'></script>
        <script src='$baseUrl/src/view/assets/js/lib/lightbox/js/lightbox.min.js'></script>
        <!-- Contact Form JavaScript File -->
        <script src='$baseUrl/src/view/assets/js/lib/contactform/contactform.js'></script>
        <!-- Template Main Javascript File -->
        <script src='$baseUrl/src/view/assets/js/main.js'></script>
        $additionalScrips
        <script>
            $('document').ready(function () {
                window.Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            });
        </script>
        </body>
        </html>
HTML;
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
     * @return string
     */
    public static function navigatorBar(array $infos, string $mode = 'dark'): string
    {
        return '<nav class="navbar navbar-expand-md navbar-<?= $mode ?> bg-<?= $mode ?> mb-5">' .
            self::link($infos['page_href'] ?? '', $infos['page_title'] ?? '') .
            self::buttonCollapse . self::collapsedContent(
                self::navHTML($infos['page_href'] ?? '', $infos['page_title'] ?? '', $infos['nav_items']) .
                self::searchHTML('...', 'Buscar', true)
            );
    }

    /**
     * @param string $target
     * @param string $text
     * @param string $classes
     * @param string $props
     * @return string
     */
    public static function link(string $target, string $text, $classes = '', string $props = ''): string
    {
        $target = Helpers::baseURL($target);
        return sprintf(/**@lang text */ "<a class='%s' href='%s' %s >%s</a>", $classes, $target, $props, $text);
    }

    /**
     * @param string $content
     * @return string
     */
    public static function collapsedContent(string $content): string
    {
        return '<div class="collapse navbar-collapse" id="collapsedContent">' . $content . '</div></nav>';
    }

    /**
     * @param string $active_href
     * @param string $active_text
     * @param array $nav_items
     * @param array $nav_dropdown
     * @param array $nav_disabled
     * @return string
     */
    public static function navHTML(string $active_href, string $active_text, array $nav_items = [], array $nav_dropdown = [], array $nav_disabled = []): string
    {
        $html = '<ul class="navbar-nav mr-auto"><li class="nav-item active">' .
            self::link(
                Helpers::baseURL($active_href),
                trim($active_text) . '<span class="sr-only">(current)</span>',
                'nav-link'
            ) .
            '</li>';
        foreach ($nav_items as $label => $link) {
            $html .= sprintf(
                '<li class="nav-item">%s</li>',
                self::link(Helpers::baseURL($link), $label, 'nav-link')
            );
        }
        foreach ($nav_dropdown as $content) {
            $html .= '<li class="nav-item dropdown">' .
                self::link(
                    '#',
                    $content['title'],
                    'nav-link dropdown-toggle',
                    'role="button"data-toggle="dropdown"aria-haspopup="true"aria-expanded="false"'
                );
            if (count($content['page_drop_links'])) {
                $html .= '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
                foreach ($content['page_drop_links'] as $label => $link) {
                    $html .= ($link === 'D') ? '<div class="dropdown-divider"></div>' : self::link($link, $label, 'dropdown-item');
                }
                $html .= '</div>';
            }
            $html .= '</li>';
        }
        foreach ($nav_disabled as $link) {
            $html .= '<li class="nav-item">' .
                self::link('#', $link, 'nav-link disabled', 'tabindex="-1" aria-disabled="true"') .
                '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    /**
     * @param string $placeholder
     * @param $buttonText
     * @param bool $formWrap
     * @return string
     */
    public
    static function searchHTML(string $placeholder, $buttonText, bool $formWrap): string
    {
        $html = '';
        if ($formWrap) {
            $html .= '<form class="form-inline my-2 my-lg-0" >';
        }
        $html .= "<input class='form-control mr-sm-2' type='search' placeholder='$placeholder' aria-label='Search'>";
        if (Helpers::stringIsOk($buttonText)) {
            $html .= "<button class='btn btn-outline-success my-2 my-sm-0' type='submit'>$buttonText</button>";
        }
        if ($formWrap) {
            $html .= '</form>';
        }
        return $html;
    }

    /**
     * @param $text
     * @param string $size
     * @return string
     */
    public
    static function centredTitle(string $text, string $size = 'h3'): string
    {
        return "<div class='row my-5'>
                <div class='col-12'>
                    <h1 class='$size text-center text-black-50'>$text</h1>
                </div>
            </div>";
    }

    /**
     * @param $text
     * @param string $text_class
     * @return string
     */
    public
    static function leftTitle(string $text, string $text_class = ''): string
    {
        return $text_class === '' ?
            "<div class='row my-5'>
                <div class='col-12'>
                    <h1 class='h3 text-black-50'>$text</h1>
                </div>
            </div>" :
            "<div class='row my-5'>
                <div class='col-12'>
                    <h1 class='$text_class'>$text</h1>
                </div>
        </div>";

    }

    /**
     * @param array $vars
     * @return string
     */
    public
    static function handlerError(array $vars): string
    {
        return (isset($vars['error']) && !!$vars['error']) ?
            "<script>
                Toast.fire({
                    type: 'error',
                    title: 'Algo deu errado, contate o ADM do sistema'
                });
            </script>" : "";

    }

    public static function content(string $content): Components
    {

        self::$HTML_CONTENT .= $content;
        return new static();
    }

    /**
     * @param string $name
     * @param bool $required
     * @param string|null $label
     * @param string|null $labelHelper
     * @param string|null $type
     * @param string|null $placeholder
     * @param string|null $class
     * @param int|null $min
     * @param int|null $max
     * @return Components
     */
    public static function input(string $name, bool $required, ?string $label = '', ?string $labelHelper = '', ?string $type = 'text', ?string $placeholder = '', ?string $class = '', ?string $wrapClass = '', ?int $min = 4, ?int $max = 20)
    {
        $key = uniqid();
        ?>
        <div class="form-group <?= $wrapClass; ?>">
            <label for="<?= $name; ?>"><?= $label; ?></label>
            <input <?php if ($required): ?>required<?php endif; ?>
                   maxlength="<?= $max; ?>" minlength="<?= $min; ?>"
                   type="<?= $type; ?>"
                   class="form-control <?= $class; ?>"
                   name="<?= $name; ?>"
                   aria-describedby="<?= $name; ?>HelpId_<?= $key; ?>"
                   placeholder="<?= $placeholder; ?>"
            />
            <small id="<?= $name; ?>HelpId_<?= $key; ?>" class="form-text text-black-50"><?= $labelHelper; ?></small>
        </div>
        <?php
        return new static();
    }
}
