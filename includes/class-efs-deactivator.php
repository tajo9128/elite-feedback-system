<?php
/**
 * Fired during plugin deactivation.
 *
 * @package    Elite_Feedback_System
 * @subpackage Elite_Feedback_System/includes
 */

class EFS_Deactivator {

    /**
     * Deactivate the plugin.
     *
     * Preserves data by default. Use NAAC/NBA Feedback > Settings to completely remove data.
     */
    public static function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Clear any scheduled cron jobs
        wp_clear_scheduled_hook('efs_send_feedback_reminders');
        wp_clear_scheduled_hook('efs_generate_periodic_reports');
        
        // Note: We do NOT delete database tables on deactivation
        // to preserve valuable feedback data for accreditation purposes.
        // Data can be manually deleted through the plugin settings page.
    }
    
    /**
     * Uninstall the plugin and remove all data.
     * This should only be called if user explicitly chooses to remove all data.
     */
    public static function uninstall() {
        global $wpdb;
        
        // Delete all plugin tables
        $tables = array(
            $wpdb->prefix . 'efs_forms',
            $wpdb->prefix . 'efs_questions',
            $wpdb->prefix . 'efs_responses',
            $wpdb->prefix . 'efs_stakeholders',
            $wpdb->prefix . 'efs_reports',
            $wpdb->prefix . 'efs_settings'
        );
        
        foreach ($tables as $table) {
            $wpdb->query("DROP TABLE IF EXISTS $table");
        }
        
        // Delete all plugin options
        delete_option('efs_version');
        delete_option('efs_db_version');
        
        // Delete uploaded files
        $upload_dir = wp_upload_dir();
        $efs_upload_dir = $upload_dir['basedir'] . '/elite-feedback-system';
        
        if (file_exists($efs_upload_dir)) {
            self::delete_directory($efs_upload_dir);
        }
    }
    
    /**
     * Recursively delete a directory
     */
    private static function delete_directory($dir) {
        if (!file_exists($dir)) {
            return true;
        }
        
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            
            if (!self::delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        
        return rmdir($dir);
    }
}
