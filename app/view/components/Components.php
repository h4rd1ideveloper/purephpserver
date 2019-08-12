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
}