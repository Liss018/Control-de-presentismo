<?php
// Conexión a la base de datos
include 'base_de_datos.php';

// Obtener parámetros de la URL
$turno = isset($_GET['turno']) ? $_GET['turno'] : '';
$id_ano = isset($_GET['id_ano']) ? $_GET['id_ano'] : '';
$id_division = isset($_GET['id_division']) ? $_GET['id_division'] : '';
$especialidad = isset($_GET['especialidad']) ? $_GET['especialidad'] : ''; // Captura el parámetro especialidad

// Obtener la fecha actual o la fecha seleccionada
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

// Validar que los parámetros requeridos no estén vacíos
if (empty($turno) || empty($id_ano) || empty($id_division) || empty($especialidad)) {
    die("Faltan parámetros necesarios para la consulta.");
}

// Obtener la asistencia filtrada por turno, año, división y nombre de especialidad
$query = "SELECT ra.nombre, ra.apellido, a.asistencia, a.observaciones
          FROM asistencia a
          JOIN registro_alum ra ON a.id_alum = ra.id_alum
          JOIN division d ON ra.id_divi = d.id
          JOIN especialidades e ON ra.id_espe = e.id_espe 
          WHERE a.fecha = ? AND d.turno = ? AND ra.id_ano = ? AND d.Nr = ? AND e.tipo = ?"; 


$stmt = $conexion->prepare($query);
$stmt->bind_param("ssiis", $fecha, $turno, $id_ano, $id_division, $especialidad);

$stmt->execute();
$result = $stmt->get_result();

// Calcular la fecha anterior y posterior
$fecha_anterior = date('Y-m-d', strtotime($fecha . ' -1 day'));
$fecha_posterior = date('Y-m-d', strtotime($fecha . ' +1 day'));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Asistencia</title>
    <link rel="stylesheet" href="CSS/header-footer.css">
</head>
<body>
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
</header>
    <div class="container">
        <h1>Asistencia del <?php echo date('d/m/Y', strtotime($fecha)); ?></h1>
        
        <div class="navigation">
            <a href="ver_asistencia_admin.php?fecha=<?php echo urlencode($fecha_anterior); ?>&turno=<?php echo urlencode($turno); ?>&id_ano=<?php echo urlencode($id_ano); ?>&id_division=<?php echo urlencode($id_division); ?>&especialidad=<?php echo urlencode($especialidad); ?>" class="arrow left">←</a>
            <a href="ver_asistencia_admin.php?fecha=<?php echo urlencode($fecha_posterior); ?>&turno=<?php echo urlencode($turno); ?>&id_ano=<?php echo urlencode($id_ano); ?>&id_division=<?php echo urlencode($id_division); ?>&especialidad=<?php echo urlencode($especialidad); ?>" class="arrow right">→</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Asistencia</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['apellido']); ?></td>
                            <td><?php echo htmlspecialchars($row['asistencia']); ?></td>
                            <td><?php echo htmlspecialchars($row['observaciones']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No hay registros de asistencia para este curso, año y turno en este día.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a class="button" href="Asistencia_admi_especialidades.php?turno=<?php echo urlencode($turno); ?>&id_ano=<?php echo urlencode($id_ano); ?>&id_division=<?php echo urlencode($id_division); ?>&especialidad=<?php echo urlencode($especialidad); ?>" >Volver</a>
    </div>
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

<?php
// Cerrar la conexión
$stmt->close();
$conexion->close();
?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 80%;
        margin: 0 auto;
        padding: 20px;
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
    }

    .navigation {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .arrow {
        text-decoration: none;
        font-size: 24px;
        color: #007bff;
    }

    .arrow:hover {
        color: #0056b3;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #007bff;
        color: white;
    }

    .button {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 15px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }

    .button:hover {
        background-color: #0056b3;
    }
</style>
