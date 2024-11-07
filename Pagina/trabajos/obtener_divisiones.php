<?php
include 'base_de_datos.php';

if (isset($_POST['anio'])) {
    $anio = $_POST['anio'];

    // Consulta para obtener las divisiones de un año específico sin duplicados
    $query = "SELECT DISTINCT Nr FROM division WHERE año = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, 'i', $anio);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Verificar si la consulta retorna datos
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['Nr'] . "'>" . $row['Nr'] . "</option>";
        }
    } else {
        echo "<option value='' disabled>No hay divisiones disponibles</option>";
    }
}
?>
