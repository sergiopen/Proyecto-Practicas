<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto - Inicio</title>
</head>
<body>
    <?php
        get_header();
        function mostrarInformacionInicio() {
            if (isset($_COOKIE['sesion'])) {
                $datos = json_decode(stripslashes($_COOKIE['sesion']), true);
                
                if (isset($datos['nombre_usuario'])) {
                    $usuario = $datos['nombre_usuario'];
                    echo '<main class="main">';
                    echo '<div class="bienvenida-usuario">';
                    echo '<h2 style="text-align: center;">¡Bienvenido, ' . esc_html($usuario) . '!</h2>';
                    echo '</div>';

                    echo '<div class="menu">';

                    echo '    <a class="menu-item" href="' . home_url('/profesores') . '">';
                    echo '        <img src="' . home_url('/wp-content/uploads/2025/04/alumno.png') . '" alt="Profesores">';
                    echo '        <span>Profesores</span>';
                    echo '    </a>';

                    echo '    <a class="menu-item" href="' . home_url('/alumnos') . '">';
                    echo '        <img src="' . home_url('/wp-content/uploads/2025/04/profesor.png') . '" alt="Alumnos">';
                    echo '        <span>Alumnos</span>';
                    echo '    </a>';

                    echo '    <a class="menu-item" href="' . home_url('/empresas') . '">';
                    echo '        <img src="' . home_url('/wp-content/uploads/2025/04/empresa.png') . '" alt="Empresas">';
                    echo '        <span>Empresas</span>';
                    echo '    </a>';

                    echo '    <a class="menu-item" href="' . home_url('/ofertas') . '">';
                    echo '        <img src="' . home_url('/wp-content/uploads/2025/04/oferta.png') . '" alt="Ofertas">';
                    echo '        <span>Ofertas</span>';
                    echo '    </a>';

                    echo '</div>';
                    echo '</main>';
                }
            }
        }
        mostrarInformacionInicio();
        
        function mostrarTablaProfesores() {
            $conexion = conexionBD();
            
            $sql = "SELECT id, nombre_usuario, nombre, apellidos, email, rol FROM profesores LIMIT 5";
            $resultado = $conexion->query($sql);
            
            if ($resultado->num_rows > 0) {
                echo '<div class="container">';
                echo '<h3>Lista de Profesores</h3>';
                echo '<table border="1" class="tabla tabla-profesores">';
                echo '<thead><tr><th>ID</th><th>Nombre de usuario</th><th>Nombre</th><th>Apellido</th><th>Email</th><th>Rol</th><th>Editar</th><th>Eliminar</th></tr></thead>';
                echo '<tbody>';
            
            while ($row = $resultado->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . esc_html($row['id']) . '</td>';
                echo '<td>' . esc_html($row['nombre_usuario']) . '</td>';
                echo '<td>' . esc_html($row['nombre']) . '</td>';
                echo '<td>' . esc_html($row['apellidos']) . '</td>';
                echo '<td>' . esc_html($row['email']) . '</td>';
                echo '<td>' . esc_html($row['rol']) . '</td>';
                echo '<td><a class="editar-btn" href="' . home_url() . '/editar-profesor?id=' . esc_html($row['id']) . '" class="btn-editar">Editar</a></td>';
                echo '<td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_profesor" value="' . esc_html($row['id']) . '">
                            <button class="btn-eliminar" type="submit" name="eliminar" onclick="return confirm(\'¿Estás seguro de que quieres eliminar este profesor?\')">Eliminar</button>
                        </form>
                      </td>';
                echo '</tr>';
            }
            
                echo '</tbody>';
                echo '</table>';
                echo '<a class="btn-mostrar" href="' . home_url() . '/profesores">Todos los profesores</a>';
                echo '</div>';
            } else {
                echo '<div class="container">';
                echo '<p>No se encontraron profesores.</p>';
                echo '<a class="btn-mostrar" href="' . home_url() . '/nuevo-profesor">Añadir profesor</a>';
                echo '</div>';
            }
            
            $conexion->close();
        }

        function mostrarTablaAlumnos() {
            $conexion = conexionBD();
            
            $sql = "SELECT id, nombre, apellidos, email, telefono, ASIR, DAW, DAM, SMR, VIDEOJUEGOS, OTROS FROM alumnos LIMIT 5";
            $resultado = $conexion->query($sql);
            
            if ($resultado->num_rows > 0) {
                echo '<div class="container">';
                echo '<h3>Lista de Alumnos</h3>';
                echo '<table border="1" class="tabla tabla-alumnos">';
            echo '<thead><tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Email</th><th>Teléfono</th><th>ASIR</th><th>DAW</th><th>DAM</th><th>SMR</th><th>VIDEOJUEGOS</th><th>Otros</th><th>Editar</th><th>Eliminar</th></tr></thead>';
            echo '<tbody>';
            while ($row = $resultado->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . esc_html($row['id']) . '</td>';
                echo '<td>' . esc_html($row['nombre']) . '</td>';
                echo '<td>' . esc_html($row['apellidos']) . '</td>';
                echo '<td>' . esc_html($row['email']) . '</td>';
                echo '<td>' . esc_html($row['telefono']) . '</td>';
                echo '<td>' . ($row['ASIR'] ? 'Sí' : 'No') . '</td>';
                echo '<td>' . ($row['DAW'] ? 'Sí' : 'No') . '</td>';
                echo '<td>' . ($row['DAM'] ? 'Sí' : 'No') . '</td>';
                echo '<td>' . ($row['SMR'] ? 'Sí' : 'No') . '</td>';
                echo '<td>' . ($row['VIDEOJUEGOS'] ? 'Sí' : 'No') . '</td>';
                echo '<td>' . (!empty($row['OTROS']) ? esc_html($row['OTROS']) : 'No') . '</td>';
                
                echo '<td><a class="editar-btn" href="' . home_url() . '/editar-alumno?id=' . esc_html($row['id']) . '">Editar</a></td>';
                echo '<td>
                        <form method="POST">
                            <input type="hidden" name="id_alumno" value="' . esc_html($row['id']) . '">
                            <button class="btn-eliminar" type="submit" name="eliminar" onclick="return confirm(\'¿Estás seguro de que quieres eliminar este alumno?\')">Eliminar</button>
                        </form>
                      </td>';
                echo '</tr>';
            }
                echo '</tbody>';
                echo '</table>';
                echo '<a class="btn-mostrar" href="' . home_url() . '/alumnos">Todos los alumnos</a>';
                echo '</div>';
            } else {
                echo '<div class="container">';
                echo '<p>No se encontraron alumnos.</p>';
                echo '<a class="btn-mostrar" href="' . home_url() . '/nuevo-alumno">Añadir alumno</a>';
                echo '</div>';
            }
            
            $conexion->close();
        }
        
        function mostrarTablaEmpresas() {
            $conexion = conexionBD();
            
            $sql = "SELECT id, nombre, direccion, telefono, email, telefono_contacto, codigo_empresa FROM empresas LIMIT 5";
            $resultado = $conexion->query($sql);
            
            if ($resultado->num_rows > 0) {
                echo '<div class="container">';
                echo '<h3>Lista de Empresas</h3>';
                echo '<table border="1" class="tabla tabla-empresas">';
            echo '<thead><tr><th>ID</th><th>Código Empresa</th><th>Nombre</th><th>Dirección</th><th>Teléfono</th><th>Email</th><th>Teléfono de Contacto</th><th>Editar</th><th>Eliminar</th></tr></thead>';
            echo '<tbody>';
            
            while ($row = $resultado->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . esc_html($row['id']) . '</td>';
                echo '<td>' . esc_html($row['codigo_empresa']) . '</td>';
                echo '<td>' . esc_html($row['nombre']) . '</td>';
                echo '<td>' . esc_html($row['direccion']) . '</td>';
                echo '<td>' . esc_html($row['telefono']) . '</td>';
                echo '<td>' . esc_html($row['email']) . '</td>';
                echo '<td>' . esc_html($row['telefono_contacto']) . '</td>';
                echo '<td><a class="editar-btn" href="' . home_url() . '/editar-empresa?id=' . esc_html($row['id']) . '" class="btn-editar">Editar</a></td>';
                echo '<td>
                        <form method="POST">
                            <input type="hidden" name="id_empresa" value="' . esc_html($row['id']) . '">
                            <button class="btn-eliminar" type="submit" name="eliminar" onclick="return confirm(\'¿Estás seguro de que quieres eliminar esta empresa?\')">Eliminar</button>
                        </form>
                      </td>';
                echo '</tr>';
            }
        
                echo '</tbody>';
                echo '</table>';
                echo '<a class="btn-mostrar" href="' . home_url() . '/empresas">Todas las empresas</a>';
                echo '</div>';
            } else {
                echo '<div class="container">';
                echo '<p>No se encontraron empresas.</p>';
                echo '<a class="btn-mostrar" href="' . home_url() . '/nueva-empresa">Añadir empresa</a>';
                echo '</div>';
            }
            
            $conexion->close();
        }
        
        function mostrarTablaOfertas() {
            $conexion = conexionBD();
            
            $sql = "SELECT id, codigo_empresa, titulo, descripcion, ASIR, DAW, DAM, SMR, VIDEOJUEGOS, OTROS, fecha_caducidad FROM ofertas LIMIT 5";
            $resultado = $conexion->query($sql);
            
            if ($resultado->num_rows > 0) {
                echo '<div class="container">';
                echo '<h3>Lista de Ofertas</h3>';
                echo '<table border="1" class="tabla tabla-ofertas">';
            echo '<thead><tr><th>ID</th><th>Código Empresa</th><th>Título</th><th>Descripción</th><th>ASIR</th><th>DAW</th><th>DAM</th><th>SMR</th><th>VIDEOJUEGOS</th><th>Otros</th><th>Fecha caducidad</th><th>Editar</th><th>Eliminar</th></tr></thead>';
            echo '<tbody>';
        
            while ($row = $resultado->fetch_assoc()) {
                echo '<tr>';
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
                echo '<td>' . (!empty($row['fecha_caducidad']) ? esc_html($row['fecha_caducidad']) : 'Nunca') . '</td>';
                echo '<td><a class="editar-btn" href="' . home_url() . '/editar-oferta?id=' . esc_html($row['id']) . '" class="btn-editar">Editar</a></td>';
                echo '<td>
                        <form method="POST">
                            <input type="hidden" name="id_oferta" value="' . esc_html($row['id']) . '">
                            <button class="btn-eliminar" type="submit" name="eliminar" onclick="return confirm(\'¿Estás seguro de que quieres eliminar esta oferta?\')">Eliminar</button>
                        </form>
                      </td>';
                echo '</tr>';
            }
        
                echo '</tbody>';
                echo '</table>';
                echo '<a class="btn-mostrar" href="' . home_url() . '/ofertas">Todas las ofertas</a>';
                echo '</div>';
            } else {
                echo '<div class="container">';
                echo '<p>No se encontraron ofertas.</p>';
                echo '<a class="btn-mostrar" href="' . home_url() . '/nueva-oferta">Añadir oferta</a>';
                echo '</div>';
            }
            
            $conexion->close();
        }

        get_footer();
    ?>
    
</body>
</html>