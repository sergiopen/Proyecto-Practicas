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

$conexion = conexionBD();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion_multiple']) && isset($_POST['alumnos_seleccionados'])) {
    $ids = $_POST['alumnos_seleccionados'];
    if (!empty($ids)) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $types = str_repeat('i', count($ids));
        $stmt_delete = $conexion->prepare("DELETE FROM alumnos WHERE id IN ($placeholders)");
        $stmt_delete->bind_param($types, ...$ids);
        $stmt_delete->execute();
        $stmt_delete->close();
        echo "<script>window.location.href=window.location.href;</script>";
        exit;
    }
}

$nombre_filtro = $_GET['nombre'] ?? '';
$email_filtro = $_GET['email'] ?? '';
$telefono_filtro = $_GET['telefono'] ?? '';
$otros_filtro = $_GET['otros'] ?? '';
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

echo '<a class="btn-mostrar" href="' . home_url() . '/crear-alumno">Añadir alumno</a>';

if ($resultado->num_rows > 0) {
    echo '<form method="POST">';
    echo '<table border="1" class="tabla tabla-alumnos">';
    echo '<thead><tr><th><input type="checkbox" id="select-all"></th><th>ID</th><th>Nombre</th><th>Apellido</th><th>Email</th><th>Teléfono</th><th>ASIR</th><th>DAW</th><th>DAM</th><th>SMR</th><th>VIDEOJUEGOS</th><th>Otros</th><th>Editar</th><th>Eliminar</th></tr></thead>';
    echo '<tbody>';
    while ($row = $resultado->fetch_assoc()) {
        echo '<tr>';
        echo '<td><input type="checkbox" name="alumnos_seleccionados[]" value="' . esc_attr($row['id']) . '"></td>';
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
                <form method="POST" onsubmit="return confirm(\'¿Estás seguro de que quieres eliminar este alumno?\')">
                    <input type="hidden" name="accion_multiple" value="1">
                    <input type="hidden" name="alumnos_seleccionados[]" value="' . esc_attr($row['id']) . '">
                    <button class="btn-eliminar" type="submit">Eliminar</button>
                </form>
              </td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
    echo '<button class="btn-filtrar" type="submit" name="accion_multiple" value="1" onclick="return confirm(\'¿Estás seguro de que quieres eliminar los alumnos seleccionados?\')">Eliminar seleccionados</button>';
    echo '</form>';
} else {
    echo '<h2>No se encontraron alumnos.</h2>';
}

echo '</div>';

$stmt->close();
$conexion->close();
?>

<script>
document.getElementById('select-all')?.addEventListener('change', function () {
    const checkboxes = document.querySelectorAll('input[name="alumnos_seleccionados[]"]');
    checkboxes.forEach(chk => chk.checked = this.checked);
});
</script>

</body>
</html>
