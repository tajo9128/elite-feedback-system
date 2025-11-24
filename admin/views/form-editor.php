<?php
/**
 * Form Editor View - The powerful form builder interface
 *
 * @package Elite_Feedback_System
 */

if (!defined('ABSPATH')) exit;

$editing = !empty($form);
$page_title = $editing ? 'Edit Form' : 'Add New Form';
?>

<div class="wrap efs-admin-wrap">
    <h1><?php echo esc_html($page_title); ?></h1>
    
    <form method="post" action="" id="efs-form-editor">
        <?php wp_nonce_field('efs_form_editor'); ?>
        <input type="hidden" name="efs_save_form" value="1">
        
        <div class="efs-editor-layout">
            <!-- Main Form Details -->
            <div class="efs-editor-main">
                <div class="efs-panel">
                    <h2>Form Details</h2>
                    
                    <table class="form-table">
                        <tr>
                            <th><label for="title">Form Title *</label></th>
                            <td>
                                <input type="text" 
                                       name="title" 
                                       id="title" 
                                       class="regular-text" 
                                       value="<?php echo $editing ? esc_attr($form->title) : ''; ?>" 
                                       required>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="description">Description</label></th>
                            <td>
                                <textarea name="description" 
                                          id="description" 
                                          rows="3" 
                                          class="large-text"><?php echo $editing ? esc_textarea($form->description) : ''; ?></textarea>
                                <p class="description">Brief description of this feedback form</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="stakeholder_type">Stakeholder Type *</label></th>
                            <td>
                                <select name="stakeholder_type" id="stakeholder_type" required>
                                    <?php 
                                    $stakeholders = array('students', 'faculty', 'alumni', 'employers', 'parents');
                                    foreach ($stakeholders as $type): 
                                        $selected = $editing && $form->stakeholder_type === $type ? 'selected' : '';
                                    ?>
                                        <option value="<?php echo esc_attr($type); ?>" <?php echo $selected; ?>>
                                            <?php echo esc_html(ucfirst($type)); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="form_type">Form Type</label></th>
                            <td>
                                <select name="form_type" id="form_type">
                                    <option value="general" <?php echo $editing && $form->form_type === 'general' ? 'selected' : ''; ?>>General</option>
                                    <option value="naac" <?php echo $editing && $form->form_type === 'naac' ? 'selected' : ''; ?>>NAAC</option>
                                    <option value="nba_course" <?php echo $editing && $form->form_type === 'nba_course' ? 'selected' : ''; ?>>NBA Course</option>
                                    <option value="nba_faculty" <?php echo $editing && $form->form_type === 'nba_faculty' ? 'selected' : ''; ?>>NBA Faculty</option>
                                </select>
                            </td>
                        </tr>
                        <tr id="naac_criterion_row">
                            <th><label for="naac_criterion">NAAC Criterion</label></th>
                            <td>
                                <select name="naac_criterion" id="naac_criterion">
                                    <option value="">None</option>
                                    <?php for ($i = 1; $i <= 7; $i++): 
                                        $selected = $editing && $form->naac_criterion == $i ? 'selected' : '';
                                    ?>
                                        <option value="<?php echo $i; ?>" <?php echo $selected; ?>>Criterion <?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="nba_outcome">NBA Outcome</label></th>
                            <td>
                                <input type="text" 
                                       name="nba_outcome" 
                                       id="nba_outcome" 
                                       class="regular-text" 
                                       value="<?php echo $editing ? esc_attr($form->nba_outcome) : ''; ?>"
                                       placeholder="e.g., PO1, PO2">
                                <p class="description">e.g., PO1, PO2, PSO1</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="academic_year">Academic Year</label></th>
                            <td>
                                <input type="text" 
                                       name="academic_year" 
                                       id="academic_year" 
                                       class="regular-text" 
                                       value="<?php echo $editing ? esc_attr($form->academic_year) : ''; ?>"
                                       placeholder="2024-2025">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="semester">Semester</label></th>
                            <td>
                                <input type="text" 
                                       name="semester" 
                                       id="semester" 
                                       class="regular-text" 
                                       value="<?php echo $editing ? esc_attr($form->semester) : ''; ?>"
                                       placeholder="Even Semester">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="department">Department/Program</label></th>
                            <td>
                                <input type="text" 
                                       name="department" 
                                       id="department" 
                                       class="regular-text" 
                                       value="<?php echo $editing ? esc_attr($form->department) : ''; ?>">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="start_date">Start Date</label></th>
                            <td>
                                <input type="date" 
                                       name="start_date" 
                                       id="start_date" 
                                       value="<?php echo $editing && $form->start_date ? date('Y-m-d', strtotime($form->start_date)) : ''; ?>">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="end_date">End Date</label></th>
                            <td>
                                <input type="date" 
                                       name="end_date" 
                                       id="end_date" 
                                       value="<?php echo $editing &&$form->end_date ? date('Y-m-d', strtotime($form->end_date)) : ''; ?>">
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Questions Builder -->
                <div class="efs-panel">
                    <div class="efs-panel-header">
                        <h2>Questions</h2>
                        <button type="button" class="button" id="efs-add-question">
                            <span class="dashicons dashicons-plus"></span> Add Question
                        </button>
                    </div>
                    
                    <div id="efs-questions-container" class="efs-questions-container">
                        <?php if (!empty($questions)): ?>
                            <?php foreach ($questions as $index => $question): ?>
                                <?php include EFS_PLUGIN_DIR . 'admin/views/partials/question-item.php'; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="efs-no-questions">No questions added yet. Click "Add Question" to start building your form.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="efs-editor-sidebar">
                <div class="efs-panel">
                    <h3>Publish</h3>
                    <div class="efs-publish-actions">
                        <label>
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1" 
                                   <?php echo $editing && $form->is_active ? 'checked' : 'checked'; ?>>
                            Active (visible to users)
                        </label>
                        
                        <label>
                            <input type="checkbox" 
                                   name="is_anonymous" 
                                   value="1" 
                                   <?php echo $editing && $form->is_anonymous ? 'checked' : ''; ?>>
                            Anonymous responses
                        </label>
                        
                        <hr>
                        
                        <button type="submit" class="button button-primary button-large" style="width: 100%;">
                            <?php echo $editing ? 'Update Form' : 'Create Form'; ?>
                        </button>
                        
                        <?php if ($editing): ?>
                            <a href="<?php echo admin_url('admin.php?page=efs-forms'); ?>" class="button button-large" style="width: 100%; text-align: center;">Cancel</a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if ($editing): ?>
                <div class="efs-panel">
                    <h3>Form Info</h3>
                    <p><strong>Created:</strong><br><?php echo date('F j, Y', strtotime($form->created_at)); ?></p>
                    <p><strong>Questions:</strong> <?php echo count($questions); ?></p>
                    <p><strong>Responses:</strong> <?php echo EFS_Database::get_response_count($form->id); ?></p>
                </div>
                <?php endif; ?>
                
                <div class="efs-panel">
                    <h3>Shortcode</h3>
                    <?php if ($editing): ?>
                        <input type="text" 
                               class="regular-text" 
                               value='[efs_feedback_form id="<?php echo $form->id; ?>"]' 
                               readonly 
                               onclick="this.select();">
                        <p class="description">Copy this shortcode to display this form on any page.</p>
                    <?php else: ?>
                        <p class="description">Shortcode will be available after creating the form.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Question Template (hidden, used by JavaScript) -->
