<!DOCTYPE html>
<html lang="en">
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

$nombre_filtro = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$email_filtro = isset($_GET['email']) ? $_GET['email'] : '';

$sql = "SELECT id, nombre_usuario, nombre, apellidos, email, rol FROM profesores WHERE 1=1";

if (!empty($nombre_filtro)) {
    $sql .= " AND nombre LIKE ?";
}

if (!empty($email_filtro)) {
    $sql .= " AND email LIKE ?";
}

$stmt = $conexion->prepare($sql);

if (!empty($nombre_filtro) && !empty($email_filtro)) {
    $nombre_filtro = "%$nombre_filtro%";
    $email_filtro = "%$email_filtro%";
    $stmt->bind_param("ss", $nombre_filtro, $email_filtro);
} elseif (!empty($nombre_filtro)) {
    $nombre_filtro = "%$nombre_filtro%";
    $stmt->bind_param("s", $nombre_filtro);
} elseif (!empty($email_filtro)) {
    $email_filtro = "%$email_filtro%";
    $stmt->bind_param("s", $email_filtro);
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

echo '<a class="btn-mostrar" href="' . home_url() . '/nuevo-profesor">Añadir profesor</a>';

if ($resultado->num_rows > 0) {
    echo '<table border="1" class="tabla tabla-profesores">';
    echo '<thead><tr><th>ID</th><th>Nombre de usuario</th><th>Nombre</th><th>Apellido</th><th>Email</th><th>Rol</th><th>Editar</th><th>Eliminar</th></tr></thead>';
    echo '<tbody>';
    
    while ($row = $resultado->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . esc_html($row['id']) . '</td>';
        echo '<td>' . esc_html($row['nombre_usuario']) . '</td>';
        echo '<td>' . esc_html($row['nombre']) . '</td>';
        echo '<td>' . esc_html($row['apellidos']) . '</td>';
        echo '<td>' . esc_html($row['email']) . '</td>';
        echo '<td>' . esc_html($row['rol']) . '</td>';
        echo '<td><a class="editar-btn" href="' . home_url() . '/editar-profesor?id=' . esc_html($row['id']) . '" class="btn-editar">Editar</a></td>';
        echo '<td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_profesor" value="' . esc_html($row['id']) . '">
                    <button class="btn-eliminar" type="submit" name="eliminar" onclick="return confirm(\'¿Estás seguro de que quieres eliminar este profesor?\')">Eliminar</button>
                </form>
              </td>';
        echo '</tr>';
    }
    
    echo '</tbody>';
    echo '</table>';
} else {
    echo '<h2>No se encontraron profesores.</h2>';
}

echo '</div>';

$stmt->close();
$conexion->close();
?>
</body>
</html>