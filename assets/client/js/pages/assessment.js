$(function () {
    files.onchange = evt => {
      const [file] = files.files
      if (file) {
        preview.src = URL.createObjectURL(file)
      }
    };

    const convertBase64 = (file) => {
        return new Promise((resolve, reject) => {
            const fileReader = new FileReader();
            fileReader.readAsDataURL(file);

            fileReader.onload = () => {
                resolve(fileReader.result);
            };

            fileReader.onerror = (error) => {
                reject(error);
            };
        });
    };

    $('[data-fr-action="submit"]').on('click', function () {
        printMessage({ status: 'success', text: 'sending to api' });

        let file = document.querySelector('input[type=file]').files[0];

        convertBase64(file)
            .then((result) => {
                let json = {
                    task: "assessment",
                    endPoint: "assessment-api",
                    initiatorElement: $(this),
                    payload: {
                        fields: { file: result }
                    },
                    customFunctions: {
                        success: (res) => {
                            printMessage({ status: 'info', text: 'response received' });
                        }
                    }
                }

                ajaxPost(json);
            });
    });
})