<script type="text/template" id="efs-question-template">
    <div class="efs-question-item" data-index="__INDEX__">
        <div class="efs-question-header">
            <span class="efs-question-drag">
                <span class="dashicons dashicons-menu"></span>
            </span>
            <span class="efs-question-title">New Question</span>
            <button type="button" class="efs-question-toggle">
                <span class="dashicons dashicons-arrow-down-alt2"></span>
            </button>
        </div>
        <div class="efs-question-body">
            <table class="form-table">
                <tr>
                    <th><label>Question Text *</label></th>
                    <td>
                        <textarea name="questions[__INDEX__][question_text]" rows="2" class="large-text" required></textarea>
                    </td>
                </tr>
                <tr>
                    <th><label>Question Type *</label></th>
                    <td>
                        <select name="questions[__INDEX__][question_type]" class="efs-question-type" required>
                            <option value="scale">Scale/Rating (1-5)</option>
                            <option value="mcq">Multiple Choice</option>
                            <option value="checkbox">Checkboxes</option>
                            <option value="yes_no">Yes/No</option>
                            <option value="text">Short Text</option>
                            <option value="textarea">Long Text</option>
                        </select>
                    </td>
                </tr>
                <tr class="efs-options-row" style="display:none;">
                    <th><label>Options</label></th>
                    <td>
                        <textarea name="questions[__INDEX__][options]" rows="3" class="large-text" placeholder="Enter one option per line"></textarea>
                        <p class="description">For MCQ/Checkbox questions, enter one option per line</p>
                    </td>
                </tr>
                <tr>
                    <th><label>Required</label></th>
                    <td>
                        <label>
                            <input type="checkbox" name="questions[__INDEX__][is_required]" value="1" checked>
                            This question is required
                        </label>
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <button type="button" class="button efs-remove-question">Remove Question</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</script>
