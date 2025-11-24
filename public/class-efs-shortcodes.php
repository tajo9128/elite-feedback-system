<?php
/**
 * Shortcodes for Elite Feedback System.
 *
 * @package    Elite_Feedback_System
 * @subpackage Elite_Feedback_System/public
 */

class EFS_Shortcodes {

    /**
     * Register all shortcodes
     */
    public function register_shortcodes() {
        add_shortcode('efs_feedback', array($this, 'display_feedback_list'));
        add_shortcode('efs_feedback_form', array($this, 'display_feedback_form'));
        add_shortcode('efs_my_responses', array($this, 'display_my_responses'));
    }

    /**
     * Display list of active feedback forms
     * Usage: [efs_feedback] or [efs_feedback stakeholder="students"]
     */
    public function display_feedback_list($atts) {
        $atts = shortcode_atts(array(
            'stakeholder' => '',
            'limit' => 10
        ), $atts);
        
        $args = array(
            'is_active' => 1,
            'limit' => intval($atts['limit'])
        );
        
        if (!empty($atts['stakeholder'])) {
            $args['stakeholder_type'] = $atts['stakeholder'];
        }
        
        $forms = EFS_Database::get_forms($args);
        
        ob_start();
        ?>
        <div class="efs-feedback-list">
            <h2 class="efs-title">Available Feedback Forms</h2>
            
            <?php if (empty($forms)): ?>
                <p class="efs-no-forms">No active feedback forms available at this time.</p>
            <?php else: ?>
                <div class="efs-forms-grid">
                    <?php foreach ($forms as $form): ?>
                        <div class="efs-form-card">
                            <h3><?php echo esc_html($form->title); ?></h3>
                            <p class="efs-description"><?php echo esc_html($form->description); ?></p>
                            
                            <div class="efs-meta">
                                <span class="efs-tag"><?php echo esc_html(ucfirst($form->stakeholder_type)); ?></span>
                                <?php if ($form->naac_criterion): ?>
                                    <span class="efs-tag efs-naac">NAAC Criterion <?php echo intval($form->naac_criterion); ?></span>
                                <?php endif; ?>
                                <?php if ($form->nba_outcome): ?>
                                    <span class="efs-tag efs-nba"><?php echo esc_html($form->nba_outcome); ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($form->end_date && strtotime($form->end_date) > time()): ?>
                                <p class="efs-deadline">Deadline: <?php echo date('F j, Y', strtotime($form->end_date)); ?></p>
                            <?php endif; ?>
                            
                            <a href="<?php echo esc_url(add_query_arg('form_id', $form->id, get_permalink())); ?>" class="efs-button">
                                Provide Feedback
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Display a specific feedback form
     * Usage: [efs_feedback_form id="123"]
     */
    public function display_feedback_form($atts) {
        $atts = shortcode_atts(array(
            'id' => isset($_GET['form_id']) ? intval($_GET['form_id']) : 0
        ), $atts);
        
        $form_id = intval($atts['id']);
        
        if (!$form_id) {
            return '<p class="efs-error">Form ID is required.</p>';
        }
        
        $form = EFS_Database::get_form($form_id);
        
        if (!$form || !$form->is_active) {
            return '<p class="efs-error">This feedback form is not available.</p>';
        }
        
        $questions = EFS_Database::get_questions($form_id);
        
        if (empty($questions)) {
            return '<p class="efs-error">This form has no questions.</p>';
        }
        
        ob_start();
        ?>
        <div class="efs-feedback-form" data-form-id="<?php echo esc_attr($form_id); ?>">
            <div class="efs-form-header">
                <h2><?php echo esc_html($form->title); ?></h2>
                <?php if ($form->description): ?>
                    <p class="efs-form-description"><?php echo esc_html($form->description); ?></p>
                <?php endif; ?>
                
                <?php if ($form->is_anonymous): ?>
                    <p class="efs-notice efs-anonymous">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                        </svg>
                        This feedback is anonymous. Your identity will not be recorded.
                    </p>
                <?php endif; ?>
            </div>
            
            <form id="efs-form-<?php echo esc_attr($form_id); ?>" class="efs-questions">
                <?php foreach ($questions as $index => $question): ?>
                    <div class="efs-question" data-question-id="<?php echo esc_attr($question->id); ?>">
                        <label class="efs-question-text">
                            <?php echo esc_html($question->question_text); ?>
                            <?php if ($question->is_required): ?>
                                <span class="efs-required">*</span>
                            <?php endif; ?>
                        </label>
                        
                        <?php echo self::render_question_input($question); ?>
                    </div>
                <?php endforeach; ?>
                
                <div class="efs-form-footer">
                    <button type="submit" class="efs-button efs-submit">
                        Submit Feedback
                    </button>
                    <p class="efs-required-note">* Required fields</p>
                </div>
            </form>
            
            <div class="efs-message" style="display: none;"></div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render question input based on type
     */
    private static function render_question_input($question) {
        $options = $question->options ? json_decode($question->options, true) : array();
        $required = $question->is_required ? 'required' : '';
        
        ob_start();
        
        switch ($question->question_type) {
            case 'scale':
            case 'rating':
                $max = isset($options['max']) ? intval($options['max']) : 5;
                ?>
                <div class="efs-scale-input">
                    <?php for ($i = 1; $i <= $max; $i++): ?>
                        <label class="efs-scale-option">
                            <input type="radio" name="question_<?php echo esc_attr($question->id); ?>" 
                                   value="<?php echo esc_attr($i); ?>" <?php echo $required; ?>>
                            <span class="efs-scale-label"><?php echo $i; ?></span>
                        </label>
                    <?php endfor; ?>
                    <div class="efs-scale-labels">
                        <span><?php echo esc_html($options['min_label'] ?? 'Poor'); ?></span>
                        <span><?php echo esc_html($options['max_label'] ?? 'Excellent'); ?></span>
                    </div>
                </div>
                <?php
                break;
                
            case 'mcq':
                if (!empty($options['choices'])):
                    ?>
                    <div class="efs-mcq-input">
                        <?php foreach ($options['choices'] as $choice): ?>
                            <label class="efs-radio-option">
                                <input type="radio" name="question_<?php echo esc_attr($question->id); ?>" 
                                       value="<?php echo esc_attr($choice); ?>" <?php echo $required; ?>>
                                <span><?php echo esc_html($choice); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <?php
                endif;
                break;
                
            case 'checkbox':
                if (!empty($options['choices'])):
                    ?>
                    <div class="efs-checkbox-input">
                        <?php foreach ($options['choices'] as $choice): ?>
                            <label class="efs-checkbox-option">
                                <input type="checkbox" name="question_<?php echo esc_attr($question->id); ?>[]" 
                                       value="<?php echo esc_attr($choice); ?>">
                                <span><?php echo esc_html($choice); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <?php
                endif;
                break;
                
            case 'yes_no':
                ?>
                <div class="efs-yesno-input">
                    <label class="efs-radio-option">
                        <input type="radio" name="question_<?php echo esc_attr($question->id); ?>" 
                               value="Yes" <?php echo $required; ?>>
                        <span>Yes</span>
                    </label>
                    <label class="efs-radio-option">
                        <input type="radio" name="question_<?php echo esc_attr($question->id); ?>" 
                               value="No" <?php echo $required; ?>>
                        <span>No</span>
                    </label>
                </div>
                <?php
                break;
                
            case 'text':
                ?>
                <input type="text" class="efs-text-input" 
                       name="question_<?php echo esc_attr($question->id); ?>" 
                       placeholder="Your answer" <?php echo $required; ?>>
                <?php
                break;
                
            case 'textarea':
                ?>
                <textarea class="efs-textarea-input" 
                          name="question_<?php echo esc_attr($question->id); ?>" 
                          rows="4" placeholder="Your detailed response" <?php echo $required; ?>></textarea>
                <?php
                break;
                
            default:
                echo '<p class="efs-error">Unsupported question type</p>';
        }
        
        return ob_get_clean();
    }

    /**
     * Display user's submitted responses
     * Usage: [efs_my_responses]
     */
    public function display_my_responses($atts) {
        if (!is_user_logged_in()) {
            return '<p class="efs-notice">Please log in to view your feedback history.</p>';
        }
        
        $user_id = get_current_user_id();
        
        // Get all forms the user has responded to
        global $wpdb;
        $responses_table = $wpdb->prefix . 'efs_responses';
        $forms_table = $wpdb->prefix . 'efs_forms';
        
        $query = "
            SELECT DISTINCT f.*, r.submitted_at
            FROM $forms_table f
            INNER JOIN $responses_table r ON f.id = r.form_id
            WHERE r.user_id = %d
            ORDER BY r.submitted_at DESC
        ";
        
        $forms = $wpdb->get_results($wpdb->prepare($query, $user_id));
        
        ob_start();
        ?>
        <div class="efs-my-responses">
            <h2>My Feedback History</h2>
            
            <?php if (empty($forms)): ?>
                <p class="efs-no-data">You haven't submitted any feedback yet.</p>
            <?php else: ?>
                <div class="efs-response-list">
                    <?php foreach ($forms as $form): ?>
                        <div class="efs-response-item">
                            <h3><?php echo esc_html($form->title); ?></h3>
                            <p class="efs-submitted-date">
                                Submitted on: <?php echo date('F j, Y, g:i a', strtotime($form->submitted_at)); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}
