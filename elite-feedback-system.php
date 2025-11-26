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
