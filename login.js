
console.log("login.js cargado");

async function login() {
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    console.log("Login:", username, password);

    const res = await fetch("login.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ username, password })
    });

    const data = await res.json();
    console.log(data);

    if (data.success) {
        if (data.rol === "medico") {
            window.location.href = "medico.html";
        } else {
            window.location.href = "paciente.html";
        }
    } else {
        document.getElementById("error").innerText =
            "Usuario o contraseña incorrectos";
    }
}

