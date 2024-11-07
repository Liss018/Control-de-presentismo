function editarAlumno(id) {
    fetch(`obtener_alumno.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                document.getElementById("nombre").value = data.nombre;
                document.getElementById("apellido").value = data.apellido;
                document.getElementById("dni").value = data.dni;
                document.getElementById("editForm").dataset.alumnoId = id;

                // Muestra el modal
                document.getElementById("editModal").style.display = "block";
            } else {
                console.error("No se encontraron datos para el alumno.");
            }
        })
        .catch((error) => {
            console.error("Error al obtener los datos del alumno:", error);
        });
}

function cerrareditModal() {
    document.getElementById("editModal").style.display = "none";
    location.reload(); // Recargar la página
}

function guardarCambios() {
    const id = document.getElementById("editForm").dataset.alumnoId;
    const nombre = document.getElementById("nombre").value;
    const apellido = document.getElementById("apellido").value;
    const dni = document.getElementById("dni").value;

    fetch('guardar_alumno.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: id,
            nombre: nombre,
            apellido: apellido,
            dni: dni
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert("Error del servidor: " + data.error);
        } else {
            alert("Datos actualizados correctamente.");
            cerrareditModal();
            location.reload(); // Recargar la página para ver los cambios
        }
    })
    .catch((error) => {
        console.error("Error al actualizar los datos:", error);
    });
}