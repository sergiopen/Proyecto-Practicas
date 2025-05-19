<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   <?php
        get_header();
        $conexion = conexionBD();

        $codigo_empresa = $_GET['codigo_empresa'] ?? null;

        $sql = "SELECT id, codigo_empresa, nombre FROM empresas";
        $resultado = $conexion->query($sql);
    ?>
    
    <div class="container">
    <form class="form-container" method="POST">
    <div class="form-field">
        <label class="form-label" for="codigo_empresa">Código de la empresa</label>
        <select class="form-input" name="codigo_empresa" id="codigo_empresa" required>
            <option value="">-- Selecciona una empresa --</option>
            <?php
                while ($empresa = $resultado->fetch_assoc()) {
                    $selected = ($codigo_empresa !== null && $codigo_empresa === $empresa['codigo_empresa']) ? 'selected' : '';
                    echo '<option value="' . $empresa['codigo_empresa'] . '" ' . $selected . '>' . htmlspecialchars($empresa['codigo_empresa'] . ' - ' . $empresa['nombre']) . '</option>';
                }
            ?>
        </select>
    </div>
    
    <div class="form-field">
        <label class="form-label" for="titulo">Título de la oferta</label>
        <input class="form-input" type="text" name="titulo" id="titulo" required />
    </div>
    
    <div class="form-field">
        <label class="form-label" for="descripcion">Descripción de la oferta</label>
        <textarea class="form-input" name="descripcion" id="descripcion" required></textarea>
    </div>
    
    <div class="form-field">
        <label class="form-label">Cursos adecuados para esta oferta:</label>
        <div class="cursos-container">
            <div class="checkbox-group">
                <input type="checkbox" id="ASIR" name="ASIR" class="form-checkbox">
                <label for="ASIR" class="form-checkbox-label">ASIR</label>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="DAW" name="DAW" class="form-checkbox">
                <label for="DAW" class="form-checkbox-label">DAW</label>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="DAM" name="DAM" class="form-checkbox">
                <label for="DAM" class="form-checkbox-label">DAM</label>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="SMR" name="SMR" class="form-checkbox">
                <label for="SMR" class="form-checkbox-label">SMR</label>
            </div>
        </div>
        <div class="checkbox-group">
            <input type="checkbox" id="videojuegos" name="videojuegos" class="form-checkbox">
            <label for="videojuegos" class="form-checkbox-label">Videojuegos</label>
        </div>
    </div>

    <div class="form-field">
        <label for="otros" class="form-label">Otros:</label>
        <input type="text" id="otros" name="otros" class="form-input">
    </div>

    <div class="form-field">
        <label for="caducidad" class="form-label">Fecha de caducidad:</label>
        <input type="date" id="caducidad" name="caducidad" class="form-input">
    </div>

    <button class="form-button" type="submit">Añadir oferta</button>
</form>
    </div>

</body>
</html>