<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto - Profesores</title>
</head>
<body>
<?php
get_header();
require_once get_template_directory() . '/includes/db-connection.php';

$conexion = conexionBD();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar']) && isset($_POST['id_profesor'])) {
    $id = intval($_POST['id_profesor']);
    $stmt = $conexion->prepare("DELETE FROM profesores WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_multiple']) && !empty($_POST['profesores_seleccionados'])) {
    $ids = array_map('intval', $_POST['profesores_seleccionados']);
    if (count($ids) > 0) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $tipos = str_repeat('i', count($ids));
        $sql_del_multi = "DELETE FROM profesores WHERE id IN ($placeholders)";
        $stmt_del_multi = $conexion->prepare($sql_del_multi);
        if ($stmt_del_multi) {
            $stmt_del_multi->bind_param($tipos, ...$ids);
            $stmt_del_multi->execute();
            $stmt_del_multi->close();
        }
    }
}

$nombre_filtro = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$email_filtro = isset($_GET['email']) ? $_GET['email'] : '';

$sql = "SELECT id, nombre_usuario, nombre, apellidos, email, rol FROM profesores WHERE 1=1";
$params = [];
$types = "";

if (!empty($nombre_filtro)) {
    $sql .= " AND nombre LIKE ?";
    $params[] = "%$nombre_filtro%";
    $types .= "s";
}

if (!empty($email_filtro)) {
    $sql .= " AND email LIKE ?";
    $params[] = "%$email_filtro%";
    $types .= "s";
}

$stmt = $conexion->prepare($sql);
if (!empty($types)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$resultado = $stmt->get_result();

echo '<div class="container">';
echo '<h2>Lista de Profesores</h2>';

echo '<form method="GET" class="form-filtro">';
echo '<label for="nombre">Nombre:</label>';
echo '<input type="text" name="nombre" id="nombre" placeholder="Nombre del profesor" value="' . esc_attr($_GET['nombre'] ?? '') . '">';
echo '<label for="email">Email:</label>';
echo '<input type="text" name="email" id="email" placeholder="Email del profesor" value="' . esc_attr($_GET['email'] ?? '') . '">';
echo '<div class="container-filtros">';
echo '<button type="submit" class="btn-filtrar">Filtrar</button>';
echo '<button type="reset" class="btn-filtrar" onclick="window.location.href=window.location.pathname;">Limpiar filtros</button>';
echo '</div>';
echo '</form>';

echo '<a class="btn-mostrar" href="' . home_url() . '/crear-profesor">Añadir profesor</a>';

if ($resultado->num_rows > 0) {
    echo '<form method="POST" onsubmit="return confirm(\'¿Estás seguro de que quieres eliminar los profesores seleccionados?\');">';
    echo '<table border="1" class="tabla tabla-profesores">';
    echo '<thead><tr>';
    echo '<th><input type="checkbox" id="checkTodos"></th>';
    echo '<th>ID</th><th>Nombre de usuario</th><th>Nombre</th><th>Apellido</th><th>Email</th><th>Rol</th><th>Editar</th><th>Eliminar</th>';
    echo '</tr></thead>';
    echo '<tbody>';

    while ($row = $resultado->fetch_assoc()) {
        echo '<tr>';
        echo '<td><input type="checkbox" name="profesores_seleccionados[]" value="' . esc_html($row['id']) . '"></td>';
        echo '<td>' . esc_html($row['id']) . '</td>';
        echo '<td>' . esc_html($row['nombre_usuario']) . '</td>';
        echo '<td>' . esc_html($row['nombre']) . '</td>';
        echo '<td>' . esc_html($row['apellidos']) . '</td>';
        echo '<td>' . esc_html($row['email']) . '</td>';
        echo '<td>' . esc_html($row['rol']) . '</td>';
        echo '<td><a class="editar-btn" href="' . home_url() . '/editar-profesor?id=' . esc_html($row['id']) . '">Editar</a></td>';
        echo '<td>
                <form method="POST" onsubmit="return confirm(\'¿Estás seguro de que quieres eliminar este profesor?\');">
                    <input type="hidden" name="id_profesor" value="' . esc_html($row['id']) . '">
                    <button class="btn-eliminar" type="submit" name="eliminar">Eliminar</button>
                </form>
              </td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '<button type="submit" name="eliminar_multiple" class="btn-filtrar btn-eliminar-multiple">Eliminar seleccionados</button>';
    echo '</form>';
} else {
    echo '<h2>No se encontraron profesores.</h2>';
}

echo '</div>';

$stmt->close();
$conexion->close();
?>
<script>
document.getElementById('checkTodos').addEventListener('change', function() {
    let checkboxes = document.querySelectorAll('input[name="profesores_seleccionados[]"]');
    checkboxes.forEach(cb => cb.checked = this.checked);
});
</script>
</body>
</html>
