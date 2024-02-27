// Funcion para formatear inputs

const formatInputs = function () {
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
}

// Funcion para notie alert
const notieAlert = function (tp, msg) {
    notie.alert({
        type: tp,
        text: msg,
        time: 6
    });
}

// Funcion para sweet alert
const sweetAlert = function (type, msg, url = null) {
    switch (type) {
        case "error":

            if (url == null) {
                Swal.fire({
                    allowOutsideClick: false,
                    title: "Error!",
                    text: msg,
                    icon: "error"
                });
            } else {
                Swal.fire({
                    allowOutsideClick: false,
                    title: "Error!",
                    text: msg,
                    icon: "error"
                }).then((result) => {

                    if (result.value) {
                        window.open(url, "_top");
                    }
                })
            }

            break;

        case "success":

            if (url == null) {
                Swal.fire({
                    allowOutsideClick: false,
                    title: "Success!",
                    text: msg,
                    icon: "success"
                });
            } else {
                Swal.fire({
                    allowOutsideClick: false,
                    title: "Success!",
                    text: msg,
                    icon: "success"
                }).then((result) => {

                    if (result.value) {
                        window.open(url, "_top");
                    }
                })
            }

            break;

        // Cuando estamos precargando

        case "loading":

            Swal.fire({
                allowOutsideClick: false,
                text: msg,
                icon: "info"
            });

            Swal.showLoading();

            break;

        // Cuando necesitamos cerrar

        case "close":

            Swal.close();

            break;

        // Cuando solicitamos confirmacion

        case "confirm":

            return new Promise(resolve => {
                Swal.fire({
                    allowOutsideClick: false,
                    text: msg,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    cancelButtonText: "Cancel",
                    confirmButtonText: "Yes, delete!"
                }).then(function (result) {

                    resolve(result.value);
                })
            });

            break;


        // Cuando necesitamos integrar un HTML

        case "html":

            Swal.fire({
                allowOutsideClick: false,
                title: "Click to continue with the payment",
                icon: "info",
                html: msg,
                showConfirmButton: false,
                showCancelButton: true,
                cancelButtonColor: "#d33"
            })

            break;
    }
}