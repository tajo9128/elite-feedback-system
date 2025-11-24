<?php
/**
 * Dashboard View
 *
 * @package Elite_Feedback_System
 */

if (!defined('ABSPATH')) exit;
?>

<div class="wrap efs-admin-wrap">
    <h1 class="wp-heading-inline">
        <?php echo esc_html(get_admin_page_title()); ?>
    </h1>
    
    <!-- Stats Cards -->
    <div class="efs-dashboard-stats">
        <div class="efs-stat-card">
            <div class="efs-stat-icon" style="background: #3498db;">
                <span class="dashicons dashicons-feedback"></span>
            </div>
            <div class="efs-stat-content">
                <h3><?php echo intval($stats['total_forms'] ?? 0); ?></h3>
                <p>Total Forms</p>
            </div>
        </div>
        
        <div class="efs-stat-card">
            <div class="efs-stat-icon" style="background: #27ae60;">
                <span class="dashicons dashicons-yes-alt"></span>
            </div>
            <div class="efs-stat-content">
                <h3><?php echo intval($stats['total_responses'] ?? 0); ?></h3>
                <p>Total Responses</p>
            </div>
        </div>
        
        <div class="efs-stat-card">
            <div class="efs-stat-icon" style="background: #f39c12;">
                <span class="dashicons dashicons-businessperson"></span>
            </div>
            <div class="efs-stat-content">
                <h3><?php echo intval($stats['active_forms'] ?? 0); ?></h3>
                <p>Active Forms</p>
            </div>
        </div>
        
        <div class="efs-stat-card">
            <div class="efs-stat-icon" style="background: #e74c3c;">
                <span class="dashicons dashicons-clock"></span>
            </div>
            <div class="efs-stat-content">
                <h3><?php echo intval($stats['recent_responses'] ?? 0); ?></h3>
                <p>Last 7 Days</p>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="efs-dashboard-section">
        <h2>Quick Actions</h2>
        <div class="efs-quick-actions">
            <a href="<?php echo admin_url('admin.php?page=efs-form-editor'); ?>" class="efs-action-btn primary">
                <span class="dashicons dashicons-plus"></span>
                Create New Form
            </a>
            <a href="<?php echo admin_url('admin.php?page=efs-templates'); ?>" class="efs-action-btn">
                <span class="dashicons dashicons-download"></span>
                Use NAAC/NBA Template
            </a>
            <a href="<?php echo admin_url('admin.php?page=efs-analytics'); ?>" class="efs-action-btn">
                <span class="dashicons dashicons-chart-bar"></span>
                View Analytics
            </a>
            <a href="<?php echo admin_url('admin.php?page=efs-naac-reports'); ?>" class="efs-action-btn">
                <span class="dashicons dashicons-media-document"></span>
                Generate Report
            </a>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="efs-dashboard-section">
        <h2>Getting Started</h2>
        <div class="efs-getting-started">
            <div class="efs-step">
                <div class="efs-step-number">1</div>
                <div class="efs-step-content">
                    <h3>Configure Settings</h3>
                    <p>Set up your institution name, logo, and accreditation details in <a href="<?php echo admin_url('admin.php?page=efs-settings'); ?>">Settings</a>.</p>
                </div>
            </div>
            
            <div class="efs-step">
                <div class="efs-step-number">2</div>
                <div class="efs-step-content">
                    <h3>Create Feedback Forms</h3>
                    <p>Use our pre-built <a href="<?php echo admin_url('admin.php?page=efs-templates'); ?>">NAAC/NBA templates</a> or <a href="<?php echo admin_url('admin.php?page=efs-form-editor'); ?>">create custom forms</a>.</p>
                </div>
            </div>
            
            <div class="efs-step">
                <div class="efs-step-number">3</div>
                <div class="efs-step-content">
                    <h3>Display on Your Website</h3>
                    <p>Add shortcode <code>[efs_feedback]</code> to any page to display feedback forms to your stakeholders.</p>
                </div>
            </div>
            
            <div class="efs-step">
                <div class="efs-step-number">4</div>
                <div class="efs-step-content">
                    <h3>Collect & Analyze</h3>
                    <p>Monitor responses, view <a href="<?php echo admin_url('admin.php?page=efs-analytics'); ?>">analytics</a>, and <a href="<?php echo admin_url('admin.php?page=efs-naac-reports'); ?>">generate reports</a> for accreditation.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Responses by Stakeholder (if available) -->
    <?php if (!empty($stats['responses_by_stakeholder'])): ?>
    <div class="efs-dashboard-section">
        <h2>Responses by Stakeholder Type</h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Stakeholder Type</th>
                    <th>Response Count</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stats['responses_by_stakeholder'] as $row): ?>
                <tr>
                    <td><?php echo esc_html(ucfirst($row->stakeholder_type)); ?></td>
                    <td><strong><?php echo intval($row->count); ?></strong></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
