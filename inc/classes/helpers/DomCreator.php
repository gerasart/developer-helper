<?php
/**
 * Created by PhpStorm.
 * User: skipin
 * Date: 06.11.18
 * Time: 14:55
 */

namespace DeveloperHelper\helpers;


class DomCreator {

    private static $dom;

    public function __construct() {
        self::$dom = new \DOMDocument('1.0', 'UTF-8');
    }

    public static function saveHtml() {
        return self::$dom->saveHTML();
    }

    /**
     * Creating an element through DomElement
     * @param type $name
     * @param type $params
     * @return string
     */
    public static function create_node($name, $params = array ()) {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $text = '';
        if (isset($params['text'])) {
            $text = $params['text'];
            unset($params['text']);
        }

        $node = $dom->appendChild($dom->createElement($name, $text));
        foreach ($params as $key => $value) {
            $node->setAttribute($key, $value);
        }

        return $dom->saveHTML();
    }

    // Dom functions
    public static function dom_create($name, $node = false, $text = '', $attr = false) {
        $dom = self::$dom;
        $parent = ($node) ? $node : $dom;

        $elem = $parent->appendChild($dom->createElement($name, $text));

        if ($attr) {
            self::dom_set_attr($elem, $attr);
        }

        return $elem;
    }

    public static function dom_set_attr($node, $attr) {
        foreach ($attr as $name => $value) {
            if (!empty($value) || $value == '0') {
                $node->setAttribute($name, $value);
            }
        }
    }
    /** End Dom Functions */

}