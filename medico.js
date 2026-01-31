async function cargarPacientes() {
    const res = await fetch("pacientes.php");
    const pacientes = await res.json();

    const ul = document.getElementById("listaPacientes");
    ul.innerHTML = "";

    pacientes.forEach(p => {
        const li = document.createElement("li");
        li.textContent = p.nombre + " " + p.apellido;
        li.style.cursor = "pointer";

        li.onclick = () => {
            document.getElementById("pacienteId").value = p.id;
            cargarHistorias(p.id);
        };


        ul.appendChild(li);
    });
}

function guardarHistoria() {
    const pacienteId = document.getElementById("pacienteId").value;
    const diagnostico = document.getElementById("diagnostico").value;
    const observaciones = document.getElementById("observaciones").value;

    if (!pacienteId) {
        alert("Seleccioná un paciente primero");
        return;
    }

    fetch("guardar_historia.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            pacienteId: pacienteId,
            diagnostico,
            observaciones
        })
    })
    .then(res => res.json())
    .then(data => {
        alert("Historia guardada correctamente");
        document.getElementById("diagnostico").value = "";
        document.getElementById("observaciones").value = "";
    });
}

function cerrarSesion() {
    window.location.href = "logout.php";
}

function cargarHistorias(pacienteId) {
    fetch(`obtener_historias.php?paciente_id=${pacienteId}`)
        .then(res => res.json())
        .then(data => {
            const contenedor = document.getElementById("historial");
            contenedor.innerHTML = "";

            if (data.length === 0) {
                contenedor.innerHTML = "<p>No hay historias clínicas.</p>";
                return;
            }

            data.forEach(h => {
                const div = document.createElement("div");
                div.style.border = "1px solid #ccc";
                div.style.padding = "10px";
                div.style.marginBottom = "10px";

                div.innerHTML = `
                    <strong>Fecha:</strong> ${h.fecha}<br>
                    <strong>Diagnóstico:</strong> ${h.diagnostico}<br>
                    <strong>Observaciones:</strong> ${h.observaciones}
                `;

                contenedor.appendChild(div);
            });
        });
}

