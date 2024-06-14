<?php
namespace src\Utils;
use src\Exceptions\NotFoundException;

// Autoloader
function autoloader($classname) {
    $lastSlash = strpos($classname, '\\') + 1;
    $classname = substr($classname, $lastSlash);
    $directory = $classname;
    // $directory = str_replace('\\', '/', $classname);
    $filename = __DIR__ . '\Src'. '\\' . $directory . '.php';
    // echo "\nFile name = ", $filename, "\n";
    require_once($filename);
}
// echo __DIR__;
spl_autoload_register('autoloader');


class Config {

    private $data;
    private static $instance;
    
    public function __construct() {
        $json = file_get_contents(__DIR__ . '/config/app.json');
        $this->data = json_decode($json, true);
    }

    public static function getInstance(){
        if(self::$instance == null){
            self::$instance = new Config();
        }
        return self::$instance;
    }

    public function get($key) {
        echo $key;
        // echo $this->data[key];
        if (!isset($this->data[$key])) {
            throw new NotFoundException("Key $key not in config.");
        }
        return $this->data[$key];
    }
}