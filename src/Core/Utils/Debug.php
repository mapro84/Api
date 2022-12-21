<?php
namespace src\Core\Utils;

class Debug{

    private static $separation = '';

    public static function setSeparation($separation){
        self::$separation = $separation;
    }

    public static function dump($value, string $name = null){
        self::setSeparation("===============================================================================");
        echo "<strong><pre style='background-color:orange'>";
        echo self::$separation;
        echo "<br>". $name." : ";
        var_dump( $value);
        echo self::$separation;
        echo "</pre></strong>";
    }

}