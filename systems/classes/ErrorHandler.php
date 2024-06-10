<?php

class ErrorHandler
{
    /**
     * Register this error handler.
     */

    public function register()
    {

        global $config;
        // Off error reporting
        if(!$config["display_errors"]) {

            ini_set('display_errors', 'off');
            error_reporting(E_ERROR | E_PARSE);
            error_reporting(0);
            
        }else{

            // Error Handler
            set_error_handler(function(string $code, string $message, string $file, string $line){
                // error suppressed with @
                if (@error_reporting() === 0) {
                    return false;
                }

                switch ($code) {
                    case E_NOTICE:
                    case E_USER_NOTICE:
                        $error = 'Notice';
                        Logger::notice($error . ': ' . $message . ' in ' . $file . ' on line ' . $line);
                        break;
                    case E_WARNING:
                    case E_USER_WARNING:
                        $error = 'Warning';
                        Logger::warning($error . ': ' . $message . ' in ' . $file . ' on line ' . $line);
                        break;
                    case E_ERROR:
                    case E_USER_ERROR:
                        $error = 'Fatal Error';
                        Logger::error($error . ': ' . $message . ' in ' . $file . ' on line ' . $line);
                        break;
                    default:
                        $error = 'Unknown';
                        Logger::unknown($error . ': ' . $message . ' in ' . $file . ' on line ' . $line);
                        break;
                }

                return true;
            });

            // Exception Handler
            set_exception_handler(function(\Throwable $e){

                Logger::exception(get_class($e) . ': ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());

            });

        }

    }

}