
function login() {
    const tipo = document.getElementById("tipo").value;
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const errorEl = document.getElementById("error");

    errorEl.innerText = "";

    fetch("login.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            username: username,
            password: password
        })
    })
    .then(res => res.json())
    .then(data => {
        console.log("Respuesta del servidor:", data);

        if (!data.success) {
            errorEl.innerText = data.message;
            return;
        }

        // Validar rol
        if (data.rol !== tipo) {
            errorEl.innerText = "El rol seleccionado no corresponde al usuario";
            return;
        }

        // Redirección
        if (data.rol === "medico") {
            window.location.href = "medico/dashboard.php";
        } else if (data.rol === "paciente") {
            window.location.href = "paciente/dashboard.php";
        }
    })
    .catch(err => {
        console.error(err);
        errorEl.innerText = "Error al conectar con el servidor";
    });
}






