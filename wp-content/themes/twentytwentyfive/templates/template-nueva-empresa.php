<?php
session_start();
get_header();

$conexion = conexionBD();

$empresa_id = $_GET['empresa_id'] ?? '';

$sql = "SELECT id, nombre, codigo_empresa FROM empresas";
$result = $conexion->query($sql);
$empresas = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $empresas[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Proyecto - Crear Oferta</title>
</head>
<body>
<div class="container">
    <h2>Crear Oferta</h2>
    <form method="POST" class="form-container">
        <div class="form-field">
            <label for="empresa_id">Empresa:</label>
            <select name="empresa_id" id="empresa_id" required>
                <option value="">Selecciona una empresa</option>
                <?php foreach ($empresas as $empresa): ?>
                    <option value="<?= $empresa['id'] ?>" <?= ($empresa_id == $empresa['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($empresa['nombre'] . ' (' . $empresa['codigo_empresa'] . ')') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-field">
            <label for="titulo">Título de la oferta:</label>
            <input type="text" name="titulo" id="titulo" required />
        </div>

        <div class="form-field">
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" required></textarea>
        </div>

        <button type="submit">Crear Oferta</button>
        <a href="<?= home_url() ?>/ofertas" class="form-button-cancel">Volver</a>
    </form>
</div>
</body>
</html>

<?php
$conexion->close();
?>
