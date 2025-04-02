<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto - Alumnos</title>
</head>
<body>
<?php
get_header();
require_once get_template_directory() . '/includes/db-connection.php';

$conexion = conexionBD();

$nombre_filtro = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$email_filtro = isset($_GET['email']) ? $_GET['email'] : '';
$telefono_filtro = isset($_GET['telefono']) ? $_GET['telefono'] : '';
$otros_filtro = isset($_GET['otros']) ? $_GET['otros'] : '';
$cursos = ['ASIR', 'DAW', 'DAM', 'SMR', 'VIDEOJUEGOS'];
$curso_filtro = [];
$params = [];
$types = "";

$sql = "SELECT id, nombre, apellidos, email, telefono, ASIR, DAW, DAM, SMR, VIDEOJUEGOS, OTROS FROM alumnos WHERE 1=1";

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
if (!empty($telefono_filtro)) {
    $sql .= " AND telefono LIKE ?";
    $params[] = "%$telefono_filtro%";
    $types .= "s";
}

if (!empty($otros_filtro)) {
    $sql .= " AND OTROS LIKE ?";
    $params[] = "%$otros_filtro%";
    $types .= "s";
}

foreach ($cursos as $curso) {
    if (isset($_GET[$curso])) {
        $curso_filtro[] = "$curso = 1";
    }
}
if (!empty($curso_filtro)) {
    $sql .= " AND (" . implode(" OR ", $curso_filtro) . ")";
}

$stmt = $conexion->prepare($sql);
if (!empty($types)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$resultado = $stmt->get_result();

echo '<div class="container">';
echo '<h2>Lista de Alumnos</h2>';

echo '<form method="GET" class="form-filtro">';
echo '<label for="nombre">Nombre:</label>';
echo '<input type="text" name="nombre" id="nombre" placeholder="Nombre del alumno" value="' . esc_attr($_GET['nombre'] ?? '') . '">';
echo '<label for="email">Email:</label>';
echo '<input type="text" name="email" id="email" placeholder="Email del alumno" value="' . esc_attr($_GET['email'] ?? '') . '">';
echo '<label for="telefono">Teléfono:</label>';
echo '<input type="text" name="telefono" id="telefono" placeholder="Teléfono del alumno" value="' . esc_attr($_GET['telefono'] ?? '') . '">';

echo '<fieldset><legend>Filtrar por Curso:</legend>';
foreach ($cursos as $curso) {
    echo '<label><input type="checkbox" name="' . $curso . '" ' . (isset($_GET[$curso]) ? 'checked' : '') . '> ' . $curso . '</label> ';
}
echo '<label for="otros">OTROS:</label>';
echo '<input type="text" name="otros" id="otros" value="' . esc_attr($_GET['otros'] ?? '') . '">';

echo '</fieldset>';

echo '<div class="container-filtros">';
echo '<button type="submit" class="btn-filtrar">Filtrar</button>';
echo '<button type="reset" class="btn-filtrar" onclick="window.location.href=window.location.pathname;">Limpiar filtros</button>';
echo '</div>';
echo '</form>';
echo '<a class="btn-mostrar" href="' . home_url() . '/nuevo-alumno">Añadir alumno</a>';

if ($resultado->num_rows > 0) {
    echo '<table border="1" class="tabla tabla-alumnos">';
    echo '<thead><tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Email</th><th>Teléfono</th><th>ASIR</th><th>DAW</th><th>DAM</th><th>SMR</th><th>VIDEOJUEGOS</th><th>Otros</th><th>Editar</th><th>Eliminar</th></tr></thead>';
    echo '<tbody>';
    while ($row = $resultado->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . esc_html($row['id']) . '</td>';
        echo '<td>' . esc_html($row['nombre']) . '</td>';
        echo '<td>' . esc_html($row['apellidos']) . '</td>';
        echo '<td>' . esc_html($row['email']) . '</td>';
        echo '<td>' . esc_html($row['telefono']) . '</td>';
        echo '<td>' . ($row['ASIR'] ? 'Sí' : 'No') . '</td>';
        echo '<td>' . ($row['DAW'] ? 'Sí' : 'No') . '</td>';
        echo '<td>' . ($row['DAM'] ? 'Sí' : 'No') . '</td>';
        echo '<td>' . ($row['SMR'] ? 'Sí' : 'No') . '</td>';
        echo '<td>' . ($row['VIDEOJUEGOS'] ? 'Sí' : 'No') . '</td>';
        echo '<td>' . (!empty($row['OTROS']) ? esc_html($row['OTROS']) : 'No') . '</td>';
        
        echo '<td><a class="editar-btn" href="' . home_url() . '/editar-alumno?id=' . esc_html($row['id']) . '">Editar</a></td>';
        echo '<td>
                <form method="POST">
                    <input type="hidden" name="id_alumno" value="' . esc_html($row['id']) . '">
                    <button class="btn-eliminar" type="submit" name="eliminar" onclick="return confirm(\'¿Estás seguro de que quieres eliminar este alumno?\')">Eliminar</button>
                </form>
              </td>';
        echo '</tr>';
    }
} else {
    echo '<h2>No se encontraron alumnos.</h2>';
}

echo '</tbody>';
echo '</table>';
echo '</div>';

$stmt->close();
$conexion->close();
?>
</body>
</html>