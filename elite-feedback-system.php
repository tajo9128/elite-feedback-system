<?php
/**
 * Plugin Name: Elite Feedback System
 * Plugin URI: https://github.com/yourusername/elite-feedback-system
 * Description: Comprehensive feedback collection and analytics system for NAAC and NBA accreditation compliance. Collect multi-stakeholder feedback, generate detailed reports, and streamline your accreditation process.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: elite-feedback-system
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 */
define('EFS_VERSION', '1.0.0');
define('EFS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('EFS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('EFS_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * The code that runs during plugin activation.
 */
function activate_elite_feedback_system() {
    require_once EFS_PLUGIN_DIR . 'includes/class-efs-activator.php';
    EFS_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_elite_feedback_system() {
    require_once EFS_PLUGIN_DIR . 'includes/class-efs-deactivator.php';
    EFS_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_elite_feedback_system');
register_deactivation_hook(__FILE__, 'deactivate_elite_feedback_system');

/**
 * The core plugin class.
 */
require EFS_PLUGIN_DIR . 'includes/class-elite-feedback-system.php';

/**
 * Begins execution of the plugin.
 */
function run_elite_feedback_system() {
    $plugin = new Elite_Feedback_System();
    $plugin->run();
}

run_elite_feedback_system();
