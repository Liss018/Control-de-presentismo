let idAlumnoActual;

        function abrirModalCodigo(idAlumno, codigo) {
            idAlumnoActual = idAlumno; // Guardar el ID del alumno actual
            document.getElementById('codigoModal').style.display = 'block'; // Mostrar el modal
        }

        function cerrarCodigoModal() {
            document.getElementById('codigoModal').style.display = 'none'; // Cerrar el modal
        }

        function confirmarCodigo() {
            const codigoIngresado = document.getElementById('codigo-input').value.trim();
            
            // Hacer la solicitud AJAX para actualizar la presencia
            fetch('actualizar_presencia.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id_alumno=' + idAlumnoActual + '&codigo=' + encodeURIComponent(codigoIngresado)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message); // Mostrar mensaje de éxito
                    location.reload(); // Recargar la página para ver los cambios
                } else {
                    alert(data.message); // Mostrar mensaje de error
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
            
            cerrarCodigoModal(); // Cerrar el modal después de enviar
        }