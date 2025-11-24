<?php
/**
 * The core plugin class.
 *
 * @package    Elite_Feedback_System
 * @subpackage Elite_Feedback_System/includes
 */

class Elite_Feedback_System {

    /**
     * The loader that's responsible for maintaining and registering all hooks.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     */
    protected $version;

    /**
     * Initialize the plugin.
     */
    public function __construct() {
        $this->version = EFS_VERSION;
        $this->plugin_name = 'elite-feedback-system';

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_api_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     */
    private function load_dependencies() {
        // Core classes
        require_once EFS_PLUGIN_DIR . 'includes/class-efs-loader.php';
        require_once EFS_PLUGIN_DIR . 'includes/class-efs-database.php';
        
        // Admin classes
        require_once EFS_PLUGIN_DIR . 'admin/class-efs-admin.php';
        require_once EFS_PLUGIN_DIR . 'admin/class-efs-admin-pages.php';
        
        // Public classes
        require_once EFS_PLUGIN_DIR . 'public/class-efs-public.php';
        require_once EFS_PLUGIN_DIR . 'public/class-efs-shortcodes.php';
        
        // API classes
        require_once EFS_PLUGIN_DIR . 'includes/api/class-efs-api.php';
        
        // Utility classes
        require_once EFS_PLUGIN_DIR . 'includes/class-efs-email.php';
        require_once EFS_PLUGIN_DIR . 'includes/class-efs-export.php';
        require_once EFS_PLUGIN_DIR . 'includes/class-efs-analytics.php';
        
        // Template classes
        require_once EFS_PLUGIN_DIR . 'templates/class-efs-naac-templates.php';
        require_once EFS_PLUGIN_DIR . 'templates/class-efs-nba-templates.php';

        $this->loader = new EFS_Loader();
    }

    /**
     * Register all admin-related hooks.
     */
    private function define_admin_hooks() {
        $plugin_admin = new EFS_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_admin_menu');
    }

    /**
     * Register all public-facing hooks.
     */
    private function define_public_hooks() {
        $plugin_public = new EFS_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        
        // Register shortcodes
        $shortcodes = new EFS_Shortcodes();
        $this->loader->add_action('init', $shortcodes, 'register_shortcodes');
    }

    /**
     * Register all API-related hooks.
     */
    private function define_api_hooks() {
        $plugin_api = new EFS_API();
        $this->loader->add_action('rest_api_init', $plugin_api, 'register_routes');
    }

    /**
     * Run the loader to execute all hooks.
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * Get the plugin name.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * Get the plugin version.
     */
    public function get_version() {
        return $this->version;
    }
}
