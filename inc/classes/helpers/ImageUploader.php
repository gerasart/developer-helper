<?php

namespace DeveloperHelper\helpers;
/**
 * Description of imageUploader
 *
 * @author Chameleon
 */
class ImageUploader
{

    public static function upload($file, $parent_post_id = false)
    {
        $filename = basename($file);
        $exist = self::exist($filename);

        $upload_file = wp_upload_bits($filename, null, file_get_contents($file));
        if (!$upload_file['error'] && !$exist) {
            $wp_filetype = wp_check_filetype($filename, null);

            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
                'post_status' => 'inherit'
            );

            if ($parent_post_id) {
                $attachment['post_parent'] = $parent_post_id;
            }

            $attachment_id = wp_insert_attachment($attachment, $upload_file['file'], $parent_post_id);

            if (!is_wp_error($attachment_id)) {
                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload_file['file']);
                wp_update_attachment_metadata($attachment_id, $attachment_data);

                return $attachment_id;
            } else {
                return false;
            }
        } elseif ($exist) {
            if ($parent_post_id) {
                $attachment = array(
                    'ID' => $exist,
                    'post_parent' => $parent_post_id
                );
                wp_update_post($attachment);
            }

            return $exist;
        }

        return false;
    }

    public static function uploadFromForm($name, $post_id = false)
    {
        $images = array();
        if (isset($_FILES) && isset($_FILES[$name]) && !empty($_FILES[$name])) {
            $files = $_FILES[$name];
            foreach ($files['name'] as $key => $filename) {
                if (!function_exists('wp_handle_upload')) {
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                }

                $file = array(
                    'name'     => $files['name'][$key],
                    'type'     => $files['type'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'error'    => $files['error'][$key],
                    'size'     => $files['size'][$key]
                );

                $movefile = wp_handle_upload($file, array( 'test_form' => false ));
                if ($movefile && empty($movefile['error'])) {
                    $images[] = self::upload($movefile['file'], $post_id);
                }
            }
        }

        return $images;
    }

    public static function uploadFromUrl($files, $post_id = 0, $return = 'id') {
        if ( !is_array($files) ) {
            $files = array($files);
        }

        if (!function_exists('media_sideload_image')) {
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';
        }

        $images = array();
        foreach ($files as $url) {
            $img = media_sideload_image( $url, $post_id, null, $return );
            if ( !is_wp_error($img) ) {
                $images[] = $img;
            }
        }

        return $images;
    }

    public static function exist($filename)
    {
        $exp = explode('.', $filename);
        $title = array_shift($exp);

        return self::get_by_title($title);
    }

    public static function get_by_title($title, $return = 'ID')
    {
        global $wpdb;

        $attachments = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_title = '$title' AND post_type = 'attachment' ", OBJECT);
        //print_r($attachments);
        if ($attachments) {
            $attachment = $attachments[0]->$return;
        } else {
            return false;
        }

        return $attachment;
    }

}
