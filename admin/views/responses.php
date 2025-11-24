<?php
/**
 * Responses View - View all responses for a specific form
 *
 * @package Elite_Feedback_System
 */

if (!defined('ABSPATH')) exit;
?>

<div class="wrap efs-admin-wrap">
    <h1>Responses: <?php echo esc_html($form->title); ?></h1>
    <a href="<?php echo admin_url('admin.php?page=efs-forms'); ?>" class="page-title-action">← Back to Forms</a>
    <a href="<?php echo admin_url('admin.php?page=efs-analytics&form_id=' . $form->id); ?>" class="page-title-action">View Analytics</a>
    
    <hr class="wp-header-end">
    
    <div class="efs-panel">
        <div class="efs-panel-header">
            <h2>Responses (<?php echo count($sessions); ?>)</h2>
            <div>
                <a href="<?php echo rest_url('efs/v1/forms/' . $form->id . '/export?format=csv'); ?>" class="button">
                    <span class="dashicons dashicons-download"></span> Export CSV
                </a>
                <a href="<?php echo rest_url('efs/v1/forms/' . $form->id . '/export?format=excel'); ?>" class="button">
                    <span class="dashicons dashicons-download"></span> Export Excel
                </a>
            </div>
        </div>
        
        <?php if (empty($sessions)): ?>
            <div class="efs-empty-state">
                <span class="dashicons dashicons-editor-help"></span>
                <h2>No responses yet</h2>
                <p>Responses will appear here once users start submitting feedback.</p>
                <p><strong>Shortcode:</strong> <code>[efs_feedback_form id="<?php echo $form->id; ?>"]</code></p>
            </div>
        <?php else: ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="15%">Submitted At</th>
                        <?php foreach ($questions as $question): ?>
                            <th><?php echo esc_html(mb_substr($question->question_text, 0, 50) . (mb_strlen($question->question_text) > 50 ? '...' : '')); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $index = 1;
                    foreach ($sessions as $session): 
                    ?>
                    <tr>
                        <td><?php echo $index++; ?></td>
                        <td><?php echo date('M j, Y g:i A', strtotime($session['submitted_at'])); ?></td>
                        <?php foreach ($questions as $question): ?>
                            <td>
                                <?php 
                                $value = $session['responses'][$question->id] ?? '-';
                                echo esc_html($value);
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
