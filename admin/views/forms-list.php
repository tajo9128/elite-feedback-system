<?php
/**
 * Forms List View
 *
 * @package Elite_Feedback_System
 */

if (!defined('ABSPATH')) exit;
?>

<div class="wrap efs-admin-wrap">
    <h1 class="wp-heading-inline">
        <?php echo esc_html(get_admin_page_title()); ?>
    </h1>
    <a href="<?php echo admin_url('admin.php?page=efs-form-editor'); ?>" class="page-title-action">Add New Form</a>
    <a href="<?php echo admin_url('admin.php?page=efs-templates'); ?>" class="page-title-action">Create from Template</a>
    
    <hr class="wp-header-end">
    
    <?php if (isset($_GET['message']) && $_GET['message'] === 'saved'): ?>
        <div class="notice notice-success is-dismissible">
            <p>Form saved successfully!</p>
        </div>
    <?php endif; ?>
    
    <?php if (empty($forms)): ?>
        <div class="efs-empty-state">
            <span class="dashicons dashicons-feedback"></span>
            <h2>No feedback forms yet</h2>
            <p>Create your first feedback form to start collecting responses.</p>
            <a href="<?php echo admin_url('admin.php?page=efs-form-editor'); ?>" class="button button-primary button-hero">Create New Form</a>
            <a href="<?php echo admin_url('admin.php?page=efs-templates'); ?>" class="button button-hero">Use NAAC/NBA Template</a>
        </div>
    <?php else: ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th class="manage-column column-cb check-column" width="50">
                        <input type="checkbox">
                    </th>
                    <th class="manage-column" width="35%">Title</th>
                    <th class="manage-column">Type</th>
                    <th class="manage-column">Stakeholder</th>
                    <th class="manage-column">Responses</th>
                    <th class="manage-column">Status</th>
                    <th class="manage-column">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($forms as $form): 
                    $response_count = EFS_Database::get_response_count($form->id);
                    $question_count = count(EFS_Database::get_questions($form->id));
                ?>
                <tr>
                    <th scope="row" class="check-column">
                        <input type="checkbox" name="form[]" value="<?php echo esc_attr($form->id); ?>">
                    </th>
                    <td class="column-primary">
                        <strong>
                            <a href="<?php echo admin_url('admin.php?page=efs-form-editor&form_id=' . $form->id); ?>">
                                <?php echo esc_html($form->title); ?>
                            </a>
                        </strong>
                        <div class="row-actions">
                            <span class="edit">
                                <a href="<?php echo admin_url('admin.php?page=efs-form-editor&form_id=' . $form->id); ?>">Edit</a> | 
                            </span>
                            <span class="view">
                                <a href="<?php echo admin_url('admin.php?page=efs-responses&form_id=' . $form->id); ?>">Responses (<?php echo $response_count; ?>)</a> | 
                            </span>
                            <span class="analytics">
                                <a href="<?php echo admin_url('admin.php?page=efs-analytics&form_id=' . $form->id); ?>">Analytics</a> | 
                            </span>
                            <span class="toggle">
                                <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=efs-forms&action=toggle&form_id=' . $form->id), 'toggle_form_' . $form->id); ?>">
                                    <?php echo $form->is_active ? 'Deactivate' : 'Activate'; ?>
                                </a> | 
                            </span>
                            <span class="trash">
                                <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=efs-forms&action=delete&form_id=' . $form->id), 'delete_form_' . $form->id); ?>" 
                                   class="submitdelete" 
                                   onclick="return confirm('Are you sure you want to delete this form? All responses will also be deleted.');">
                                    Delete
                                </a>
                            </span>
                        </div>
                    </td>
                    <td>
                        <?php if ($form->naac_criterion): ?>
                            <span class="efs-badge efs-badge-naac">NAAC Criterion <?php echo intval($form->naac_criterion); ?></span>
                        <?php elseif ($form->nba_outcome): ?>
                            <span class="efs-badge efs-badge-nba"><?php echo esc_html($form->nba_outcome); ?></span>
                        <?php else: ?>
                            <span class="efs-badge"><?php echo esc_html(ucfirst($form->form_type)); ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo esc_html(ucfirst($form->stakeholder_type)); ?></td>
                    <td>
                        <strong><?php echo $response_count; ?></strong> responses<br>
                        <small><?php echo $question_count; ?> questions</small>
                    </td>
                    <td>
                        <?php if ($form->is_active): ?>
                            <span class="efs-status efs-status-active">Active</span>
                        <?php else: ?>
                            <span class="efs-status efs-status-inactive">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo admin_url('admin.php?page=efs-form-editor&form_id=' . $form->id); ?>" class="button button-small">Edit</a>
                        <a href="<?php echo admin_url('admin.php?page=efs-responses&form_id=' . $form->id); ?>" class="button button-small">View Responses</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
