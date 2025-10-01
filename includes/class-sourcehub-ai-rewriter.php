<?php
/**
 * AI content rewriting functionality
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * SourceHub AI Rewriter Class
 */
class SourceHub_AI_Rewriter {

    /**
     * OpenAI API endpoint
     */
    const OPENAI_API_ENDPOINT = 'https://api.openai.com/v1/chat/completions';

    /**
     * Default model
     */
    const DEFAULT_MODEL = 'gpt-4';

    /**
     * Rewrite content based on settings
     *
     * @param array $post_data Post data
     * @param array $ai_settings AI rewriting settings
     * @return array Modified post data
     */
    public function rewrite_content($post_data, $ai_settings) {
        if (empty($ai_settings['enabled']) || !$this->is_configured()) {
            return $post_data;
        }

        try {
            // Rewrite title if enabled
            if (!empty($ai_settings['rewrite_title'])) {
                $new_title = $this->rewrite_title($post_data['title'], $ai_settings);
                if ($new_title) {
                    $post_data['title'] = $new_title;
                }
            }

            // Rewrite content if enabled
            if (!empty($ai_settings['rewrite_content'])) {
                $new_content = $this->rewrite_post_content($post_data['content'], $ai_settings);
                if ($new_content) {
                    $post_data['content'] = $new_content;
                }
            }

            // Rewrite excerpt if enabled and exists
            if (!empty($ai_settings['rewrite_excerpt']) && !empty($post_data['excerpt'])) {
                $new_excerpt = $this->rewrite_excerpt($post_data['excerpt'], $ai_settings);
                if ($new_excerpt) {
                    $post_data['excerpt'] = $new_excerpt;
                }
            }

            // Rewrite SEO meta if enabled
            if (!empty($ai_settings['rewrite_seo']) && !empty($post_data['yoast_meta'])) {
                $post_data['yoast_meta'] = $this->rewrite_seo_meta($post_data['yoast_meta'], $ai_settings);
            }

            SourceHub_Logger::success(
                'Content successfully rewritten using AI',
                array('settings' => $ai_settings),
                null,
                null,
                'ai_rewrite'
            );

        } catch (Exception $e) {
            SourceHub_Logger::error(
                'AI rewriting failed: ' . $e->getMessage(),
                array('settings' => $ai_settings, 'error' => $e->getMessage()),
                null,
                null,
                'ai_rewrite'
            );
        }

        return $post_data;
    }

    /**
     * Rewrite post title
     *
     * @param string $title Original title
     * @param array $settings AI settings
     * @return string|false Rewritten title or false on failure
     */
    public function rewrite_title($title, $settings) {
        $prompt = $this->build_title_prompt($title, $settings);
        
        $response = $this->call_openai_api($prompt, array(
            'max_tokens' => 100,
            'temperature' => 0.7
        ));

        if ($response && isset($response['choices'][0]['message']['content'])) {
            return trim($response['choices'][0]['message']['content']);
        }

        return false;
    }

    /**
     * Rewrite post content
     *
     * @param string $content Original content
     * @param array $settings AI settings
     * @return string|false Rewritten content or false on failure
     */
    public function rewrite_post_content($content, $settings) {
        // Check content length limits (strip tags only for counting, not for processing)
        $word_count = str_word_count(strip_tags($content));
        $max_words = get_option('sourcehub_ai_max_words', 2000);
        
        if ($word_count > $max_words) {
            SourceHub_Logger::warning(
                sprintf('Content too long for AI rewriting (%d words, max %d)', $word_count, $max_words),
                array('word_count' => $word_count, 'max_words' => $max_words),
                null,
                null,
                'ai_rewrite'
            );
            return false;
        }

        $prompt = $this->build_content_prompt($content, $settings);
        
        $response = $this->call_openai_api($prompt, array(
            'max_tokens' => min(4000, $word_count * 2),
            'temperature' => 0.8
        ));

        if ($response && isset($response['choices'][0]['message']['content'])) {
            return trim($response['choices'][0]['message']['content']);
        }

        return false;
    }

    /**
     * Rewrite post excerpt
     *
     * @param string $excerpt Original excerpt
     * @param array $settings AI settings
     * @return string|false Rewritten excerpt or false on failure
     */
    public function rewrite_excerpt($excerpt, $settings) {
        $prompt = $this->build_excerpt_prompt($excerpt, $settings);
        
        $response = $this->call_openai_api($prompt, array(
            'max_tokens' => 200,
            'temperature' => 0.7
        ));

        if ($response && isset($response['choices'][0]['message']['content'])) {
            return trim($response['choices'][0]['message']['content']);
        }

        return false;
    }

