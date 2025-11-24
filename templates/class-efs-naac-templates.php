<?php
/**
 * NAAC Feedback Templates.
 *
 * @package    Elite_Feedback_System
 * @subpackage Elite_Feedback_System/templates
 */

class EFS_NAAC_Templates {

    /**
     * Get all NAAC criteria
     */
    public static function get_criteria() {
        return array(
            1 => 'Curricular Aspects',
            2 => 'Teaching-Learning and Evaluation',
            3 => 'Research, Innovations and Extension',
            4 => 'Infrastructure and Learning Resources',
            5 => 'Student Support and Progression',
            6 => 'Governance, Leadership and Management',
            7 => 'Institutional Values and Best Practices'
        );
    }

    /**
     * Get template for a specific criterion
     */
    public static function get_template($criterion, $stakeholder_type = 'students') {
        $templates = array(
            1 => self::criterion_1_template($stakeholder_type),
            2 => self::criterion_2_template($stakeholder_type),
            3 => self::criterion_3_template($stakeholder_type),
            4 => self::criterion_4_template($stakeholder_type),
            5 => self::criterion_5_template($stakeholder_type),
            6 => self::criterion_6_template($stakeholder_type),
            7 => self::criterion_7_template($stakeholder_type)
        );
        
        return isset($templates[$criterion]) ? $templates[$criterion] : null;
    }

    /**
     * Criterion 1: Curricular Aspects
     */
    private static function criterion_1_template($stakeholder_type) {
        $criteria = self::get_criteria();
        
        return array(
            'title' => 'NAAC Criterion 1: ' . $criteria[1],
            'description' => 'Feedback on curriculum design, development, and implementation',
            'naac_criterion' => 1,
            'stakeholder_type' => $stakeholder_type,
            'questions' => array(
                array(
                    'question_text' => 'The syllabus is well-structured and covers all relevant topics.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 1
                ),
                array(
                    'question_text' => 'The curriculum provides adequate knowledge and skills relevant to the industry/profession.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 1
                ),
                array(
                    'question_text' => 'Course materials and resources are up-to-date and relevant.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 1
                ),
                array(
                    'question_text' => 'The curriculum includes sufficient practical/hands-on learning opportunities.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 1
                ),
                array(
                    'question_text' => 'Suggestions for curriculum improvement (if any):',
                    'question_type' => 'textarea',
                    'is_required' => 0,
                    'weightage' => 0,
                    'naac_criterion' => 1
                )
            )
        );
    }

    /**
     * Criterion 2: Teaching-Learning and Evaluation
     */
    private static function criterion_2_template($stakeholder_type) {
        $criteria = self::get_criteria();
        
        return array(
            'title' => 'NAAC Criterion 2: ' . $criteria[2],
            'description' => 'Feedback on teaching methods, learning experiences, and evaluation processes',
            'naac_criterion' => 2,
            'stakeholder_type' => $stakeholder_type,
            'questions' => array(
                array(
                    'question_text' => 'Teachers demonstrate thorough knowledge of the subject matter.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 2
                ),
                array(
                    'question_text' => 'Teaching methods are effective and engaging.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 2
                ),
                array(
                    'question_text' => 'Teachers use modern teaching aids and technology effectively.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 2
                ),
                array(
                    'question_text' => 'Evaluation methods are fair and transparent.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 2
                ),
                array(
                    'question_text' => 'Feedback on assignments/exams is timely and constructive.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 2
                ),
                array(
                    'question_text' => 'Teachers are accessible for doubt clearing and guidance.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 2
                )
            )
        );
    }

    /**
     * Criterion 3: Research, Innovations and Extension
     */
    private static function criterion_3_template($stakeholder_type) {
        $criteria = self::get_criteria();
        
        return array(
            'title' => 'NAAC Criterion 3: ' . $criteria[3],
            'description' => 'Feedback on research culture, innovation, and community outreach',
            'naac_criterion' => 3,
            'stakeholder_type' => $stakeholder_type,
            'questions' => array(
                array(
                    'question_text' => 'The institution promotes research culture among students.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 3
                ),
                array(
                    'question_text' => 'Adequate research facilities and resources are available.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 3
                ),
                array(
                    'question_text' => 'Students are encouraged to participate in innovation and entrepreneurship activities.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 3
                ),
                array(
                    'question_text' => 'The institution engages in meaningful community outreach programs.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 3
                )
            )
        );
    }

