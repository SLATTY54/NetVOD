<?php

namespace database;

use PDO;

class ConnectionFactory
{
    private static $db = null;
    public static $config = [];

    public static function setConfig($f){
        self::$config = parse_ini_file($f);
    }

    public static function makeConnection()
    {
        if (self::$db == null){
            $dsn = self::$config['driver'].':host='.self::$config['host'].';dbname='.self::$config['database'];

            self::$db = new PDO($dsn, self::$config['username'], self::$config['password'],[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false,
            ]);

            self::$db->prepare('SET NAMES \'utf8\'')->execute();
        }
        return self::$db;
    }
}