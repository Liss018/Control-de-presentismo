<?php
// guardar_asistencia.php

error_reporting(E_ALL);
ini_set('display_errors', 1); // Mostrar todos los errores

require 'base_de_datos.php';

// Recibir los datos JSON
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    // Iniciar la transacción
    mysqli_begin_transaction($conexion);

    try {
        foreach ($data as $record) {
            $id_alum = $record['id'];
            $estado = $record['estado'];
            $fecha = $record['fecha'];

            // Verificar si ya existe un registro para el mismo día
            $stmt_check = $conexion->prepare("SELECT * FROM asistencia WHERE id_alum = ? AND fecha = ?");
            $stmt_check->bind_param('is', $id_alum, $fecha);
            $stmt_check->execute();
            $result = $stmt_check->get_result();

            if ($result->num_rows > 0) {
                // Actualizar registro existente
                $stmt_update = $conexion->prepare("UPDATE asistencia SET asistencia = ? WHERE id_alum = ? AND fecha = ?");
                $stmt_update->bind_param('sis', $estado, $id_alum, $fecha);
                $stmt_update->execute();
            } else {
                // Insertar nuevo registro
                $stmt_insert = $conexion->prepare("INSERT INTO asistencia (id_alum, asistencia, fecha) VALUES (?, ?, ?)");
                $stmt_insert->bind_param('iss', $id_alum, $estado, $fecha);
                $stmt_insert->execute();
            }
        }

        // Confirmar la transacción
        mysqli_commit($conexion);
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        mysqli_rollback($conexion);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No se recibieron datos.']);
}
