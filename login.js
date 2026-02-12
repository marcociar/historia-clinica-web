document.getElementById("loginForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    const response = await fetch("login.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            username: username,
            password: password
        })
    });

    const data = await response.json();
    console.log(data);

    if (data.success) {
        if (data.rol === "medico") {
            window.location.href = "medico/panel_medico.php";
        } else {
            window.location.href = "paciente/panel_paciente.php";
        }
    } else {
        document.getElementById("mensaje").innerText = data.message;
    }
});
