<?php

namespace App\view\components;
class Components
{
    public static function centredTextH1($text)
    {
        echo sprintf('
            <div class="row my-5">
                <div class="col-12">
                    <h1 class="h3 text-center text-black-50">%s </h1>
                </div>
            </div>
        ', $text);
    }

    public static function table($data)
    {

        $html = '<table class="table table-striped table-hover" ><thead><tr> ';
        foreach ($data[0] as $key){
            $html .= sprintf('<th scope="col">%s</th>', $key);
        }
        $html .= '</tr></thead><tbody>';
        foreach ($data as $index => $row)  {
            $html .= '<tr>';
            $header = 0;
            foreach ($row as $key => $value) {
                $html .=(0 === $header++)? sprintf('<th scope ="row" > %s</th >', $value):sprintf('<td>%s</td>', $value);
            }
            $html .= '</tr >';
        }
        $html .= '</tbody></table>';
        echo $html;
    }
}