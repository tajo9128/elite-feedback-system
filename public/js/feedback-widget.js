/**
 * Elite Feedback System - Frontend JavaScript
 */

(function ($) {
    'use strict';

    // Initialize when DOM is ready
    $(document).ready(function () {
        initFeedbackForms();
    });

    /**
     * Initialize feedback forms
     */
    function initFeedbackForms() {
        $('.efs-feedback-form form').on('submit', handleFormSubmit);
    }

    /**
     * Handle form submission
     */
    function handleFormSubmit(e) {
        e.preventDefault();

        const $form = $(this);
        const $formContainer = $form.closest('.efs-feedback-form');
        const formId = $formContainer.data('form-id');
        const $submitButton = $form.find('.efs-submit');
        const $message = $formContainer.find('.efs-message');

        // Validate form
        if (!validateForm($form)) {
            showMessage($message, 'Please fill in all required fields.', 'error');
            return;
        }

        // Disable submit button
        const originalText = $submitButton.text();
        $submitButton.prop('disabled', true).html('<span class="efs-loading"></span> Submitting...');

        // Collect form data
        const responses = collectFormData($form, formId);

        // Submit via API
        submitFeedback(formId, responses)
            .done(function (response) {
                showMessage($message, 'Thank you! Your feedback has been submitted successfully.', 'success');
                $form.hide();

                // Scroll to message
                $('html, body').animate({
                    scrollTop: $message.offset().top - 100
                }, 500);
            })
            .fail(function (xhr) {
                const errorMsg = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'An error occurred while submitting your feedback. Please try again.';
                showMessage($message, errorMsg, 'error');
                $submitButton.prop('disabled', false).text(originalText);
            });
    }

    /**
     * Validate form
     */
    function validateForm($form) {
        let isValid = true;

        $form.find('[required]').each(function () {
            const $field = $(this);
            const type = $field.attr('type');
            const name = $field.attr('name');

            if (type === 'radio' || type === 'checkbox') {
                const $group = $form.find('[name="' + name + '"]');
                if (!$group.is(':checked')) {
                    isValid = false;
                    $field.closest('.efs-question').addClass('efs-error-state');
                } else {
                    $field.closest('.efs-question').removeClass('efs-error-state');
                }
            } else {
                if (!$field.val()) {
                    isValid = false;
                    $field.addClass('efs-error-state');
                } else {
                    $field.removeClass('efs-error-state');
                }
            }
        });

        return isValid;
    }

    /**
     * Collect form data
     */
    function collectFormData($form, formId) {
        const responses = [];

        $form.find('.efs-question').each(function () {
            const $question = $(this);
            const questionId = $question.data('question-id');
            const $inputs = $question.find('input, textarea, select');

            if ($inputs.attr('type') === 'radio') {
                const $checked = $inputs.filter(':checked');
                if ($checked.length) {
                    responses.push({
                        question_id: questionId,
                        value: $checked.val()
                    });
                }
            } else if ($inputs.attr('type') === 'checkbox') {
                const values = [];
                $inputs.filter(':checked').each(function () {
                    values.push($(this).val());
                });
                if (values.length) {
                    responses.push({
                        question_id: questionId,
                        value: values.join(', ')
                    });
                }
            } else {
                const value = $inputs.val();
                if (value) {
                    responses.push({
                        question_id: questionId,
                        value: value
                    });
                }
            }
        });

        return responses;
    }

    /**
     * Submit feedback via API
     */
    function submitFeedback(formId, responses) {
        return $.ajax({
            url: efsPublicConfig.apiUrl + '/responses',
            method: 'POST',
            contentType: 'application/json',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', efsPublicConfig.nonce);
            },
            data: JSON.stringify({
                form_id: formId,
                responses: responses
            })
        });
    }

    /**
     * Show message
     */
    function showMessage($element, message, type) {
        $element
            .removeClass('efs-success efs-error')
            .addClass('efs-' + type)
            .html(message)
            .slideDown(300);
    }

})(jQuery);
