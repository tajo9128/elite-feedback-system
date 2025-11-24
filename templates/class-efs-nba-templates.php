<?php
/**
 * NBA Feedback Templates.
 *
 * @package    Elite_Feedback_System
 * @subpackage Elite_Feedback_System/templates
 */

class EFS_NBA_Templates {

    /**
     * Get all NBA Program Outcomes (PO)
     */
    public static function get_program_outcomes() {
        return array(
            'PO1' => 'Engineering Knowledge',
            'PO2' => 'Problem Analysis',
            'PO3' => 'Design/Development of Solutions',
            'PO4' => 'Conduct Investigations of Complex Problems',
            'PO5' => 'Modern Tool Usage',
            'PO6' => 'The Engineer and Society',
            'PO7' => 'Environment and Sustainability',
            'PO8' => 'Ethics',
            'PO9' => 'Individual and Team Work',
            'PO10' => 'Communication',
            'PO11' => 'Project Management and Finance',
            'PO12' => 'Life-long Learning'
        );
    }

    /**
     * Get template for course feedback (aligned with NBA)
     */
    public static function get_course_feedback_template($course_name, $department, $pos_covered = array()) {
        return array(
            'title' => 'Course Feedback: ' . $course_name,
            'description' => 'Feedback on course delivery and outcome attainment',
            'form_type' => 'nba_course',
            'stakeholder_type' => 'students',
            'department' => $department,
            'nba_outcome' => !empty($pos_covered) ? implode(',', $pos_covered) : '',
            'questions' => array(
                array(
                    'question_text' => 'The course objectives were clearly defined and communicated.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0
                ),
                array(
                    'question_text' => 'The course content was well-organized and covered the syllabus comprehensively.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0
                ),
                array(
                    'question_text' => 'The instructor demonstrated expertise and clarity in teaching.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0
                ),
                array(
                    'question_text' => 'Learning resources (textbooks, references, online materials) were adequate.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0
                ),
                array(
                    'question_text' => 'Practical sessions/labs helped reinforce theoretical concepts.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0
                ),
                array(
                    'question_text' => 'The evaluation pattern (assignments, tests, exams) was fair and comprehensive.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0
                ),
                array(
                    'question_text' => 'I have achieved the course outcomes effectively.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0
                ),
                array(
                    'question_text' => 'Rate your overall satisfaction with this course.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Very Dissatisfied', 'max_label' => 'Very Satisfied')),
                    'is_required' => 1,
                    'weightage' => 1.0
                ),
                array(
                    'question_text' => 'Suggestions for course improvement:',
                    'question_type' => 'textarea',
                    'is_required' => 0,
                    'weightage' => 0
                )
            )
        );
    }

    /**
     * Get template for faculty feedback (students about faculty)
     */
    public static function get_faculty_feedback_template() {
        return array(
            'title' => 'Faculty Performance Feedback',
            'description' => 'Student feedback on faculty teaching effectiveness',
            'form_type' => 'nba_faculty',
            'stakeholder_type' => 'students',
            'questions' => array(
                array(
                    'question_text' => 'The faculty member demonstrates mastery of the subject.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0
                ),
                array(
                    'question_text' => 'The faculty member explains concepts clearly and effectively.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0
                ),
                array(
                    'question_text' => 'The faculty member uses effective teaching methods and aids.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0
                ),
                array(
                    'question_text' => 'The faculty member encourages student participation and interaction.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0
                ),
                array(
                    'question_text' => 'The faculty member is punctual and completes the syllabus on time.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0
                ),
                array(
                    'question_text' => 'The faculty member is accessible for doubt resolution and guidance.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0
                ),
                array(
                    'question_text' => 'The faculty member provides timely and constructive feedback.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0
                )
            )
        );
    }

    /**
     * Get template for program outcome attainment (students)
     */
    public static function get_po_attainment_template($program_outcome) {
        $pos = self::get_program_outcomes();
        $po_title = isset($pos[$program_outcome]) ? $pos[$program_outcome] : $program_outcome;
        
        return array(
            'title' => 'Program Outcome Attainment: ' . $program_outcome . ' - ' . $po_title,
            'description' => 'Self-assessment of program outcome attainment',
            'form_type' => 'nba_po_attainment',
            'stakeholder_type' => 'students',
            'nba_outcome' => $program_outcome,
            'questions' => array(
                array(
                    'question_text' => 'I understand what "' . $po_title . '" means in the context of my program.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'nba_outcome' => $program_outcome
                ),
                array(
                    'question_text' => 'The program curriculum has adequately addressed this outcome.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'nba_outcome' => $program_outcome
                ),
                array(
                    'question_text' => 'I have achieved competency in this outcome through courses and activities.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'nba_outcome' => $program_outcome
                ),
                array(
                    'question_text' => 'Rate your overall attainment level for this outcome.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 3, 'min_label' => 'Low', 'max_label' => 'High')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'nba_outcome' => $program_outcome
                )
            )
        );
    }

    /**
     * Get employer feedback template
     */
    public static function get_employer_feedback_template() {
        return array(
            'title' => 'Employer Feedback on Graduate Competencies',
            'description' => 'Employer assessment of graduate skills and program outcomes',
            'form_type' => 'nba_employer',
            'stakeholder_type' => 'employers',
            'questions' => array(
                array(
                    'question_text' => 'Graduates demonstrate strong technical knowledge in their field.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'nba_outcome' => 'PO1'
                ),
                array(
                    'question_text' => 'Graduates can analyze and solve complex engineering problems effectively.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'nba_outcome' => 'PO2'
                ),
                array(
                    'question_text' => 'Graduates demonstrate ability to design solutions and systems.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'nba_outcome' => 'PO3'
                ),
                array(
                    'question_text' => 'Graduates are proficient in using modern engineering tools and software.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'nba_outcome' => 'PO5'
                ),
                array(
                    'question_text' => 'Graduates work effectively in teams.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'nba_outcome' => 'PO9'
                ),
                array(
                    'question_text' => 'Graduates communicate clearly and professionally.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'nba_outcome' => 'PO10'
                ),
                array(
                    'question_text' => 'Graduates demonstrate professional ethics and responsibility.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'nba_outcome' => 'PO8'
                ),
                array(
                    'question_text' => 'Graduates show commitment to continuous learning and professional development.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0,
                    'nba_outcome' => 'PO12'
                ),
                array(
                    'question_text' => 'Overall, how satisfied are you with the quality of graduates from this institution?',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Very Dissatisfied', 'max_label' => 'Very Satisfied')),
                    'is_required' => 1,
                    'weightage' => 1.0
                ),
                array(
                    'question_text' => 'Suggestions for program improvement:',
                    'question_type' => 'textarea',
                    'is_required' => 0,
                    'weightage' => 0
                )
            )
        );
    }

    /**
     * Get alumni feedback template
     */
    public static function get_alumni_feedback_template() {
        return array(
            'title' => 'Alumni Feedback on Program Quality',
            'description' => 'Alumni assessment of program effectiveness and outcome attainment',
            'form_type' => 'nba_alumni',
            'stakeholder_type' => 'alumni',
            'questions' => array(
                array(
                    'question_text' => 'The program adequately prepared me for my current profession/higher studies.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0
                ),
                array(
                    'question_text' => 'The technical skills learned are relevant and applicable in my work.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0
                ),
                array(
                    'question_text' => 'The program developed my problem-solving and analytical abilities effectively.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0
                ),
                array(
                    'question_text' => 'I can work effectively in multidisciplinary teams due to my education.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0
                ),
                array(
                    'question_text' => 'My communication skills were well-developed during the program.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0
                ),
                array(
                    'question_text' => 'The program instilled values of professional ethics and social responsibility.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0
                ),
                array(
                    'question_text' => 'I am engaged in continuous learning and professional development.',
                    'question_type' => 'scale',
                    'options' => json_encode(array('max' => 5, 'min_label' => 'Strongly Disagree', 'max_label' => 'Strongly Agree')),
                    'is_required' => 1,
                    'weightage' => 1.0
                ),
                array(
                    'question_text' => 'What is your current employment status?',
                    'question_type' => 'mcq',
                    'options' => json_encode(array('choices' => array(
                        'Employed in core engineering field',
                        'Employed in non-core field',
                        'Self-employed/Entrepreneur',
                        'Pursuing higher studies',
                        'Unemployed and seeking opportunities',
                        'Other'
                    ))),
                    'is_required' => 1,
                    'weightage' => 0
                ),
                array(
                    'question_text' => 'Suggestions for improving the program:',
                    'question_type' => 'textarea',
                    'is_required' => 0,
                    'weightage' => 0
                )
            )
        );
    }

    /**
     * Create form from template
     */
    public static function create_form_from_template($template_data, $academic_year, $semester = '') {
        $template_data['academic_year'] = $academic_year;
        $template_data['semester'] = $semester;
        
        // Extract questions
        $questions = $template_data['questions'];
        unset($template_data['questions']);
        
        // Create form
        $form_id = EFS_Database::create_form($template_data);
        
        // Create questions
        foreach ($questions as $index => $question) {
            $question['form_id'] = $form_id;
            if (!isset($question['sort_order'])) {
                $question['sort_order'] = $index;
            }
            EFS_Database::create_question($question);
        }
        
        return $form_id;
    }
}
