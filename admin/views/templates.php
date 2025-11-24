<?php
/**
 * Templates View - Quick Create from NAAC/NBA Templates
 *
 * @package Elite_Feedback_System
 */

if (!defined('ABSPATH')) exit;
?>

<div class="wrap efs-admin-wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <p class="description">Quickly create feedback forms using pre-built NAAC and NBA templates.</p>
    
    <div class="efs-editor-layout">
        <div class="efs-editor-main">
            <!-- NAAC Templates -->
            <div class="efs-panel">
                <h2>NAAC Feedback Templates</h2>
                <p>Create feedback forms for NAAC accreditation criteria.</p>
                
                <form method="post" action="">
                    <?php wp_nonce_field('create_from_template'); ?>
                    <input type="hidden" name="create_from_template" value="1">
                    
                    <table class="form-table">
                        <tr>
                            <th><label for="naac_template">Select Template *</label></th>
                            <td>
                                <select name="template_type" id="naac_template" required onchange="efsShowTemplateForm(this.value)">
                                    <option value="">Choose a template...</option>
                                    <option value="naac_all">All 7 NAAC Criteria (Creates 7 forms)</option>
                                    <optgroup label="Individual Criteria">
                                        <option value="naac_1">Criterion 1: Curricular Aspects</option>
                                        <option value="naac_2">Criterion 2: Teaching-Learning and Evaluation</option>
                                        <option value="naac_3">Criterion 3: Research, Innovations and Extension</option>
                                        <option value="naac_4">Criterion 4: Infrastructure and Learning Resources</option>
                                        <option value="naac_5">Criterion 5: Student Support and Progression</option>
                                        <option value="naac_6">Criterion 6: Governance, Leadership and Management</option>
                                        <option value="naac_7">Criterion 7: Institutional Values and Best Practices</option>
                                    </optgroup>
                                </select>
                            </td>
                        </tr>
                    </table>
                    
                    <div id="naac-form-fields" class="efs-template-form" style="display:none;">
                        <table class="form-table">
                            <tr>
                                <th><label for="stakeholder_type">Stakeholder Type *</label></th>
                                <td>
                                    <select name="stakeholder_type" id="stakeholder_type">
                                        <option value="students">Students</option>
                                        <option value="faculty">Faculty</option>
                                        <option value="alumni">Alumni</option>
                                        <option value="employers">Employers</option>
                                        <option value="parents">Parents</option>
                                    </select>
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
                                           value="<?php echo date('Y') . '-' . (date('Y') + 1); ?>">
                                </td>
                            </tr>
                            <tr>
                                <th><label for="semester">Semester *</label></th>
                                <td>
                                    <select name="semester" id="semester">
                                        <option value="Odd Semester">Odd Semester</option>
                                        <option value="Even Semester">Even Semester</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        
                        <p class="submit">
                            <button type="submit" class="button button-primary button-large">
                                <span class="dashicons dashicons-plus"></span>
                                Create Form(s) from Template
                            </button>
                        </p>
                    </div>
                </form>
            </div>
            
            <!-- NBA Templates -->
            <div class="efs-panel">
                <h2>NBA Feedback Templates</h2>
                <p>Create feedback forms for NBA outcome-based education.</p>
                
                <form method="post" action="">
                    <?php wp_nonce_field('create_from_template'); ?>
                    <input type="hidden" name="create_from_template" value="1">
                    
                    <table class="form-table">
                        <tr>
                            <th><label for="nba_template">Select Template *</label></th>
                            <td>
                                <select name="template_type" id="nba_template" required onchange="efsShowTemplateForm(this.value)">
                                    <option value="">Choose a template...</option>
                                    <option value="nba_course">Course Feedback</option>
                                    <option value="nba_faculty">Faculty Performance Evaluation</option>
                                    <option value="nba_po">Program Outcome Attainment</option>
                                    <option value="nba_employer">Employer Feedback</option>
                                    <option value="nba_alumni">Alumni Feedback</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    
                    <div id="nba-course-fields" class="efs-template-form" style="display:none;">
                        <table class="form-table">
                            <tr>
                                <th><label for="course_name">Course Name *</label></th>
                                <td>
                                    <input type="text" 
                                           name="course_name" 
                                           id="course_name" 
                                           class="regular-text" 
                                           placeholder="Data Structures">
                                </td>
                            </tr>
                            <tr>
                                <th><label for="department">Department/Program *</label></th>
                                <td>
                                    <input type="text" 
                                           name="department" 
                                           id="department" 
                                           class="regular-text" 
                                           placeholder="Computer Science">
                                </td>
                            </tr>
                            <tr>
                                <th><label for="academic_year_nba">Academic Year *</label></th>
                                <td>
                                    <input type="text" 
                                           name="academic_year" 
                                           id="academic_year_nba" 
                                           class="regular-text" 
                                           placeholder="2024-2025" 
                                           value="<?php echo date('Y') . '-' . (date('Y') + 1); ?>">
                                </td>
                            </tr>
                            <tr>
                                <th><label for="semester_nba">Semester *</label></th>
                                <td>
                                    <select name="semester" id="semester_nba">
                                        <option value="Odd Semester">Odd Semester</option>
                                        <option value="Even Semester">Even Semester</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        
                        <p class="submit">
                            <button type="submit" class="button button-primary button-large">
                                <span class="dashicons dashicons-plus"></span>
                                Create Form from Template
                            </button>
                        </p>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Sidebar Info -->
        <div class="efs-editor-sidebar">
            <div class="efs-panel">
                <h3>About Templates</h3>
                <p>Templates provide pre-configured feedback forms with questions specifically designed for NAAC and NBA requirements.</p>
                
                <h4>NAAC Templates</h4>
                <ul>
                    <li>All 7 criteria covered</li>
                    <li>Stakeholder-specific questions</li>
                    <li>Criterion-wise mapping</li>
                </ul>
                
                <h4>NBA Templates</h4>
                <ul>
                    <li>PO1-PO12 outcome mapping</li>
                    <li>Course & Faculty evaluation</li>
                    <li>Employer & Alumni feedback</li>
                </ul>
            </div>
        </div>
    </div>
</div>
