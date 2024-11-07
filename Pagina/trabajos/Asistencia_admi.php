<?php
include 'base_de_datos.php';
session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado como administrador
if (!isset($_SESSION['admin_id'])) {
    // Redirigir al inicio de sesión si no está autenticado
    header("Location: sesion_admi.html");
    exit();
}

// Obtener los valores de la URL si están definidos
$turno = isset($_GET['turno']) ? htmlspecialchars($_GET['turno']) : '';
$id_ano = isset($_GET['año']) ? intval($_GET['año']) : '';
$id_division = isset($_GET['id_division']) ? intval($_GET['id_division']) : 0;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencia</title>
    
    <link rel="stylesheet" href="CSS/header-footer.css">
    <link rel="stylesheet" href="CSS/lista.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    
  <script src="assets/plugins/qrCode.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
<script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>

<header class="header">
    <div class="top-bar">
        <div class="logo">
            <a href="https://www.ottokrause.edu.ar/"><img src="imagenes/logo_Krause.png" alt="Logo"></a>
        </div>
        
        <div class="menu-icon" id="menu-icon">&#9776;</div>
       
        <nav class="nav" id="nav">
            <ul>
                <hr>
                <li class="inicio"><a class="Logout" href="cerrar_sesion_admin.php">CERRAR SESION</a></li>
                <hr>
            </ul>
        </nav>
        <script src="scripts/header.js"></script>
    </div>
    <hr>
    <button class="volver-boton" onclick="window.location.href='divisiones.php?turno=<?php echo urlencode($turno); ?>&id_ano=<?php echo $id_ano; ?>';">Volver</button>
</header>


<h2>Alumnos</h2>



<div class="contenedor">
<table border>
    <thead>
      <tr>
        <th>Número</th>
        <th>Nombre y Apellido</th>
        <th>DNI</th>
        <th>Estado</th>
        <th>Escanear QR</th>
        <th>Editar</th>
        <th>Eliminar</th> <!-- Nueva columna -->
      </tr>  
    </thead>
    <tbody>
    <?php
    $fechaActual = date('Y-m-d');

    // Modificar la consulta SQL para incluir el estado de asistencia
    $sql = "SELECT registro_alum.id_alum, registro_alum.dni, registro_alum.apellido,
        CONCAT(registro_alum.apellido, ' ', registro_alum.nombre) AS nombre_completo,
        registro_alum.codigo,
        COALESCE(asistencia.asistencia, 'Ausente') AS estado
    FROM registro_alum 
    INNER JOIN division ON registro_alum.id_divi = division.id
    LEFT JOIN asistencia ON registro_alum.id_alum = asistencia.id_alum AND asistencia.fecha = ? 
    WHERE division.id = ? AND division.turno = ? AND division.año = ?
    ORDER BY registro_alum.apellido ASC";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('sisi', $fechaActual, $id_division, $turno, $id_ano);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $contador = 1;
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $contador . '</td>';
            echo '<td>' . htmlspecialchars($row["nombre_completo"]) . '</td>';
            echo '<td>' . htmlspecialchars($row["dni"]) . '</td>';
            echo '<td><button onclick="abrirModalCodigo(' . $row["id_alum"] . ', \'' . htmlspecialchars($row["codigo"]) . '\', \'' . htmlspecialchars($row["estado"]) . '\')">Guardar Presencia</button></td>';
            echo '<td><button onclick="abrirVentanaEmergente(' . $row["id_alum"] . ', \'' . $row["codigo"] . '\')">Escanear QR</button></td>';
            echo '<td>
                <button onclick="editarAlumno(' . $row["id_alum"] . ')">
                    <i class="fas fa-edit"></i>
                </button>
            </td>';
            echo '<td>
                <button onclick="eliminarAlumno(' . $row["id_alum"] . ')">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>'; // Botón para eliminar
            echo '</tr>';
            $contador++;
        }
    } else {
        echo "<tr><td colspan='7'>No hay alumnos disponibles para esta división, turno y año.</td></tr>";
    }

    $conexion->close();
    ?>
    </tbody>
</table>
</div>

<button class="vol" onclick="window.location.href='ver_asistencia.php?turno=<?php echo urlencode($turno); ?>&id_ano=<?php echo $id_ano; ?>&id_division=<?php echo $id_division; ?>';">
    Ver Asistencia
</button>
<style>
    .vol{
        width: 30%;
        margin:0px auto;
        font-size:20px;
    }
