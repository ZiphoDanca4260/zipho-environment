// --- DOCUMENT READY ------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------

$(function () {
    $('[data-fr-action="evaluate-my-car"]').on('click', async function () {
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
            '(str@3-100)*client/client_name',
            '(str@100)*client/client_email',
            '(str@13)*client/client_phone',
            '(str@50)*car/make',
            '(str@100)*car/model',
            '(int@4)*car/year',
            '(str@50)*car/transmission',
            '(str@30)*car/mileage',
            '(str@30)*car/color'
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

        sendEmail($(this), data, 'evaluateMyCar').then(res => {
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

    //---autocomplete for inputs
    $('[data-fr-name0="make"]').on('input', debounce(function () {
        let $datalist = $('#autocomplete_makes').empty();
        const val = $(this).val();
        if (val.length < 1) {
            return false;
        }

        const results = minisearch.search(val);
        if (!results.length) {
            return false;
        }

        let unique_makes = [];
        results.forEach(search_result => {
            const vehicle = A1.vehicles.find(v => v.vehicle_primo_id === search_result.id)
            if (!unique_makes.includes(vehicle.vehicle_make)) {
                unique_makes.push(vehicle.vehicle_make)
            }
        });

        unique_makes.slice(0, 10).forEach(make => {
            $('<option/>', { value: make }).appendTo($datalist);
        });
    }, 200));
    $('[data-fr-name0="model"]').on('input', debounce(function () {
        let $datalist = $('#autocomplete_models').empty();
        const val = $(this).val();
        if (val.length < 1) {
            return false;
        }

        const results = minisearch.search(val);
        if (!results.length) {
            return false;
        }

        let unique_models = [];
        results.forEach(search_result => {
            const vehicle = A1.vehicles.find(v => v.vehicle_primo_id === search_result.id)
            if (!unique_models.includes(vehicle.vehicle_model)) {
                unique_models.push(vehicle.vehicle_model)
            }
        });

        unique_models.slice(0, 10).forEach(model => {
            $('<option/>', { value: model }).appendTo($datalist);
        });
    }, 200));

    //---render uploaded files
    $('[data-fr-action="file-input"]').on('change', function () {
        gatherFiles($(this), $('[data-fr-section="uploded-files"]'));
    });
});