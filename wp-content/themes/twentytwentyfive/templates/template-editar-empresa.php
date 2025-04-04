<?php
    require_once get_template_directory() . '/includes/db-connection.php';
    get_header();
    $conexion = conexionBD();

    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if ($id) {
        $sql = "SELECT codigo_empresa, nombre, direccion, telefono, email, telefono_contacto FROM empresas WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $empresa = $resultado->fetch_assoc();
        $stmt->close();

        if ($empresa === null) {
            echo "<h2 style='text-align:center;'>Empresa no encontrada.</h2>";
            exit;
        }

        $codigoEmpresaAntiguo = $empresa['codigo_empresa'];
        $nombreEmpresaAntiguo = $empresa['nombre'];
        $direccionEmpresaAntigua = $empresa['direccion'];
        $telefonoEmpresaAntiguo = $empresa['telefono'];
        $emailEmpresaAntiguo = $empresa['email'];
        $telefonoContactoEmpresaAntiguo = $empresa['telefono_contacto'];

    } else {
        echo "<h2 style='text-align:center;'>Empresa no encontrada.</h2>";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!isset($_POST['codigo_empresa-editar']) || trim($_POST['codigo_empresa-editar']) === '' || 
            !isset($_POST['nombre-editar-empresa']) || trim($_POST['nombre-editar-empresa']) === '' || 
            !isset($_POST['direccion-editar-empresa']) || trim($_POST['direccion-editar-empresa']) === '' || 
            !isset($_POST['telefono-editar-empresa']) || trim($_POST['telefono-editar-empresa']) === '' || 
            !isset($_POST['email-editar-empresa']) || trim($_POST['email-editar-empresa']) === '' || 
            !isset($_POST['telefono_contacto-editar-empresa']) || trim($_POST['telefono_contacto-editar-empresa']) === '') {
            echo "<p class='error'>Todos los campos obligatorios deben estar completos. <a href='#' onclick='history.back(); return false;'>Volver atrás</a></p>";
            exit;
        }

        $codigoEmpresa = sanitize_text_field($_POST['codigo_empresa-editar']);
        $nombreEmpresa = sanitize_text_field($_POST['nombre-editar-empresa']);
        $direccionEmpresa = sanitize_text_field($_POST['direccion-editar-empresa']);
        $telefonoEmpresa = sanitize_text_field($_POST['telefono-editar-empresa']);
        $emailEmpresa = sanitize_email($_POST['email-editar-empresa']);
        $telefonoContactoEmpresa = sanitize_text_field($_POST['telefono_contacto-editar-empresa']);

        $sql = "UPDATE empresas SET codigo_empresa = ?, nombre = ?, direccion = ?, telefono = ?, email = ?, telefono_contacto = ? WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssssi", $codigoEmpresa, $nombreEmpresa, $direccionEmpresa, $telefonoEmpresa, $emailEmpresa, $telefonoContactoEmpresa, $id);

        if ($stmt->execute()) {
            if ($codigoEmpresaAntiguo != $codigoEmpresa) {
                $sqlUpdateOfertas = "UPDATE ofertas SET codigo_empresa = ? WHERE codigo_empresa = ?";
                $stmtUpdateOfertas = $conexion->prepare($sqlUpdateOfertas);
                $stmtUpdateOfertas->bind_param("ss", $codigoEmpresa, $codigoEmpresaAntiguo);
                $stmtUpdateOfertas->execute();
                $stmtUpdateOfertas->close();
            }

            echo "<p class='exito'>Empresa actualizada correctamente.</p>";
        } else {
            echo "<p class='error'>Error al actualizar la empresa.</p>";
        }

        $stmt->close();
        $conexion->close();
    }
?>

<main>
    <div class="container">
    <h2>Editando la empresa: <?php echo esc_attr($nombreEmpresaAntiguo); ?></h2>
    
    <form class="form-container" method="POST">
        <div class="form-field">
            <label class="form-label" for="codigo_empresa">Código de la empresa <b style='color: red;'>*</b></label>
            <input class="form-input" type="text" name="codigo_empresa-editar" id="codigo_empresa" value="<?php echo esc_attr($codigoEmpresaAntiguo);?>" required />
        </div>

        <div class="form-field">
            <label class="form-label" for="nombre-empresa">Nombre de la empresa <b style='color: red;'>*</b></label>
            <input class="form-input" type="text" name="nombre-editar-empresa" id="nombre-empresa" value="<?php echo esc_attr($nombreEmpresaAntiguo);?>" required />
        </div>

        <div class="form-field">
            <label class="form-label" for="direccion-empresa">Dirección de la empresa <b style='color: red;'>*</b></label>
            <input class="form-input" type="text" name="direccion-editar-empresa" id="direccion-empresa" value="<?php echo esc_attr($direccionEmpresaAntigua);?>" required />
        </div>

        <div class="form-field">
            <label class="form-label" for="telefono-empresa">Teléfono de la empresa <b style='color: red;'>*</b></label>
            <input class="form-input" type="text" name="telefono-editar-empresa" id="telefono-empresa" value="<?php echo esc_attr($telefonoEmpresaAntiguo);?>" required />
        </div>

        <div class="form-field">
            <label class="form-label" for="email-empresa">Email de la empresa <b style='color: red;'>*</b></label>
            <input class="form-input" type="email" name="email-editar-empresa" id="email-empresa" value="<?php echo esc_attr($emailEmpresaAntiguo);?>" required />
        </div>

        <div class="form-field">
            <label class="form-label" for="telefono_contacto-empresa">Teléfono de contacto <b style='color: red;'>*</b></label>
            <input class="form-input" type="text" name="telefono_contacto-editar-empresa" id="telefono_contacto-empresa" value="<?php echo esc_attr($telefonoContactoEmpresaAntiguo);?>" required />
        </div>

        <span><b style='color: red;'>*</b> Campo obligatorio</span>
        <button class="form-button" type="submit">Editar empresa</button>
    </form>
    </div>
</main>
</body>
</html>
