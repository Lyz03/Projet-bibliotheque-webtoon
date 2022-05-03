<?php

use App\Router;

require __DIR__ . '/../includes.php';

session_set_cookie_params(['path' => '/;SameSite=lax']);
session_start();

Router::route();