<?php

namespace App\pages;

require_once(dirname(__FILE__) . '\Post.php');
if ((isset($context) && count($context)) || (defined('context') && count(context)))
    echo Post_create($context ?? context);
else {
    echo "Missing parameters";
}