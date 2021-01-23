<?php
class Database
{
    protected static $db;
    private function __construct()
    {
        $db_host = "localhost";
        $db_user = "root";
        $db_password = "root";
        $db_name = "db_relatorios";
        $db_driver = "mysql";
        $options = array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION );
      
        try
        {
            self::$db = new PDO("$db_driver:host=$db_host; dbname=$db_name", $db_user, $db_password, $options);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$db->exec('SET NAMES utf8');
        }
        catch (PDOException $e)
        {
            die("Connection Error: " . $e->getMessage());
        }
    }
    public static function connectionPDO()
    {
        if (!self::$db)
        {
            new Database();
        }
        return self::$db;
    }
}
?>