<?php

class Database {
    private static $dbHost = "localhost";
    private static $dbName = "capsite";
    private static $dbUser = "root";
    private static $dbPassword = "";

    private static $connection = Null;

    public static function connect(){
        try{
            $options = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
              );
            self::$connection = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName,self::$dbUser,self::$dbPassword, $options );
        }catch(PDOException $e){
            die($e->getMessage());
        };
        return self::$connection;
    }

    public static function disconnect(){
        self::$connection = null; 
    } 
};

Database::connect();

?>