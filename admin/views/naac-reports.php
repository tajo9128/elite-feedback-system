<?php
/**
 * NAAC Reports View
 *
 * @package Elite_Feedback_System
 */

if (!defined('ABSPATH')) exit;
?>

<div class="wrap efs-admin-wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="efs-report-form">
        <h2>Generate NAAC Report</h2>
        <p>Generate comprehensive feedback reports for NAAC accreditation.</p>
        
        <form method="post" action="">
            <?php wp_nonce_field('generate_naac_report'); ?>
            
            <table class="form-table">
                <tr>
                    <th><label for="criterion">NAAC Criterion *</label></th>
                    <td>
                        <select name="criterion" id="criterion" required>
                            <option value="">All Criteria</option>
                            <?php foreach ($criteria as $num => $title): ?>
                                <option value="<?php echo esc_attr($num); ?>">
                                    Criterion <?php echo $num; ?>: <?php echo esc_html($title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description">Select a specific criterion or generate report for all criteria</p>
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
                <button type="submit" name="generate_naac_report" class="button button-primary button-large">
                    <span class="dashicons dashicons-media-document"></span>
                    Generate Report
                </button>
            </p>
        </form>
    </div>
    
    <div class="efs-dashboard-section" style="margin-top: 30px;">
        <h2>Available NAAC Criteria</h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Criterion</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($criteria as $num => $title): ?>
                <tr>
                    <td><strong>Criterion <?php echo $num; ?></strong></td>
                    <td><?php echo esc_html($title); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
