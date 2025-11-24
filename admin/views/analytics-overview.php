<?php
/**
 * Analytics Overview View
 *
 * @package Elite_Feedback_System
 */

if (!defined('ABSPATH')) exit;
?>

<div class="wrap efs-admin-wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <!-- Overview Stats -->
    <div class="efs-analytics-grid">
        <div class="efs-analytics-card">
            <h3>Total Forms</h3>
            <div class="efs-metric"><?php echo intval($overview['total_forms'] ?? 0); ?></div>
            <p><?php echo intval($overview['active_forms'] ?? 0); ?> active</p>
        </div>
        
        <div class="efs-analytics-card">
            <h3>Total Responses</h3>
            <div class="efs-metric"><?php echo intval($overview['total_responses'] ?? 0); ?></div>
            <p><?php echo intval($overview['recent_responses'] ?? 0); ?> in last 7 days</p>
        </div>
        
        <div class="efs-analytics-card">
            <h3>Average Response Rate</h3>
            <div class="efs-metric"><?php echo number_format($overview['avg_response_rate'] ?? 0, 1); ?>%</div>
            <p>Across all forms</p>
        </div>
        
        <div class="efs-analytics-card">
            <h3>Completion Rate</h3>
            <div class="efs-metric"><?php echo number_format($overview['completion_rate'] ?? 0, 1); ?>%</div>
            <p>Forms fully completed</p>
        </div>
    </div>
    
    <!-- Forms List with Analytics -->
    <div class="efs-dashboard-section">
        <h2>Form-wise Analytics</h2>
        <?php if (empty($forms)): ?>
            <p>No forms available. <a href="<?php echo admin_url('admin.php?page=efs-form-editor'); ?>">Create your first form</a>.</p>
        <?php else: ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Form Title</th>
                        <th>Type</th>
                        <th>Questions</th>
                        <th>Responses</th>
                        <th>Avg. Rating</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($forms as $form): 
                        $form_analytics = EFS_Analytics::get_form_analytics($form->id);
                        $responses = EFS_Database::get_response_count($form->id);
                        $questions = count(EFS_Database::get_questions($form->id));
                    ?>
                    <tr>
                        <td>
                            <strong><?php echo esc_html($form->title); ?></strong><br>
                            <small><?php echo esc_html($form->academic_year); ?> - <?php echo esc_html($form->semester); ?></small>
                        </td>
                        <td>
                            <?php if ($form->naac_criterion): ?>
                                <span class="efs-badge efs-badge-naac">NAAC <?php echo $form->naac_criterion; ?></span>
                            <?php elseif ($form->nba_outcome): ?>
                                <span class="efs-badge efs-badge-nba"><?php echo esc_html($form->nba_outcome); ?></span>
                            <?php else: ?>
                                <?php echo esc_html(ucfirst($form->form_type)); ?>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $questions; ?></td>
                        <td><strong><?php echo $responses; ?></strong></td>
                        <td><?php echo isset($form_analytics['average_rating']) ? number_format($form_analytics['average_rating'], 2) : 'N/A'; ?></td>
                        <td>
                            <a href="<?php echo admin_url('admin.php?page=efs-analytics&form_id=' . $form->id); ?>" class="button button-small">View Details</a>
                            <a href="<?php echo admin_url('admin.php?page=efs-responses&form_id=' . $form->id); ?>" class="button button-small">Responses</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
