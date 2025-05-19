<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto - Editar Profesor</title>
</head>
<body>
<?php
    require_once get_template_directory() . '/includes/db-connection.php';
    get_header();
    $conexion = conexionBD();

    $id = isset($_GET['id']) ? $_GET['id'] : null;
    
    if ($id) {
        $sql = "SELECT nombre, apellidos, email, nombre_usuario, rol FROM profesores WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $profesor = $resultado->fetch_assoc();
        $stmt->close();

    if ($profesor === null) {
        echo "<h2 style='text-align:center;'>Profesor no encontrado.</h2>";
        exit;
    }

    $nombreProfesorAntiguo = $profesor['nombre'];
    $apellidosProfesorAntiguo = $profesor['apellidos'];
    $emailProfesorAntiguo = $profesor['email'];
    $usuarioProfesorAntiguo = $profesor['nombre_usuario'];
    $rolProfesorAntiguo = $profesor['rol'];

    if (is_null($nombreProfesorAntiguo) || is_null($apellidosProfesorAntiguo) || is_null($emailProfesorAntiguo) || is_null($usuarioProfesorAntiguo) || is_null($rolProfesorAntiguo)) {
        echo "<h2 style='text-align:center;'>Profesor no encontrado.</h2>";
        exit;
    }

    } else {
        echo "<h2 style='text-align:center;'>Profesor no encontrado.</h2>";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!isset($_POST['nombre-editar-profesor']) || trim($_POST['nombre-editar-profesor']) === '' || !isset($_POST['apellidos-editar-profesor']) || trim($_POST['apellidos-editar-profesor']) === '' || !isset($_POST['email-editar-profesor']) || trim($_POST['email-editar-profesor']) === '' || !isset($_POST['usuario-editar-profesor']) || trim($_POST['usuario-editar-profesor']) === '' || !isset($_POST['rol-editar-profesor']) || trim($_POST['rol-editar-profesor']) === '') {
            echo "<p class='error'>Todos los campos obligatorios deben estar completos. <a href='#' onclick='history.back(); return false;'>Volver atrás</a></p>";
            exit;
        }

        $nombreProfesor = sanitize_text_field($_POST['nombre-editar-profesor']);
        $apellidosProfesor = sanitize_text_field($_POST['apellidos-editar-profesor']);
        $emailProfesor = sanitize_email($_POST['email-editar-profesor']);
        $passwordProfesor = !empty($_POST['password-editar-profesor']) ? password_hash($_POST['password-editar-profesor'], PASSWORD_BCRYPT) : null;
        $usuarioProfesor = sanitize_text_field($_POST['usuario-editar-profesor']);
        $rolProfesor = sanitize_text_field($_POST['rol-editar-profesor']);        

        if ($passwordProfesor !== null && strlen($_POST['password-editar-profesor']) >= 8) {
            $sql = "UPDATE profesores SET nombre = ?, apellidos = ?, email = ?, nombre_usuario = ?, rol = ?, password = ? WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssssssi", $nombreProfesor, $apellidosProfesor, $emailProfesor, $usuarioProfesor, $rolProfesor, $passwordProfesor, $id);
        } else {
            $sql = "UPDATE profesores SET nombre = ?, apellidos = ?, email = ?, nombre_usuario = ?, rol = ? WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sssssi", $nombreProfesor, $apellidosProfesor, $emailProfesor, $usuarioProfesor, $rolProfesor, $id);
        }        

        if ($stmt->execute()) {
            echo "<p class='exito'>Profesor actualizado correctamente.</p>";
        } else {
            echo "<p class='error'>Error al actualizar el profesor.</p>";
        }

        $stmt->close();
        $conexion->close();
    }
?>

<main>
    <div class="container">
    <h2>Editando al profesor: <?php echo esc_attr($nombreProfesorAntiguo) . " " . esc_attr($apellidosProfesorAntiguo); ?></h2>
    
    <form class="form-container" method="POST">
    <div class="form-field">
        <label class="form-label" for="nombre-profesor">Nombre del profesor <b style='color: red;'>*</b></label>
        <input class="form-input" type="text" name="nombre-editar-profesor" id="nombre-profesor" value="<?php echo esc_attr($nombreProfesorAntiguo);?>" required />
    </div>
    
    <div class="form-field">
        <label class="form-label" for="apellidos-profesor">Apellidos del profesor <b style='color: red;'>*</b></label>
        <input class="form-input" type="text" name="apellidos-editar-profesor" id="apellidos-profesor" value="<?php echo esc_attr($apellidosProfesorAntiguo);?>" required />
    </div>
    
    <div class="form-field">
        <label class="form-label" for="email-profesor">Email del profesor <b style='color: red;'>*</b></label>
        <input class="form-input" type="email" name="email-editar-profesor" id="email-profesor" value="<?php echo esc_attr($emailProfesorAntiguo);?>" required />
    </div>

    <div class="form-field">
        <label class="form-label" for="password-profesor">Contraseña del profesor</label>
        <input class="form-input" type="password" name="password-editar-profesor" id="password-profesor" placeholder="Mínimo 8 caracteres" />
    </div>
    
    <div class="form-field">
        <label class="form-label" for="usuario-profesor">Nombre de usuario del profesor <b style='color: red;'>*</b></label>
        <input class="form-input" type="text" name="usuario-editar-profesor" id="usuario-profesor" value="<?php echo esc_attr($usuarioProfesorAntiguo);?>" required />
    </div>
    
    <div class="form-field">
        <label class="form-label" for="rol-profesor">Rol del profesor <b style='color: red;'>*</b></label>
        <select class="form-input" name="rol-editar-profesor" id="rol-profesor" required>
            <option value="admin">Administrador</option>
            <option value="estandar">Estándar</option>
        </select>
    </div>
    <span><b style='color: red;'>*</b> Campo obligatorio</span>
    
    <button class="form-button" type="submit">Editar profesor</button>
    <?php echo '<a class="form-button-cancel" href="' . home_url() . '/profesores">Volver</a>'; ?>
</form>
</div>
</main>
</body>
</html>