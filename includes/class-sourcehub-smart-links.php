<?php
/**
 * SourceHub Smart Links Processor
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * SourceHub Smart Links Class
 */
class SourceHub_Smart_Links {

    /**
     * Process smart links in content for a specific spoke
     *
     * @param string $content Post content
     * @param object $spoke_connection Spoke connection object
     * @return string Processed content with smart links replaced
     */
    public static function process_content($content, $spoke_connection) {
        if (empty($content) || empty($spoke_connection)) {
            error_log('SourceHub Smart Links: Empty content or connection');
            return $content;
        }

        // Get the spoke's base URL
        $spoke_url = rtrim($spoke_connection->url, '/');
        error_log('SourceHub Smart Links: Processing for spoke URL: ' . $spoke_url);
        
        // Find all smart links in the content - try multiple patterns
        $patterns = array(
            '/<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i',
            '/<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i'
        );
        
        $processed_content = $content;
        
        foreach ($patterns as $pattern) {
            error_log('SourceHub Smart Links: Trying pattern: ' . $pattern);
            
            $processed_content = preg_replace_callback($pattern, function($matches) use ($spoke_url) {
                $smart_path = $matches[1];
                $link_text = strip_tags($matches[2]); // Remove any HTML from link text
                
                error_log('SourceHub Smart Links: Found match - Path: ' . $smart_path . ', Text: ' . $link_text);
                
                // Clean up the text
                $link_text = trim($link_text);
                
                // Build the full URL
                $full_url = $spoke_url . $smart_path;
                
                error_log('SourceHub Smart Links: Creating link - URL: ' . $full_url . ', Text: ' . $link_text);
                
                // Create a proper link
                return sprintf(
                    '<a href="%s" class="sourcehub-smart-link-processed">%s</a>',
                    esc_url($full_url),
                    esc_html($link_text)
                );
            }, $processed_content);
            
            // If we made changes, break out of the loop
            if ($processed_content !== $content) {
                break;
            }
        }

        if ($processed_content === $content) {
            error_log('SourceHub Smart Links: No smart links found in content');
        } else {
            error_log('SourceHub Smart Links: Successfully processed smart links');
        }

        return $processed_content;
    }

    /**
     * Process custom smart links in content for a specific spoke
     *
     * @param string $content Post content
     * @param object $spoke_connection Spoke connection object
     * @return string Processed content with custom smart links replaced
     */
    public static function process_custom_content($content, $spoke_connection) {
        if (empty($content) || empty($spoke_connection)) {
            error_log('SourceHub Custom Smart Links: Empty content or connection');
            return $content;
        }

        error_log('SourceHub Custom Smart Links: Processing for connection "' . $spoke_connection->name . '" (ID: ' . $spoke_connection->id . ')');
        error_log('SourceHub Custom Smart Links: Content length: ' . strlen($content));
        
        // Also log to SourceHub activity log
        if (class_exists('SourceHub_Logger')) {
            SourceHub_Logger::log('Custom Smart Links processing started for: ' . $spoke_connection->name, 'info');
        }
        
        // Find all custom smart links in the content
        $patterns = array(
            '/<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/is',
            '/<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/is'
        );
        
        error_log('SourceHub Custom Smart Links: Looking for patterns in content: ' . substr($content, 0, 500));
        
        $processed_content = $content;
        
        foreach ($patterns as $pattern) {
            error_log('SourceHub Custom Smart Links: Trying pattern: ' . $pattern);
            
            $processed_content = preg_replace_callback($pattern, function($matches) use ($spoke_connection) {
                // Handle multiple levels of HTML entity encoding
                $custom_urls_json = $matches[1];
                error_log('SourceHub Custom Smart Links: Raw JSON: ' . $custom_urls_json);
                $custom_urls_json = html_entity_decode($custom_urls_json, ENT_QUOTES | ENT_HTML5);
                error_log('SourceHub Custom Smart Links: After first decode: ' . $custom_urls_json);
                $custom_urls_json = html_entity_decode($custom_urls_json, ENT_QUOTES | ENT_HTML5); // Second pass for double encoding
                error_log('SourceHub Custom Smart Links: After second decode: ' . $custom_urls_json);
                $link_text = strip_tags($matches[2]);
                
                error_log('SourceHub Custom Smart Links: Found match - URLs JSON: ' . $custom_urls_json . ', Text: ' . $link_text);
                error_log('SourceHub Custom Smart Links: Processing for spoke connection: ' . $spoke_connection->name . ' (ID: ' . $spoke_connection->id . ')');
                
                // Decode the JSON to get the URLs for each spoke
                $custom_urls = json_decode($custom_urls_json, true);
                if (!is_array($custom_urls)) {
                    error_log('SourceHub Custom Smart Links: Invalid JSON data: ' . json_last_error_msg());
                    return $matches[0]; // Return original if JSON is invalid
                }
                
                error_log('SourceHub Custom Smart Links: Decoded URLs: ' . print_r($custom_urls, true));
                
                // Get the URL for this specific spoke (try both connection name and ID)
                $spoke_url = '';
                if (isset($custom_urls[$spoke_connection->name])) {
                    $spoke_url = $custom_urls[$spoke_connection->name];
                    error_log('SourceHub Custom Smart Links: Found URL by name "' . $spoke_connection->name . '": ' . $spoke_url);
                } elseif (isset($custom_urls[$spoke_connection->id])) {
                    $spoke_url = $custom_urls[$spoke_connection->id];
                    error_log('SourceHub Custom Smart Links: Found URL by ID "' . $spoke_connection->id . '": ' . $spoke_url);
                }
                
                if (empty($spoke_url)) {
                    error_log('SourceHub Custom Smart Links: No URL found for spoke "' . $spoke_connection->name . '" (ID: ' . $spoke_connection->id . ')');
                    error_log('SourceHub Custom Smart Links: Available URL keys: ' . print_r(array_keys($custom_urls), true));
                    // Return just the text without link if no URL is set for this spoke
                    return esc_html(trim($link_text));
                }
                
                // Clean up the text
                $link_text = trim($link_text);
                
                error_log('SourceHub Custom Smart Links: Creating link - URL: ' . $spoke_url . ', Text: ' . $link_text);
                
                // Create a proper link
                return sprintf(
                    '<a href="%s" class="sourcehub-custom-smart-link-processed">%s</a>',
                    esc_url($spoke_url),
                    esc_html($link_text)
                );
            }, $processed_content);
            
            // If we made changes, break out of the loop
            if ($processed_content !== $content) {
                break;
            }
        }

        if ($processed_content === $content) {
            error_log('SourceHub Custom Smart Links: No custom smart links found in content');
        } else {
            error_log('SourceHub Custom Smart Links: Successfully processed custom smart links');
        }

        return $processed_content;
    }

