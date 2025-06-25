<?php
require 'db.php';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM Observacion WHERE id_observacion = :id");
    $stmt->execute([':id' => $_GET['id']]);
}

header('Location: observaciones_form.php');
exit;