// --- VALIDATIONS AND INPUT PROMPTS ---------------------------------------------------------------
// --- INPUT PROMPTS ETC. --------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------


const validateEmailAddressPrompt = debounce(($input) => {
    const email = $input.val();

    if (!email) {
        clearUIMessages($input.closest('.form_boxes'), false);
        return;
    }

    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const result = regex.test(email);
    if (!result) {
        $input.addClass('failed-realtime-validation');
        printInputFeedback($input, { status: 'error', text: 'The email is invalid!' });
    } else {
        printInputFeedback($input, { status: 'success' });
        clearUIMessages($input.closest('.form_boxes'));
    }
}, 500);

const validatePhoneNumberPrompt = debounce(($input) => {
    let phone_number = $input.val();

    if (!/^[0-9-+() ]*$/.test(phone_number)) {
        printInputFeedback($input, { status: 'error', text: 'Phone number should not contain special chars.' });
        return;
    }

    phone_number = phone_number.replace(/\D/g, '');
    if (!phone_number) {
        clearUIMessages($input.closest('.form_boxes'), false);
        return;
    }

    const regex = /^\d{9,12}$/;
    let result = regex.test(phone_number);
    if (!result) {
        $input.addClass('failed-realtime-validation');
        printInputFeedback($input, { status: 'error', text: 'Please enter a valid phone number!' });
    } else {
        clearUIMessages($input.closest('.form_boxes'));
        printInputFeedback($input, { status: 'success' });
    }
}, 500);

const validateFullNamePrompt = debounce(($input) => {
    const name = $input.val();

    if (!name) {
        clearUIMessages($input.closest('.form_boxes'), false);
        return;
    }

    const regex = /^(?=.*[a-zA-Z].*)([^<>@#&$:;{}!%^*().?_\/\\+=0-9]{3,})$/;
    const result = regex.test(name);
    if (!result) {
        $input.addClass('failed-realtime-validation');
        printInputFeedback($input, { status: 'error', text: 'The name is invalid! Use only letters and at least 3 characters' });
    } else {
        printInputFeedback($input, { status: 'success' });
        clearUIMessages($input.closest('.form_boxes'));
    }
}, 500);


// --- RECAPTCHA -----------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------


const reCaptcha = () => {
    return new Promise((resolve, reject) => {
        grecaptcha.ready(() => {
            grecaptcha.execute(A1.public_settings.google.recaptchaV3, { action: 'submit' }).then((token) => {
                $.ajax({
                    type: "POST",
                    url: A1.apiDomain + "/api/auth",
                    data: "task=verifyCaptcha" +
                        "&__token=" + A1.__token +
                        "&data=" + encodeURIComponent(JSON.stringify({ token, captchaV: "V3" })),
                    dataType: "json",
                    success: function (msg) {
                        if (A1.obj) $(A1.obj).removeClass('button-loading').prop('disabled', false)
                        if (msg.status == 'success') {
                            resolve(true);
                        } else {
                            resolve(false);
                        }
                        printMessage(msg)
                    },
                    error: function () {
                        if (A1.obj) $(A1.obj).removeClass('button-loading').prop('disabled', false)
                        resolve(false);
                    }
                });
            }).catch((error) => {
                printMessage({ status: "error", text: `reCAPTCHA Error: ${error}` })
                resolve(false);
            });
        });
    });
};

const recaptchaFallBack = (button) => {
    return new Promise((resolve, reject) => {
        const popoverContent = `
            <div id="recaptcha-v2-container" style="width: 304px; height: 78px;"></div>
        `;

        button.popover({
            container: 'body',
            html: true,
            content: popoverContent,
            sanitize: false,
            trigger: 'manual'
        });

        button.popover('show');
        button.on('hidden.bs.popover', function () {
            button.popover('dispose');
        });

        //---update the reCAPTCHA container contents when the popover is shown
        button.on('shown.bs.popover', function () {
            $('.popover').css('max-width', '340px');

            if ($('#recaptcha-v2-container').children().length === 0) {
                grecaptcha.render('recaptcha-v2-container', {
                    sitekey: A1.public_settings.google.recaptchaV2,
                    callback: (token) => {
                        $.ajax({
                            type: "POST",
                            url: A1.apiDomain + "/api/auth",
                            data: "task=verifyCaptcha" +
                                "&__token=" + A1.__token +
                                "&data=" + encodeURIComponent(JSON.stringify({ token, captchaV: "V2" })),
                            dataType: "json",
                            success: function (msg) {
                                if (msg.status === 'success') {
                                    resolve(true);
                                } else {
                                    resolve(false);
                                }
                                printMessage(msg);
                            },
                            error: function () {
                                resolve(false);
                            },
                            complete: function () {
                                button.popover('hide');
                            }
                        });
                    },
                    'expired-callback': () => {
                        printMessage({ 'status': 'false', 'text': 'reCAPTCHA expired' });
                        resolve(false);
                    },
                });
            }
        });
    });
};


// --- MISC ----------------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------


const sendEmail = async ($button, data, task) => {
    if (!await confirmAction(task)) {
        $button.prop('disabled', false).removeClass('button-loading');
        return false;
    }

    return new Promise(async (resolve, reject) => {
        if (!task) {
            reject({ status: 'error', text: 'invalid action!' });
        }

        if ($button) {
            $button.attr('disabled', true).addClass('button-loading');
        }

        try {
            await ajaxPost({
                payload: data, endPoint: 'api/mailer', initiatorElement: $button, task: task,
                customFunctions: {
                    success: function (result) {
                        resolve(result);
                    }
                }
            });
        } catch (error) {
            reject();
        }
    })
}


// --- DOCUMENT READY ------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------


$(function () {
    //---nothing yet
});