    /**
     * Rewrite SEO meta data
     *
     * @param array $yoast_meta Original Yoast meta
     * @param array $settings AI settings
     * @return array Modified Yoast meta
     */
    public function rewrite_seo_meta($yoast_meta, $settings) {
        $modified_meta = $yoast_meta;

        // Rewrite SEO title
        if (!empty($yoast_meta['_yoast_wpseo_title'])) {
            $new_title = $this->rewrite_seo_title($yoast_meta['_yoast_wpseo_title'], $settings);
            if ($new_title) {
                $modified_meta['_yoast_wpseo_title'] = $new_title;
            }
        }

        // Rewrite meta description
        if (!empty($yoast_meta['_yoast_wpseo_metadesc'])) {
            $new_desc = $this->rewrite_meta_description($yoast_meta['_yoast_wpseo_metadesc'], $settings);
            if ($new_desc) {
                $modified_meta['_yoast_wpseo_metadesc'] = $new_desc;
            }
        }

        // Rewrite social media titles and descriptions
        $social_fields = array(
            '_yoast_wpseo_twitter-title',
            '_yoast_wpseo_twitter-description',
            '_yoast_wpseo_opengraph-title',
            '_yoast_wpseo_opengraph-description'
        );

        foreach ($social_fields as $field) {
            if (!empty($yoast_meta[$field])) {
                $is_title = strpos($field, 'title') !== false;
                $new_value = $is_title ? 
                    $this->rewrite_seo_title($yoast_meta[$field], $settings) :
                    $this->rewrite_meta_description($yoast_meta[$field], $settings);
                
                if ($new_value) {
                    $modified_meta[$field] = $new_value;
                }
            }
        }

        return $modified_meta;
    }

    /**
     * Rewrite SEO title
     *
     * @param string $title Original SEO title
     * @param array $settings AI settings
     * @return string|false Rewritten title or false on failure
     */
    private function rewrite_seo_title($title, $settings) {
        $prompt = sprintf(
            "Rewrite this SEO title to be more engaging while keeping it under 60 characters and maintaining the core message. It is important not to wrap the title in quotes %s\n\nOriginal title: %s\n\nRewritten title:",
            $this->get_tone_instruction($settings),
            $title
        );

        $response = $this->call_openai_api($prompt, array(
            'max_tokens' => 80,
            'temperature' => 0.7
        ));

        if ($response && isset($response['choices'][0]['message']['content'])) {
            $rewritten_title = trim($response['choices'][0]['message']['content']);
            
            // Remove only wrapping quotes, not quotes that are part of the content
            // Check if title starts and ends with the same quote type
            if ((substr($rewritten_title, 0, 1) === '"' && substr($rewritten_title, -1) === '"') ||
                (substr($rewritten_title, 0, 1) === "'" && substr($rewritten_title, -1) === "'")) {
                $rewritten_title = substr($rewritten_title, 1, -1);
            }
            
            return $rewritten_title;
        }

        return false;
    }

    /**
     * Rewrite meta description
     *
     * @param string $description Original meta description
     * @param array $settings AI settings
     * @return string|false Rewritten description or false on failure
     */
    private function rewrite_meta_description($description, $settings) {
        $prompt = sprintf(
            "Rewrite this meta description to be more compelling while keeping it under 160 characters and maintaining the core message. %s\n\nOriginal description: %s\n\nRewritten description:",
            $this->get_tone_instruction($settings),
            $description
        );

        $response = $this->call_openai_api($prompt, array(
            'max_tokens' => 200,
            'temperature' => 0.7
        ));

        if ($response && isset($response['choices'][0]['message']['content'])) {
            return trim($response['choices'][0]['message']['content']);
        }

        return false;
    }

    /**
     * Build title rewriting prompt
     *
     * @param string $title Original title
     * @param array $settings AI settings
     * @return string
     */
    private function build_title_prompt($title, $settings) {
        $instructions = array();
        
        if (!empty($settings['tone'])) {
            $instructions[] = sprintf('Use a %s tone', $settings['tone']);
        }
        
        if (!empty($settings['target_audience'])) {
            $instructions[] = sprintf('Target audience: %s', $settings['target_audience']);
        }
        
        if (!empty($settings['regional_focus'])) {
            $instructions[] = sprintf('Add regional focus for: %s', $settings['regional_focus']);
        }

        $instruction_text = !empty($instructions) ? implode('. ', $instructions) . '.' : '';

        return sprintf(
            "Rewrite this article title to make it more engaging and clickable while preserving the core meaning. Do not change any direct quotes or quoted material. %s\n\nOriginal title: %s\n\nRewritten title:",
            $instruction_text,
            $title
        );
    }

