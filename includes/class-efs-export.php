<?php
/**
 * Export and report generation helper class.
 *
 * @package    Elite_Feedback_System
 * @subpackage Elite_Feedback_System/includes
 */

class EFS_Export {

    /**
     * Generate NAAC criterion report
     */
    public static function generate_naac_report($criterion = null, $academic_year = null, $format = 'pdf') {
        $data = EFS_Analytics::get_naac_analytics($criterion, $academic_year);
        
        $report_data = array(
            'title' => 'NAAC Criterion Feedback Report',
            'criterion' => $criterion,
            'academic_year' => $academic_year,
            'generated_date' => current_time('mysql'),
            'data' => $data,
            'institution_name' => EFS_Database::get_setting('institution_name', get_bloginfo('name'))
        );
        
        if ($format === 'pdf') {
            return self::generate_pdf_report($report_data, 'naac');
        } elseif ($format === 'excel') {
            return self::generate_excel_report($report_data, 'naac');
        } else {
            return self::generate_csv_report($report_data, 'naac');
        }
    }
    
    /**
     * Generate NBA outcome report
     */
    public static function generate_nba_report($program = null, $academic_year = null, $format = 'pdf') {
        $data = EFS_Analytics::get_nba_analytics($program, $academic_year);
        
        $report_data = array(
            'title' => 'NBA Program Outcome Feedback Report',
            'program' => $program,
            'academic_year' => $academic_year,
            'generated_date' => current_time('mysql'),
            'data' => $data,
            'institution_name' => EFS_Database::get_setting('institution_name', get_bloginfo('name'))
        );
        
        if ($format === 'pdf') {
            return self::generate_pdf_report($report_data, 'nba');
        } elseif ($format === 'excel') {
            return self::generate_excel_report($report_data, 'nba');
        } else {
            return self::generate_csv_report($report_data, 'nba');
        }
    }
    
    /**
     * Generate PDF report
     */
    private static function generate_pdf_report($data, $type) {
        // Create uploads directory if needed
        $upload_dir = wp_upload_dir();
        $reports_dir = $upload_dir['basedir'] . '/elite-feedback-system/reports';
        
        if (!file_exists($reports_dir)) {
            wp_mkdir_p($reports_dir);
        }
        
        $filename = sprintf(
            '%s-report-%s-%s.pdf',
            $type,
            $data['academic_year'] ?? 'all',
            date('YmdHis')
        );
        
        $filepath = $reports_dir . '/' . $filename;
        
        // Generate HTML content for PDF
        $html = self::get_report_html($data, $type);
        
        // Note: In production, you would use a PDF library like TCPDF or mPDF
        // For now, we'll create a simple HTML file and return the path
        // You can integrate a PDF library later
        
        file_put_contents($filepath, $html);
        
        // Save report record to database
        global $wpdb;
        $table = $wpdb->prefix . 'efs_reports';
        
        $wpdb->insert($table, array(
            'report_type' => $type,
            'title' => $data['title'],
            'parameters' => json_encode(array(
                'criterion' => $data['criterion'] ?? null,
                'program' => $data['program'] ?? null,
                'academic_year' => $data['academic_year'] ?? null
            )),
            'file_path' => $filepath,
            'file_type' => 'pdf',
            'generated_by' => get_current_user_id()
        ));
        
        return array(
            'success' => true,
            'file_path' => $filepath,
            'file_url' => $upload_dir['baseurl'] . '/elite-feedback-system/reports/' . $filename,
            'filename' => $filename
        );
    }
    
