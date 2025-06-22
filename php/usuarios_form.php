<?php
require 'db.php';

$mensaje = isset($_GET['success']) ? '✅ Usuario registrado.' : '';
$usuarios = $pdo->query(
    "SELECT id_usuario, nombre, email, tipo_usuario
     FROM Usuario
     ORDER BY id_usuario DESC"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios – CRUD básico</title>
    <style>
        body{font-family:sans-serif;max-width:700px;margin:2rem auto}
        input,select{padding:.4rem;margin:.2rem 0;width:100%}
        .btn{padding:.6rem 1.2rem;border:none;border-radius:4px;
             background:#0d6efd;color:#fff;cursor:pointer;font-size:1rem}
        .btn:hover{background:#0b5ed7}
        table{width:100%;border-collapse:collapse;margin-top:1rem}
        th,td{border:1px solid #ccc;padding:.4rem;text-align:left}
        th{background:#f4f4f4}
        .msg{background:#e7ffe7;border:1px solid #8f8;padding:.5rem;margin-bottom:1rem}
    </style>
</head>
<body>

<h2>Alta de usuarios</h2>

<?php if ($mensaje): ?>
    <div class="msg"><?= $mensaje ?></div>
<?php endif; ?>

<form action="usuarios_insertar.php" method="POST">
    <label>Nombre
        <input type="text" name="nombre" required>
    </label><br>

    <label>Email
        <input type="email" name="email" required>
    </label><br>

    <label>Tipo de usuario
        <select name="tipo_usuario" required>
            <option value="administrador">Administrador</option>
            <option value="biologo">Biólogo</option>
            <option value="invitado">Invitado</option>
        </select>
    </label><br>

    <button type="submit" class="btn">Guardar</button>
</form>

<!-- 🔽 Botón para mostrar / ocultar la tabla -->
<button id="toggleUsuarios" class="btn" style="margin-top:2rem">
    Usuarios registrados
</button>

<!-- 🔽 Tabla inicialmente oculta -->
<div id="tablaUsuarios" style="display:none">
    <table>
        <thead>
            <tr>
                <th>ID</th><th>Nombre</th><th>Email</th><th>Tipo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u['id_usuario'] ?></td>
                    <td><?= htmlspecialchars($u['nombre']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['tipo_usuario']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById('toggleUsuarios').addEventListener('click', () => {
    const div = document.getElementById('tablaUsuarios');
    div.style.display = div.style.display === 'none' ? 'block' : 'none';
});
</script>

</body>
</html>