    /**
     * Build content rewriting prompt
     *
     * @param string $content Original content
     * @param array $settings AI settings
     * @return string
     */
    private function build_content_prompt($content, $settings) {
        $instructions = array();
        
        if (!empty($settings['tone'])) {
            $instructions[] = sprintf('Use a %s tone throughout', $settings['tone']);
        }
        
        if (!empty($settings['length_adjustment'])) {
            switch ($settings['length_adjustment']) {
                case 'expand':
                    $instructions[] = 'Expand the content with additional details and examples';
                    break;
                case 'condense':
                    $instructions[] = 'Condense the content while keeping all key information';
                    break;
            }
        }
        
        if (!empty($settings['target_audience'])) {
            $instructions[] = sprintf('Write for this target audience: %s', $settings['target_audience']);
        }
        
        if (!empty($settings['regional_focus'])) {
            $instructions[] = sprintf('Add regional context and references for: %s', $settings['regional_focus']);
        }
        
        if (!empty($settings['custom_instructions'])) {
            $instructions[] = $settings['custom_instructions'];
        }

        $instruction_text = !empty($instructions) ? implode('. ', $instructions) . '.' : '';

        return sprintf(
            "Rewrite this article content to make it more engaging while preserving all factual information and key points. Do not change any direct quotes or quoted material - keep all quotes exactly as they appear in the original. IMPORTANT: Preserve all HTML tags, iframes, and embedded content exactly as they appear - do not remove or modify any HTML elements. %s\n\nOriginal content:\n%s\n\nRewritten content:",
            $instruction_text,
            $content
        );
    }

    /**
     * Build excerpt rewriting prompt
     *
     * @param string $excerpt Original excerpt
     * @param array $settings AI settings
     * @return string
     */
    private function build_excerpt_prompt($excerpt, $settings) {
        $instruction_text = $this->get_tone_instruction($settings);

        return sprintf(
            "Rewrite this article excerpt to make it more compelling and engaging. Do not change any direct quotes or quoted material. %s\n\nOriginal excerpt: %s\n\nRewritten excerpt:",
            $instruction_text,
            $excerpt
        );
    }

    /**
     * Get tone instruction from settings
     *
     * @param array $settings AI settings
     * @return string
     */
    private function get_tone_instruction($settings) {
        $instructions = array();
        
        if (!empty($settings['tone'])) {
            $instructions[] = sprintf('Use a %s tone', $settings['tone']);
        }
        
        if (!empty($settings['target_audience'])) {
            $instructions[] = sprintf('Target audience: %s', $settings['target_audience']);
        }

        return !empty($instructions) ? implode('. ', $instructions) . '.' : '';
    }

    /**
     * Call OpenAI API
     *
     * @param string $prompt The prompt to send
     * @param array $options API options
     * @return array|false API response or false on failure
     */
    private function call_openai_api($prompt, $options = array()) {
        $api_key = get_option('sourcehub_openai_api_key');
        
        if (empty($api_key)) {
            throw new Exception('OpenAI API key not configured');
        }

        $defaults = array(
            'model' => get_option('sourcehub_openai_model', self::DEFAULT_MODEL),
            'max_tokens' => 1000,
            'temperature' => 0.7,
            'top_p' => 1,
            'frequency_penalty' => 0,
            'presence_penalty' => 0
        );

        $options = wp_parse_args($options, $defaults);

        $body = array(
            'model' => $options['model'],
            'messages' => array(
                array(
                    'role' => 'user',
                    'content' => $prompt
                )
            ),
            'max_tokens' => $options['max_tokens'],
            'temperature' => $options['temperature'],
            'top_p' => $options['top_p'],
            'frequency_penalty' => $options['frequency_penalty'],
            'presence_penalty' => $options['presence_penalty']
        );

        $response = wp_remote_post(self::OPENAI_API_ENDPOINT, array(
            'timeout' => 60,
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $api_key
            ),
            'body' => json_encode($body)
        ));

