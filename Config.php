<?php

namespace App;

class Config
{

    public const HOST = "localhost";
    public const DB_NAME = "webtoon_library";
    public const USER = "root";
    public const PASSWORD = "";
    public const CHARSET = "utf8";

    public const ROLE = [
        'user',
        'admin',
    ];

    public const DEFAULT_LIST = [
        'Pile à lire',
        'Envies',
        'Lu',
    ];

    public const CARD_TYPE = [
        'Fantastique',
        'Comédie',
        'Action',
        'Tranche de vie',
        'Romance',
        'Super Hero',
        'Sport',
        'SF',
        'Horreur',
    ];

}