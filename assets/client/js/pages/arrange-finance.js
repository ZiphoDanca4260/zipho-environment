// --- MISC ----------------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------

const debouncedRenderFinanceResults = debounce(() => {
    const table = $('[data-fr-output="results-table"]');

    var calc = A1.finance_calculator;
    var total_loan = calc.price + calc.fees;
    var monthly_interest = (calc.interest / 100) / 12;
    var one_plus_r_n = "(1&nbsp;+&nbsp;" + monthly_interest.toFixed(4) + ")<sup>" + calc.period + '</sup>';
    var subtract = one_plus_r_n + "&nbsp;-&nbsp;1";
    A1.finance_calculator.repayment_amount = total_loan * ((monthly_interest * Math.pow((1 + monthly_interest), calc.period)) / (Math.pow((1 + monthly_interest), calc.period) - 1));

    $('[data-fr-output="loan-total"]').html(total_loan.toFixed(2));
    $('[data-fr-output="interest-rate"]').html(monthly_interest.toFixed(4));
    $('[data-fr-output="compounded-interest"]').html(one_plus_r_n);
    $('[data-fr-output="compounded-interest-subtraction"]').html(subtract);
    $('[data-fr-output="total"]').html(parseFloat(calc.repayment_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
}, 500)

//---function to validate input for only positive numbers and one decimal point.
const isNumeric = (n) => {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

// --- DOCUMENT READY ------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------

$(function () {
    //---set up calculation object
    if (!A1.finance_calculator) {
        A1.finance_calculator = {
            price: 120000,
            fees: 9550,
            interest: 12.5,
            period: 24,
            repayment_amount: 0
        };
    }

    //---place values in inputs
    $('[data-fr-input="price-input"]').val('R' + A1.finance_calculator.price.toLocaleString('en-US', { maximumFractionDigits: 2 }));
    $('[data-fr-select="period-select"]').val(A1.finance_calculator.period);

    //---price input validation
    $('[data-fr-input="price-input"]').on("keypress", function (e) {
        // Allow only numbers and dots
        if (!(e.which >= 48 && e.which <= 57 || e.which == 46)) {
            e.preventDefault();
        }

        // Allow a single dot
        var parts = this.value.split(".");
        if ((parts.length > 1 && e.which == 46) || (parts.length >= 2 && parts[1].length >= 2)) {
            e.preventDefault();
        }
    }).on("input", function (e) {
        var newValue = this.value.replace(/[^0-9.]/g, ''); // Keep only numbers and dot
        newValue = newValue.replace(/^0+/g, ''); // Remove any leading zeros
        var parts = newValue.split(".");

        // Format the whole part of number, keep the decimals as is
        newValue = parseFloat(parts[0]).toLocaleString('en-US', { maximumFractionDigits: 0 });

        // If there are decimals, append to the newValue
        if (parts.length > 1) {
            newValue += '.' + parts[1];
        }

        if (parts[1] && parts[1].length > 2) {
            newValue = newValue.slice(0, -1);
        }

        // Add 'R' if it doesn't exist
        this.value = newValue.startsWith("R") ? newValue : 'R' + newValue;

        // If the value is RNaN, change it to R0.
        if (this.value == 'RNaN') {
            this.value = 'R0'
        }

        var priceStr = this.value.replace(/[^0-9.]/g, ''); //Remove non-numeric characters
        A1.finance_calculator.price = parseFloat(priceStr); //Convert to float and assign to your price
        debouncedRenderFinanceResults(); //---render
    }).on("paste", function (e) {
        var pastedData = e.originalEvent.clipboardData.getData('text');
        // If the pasted data doesn't match format, prevent paste
        if (/^R?\d{1,3}(,\d{3})*(\.\d{0,2})?$/g.test(pastedData) === false) {
            e.preventDefault();
        } else {
            e.preventDefault(); // prevent default action
            // Remove 'R' and ',' then convert this value to a float
            var cleanPaste = pastedData.replace(/R|,/g, '');
            var formattedPaste = parseFloat(cleanPaste).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            // Add 'R' if it doesn't exist
            this.value = formattedPaste.startsWith("R") ? formattedPaste : 'R' + formattedPaste;
            // Update A1.finance_calculator.price
            A1.finance_calculator.price = parseFloat(cleanPaste); // Convert cleaned paste to float and assign to your price
            debouncedRenderFinanceResults();
        }
    });

    //---select on change event
    $('[data-fr-select="period-select"]').on('change', function () {
        A1.finance_calculator.period = Number($(this).val());
        debouncedRenderFinanceResults();
    })
    //---render the default finance calculator results
    debouncedRenderFinanceResults();

    //---finance contact(send-email) button
    $('[data-fr-action="contact-finance"]').on('click', async function () {
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
            '(str@150)*car_name',
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

        sendEmail($(this), data, 'contactFinance').then(res => {
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

    //---if vehicle is passed, set up the inputs.
    if (Params.hasOwnProperty('vehicle_primo_id')) {
        const vehicle_primo_id = Params['vehicle_primo_id'];
        const vehicle = A1.vehicles.find(v => v.vehicle_primo_id == vehicle_primo_id);
        if (!vehicle) {
            printMessage({ status: 'info', text: 'Hmm.. The requested vehicle could not be found, please enter the information manually to the inputs if necessary.' });
            return false;
        }

        $('[data-fr-name0="car_name"]').val(vehicle_primo_id + ' - ' + vehicle.vehicle_friendly_name);
        $('[data-fr-input="price-input"]').val(vehicle.vehicle_price).trigger('input');
    }
});