    /**
     * Generate Excel report
     */
    private static function generate_excel_report($data, $type) {
        $upload_dir = wp_upload_dir();
        $reports_dir = $upload_dir['basedir'] . '/elite-feedback-system/reports';
        
        if (!file_exists($reports_dir)) {
            wp_mkdir_p($reports_dir);
        }
        
        $filename = sprintf(
            '%s-report-%s-%s.csv',
            $type,
            $data['academic_year'] ?? 'all',
            date('YmdHis')
        );
        
        $filepath = $reports_dir . '/' . $filename;
        
        // Create CSV file
        $file = fopen($filepath, 'w');
        
        // Add header
        fputcsv($file, array(
            $data['institution_name'],
            $data['title'],
            'Generated: ' . date('F j, Y, g:i a')
        ));
        
        fputcsv($file, array()); // Empty row
        
        // Add data headers based on type
        if ($type === 'naac') {
            fputcsv($file, array(
                'NAAC Criterion',
                'Stakeholder Type',
                'Form Count',
                'Response Count',
                'Average Score'
            ));
            
            foreach ($data['data'] as $row) {
                fputcsv($file, array(
                    'Criterion ' . $row->naac_criterion,
                    ucfirst($row->stakeholder_type),
                    $row->form_count,
                    $row->response_count,
                    round($row->avg_score, 2)
                ));
            }
        } else {
            fputcsv($file, array(
                'NBA Outcome',
                'Department/Program',
                'Stakeholder Type',
                'Form Count',
                'Response Count',
                'Average Score'
            ));
            
            foreach ($data['data'] as $row) {
                fputcsv($file, array(
                    $row->nba_outcome,
                    $row->department,
                    ucfirst($row->stakeholder_type),
                    $row->form_count,
                    $row->response_count,
                    round($row->avg_score, 2)
                ));
            }
        }
        
        fclose($file);
        
        return array(
            'success' => true,
            'file_path' => $filepath,
            'file_url' => $upload_dir['baseurl'] . '/elite-feedback-system/reports/' . $filename,
            'filename' => $filename
        );
    }
    
    /**
     * Generate CSV report
     */
    private static function generate_csv_report($data, $type) {
        return self::generate_excel_report($data, $type); // CSV and Excel use same method
    }
    
