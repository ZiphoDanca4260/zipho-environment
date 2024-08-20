// --- WHEREVER THERE'S A CONTACT FORM -------------------------------------------------------------
// --- THERE'S A CONTROLLER ------------------------------------------------------------------------

$(function () {
    $('[data-fr-action="send-message"]').on('click', async function () {
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
            '(str@4096)*client_message'
        ];

        let invalidFields = checkRequiredFields(data, requiredFields);
        if (invalidFields.length) {
            printInvalidFields(invalidFields, $body);
            $(this).prop('disabled', false).removeClass('button-loading');
            return false;
        }

        sendEmail($(this), data, 'contactUs').then(res => {
            if (res?.status == 'success') {
                $body.find('input, textarea').val('').trigger('input');
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
    $('[data-fr-name0="client_name"], [data-fr-name0="client_surname"]').each(function () {
        $(this).on('input', function () {
            validateFullNamePrompt($(this));
        });
    });

    //---if vehicle is passed, set up the inputs.
    if (Params.hasOwnProperty('vehicle_primo_id')) {
        const vehicle_primo_id = Params['vehicle_primo_id'];
        const vehicle = A1.vehicles.find(v => v.vehicle_primo_id == vehicle_primo_id);
        if (!vehicle) {
            printMessage({ status: 'info', text: 'Hmm.. The requested vehicle could not be found, please enter the information manually to the inputs if necessary.' });
            return false;
        }

        $('[data-fr-name0="client_message"]').val(`Good day!\nI would like to learn more about the listing ${vehicle_primo_id} - ${vehicle.vehicle_friendly_name}.`);
        $('[data-fr-name0="sales_enquiry"]').prop('checked', true)
    }
});