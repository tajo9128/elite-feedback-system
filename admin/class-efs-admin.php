<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Elite_Feedback_System
 * @subpackage Elite_Feedback_System/admin
 */

class EFS_Admin {

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     */
    public function enqueue_styles() {
        if ($this->is_plugin_page()) {
            wp_enqueue_style(
                $this->plugin_name,
                EFS_PLUGIN_URL . 'admin/css/efs-admin.css',
                array(),
                $this->version,
                'all'
            );
        }
    }

    /**
     * Register the JavaScript for the admin area.
     */
    public function enqueue_scripts() {
        if ($this->is_plugin_page()) {
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-sortable');
            
            wp_enqueue_script(
                $this->plugin_name,
                EFS_PLUGIN_URL . 'admin/js/efs-admin.js',
                array('jquery', 'jquery-ui-sortable'),
                $this->version,
                true
            );
            
            // Pass data to JavaScript
            wp_localize_script($this->plugin_name, 'efsAdmin', array(
                'apiUrl' => rest_url('efs/v1'),
                'nonce' => wp_create_nonce('wp_rest'),
                'pluginUrl' => EFS_PLUGIN_URL,
                'ajaxUrl' => admin_url('admin-ajax.php')
            ));
        }
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        // Main menu
        add_menu_page(
            'Elite Feedback System',
            'Feedback System',
            'manage_options',
            'efs-dashboard',
            array('EFS_Admin_Pages', 'display_dashboard'),
            'dashicons-feedback',
            30
        );
        
        // Dashboard
        add_submenu_page(
            'efs-dashboard',
            'Dashboard',
            'Dashboard',
            'manage_options',
            'efs-dashboard',
            array('EFS_Admin_Pages', 'display_dashboard')
        );
        
        // Forms List
        add_submenu_page(
            'efs-dashboard',
            'Manage Forms',
            'All Forms',
            'manage_options',
            'efs-forms',
            array('EFS_Admin_Pages', 'display_forms_list')
        );
        
        // Add/Edit Form
        add_submenu_page(
            null, // Hidden from menu
            'Add/Edit Form',
            'Add/Edit Form',
            'manage_options',
            'efs-form-editor',
            array('EFS_Admin_Pages', 'display_form_editor')
        );
        
        // Templates (Quick Create)
        add_submenu_page(
            'efs-dashboard',
            'Create from Template',
            'Templates',
            'manage_options',
            'efs-templates',
            array('EFS_Admin_Pages', 'display_templates')
        );
        
        // Responses
        add_submenu_page(
            null, // Hidden
            'View Responses',
            'Responses',
            'manage_options',
            'efs-responses',
            array('EFS_Admin_Pages', 'display_responses')
        );
        
        // Analytics
        add_submenu_page(
            'efs-dashboard',
            'Analytics',
            'Analytics',
            'manage_options',
            'efs-analytics',
            array('EFS_Admin_Pages', 'display_analytics')
        );
        
        // NAAC Reports
        add_submenu_page(
            'efs-dashboard',
            'NAAC Reports',
            'NAAC Reports',
            'manage_options',
            'efs-naac-reports',
            array('EFS_Admin_Pages', 'display_naac_reports')
        );
        
        // NBA Reports
        add_submenu_page(
            'efs-dashboard',
            'NBA Reports',
            'NBA Reports',
            'manage_options',
            'efs-nba-reports',
            array('EFS_Admin_Pages', 'display_nba_reports')
        );
        
        // Settings
        add_submenu_page(
            'efs-dashboard',
            'Settings',
            'Settings',
            'manage_options',
            'efs-settings',
            array('EFS_Admin_Pages', 'display_settings')
        );
    }

    /**
     * Check if we're on a plugin page
     */
    private function is_plugin_page() {
        $screen = get_current_screen();
        if (!$screen) return false;
        
        return strpos($screen->id, 'efs-') !== false || 
               strpos($screen->id, 'elite-feedback-system') !== false ||
               strpos($screen->id, 'feedback-system') !== false;
    }
}

