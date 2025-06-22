<?php
require 'db.php';

/* 1. Validamos que llegue por POST */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $sql = "INSERT INTO Usuario (nombre, email, tipo_usuario)
            VALUES (:nombre, :email, :tipo)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre' => $_POST['nombre'] ?? '',
        ':email'  => $_POST['email'] ?? '',
        ':tipo'   => $_POST['tipo_usuario'] ?? 'invitado',
    ]);
}

/* 2. Volvemos al formulario con un parámetro de éxito */
header('Location: usuarios_form.php?success=1');
exit;