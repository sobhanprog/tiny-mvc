<?php

namespace System\Database\DBConnction;

use PDO;
use PDOException;

class DBConnction
{
    private static $dbConctioninstance = null;

    private function __construct()
    {
    }

    public static function getDBConctionInstance()
    {
        if (self::$dbConctioninstance == null) {
            self::$dbConctioninstance = new DBConnction();
        }
        return self::$dbConctioninstance->dbConction();
    }

    private function dbConction()
    {
        $option = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
        try {
            return new PDO("mysql:host=" . DB_HOST . ';dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD, $option);
        } catch (\Exception $e) {
            echo "error in database connction" . $e->getMessage();
            return false;
        }
    }

    public static function newInsertId()
    {
        return self::getDBConctionInstance()->lastInsertId();
    }
}

