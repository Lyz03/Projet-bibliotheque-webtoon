<?php

require __DIR__ . '/Config.php';
require __DIR__ . '/Router.php';
require __DIR__ . '/DB.php';

require __DIR__ . '/Entity/User.php';
require __DIR__ . '/Entity/Card.php';
require __DIR__ . '/Entity/Comment.php';
require __DIR__ . '/Entity/Rating.php';
require __DIR__ . '/Entity/WebtoonList.php';

require __DIR__ . '/Manager/CardManager.php';
require __DIR__ . '/Manager/CommentManager.php';
require __DIR__ . '/Manager/ListManager.php';
require __DIR__ . '/Manager/RatingManager.php';
require __DIR__ . '/Manager/UserManager.php';

require __DIR__ . "/Controller/AbstractController.php";
require __DIR__ . "/Controller/ErrorController.php";
require __DIR__ . "/Controller/HomeController.php";
require __DIR__ . "/Controller/ConnectionController.php";
require __DIR__ . "/Controller/ListController.php";
require __DIR__ . "/Controller/CardController.php";
require __DIR__ . "/Controller/UserController.php";