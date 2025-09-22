<?php
namespace App\Database;
use mysqli;
class DB {
    private static  $conn = null;

    public static function get() {
        if(self::$conn === null){
            self::$conn = new mysqli("localhost","root","","todo_app");
        }
        return self::$conn;
    }
}
