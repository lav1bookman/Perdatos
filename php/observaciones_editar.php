<?php
require 'db.php';

if (!isset($_GET['id'])) {
    header('Location: observaciones_form.php');
    exit;
}

$id = $_GET['id'];

$obs = $pdo->prepare("SELECT * FROM Observacion WHERE id_observacion = :id");
$obs->execute([':id' => $id]);
$observacion = $obs->fetch();

$usuarios = $pdo->query("SELECT id_usuario, nombre FROM Usuario")->fetchAll();
$especies = $pdo->query("SELECT id_especie, nombre_comun FROM EspecieMarina")->fetchAll();
$zonas    = $pdo->query("SELECT id_zona, nombre FROM ZonaMarina")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar observación</title>
    <style>
        body{font-family:sans-serif;max-width:700px;margin:2rem auto}
        input,select{padding:.4rem;margin:.2rem 0;width:100%}
        .btn{padding:.6rem 1.2rem;border:none;border-radius:4px;
             background:#198754;color:#fff;cursor:pointer}
    </style>
</head>
<body>

<h2>Editar observación #<?= $id ?></h2>

<form action="observaciones_actualizar.php" method="POST">
    <input type="hidden" name="id" value="<?= $id ?>">

    <label>Fecha
        <input type="date" name="fecha" required value="<?= $observacion['fecha'] ?>">
    </label><br>

    <label>Usuario
        <select name="id_usuario" required>
            <?php foreach ($usuarios as $u): ?>
                <option value="<?= $u['id_usuario'] ?>" <?= $u['id_usuario'] == $observacion['id_usuario'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br>

    <label>Especie Marina
        <select name="id_especie" required>
            <?php foreach ($especies as $e): ?>
                <option value="<?= $e['id_especie'] ?>" <?= $e['id_especie'] == $observacion['id_especie'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($e['nombre_comun']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br>

    <label>Zona Marina
        <select name="id_zona" required>
            <?php foreach ($zonas as $z): ?>
                <option value="<?= $z['id_zona'] ?>" <?= $z['id_zona'] == $observacion['id_zona'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($z['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br>

    <button type="submit" class="btn">Actualizar</button>
</form>

<a class="back-link" href="observaciones_insertar.php">⬅️ Volver a observaciones_insertar</a>

</body>
</html>