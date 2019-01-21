<?php
/**
 * Created by PhpStorm.
 * User: skipin
 * Date: 06.11.18
 * Time: 15:57
 */

namespace DeveloperHelper\helpers;


class PostTypes {

    public static function getPostTypes() {
        $args = array(
            'public' => true,
            '_builtin' => false
        );

        $post_types = array_values( get_post_types( $args, 'names' ) );
        $post_types = array_merge($post_types, ['post', 'page']);

        sort($post_types);
        return $post_types;
    }

    public static function copyMetaFields($source_id, $target_id) {
        $meta_keys = get_post_custom_keys($source_id);

        foreach ($meta_keys as $meta_key) {
            $meta_values = get_post_custom_values($meta_key, $source_id);
            foreach ($meta_values as $meta_value) {
                $meta_value = maybe_unserialize($meta_value);
                add_post_meta($target_id, $meta_key, duplicate_post_wp_slash($meta_value));
            }
        }
    }

}