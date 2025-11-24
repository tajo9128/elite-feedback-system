<?php
/**
 * Analytics helper class.
 *
 * @package    Elite_Feedback_System
 * @subpackage Elite_Feedback_System/includes
 */

class EFS_Analytics {

    /**
     * Get overview analytics
     */
    public static function get_overview() {
        global $wpdb;
        
        $forms_table = $wpdb->prefix . 'efs_forms';
        $responses_table = $wpdb->prefix . 'efs_responses';
        
        // Total forms
        $total_forms = $wpdb->get_var("SELECT COUNT(*) FROM $forms_table");
        
        // Active forms
        $active_forms = $wpdb->get_var("SELECT COUNT(*) FROM $forms_table WHERE is_active = 1");
        
        // Total responses
        $total_responses = $wpdb->get_var("SELECT COUNT(DISTINCT session_id) FROM $responses_table");
        
        // Responses by stakeholder type
        $responses_by_stakeholder = $wpdb->get_results("
            SELECT f.stakeholder_type, COUNT(DISTINCT r.session_id) as count
            FROM $responses_table r
            INNER JOIN $forms_table f ON r.form_id = f.id
            GROUP BY f.stakeholder_type
        ");
        
        // Recent responses (last 7 days)
        $recent_responses = $wpdb->get_var("
            SELECT COUNT(DISTINCT session_id)
            FROM $responses_table
            WHERE submitted_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        ");
        
        // Response rate trend (last 30 days)
        $trend_data = $wpdb->get_results("
            SELECT DATE(submitted_at) as date, COUNT(DISTINCT session_id) as count
            FROM $responses_table
            WHERE submitted_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY DATE(submitted_at)
            ORDER BY date ASC
        ");
        
        return array(
            'total_forms' => intval($total_forms),
            'active_forms' => intval($active_forms),
            'total_responses' => intval($total_responses),
            'recent_responses' => intval($recent_responses),
            'responses_by_stakeholder' => $responses_by_stakeholder,
            'trend_data' => $trend_data
        );
    }
    
    /**
     * Get form-specific analytics
     */
    public static function get_form_analytics($form_id) {
        global $wpdb;
        
        $responses_table = $wpdb->prefix . 'efs_responses';
        $questions_table = $wpdb->prefix . 'efs_questions';
        
        // Total responses for this form
        $total_responses = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(DISTINCT session_id) FROM $responses_table WHERE form_id = %d",
            $form_id
        ));
        
        // Get all questions with analytics
        $questions = $wpdb->get_results($wpdb->prepare("
            SELECT * FROM $questions_table WHERE form_id = %d ORDER BY sort_order
        ", $form_id));
        
        $question_analytics = array();
        
        foreach ($questions as $question) {
            $stats = self::get_question_statistics($question->id, $question->question_type);
            
            $question_analytics[] = array(
                'question_id' => $question->id,
                'question_text' => $question->question_text,
                'question_type' => $question->question_type,
                'statistics' => $stats
            );
        }
        
        return array(
            'form_id' => $form_id,
            'total_responses' => intval($total_responses),
            'questions' => $question_analytics
        );
    }
    
    /**
     * Get statistics for a specific question
     */
    private static function get_question_statistics($question_id, $question_type) {
        global $wpdb;
        
        $responses_table = $wpdb->prefix . 'efs_responses';
        
        $responses = $wpdb->get_col($wpdb->prepare(
            "SELECT response_value FROM $responses_table WHERE question_id = %d",
            $question_id
        ));
        
        if (empty($responses)) {
            return array(
                'total_responses' => 0,
                'question_type' => $question_type
            );
        }
        
        $stats = array(
            'total_responses' => count($responses),
            'question_type' => $question_type
        );
        
        // For scale/numeric questions
        if (in_array($question_type, array('scale', 'rating', 'number'))) {
            $numeric_responses = array_filter($responses, 'is_numeric');
            
            if (!empty($numeric_responses)) {
                $values = array_map('floatval', $numeric_responses);
                
                $stats['average'] = round(array_sum($values) / count($values), 2);
                $stats['min'] = min($values);
                $stats['max'] = max($values);
                $stats['median'] = self::calculate_median($values);
                $stats['std_deviation'] = self::calculate_std_deviation($values);
                
                // Distribution
                $distribution = array_count_values($numeric_responses);
                ksort($distribution);
                $stats['distribution'] = $distribution;
            }
        }
        
        // For MCQ and other choice-based questions
        if (in_array($question_type, array('mcq', 'checkbox', 'yes_no'))) {
            $distribution = array_count_values($responses);
            arsort($distribution);
            $stats['distribution'] = $distribution;
            
            // Calculate percentages
            $total = count($responses);
            $percentages = array();
            foreach ($distribution as $option => $count) {
                $percentages[$option] = round(($count / $total) * 100, 1);
            }
            $stats['percentages'] = $percentages;
        }
        
        return $stats;
    }
    
    /**
     * Calculate median
     */
    private static function calculate_median($values) {
        sort($values);
        $count = count($values);
        $middle = floor($count / 2);
        
        if ($count % 2 == 0) {
            return ($values[$middle - 1] + $values[$middle]) / 2;
        } else {
            return $values[$middle];
        }
    }
    
    /**
     * Calculate standard deviation
     */
    private static function calculate_std_deviation($values) {
        $count = count($values);
        if ($count < 2) {
            return 0;
        }
        
        $mean = array_sum($values) / $count;
        $variance = 0;
        
        foreach ($values as $value) {
            $variance += pow($value - $mean, 2);
        }
        
        $variance = $variance / ($count - 1);
        return round(sqrt($variance), 2);
    }
    
    /**
     * Get NAAC criterion-wise analytics
     */
    public static function get_naac_analytics($criterion = null, $academic_year = null) {
        global $wpdb;
        
        $forms_table = $wpdb->prefix . 'efs_forms';
        $questions_table = $wpdb->prefix . 'efs_questions';
        $responses_table = $wpdb->prefix . 'efs_responses';
        
        $where = array('f.naac_criterion IS NOT NULL');
        
        if ($criterion) {
            $where[] = $wpdb->prepare('f.naac_criterion = %d', $criterion);
        }
        
        if ($academic_year) {
            $where[] = $wpdb->prepare('f.academic_year = %s', $academic_year);
        }
        
        $where_clause = implode(' AND ', $where);
        
        $query = "
            SELECT 
                f.naac_criterion,
                f.stakeholder_type,
                COUNT(DISTINCT f.id) as form_count,
                COUNT(DISTINCT r.session_id) as response_count,
                AVG(CAST(r.response_value AS DECIMAL(10,2))) as avg_score
            FROM $forms_table f
            LEFT JOIN $responses_table r ON f.id = r.form_id
            WHERE $where_clause
            GROUP BY f.naac_criterion, f.stakeholder_type
            ORDER BY f.naac_criterion, f.stakeholder_type
        ";
        
        return $wpdb->get_results($query);
    }
    
    /**
     * Get NBA outcome-wise analytics
     */
    public static function get_nba_analytics($program = null, $academic_year = null) {
        global $wpdb;
        
        $forms_table = $wpdb->prefix . 'efs_forms';
        $responses_table = $wpdb->prefix . 'efs_responses';
        
        $where = array('f.nba_outcome IS NOT NULL', "f.nba_outcome != ''");
        
        if ($program) {
            $where[] = $wpdb->prepare('f.department = %s', $program);
        }
        
        if ($academic_year) {
            $where[] = $wpdb->prepare('f.academic_year = %s', $academic_year);
        }
        
        $where_clause = implode(' AND ', $where);
        
        $query = "
            SELECT 
                f.nba_outcome,
                f.department,
                f.stakeholder_type,
                COUNT(DISTINCT f.id) as form_count,
                COUNT(DISTINCT r.session_id) as response_count,
                AVG(CAST(r.response_value AS DECIMAL(10,2))) as avg_score
            FROM $forms_table f
            LEFT JOIN $responses_table r ON f.id = r.form_id
            WHERE $where_clause
            GROUP BY f.nba_outcome, f.department, f.stakeholder_type
            ORDER BY f.nba_outcome, f.department
        ";
        
        return $wpdb->get_results($query);
    }
}
