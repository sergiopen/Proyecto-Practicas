<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto - Empresas</title>
</head>
<body>
<?php
get_header();
$conexion = conexionBD();

$nombre_filtro = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$email_filtro = isset($_GET['email']) ? $_GET['email'] : '';
$codigo_empresa_filtro = isset($_GET['codigo_empresa']) ? $_GET['codigo_empresa'] : ''; // Filtro para código de empresa
$cursos = ['ASIR', 'DAW', 'DAM', 'SMR', 'VIDEOJUEGOS'];
$curso_filtro = [];
$params = [];
$types = "";

$sql = "SELECT id, nombre, direccion, telefono, email, telefono_contacto, codigo_empresa FROM empresas WHERE 1=1";

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

if (!empty($codigo_empresa_filtro)) {
    $sql .= " AND codigo_empresa LIKE ?";
    $params[] = "%$codigo_empresa_filtro%";
    $types .= "s";
}

$stmt = $conexion->prepare($sql);

if (!empty($types)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$resultado = $stmt->get_result();

echo '<div class="container">';
echo '<h2>Lista de Empresas</h2>';
echo '<form method="GET" class="form-filtro">';
echo '<label for="nombre">Nombre:</label>';
echo '<input type="text" name="nombre" id="nombre" placeholder="Nombre de la empresa" value="' . esc_attr($_GET['nombre'] ?? '') . '">';
echo '<label for="email">Email:</label>';
echo '<input type="text" name="email" id="email" placeholder="Email de la empresa" value="' . esc_attr($_GET['email'] ?? '') . '">';
echo '<label for="codigo_empresa">Código Empresa:</label>';
echo '<input type="text" name="codigo_empresa" id="codigo_empresa" placeholder="Código de la empresa" value="' . esc_attr($_GET['codigo_empresa'] ?? '') . '">';
echo '<div class="container-filtros">';
echo '<button type="submit" class="btn-filtrar">Filtrar</button>';
echo '<button type="reset" class="btn-filtrar" onclick="window.location.href=window.location.pathname;">Limpiar filtros</button>';
echo '</div>';
echo '</form>';

echo '<a class="btn-mostrar" href="' . home_url() . '/nueva-empresa">Añadir empresa</a>';

if ($resultado->num_rows > 0) {
    echo '<table border="1" class="tabla tabla-empresas">';
    echo '<thead><tr><th>ID</th><th>Código Empresa</th><th>Nombre</th><th>Dirección</th><th>Teléfono</th><th>Email</th><th>Teléfono de Contacto</th><th>Editar</th><th>Eliminar</th></tr></thead>';
    echo '<tbody>';
    
    while ($row = $resultado->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . esc_html($row['id']) . '</td>';
        echo '<td>' . esc_html($row['codigo_empresa']) . '</td>';
        echo '<td>' . esc_html($row['nombre']) . '</td>';
        echo '<td>' . esc_html($row['direccion']) . '</td>';
        echo '<td>' . esc_html($row['telefono']) . '</td>';
        echo '<td>' . esc_html($row['email']) . '</td>';
        echo '<td>' . esc_html($row['telefono_contacto']) . '</td>';
        echo '<td><a class="editar-btn" href="' . home_url() . '/editar-empresa?id=' . esc_html($row['id']) . '" class="btn-editar">Editar</a></td>';
        echo '<td>
                <form method="POST">
                    <input type="hidden" name="id_empresa" value="' . esc_html($row['id']) . '">
                    <button class="btn-eliminar" type="submit" name="eliminar" onclick="return confirm(\'¿Estás seguro de que quieres eliminar esta empresa?\')">Eliminar</button>
                </form>
              </td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
} else {
    echo '<h2>No se encontraron empresas.</h2>';
}

echo '</div>';

$stmt->close();
$conexion->close();
?>
</body>
</html>