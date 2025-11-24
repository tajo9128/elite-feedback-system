<?php
/**
 * Fired during plugin activation.
 *
 * @package    Elite_Feedback_System
 * @subpackage Elite_Feedback_System/includes
 */

class EFS_Activator {

    /**
     * Activate the plugin.
     *
     * Creates all necessary database tables for the feedback system.
     */
    public static function activate() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        // Create forms table
        $table_forms = $wpdb->prefix . 'efs_forms';
        $sql_forms = "CREATE TABLE IF NOT EXISTS $table_forms (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            description text,
            form_type varchar(50) DEFAULT 'general',
            stakeholder_type varchar(50) DEFAULT 'students',
            naac_criterion tinyint(1),
            nba_outcome varchar(50),
            academic_year varchar(20),
            semester varchar(20),
            department varchar(100),
            is_active tinyint(1) DEFAULT 1,
            is_anonymous tinyint(1) DEFAULT 0,
            start_date datetime,
            end_date datetime,
            created_by bigint(20) UNSIGNED,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY form_type (form_type),
            KEY stakeholder_type (stakeholder_type),
            KEY is_active (is_active),
            KEY academic_year (academic_year)
        ) $charset_collate;";
        dbDelta($sql_forms);
        
        // Create questions table
        $table_questions = $wpdb->prefix . 'efs_questions';
        $sql_questions = "CREATE TABLE IF NOT EXISTS $table_questions (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            form_id bigint(20) UNSIGNED NOT NULL,
            question_text text NOT NULL,
            question_type varchar(50) NOT NULL DEFAULT 'scale',
            options text,
            is_required tinyint(1) DEFAULT 1,
            weightage decimal(5,2) DEFAULT 1.00,
            sort_order int(11) DEFAULT 0,
            naac_criterion tinyint(1),
            nba_outcome varchar(50),
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY form_id (form_id),
            KEY question_type (question_type),
            KEY sort_order (sort_order),
            CONSTRAINT fk_questions_form FOREIGN KEY (form_id) REFERENCES $table_forms(id) ON DELETE CASCADE
        ) $charset_collate;";
        dbDelta($sql_questions);
        
        // Create responses table
        $table_responses = $wpdb->prefix . 'efs_responses';
        $sql_responses = "CREATE TABLE IF NOT EXISTS $table_responses (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            form_id bigint(20) UNSIGNED NOT NULL,
            question_id bigint(20) UNSIGNED NOT NULL,
            user_id bigint(20) UNSIGNED,
            response_value text NOT NULL,
            response_text text,
            session_id varchar(100),
            ip_address varchar(45),
            user_agent text,
            submitted_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY form_id (form_id),
            KEY question_id (question_id),
            KEY user_id (user_id),
            KEY session_id (session_id),
            KEY submitted_at (submitted_at),
            CONSTRAINT fk_responses_form FOREIGN KEY (form_id) REFERENCES $table_forms(id) ON DELETE CASCADE,
            CONSTRAINT fk_responses_question FOREIGN KEY (question_id) REFERENCES $table_questions(id) ON DELETE CASCADE
        ) $charset_collate;";
        dbDelta($sql_responses);
        
        // Create stakeholders table
        $table_stakeholders = $wpdb->prefix . 'efs_stakeholders';
        $sql_stakeholders = "CREATE TABLE IF NOT EXISTS $table_stakeholders (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id bigint(20) UNSIGNED,
            stakeholder_type varchar(50) NOT NULL,
            name varchar(255),
            email varchar(100),
            department varchar(100),
            designation varchar(100),
            year_of_study varchar(20),
            roll_number varchar(50),
            company_name varchar(255),
            graduation_year varchar(20),
            phone varchar(20),
            metadata text,
            is_active tinyint(1) DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY stakeholder_type (stakeholder_type),
            KEY email (email),
            KEY roll_number (roll_number)
        ) $charset_collate;";
        dbDelta($sql_stakeholders);
        
        // Create reports table
        $table_reports = $wpdb->prefix . 'efs_reports';
        $sql_reports = "CREATE TABLE IF NOT EXISTS $table_reports (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            report_type varchar(50) NOT NULL,
            title varchar(255) NOT NULL,
            parameters text,
            file_path varchar(500),
            file_type varchar(20),
            generated_by bigint(20) UNSIGNED,
            generated_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY report_type (report_type),
            KEY generated_at (generated_at)
        ) $charset_collate;";
        dbDelta($sql_reports);
        
        // Create settings table
        $table_settings = $wpdb->prefix . 'efs_settings';
        $sql_settings = "CREATE TABLE IF NOT EXISTS $table_settings (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            setting_key varchar(100) NOT NULL,
            setting_value longtext,
            setting_type varchar(50) DEFAULT 'general',
            PRIMARY KEY (id),
            UNIQUE KEY setting_key (setting_key)
        ) $charset_collate;";
        dbDelta($sql_settings);
        
        // Add default settings
        self::add_default_settings();
        
        // Add version option
        add_option('efs_version', EFS_VERSION);
        add_option('efs_db_version', '1.0.0');
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Add default settings to the database
     */
    private static function add_default_settings() {
        global $wpdb;
        $table_settings = $wpdb->prefix . 'efs_settings';
        
        $default_settings = array(
            array('setting_key' => 'institution_name', 'setting_value' => get_bloginfo('name'), 'setting_type' => 'general'),
            array('setting_key' => 'institution_logo', 'setting_value' => '', 'setting_type' => 'general'),
            array('setting_key' => 'naac_grade', 'setting_value' => '', 'setting_type' => 'accreditation'),
            array('setting_key' => 'nba_accredited_programs', 'setting_value' => '[]', 'setting_type' => 'accreditation'),
            array('setting_key' => 'email_notifications', 'setting_value' => '1', 'setting_type' => 'notifications'),
            array('setting_key' => 'admin_email', 'setting_value' => get_option('admin_email'), 'setting_type' => 'notifications'),
            array('setting_key' => 'allow_anonymous', 'setting_value' => '1', 'setting_type' => 'privacy'),
            array('setting_key' => 'collect_ip_address', 'setting_value' => '0', 'setting_type' => 'privacy'),
        );
        
        foreach ($default_settings as $setting) {
            $wpdb->replace($table_settings, $setting);
        }
    }
}