    /**
     * Get report HTML template
     */
    private static function get_report_html($data, $type) {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title><?php echo esc_html($data['title']); ?></title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 40px;
                    color: #333;
                }
                .header {
                    text-align: center;
                    border-bottom: 3px solid #2c3e50;
                    padding-bottom: 20px;
                    margin-bottom: 30px;
                }
                .institution-name {
                    font-size: 24px;
                    font-weight: bold;
                    color: #2c3e50;
                    margin-bottom: 10px;
                }
                .report-title {
                    font-size: 20px;
                    color: #34495e;
                    margin-bottom: 5px;
                }
                .report-meta {
                    color: #7f8c8d;
                    font-size: 14px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                }
                th {
                    background-color: #3498db;
                    color: white;
                    padding: 12px;
                    text-align: left;
                    font-weight: bold;
                }
                td {
                    padding: 10px;
                    border-bottom: 1px solid #ddd;
                }
                tr:nth-child(even) {
                    background-color: #f8f9fa;
                }
                .footer {
                    margin-top: 40px;
                    padding-top: 20px;
                    border-top: 2px solid #ecf0f1;
                    text-align: center;
                    color: #95a5a6;
                    font-size: 12px;
                }
                .summary {
                    background-color: #e8f4f8;
                    padding: 15px;
                    border-left: 4px solid #3498db;
                    margin: 20px 0;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="institution-name"><?php echo esc_html($data['institution_name']); ?></div>
                <div class="report-title"><?php echo esc_html($data['title']); ?></div>
                <div class="report-meta">
                    <?php if (!empty($data['academic_year'])): ?>
                        Academic Year: <?php echo esc_html($data['academic_year']); ?> |
                    <?php endif; ?>
                    Generated: <?php echo date('F j, Y, g:i a'); ?>
                </div>
            </div>
            
            <?php if ($type === 'naac'): ?>
                <h2>NAAC Criterion-wise Feedback Analysis</h2>
                
                <?php if (!empty($data['criterion'])): ?>
                    <div class="summary">
                        <strong>Filtered by:</strong> Criterion <?php echo intval($data['criterion']); ?>
                    </div>
                <?php endif; ?>
                
                <table>
                    <thead>
                        <tr>
                            <th>NAAC Criterion</th>
                            <th>Stakeholder Type</th>
                            <th>Form Count</th>
                            <th>Response Count</th>
                            <th>Average Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['data'])): ?>
                            <?php foreach ($data['data'] as $row): ?>
                                <tr>
                                    <td>Criterion <?php echo intval($row->naac_criterion); ?></td>
                                    <td><?php echo esc_html(ucfirst($row->stakeholder_type)); ?></td>
                                    <td><?php echo intval($row->form_count); ?></td>
                                    <td><?php echo intval($row->response_count); ?></td>
                                    <td><?php echo number_format($row->avg_score, 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: #7f8c8d;">No data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
            <?php else: // NBA ?>
                <h2>NBA Program Outcome Feedback Analysis</h2>
                
                <?php if (!empty($data['program'])): ?>
                    <div class="summary">
                        <strong>Program/Department:</strong> <?php echo esc_html($data['program']); ?>
                    </div>
                <?php endif; ?>
                
                <table>
                    <thead>
                        <tr>
                            <th>NBA Outcome</th>
                            <th>Department/Program</th>
                            <th>Stakeholder Type</th>
                            <th>Form Count</th>
                            <th>Response Count</th>
                            <th>Average Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['data'])): ?>
                            <?php foreach ($data['data'] as $row): ?>
                                <tr>
                                    <td><?php echo esc_html($row->nba_outcome); ?></td>
                                    <td><?php echo esc_html($row->department); ?></td>
                                    <td><?php echo esc_html(ucfirst($row->stakeholder_type)); ?></td>
                                    <td><?php echo intval($row->form_count); ?></td>
                                    <td><?php echo intval($row->response_count); ?></td>
                                    <td><?php echo number_format($row->avg_score, 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; color: #7f8c8d;">No data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php endif; ?>
            
            <div class="footer">
                <p>This report was automatically generated by Elite Feedback System</p>
                <p>For accreditation purposes (NAAC/NBA)</p>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Export form responses to CSV
     */
    public static function export_form_responses($form_id) {
        $form = EFS_Database::get_form($form_id);
        $questions = EFS_Database::get_questions($form_id);
        $responses = EFS_Database::get_responses($form_id);
        
        $upload_dir = wp_upload_dir();
        $exports_dir = $upload_dir['basedir'] . '/elite-feedback-system/exports';
        
        if (!file_exists($exports_dir)) {
            wp_mkdir_p($exports_dir);
        }
        
        $filename = sprintf(
            'responses-%s-%s.csv',
            sanitize_file_name($form->title),
            date('YmdHis')
        );
        
        $filepath = $exports_dir . '/' . $filename;
        $file = fopen($filepath, 'w');
        
        // Build header row
        $headers = array('Response ID', 'Session ID', 'Submitted At');
        foreach ($questions as $question) {
            $headers[] = strip_tags($question->question_text);
        }
        fputcsv($file, $headers);
        
        // Group responses by session
        $sessions = array();
        foreach ($responses as $response) {
            if (!isset($sessions[$response->session_id])) {
                $sessions[$response->session_id] = array(
                    'session_id' => $response->session_id,
                    'submitted_at' => $response->submitted_at,
                    'responses' => array()
                );
            }
            $sessions[$response->session_id]['responses'][$response->question_id] = $response->response_value;
        }
        
        // Write data rows
        $row_id = 1;
        foreach ($sessions as $session) {
            $row = array($row_id++, $session['session_id'], $session['submitted_at']);
            foreach ($questions as $question) {
                $row[] = $session['responses'][$question->id] ?? '';
            }
            fputcsv($file, $row);
        }
        
        fclose($file);
        
        return array(
            'success' => true,
            'file_path' => $filepath,
            'file_url' => $upload_dir['baseurl'] . '/elite-feedback-system/exports/' . $filename,
            'filename' => $filename
        );
    }
}
