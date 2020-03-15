<?php

namespace App;

use InvalidArgumentException;
use App\Helpers;

/**
 * Class Components
 * @package App\view\components
 */
class Components
{

    /**
     * @var string HTML Content
     */
    private static $HTML_CONTENT;

    /**
     * @param array $config
     * @return Components
     */
    public static function headerHTML(array $config = []): Components
    {
        self::$HTML_CONTENT = '';
        $baseUrl = substr(Helpers::baseURL(), -1) !== '/' ? Helpers::baseURL() :  substr(Helpers::baseURL(), 0, -1);
        $title = $config['title'] ?? 'Home';
        $keywords = $config['keywords'] ?? '';
        $description = $config['description'] ?? '';
        $more = $config['more'] ?? '';
        self::$HTML_CONTENT .=
            "
    <!doctype html>
    <html lang='pt-br'>
    <head>
        <meta charset='UTF-8'/>
        <meta name='viewport' content='width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0'/>
        <meta http-equiv='X-UA-Compatible' content='ie=edge'/>
        <meta content='$keywords' name='keywords'>
        <meta content='$description' name='description'>
        <!-- Google Fonts -->
        <link href='https://fonts.googleapis.com/css?family=Anton|Montserrat:300,400,700&display=swap&subset=latin-ext' rel='stylesheet'>
        <!-- Bootstrap CSS -->
        <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css' integrity='sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh' crossorigin='anonymous'>
        <link rel='stylesheet' type='text/css' href='https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css'/>
        <link rel='stylesheet' href='$baseUrl/src/pages/css/style.css'/>
        $more
        <title>$title</title>
    </head>
    <body>";
        return new static();
    }

    /**
     * @TODO param array $config
     * @param string $more
     * @return string
     */
    public static function footerHTML(string $more = ''): string
    {
        return self::$HTML_CONTENT . "
            <!-- JavaScript Libraries -->
            <script src='https://code.jquery.com/jquery-3.4.1.slim.min.js' integrity='sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n' crossorigin='anonymous'></script>
            <script src='https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js' integrity='sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo' crossorigin='anonymous'></script>
            <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js' integrity='sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6' crossorigin='anonymous'></script>
            <script type='text/javascript' src='https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js'></script>        
            $more
        </body>
        </html>";
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
            <input <?php if ($required) : ?>required<?php endif; ?> maxlength="<?= $max; ?>" minlength="<?= $min; ?>" type="<?= $type; ?>" class="form-control <?= $class; ?>" name="<?= $name; ?>" aria-describedby="<?= $name; ?>HelpId_<?= $key; ?>" placeholder="<?= $placeholder; ?>" />
            <small id="<?= $name; ?>HelpId_<?= $key; ?>" class="form-text text-black-50"><?= $labelHelper; ?></small>
        </div>
<?php
        return new static();
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
}
