console.log('test')

$(function () {
    $('[data-fr-action="test-ajax"]').on('click', function () {
        printMessage({ status: 'success', text: 'sending to api' });

        let json = {
            task: "assessment",
            endPoint: "assessment-api",
            initiatorElement: $(this),
            payload: {
                fields: { field1: "value1", field2: "value2" }
            },
            customFunctions: {
                success: (res) => {
                    printMessage({ status: 'info', text: 'response received' });
                }
            }
        }

        ajaxPost(json);
    })
})