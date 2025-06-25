<?php
require 'db.php';

$mensaje = isset($_GET['success']) ? '✅ Usuario registrado.' : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO Usuario (nombre, email, tipo_usuario) VALUES (:nombre, :email, :tipo)");
    $stmt->execute([
        ':nombre' => $_POST['nombre'],
        ':email' => $_POST['email'],
        ':tipo' => $_POST['tipo_usuario'],
    ]);
    header('Location: usuarios_form.php?success=1');
    exit;
}

$usuarios = $pdo->query("SELECT * FROM Usuario ORDER BY id_usuario DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios</title>
    <style>
        body{font-family:sans-serif;max-width:700px;margin:2rem auto}
        input,select{padding:.4rem;margin:.2rem 0;width:100%}
        .btn{padding:.6rem 1.2rem;border:none;border-radius:4px;background:#198754;color:#fff;cursor:pointer}
        table{width:100%;border-collapse:collapse;margin-top:1rem}
        th,td{border:1px solid #ccc;padding:.4rem;text-align:left}
        th{background:#f4f4f4}
        .msg{background:#e7ffe7;border:1px solid #8f8;padding:.5rem;margin-bottom:1rem}
        .back-link{margin-top:2rem;display:inline-block;color:#0d6efd;text-decoration:none}
    </style>
</head>
<body>

<h2>Registrar usuario</h2>

<?php if ($mensaje): ?>
    <div class="msg"><?= $mensaje ?></div>
<?php endif; ?>

<form method="POST">
    <label>Nombre
        <input type="text" name="nombre" required>
    </label><br>

    <label>Email
        <input type="email" name="email" required>
    </label><br>

    <label>Tipo de usuario
        <select name="tipo_usuario" required>
            <option value="">Seleccione un tipo</option>
            <option value="investigador">Investigador</option>
            <option value="voluntario">Voluntario</option>
            <option value="administrador">Administrador</option>
        </select>
    </label><br>

    <button type="submit" class="btn">Guardar Usuario</button>
</form>

<h2>Usuarios registrados</h2>
<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Tipo</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['nombre']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['tipo_usuario']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a class="back-link" href="index.php">⬅️ Volver al inicio</a>

</body>
</html>