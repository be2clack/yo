<?php

class Logger
{

    public static function set($params=[])
    {

        global $config;

        if (isset($params['message'])) {

            if (is_array($params['message'])) {
                if(count($params['message'])) {
                    $content = '[' . date('Y-m-d H:i:s') . ']: ' . json_encode($params['message'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
                }
            } else {
                $content = '['.date('Y-m-d H:i:s').']: '.$params['message'] . PHP_EOL;
            }

            if(isset($content)) {
                file_put_contents($config['basePath'] . '/' . $params['type'] . '_' . date('Y-m-d') . '.log', $content, FILE_APPEND);
            }

        }

    }

    public static function exception($message=null)
    {
        self::set(['type'=>'exception', 'message'=>$message]);
    }

    public static function notice($message=null)
    {
        self::set(['type'=>'notice', 'message'=>$message]);
    }

    public static function warning($message=null)
    {
        self::set(['type'=>'warning', 'message'=>$message]);
    }

    public static function error($message=null)
    {
        self::set(['type'=>'error', 'message'=>$message]);
    }

    public static function unknown($message=null)
    {
        self::set(['type'=>'unknown', 'message'=>$message]);
    }

    public static function info($message=null)
    {
        self::set(['type'=>'info', 'message'=>$message]);
    }


}