// --- DOCUMENT READY ------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------

$(function () {
    $('[data-fr-action="send-job-application"]').on('click', async function () {
        $(this).prop('disabled', true).addClass('button-loading');
        const $body = $(this).closest('form');

        if ($('.failed-realtime-validation', $body).length) {
            $(this).prop('disabled', false).removeClass('button-loading');
            printMessage({ status: 'error', text: 'Some inputs have errors!' });
            return false;
        }

        let captchaResult = await reCaptcha();
        if (!captchaResult) {
            captchaResult = await recaptchaFallBack($(this));
        }
        if (!captchaResult) {
            $(this).prop('disabled', false).removeClass('button-loading');
            printMessage({ status: 'error', text: 'The google captcha has failed, unable to process your request at this time. If you are using VPN please turn it off and try again.' });
            return false;
        }

        //---check data
        const data = gatherData($body);
        if (!data) {
            $(this).prop('disabled', false).removeClass('button-loading');
            return false;
        }

        const requiredFields = [
            '(str@3-100)*client_name',
            '(str@100)*client_email',
            '(str@13)*client_phone',
        ];

        let invalidFields = checkRequiredFields(data, requiredFields);
        if (invalidFields.length) {
            printInvalidFields(invalidFields, $body);
            $(this).prop('disabled', false).removeClass('button-loading');
            return false;
        }

        //---get the attachments
        if (A1.uploaded_files && A1.uploaded_files.length) {
            data['attachments'] = A1.uploaded_files;
        }

        sendEmail($(this), data, 'sendJobApplication').then(res => {
            if (res?.status == 'success') {
                A1.uploaded_files = [];
                renderGatheredFiles($('[data-fr-section="uploded-files"]'));

                $body.find('input').val('').trigger('input');
            }
        }).catch(() => {
            setTimeout(() => {
                $(this).prop('disabled', false).removeClass('button-loading');
            }, 1500)
        });
    });

    //---realtime validations
    $('[data-fr-name0="client_email"]').on('input', function () {
        validateEmailAddressPrompt($(this));
    });
    $('[data-fr-name0="client_phone"]').on('input', function () {
        validatePhoneNumberPrompt($(this));
    });
    $('[data-fr-name0="client_name"]').on('input', function () {
        validateFullNamePrompt($(this));
    });

    //---render uploaded files
    $('[data-fr-action="file-input"]').on('change', function () {
        gatherFiles($(this), $('[data-fr-section="uploded-files"]'));
    })
});