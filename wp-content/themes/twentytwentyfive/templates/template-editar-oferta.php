<?php
    require_once get_template_directory() . '/includes/db-connection.php';
    get_header();
    $conexion = conexionBD();

    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if ($id) {
        $sql = "SELECT id, codigo_empresa, titulo, descripcion, ASIR, VIDEOJUEGOS, DAM, DAW, SMR, fecha_caducidad, otros FROM ofertas WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $oferta = $resultado->fetch_assoc();
        $stmt->close();

        if ($oferta === null) {
            echo "<h2 style='text-align:center;'>Oferta no encontrada.</h2>";
            exit;
        }

        $codigoEmpresaAntiguo = $oferta['codigo_empresa'];
        $tituloAntiguo = $oferta['titulo'];
        $descripcionAntigua = $oferta['descripcion'];
        $asirAntiguo = $oferta['ASIR'];
        $videojuegosAntiguo = $oferta['VIDEOJUEGOS'];
        $damAntiguo = $oferta['DAM'];
        $dawAntiguo = $oferta['DAW'];
        $smrAntiguo = $oferta['SMR'];
        $fechaCaducidadAntigua = $oferta['fecha_caducidad'];
        $otrosAntiguo = $oferta['otros'];
    } else {
        echo "<h2 style='text-align:center;'>Oferta no encontrada.</h2>";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $codigoEmpresa = sanitize_text_field($_POST['codigo_empresa-editar']);
        $titulo = sanitize_text_field($_POST['titulo-editar']);
        $descripcion = sanitize_textarea_field($_POST['descripcion-editar']);
        $asir = isset($_POST['asir']) ? 1 : 0;
        $videojuegos = isset($_POST['videojuegos']) ? 1 : 0;
        $dam = isset($_POST['dam']) ? 1 : 0;
        $daw = isset($_POST['daw']) ? 1 : 0;
        $smr = isset($_POST['smr']) ? 1 : 0;
        $fechaCaducidad = isset($_POST['fecha_caducidad-editar']) ? $_POST['fecha_caducidad-editar'] : ''; // Fecha de caducidad
        $otros = sanitize_text_field($_POST['otros-editar']);
    
        if(empty($codigoEmpresa) || empty($titulo) || empty($descripcion)) {
            echo "<p class='error'>Todos los campos obligatorios deben estar completos. <a href='#' onclick='history.back(); return false;'>Volver atrás</a></p>";
            exit;
        }

        if ($fechaCaducidad !== $fechaCaducidadAntigua) {
            if(empty($fechaCaducidad)) {
                $fechaCaducidad = null;
            }

            $sqlFecha = "UPDATE ofertas SET fecha_caducidad = ? WHERE id = ?";
            $stmtFecha = $conexion->prepare($sqlFecha);

            if ($stmtFecha === false) {
                die("Error al preparar la consulta SQL: " . $conexion->error);
            }

            $stmtFecha->bind_param('si', $fechaCaducidad, $id);
            
            if($stmtFecha->execute()) {
                echo "<p class='exito'>Fecha de caducidad actualizada correctamente.</p>";
            } else {
                echo "<p class='error'>Error al actualizar la fecha de caducidad: " . $stmtFecha->error . "</p>";
            }

            $stmtFecha->close();
        }
    
        $sql = "UPDATE ofertas SET codigo_empresa = ?, titulo = ?, descripcion = ?, ASIR = ?, VIDEOJUEGOS = ?, DAM = ?, DAW = ?, SMR = ?, otros = ? WHERE id = ?";
        $stmt = $conexion->prepare($sql);
    
        if ($stmt === false) {
            die("Error al preparar la consulta SQL: " . $conexion->error);
        }
    
        $stmt->bind_param('sssiiiiisi', $codigoEmpresa, $titulo, $descripcion, $asir, $videojuegos, $dam, $daw, $smr, $otros, $id);
    
        if ($stmt->execute()) {
            echo "<p class='exito'>Oferta actualizada correctamente.</p>";
        } else {
            echo "<p class='error'>Error al actualizar la oferta: " . $stmt->error . "</p>";
        }
    
        $stmt->close();
        $conexion->close();
    }
    
?>

<main>
    <div class="container">
        <h2>Editando la oferta: <?php echo esc_attr($tituloAntiguo); ?></h2>

        <form class="form-container" method="POST">
            <div class="form-field">
                <label class="form-label" for="codigo_empresa">Código de la Empresa <b style='color: red;'>*</b></label>
                <input class="form-input" type="text" name="codigo_empresa-editar" id="codigo_empresa" value="<?php echo esc_attr($codigoEmpresaAntiguo);?>" required />
            </div>

            <div class="form-field">
                <label class="form-label" for="titulo">Título de la oferta <b style='color: red;'>*</b></label>
                <input class="form-input" type="text" name="titulo-editar" id="titulo" value="<?php echo esc_attr($tituloAntiguo);?>" required />
            </div>

            <div class="form-field">
                <label class="form-label" for="descripcion">Descripción de la oferta <b style='color: red;'>*</b></label>
                <textarea class="form-input" name="descripcion-editar" id="descripcion"><?php echo esc_attr($descripcionAntigua);?></textarea>
            </div>

            <div class="form-field">
                <label>Cursos:</label>
                    <label><input type="checkbox" name="asir" <?php echo ($asirAntiguo) ? 'checked' : ''; ?> /> ASIR</label>
                    <label><input type="checkbox" name="daw" <?php echo ($dawAntiguo) ? 'checked' : ''; ?> /> DAW</label>
                    <label><input type="checkbox" name="dam" <?php echo ($damAntiguo) ? 'checked' : ''; ?> /> DAM</label>
                    <label><input type="checkbox" name="smr" <?php echo ($smrAntiguo) ? 'checked' : ''; ?> /> SMR</label>
                    <label><input type="checkbox" name="videojuegos" <?php echo ($videojuegosAntiguo) ? 'checked' : ''; ?> /> Videojuegos</label>
            </div>

            <div class="form-field">
                <label class="form-label" for="fecha_caducidad">Fecha de Caducidad</label>
                <input class="form-input" type="date" name="fecha_caducidd-editar" id="fecha_caducidad" value="<?php echo esc_attr($fechaCaducidadAntigua);?>" />
            </div>

            <div class="form-field">
                <label class="form-label" for="otros">Otros</label>
                <input class="form-input" type="text" name="otros-editar" id="otros" value="<?php echo esc_attr($otrosAntiguo);?>" />
            </div>
            <span><b style='color: red;'>*</b> Campo obligatorio</span>
            <div class="form-field">
                <button type="submit" class="form-button">Guardar cambios</button>
            </div>
        </form>
    </div>
</main>
