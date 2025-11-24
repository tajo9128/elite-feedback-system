<?php
/**
 * Admin Pages Handler
 *
 * @package    Elite_Feedback_System
 * @subpackage Elite_Feedback_System/admin
 */

class EFS_Admin_Pages {

    /**
     * Display Dashboard Page
     */
    public static function display_dashboard() {
        // Get overview statistics
        $stats = EFS_Analytics::get_overview();
        
        include EFS_PLUGIN_DIR . 'admin/views/dashboard.php';
    }

    /**
     * Display Forms List Page
     */
    public static function display_forms_list() {
        global $wpdb;
        
        // Handle form deletion
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['form_id'])) {
            check_admin_referer('delete_form_' . $_GET['form_id']);
            EFS_Database::delete_form(intval($_GET['form_id']));
            echo '<div class="notice notice-success"><p>Form deleted successfully.</p></div>';
        }
        
        // Handle form activation toggle
        if (isset($_GET['action']) && $_GET['action'] === 'toggle' && isset($_GET['form_id'])) {
            check_admin_referer('toggle_form_' . $_GET['form_id']);
            $form = EFS_Database::get_form(intval($_GET['form_id']));
            EFS_Database::update_form($_GET['form_id'], array('is_active' => $form->is_active ? 0 : 1));
            echo '<div class="notice notice-success"><p>Form status updated.</p></div>';
        }
        
        // Get all forms
        $forms = EFS_Database::get_forms(array('limit' => 100));
        
        include EFS_PLUGIN_DIR . 'admin/views/forms-list.php';
    }

    /**
     * Display Add/Edit Form Page
     */
    public static function display_form_editor() {
        $form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
        $form = null;
        $questions = array();
        
        if ($form_id) {
            $form = EFS_Database::get_form($form_id);
            $questions = EFS_Database::get_questions($form_id);
        }
        
        // Handle form submission
        if (isset($_POST['efs_save_form'])) {
            check_admin_referer('efs_form_editor');
            
            $form_data = array(
                'title' => sanitize_text_field($_POST['title']),
                'description' => sanitize_textarea_field($_POST['description']),
                'form_type' => sanitize_text_field($_POST['form_type']),
                'stakeholder_type' => sanitize_text_field($_POST['stakeholder_type']),
                'naac_criterion' => !empty($_POST['naac_criterion']) ? intval($_POST['naac_criterion']) : null,
                'nba_outcome' => sanitize_text_field($_POST['nba_outcome']),
                'academic_year' => sanitize_text_field($_POST['academic_year']),
                'semester' => sanitize_text_field($_POST['semester']),
                'department' => sanitize_text_field($_POST['department']),
                'is_active' => isset($_POST['is_active']) ? 1 : 0,
                'is_anonymous' => isset($_POST['is_anonymous']) ? 1 : 0,
                'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
                'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null
            );
            
            if ($form_id) {
                // Update existing form
                EFS_Database::update_form($form_id, $form_data);
                $saved_form_id = $form_id;
            } else {
                // Create new form
                $saved_form_id = EFS_Database::create_form($form_data);
            }
            
            // Save questions
            if (isset($_POST['questions']) && is_array($_POST['questions'])) {
                // Delete existing questions if updating
                if ($form_id) {
                    $existing_questions = EFS_Database::get_questions($form_id);
                    foreach ($existing_questions as $q) {
                        EFS_Database::delete_question($q->id);
                    }
                }
                
                // Add new questions
                foreach ($_POST['questions'] as $index => $question_data) {
                    if (empty($question_data['question_text'])) continue;
                    
                    $question = array(
                        'form_id' => $saved_form_id,
                        'question_text' => sanitize_textarea_field($question_data['question_text']),
                        'question_type' => sanitize_text_field($question_data['question_type']),
                        'is_required' => isset($question_data['is_required']) ? 1 : 0,
                        'weightage' => isset($question_data['weightage']) ? floatval($question_data['weightage']) : 1.0,
                        'sort_order' => $index,
                        'naac_criterion' => !empty($question_data['naac_criterion']) ? intval($question_data['naac_criterion']) : null,
                        'nba_outcome' => sanitize_text_field($question_data['nba_outcome'])
                    );
                    
                    // Handle options for MCQ, checkbox, scale
                    if (!empty($question_data['options'])) {
                        $question['options'] = $question_data['options'];
                    }
                    
                    EFS_Database::create_question($question);
                }
            }
            
            wp_redirect(admin_url('admin.php?page=efs-forms&message=saved'));
            exit;
        }
        
        include EFS_PLUGIN_DIR . 'admin/views/form-editor.php';
    }

    /**
     * Display Form Responses Page
     */
    public static function display_responses() {
        $form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
        
        if (!$form_id) {
            echo '<div class="wrap"><h1>Form Responses</h1><p>Please select a form to view responses.</p></div>';
            return;
        }
        
        $form = EFS_Database::get_form($form_id);
        $questions = EFS_Database::get_questions($form_id);
        $responses = EFS_Database::get_responses($form_id, array('limit' => 500));
        
        // Group responses by session
        $sessions = array();
        foreach ($responses as $response) {
            if (!isset($sessions[$response->session_id])) {
                $sessions[$response->session_id] = array(
                    'session_id' => $response->session_id,
                    'submitted_at' => $response->submitted_at,
                    'responses' => array()
                );
            }
            $sessions[$response->session_id]['responses'][$response->question_id] = $response->response_value;
        }
        
        include EFS_PLUGIN_DIR . 'admin/views/responses.php';
    }

    /**
     * Display Analytics Page
     */
    public static function display_analytics() {
        $form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
        
        if ($form_id) {
            $form = EFS_Database::get_form($form_id);
            $analytics = EFS_Analytics::get_form_analytics($form_id);
            include EFS_PLUGIN_DIR . 'admin/views/analytics-single.php';
        } else {
            $overview = EFS_Analytics::get_overview();
            $forms = EFS_Database::get_forms(array('is_active' => 1, 'limit' => 50));
            include EFS_PLUGIN_DIR . 'admin/views/analytics-overview.php';
        }
    }

    /**
     * Display NAAC Reports Page
     */
    public static function display_naac_reports() {
        // Handle report generation
        if (isset($_POST['generate_naac_report'])) {
            check_admin_referer('generate_naac_report');
            
            $criterion = !empty($_POST['criterion']) ? intval($_POST['criterion']) : null;
            $academic_year = sanitize_text_field($_POST['academic_year']);
            $format = sanitize_text_field($_POST['format']);
            
            $report = EFS_Export::generate_naac_report($criterion, $academic_year, $format);
            
            if ($report['success']) {
                echo '<div class="notice notice-success"><p>Report generated! <a href="' . esc_url($report['file_url']) . '" target="_blank">Download Report</a></p></div>';
            }
        }
        
        $criteria = EFS_NAAC_Templates::get_criteria();
        include EFS_PLUGIN_DIR . 'admin/views/naac-reports.php';
    }

    /**
     * Display NBA Reports Page
     */
    public static function display_nba_reports() {
        // Handle report generation
        if (isset($_POST['generate_nba_report'])) {
            check_admin_referer('generate_nba_report');
            
            $program = sanitize_text_field($_POST['program']);
            $academic_year = sanitize_text_field($_POST['academic_year']);
            $format = sanitize_text_field($_POST['format']);
            
            $report = EFS_Export::generate_nba_report($program, $academic_year, $format);
            
            if ($report['success']) {
                echo '<div class="notice notice-success"><p>Report generated! <a href="' . esc_url($report['file_url']) . '" target="_blank">Download Report</a></p></div>';
            }
        }
        
        $outcomes = EFS_NBA_Templates::get_program_outcomes();
        include EFS_PLUGIN_DIR . 'admin/views/nba-reports.php';
    }

    /**
     * Display Settings Page
     */
    public static function display_settings() {
        // Handle settings save
        if (isset($_POST['efs_save_settings'])) {
            check_admin_referer('efs_settings');
            
            $settings = array(
                'institution_name' => sanitize_text_field($_POST['institution_name']),
                'institution_logo' => esc_url_raw($_POST['institution_logo']),
                'admin_email' => sanitize_email($_POST['admin_email']),
                'naac_grade' => sanitize_text_field($_POST['naac_grade']),
                'nba_accredited_programs' => sanitize_textarea_field($_POST['nba_accredited_programs']),
                'email_notifications' => isset($_POST['email_notifications']) ? 1 : 0,
                'allow_anonymous' => isset($_POST['allow_anonymous']) ? 1 : 0,
                'collect_ip_address' => isset($_POST['collect_ip_address']) ? 1 : 0
            );
            
            foreach ($settings as $key => $value) {
                EFS_Database::update_setting($key, $value);
            }
            
            echo '<div class="notice notice-success"><p>Settings saved successfully!</p></div>';
        }
        
        // Get current settings
        $settings = array();
        $setting_keys = array('institution_name', 'institution_logo', 'admin_email', 'naac_grade', 
                              'nba_accredited_programs', 'email_notifications', 'allow_anonymous', 'collect_ip_address');
        foreach ($setting_keys as $key) {
            $settings[$key] = EFS_Database::get_setting($key);
        }
        
        include EFS_PLUGIN_DIR . 'admin/views/settings.php';
    }

    /**
     * Display Templates Page (Quick Create)
     */
    public static function display_templates() {
        // Handle template creation
        if (isset($_POST['create_from_template'])) {
            check_admin_referer('create_from_template');
            
            $template_type = sanitize_text_field($_POST['template_type']);
            $stakeholder = sanitize_text_field($_POST['stakeholder_type']);
            $academic_year = sanitize_text_field($_POST['academic_year']);
            $semester = sanitize_text_field($_POST['semester']);
            
            $created_forms = array();
            
            if ($template_type === 'naac_all') {
                // Create all 7 NAAC criteria forms
                $created_forms = EFS_NAAC_Templates::create_all_templates($stakeholder, $academic_year, $semester);
            } elseif (strpos($template_type, 'naac_') === 0) {
                // Create specific NAAC criterion
                $criterion = intval(str_replace('naac_', '', $template_type));
                $template = EFS_NAAC_Templates::get_template($criterion, $stakeholder);
                $template['academic_year'] = $academic_year;
                $template['semester'] = $semester;
                
                $form_id = EFS_Database::create_form($template);
                foreach ($template['questions'] as $index => $question) {
                    $question['form_id'] = $form_id;
                    $question['sort_order'] = $index;
                    EFS_Database::create_question($question);
                }
                $created_forms = array($form_id);
            } elseif ($template_type === 'nba_course') {
                $course_name = sanitize_text_field($_POST['course_name']);
                $department = sanitize_text_field($_POST['department']);
                $template = EFS_NBA_Templates::get_course_feedback_template($course_name, $department);
                $created_forms = array(EFS_NBA_Templates::create_form_from_template($template, $academic_year, $semester));
            }
            
            if (count($created_forms) > 0) {
                echo '<div class="notice notice-success"><p>' . count($created_forms) . ' form(s) created successfully!</p></div>';
            }
        }
        
        include EFS_PLUGIN_DIR . 'admin/views/templates.php';
    }
}
