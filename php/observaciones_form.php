<?php
require 'db.php';

$mensaje = isset($_GET['success']) ? '✅ Observación registrada.' : '';

$usuarios = $pdo->query("SELECT id_usuario, nombre FROM Usuario")->fetchAll();
$especies = $pdo->query("SELECT id_especie, nombre_comun FROM EspecieMarina")->fetchAll();
$zonas    = $pdo->query("SELECT id_zona, nombre FROM ZonaMarina")->fetchAll();

$observaciones = $pdo->query("
    SELECT o.id_observacion, o.fecha, u.nombre AS usuario, e.nombre_comun AS especie, z.nombre AS zona
    FROM Observacion o
    JOIN Usuario u ON o.id_usuario = u.id_usuario
    JOIN EspecieMarina e ON o.id_especie = e.id_especie
    JOIN ZonaMarina z ON o.id_zona = z.id_zona
    ORDER BY o.id_observacion DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Observación</title>
    <style>
        body{font-family:sans-serif;max-width:700px;margin:2rem auto}
        input,select{padding:.4rem;margin:.2rem 0;width:100%}
        .btn{padding:.6rem 1.2rem;border:none;border-radius:4px;background:#0d6efd;color:#fff;cursor:pointer}
        .btn-small {
            display: inline-block;
            padding: .3rem .6rem;
            margin: .1rem 0;
            font-size: 0.9rem;
            background: #6c757d;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn-small:hover {
            background: #5a6268;
        }
        table{width:100%;border-collapse:collapse;margin-top:1rem}
        th,td{border:1px solid #ccc;padding:.4rem;text-align:left}
        th{background:#f4f4f4}
        .msg{background:#e7ffe7;border:1px solid #8f8;padding:.5rem;margin-bottom:1rem}
    </style>
</head>
<body>

<h2>Registrar observación</h2>

<?php if ($mensaje): ?>
    <div class="msg"><?= $mensaje ?></div>
<?php endif; ?>

<form action="observaciones_insertar.php" method="POST">
    <label>Fecha
        <input type="date" name="fecha" required>
    </label><br>

    <label>Usuario
        <select name="id_usuario" required>
            <option value="">Seleccione un usuario</option>
            <?php foreach ($usuarios as $u): ?>
                <option value="<?= $u['id_usuario'] ?>"><?= htmlspecialchars($u['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>

    <label>Especie Marina
        <select name="id_especie" required>
            <option value="">Seleccione una especie</option>
            <?php foreach ($especies as $e): ?>
                <option value="<?= $e['id_especie'] ?>"><?= htmlspecialchars($e['nombre_comun']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>

    <label>Zona Marina
        <select name="id_zona" required>
            <option value="">Seleccione una zona</option>
            <?php foreach ($zonas as $z): ?>
                <option value="<?= $z['id_zona'] ?>"><?= htmlspecialchars($z['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>

    <button type="submit" class="btn">Guardar Observación</button>
</form>

<h2>Observaciones registradas</h2>
<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Usuario</th>
            <th>Especie</th>
            <th>Zona</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($observaciones as $obs): ?>
            <tr>
                <td><?= $obs['fecha'] ?></td>
                <td><?= htmlspecialchars($obs['usuario']) ?></td>
                <td><?= htmlspecialchars($obs['especie']) ?></td>
                <td><?= htmlspecialchars($obs['zona']) ?></td>
                <td>
                    <a class="btn-small" href="observaciones_editar.php?id=<?= $obs['id_observacion'] ?>">✏️ Editar</a>
                    <a class="btn-small" href="observaciones_eliminar.php?id=<?= $obs['id_observacion'] ?>"
                       onclick="return confirm('¿Eliminar esta observación?')">🗑️ Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<hr>
<h2>Consultas adicionales</h2>

<?php
$total = $pdo->query("SELECT COUNT(*) AS total FROM Observacion")->fetch();
?>
<p><strong>Total de observaciones:</strong> <?= $total['total'] ?></p>

<h3>Últimas 3 observaciones</h3>
<?php
$ultimas = $pdo->query("
    SELECT o.fecha, u.nombre AS usuario, e.nombre_comun AS especie
    FROM Observacion o
    JOIN Usuario u ON o.id_usuario = u.id_usuario
    JOIN EspecieMarina e ON o.id_especie = e.id_especie
    ORDER BY o.fecha DESC
    LIMIT 3
")->fetchAll();
?>
<ul>
    <?php foreach ($ultimas as $u): ?>
        <li><?= $u['fecha'] ?> – <?= htmlspecialchars($u['usuario']) ?> observó <?= htmlspecialchars($u['especie']) ?></li>
    <?php endforeach; ?>
</ul>

<h3>Observaciones por especie</h3>
<?php
$porEspecie = $pdo->query("
    SELECT e.nombre_comun, COUNT(*) AS cantidad
    FROM Observacion o
    JOIN EspecieMarina e ON o.id_especie = e.id_especie
    GROUP BY e.nombre_comun
    ORDER BY cantidad DESC
")->fetchAll();
?>
<table>
    <thead>
        <tr><th>Especie</th><th>Número de observaciones</th></tr>
    </thead>
    <tbody>
        <?php foreach ($porEspecie as $e): ?>
            <tr>
                <td><?= htmlspecialchars($e['nombre_comun']) ?></td>
                <td><?= $e['cantidad'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Filtrar observaciones por usuario</h3>
<form method="GET">
    <select name="filtrar_usuario" onchange="this.form.submit()">
        <option value="">Seleccione un usuario</option>
        <?php foreach ($usuarios as $u): ?>
            <option value="<?= $u['id_usuario'] ?>" <?= ($_GET['filtrar_usuario'] ?? '') == $u['id_usuario'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($u['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<?php if (!empty($_GET['filtrar_usuario'])):
    $filtrado = $pdo->prepare("
        SELECT o.fecha, e.nombre_comun, z.nombre AS zona
        FROM Observacion o
        JOIN EspecieMarina e ON o.id_especie = e.id_especie
        JOIN ZonaMarina z ON o.id_zona = z.id_zona
        WHERE o.id_usuario = :id
    ");
    $filtrado->execute([':id' => $_GET['filtrar_usuario']]);
    $resultados = $filtrado->fetchAll();
?>
    <ul>
        <?php foreach ($resultados as $obs): ?>
            <li><?= $obs['fecha'] ?> – Vió <?= htmlspecialchars($obs['nombre_comun']) ?> en <?= htmlspecialchars($obs['zona']) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<a class="back-link" href="index.php">⬅️ Volver al inicio</a>

</body>
</html>