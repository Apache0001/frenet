<?php

namespace Source\Core;

/**
 * Class Connect [Singleton Pattern]
 * 
 * @author Pablo O.Mesquita <pablo_omesquita@hotmail.com>
 * @package Source\Core
 */

class Connect
{
    /** @const array */
    const DB_OPTIONS = [
        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_CASE => \PDO::CASE_NATURAL
    ];

    /** @var \PDO */
    private static $instance;

    /**
     * getInstance
     *
     * @return \PDO
     */
    public static function getInstance(): ?\PDO
    {
        if(empty(self::$instance)){

            try{
                self::$instance =  new \PDO(
                    "mysql:host=".CONF_DB_HOST.";dbname=".CONF_DB_NAME,
                    CONF_DB_USER,
                    CONF_DB_PASSWD,
                    self::DB_OPTIONS
                );
    
                return self::$instance;

            }catch(\PDOException $exception){
                echo 'erro de conexão: '.$exception;
            }
          
        }

        return self::$instance;
    }

       /**
     * Connect constructor.
     */
    final private function __construct()
    {
    }

    /**
     * Connect clone.
     */
    final private function __clone()
    {
    }
}