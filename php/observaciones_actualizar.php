<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "UPDATE Observacion
            SET fecha = :fecha,
                id_usuario = :id_usuario,
                id_especie = :id_especie,
                id_zona = :id_zona
            WHERE id_observacion = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':fecha'      => $_POST['fecha'],
        ':id_usuario' => $_POST['id_usuario'],
        ':id_especie' => $_POST['id_especie'],
        ':id_zona'    => $_POST['id_zona'],
        ':id'         => $_POST['id'],
    ]);
}

header('Location: observaciones_form.php?success=1');
exit;