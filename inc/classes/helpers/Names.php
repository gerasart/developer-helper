<?php
/**
 * Created by PhpStorm.
 * User: skipin
 * Date: 06.11.18
 * Time: 16:27
 */

namespace DeveloperHelper\helpers;


class Names {

    public static function getTrueClassName( $class = null ) {
        $reflect = new \ReflectionClass( $class );

        return $reflect->getShortName();
    }

    public static function getMainNamespace($class = false) {
        if ( $class ) {
            $classname = ( is_object($class) ) ? get_class( $class ) : $class;

            $explode = explode( '\\', $classname );

            return $explode[0];
        } else {
            return __NAMESPACE__;
        }
    }

    public static function getTemplateName($class) {
        $short = self::getTrueClassName($class);
        $name = strtolower( preg_replace("/([A-Z])/", "-$1", $short) );

        $template = substr($name, 1);
        return $template;
    }

}