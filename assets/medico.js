
document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("formPaciente");
    const mensaje = document.getElementById("mensaje");
    const lista = document.getElementById("listaPacientes");

    // =========================
    // CREAR PACIENTE
    // =========================
    form.addEventListener("submit", async (e) => {
        e.preventDefault();
        mensaje.textContent = "Guardando paciente...";

        const formData = new FormData(form);

        try {
            const response = await fetch("crear_paciente.php", {
                method: "POST",
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                mensaje.textContent = "✅ Paciente creado correctamente";
                form.reset();
                cargarPacientes();
            } else {
                mensaje.textContent = "❌ " + data.message;
            }

        } catch (error) {
            console.error(error);
            mensaje.textContent = "❌ Error de conexión con el servidor";
        }
    });

    // =========================
    // LISTAR PACIENTES
    // =========================
    async function cargarPacientes() {
        lista.innerHTML = "<li>Cargando...</li>";

        try {
            const response = await fetch("pacientes.php");
            const data = await response.json();

            lista.innerHTML = "";

            if (data.success && data.pacientes.length > 0) {
                data.pacientes.forEach(p => {
                    const li = document.createElement("li");
                    li.textContent = `${p.apellido}, ${p.nombre}`;
                    lista.appendChild(li);
                });
            } else {
                lista.innerHTML = "<li>No hay pacientes</li>";
            }

        } catch (error) {
            console.error(error);
            lista.innerHTML = "<li>Error al cargar pacientes</li>";
        }
    }

    // Cargar al iniciar
    cargarPacientes();
});