    /**
     * Process smart links in post title
     *
     * @param string $title Post title
     * @param object $spoke_connection Spoke connection object
     * @return string Processed title with smart links replaced
     */
    public static function process_title($title, $spoke_connection) {
        if (empty($title) || empty($spoke_connection)) {
            return $title;
        }

        // For titles, we just remove the smart link markup and keep the text
        $pattern = '/<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i';
        
        $processed_title = preg_replace_callback($pattern, function($matches) {
            $link_text = strip_tags($matches[2]); // Remove any HTML from link text
            
            // Clean up the text
            return trim($link_text);
        }, $title);

        return $processed_title;
    }

    /**
     * Process custom smart links in post title
     *
     * @param string $title Post title
     * @param object $spoke_connection Spoke connection object
     * @return string Processed title with custom smart links replaced
     */
    public static function process_custom_title($title, $spoke_connection) {
        if (empty($title) || empty($spoke_connection)) {
            return $title;
        }

        // For titles, we just remove the custom smart link markup and keep the text
        $patterns = array(
            '/<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i',
            '/<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i'
        );
        
        $processed_title = $title;
        
        foreach ($patterns as $pattern) {
            $processed_title = preg_replace_callback($pattern, function($matches) {
                $link_text = strip_tags($matches[2]); // Remove any HTML from link text
                
                // Clean up the text
                return trim($link_text);
            }, $processed_title);
            
            // If we made changes, break out of the loop
            if ($processed_title !== $title) {
                break;
            }
        }

        return $processed_title;
    }

    /**
     * Process smart links in excerpt
     *
     * @param string $excerpt Post excerpt
     * @param object $spoke_connection Spoke connection object
     * @return string Processed excerpt with smart links replaced
     */
    public static function process_excerpt($excerpt, $spoke_connection) {
        if (empty($excerpt) || empty($spoke_connection)) {
            return $excerpt;
        }

        // Get the spoke's base URL
        $spoke_url = rtrim($spoke_connection->url, '/');
        
        // Find all smart links in the excerpt
        $pattern = '/<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i';
        
        $processed_excerpt = preg_replace_callback($pattern, function($matches) use ($spoke_url) {
            $smart_path = $matches[1];
            $link_text = strip_tags($matches[2]); // Remove any HTML from link text
            
            // Clean up the text
            $link_text = trim($link_text);
            
            // Build the full URL
            $full_url = $spoke_url . $smart_path;
            
            // Create a proper link
            return sprintf(
                '<a href="%s" class="sourcehub-smart-link-processed">%s</a>',
                esc_url($full_url),
                esc_html($link_text)
            );
        }, $excerpt);

        return $processed_excerpt;
    }

