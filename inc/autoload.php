<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Autload classes from classes/ folder
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'basel_autoload' ) ) {
    function basel_autoload($className) {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strripos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $className = str_replace('BASEL_', '', $className);
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        $fileName = BASEL_CLASSES . DIRECTORY_SEPARATOR . $fileName;
        if( file_exists( $fileName )) {
            require $fileName;
        }
    }

    spl_autoload_register('basel_autoload');
}


