<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto - Iniciar sesión</title>
</head>
<body>
    <?php
    get_header();
    ?>
    <div class="container">
        <form method="post" class="form-container">
        <div class="form-field">
            <label for="usuario" class="form-label">Usuario:</label>
            <input type="text" id="usuario" name="usuario" class="form-input" required placeholder="Usuario">
        </div>

        <div class="form-field">
            <label for="password" class="form-label">Contraseña:</label>
            <input type="password" id="password" name="password" class="form-input" required placeholder="Contraseña">
        </div>
        <input class="form-button" type="submit" value="Iniciar Sesión" />
    </form>
    </div>
</body>
</html>