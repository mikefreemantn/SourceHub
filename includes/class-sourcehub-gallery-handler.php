<?php
/**
 * SourceHub Gallery Handler
 *
 * Handles gallery syndication by downloading images and remapping IDs
 *
 * @package SourceHub
 */

if (!defined('ABSPATH')) {
    exit;
}

class SourceHub_Gallery_Handler {

    /**
     * Process post content to handle galleries
     *
     * @param string $content Post content
     * @param int $hub_post_id Original hub post ID
     * @param int $spoke_post_id New spoke post ID
     * @param array $hub_images Array of hub image data
     * @return string Modified content with remapped gallery IDs
     */
    public static function process_galleries($content, $hub_post_id, $spoke_post_id, $hub_images = array()) {
        // Process classic gallery shortcodes
        $content = self::process_gallery_shortcodes($content, $hub_images);
        
        // Process Gutenberg gallery blocks
        $content = self::process_gallery_blocks($content, $hub_images);
        
        return $content;
    }

    /**
     * Process classic WordPress gallery shortcodes
     *
     * @param string $content Post content
     * @param array $hub_images Array of hub image data
     * @return string Modified content
     */
    private static function process_gallery_shortcodes($content, $hub_images) {
        // Match [gallery ids="123,456,789"] shortcodes
        $pattern = '/\[gallery([^\]]*ids=["\']([0-9,\s]+)["\'][^\]]*)\]/i';
        
        $content = preg_replace_callback($pattern, function($matches) use ($hub_images) {
            $full_shortcode = $matches[0];
            $shortcode_attrs = $matches[1];
            $ids_string = $matches[2];
            
            // Parse the IDs
            $hub_ids = array_map('trim', explode(',', $ids_string));
            $spoke_ids = array();
            
            // Map hub IDs to spoke IDs
            foreach ($hub_ids as $hub_id) {
                if (isset($hub_images[$hub_id])) {
                    $spoke_ids[] = $hub_images[$hub_id]['spoke_id'];
                } else {
                    error_log("SourceHub Gallery: Hub image ID {$hub_id} not found in image map");
                }
            }
            
            if (empty($spoke_ids)) {
                error_log("SourceHub Gallery: No valid spoke IDs found for gallery, removing shortcode");
                return ''; // Remove gallery if no valid images
            }
            
            // Build new shortcode with spoke IDs
            $new_ids = implode(',', $spoke_ids);
            $new_shortcode = str_replace($ids_string, $new_ids, $full_shortcode);
            
            error_log("SourceHub Gallery: Remapped gallery shortcode from IDs [{$ids_string}] to [{$new_ids}]");
            
            return $new_shortcode;
        }, $content);
        
        return $content;
    }

    /**
     * Process Gutenberg gallery blocks
     *
     * @param string $content Post content
     * @param array $hub_images Array of hub image data
     * @return string Modified content
     */
    private static function process_gallery_blocks($content, $hub_images) {
        // Match Gutenberg gallery blocks
        // Pattern: <!-- wp:gallery --> ... <!-- /wp:gallery -->
        $pattern = '/<!-- wp:gallery(.*?)-->(.*?)<!-- \/wp:gallery -->/s';
        
        $content = preg_replace_callback($pattern, function($matches) use ($hub_images) {
            $block_attrs = $matches[1];
            $block_content = $matches[2];
            
            // Find all image IDs in the block content
            // Pattern: wp-image-123 or "id":123
            preg_match_all('/(?:wp-image-|"id":)(\d+)/', $block_content, $id_matches);
            
            if (empty($id_matches[1])) {
                return $matches[0]; // No IDs found, return unchanged
            }
            
            $modified_content = $block_content;
            $remapped = array();
            
            // Replace each hub ID with spoke ID
            foreach ($id_matches[1] as $hub_id) {
                if (isset($hub_images[$hub_id])) {
                    $spoke_id = $hub_images[$hub_id]['spoke_id'];
                    
                    // Replace wp-image-{hub_id} with wp-image-{spoke_id}
                    $modified_content = str_replace(
                        'wp-image-' . $hub_id,
                        'wp-image-' . $spoke_id,
                        $modified_content
                    );
                    
                    // Replace "id":{hub_id} with "id":{spoke_id}
                    $modified_content = preg_replace(
                        '/"id":' . $hub_id . '(?=\D|$)/',
                        '"id":' . $spoke_id,
                        $modified_content
                    );
                    
                    // Replace image URLs if present
                    if (isset($hub_images[$hub_id]['hub_url']) && isset($hub_images[$hub_id]['spoke_url'])) {
                        $modified_content = str_replace(
                            $hub_images[$hub_id]['hub_url'],
                            $hub_images[$hub_id]['spoke_url'],
                            $modified_content
                        );
                    }
                    
                    $remapped[$hub_id] = $spoke_id;
                } else {
                    error_log("SourceHub Gallery: Hub image ID {$hub_id} not found in image map for Gutenberg block");
                }
            }
            
            if (!empty($remapped)) {
                error_log("SourceHub Gallery: Remapped Gutenberg gallery block IDs: " . json_encode($remapped));
            }
            
            return '<!-- wp:gallery' . $block_attrs . '-->' . $modified_content . '<!-- /wp:gallery -->';
        }, $content);
        
        return $content;
    }

    /**
     * Extract all image IDs from post content (galleries and inline images)
     *
     * @param string $content Post content
     * @return array Array of image IDs
     */
    public static function extract_image_ids($content) {
        $image_ids = array();
        
        // Extract from gallery shortcodes
        if (preg_match_all('/\[gallery[^\]]*ids=["\']([0-9,\s]+)["\'][^\]]*\]/i', $content, $matches)) {
            foreach ($matches[1] as $ids_string) {
                $ids = array_map('trim', explode(',', $ids_string));
                $image_ids = array_merge($image_ids, $ids);
            }
        }
        
        // Extract from Gutenberg blocks
        if (preg_match_all('/(?:wp-image-|"id":)(\d+)/', $content, $matches)) {
            $image_ids = array_merge($image_ids, $matches[1]);
        }
        
        // Extract from img tags
        if (preg_match_all('/wp-image-(\d+)/', $content, $matches)) {
            $image_ids = array_merge($image_ids, $matches[1]);
        }
        
        return array_unique(array_filter($image_ids));
    }

    /**
     * Get image data for syndication
     *
     * @param int $attachment_id Attachment ID
     * @return array|false Image data or false on failure
     */
    public static function get_image_data($attachment_id) {
        $image_url = wp_get_attachment_url($attachment_id);
        
        if (!$image_url) {
            return false;
        }
        
        $image_data = wp_get_attachment_image_src($attachment_id, 'full');
        
        return array(
            'id' => $attachment_id,
            'url' => $image_url,
            'width' => isset($image_data[1]) ? $image_data[1] : null,
            'height' => isset($image_data[2]) ? $image_data[2] : null,
            'title' => get_the_title($attachment_id),
            'alt' => get_post_meta($attachment_id, '_wp_attachment_image_alt', true),
            'caption' => wp_get_attachment_caption($attachment_id),
            'description' => get_post_field('post_content', $attachment_id),
            'filename' => basename($image_url)
        );
    }
}