        if (is_wp_error($response)) {
            throw new Exception('API request failed: ' . $response->get_error_message());
        }

        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);

        if ($response_code !== 200) {
            $error_data = json_decode($response_body, true);
            $error_message = isset($error_data['error']['message']) ? 
                $error_data['error']['message'] : 
                'API request failed with code ' . $response_code;
            
            throw new Exception($error_message);
        }

        return json_decode($response_body, true);
    }

    /**
     * Check if AI rewriting is configured
     *
     * @return bool
     */
    public function is_configured() {
        $api_key = get_option('sourcehub_openai_api_key');
        return !empty($api_key);
    }

    /**
     * Test API connection
     *
     * @return array Test result
     */
    public function test_api_connection() {
        if (!$this->is_configured()) {
            return array(
                'success' => false,
                'message' => __('OpenAI API key not configured', 'sourcehub')
            );
        }

        try {
            $response = $this->call_openai_api('Say "Hello, SourceHub!" to test the connection.', array(
                'max_tokens' => 50,
                'temperature' => 0
            ));

            if ($response && isset($response['choices'][0]['message']['content'])) {
                return array(
                    'success' => true,
                    'message' => __('API connection successful', 'sourcehub'),
                    'response' => trim($response['choices'][0]['message']['content'])
                );
            } else {
                return array(
                    'success' => false,
                    'message' => __('Invalid API response', 'sourcehub')
                );
            }

        } catch (Exception $e) {
            return array(
                'success' => false,
                'message' => $e->getMessage()
            );
        }
    }

    /**
     * Get available models
     *
     * @return array
     */
    public static function get_available_models() {
        return array(
            'gpt-4' => 'GPT-4 (Recommended)',
            'gpt-4-turbo-preview' => 'GPT-4 Turbo',
            'gpt-4o-mini' => 'GPT-4o Mini (Most Cost-Effective)',
            'gpt-3.5-turbo' => 'GPT-3.5 Turbo (Faster, Lower Cost)',
            'gpt-3.5-turbo-16k' => 'GPT-3.5 Turbo 16K'
        );
    }

    /**
     * Get available tones
     *
     * @return array
     */
    public static function get_available_tones() {
        return array(
            'professional' => __('Professional', 'sourcehub'),
            'casual' => __('Casual', 'sourcehub'),
            'formal' => __('Formal', 'sourcehub'),
            'friendly' => __('Friendly', 'sourcehub'),
            'authoritative' => __('Authoritative', 'sourcehub'),
            'conversational' => __('Conversational', 'sourcehub'),
            'urgent' => __('Urgent', 'sourcehub'),
            'neutral' => __('Neutral', 'sourcehub')
        );
    }

    /**
     * Get length adjustment options
     *
     * @return array
     */
    public static function get_length_adjustments() {
        return array(
            'maintain' => __('Maintain Length', 'sourcehub'),
            'expand' => __('Expand Content', 'sourcehub'),
            'condense' => __('Condense Content', 'sourcehub')
        );
    }

    /**
     * Estimate API cost for content
     *
     * @param string $content Content to estimate
     * @param string $model Model to use
     * @return array Cost estimation
     */
    public function estimate_cost($content, $model = 'gpt-4') {
        // Rough token estimation (1 token ≈ 4 characters)
        $input_tokens = strlen($content) / 4;
        $estimated_output_tokens = $input_tokens * 1.2; // Assume 20% expansion
        
        // Pricing per 1K tokens (as of 2024)
        $pricing = array(
            'gpt-4' => array('input' => 0.03, 'output' => 0.06),
            'gpt-4-turbo-preview' => array('input' => 0.01, 'output' => 0.03),
            'gpt-4o-mini' => array('input' => 0.00015, 'output' => 0.0006),
            'gpt-3.5-turbo' => array('input' => 0.0015, 'output' => 0.002),
            'gpt-3.5-turbo-16k' => array('input' => 0.003, 'output' => 0.004)
        );

        if (!isset($pricing[$model])) {
            $model = 'gpt-4';
        }

        $input_cost = ($input_tokens / 1000) * $pricing[$model]['input'];
        $output_cost = ($estimated_output_tokens / 1000) * $pricing[$model]['output'];
        $total_cost = $input_cost + $output_cost;

        return array(
            'input_tokens' => round($input_tokens),
            'estimated_output_tokens' => round($estimated_output_tokens),
            'input_cost' => $input_cost,
            'output_cost' => $output_cost,
            'total_cost' => $total_cost,
            'formatted_cost' => '$' . number_format($total_cost, 4)
        );
    }
}
