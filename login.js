
console.log("login.js cargado");

function login() {
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();
    const tipo = document.getElementById("tipo").value; // medico | paciente
    const errorEl = document.getElementById("error");

    errorEl.innerText = "";

    if (!username || !password) {
        errorEl.innerText = "Completá usuario y contraseña";
        return;
    }

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
    .then(response => response.json())
    .then(data => {
        console.log("Respuesta del servidor:", data);

        if (!data.success) {
            errorEl.innerText = data.message;
            return;
        }

        // Validar que el rol coincida con el selector
        if (data.rol !== tipo) {
            errorEl.innerText = "El rol seleccionado no corresponde al usuario";
            return;
        }

        // Redirección
        if (data.rol === "medico") {
            window.location.href = "medico.html";
        } else if (data.rol === "paciente") {
            window.location.href = "paciente.html";
        }
    })
    .catch(err => {
        console.error(err);
        errorEl.innerText = "Error al conectar con el servidor";
    });
}





