<?php

namespace App;

class Config
{



    // Card nb by page
    public const CARD_LIMIT = 10;

    // Send mail
    public const APP_URL = 'http://localhost:8000';

    // Success message color
    public const SUCCESS = '#92b985';

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
        'Action',
        'Aventure',
        'Comédie',
        'Fantastique',
        'Horreur',
        'Mystère',
        'Romance',
        'SF',
        'Thriller',
        'Tranche de vie',
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