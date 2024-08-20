/**
 * 
 * This file includes functions that are used across
 * all pages of the website.
 * 
 * 
 * ## DEPENDED FILES:
 * - templates/default/assets/alert-modal.php
 * - assets/default/css/default.css
 * 
 * 
 * ## DEPENDED LIBRARIES:
 * - jquery 3.7
 * - bootstrap 5.3
 * - toaster
 * - sweetalert v2
 * 
 */

// --- GET PARAMS AND SET DOMAIN NAME
let Params;
(window.onpopstate = function () {
    var match,
        pl = /\+/g,  // Regex for replacing addition symbol with a space
        search = /([^&=]+)=?([^&]*)/g,
        decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
        query = window.location.search.substring(1);

    Params = {};
    while (match = search.exec(query))
        Params[decode(match[1])] = decode(match[2]);
})();
document.addEventListener("DOMContentLoaded", function () {
    if (A1.domain.includes('sandbox.')) {
        A1.apiDomain = A1.domain.replace('://www.sandbox.', '://api-sandbox.');
        if (!A1.apiDomain.includes('://api-sandbox.')) A1.apiDomain = A1.domain.replace('://sandbox.', '://api-sandbox.');
    } else {
        A1.apiDomain = A1.domain.replace('://www.', '://api.');
        if (!A1.apiDomain.includes("://api.")) A1.apiDomain = A1.apiDomain.replace('://', '://api.');
    }

    A1.wssDomain = A1.domain.replace('://www.', '://wss.');
    if (!A1.wssDomain.includes("://wss.")) A1.wssDomain = A1.wssDomain.replace('://', '://wss.');
});

//---gather all query to pass api request 
let fullParam = Object.entries(Params).map(([key, val]) => `${key}=${val}`).join('&');


// --- WEBSOCKET -----------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------


// $(() => {
//     const access_token = A1.jwt;
//     let socket;

//     const config = {
//         "reconnection": true,
//         "reconnectionDelay": 1000,
//         "reconnectionDelayMax": 3000,
//         "reconnectionAttempts": 3
//     }

//     if (!access_token) {
//         socket = io(A1.wssDomain, {
//             ...config
//         });
//     } else {
//         socket = io(A1.wssDomain, {
//             query: {
//                 token: access_token
//             },
//             ...config
//         });
//     }

//     let connection_attempts = config.reconnectionAttempts;

//     //---connection handler
//     socket.on('connect', function () {
//         console.log('connected');
//     });

//     socket.on('connect_error', function () {
//         if (connection_attempts === config.reconnectionAttempts) {
//             printMessage({ status: 'error', text: 'Websocket failed to connect, retrying...' });
//         }

//         if (connection_attempts <= 0) {
//             printMessage({ status: 'error', text: 'Websocket could not reconnect. Some functionalities may be disabled.' })
//             socket.off('connect_error');
//         }

//         --connection_attempts;
//     });

//     socket.on('disconnect', function () {
//         console.log('disconnect');
//     });
// });


// --- AUTHENTICATION ------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------


const logIn = (obj) => {
    let body = $(obj).closest('[data-fr-section="login-form"]');
    let json = {
        task: "login",
        endPoint: "api/auth",
        initiatorElement: obj,
        payload: {
            fields: gatherData(body)
        }
    }

    ajaxPost(json);
};

const OTPConfirm = (obj) => {

};

const logOut = (obj) => {

};


// --- GATHER DATA CHAIN & HELPERS -----------------------------------------------------------------
// -------------------------------------------------------------------------------------------------


