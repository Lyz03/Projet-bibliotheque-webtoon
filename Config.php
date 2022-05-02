<?php

namespace App;

class Config
{

    // DB
    public const HOST = "localhost";
    public const DB_NAME = "webtoon_library";
    public const USER = "root";
    public const PASSWORD = "";
    public const CHARSET = "utf8";

    // send mail
    public const APP_URL = 'http://localhost:8000';

    // Role
    public const ROLE = [
        'user',
        'admin',
    ];

    // Default list name
    public const DEFAULT_LIST = [
        'Pile à lire',
        'Envies',
        'Lu',
    ];

    // Card's type name
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

    // Avatar list
    public const AVATAR = [
        'asmo.png',
        'asmo2.png',
        'bitna.png',
        'lyz.png',
        'lyz2.png',
        'lyz3.png',
        'tenes.png',
    ];
}