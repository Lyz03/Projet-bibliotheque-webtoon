<?php

namespace App;

use PDO;
use PDOException;

class DB
{
    private static ?PDO $connection = null;

    /**
     * Connection to the database (specified in Config)
     * @return PDO
     */
    public static function getConnection(): PDO
    {
        if(self::$connection  === null) {
            try {
                $dsn = 'mysql:host=' . Config::HOST . ';dbname=' . Config::DB_NAME . ';charset=' . Config::CHARSET;
                self::$connection  = new PDO($dsn, Config::USER, Config::PASSWORD);
                self::$connection ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection ->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            }
            catch (PDOException $err) {
                die();
            }
        }

        return self::$connection ;
    }
}