const gatherData = ($targetDiv) => {
    clearUIMessages();

    let json = {};

    function setNestedObjectValue(json, path, value) {
        path.reduce((result, key, i) => {
            if (i === path.length - 1) {
                result[key] = value;   //--on the last key assign the value
            } else {
                result[key] = result[key] || {};
            }
            return result[key];
        }, json);
    }

    function getObjectPath($el) {
        let i = 0;
        let path = [];
        let attr;

        while ((attr = $el.attr(`data-fr-name${i}`)) !== undefined) {
            path.unshift(attr);
            i++;
        }

        return path;
    }

    let critical_error = false;
    $('[data-fr-name0]', $targetDiv).each(function () {
        let $el = $(this);
        let type = $el.attr('type');
        let path = getObjectPath($el);
        if (!path.length) return true;
        let value, skip = false;
        switch (type) {
            case 'html':
                value = $el.html();
                break;
            case 'array':
                skip = true;
                break;
            case 'checkbox':
                value = $el.prop('checked') ? 1 : 0;
                break;
            case 'radio':
                value = $el.prop('checked');
                break;
            default:
                value = $el.val();
        }

        if (!skip && hasError($el, value, path)) {
            critical_error = true;
            return false;
        }

        if (!skip) {
            value = compress(value);
            setNestedObjectValue(json, path, value);
        }
    });

    if (critical_error) {
        return false;
    }

    return json;
};

const hasError = ($el, val, path) => {
    let keys = path.filter(Boolean);
    let lastKey = keys.pop();

    let script = $el.attr('data-fr-script');
    let required = $el.attr('required') !== undefined;
    let errField = ucwords(path[path.length - 1].replace('_', ' ')) || $el.attr('placeholder') || $el.attr('data-placeholder') || ucwords(lastKey.replace(/_/g, ' ')) || '--unknown_field--';

    if (required && (!val && val !== 0)) {
        printMessage({ text: `Data cannot be blank for ${errField}!` });
        $el.parent().addClass('has-error');
        printInputFeedback($el, { status: 'error', text: 'This input can not be blank!' });
        if (script) eval(script);
        return true;
    }

    return false;
};

