<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Proyecto - Nuevo Alumno</title>
</head>
<body>
<?php
get_header();
?>

<div class="container">
    <h2>Nuevo Alumno</h2>
    <form class="form-container" method="POST" action="">
        <div class="form-field">
            <label class="form-label" for="nombre">Nombre:</label>
            <input class="form-input" type="text" id="nombre" name="nombre" required />
        </div>

        <div class="form-field">
            <label class="form-label" for="apellidos">Apellidos:</label>
            <input class="form-input" type="text" id="apellidos" name="apellidos" required />
        </div>

        <div class="form-field">
            <label class="form-label" for="email">Correo electrónico:</label>
            <input class="form-input" type="email" id="email" name="email" required />
        </div>

        <div class="form-field">
            <label class="form-label" for="telefono">Teléfono:</label>
            <input class="form-input" type="tel" id="telefono" name="telefono" required />
        </div>

        <div class="form-field">
            <label class="form-label">Cursos:</label>
            <div class="cursos-container">
                <div class="checkbox-group">
                    <input type="checkbox" id="ASIR" name="ASIR" class="form-checkbox" />
                    <label for="ASIR" class="form-checkbox-label">ASIR</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="DAW" name="DAW" class="form-checkbox" />
                    <label for="DAW" class="form-checkbox-label">DAW</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="DAM" name="DAM" class="form-checkbox" />
                    <label for="DAM" class="form-checkbox-label">DAM</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="SMR" name="SMR" class="form-checkbox" />
                    <label for="SMR" class="form-checkbox-label">SMR</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="videojuegos" name="VIDEOJUEGOS" class="form-checkbox" />
                    <label for="videojuegos" class="form-checkbox-label">Videojuegos</label>
                </div>
            </div>
        </div>

        <div class="form-field">
            <label class="form-label" for="otros">Otros:</label>
            <input class="form-input" type="text" id="otros" name="OTROS" />
        </div>

        <button class="form-button" type="submit">Añadir Alumno</button>
        <?php echo '<a class="form-button-cancel" href="' . home_url() . '/alumnos">Volver</a>'; ?>
    </form>
</div>

</body>
</html>