    /**
     * Criterion 4: Infrastructure and Learning Resources
     */
    private static function criterion_4_template($stakeholder_type) {
        $criteria = self::get_criteria();
        
        return array(
            'title' => 'NAAC Criterion 4: ' . $criteria[4],
            'description' => 'Feedback on infrastructure facilities and learning resources',
            'naac_criterion' => 4,
            'stakeholder_type' => $stakeholder_type,
            'questions' => array(
                array(
                    'question_text' => 'Classrooms are well-equipped and comfortable.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 4
                ),
                array(
                    'question_text' => 'Laboratory facilities are adequate and well-maintained.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 4
                ),
                array(
                    'question_text' => 'Library resources (books, journals, digital resources) are sufficient.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 4
                ),
                array(
                    'question_text' => 'Internet and Wi-Fi connectivity is reliable across campus.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 4
                ),
                array(
                    'question_text' => 'Sports and recreational facilities are accessible and adequate.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 4
                )
            )
        );
    }

    /**
     * Criterion 5: Student Support and Progression
     */
    private static function criterion_5_template($stakeholder_type) {
        $criteria = self::get_criteria();
        
        return array(
            'title' => 'NAAC Criterion 5: ' . $criteria[5],
            'description' => 'Feedback on student support services and career progression',
            'naac_criterion' => 5,
            'stakeholder_type' => $stakeholder_type,
            'questions' => array(
                array(
                    'question_text' => 'The institution provides adequate career guidance and counseling.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 5
                ),
                array(
                    'question_text' => 'Placement and training opportunities meet expectations.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 5
                ),
                array(
                    'question_text' => 'Scholarships and financial aid are accessible to deserving students.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 5
                ),
                array(
                    'question_text' => 'Co-curricular and extracurricular activities are well-organized.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 5
                )
            )
        );
    }

    /**
     * Criterion 6: Governance, Leadership and Management
     */
    private static function criterion_6_template($stakeholder_type) {
        $criteria = self::get_criteria();
        
        return array(
            'title' => 'NAAC Criterion 6: ' . $criteria[6],
            'description' => 'Feedback on institutional governance and administration',
            'naac_criterion' => 6,
            'stakeholder_type' => $stakeholder_type,
            'questions' => array(
                array(
                    'question_text' => 'The institution\'s vision and mission are clearly communicated.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 6
                ),
                array(
                    'question_text' => 'Administrative processes are transparent and efficient.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 6
                ),
                array(
                    'question_text' => 'The grievance redressal mechanism is effective.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 6
                ),
                array(
                    'question_text' => 'Financial management and resource allocation are appropriate.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 6
                )
            )
        );
    }

    /**
     * Criterion 7: Institutional Values and Best Practices
     */
    private static function criterion_7_template($stakeholder_type) {
        $criteria = self::get_criteria();
        
        return array(
            'title' => 'NAAC Criterion 7: ' . $criteria[7],
            'description' => 'Feedback on institutional values, ethics, and best practices',
            'naac_criterion' => 7,
            'stakeholder_type' => $stakeholder_type,
            'questions' => array(
                array(
                    'question_text' => 'The institution promotes gender equity and inclusiveness.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 7
                ),
                array(
                    'question_text' => 'Environmental sustainability practices are implemented effectively.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 7
                ),
                array(
                    'question_text' => 'The institution maintains high ethical and professional standards.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 7
                ),
                array(
                    'question_text' => 'The campus fosters a safe and respectful environment.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'naac_criterion' => 7
                )
            )
        );
    }

    /**
     * Create all NAAC templates for a stakeholder
     */
    public static function create_all_templates($stakeholder_type, $academic_year, $semester = '') {
        $created_forms = array();
        
        for ($criterion = 1; $criterion <= 7; $criterion++) {
            $template = self::get_template($criterion, $stakeholder_type);
            
            if (!$template) {
                continue;
            }
            
            $template['academic_year'] = $academic_year;
            $template['semester'] = $semester;
            
            // Create form
            $form_id = EFS_Database::create_form($template);
            
            // Create questions
            foreach ($template['questions'] as $index => $question) {
                $question['form_id'] = $form_id;
                $question['sort_order'] = $index;
                EFS_Database::create_question($question);
            }
            
            $created_forms[] = $form_id;
        }
        
        return $created_forms;
    }
}
