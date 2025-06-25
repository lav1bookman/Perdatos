<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "INSERT INTO Observacion (fecha, id_usuario, id_especie, id_zona)
            VALUES (:fecha, :id_usuario, :id_especie, :id_zona)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':fecha' => $_POST['fecha'],
        ':id_usuario' => $_POST['id_usuario'],
        ':id_especie' => $_POST['id_especie'],
        ':id_zona' => $_POST['id_zona'],
    ]);
}

header('Location: observaciones_form.php?success=1');
exit;