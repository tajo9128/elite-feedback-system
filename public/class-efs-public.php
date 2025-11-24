<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @package    Elite_Feedback_System
 * @subpackage Elite_Feedback_System/public
 */

class EFS_Public {

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side.
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            $this->plugin_name,
            EFS_PLUGIN_URL . 'public/css/feedback-styles.css',
            array(),
            $this->version,
            'all'
        );
    }

    /**
     * Register the JavaScript for the public-facing side.
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            $this->plugin_name,
            EFS_PLUGIN_URL . 'public/js/feedback-widget.js',
            array('jquery'),
            $this->version,
            true
        );
        
        wp_localize_script($this->plugin_name, 'efsPublicConfig', array(
            'apiUrl' => rest_url('efs/v1'),
            'nonce' => wp_create_nonce('wp_rest')
        ));
    }
}
