<?php
class Log{
    public static function write($message){
        $path = "../parser.log";
        
        date_default_timezone_set("Europe/London");
        file_put_contents($path, date('Y-m-d H:i:s')." - ".(string)$message."\n", FILE_APPEND);
    }
}
