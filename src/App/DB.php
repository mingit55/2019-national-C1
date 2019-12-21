<?php
namespace app;

class DB {
    static $conn = null;
    static $option = [
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
    ];

    static function getDB(){
        if(self::$conn == null){
            self::$conn = new \PDO("mysql:host=localhost;dbname=biff1;charset=utf8mb4", "root", "", self::$option);

            $q = self::$conn->query("SELECT * FROM users WHERE user_id = 'admin'");
            if(!$q->rowCount()){
                $admin = ["admin", "관리자", hash("sha256", '1234')];
                $q = self::$conn->prepare("INSERT INTO users(user_id, user_name, password) VALUES (?, ?, ?)");
                $q->execute($admin);
            }
        }
        return self::$conn;
    }

    static function query($sql, $data = []){
        $q = self::getDB()->prepare($sql);
        $q->execute($data);
        return $q;
    }

    static function fetch($sql, $data = []){
        return self::query($sql, $data)->fetch();
    }

    static function fetchAll($sql, $data = []){
        return self::query($sql, $data)->fetchAll();
    }

    static function lastInsertId(){
        return self::getDB()->lastInsertId();
    }
}