    /**
     * Process custom smart links in excerpt
     *
     * @param string $excerpt Post excerpt
     * @param object $spoke_connection Spoke connection object
     * @return string Processed excerpt with custom smart links replaced
     */
    public static function process_custom_excerpt($excerpt, $spoke_connection) {
        if (empty($excerpt) || empty($spoke_connection)) {
            return $excerpt;
        }

        // Find all custom smart links in the excerpt
        $patterns = array(
            '/<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i',
            '/<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i'
        );
        
        $processed_excerpt = $excerpt;
        
        foreach ($patterns as $pattern) {
            $processed_excerpt = preg_replace_callback($pattern, function($matches) use ($spoke_connection) {
                // Handle multiple levels of HTML entity encoding
                $custom_urls_json = $matches[1];
                $custom_urls_json = html_entity_decode($custom_urls_json, ENT_QUOTES | ENT_HTML5);
                $custom_urls_json = html_entity_decode($custom_urls_json, ENT_QUOTES | ENT_HTML5); // Second pass for double encoding
                $link_text = strip_tags($matches[2]);
                
                // Decode the JSON to get the URLs for each spoke
                $custom_urls = json_decode($custom_urls_json, true);
                if (!is_array($custom_urls)) {
                    return $matches[0]; // Return original if JSON is invalid
                }
                
                // Get the URL for this specific spoke (try both connection name and ID)
                $spoke_url = '';
                if (isset($custom_urls[$spoke_connection->name])) {
                    $spoke_url = $custom_urls[$spoke_connection->name];
                } elseif (isset($custom_urls[$spoke_connection->id])) {
                    $spoke_url = $custom_urls[$spoke_connection->id];
                }
                
                if (empty($spoke_url)) {
                    // Return just the text without link if no URL is set for this spoke
                    return esc_html(trim($link_text));
                }
                
                // Clean up the text
                $link_text = trim($link_text);
                
                // Create a proper link
                return sprintf(
                    '<a href="%s" class="sourcehub-custom-smart-link-processed">%s</a>',
                    esc_url($spoke_url),
                    esc_html($link_text)
                );
            }, $processed_excerpt);
            
            // If we made changes, break out of the loop
            if ($processed_excerpt !== $excerpt) {
                break;
            }
        }

        return $processed_excerpt;
    }

    /**
     * Get all smart links from content for debugging
     *
     * @param string $content Content to analyze
     * @return array Array of smart links found
     */
    public static function get_smart_links($content) {
        $smart_links = array();
        
        if (empty($content)) {
            return $smart_links;
        }

        $pattern = '/<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i';
        
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $match) {
            $smart_links[] = array(
                'path' => $match[1],
                'text' => strip_tags($match[2]),
                'full_match' => $match[0]
            );
        }

        return $smart_links;
    }

    /**
     * Validate smart link path
     *
     * @param string $path URL path to validate
     * @return bool True if valid, false otherwise
     */
    public static function validate_path($path) {
        if (empty($path)) {
            return false;
        }

        // Basic validation - should start with / or be a relative path
        if (!preg_match('/^\/[a-zA-Z0-9\-_\/]*$/', $path) && !preg_match('/^[a-zA-Z0-9\-_\/]+$/', $path)) {
            return false;
        }

        return true;
    }

    /**
     * Log smart link processing for debugging
     *
     * @param string $content Original content
     * @param string $processed_content Processed content
     * @param object $spoke_connection Spoke connection
     */
    public static function log_processing($content, $processed_content, $spoke_connection) {
        $smart_links = self::get_smart_links($content);
        
        if (!empty($smart_links)) {
            error_log(sprintf(
                'SourceHub Smart Links: Processed %d smart links for spoke "%s" (%s)',
                count($smart_links),
                $spoke_connection->name,
                $spoke_connection->url
            ));
            
            foreach ($smart_links as $link) {
                error_log(sprintf(
                    'SourceHub Smart Links: "%s" -> %s%s',
                    $link['text'],
                    rtrim($spoke_connection->url, '/'),
                    $link['path']
                ));
            }
        }
    }
}
