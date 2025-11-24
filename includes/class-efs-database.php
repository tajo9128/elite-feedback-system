<?php
/**
 * Database helper class for Elite Feedback System.
 *
 * @package    Elite_Feedback_System
 * @subpackage Elite_Feedback_System/includes
 */

class EFS_Database {

    /**
     * Get all forms
     */
    public static function get_forms($args = array()) {
        global $wpdb;
        $table = $wpdb->prefix . 'efs_forms';
        
        $defaults = array(
            'is_active' => null,
            'form_type' => null,
            'stakeholder_type' => null,
            'academic_year' => null,
            'limit' => 100,
            'offset' => 0,
            'orderby' => 'created_at',
            'order' => 'DESC'
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $where = array('1=1');
        
        if ($args['is_active'] !== null) {
            $where[] = $wpdb->prepare('is_active = %d', $args['is_active']);
        }
        
        if ($args['form_type']) {
            $where[] = $wpdb->prepare('form_type = %s', $args['form_type']);
        }
        
        if ($args['stakeholder_type']) {
            $where[] = $wpdb->prepare('stakeholder_type = %s', $args['stakeholder_type']);
        }
        
        if ($args['academic_year']) {
            $where[] = $wpdb->prepare('academic_year = %s', $args['academic_year']);
        }
        
        $where_clause = implode(' AND ', $where);
        $order_clause = sprintf('%s %s', sanitize_sql_orderby($args['orderby']), $args['order']);
        
        $query = "SELECT * FROM $table WHERE $where_clause ORDER BY $order_clause LIMIT %d OFFSET %d";
        
        return $wpdb->get_results($wpdb->prepare($query, $args['limit'], $args['offset']));
    }
    
    /**
     * Get a single form by ID
     */
    public static function get_form($form_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'efs_forms';
        
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $form_id));
    }
    
    /**
     * Create a new form
     */
    public static function create_form($data) {
        global $wpdb;
        $table = $wpdb->prefix . 'efs_forms';
        
        $defaults = array(
            'created_by' => get_current_user_id(),
            'is_active' => 1,
            'is_anonymous' => 0
        );
        
        $data = wp_parse_args($data, $defaults);
        
        $wpdb->insert($table, $data);
        
        return $wpdb->insert_id;
    }
    
    /**
     * Update a form
     */
    public static function update_form($form_id, $data) {
        global $wpdb;
        $table = $wpdb->prefix . 'efs_forms';
        
        return $wpdb->update($table, $data, array('id' => $form_id));
    }
    
    /**
     * Delete a form
     */
    public static function delete_form($form_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'efs_forms';
        
        return $wpdb->delete($table, array('id' => $form_id));
    }
    
    /**
     * Get questions for a form
     */
    public static function get_questions($form_id, $orderby = 'sort_order', $order = 'ASC') {
        global $wpdb;
        $table = $wpdb->prefix . 'efs_questions';
        
        $query = "SELECT * FROM $table WHERE form_id = %d ORDER BY %s %s";
        
        return $wpdb->get_results($wpdb->prepare(
            $query,
            $form_id,
            sanitize_sql_orderby($orderby),
            $order
        ));
    }
    
    /**
     * Create a question
     */
    public static function create_question($data) {
        global $wpdb;
        $table = $wpdb->prefix . 'efs_questions';
        
        // Serialize options if array
        if (isset($data['options']) && is_array($data['options'])) {
            $data['options'] = json_encode($data['options']);
        }
        
        $wpdb->insert($table, $data);
        
        return $wpdb->insert_id;
    }
    
    /**
     * Update a question
     */
    public static function update_question($question_id, $data) {
        global $wpdb;
        $table = $wpdb->prefix . 'efs_questions';
        
        // Serialize options if array
        if (isset($data['options']) && is_array($data['options'])) {
            $data['options'] = json_encode($data['options']);
        }
        
        return $wpdb->update($table, $data, array('id' => $question_id));
    }
    
    /**
     * Delete a question
     */
    public static function delete_question($question_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'efs_questions';
        
        return $wpdb->delete($table, array('id' => $question_id));
    }
    
    /**
     * Save a response
     */
    public static function save_response($data) {
        global $wpdb;
        $table = $wpdb->prefix . 'efs_responses';
        
        $defaults = array(
            'user_id' => get_current_user_id() ?: null,
            'session_id' => self::get_session_id(),
            'ip_address' => self::get_client_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        );
        
        $data = wp_parse_args($data, $defaults);
        
        $wpdb->insert($table, $data);
        
        return $wpdb->insert_id;
    }
    
    /**
     * Get responses for a form
     */
    public static function get_responses($form_id, $args = array()) {
        global $wpdb;
        $table = $wpdb->prefix . 'efs_responses';
        
        $defaults = array(
            'question_id' => null,
            'user_id' => null,
            'session_id' => null,
            'limit' => 1000,
            'offset' => 0
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $where = array($wpdb->prepare('form_id = %d', $form_id));
        
        if ($args['question_id']) {
            $where[] = $wpdb->prepare('question_id = %d', $args['question_id']);
        }
        
        if ($args['user_id']) {
            $where[] = $wpdb->prepare('user_id = %d', $args['user_id']);
        }
        
        if ($args['session_id']) {
            $where[] = $wpdb->prepare('session_id = %s', $args['session_id']);
        }
        
        $where_clause = implode(' AND ', $where);
        
        $query = "SELECT * FROM $table WHERE $where_clause ORDER BY submitted_at DESC LIMIT %d OFFSET %d";
        
        return $wpdb->get_results($wpdb->prepare($query, $args['limit'], $args['offset']));
    }
    
    /**
     * Get response count for a form
     */
    public static function get_response_count($form_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'efs_responses';
        
        return (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(DISTINCT session_id) FROM $table WHERE form_id = %d",
            $form_id
        ));
    }
    
    /**
     * Get or create session ID
     */
    private static function get_session_id() {
        if (!session_id()) {
            session_start();
        }
        
        if (!isset($_SESSION['efs_session_id'])) {
            $_SESSION['efs_session_id'] = wp_generate_password(32, false);
        }
        
        return $_SESSION['efs_session_id'];
    }
    
    /**
     * Get client IP address
     */
    private static function get_client_ip() {
        $ip = '';
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        }
        
        return sanitize_text_field($ip);
    }
    
    /**
     * Get setting value
     */
    public static function get_setting($key, $default = '') {
        global $wpdb;
        $table = $wpdb->prefix . 'efs_settings';
        
        $value = $wpdb->get_var($wpdb->prepare(
            "SELECT setting_value FROM $table WHERE setting_key = %s",
            $key
        ));
        
        return $value !== null ? $value : $default;
    }
    
    /**
     * Update setting value
     */
    public static function update_setting($key, $value, $type = 'general') {
        global $wpdb;
        $table = $wpdb->prefix . 'efs_settings';
        
        return $wpdb->replace($table, array(
            'setting_key' => $key,
            'setting_value' => $value,
            'setting_type' => $type
        ));
    }
}
