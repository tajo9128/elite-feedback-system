<?php
/**
 * Settings View
 *
 * @package Elite_Feedback_System
 */

if (!defined('ABSPATH')) exit;
?>

<div class="wrap efs-admin-wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <form method="post" action="">
        <?php wp_nonce_field('efs_settings'); ?>
        <input type="hidden" name="efs_save_settings" value="1">
        
        <div class="efs-panel">
            <h2>Institution Information</h2>
            <table class="form-table">
                <tr>
                    <th><label for="institution_name">Institution Name *</label></th>
                    <td>
                        <input type="text" 
                               name="institution_name" 
                               id="institution_name" 
                               class="regular-text" 
                               value="<?php echo esc_attr($settings['institution_name'] ?? get_bloginfo('name')); ?>" 
                               required>
                    </td>
                </tr>
                <tr>
                    <th><label for="institution_logo">Institution Logo URL</label></th>
                    <td>
                        <input type="url" 
                               name="institution_logo" 
                               id="institution_logo" 
                               class="regular-text" 
                               value="<?php echo esc_url($settings['institution_logo'] ?? ''); ?>" 
                               placeholder="https://example.com/logo.png">
                        <p class="description">Full URL to your institution's logo (used in reports)</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="admin_email">Admin Email *</label></th>
                    <td>
                        <input type="email" 
                               name="admin_email" 
                               id="admin_email" 
                               class="regular-text" 
                               value="<?php echo esc_attr($settings['admin_email'] ?? get_option('admin_email')); ?>" 
                               required>
                        <p class="description">Email address for feedback notifications</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="efs-panel">
            <h2>Accreditation Information</h2>
            <table class="form-table">
                <tr>
                    <th><label for="naac_grade">NAAC Grade</label></th>
                    <td>
                        <select name="naac_grade" id="naac_grade">
                            <option value="">Not Accredited</option>
                            <?php 
                            $grades = array('A++', 'A+', 'A', 'B++', 'B+', 'B', 'C');
                            foreach ($grades as $grade): 
                                $selected = ($settings['naac_grade'] ?? '') === $grade ? 'selected' : '';
                            ?>
                                <option value="<?php echo esc_attr($grade); ?>" <?php echo $selected; ?>><?php echo $grade; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="nba_accredited_programs">NBA Accredited Programs</label></th>
                    <td>
                        <textarea name="nba_accredited_programs" 
                                  id="nba_accredited_programs" 
                                  rows="3" 
                                  class="large-text"><?php echo esc_textarea($settings['nba_accredited_programs'] ?? ''); ?></textarea>
                        <p class="description">Enter comma-separated list of NBA accredited programs (e.g., CSE, ECE, ME)</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="efs-panel">
            <h2>Feedback Options</h2>
            <table class="form-table">
                <tr>
                    <th>Email Notifications</th>
                    <td>
                        <label>
                            <input type="checkbox" 
                                   name="email_notifications" 
                                   value="1" 
                                   <?php checked($settings['email_notifications'] ?? 1, 1); ?>>
                            Send email notifications for new responses
                        </label>
                    </td>
                </tr>
                <tr>
                    <th>Anonymous Feedback</th>
                    <td>
                        <label>
                            <input type="checkbox" 
                                   name="allow_anonymous" 
                                   value="1" 
                                   <?php checked($settings['allow_anonymous'] ?? 1, 1); ?>>
                            Allow anonymous feedback responses (default)
                        </label>
                    </td>
                </tr>
                <tr>
                    <th>IP Address Collection</th>
                    <td>
                        <label>
                            <input type="checkbox" 
                                   name="collect_ip_address" 
                                   value="1" 
                                   <?php checked($settings['collect_ip_address'] ?? 0, 1); ?>>
                            Collect IP addresses (for duplicate prevention)
                        </label>
                        <p class="description">Enable this to prevent multiple submissions from the same IP</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <p class="submit">
            <button type="submit" class="button button-primary button-large">
                Save Settings
            </button>
        </p>
    </form>
</div>
