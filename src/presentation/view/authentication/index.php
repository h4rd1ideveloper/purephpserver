<?php

$context = $context ?? [];

use App\presentation\view\authentication\AuthView;

echo new AuthView($context);
