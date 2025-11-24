<?php
/**
 * REST API endpoints for Elite Feedback System.
 *
 * @package    Elite_Feedback_System
 * @subpackage Elite_Feedback_System/includes/api
 */

class EFS_API {

    /**
     * Register REST API routes
     */
    public function register_routes() {
        $namespace = 'efs/v1';
        
        // Forms endpoints
        register_rest_route($namespace, '/forms', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_forms'),
                'permission_callback' => array($this, 'admin_permissions_check')
            ),
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_form'),
                'permission_callback' => array($this, 'admin_permissions_check')
            )
        ));
        
        register_rest_route($namespace, '/forms/(?P<id>\d+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_form'),
                'permission_callback' => '__return_true'
            ),
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'update_form'),
                'permission_callback' => array($this, 'admin_permissions_check')
            ),
            array(
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => array($this, 'delete_form'),
                'permission_callback' => array($this, 'admin_permissions_check')
            )
        ));
        
        // Active forms (public endpoint)
        register_rest_route($namespace, '/forms/active', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_active_forms'),
            'permission_callback' => '__return_true'
        ));
        
        // Questions endpoints
        register_rest_route($namespace, '/forms/(?P<form_id>\d+)/questions', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_questions'),
                'permission_callback' => '__return_true'
            ),
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_question'),
                'permission_callback' => array($this, 'admin_permissions_check')
            )
        ));
        
        register_rest_route($namespace, '/questions/(?P<id>\d+)', array(
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'update_question'),
                'permission_callback' => array($this, 'admin_permissions_check')
            ),
            array(
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => array($this, 'delete_question'),
                'permission_callback' => array($this, 'admin_permissions_check')
            )
        ));
        
        // Responses endpoints
        register_rest_route($namespace, '/responses', array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => array($this, 'submit_response'),
            'permission_callback' => '__return_true'
        ));
        
        register_rest_route($namespace, '/forms/(?P<form_id>\d+)/responses', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_responses'),
            'permission_callback' => array($this, 'admin_permissions_check')
        ));
        
        // Analytics endpoints
        register_rest_route($namespace, '/analytics/overview', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_analytics_overview'),
            'permission_callback' => array($this, 'admin_permissions_check')
        ));
        
        register_rest_route($namespace, '/analytics/forms/(?P<form_id>\d+)', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_form_analytics'),
            'permission_callback' => array($this, 'admin_permissions_check')
        ));
        
        // Reports endpoints
        register_rest_route($namespace, '/reports/naac', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'generate_naac_report'),
            'permission_callback' => array($this, 'admin_permissions_check')
        ));
        
        register_rest_route($namespace, '/reports/nba', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'generate_nba_report'),
            'permission_callback' => array($this, 'admin_permissions_check')
        ));
        
        // Settings endpoints
        register_rest_route($namespace, '/settings', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_settings'),
                'permission_callback' => array($this, 'admin_permissions_check')
            ),
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'update_settings'),
                'permission_callback' => array($this, 'admin_permissions_check')
            )
        ));
    }
    
    /**
     * Check if user has admin permissions
     */
    public function admin_permissions_check() {
        return current_user_can('manage_options');
    }
    
    /**
     * Get all forms
     */
    public function get_forms($request) {
        $forms = EFS_Database::get_forms();
        
        // Add question count and response count to each form
        foreach ($forms as &$form) {
            $form->question_count = count(EFS_Database::get_questions($form->id));
            $form->response_count = EFS_Database::get_response_count($form->id);
        }
        
        return rest_ensure_response($forms);
    }
    
    /**
     * Get a single form
     */
    public function get_form($request) {
        $form_id = $request['id'];
        $form = EFS_Database::get_form($form_id);
        
        if (!$form) {
            return new WP_Error('not_found', 'Form not found', array('status' => 404));
        }
        
        $form->questions = EFS_Database::get_questions($form_id);
        
        // Parse JSON options for questions
        foreach ($form->questions as &$question) {
            if ($question->options) {
                $question->options = json_decode($question->options);
            }
        }
        
        return rest_ensure_response($form);
    }
    
    /**
     * Create a new form
     */
    public function create_form($request) {
        $params = $request->get_json_params();
        
        $form_data = array(
            'title' => sanitize_text_field($params['title']),
            'description' => sanitize_textarea_field($params['description'] ?? ''),
            'form_type' => sanitize_text_field($params['form_type'] ?? 'general'),
            'stakeholder_type' => sanitize_text_field($params['stakeholder_type'] ?? 'students'),
            'naac_criterion' => isset($params['naac_criterion']) ? intval($params['naac_criterion']) : null,
            'nba_outcome' => sanitize_text_field($params['nba_outcome'] ?? ''),
            'academic_year' => sanitize_text_field($params['academic_year'] ?? ''),
            'semester' => sanitize_text_field($params['semester'] ?? ''),
            'department' => sanitize_text_field($params['department'] ?? ''),
            'is_active' => isset($params['is_active']) ? intval($params['is_active']) : 1,
            'is_anonymous' => isset($params['is_anonymous']) ? intval($params['is_anonymous']) : 0,
            'start_date' => $params['start_date'] ?? null,
            'end_date' => $params['end_date'] ?? null
        );
        
        $form_id = EFS_Database::create_form($form_data);
        
        // Create questions if provided
        if (isset($params['questions']) && is_array($params['questions'])) {
            foreach ($params['questions'] as $index => $question) {
                $question_data = array(
                    'form_id' => $form_id,
                    'question_text' => sanitize_textarea_field($question['question_text']),
                    'question_type' => sanitize_text_field($question['question_type'] ?? 'scale'),
                    'options' => $question['options'] ?? null,
                    'is_required' => isset($question['is_required']) ? intval($question['is_required']) : 1,
                    'weightage' => isset($question['weightage']) ? floatval($question['weightage']) : 1.0,
                    'sort_order' => isset($question['sort_order']) ? intval($question['sort_order']) : $index,
                    'naac_criterion' => isset($question['naac_criterion']) ? intval($question['naac_criterion']) : null,
                    'nba_outcome' => sanitize_text_field($question['nba_outcome'] ?? '')
                );
                
                EFS_Database::create_question($question_data);
            }
        }
        
        return rest_ensure_response(array(
            'success' => true,
            'form_id' => $form_id,
            'message' => 'Form created successfully'
        ));
    }
    
    /**
     * Update a form
     */
    public function update_form($request) {
        $form_id = $request['id'];
        $params = $request->get_json_params();
        
        $form_data = array();
        
        if (isset($params['title'])) {
            $form_data['title'] = sanitize_text_field($params['title']);
        }
        if (isset($params['description'])) {
            $form_data['description'] = sanitize_textarea_field($params['description']);
        }
        if (isset($params['is_active'])) {
            $form_data['is_active'] = intval($params['is_active']);
        }
        if (isset($params['start_date'])) {
            $form_data['start_date'] = $params['start_date'];
        }
        if (isset($params['end_date'])) {
            $form_data['end_date'] = $params['end_date'];
        }
        
        EFS_Database::update_form($form_id, $form_data);
        
        return rest_ensure_response(array(
            'success' => true,
            'message' => 'Form updated successfully'
        ));
    }
    
    /**
     * Delete a form
     */
    public function delete_form($request) {
        $form_id = $request['id'];
        
        EFS_Database::delete_form($form_id);
        
        return rest_ensure_response(array(
            'success' => true,
            'message' => 'Form deleted successfully'
        ));
    }
    
    /**
     * Get active forms
     */
    public function get_active_forms($request) {
        $args = array('is_active' => 1);
        
        $stakeholder_type = $request->get_param('stakeholder_type');
        if ($stakeholder_type) {
            $args['stakeholder_type'] = $stakeholder_type;
        }
        
        $forms = EFS_Database::get_forms($args);
        
        return rest_ensure_response($forms);
    }
    
    /**
     * Get questions for a form
     */
    public function get_questions($request) {
        $form_id = $request['form_id'];
        $questions = EFS_Database::get_questions($form_id);
        
        // Parse JSON options
        foreach ($questions as &$question) {
            if ($question->options) {
                $question->options = json_decode($question->options);
            }
        }
        
        return rest_ensure_response($questions);
    }
    
    /**
     * Create a question
     */
    public function create_question($request) {
        $params = $request->get_json_params();
        $form_id = $request['form_id'];
        
        $question_data = array(
            'form_id' => $form_id,
            'question_text' => sanitize_textarea_field($params['question_text']),
            'question_type' => sanitize_text_field($params['question_type'] ?? 'scale'),
            'options' => $params['options'] ?? null,
            'is_required' => isset($params['is_required']) ? intval($params['is_required']) : 1,
            'weightage' => isset($params['weightage']) ? floatval($params['weightage']) : 1.0,
            'sort_order' => isset($params['sort_order']) ? intval($params['sort_order']) : 0
        );
        
        $question_id = EFS_Database::create_question($question_data);
        
        return rest_ensure_response(array(
            'success' => true,
            'question_id' => $question_id
        ));
    }
    
    /**
     * Update a question
     */
    public function update_question($request) {
        $question_id = $request['id'];
        $params = $request->get_json_params();
        
        $question_data = array();
        
        if (isset($params['question_text'])) {
            $question_data['question_text'] = sanitize_textarea_field($params['question_text']);
        }
        if (isset($params['options'])) {
            $question_data['options'] = $params['options'];
        }
        if (isset($params['sort_order'])) {
            $question_data['sort_order'] = intval($params['sort_order']);
        }
        
        EFS_Database::update_question($question_id, $question_data);
        
        return rest_ensure_response(array('success' => true));
    }
    
    /**
     * Delete a question
     */
    public function delete_question($request) {
        $question_id = $request['id'];
        
        EFS_Database::delete_question($question_id);
        
        return rest_ensure_response(array('success' => true));
    }
    
    /**
     * Submit feedback response
     */
    public function submit_response($request) {
        $params = $request->get_json_params();
        
        if (!isset($params['form_id']) || !isset($params['responses'])) {
            return new WP_Error('missing_data', 'Form ID and responses are required', array('status' => 400));
        }
        
        $form_id = intval($params['form_id']);
        $responses = $params['responses'];
        
        foreach ($responses as $response) {
            $response_data = array(
                'form_id' => $form_id,
                'question_id' => intval($response['question_id']),
                'response_value' => sanitize_text_field($response['value']),
                'response_text' => isset($response['text']) ? sanitize_textarea_field($response['text']) : ''
            );
            
            EFS_Database::save_response($response_data);
        }
        
        return rest_ensure_response(array(
            'success' => true,
            'message' => 'Feedback submitted successfully'
        ));
    }
    
    /**
     * Get responses for a form
     */
    public function get_responses($request) {
        $form_id = $request['form_id'];
        $responses = EFS_Database::get_responses($form_id);
        
        return rest_ensure_response($responses);
    }
    
    /**
     * Get analytics overview
     */
    public function get_analytics_overview($request) {
        require_once EFS_PLUGIN_DIR . 'includes/class-efs-analytics.php';
        
        $analytics = EFS_Analytics::get_overview();
        
        return rest_ensure_response($analytics);
    }
    
    /**
     * Get form-specific analytics
     */
    public function get_form_analytics($request) {
        require_once EFS_PLUGIN_DIR . 'includes/class-efs-analytics.php';
        
        $form_id = $request['form_id'];
        $analytics = EFS_Analytics::get_form_analytics($form_id);
        
        return rest_ensure_response($analytics);
    }
    
    /**
     * Generate NAAC report
     */
    public function generate_naac_report($request) {
        require_once EFS_PLUGIN_DIR . 'includes/class-efs-export.php';
        
        $criterion = $request->get_param('criterion');
        $academic_year = $request->get_param('academic_year');
        $format = $request->get_param('format') ?? 'pdf';
        
        $report = EFS_Export::generate_naac_report($criterion, $academic_year, $format);
        
        return rest_ensure_response($report);
    }
    
    /**
     * Generate NBA report
     */
    public function generate_nba_report($request) {
        require_once EFS_PLUGIN_DIR . 'includes/class-efs-export.php';
        
        $program = $request->get_param('program');
        $academic_year = $request->get_param('academic_year');
        $format = $request->get_param('format') ?? 'pdf';
        
        $report = EFS_Export::generate_nba_report($program, $academic_year, $format);
        
        return rest_ensure_response($report);
    }
    
    /**
     * Get settings
     */
    public function get_settings($request) {
        global $wpdb;
        $table = $wpdb->prefix . 'efs_settings';
        
        $settings = $wpdb->get_results("SELECT * FROM $table", ARRAY_A);
        
        $formatted = array();
        foreach ($settings as $setting) {
            $formatted[$setting['setting_key']] = $setting['setting_value'];
        }
        
        return rest_ensure_response($formatted);
    }
    
    /**
     * Update settings
     */
    public function update_settings($request) {
        $params = $request->get_json_params();
        
        foreach ($params as $key => $value) {
            EFS_Database::update_setting($key, $value);
        }
        
        return rest_ensure_response(array(
            'success' => true,
            'message' => 'Settings updated successfully'
        ));
    }
}
