<?php
/**
 * NBA Reports View
 *
 * @package Elite_Feedback_System
 */

if (!defined('ABSPATH')) exit;
?>

<div class="wrap efs-admin-wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="efs-report-form">
        <h2>Generate NBA Report</h2>
        <p>Generate comprehensive feedback reports for NBA accreditation and program outcome assessment.</p>
        
        <form method="post" action="">
            <?php wp_nonce_field('generate_nba_report'); ?>
            
            <table class="form-table">
                <tr>
                    <th><label for="program">Program/Department *</label></th>
                    <td>
                        <input type="text" 
                               name="program" 
                               id="program" 
                               class="regular-text" 
                               placeholder="e.g., Computer Science Engineering" 
                               required>
                    </td>
                </tr>
                <tr>
                    <th><label for="academic_year">Academic Year *</label></th>
                    <td>
                        <input type="text" 
                               name="academic_year" 
                               id="academic_year" 
                               class="regular-text" 
                               placeholder="2024-2025" 
                               required>
                    </td>
                </tr>
                <tr>
                    <th><label for="format">Format *</label></th>
                    <td>
                        <select name="format" id="format" required>
                            <option value="pdf">PDF Report</option>
                            <option value="excel">Excel (XLSX)</option>
                            <option value="csv">CSV</option>
                        </select>
                    </td>
                </tr>
            </table>
            
            <p class="submit">
                <button type="submit" name="generate_nba_report" class="button button-primary button-large">
                    <span class="dashicons dashicons-media-document"></span>
                    Generate Report
                </button>
            </p>
        </form>
    </div>
    
    <div class="efs-dashboard-section" style="margin-top: 30px;">
        <h2>NBA Program Outcomes (PO1-PO12)</h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th width="10%">Code</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($outcomes as $code => $description): ?>
                <tr>
                    <td><strong><?php echo esc_html($code); ?></strong></td>
                    <td><?php echo esc_html($description); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