const compress = (str) => {
    if (str === null || typeof str === 'undefined') return '';
    if (typeof str === 'boolean' || typeof str === 'number') str = str.toString();
    return str.replace(/\s+/g, ' ').replace(/`/g, "'");
};

const debounce = (func, delay) => {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            func.apply(this, args);
        }, delay);
    };
};

const debouncePromise = (func, wait) => {
    let timeout;
    return function (...args) {
        return new Promise(resolve => {
            if (timeout) {
                clearTimeout(timeout);
            }
            timeout = setTimeout(() => {
                resolve(func(...args));
            }, wait);
        });
    };
};

const gatherFiles = ($input, $targetDiv) => { //---migrate to other projects //TODO:
    if (!A1.uploaded_files) {
        A1.uploaded_files = [];
    }

    if (!$input || !$input.length) {
        return false;
    }

    const files = $input[0].files;
    let readersPromises = [];
    for (let file of files) {
        if (A1.uploaded_files.find(f => f.name === file.name)) {
            printMessage({ status: 'info', text: `The file: ${file.name} submitted is already in the uploaded files list.` });
            continue;
        }

        let reader = new FileReader();
        readersPromises.push(new Promise((resolve, reject) => {
            reader.onload = function (e) {
                if (file.size > 6 * 1024 * 1024) {
                    printMessage({ status: 'error', text: 'Max allowed size for a file is 6Mb.' });
                    reject('Max allowed size for a file is 6Mb.');
                }

                if (!file.type.match(/(audio\/.*|video\/.*|application\/pdf|application\/vnd\.openxmlformats-officedocument\.wordprocessingml\.document|application\/vnd.ms-excel|application\/msword|application\/vnd\.openxmlformats-officedocument\.spreadsheetml\.sheet|image\/.*|text\/.*)/)) {
                    printMessage({ status: 'error', text: `The provided file format: ${file.type} is not supported.` });
                    reject("File type is not allowed.");
                }

                if (file.type.startsWith('audio')) {
                    let audio = document.createElement('audio');
                    audio.src = e.target.result;
                    audio.controls = true;
                    resolve({
                        name: file.name,
                        type: file.type,
                        size: file.size,
                        element: audio,
                        data: e.target.result,
                    });
                }
                else if (file.type.startsWith('video')) {
                    let video = document.createElement('video');
                    video.src = e.target.result;
                    video.controls = true;
                    resolve({
                        name: file.name,
                        type: file.type,
                        size: file.size,
                        element: video,
                        data: e.target.result,
                    });
                } else {
                    resolve({
                        name: file.name,
                        size: file.size,
                        type: file.type,
                        data: e.target.result,
                    });
                }
            };

            reader.onerror = () => reject(reader.error);
            reader.readAsDataURL(file);
        }));
    }

    Promise.all(readersPromises)
        .then(resolvedFiles => {
            resolvedFiles.forEach(file => A1.uploaded_files.push(file));
            if ($targetDiv && $targetDiv.length) {
                renderGatheredFiles($targetDiv);
            }
        }).catch(error => console.error('FileReader error:', error));
};

const renderGatheredFiles = ($targetDiv) => {

    if (!$targetDiv || !$targetDiv.length) {
        return false;
    }

    $targetDiv.empty();
    A1.uploaded_files.forEach((file) => {
        let $card = $('<div>', {
            class: 'd-flex flex-column form_boxes p-3 border border-1 rounded-3 justify-content-around align-items-center',
            style: 'width: 250px;'
        });
        let $previewBox = $('<div>', { class: 'd-flex flex-column gap-3 justify-content-center align-items-center w-100 flex-grow-1 align-self-stretch object-fit-contain', style: 'max-height: 200px;' }).appendTo($card);

        switch (file.type) {
            case 'text/plain':
                $('<i>', { class: 'fa-solid fa-file-lines mb-3 mt-2', style: "font-size: 50px;" }).appendTo($previewBox);
                break;
            case 'application/pdf':
                $('<i>', { class: 'fa-solid fa-file-pdf mb-3 mt-2', style: "font-size: 50px;" }).appendTo($previewBox);
                break;
            case 'application/msword':
                $('<i>', { class: 'fa-solid fa-file-word mb-3 mt-2', style: "font-size: 50px;" }).appendTo($previewBox);
                break;
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
            case 'application/vnd.ms-excel':
                $('<i>', { class: 'fa-solid fa-file-excel mb-3 mt-2', style: "font-size: 50px;" }).appendTo($previewBox);
                break;
            case 'application/vnd.ms-powerpoint':
                $('<i>', { class: 'fa-solid fa-file-powerpoint mb-3 mt-2', style: "font-size: 50px;" }).appendTo($previewBox);
                break;
            case 'image/jpeg':
            case 'image/png':
            case 'image/gif':
                $('<img>', { src: file.data, class: 'img-fluid h-100' }).appendTo($previewBox);
                break;
            case 'audio/mpeg':
            case 'audio/wav':
                $(file.element).addClass('img-fluid h-100');
                $previewBox.append('<i class="fa-solid fa-file-audio" style="font-size: 50px;"></i>')
                $previewBox.append(file.element);
                break;
            case 'video/mp4':
                $(file.element).addClass('img-fluid');
                $previewBox.append(file.element);
                break;
            default:
                $('<i>', { class: 'fa-solid fa-file mb-3 mt-2', style: "font-size: 50px;" }).appendTo($previewBox);
                break;
        }

        $('<span>', { class: 'text-break mt-auto mb-2', text: file.name }).appendTo($card);

        let $row = $('<div>', { class: 'd-flex flex-row gap-2 align-self-stretch' }).appendTo($card);
        $("<a>", {
            href: file.data,
            download: file.name,
            html: '<i class="fa-solid fa-eye"></i>',
            target: '_blank',
            class: 'btn btn-primary btn-sm flex-grow-1 py-1'
        }).appendTo($row);

        $('<button>', {
            class: 'btn btn-danger btn-sm flex-grow-1 py-1',
            html: '<i class="fa-solid fa-trash-can text-light"></i>'
        }).appendTo($row).on('click', function () {
            A1.uploaded_files = A1.uploaded_files.filter(f => f.name != file.name);
            renderGatheredFiles($targetDiv);
        });

        $targetDiv.append($card);
    });
};


// --- AJAX REQUESTS AND ERROR HANDLING ------------------------------------------------------------
// -------------------------------------------------------------------------------------------------


const ajaxPost = async (options = {}) => {
    if ($('.has-error').length) return false
    const defaultOptions = {
        endPoint: '',
        url: A1.domain + '/',
        payload: {},
        initiatorElement: null,
        task: ''
    };
    options = {
        ...defaultOptions,
        ...options,
        customFunctions: {
            beforeSend: (typeof (options.customFunctions && options.customFunctions.beforeSend) === 'function')
                ? options.customFunctions.beforeSend
                : function () { },
            success: (typeof (options.customFunctions && options.customFunctions.success) === 'function')
                ? options.customFunctions.success
                : function () { },
            error: (typeof (options.customFunctions && options.customFunctions.error) === 'function')
                ? options.customFunctions.error
                : function () { },
            complete: (typeof (options.customFunctions && options.customFunctions.complete) === 'function')
                ? options.customFunctions.complete
                : function () { },
        }
    };

    try {
        return $.ajax({
            type: "POST",
            url: options.url + options.endPoint + '?' + fullParam,
            data: {
                '__token': A1.__token,
                'jwt': A1.jwt,
                'data': JSON.stringify(options.payload),
                'task': options.task
            },
            dataType: 'json',
            beforeSend: function () {
                if (options.initiatorElement) {
                    options.initiatorElement.addClass('button-loading position-relative').prop('disabled', true);
                }
                options.customFunctions.beforeSend();
            },
            success: function (msg) {
                if (msg.scripts) {
                    $.each(msg.scripts, function (script) {
                        eval(script);
                    });
                }
                printMessage(msg);
                options.customFunctions.success(msg);
            },
            error: function (jqXHR) {
                errors(jqXHR, this);
                options.customFunctions.error(jqXHR);
            },
            complete: function () {
                if (options.initiatorElement) {
                    options.initiatorElement.removeClass('button-loading').prop('disabled', false);
                }
                options.customFunctions.complete();
            }
        });
    } catch (e) {
        console.error('An error occurred: ', e);
    }
};

const errors = function (jqXHR, that) {
    printMessage({ text: "Error Occured!" });

    let json = {};
    if (!jqXHR.status) {
        return false;
    }

    if (jqXHR.status) {
        json.status = jqXHR.status;
    }

    if (jqXHR.responseText) {
        json.response = jqXHR.responseText;
    }

    if (jqXHR.text) {
        json.response = jqXHR.text;
    }

    if (typeof that === 'undefined') that = null;

    json.referer = document.location.href;
    json.url = that?.url ?? document.location.href;
    json.type = that?.type ?? '';

    json.response = json.response.replace(/\n/g, '<br>').replace(/\s/g, '&nbsp;');
    json.user = { user_name: A1.user.user_name, user_group_id: A1.user.user_group_id };

    json.data = that?.data ? urlParams(that.data) : '';
    let url = A1.apiDomain + "/api/error";
    url += (Object.keys(Params).length) ? "?" + fullParam : "";

    // $.ajax({
    //     type: "POST",
    //     url,
    //     data: "jwt=" + A1.jwt + "&__token=" + A1.__token + "&task=send" +
    //         "&data=" + encodeURIComponent(JSON.stringify(json)),
    //     dataType: "json",
    //     success: function (msg) {
    //         printMessage(msg);
    //         if (msg.scripts) {
    //             $.each(msg.scripts, function (index, script) {
    //                 eval(script);
    //             })
    //         }
    //     }
    // });
};

const urlParams = (query) => {
    let params = {};
    const additionSymbolRegex = /\+/g;  //---regex for replacing addition symbol with a space
    const paramRegex = /([^&=]+)=?([^&]*)/g;

    const decode = (str) => decodeURIComponent(str.replace(additionSymbolRegex, ' '));

    let match;
    while (match = paramRegex.exec(query)) {
        const key = decode(match[1]);
        let value = decode(match[2]);

        try {
            value = JSON.parse(value);
        } catch (e) {
            // Do nothing, continue with value as a string
        }

        if (key in params) {
            if (!Array.isArray(params[key])) {
                params[key] = [params[key]];
            }
            params[key].push(value);
        } else {
            params[key] = value;
        }
    }

    return params;
};


// --- DATA & STORAGE MANIPULATION -----------------------------------------------------------------
// -------------------------------------------------------------------------------------------------


const cookieSet = (cookie, task = false) => {
    var now = new Date();
    var time = now.getTime();
    var expireTime;
    expireTime = time + 1000;
    if (!task) expireTime = time + 1000 * 36000;
    now.setTime(expireTime);
    document.cookie = `${cookie};expire=${now.toUTCString()};path=/`;
};

// --- UI MESSAGES AND ALERTS ----------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------


const printMessage = (obj = { status, text }) => {
    if (!obj) return false;
    if (!obj.text) return false;

    if (!obj.method) obj.method = "toastr";
    if (!obj.status) obj.status = "error";

    if (obj.method == 'toastr') {
        toastr.options = {
            closeButton: true,
            newestOnTop: false,
            progressBar: true,
            preventDuplicates: false,
            showDuration: "300",
            hideDuration: "1000",
            timeOut: "10000",
            extendedTimeOut: "200000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut"
        }
        toastr[obj.status](obj.text);
    } else if (obj.method == 'modal') {
        if (obj.status == 'error') {
            obj.class = "modal-header alert alert-danger";
        } else {
            obj.class = "modal-header alert alert-" + obj.status;
        }
        let body = $('#alert-modal').modal('show');
        $('.modal-header', body).removeClass().addClass(obj.class);
        $('.modal-title', body).html(lang['text'][obj.status]);
        $('.modal-body', body).html(obj.text)
    }
};

const printAlert = ($targetDiv, alert = { status, text }) => {
    //---set default options
    const defaultAlert = {
        status: 'warning',
        text: 'Something is not right...'
    };
    alertOptions = Object.assign({}, defaultAlert, alert);

    //---if alert with same message exists in targetDiv remove it.
    $targetDiv.find('.alert').each(function () {
        if ($(this).text().trim() === alertOptions.text) {
            $(this).remove();
        }
    });

    //---build alert
    let alertHtml = `
        <div class="alert alert-${alertOptions.status} alert-dismissible fade" role="alert">
            ${alertOptions.text}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    //---render to DOM
    let $alert = $(alertHtml).appendTo($targetDiv);
    $alert.addClass('show').hide().fadeIn(500);
};

const printInputFeedback = ($targetInput, alert = { status, text }) => {
    //---check the alert type
    alert.status = (alert.status === 'success') ? 'success' : 'error';

    //---set classes
    let classes = [
        (alert.status == 'error') ? 'is-invalid' : 'is-valid',
        (alert.status == 'error') ? 'invalid-feedback' : 'valid-feedback'
    ];
    let oppositeClasses = [
        (alert.status == 'error') ? 'is-valid' : 'is-invalid',
        (alert.status == 'error') ? 'valid-feedback' : 'invalid-feedback'
    ];

    //---remove and add classes
    $targetInput.removeClass(oppositeClasses[0]).addClass(classes[0]);

    //---determine where to render the feedback div
    let $renderAfter = $targetInput;

    //---if target is inside form-floating then set class on that too.
    if ($targetInput.closest('.form-floating').length) {
        let $floatingWrapper = $targetInput.closest('.form-floating');
        $floatingWrapper.removeClass(oppositeClasses[0]).addClass(classes[0]);
        $renderAfter = $floatingWrapper;
    }

    //---if target is inside input-group then set class on that too.
    if ($targetInput.closest('.input-group').length) {
        let $inputGroup = $targetInput.closest('.input-group');
        $inputGroup.removeClass(oppositeClasses[0]).addClass(classes[0]);
        $renderAfter = $inputGroup;
    }

    //---check if message exists
    if (!alert.text) {
        //remove existing feedback div
        $renderAfter.parent().find('.' + classes[1]).remove();
        return;
    }

    //---check if feedback div already exists
    let $existingFeedbackDiv = $renderAfter.parent().find('.' + classes[1]);
    if ($existingFeedbackDiv.length > 0) {
        //if feedback exists and message is the same, return
        if ($existingFeedbackDiv.html() === alert.text) {
            return;
        } else { //if message is different, update the content
            $existingFeedbackDiv.html(alert.text);
            return;
        }
    }

    //---if feedback doesn't exist, create it
    $('<div/>', {
        class: classes[1],
        html: alert.text
    }).insertAfter($renderAfter);
};

const printInvalidFields = (invalidFields, $targetDiv) => {

    invalidFields.forEach(fieldGroup => {
        if (Array.isArray(fieldGroup)) {
            const message = "Either " + fieldGroup.map(field => field.field).join(' or ') + " is required!";

            fieldGroup.forEach(singleField => {
                let $invalidField = $(`[data-fr-name0="${singleField.field}"]`, $targetDiv);
                const individualErrorMessage = `${message} and ${singleField.field} failed because: ${singleField.message}`;
                printInputFeedback($invalidField, { status: 'error', text: individualErrorMessage });
            });

            const combinedMessage = message + ', ' + fieldGroup.map(field => `Field "${field.field}" failed because: ${field.message}`).join(' and ');
            printMessage({ status: 'error', text: combinedMessage });
            return;
        }

        let $invalidField = $(`[data-fr-name0="${fieldGroup.field}"]`, $targetDiv);
        const individualErrorMessage = `Field "${fieldGroup.field}" failed because: ${fieldGroup.message}`;
        printInputFeedback($invalidField, { status: 'error', text: individualErrorMessage });
        printMessage({ status: 'error', text: individualErrorMessage });
    });
};

const clearUIMessages = ($targetDiv = null, errorsOnly = true) => {
    //---if $target is not provided, operate on entire document, else only within $target.
    let $root = $targetDiv || $(document);

    //---clear alert and feedback divs
    if (errorsOnly) {
        $root.find('.alert.alert-danger, .invalid-feedback').remove();
    } else {
        $root.find('.alert').remove();
        $root.find('.invalid-feedback, .valid-feedback').remove();
    }

    //---clear classes
    let classesToRemove = errorsOnly
        ? ['alert', 'is-invalid', 'has-error', 'failed-realtime-validation']
        : ['alert', 'is-valid', 'is-invalid', 'has-error', 'failed-realtime-validation'];

    classesToRemove.forEach(className => {
        let elements = $root.find('.' + className);
        elements.removeClass(className);

        if ($root.hasClass(className)) {
            $root.removeClass(className);
        }
    });
};

/**
 * 
 * @param {string} task - The API task key in lang['confirmActions'].
 * @returns {boolean} - Resolves with the user input {true} or {false}.
 */
const confirmAction = async (task = 'default', icon) => {
    if (!lang['confirmActions'][task]) lang['confirmActions'][task] = `Are you sure you want to continue`;
    var taskText = lang['confirmActions'][task];
    return Swal.fire({
        title: taskText,
        icon: icon ?? 'info',
        showCancelButton: (icon === 'success') ? false : true,
        confirmButtonText: 'Yes!',
        reverseButtons: true
    }).then(result => result.isConfirmed);
};

/**
 * @param {string} task - The API task key in lang['confirmActions'].
 * @param {string} inputType - Input type: 'select', 'text', or 'date'.
 * @param {Object|string} inputOptions - For 'select': {options{}, placeholder}; for 'text': the placeholder string; not needed for 'date'.
 * @param {Boolean} required - If input is strictly required.
 * @returns {Promise} - Resolves with input value if confirmed, true if not required and no input, or false if cancelled.
 */
const confirmActionInput = async (task = 'default', inputType = "select", inputOptions, required = false) => {
    if (!lang['ConfirmAction'][task]) lang['ConfirmAction'][task] = `Are you sure you want to continue?`

    var inputAttributes = {
        inputValidator: (value) => {
            value = value.trim();
            if (required && !value) {
                return 'You must provide a value for this field';
            }
        }
    }

    switch (inputType) {
        case 'select':
            inputAttributes.input = 'select';
            inputAttributes.inputOptions = inputOptions.options || {};
            inputAttributes.inputPlaceholder = inputOptions.placeholder || 'Select an option';
            break;
        case 'text':
            inputAttributes.input = 'text';
            inputAttributes.inputPlaceholder = inputOptions || 'Enter text';
            break;
        case 'date':
            inputAttributes.input = 'date';
            break;
        default:
            throw new Error(`Invalid inputType: ${inputType}`);
    }

    //---show dialog
    return Swal.fire({
        title: lang['ConfirmAction'][task],
        icon: 'info',
        ...inputAttributes,
        showCancelButton: true,
        confirmButtonText: 'Yes!',
        reverseButtons: true
    }).then(result => {
        if (result.isConfirmed && (required || result.value.trim() !== "")) {

            return result.value || true;
        } else if (result.isConfirmed) {

            return true;
        } else {

            return false;
        }
    })
};


// --- DATE AND STRING MANIPULATION ----------------------------------------------------------------
// -------------------------------------------------------------------------------------------------


const ucwords = (str) => {
    return str.toLowerCase().replace(/^(.)|\s+(.)/g, function ($1) {
        return $1.toUpperCase();
    });
};

const getDateInUnix = (date) => {
    try {
        if (typeof date === 'string') {
            return Date.parse(date) / 1000;
        } else if (date instanceof Date) {
            return date.getTime() / 1000;
        } else {
            throw new Error('Invalid date type. Must be a string or Date object');
        }
    } catch (error) {
        console.warn(`Could not convert date: ${date}`, error);
        return false;
    }
};


// --- REQUIRED FIELDS VALIDATION FUNCTION CHAIN  --------------------------------------------------
// -------------------------------------------------------------------------------------------------


/**
 * Validates if specified fields in a data object exist and meet defined criteria. Supports 
 * deep checks with path-like syntax, type validation, truthiness checks and "either-or" logic for multiple fields etc.
 * 
 * @param {Object} data - The data object to be evaluated
 * @param {Array}  requiredFields - An array of strings representing the fields to check, with optional type, length range, and truthiness specifications. Nested arrays implement "either-or" logic.
 * 
 * @return {Array} - An array objects that contain missing fields and their reasons, if either-or logic is used, there will be a nested array just like the input.
 * 
 * @tutorial
 * 
 * The function could be used like this:
 *    let data = {
 *      name: 'John Doe',
 *      contact: { email: 'john.doe@example.com' },
 *      age: 30,
 *      isActive: true,
 *      isVerified: true
 *    };
 * 
 *    checkRequiredFields(
 *      data,
 *      [ 
 *        'name',
 *        '(str@5-50)contact/email',
 *        '(int)age',
 *        '*isActive',
 *        ['(str)contact/email', '(str@7-11)*contact/phone'],
 *        '(bool)*isVerified'
 *      ]
 *    );
 */
const checkRequiredFields = (data, requiredFields = []) => {
    let missingFields = [];

    for (const field of requiredFields) {
        //---process nested array with either-or logic
        if (Array.isArray(field)) {
            let eitherExists = false;
            let fieldNamesAndReasons = [];

            for (const subField of field) {
                const [exists, reason] = processField(subField, data);
                if (exists) {
                    eitherExists = true;
                    break;
                } else {
                    const fieldName = getFieldName(subField);
                    fieldNamesAndReasons.push({ field: fieldName, message: reason });
                }
            }

            if (!eitherExists) {
                missingFields.push(fieldNamesAndReasons);
            }

        } else {
            const [exists, reason] = processField(field, data);

            if (!exists) {
                const fieldName = getFieldName(field);
                missingFields.push({ field: fieldName, message: reason });
            }
        }
    }

    return missingFields;
};

const getFieldName = (fullField) => {
    const fieldPath = fullField.match(/\((.*?)\)(.*)/)[2];
    const fieldName = fieldPath.split('/').reverse()[0];
    return fieldName.replace('*', '');
};

const processField = (field, data) => {
    let checkForTruthyValue = field.indexOf('*') > -1;
    if (checkForTruthyValue) {
        field = field.replace('*', '');
    }

    let matches = field.match(/\((.*?)\)(.*)/);
    let typeAndLength = (matches && matches[1]) || '';
    let fieldPath = (matches && matches[2]) || field;
    let type = typeAndLength.split('@')[0];
    let length = typeAndLength.split('@')[1] || null;

    //---split the path by '/' and traverse the data array
    let path = fieldPath.split('/');
    let valueFound = data;

    for (const part of path) {
        if (part in valueFound) {
            valueFound = valueFound[part];
        } else {
            return [false, "field not found"]; //---part of the path not found
        }
    }

    if (checkForTruthyValue && (!valueFound && typeof valueFound !== 'number')) {
        return [false, "value is empty!"];
    }

    //---perform type and length validation
    let valueIsValid = validateField(valueFound, type, length);
    if (valueIsValid !== true) {
        return [false, valueIsValid];
    }

    return [true, null];
};

const validateField = (value, type, length) => {
    let max_length = length;
    let min_length = 0;
    if (length.indexOf('-') > -1) {
        min_length = length.split("-")[0];
        max_length = length.split("-")[1];
    }

    switch (type) {
        case 'str':
            if (typeof value !== 'string') {
                return 'value is not a string';
            }
            if (max_length !== null && (min_length > value.length || value.length > max_length)) {
                return `value is shorter or longer than the accepted length, accepted length of value is between ${min_length} and ${max_length}`;
            }
            break;
        case 'int':
            if (isNaN(value)) {
                return 'value is not a number';
            }
            if (max_length !== null && (min_length > value.toString().length || value.toString().length > max_length)) {
                return `count of digits are less or more than accepted count, accepted count of digits are between ${min_length} and ${max_length}`;
            }
            break;
        case 'bool':
            if (typeof value !== 'boolean')
                return 'value is not a boolean';
            break;
        case 'array':
            if (!Array.isArray(value))
                return 'value is not an array';
            break;
        default:
            return true;
    }

    return true; //--if came all this way, then it's all good.
};

// --- DOCUMENT READY ------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------


$(function () {
    //---remove the page-loader overlay
    $('.page-loader svg').addClass('animated bounceOutRight');
    $('.page-loader-wrapper').addClass('animated fadeOut');
    setTimeout(() => { $('.page-loader-wrapper').remove() }, 1000);

    //---print messages from the session if any
    A1.message?.forEach(msg => {
        printMessage(msg);
    });

    //---if hash is provided for tab, then open and focus that tab
    if (window.location.hash) {
        const tab_id = window.location.hash;

        const $tab = $(tab_id);
        if ($tab.length) {
            $tab.tab('show');
            $tab[0].scrollIntoView({ behavior: 'smooth' });
        }
    }
});