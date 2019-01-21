<?php
/**
 * Created by PhpStorm.
 * User: skipin
 * Date: 06.11.18
 * Time: 17:46
 */

namespace DeveloperHelper\helpers;


class MultiLanguages {

    public static function createMultiText($texts) {
        if ( !function_exists('wpm_get_lang_option') ) {
            return implode(' / ', $texts);
        }

        $langs = array_keys( wpm_get_lang_option() );
        $text = '';
        foreach($texts as $lang => $lang_text) {
            $lang = ( is_string($lang) ) ? $lang : $langs[$lang];

            if(empty($lang_text)) continue;

            $text .= '[:'.$lang.']'.$lang_text;
        }
        if(!empty($text)) $text .= '[:]';

        return $text;
    }

    public static function addToTitle($title, $add) {
        $titles = [];

        $langs = array_keys( wpm_get_lang_option() );
        foreach($langs as $lang) {
            $text = wpm_translate_string( $title, $lang );

            $titles[$lang] = $text . $add;
        }

        return self::createMultiText($titles);
    }

}