</style>
<footer>
    <div class="footer-left">
        <img src="imagenes/escudo inferior.png" class="footer-logo">
        <span><a href="https://miok.ottokrause.edu.ar/">MIOK</a></span>
    </div>
    <div class="footer-right">
        <a href="#"><img src="imagenes/twitter.png" class="icon-twitter"></a>
        <a href="#"><img src="imagenes/facebook.png" class="icon-facebook"></a>
    </div>
</footer>
</body>
</html>

<script src="scripts/alterar_alumno.js"></script>
<script src="scripts/eliminar.js"></script>

<!-- Modal para editar datos del alumno -->
<div id="editModal" style="display: none;">
    <form id="editForm">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" required>

        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" required>

        <label for="dni">DNI:</label>
        <input type="text" id="dni" required>

        <button type="button" onclick="guardarCambios()">Guardar Cambios</button>
        <button type="button" onclick="cerrareditModal()">Cancelar</button>
    </form>
</div>

<!-- Modal para escanear el código QR -->
<div id="qrScannerModal" style="display: none;">
    <div class="modal-content">
        <h5 class="text-center">Escanear código QR</h5>
        <video id="video" playsinline></video>
        <canvas id="qr-canvas" hidden></canvas>
        <div class="text-center">
            <button id="btn-scan-qr" onclick="encenderCamara()">Iniciar Escaneo</button>
            <button class="btn btn-danger btn-sm rounded-3" onclick="cerrarCamara()">Detener cámara</button>
            <button class="btn btn-secondary btn-sm rounded-3" onclick="cerrarModal()">Cerrar</button>
        </div>
        <!-- Sección para mostrar el resultado -->
        <div id="scan-result" class="text-center" style="margin-top: 20px; display: none;">
            <p id="qr-text"></p>
            <button id="btn-copy" class="btn btn-primary btn-sm rounded-3" onclick="copiarTexto()" style="margin-left: 10px;">Copiar</button>
        </div>
    </div>
</div>

<!-- Ventana Modal -->
<div id="modal-verificar" style="display: none;">
    <div class="modal-content">
        <span id="close-modal" style="cursor: pointer;">&times;</span>
        <h2>Verificar Código del Alumno</h2>
        <input type="text" id="codigo-alumno" placeholder="Pega el código del alumno" />
        <button id="btn-verificar">Verificar</button>
        <p id="estado-verificacion"></p>
    </div>
</div>



<!-- Modal para ingresar el código del alumno -->
<div id="codigoModal">
    <div class="modal-content">
        <h5>Ingresar Código del Alumno</h5>
        <input type="text" id="codigo-input" placeholder="Escribe el código" required>
        <button id="btn-confirmar" onclick="confirmarCodigo()">Confirmar</button>
        <button onclick="cerrarCodigoModal()">Cancelar</button>
    </div>
</div>

<script>
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
    </script>

<script>
    // Variables globales
    let codigoEscaneado = ""; // Almacena el código escaneado
    const btnVerificar = document.getElementById("btn-verificar");
    const modalVerificar = document.getElementById("modal-verificar");
    const closeModal = document.getElementById("close-modal");
    const estadoVerificacion = document.getElementById("estado-verificacion");

    // Callback cuando termina de leer el código QR
    qrcode.callback = (respuesta) => {
        if (respuesta) {
            codigoEscaneado = respuesta; // Guarda el código escaneado
            Swal.fire(respuesta);
            activarSonido();
            cerrarCamara();
            mostrarModal(); // Muestra la ventana modal para verificar
        }
    };

    // Función para mostrar la ventana modal
    function mostrarModal() {
        modalVerificar.style.display = "flex"; // Muestra la ventana modal
    }

    // Evento para cerrar la ventana modal
    closeModal.addEventListener("click", () => {
        modalVerificar.style.display = "none"; // Cierra la ventana modal
    });

    function abrirVentanaEmergente(idAlumno, codigo) {
        // Abre el modal para escanear el QR
        document.getElementById('qrScannerModal').style.display = 'block';
        // Puedes aquí cargar el código del alumno si es necesario
    }

    function cerrarModal() {
        // Cierra el modal
        document.getElementById('qrScannerModal').style.display = 'none';
    }
</script>

<script src="scripts/QR.js"></script>
<script>
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
            cerrarModal();
            location.reload(); // Recargar la página para ver los cambios
        }
    })
    .catch((error) => {
        console.error("Error al actualizar los datos:", error);
    });
}
</script>