<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Proyecto - Empresas</title>
</head>
<body>
<?php
get_header();
$conexion = conexionBD();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['eliminar_multiples']) && !empty($_POST['ids_empresas'])) {
        $ids = $_POST['ids_empresas'];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $tipos = str_repeat('i', count($ids));
        $sql_delete = "DELETE FROM empresas WHERE id IN ($placeholders)";
        $stmt_delete = $conexion->prepare($sql_delete);

        $bind_names[] = $tipos;
        for ($i = 0; $i < count($ids); $i++) {
            $bind_names[] = &$ids[$i];
        }

        call_user_func_array([$stmt_delete, 'bind_param'], $bind_names);
        $stmt_delete->execute();
        $stmt_delete->close();
    } elseif (isset($_POST['eliminar']) && isset($_POST['id_empresa'])) {
        $idEliminar = (int) $_POST['id_empresa'];
        $sql_delete = "DELETE FROM empresas WHERE id = ?";
        $stmt_delete = $conexion->prepare($sql_delete);
        $stmt_delete->bind_param('i', $idEliminar);
        $stmt_delete->execute();
        $stmt_delete->close();
    }
}

$nombre_filtro = $_GET['nombre'] ?? '';
$email_filtro = $_GET['email'] ?? '';
$codigo_empresa_filtro = $_GET['codigo_empresa'] ?? '';
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
echo '<input type="text" name="nombre" id="nombre" placeholder="Nombre de la empresa" value="' . esc_attr($nombre_filtro) . '">';
echo '<label for="email">Email:</label>';
echo '<input type="text" name="email" id="email" placeholder="Email de la empresa" value="' . esc_attr($email_filtro) . '">';
echo '<label for="codigo_empresa">Código Empresa:</label>';
echo '<input type="text" name="codigo_empresa" id="codigo_empresa" placeholder="Código de la empresa" value="' . esc_attr($codigo_empresa_filtro) . '">';
echo '<div class="container-filtros">';
echo '<button type="submit" class="btn-filtrar">Filtrar</button>';
echo '<button type="reset" class="btn-filtrar" onclick="window.location.href=window.location.pathname;">Limpiar filtros</button>';
echo '</div>';
echo '</form>';

echo '<a class="btn-mostrar" href="' . home_url() . '/crear-empresa">Añadir empresa</a>';

if ($resultado->num_rows > 0) {
    echo '<form method="POST" onsubmit="return confirm(\'¿Seguro que quieres eliminar las empresas seleccionadas?\');">';
    echo '<table border="1" class="tabla tabla-empresas">';
    echo '<thead><tr><th><input type="checkbox" id="checkTodos" onclick="toggleTodos(this)"></th><th>ID</th><th>Código Empresa</th><th>Nombre</th><th>Dirección</th><th>Teléfono</th><th>Email</th><th>Teléfono de Contacto</th><th>Editar</th><th>Eliminar</th><th>Crear Oferta</th></tr></thead>';
    echo '<tbody>';

    while ($row = $resultado->fetch_assoc()) {
        echo '<tr>';
        echo '<td><input type="checkbox" name="ids_empresas[]" value="' . esc_html($row['id']) . '"></td>';
        echo '<td>' . esc_html($row['id']) . '</td>';
        echo '<td>' . esc_html($row['codigo_empresa']) . '</td>';
        echo '<td>' . esc_html($row['nombre']) . '</td>';
        echo '<td>' . esc_html($row['direccion']) . '</td>';
        echo '<td>' . esc_html($row['telefono']) . '</td>';
        echo '<td>' . esc_html($row['email']) . '</td>';
        echo '<td>' . esc_html($row['telefono_contacto']) . '</td>';
        echo '<td><a class="editar-btn" href="' . home_url() . '/editar-empresa?id=' . esc_html($row['id']) . '">Editar</a></td>';
        echo '<td>
                <form method="POST" style="margin:0;">
                    <input type="hidden" name="id_empresa" value="' . esc_html($row['id']) . '">
                    <button class="btn-eliminar" type="submit" name="eliminar" onclick="return confirm(\'¿Estás seguro de que quieres eliminar esta empresa?\')">Eliminar</button>
                </form>
              </td>';
        echo '<td><a class="btn-enviar-correo" href="' . home_url() . '/crear-oferta?codigo_empresa=' . esc_html($row['codigo_empresa']) . '">Crear oferta</a></td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '<button type="submit" name="eliminar_multiples" class="btn-filtrar btn-eliminar-multiples">Eliminar seleccionadas</button>';
    echo '</form>';
} else {
    echo '<h2>No se encontraron empresas.</h2>';
}

echo '</div>';

$stmt->close();
$conexion->close();
?>

<script>
function toggleTodos(source) {
    const checkboxes = document.getElementsByName('ids_empresas[]');
    for(let i=0; i<checkboxes.length; i++) {
        checkboxes[i].checked = source.checked;
    }
}
</script>
</body>
</html>
