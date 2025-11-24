<?php
/**
 * Email notification helper class.
 *
 * @package    Elite_Feedback_System
 * @subpackage Elite_Feedback_System/includes
 */

class EFS_Email {

    /**
     * Send feedback request email
     */
    public static function send_feedback_request($to_email, $form_id, $stakeholder_name = '') {
        $form = EFS_Database::get_form($form_id);
        
        if (!$form) {
            return false;
        }
        
        $subject = sprintf(
            '[%s] Feedback Request: %s',
            EFS_Database::get_setting('institution_name', get_bloginfo('name')),
            $form->title
        );
        
        $form_url = add_query_arg(
            array('form_id' => $form_id),
            home_url('/feedback/')
        );
        
        $message = self::get_email_template('feedback-request', array(
            'stakeholder_name' => $stakeholder_name,
            'form_title' => $form->title,
            'form_description' => $form->description,
            'form_url' => $form_url,
            'end_date' => $form->end_date ? date('F j, Y', strtotime($form->end_date)) : 'Soon',
            'institution_name' => EFS_Database::get_setting('institution_name', get_bloginfo('name'))
        ));
        
        return self::send_email($to_email, $subject, $message);
    }
    
    /**
     * Send feedback confirmation email
     */
    public static function send_feedback_confirmation($to_email, $form_id, $stakeholder_name = '') {
        $form = EFS_Database::get_form($form_id);
        
        if (!$form) {
            return false;
        }
        
        $subject = sprintf(
            '[%s] Feedback Received - Thank You!',
            EFS_Database::get_setting('institution_name', get_bloginfo('name'))
        );
        
        $message = self::get_email_template('feedback-confirmation', array(
            'stakeholder_name' => $stakeholder_name,
            'form_title' => $form->title,
            'institution_name' => EFS_Database::get_setting('institution_name', get_bloginfo('name'))
        ));
        
        return self::send_email($to_email, $subject, $message);
    }
    
    /**
     * Send admin notification for new response
     */
    public static function send_admin_notification($form_id, $response_count) {
        $form = EFS_Database::get_form($form_id);
        
        if (!$form) {
            return false;
        }
        
        $admin_email = EFS_Database::get_setting('admin_email', get_option('admin_email'));
        
        $subject = sprintf(
            '[%s] New Feedback Response: %s',
            EFS_Database::get_setting('institution_name', get_bloginfo('name')),
            $form->title
        );
        
        $admin_url = admin_url('admin.php?page=efs-forms&form_id=' . $form_id);
        
        $message = self::get_email_template('admin-notification', array(
            'form_title' => $form->title,
            'response_count' => $response_count,
            'admin_url' => $admin_url,
            'institution_name' => EFS_Database::get_setting('institution_name', get_bloginfo('name'))
        ));
        
        return self::send_email($admin_email, $subject, $message);
    }
    
    /**
     * Get email template
     */
    private static function get_email_template($template_name, $data = array()) {
        extract($data);
        
        ob_start();
        
        switch ($template_name) {
            case 'feedback-request':
                ?>
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f5f5f5;">
                    <div style="background-color: #ffffff; padding: 30px; border-radius: 8px;">
                        <h2 style="color: #2c3e50; margin-top: 0;">Feedback Request</h2>
                        
                        <?php if ($stakeholder_name): ?>
                            <p>Dear <?php echo esc_html($stakeholder_name); ?>,</p>
                        <?php else: ?>
                            <p>Dear Stakeholder,</p>
                        <?php endif; ?>
                        
                        <p>We value your opinion and would appreciate your feedback on the following:</p>
                        
                        <div style="background-color: #e8f4f8; padding: 20px; border-left: 4px solid #3498db; margin: 20px 0;">
                            <h3 style="margin-top: 0; color: #2980b9;"><?php echo esc_html($form_title); ?></h3>
                            <p><?php echo esc_html($form_description); ?></p>
                        </div>
                        
                        <p>Your feedback is important for our continuous improvement and accreditation processes (NAAC/NBA).</p>
                        
                        <p style="text-align: center; margin: 30px 0;">
                            <a href="<?php echo esc_url($form_url); ?>" style="background-color: #3498db; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">
                                Provide Feedback
                            </a>
                        </p>
                        
                        <p style="color: #7f8c8d; font-size: 14px;">
                            Please complete this feedback by: <strong><?php echo esc_html($end_date); ?></strong>
                        </p>
                        
                        <p>Thank you for your time and valuable input!</p>
                        
                        <p>Best regards,<br>
                        <strong><?php echo esc_html($institution_name); ?></strong></p>
                    </div>
                    
                    <p style="text-align: center; color: #95a5a6; font-size: 12px; margin-top: 20px;">
                        This is an automated message. Please do not reply to this email.
                    </p>
                </div>
                <?php
                break;
                
            case 'feedback-confirmation':
                ?>
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f5f5f5;">
                    <div style="background-color: #ffffff; padding: 30px; border-radius: 8px;">
                        <h2 style="color: #27ae60; margin-top: 0;">✓ Feedback Received</h2>
                        
                        <?php if ($stakeholder_name): ?>
                            <p>Dear <?php echo esc_html($stakeholder_name); ?>,</p>
                        <?php else: ?>
                            <p>Dear Stakeholder,</p>
                        <?php endif; ?>
                        
                        <p>Thank you for submitting your feedback for:</p>
                        
                        <div style="background-color: #e8f8f5; padding: 20px; border-left: 4px solid #27ae60; margin: 20px 0;">
                            <h3 style="margin-top: 0; color: #27ae60;"><?php echo esc_html($form_title); ?></h3>
                        </div>
                        
                        <p>Your response has been recorded successfully. Your feedback is invaluable to us and will help improve our institution's quality and accreditation processes.</p>
                        
                        <p>We appreciate the time you took to share your thoughts with us.</p>
                        
                        <p>Best regards,<br>
                        <strong><?php echo esc_html($institution_name); ?></strong></p>
                    </div>
                </div>
                <?php
                break;
                
            case 'admin-notification':
                ?>
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
                    <h2 style="color: #2c3e50;">New Feedback Response</h2>
                    
                    <p>A new feedback response has been submitted:</p>
                    
                    <ul>
                        <li><strong>Form:</strong> <?php echo esc_html($form_title); ?></li>
                        <li><strong>Total Responses:</strong> <?php echo intval($response_count); ?></li>
                    </ul>
                    
                    <p>
                        <a href="<?php echo esc_url($admin_url); ?>" style="background-color: #3498db; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
                            View Responses
                        </a>
                    </p>
                    
                    <p style="color: #7f8c8d; font-size: 12px;">
                        From <?php echo esc_html($institution_name); ?> Feedback System
                    </p>
                </div>
                <?php
                break;
        }
        
        return ob_get_clean();
    }
    
    /**
     * Send email using WordPress mail function
     */
    private static function send_email($to, $subject, $message) {
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . EFS_Database::get_setting('institution_name', get_bloginfo('name')) . ' <' . get_option('admin_email') . '>'
        );
        
        return wp_mail($to, $subject, $message, $headers);
    }
    
    /**
     * Send bulk feedback requests
     */
    public static function send_bulk_requests($emails, $form_id) {
        $sent_count = 0;
        
        foreach ($emails as $email_data) {
            $email = is_array($email_data) ? $email_data['email'] : $email_data;
            $name = is_array($email_data) ? ($email_data['name'] ?? '') : '';
            
            if (self::send_feedback_request($email, $form_id, $name)) {
                $sent_count++;
            }
            
            // Small delay to prevent email throttling
            usleep(100000); // 0.1 second
        }
        
        return $sent_count;
    }
}
