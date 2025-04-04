<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto - Editar Alumno</title>
</head>
<body>
<?php
    require_once get_template_directory() . '/includes/db-connection.php';
    get_header();
    $conexion = conexionBD();

    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if ($id) {
        $sql = "SELECT nombre, apellidos, email, telefono, asir, daw, dam, smr, videojuegos, otros FROM alumnos WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $alumno = $resultado->fetch_assoc();
        $stmt->close();

        if ($alumno === null) {
            echo "<h2 style='text-align:center;'>Alumno no encontrado.</h2>";
            exit;
        }

        $nombreAlumnoAntiguo = $alumno['nombre'];
        $apellidosAlumnoAntiguo = $alumno['apellidos'];
        $emailAlumnoAntiguo = $alumno['email'];
        $telefonoAlumnoAntiguo = $alumno['telefono'];
        $asirAlumnoAntiguo = $alumno['asir'];
        $dawAlumnoAntiguo = $alumno['daw'];
        $damAlumnoAntiguo = $alumno['dam'];
        $smrAlumnoAntiguo = $alumno['smr'];
        $videojuegosAlumnoAntiguo = $alumno['videojuegos'];
        $otrosAlumnoAntiguo = $alumno['otros'];

    } else {
        echo "<h2 style='text-align:center;'>Alumno no encontrado.</h2>";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!isset($_POST['nombre-editar-alumno']) || trim($_POST['nombre-editar-alumno']) === '' || 
            !isset($_POST['apellidos-editar-alumno']) || trim($_POST['apellidos-editar-alumno']) === '' || 
            !isset($_POST['email-editar-alumno']) || trim($_POST['email-editar-alumno']) === '') {
            echo "<p class='error'>Todos los campos obligatorios deben estar completos. <a href='#' onclick='history.back(); return false;'>Volver atrás</a></p>";
            exit;
        }

        $nombreAlumno = sanitize_text_field($_POST['nombre-editar-alumno']);
        $apellidosAlumno = sanitize_text_field($_POST['apellidos-editar-alumno']);
        $emailAlumno = sanitize_email($_POST['email-editar-alumno']);
        $telefonoAlumno = sanitize_text_field($_POST['telefono-editar-alumno']);
        $asir = isset($_POST['asir']) ? 1 : 0;
        $daw = isset($_POST['daw']) ? 1 : 0;
        $dam = isset($_POST['dam']) ? 1 : 0;
        $smr = isset($_POST['smr']) ? 1 : 0;
        $videojuegos = isset($_POST['videojuegos']) ? 1 : 0;
        $otros = sanitize_text_field($_POST['otros-editar-alumno']);

        $sql = "UPDATE alumnos SET nombre = ?, apellidos = ?, email = ?, telefono = ?, asir = ?, daw = ?, dam = ?, smr = ?, videojuegos = ?, otros = ? WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssiiiiisi", $nombreAlumno, $apellidosAlumno, $emailAlumno, $telefonoAlumno, $asir, $daw, $dam, $smr, $videojuegos, $otros, $id);

        if ($stmt->execute()) {
            echo "<p class='exito'>Alumno actualizado correctamente.</p>";
        } else {
            echo "<p class='error'>Error al actualizar el alumno.</p>";
        }

        $stmt->close();
        $conexion->close();
    }
?>

<main>
    <div class="container">
    <h2>Editando al alumno: <?php echo esc_attr($nombreAlumnoAntiguo) . " " . esc_attr($apellidosAlumnoAntiguo); ?></h2>
    
    <form class="form-container" method="POST">
        <div class="form-field">
            <label class="form-label" for="nombre-alumno">Nombre del alumno <b style='color: red;'>*</b></label>
            <input class="form-input" type="text" name="nombre-editar-alumno" id="nombre-alumno" value="<?php echo esc_attr($nombreAlumnoAntiguo);?>" required />
        </div>

        <div class="form-field">
            <label class="form-label" for="apellidos-alumno">Apellidos del alumno <b style='color: red;'>*</b></label>
            <input class="form-input" type="text" name="apellidos-editar-alumno" id="apellidos-alumno" value="<?php echo esc_attr($apellidosAlumnoAntiguo);?>" required />
        </div>

        <div class="form-field">
            <label class="form-label" for="email-alumno">Email del alumno <b style='color: red;'>*</b></label>
            <input class="form-input" type="email" name="email-editar-alumno" id="email-alumno" value="<?php echo esc_attr($emailAlumnoAntiguo);?>" required />
        </div>

        <div class="form-field">
            <label class="form-label" for="telefono-alumno">Teléfono del alumno <b style='color: red;'>*</b></label>
            <input class="form-input" type="text" name="telefono-editar-alumno" id="telefono-alumno" value="<?php echo esc_attr($telefonoAlumnoAntiguo);?>" />
        </div>

        <div class="form-field">
            <label class="form-label">Cursos:</label>
            <label><input type="checkbox" name="asir" <?php echo ($asirAlumnoAntiguo) ? 'checked' : ''; ?> /> ASIR</label>
            <label><input type="checkbox" name="daw" <?php echo ($dawAlumnoAntiguo) ? 'checked' : ''; ?> /> DAW</label>
            <label><input type="checkbox" name="dam" <?php echo ($damAlumnoAntiguo) ? 'checked' : ''; ?> /> DAM</label>
            <label><input type="checkbox" name="smr" <?php echo ($smrAlumnoAntiguo) ? 'checked' : ''; ?> /> SMR</label>
            <label><input type="checkbox" name="videojuegos" <?php echo ($videojuegosAlumnoAntiguo) ? 'checked' : ''; ?> /> Videojuegos</label>
        </div>

        <div class="form-field">
            <label class="form-label" for="otros-alumno">Otro curso:</label>
            <input class="form-input" type="text" name="otros-editar-alumno" id="otros-alumno" value="<?php echo esc_attr($otrosAlumnoAntiguo); ?>" />
        </div>

        <span><b style='color: red;'>*</b> Campo obligatorio</span>

        <button class="form-button" type="submit">Editar alumno</button>
    </form>
    </div>
</main>
</body>
</html>
