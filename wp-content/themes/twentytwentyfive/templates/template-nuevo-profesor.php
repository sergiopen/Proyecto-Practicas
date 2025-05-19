<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto - Nuevo Profesor</title>
</head>
<body>
   <?php
    get_header();
    ?>
    
    <div class="container">
        <h2>Nuevo profesor</h2>
        <form class="form-container" method="POST">
            <div class="form-field">
                <label class="form-label" for="nombre-profesor">Nombre del profesor</label>
                <input class="form-input" type="text" name="nombre-profesor" id="nombre-profesor" required />
            </div>
            
            <div class="form-field">
                <label class="form-label" for="apellidos-profesor">Apellidos del profesor</label>
                <input class="form-input" type="text" name="apellidos-profesor" id="apellidos-profesor" required />
            </div>
            
            <div class="form-field">
                <label class="form-label" for="email-profesor">Email del profesor</label>
                <input class="form-input" type="email" name="email-profesor" id="email-profesor" required />
            </div>

            <div class="form-field">
                <label class="form-label" for="password-profesor">Contraseña del profesor</label>
                <input class="form-input" type="password" name="password-profesor" id="password-profesor" placeholder="Mínimo 8 caracteres" required />
            </div>
            
            <div class="form-field">
                <label class="form-label" for="usuario-profesor">Nombre de usuario del profesor</label>
                <input class="form-input" type="text" name="usuario-profesor" id="usuario-profesor" required />
            </div>
            
            <div class="form-field">
                <label class="form-label" for="rol-profesor">Rol del profesor</label>
                <select class="form-input" name="rol-profesor" id="rol-profesor" required>
                    <option value="admin">Administrador</option>
                    <option value="estandar">Estándar</option>
                </select>
            </div>
            
            <button class="form-button" type="submit">Añadir profesor</button>
            <?php echo '<a class="form-button-cancel" href="' . home_url() . '/profesores">Volver</a>'; ?>
        </form>
    </div>

</body>
</html>