<?php

namespace App\pages;

use App\pages\components\Post;

if ((isset($context) && count($context)) || (defined('context') && count(context)))
    echo Post::create($context ?? context);
else {
    echo "Missing parameters";
}