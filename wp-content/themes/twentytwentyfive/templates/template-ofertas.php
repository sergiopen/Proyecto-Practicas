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

echo '<a class="btn-mostrar" href="' . home_url() . '/crear-oferta">Añadir oferta</a>';

if ($resultado->num_rows > 0) {
    echo '<form method="POST" id="form-eliminar-masivo" onsubmit="return confirm(\'¿Estás seguro de que quieres eliminar las ofertas seleccionadas?\');">';
    echo '<table border="1" class="tabla tabla-ofertas">';
    echo '<thead><tr>';
    echo '<th><input type="checkbox" id="checkAll"></th>';
    echo '<th>ID</th><th>Código Empresa</th><th>Título</th><th>Descripción</th><th>ASIR</th><th>DAW</th><th>DAM</th><th>SMR</th><th>VIDEOJUEGOS</th><th>Otros</th><th>Fecha caducidad</th><th>Enviar Correo</th><th>Editar</th><th>Eliminar</th>';
    echo '</tr></thead>';
    echo '<tbody>';

    $fecha_actual = date('Y-m-d');

    while ($row = $resultado->fetch_assoc()) {
        $caducada = false;
        if (!empty($row['fecha_caducidad']) && $row['fecha_caducidad'] < $fecha_actual) {
            $caducada = true;
        }

        echo '<tr' . ($caducada ? ' style="background-color:#f8d7da;"' : '') . '>';
        echo '<td><input type="checkbox" name="ids_ofertas[]" value="' . esc_html($row['id']) . '"></td>';
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
        echo '<td>' . (!empty($row['fecha_caducidad']) ? esc_html($row['fecha_caducidad']) : 'Nunca') . ($caducada ? ' <strong style="color:red;">(Caducada)</strong>' : '') . '</td>';

        echo '<td>
                <form method="POST" style="margin:0;">
                    <input type="hidden" name="id_oferta_correo" value="' . esc_html($row['id']) . '">
                    <button type="submit" name="enviar_correo" class="btn-enviar-correo" onclick="return confirm(\'¿Estás seguro que quieres enviar un correo a todos los alumnos que cumplen con los cursos?\')">Enviar Correo</button>
                </form>
              </td>';

        echo '<td><a class="editar-btn" href="' . home_url() . '/editar-oferta?id=' . esc_html($row['id']) . '" class="btn-editar">Editar</a></td>';

        echo '<td>
                <form method="POST" style="margin:0;">
                    <input type="hidden" name="id_oferta" value="' . esc_html($row['id']) . '">
                    <button class="btn-eliminar" type="submit" name="eliminar" onclick="return confirm(\'¿Estás seguro de que quieres eliminar esta oferta?\')">Eliminar</button>
                </form>
              </td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '<button type="submit" name="eliminar_masivo" class="btn-filtrar btn-eliminar-masivo">Eliminar seleccionados</button>';
    echo '</form>';
} else {
    echo '<h2>No se encontraron ofertas.</h2>';
}

echo '</div>';

$stmt->close();
$conexion->close();

if (isset($_POST['eliminar_masivo']) && !empty($_POST['ids_ofertas']) && is_array($_POST['ids_ofertas'])) {
    $ids_to_delete = $_POST['ids_ofertas'];
    $conexion = conexionBD();

    $placeholders = implode(',', array_fill(0, count($ids_to_delete), '?'));
    $tipos = str_repeat('i', count($ids_to_delete));

    $sql_delete = "DELETE FROM ofertas WHERE id IN ($placeholders)";
    $stmt_delete = $conexion->prepare($sql_delete);
    $stmt_delete->bind_param($tipos, ...$ids_to_delete);
    if ($stmt_delete->execute()) {
        echo '<p class="exito">Ofertas eliminadas correctamente.</p>';
        echo '<script>window.location.href=window.location.href;</script>';
        exit;
    } else {
        echo '<p class="error">Error al eliminar las ofertas: ' . $conexion->error . '</p>';
    }
    $stmt_delete->close();
    $conexion->close();
}

if (isset($_POST['enviar_correo'])) {
    $id_oferta = $_POST['id_oferta_correo'];
    enviar_correo_a_usuarios($id_oferta);
}

function enviar_correo_a_usuarios($id_oferta) {
    $conexion = conexionBD();
    
    $sql_oferta = "SELECT * FROM ofertas WHERE id = ?";
    $stmt = $conexion->prepare($sql_oferta);
    $stmt->bind_param("i", $id_oferta);
    $stmt->execute();
    $resultado_oferta = $stmt->get_result();
    $oferta = $resultado_oferta->fetch_assoc();

    $codigo_empresa = $oferta['codigo_empresa'];

    $sql_empresa = "SELECT nombre, telefono, email FROM empresas WHERE codigo_empresa = ?";
    $stmt_empresa = $conexion->prepare($sql_empresa);
    $stmt_empresa->bind_param("s", $codigo_empresa);
    $stmt_empresa->execute();
    $resultado_empresa = $stmt_empresa->get_result();
    $empresa = $resultado_empresa->fetch_assoc();

    $nombre_empresa = $empresa['nombre'];
    $telefono_persona_contacto = $empresa['telefono'];
    $email_empresa = $empresa['email'];

    $cursos = [];
    if ($oferta['ASIR']) $cursos[] = 'ASIR = 1';
    if ($oferta['DAW']) $cursos[] = 'DAW = 1';
    if ($oferta['DAM']) $cursos[] = 'DAM = 1'; 
    if ($oferta['SMR']) $cursos[] = 'SMR = 1'; 
    if ($oferta['VIDEOJUEGOS']) $cursos[] = 'VIDEOJUEGOS = 1';

    if ($oferta['OTROS']) {
        $otros_cursos = explode(',', $oferta['OTROS']);
        foreach ($otros_cursos as $curso) {
            $curso_limpio = trim($curso);
            if (!empty($curso_limpio)) {
                $curso_limpio = $conexion->real_escape_string($curso_limpio);
                $cursos[] = "FIND_IN_SET('$curso_limpio', OTROS)";
            }
        }
    }

    if (!empty($cursos)) {
        $sql_usuarios = "SELECT email FROM alumnos WHERE " . implode(" OR ", $cursos);
        $resultado_usuarios = $conexion->query($sql_usuarios);

        if (!$resultado_usuarios) {
            echo 'Error en la consulta: ' . $conexion->error;
            return;
        }

        if ($resultado_usuarios->num_rows > 0) {
            $phpmailer = new PHPMailer\PHPMailer\PHPMailer();
            $phpmailer->isSMTP();
            $phpmailer->Host = 'smtp.gmail.com';
            $phpmailer->SMTPAuth = true;
            $phpmailer->Username = 'sergiopenasobrado@gmail.com'; 
            $phpmailer->Password = 'xhnzxrlmlfmuygnj'; 
            $phpmailer->SMTPSecure = 'tls';
            $phpmailer->Port = 587;
            $phpmailer->setFrom('sergiopenasobrado@gmail.com', 'Sergio');
            $phpmailer->Subject = 'IES Fuengirola nº1 - Oferta relevante para ti';
            
            $titulo_oferta = $oferta['titulo'];
            $descripcion_oferta = $oferta['descripcion']; 

            $cuerpo_email = "
                <p>¡Hola!</p>
                <p>Quizá te interese una nueva oferta: <strong>$titulo_oferta</strong>.</p>
                <p><strong>Descripción de la oferta:</strong> $descripcion_oferta</p>
                <p><strong>Nombre de la empresa: </strong>$nombre_empresa</p>
                <p><strong>Teléfono de contacto:</strong> $telefono_persona_contacto</p>
                <p><strong>Email de la empresa:</strong> $email_empresa</p>";

            while ($usuario = $resultado_usuarios->fetch_assoc()) {
                $phpmailer->addAddress($usuario['email']);
                $phpmailer->Body = $cuerpo_email;
                $phpmailer->isHTML(true);
                $phpmailer->send();
                $phpmailer->clearAddresses();
            }

            echo '<p class="exito">Correos enviados correctamente a los usuarios que coinciden con los cursos.</p>';
        } else {
            echo '<p class="error">No se encontraron usuarios que coincidan con esta oferta.</p>';
        }
    }

    $conexion->close();
}
?>

<script>
document.getElementById('checkAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input[name="ids_ofertas[]"]');
    checkboxes.forEach(chk => chk.checked = this.checked);
});
</script>
</body>
</html>
