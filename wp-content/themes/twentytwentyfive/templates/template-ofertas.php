<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto - Ofertas</title>
</head>
<body>
<?php
get_header();
$conexion = conexionBD();

$nombre_filtro = isset($_GET['titulo']) ? $_GET['titulo'] : '';
$codigo_empresa_filtro = isset($_GET['codigo_empresa']) ? $_GET['codigo_empresa'] : '';
$otros_filtro = isset($_GET['otros']) ? $_GET['otros'] : ''; 
$cursos_filtro = [];
$params = [];
$types = "";

$cursos = ['ASIR', 'DAW', 'DAM', 'SMR', 'VIDEOJUEGOS'];

$sql = "SELECT id, codigo_empresa, titulo, descripcion, ASIR, DAW, DAM, SMR, VIDEOJUEGOS, OTROS, fecha_caducidad FROM ofertas WHERE 1=1";
if (!empty($nombre_filtro)) {
    $sql .= " AND titulo LIKE ?";
    $params[] = "%$nombre_filtro%";
    $types .= "s";
}

if (!empty($codigo_empresa_filtro)) {
    $sql .= " AND codigo_empresa LIKE ?";
    $params[] = "%$codigo_empresa_filtro%";
    $types .= "s";
}

if (!empty($otros_filtro)) {
    $sql .= " AND OTROS LIKE ?";
    $params[] = "%$otros_filtro%";
    $types .= "s";
}

foreach ($cursos as $curso) {
    if (isset($_GET[$curso])) {
        $cursos_filtro[] = "$curso = 1"; 
    }
}

if (!empty($cursos_filtro)) {
    $sql .= " AND (" . implode(" OR ", $cursos_filtro) . ")";
}

$stmt = $conexion->prepare($sql);

if (!empty($types)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$resultado = $stmt->get_result();

echo '<div class="container">';
echo '<h2>Lista de Ofertas</h2>';
echo '<form method="GET" class="form-filtro">';
echo '<label for="titulo">Título:</label>';
echo '<input type="text" name="titulo" id="titulo" placeholder="Título de la oferta" value="' . esc_attr($_GET['titulo'] ?? '') . '">';
echo '<label for="codigo_empresa">Código Empresa:</label>';
echo '<input type="text" name="codigo_empresa" id="codigo_empresa" placeholder="Código de la empresa" value="' . esc_attr($_GET['codigo_empresa'] ?? '') . '">';

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
echo '<a class="btn-mostrar" href="' . home_url() . '/nueva-oferta">Añadir oferta</a>';

if ($resultado->num_rows > 0) {
    echo '<table border="1" class="tabla tabla-ofertas">';
    echo '<thead><tr><th>ID</th><th>Código Empresa</th><th>Título</th><th>Descripción</th><th>ASIR</th><th>DAW</th><th>DAM</th><th>SMR</th><th>VIDEOJUEGOS</th><th>Otros</th><th>Editar</th><th>Eliminar</th></tr></thead>';
    echo '<tbody>';

    while ($row = $resultado->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . esc_html($row['id']) . '</td>';
        echo '<td>' . esc_html($row['codigo_empresa']) . '</td>';
        echo '<td>' . esc_html($row['titulo']) . '</td>';
        echo '<td>' . esc_html($row['descripcion']) . '</td>';
        echo '<td>' . ($row['ASIR'] ? 'Sí' : 'No') . '</td>';
        echo '<td>' . ($row['DAW'] ? 'Sí' : 'No') . '</td>';
        echo '<td>' . ($row['DAM'] ? 'Sí' : 'No') . '</td>';
        echo '<td>' . ($row['SMR'] ? 'Sí' : 'No') . '</td>';
        echo '<td>' . ($row['VIDEOJUEGOS'] ? 'Sí' : 'No') . '</td>';
        echo '<td>' . (!empty($row['OTROS']) ? esc_html($row['OTROS']) : 'No') . '</td>';
        echo '<td><a class="editar-btn" href="' . home_url() . '/editar-oferta?id=' . esc_html($row['id']) . '" class="btn-editar">Editar</a></td>';
        echo '<td>
                <form method="POST">
                    <input type="hidden" name="id_oferta" value="' . esc_html($row['id']) . '">
                    <button class="btn-eliminar" type="submit" name="eliminar" onclick="return confirm(\'¿Estás seguro de que quieres eliminar esta oferta?\')">Eliminar</button>
                </form>
              </td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
} else {
    echo '<h2>No se encontraron ofertas.</h2>';
}

echo '</div>';

$stmt->close();
$conexion->close();
?>
</body>
</html>