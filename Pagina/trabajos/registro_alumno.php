

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Alumno</title>
    <link rel="stylesheet" href="CSS/registro_alum.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <div class="logo">
                <img src="./imagenes/otto_krause.png" alt="Escuela Técnica Otto Krause" class="logo-img">
                <h3>Escuela Técnica<br>OTTO KRAUSE</h3>
            </div>
            <div class="registro">
                <div class="icono">
                    <!-- Puedes añadir aquí un ícono de libreta si lo tienes -->
                </div>
                <h2>REGISTRO DE ALUMNO</h2>
            </div>
        </div>
        <div class="right-panel">
            <h2>Datos</h2>
            <form action="guardar_registro.php" method="post">
                <input type="text" name="nombre" placeholder="Nombre" required>
                <input type="text" name="apellido" placeholder="Apellido" required>
                <input type="number" name="dni" placeholder="Dni" required>
                <input type="number" name="telefono" placeholder="Telefono" required>

                <!-- Selector para el año -->
                <select name="anio" id="anio" required>
                    <option value="" disabled selected>Seleccionar Año</option>
                    <?php
                    // Conexión a la base de datos
                    include 'base_de_datos.php';
                    
                    // Consulta para obtener los años
                    $query = "SELECT id_ano, descripcion FROM año";
                    $result = mysqli_query($conexion, $query);
                    
                    // Verificar si la consulta retorna datos
                    if (mysqli_num_rows($result) > 0) {
                        // Mostrar los años en el dropdown
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row['id_ano'] . "'>" . $row['descripcion'] . "</option>";
                        }
                    } else {
                        echo "<option value='' disabled>No hay años disponibles</option>";
                    }
                    ?>
                </select>

                <!-- Selector para la división -->
                <select name="division" id="division" required>
                    <option value="" disabled selected>Seleccionar División</option>
                </select>

                <!-- Selector para la especialidad (se oculta inicialmente) -->
                <select name="especialidad" id="especialidad" style="display: none;">
                    <option value="" disabled selected>Seleccionar Especialidad</option>
                </select>

                <button type="submit">INGRESAR</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Cuando se selecciona un año, cargar las divisiones correspondientes
            $('#anio').change(function() {
                var anio = $(this).val();

                if (anio) {
                    // Mostrar u ocultar el selector de especialidad según el año
                    if (anio >= 3) {
                        $('#especialidad').show().prop('required', true); // Mostrar y agregar "required"
                    } else {
                        $('#especialidad').hide().prop('required', false); // Ocultar y quitar "required"
                    }

                    // Cargar las divisiones correspondientes
                    $.ajax({
                        url: 'obtener_divisiones.php', // Archivo que manejará la solicitud AJAX
                        type: 'POST',
                        data: {anio: anio},
                        success: function(data) {
                            $('#division').html(data); // Llenar el dropdown de divisiones
                            
                            $('#especialidad').html('<option value="" disabled selected>Seleccionar Especialidad</option>'); // Resetear especialidades
                        }
                    });
                } else {
                    $('#division').html('<option value="" disabled selected>Seleccionar División</option>');
                    $('#especialidad').html('<option value="" disabled selected>Seleccionar Especialidad</option>');
                    $('#especialidad').hide().prop('required', false);
                }
            });

            // Cuando la división cambia, cargar las especialidades correspondientes
            $('#division').change(function() {  
                var anio = $('#anio').val();
                var division = $(this).val();

                if (anio && division) {
                    $.ajax({
                        url: 'obtener_especialidades.php', // Archivo que manejará la solicitud AJAX
                        type: 'POST',
                        data: {anio: anio, division: division},
                        success: function(data) {
                            $('#especialidad').html(data); // Llenar el dropdown de especialidades
                        }
                    });
                } else {
                    $('#especialidad').html('<option value="" disabled selected>Seleccionar Especialidad</option>');
                }
            });
        });
    </script>
</body>
</html>
