<?php
include 'base_de_datos.php';

if (isset($_POST['anio']) && isset($_POST['division'])) {
    $anio = $_POST['anio'];
    $division_nr = $_POST['division']; // Este es el número de la división (Nr), no el id

    // Primero obtenemos los ids de las divisiones que coinciden con el año y el número de división
    $query_division = "SELECT id FROM division WHERE año = ? AND Nr = ?";
    $stmt_division = mysqli_prepare($conexion, $query_division);
    mysqli_stmt_bind_param($stmt_division, 'ii', $anio, $division_nr);
    mysqli_stmt_execute($stmt_division);
    $result_division = mysqli_stmt_get_result($stmt_division);

    $divisiones_ids = [];
    while ($row = mysqli_fetch_assoc($result_division)) {
        $divisiones_ids[] = $row['id'];
    }

    // Ahora buscamos todas las especialidades que coincidan con esos ids de divisiones
    if (!empty($divisiones_ids)) {
        $placeholders = implode(',', array_fill(0, count($divisiones_ids), '?'));
        $query_especialidades = "SELECT id_espe, tipo FROM especialidades WHERE id_ano = ? AND id_divi IN ($placeholders)";
        $stmt_especialidades = mysqli_prepare($conexion, $query_especialidades);

        // Generamos un array de parámetros con el año y los ids de las divisiones
        $types = str_repeat('i', count($divisiones_ids) + 1);
        $params = array_merge([$anio], $divisiones_ids);
        mysqli_stmt_bind_param($stmt_especialidades, $types, ...$params);

        mysqli_stmt_execute($stmt_especialidades);
        $result_especialidades = mysqli_stmt_get_result($stmt_especialidades);

        if (mysqli_num_rows($result_especialidades) > 0) {
            while ($row = mysqli_fetch_assoc($result_especialidades)) {
                echo "<option value='" . $row['id_espe'] . "'>" . $row['tipo'] . "</option>";
            }
        } else {
            echo "<option value='' disabled>No hay especialidades disponibles</option>";
        }
    } else {
        echo "<option value='' disabled>No hay divisiones coincidentes</option>";
    }
}
?>
