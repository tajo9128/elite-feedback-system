/**
 * Elite Feedback System - Admin JavaScript
 * Handles form builder, drag-and-drop questions, analytics, and more
 */

(function ($) {
    'use strict';

    let questionIndex = 0;

    $(document).ready(function () {
        initFormEditor();
        initAnalytics();
    });

    /**
     * Initialize Form Editor
     */
    function initFormEditor() {
        // Add Question Button
        $('#efs-add-question').on('click', function () {
            addQuestion();
        });

        // Remove Question
        $(document).on('click', '.efs-remove-question', function () {
            if (confirm('Are you sure you want to remove this question?')) {
                $(this).closest('.efs-question-item').fadeOut(300, function () {
                    $(this).remove();
                    updateQuestionNumbers();
                });
            }
        });

        // Toggle Question Body
        $(document).on('click', '.efs-question-toggle', function () {
            const $item = $(this).closest('.efs-question-item');
            const $body = $item.find('.efs-question-body');
            const $icon = $(this).find('.dashicons');

            $body.slideToggle(300);
            $icon.toggleClass('dashicons-arrow-down-alt2 dashicons-arrow-up-alt2');
        });

        // Question Type Change - Show/Hide Options
        $(document).on('change', '.efs-question-type', function () {
            const type = $(this).val();
            const $optionsRow = $(this).closest('.efs-question-body').find('.efs-options-row');

            if (type === 'mcq' || type === 'checkbox') {
                $optionsRow.show();
            } else {
                $optionsRow.hide();
            }
        });

        // Update question title on text change
        $(document).on('input', 'textarea[name*="[question_text]"]', function () {
            const text = $(this).val();
            const shortText = text.length > 60 ? text.substring(0, 60) + '...' : text;
            $(this).closest('.efs-question-item').find('.efs-question-title').text(shortText || 'New Question');
        });

        // Make questions sortable
        if ($('#efs-questions-container').length) {
            $('#efs-questions-container').sortable({
                handle: '.efs-question-drag',
                placeholder: 'efs-question-placeholder',
                cursor: 'move',
                tolerance: 'pointer',
                update: function () {
                    updateQuestionNumbers();
                }
            });
        }

        // Initialize question index
        questionIndex = $('.efs-question-item').length;
    }

    /**
     * Add a new question
     */
    function addQuestion() {
        const template = $('#efs-question-template').html();
        const newQuestion = template.replace(/__INDEX__/g, questionIndex);

        if ($('.efs-no-questions').length) {
            $('.efs-no-questions').remove();
        }

        $('#efs-questions-container').append(newQuestion);
        const $newItem = $('.efs-question-item').last();

        // Scroll to new question
        $('html, body').animate({
            scrollTop: $newItem.offset().top - 100
        }, 500);

        questionIndex++;
    }

    /**
     * Update question numbers after sorting or deletion
     */
    function updateQuestionNumbers() {
        $('.efs-question-item').each(function (index) {
            $(this).attr('data-index', index);

            // Update all input names
            $(this).find('input, select, textarea').each(function () {
                const name = $(this).attr('name');
                if (name) {
                    const newName = name.replace(/\[\d+\]/, '[' + index + ']');
                    $(this).attr('name', newName);
                }
            });
        });
    }

    /**
     * Initialize Analytics
     */
    function initAnalytics() {
        // Export responses
        $('.efs-export-btn').on('click', function (e) {
            e.preventDefault();
            const formId = $(this).data('form-id');
            const format = $(this).data('format');

            window.location.href = efsAdmin.apiUrl + '/forms/' + formId + '/export?format=' + format;
        });
    }

    /**
     * Template Selection Helper
     */
    window.efsShowTemplateForm = function (templateType) {
        $('.efs-template-form').hide();

        if (templateType === 'naac_all' || templateType.startsWith('naac_')) {
            $('#naac-form-fields').show();
        } else if (templateType === 'nba_course') {
            $('#nba-course-fields').show();
        }
    };

    /**
     * Copy to Clipboard
     */
    $(document).on('click', 'input[readonly]', function () {
        $(this).select();
        try {
            document.execCommand('copy');
            $(this).after('<span class="efs-copied">Copied!</span>');
            setTimeout(function () {
                $('.efs-copied').fadeOut(function () {
                    $(this).remove();
                });
            }, 2000);
        } catch (err) {
            console.error('Failed to copy');
        }
    });

})(jQuery);
