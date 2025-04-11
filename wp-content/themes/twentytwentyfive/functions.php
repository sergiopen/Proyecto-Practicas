<?php
/**
 * Twenty Twenty-Five functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since Twenty Twenty-Five 1.0
 */

// Adds theme support for post formats.
if ( ! function_exists( 'twentytwentyfive_post_format_setup' ) ) :
	/**
	 * Adds theme support for post formats.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_post_format_setup() {
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
	}
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_post_format_setup' );

// Enqueues editor-style.css in the editors.
if ( ! function_exists( 'twentytwentyfive_editor_style' ) ) :
	/**
	 * Enqueues editor-style.css in the editors.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_editor_style() {
		add_editor_style( get_parent_theme_file_uri( 'assets/css/editor-style.css' ) );
	}
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_editor_style' );

// Enqueues style.css on the front.
if ( ! function_exists( 'twentytwentyfive_enqueue_styles' ) ) :
	/**
	 * Enqueues style.css on the front.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_enqueue_styles() {
		wp_enqueue_style(
			'twentytwentyfive-style',
			get_parent_theme_file_uri( 'style.css' ),
			array(),
			wp_get_theme()->get( 'Version' )
		);
	}
endif;
add_action( 'wp_enqueue_scripts', 'twentytwentyfive_enqueue_styles' );

// Registers custom block styles.
if ( ! function_exists( 'twentytwentyfive_block_styles' ) ) :
	/**
	 * Registers custom block styles.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_block_styles() {
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'twentytwentyfive' ),
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_block_styles' );

// Registers pattern categories.
if ( ! function_exists( 'twentytwentyfive_pattern_categories' ) ) :
	/**
	 * Registers pattern categories.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_pattern_categories() {

		register_block_pattern_category(
			'twentytwentyfive_page',
			array(
				'label'       => __( 'Pages', 'twentytwentyfive' ),
				'description' => __( 'A collection of full page layouts.', 'twentytwentyfive' ),
			)
		);

		register_block_pattern_category(
			'twentytwentyfive_post-format',
			array(
				'label'       => __( 'Post formats', 'twentytwentyfive' ),
				'description' => __( 'A collection of post format patterns.', 'twentytwentyfive' ),
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_pattern_categories' );

// Registers block binding sources.
if ( ! function_exists( 'twentytwentyfive_register_block_bindings' ) ) :
	/**
	 * Registers the post format block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_register_block_bindings() {
		register_block_bindings_source(
			'twentytwentyfive/format',
			array(
				'label'              => _x( 'Post format name', 'Label for the block binding placeholder in the editor', 'twentytwentyfive' ),
				'get_value_callback' => 'twentytwentyfive_format_binding',
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_register_block_bindings' );

// Registers block binding callback function for the post format name.
if ( ! function_exists( 'twentytwentyfive_format_binding' ) ) :
	/**
	 * Callback function for the post format name block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return string|void Post format name, or nothing if the format is 'standard'.
	 */
	function twentytwentyfive_format_binding() {
		$post_format_slug = get_post_format();

		if ( $post_format_slug && 'standard' !== $post_format_slug ) {
			return get_post_format_string( $post_format_slug );
		}
	}
endif;

require_once( WP_PLUGIN_DIR . '/phpmailer/src/PHPMailer.php' );
require_once( WP_PLUGIN_DIR . '/phpmailer/src/SMTP.php' );
require_once( WP_PLUGIN_DIR . '/phpmailer/src/Exception.php' );

function agregarRutaProfesores() {
    add_rewrite_rule('^profesores/?$', 'index.php?profesores=1', 'top');
}
add_action('init', 'agregarRutaProfesores');

function agregarRutaAlumnos() {
    add_rewrite_rule('^alumnos/?$', 'index.php?alumnos=1', 'top');
}
add_action('init', 'agregarRutaAlumnos');

function agregarRutaEmpresas() {
    add_rewrite_rule('^empresas/?$', 'index.php?empresas=1', 'top');
}
add_action('init', 'agregarRutaEmpresas');

function agregarRutaOferta() {
    add_rewrite_rule('^ofertas/?$', 'index.php?ofertas=1', 'top');
}
add_action('init', 'agregarRutaOferta');
function agregarRutaEditarProfesor() {
    add_rewrite_rule('^editar-profesor/?$', 'index.php?editar-profesor=1', 'top');
}
add_action('init', 'agregarRutaEditarProfesor');
function agregarRutaEditarAlumno() {
    add_rewrite_rule('^editar-alumno/?$', 'index.php?editar-alumno=1', 'top');
}
add_action('init', 'agregarRutaEditarAlumno');
function agregarRutaEditarEmpresa() {
    add_rewrite_rule('^editar-empresa/?$', 'index.php?editar-empresa=1', 'top');
}
add_action('init', 'agregarRutaEditarEmpresa');
function agregarRutaEditarOferta() {
    add_rewrite_rule('^editar-oferta/?$', 'index.php?editar-oferta=1', 'top');
}
add_action('init', 'agregarRutaEditarOferta');

function agregarQueryVars($vars) {
    $vars[] = 'profesores';
    $vars[] = 'alumnos';
    $vars[] = 'empresas';
    $vars[] = 'ofertas';
    $vars[] = 'editar-profesor';
    $vars[] = 'editar-alumno';
    $vars[] = 'editar-empresa';
    $vars[] = 'editar-oferta';
    return $vars;
}
add_filter('query_vars', 'agregarQueryVars');

function cargarTemplates($template) {
    if (get_query_var('profesores') == 1) {
        return get_template_directory() . '/templates/template-profesores.php';
    }
    if (get_query_var('alumnos') == 1) {
        return get_template_directory() . '/templates/template-alumnos.php';
    }
    if (get_query_var('empresas') == 1) {
        return get_template_directory() . '/templates/template-empresas.php';
    }
    if (get_query_var('ofertas') == 1) {
        return get_template_directory() . '/templates/template-ofertas.php';
    }
    if (get_query_var('editar-profesor') == 1) {
        return get_template_directory() . '/templates/template-editar-profesor.php';
    }
    if (get_query_var('editar-alumno') == 1) {
        return get_template_directory() . '/templates/template-editar-alumno.php';
    }
    if( get_query_var('editar-empresa') == 1) {
        return get_template_directory() . '/templates/template-editar-empresa.php';
    }
    if( get_query_var('editar-oferta') == 1) {
        return get_template_directory() . '/templates/template-editar-oferta.php';
    }
    return $template;
}
add_filter('template_include', 'cargarTemplates');

require_once get_template_directory() . '/includes/db-connection.php';
function comprobarSesion() {
    if (is_admin() || wp_doing_ajax()) {
        return;
    }

    $sesion_activa = isset($_COOKIE['sesion']) && !empty($_COOKIE['sesion']);
    $pagina_login = is_page('login');
    $pagina_actual = get_post_field('post_name', get_queried_object_id());

    $paginas_publicas = array('login');

    if (!$sesion_activa && (is_front_page() || is_home())) {
        wp_safe_redirect(get_site_url() . '/login');
        exit;
    }

    if (!$sesion_activa && !$pagina_login && !in_array($pagina_actual, $paginas_publicas)) {
        wp_safe_redirect(get_site_url() . '/login');
        exit;
    }

    if ($sesion_activa && $pagina_login) {
        wp_safe_redirect(home_url());
        exit;
    }
}

add_action('template_redirect', 'comprobarSesion');

function iniciarSesion() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['usuario'], $_POST['password'])) {
        $usuario = sanitize_text_field($_POST['usuario']);
        $password = sanitize_text_field($_POST['password']);

        $conexion = conexionBD();

        if ($conexion->connect_error) {
            die("Algo ha ido mal");
        }

        $sql = "SELECT id, nombre_usuario, password FROM profesores WHERE nombre_usuario = ?";
        $queryFormateada = $conexion->prepare($sql);
        $queryFormateada->bind_param('s', $usuario);
        $queryFormateada->execute();
        $resultadoLogin = $queryFormateada->get_result();

        if ($resultadoLogin->num_rows == 1) {
            $usuarioDB = $resultadoLogin->fetch_assoc();

            if (password_verify($password, $usuarioDB['password'])) {
                $datos = [
                    "id" => $usuarioDB["id"],
                    "nombre_usuario" => $usuarioDB["nombre_usuario"],
                ];

                $jsonFormateo = json_encode($datos);
                setcookie('sesion', $jsonFormateo, time() + 3600 * 24 * 30, '/');
                wp_redirect(home_url());
                exit;
            } else {
                echo "<p class='error'>Usuario o contraseña incorrectos</p>";
            }
        } else {
            echo "<p class='error'>Usuario o contraseña incorrectos</p>";
        }

        $conexion->close();
    }
}
add_action("init", "iniciarSesion");

function cerrarSesion() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cerrar_sesion'])) {
        setcookie('sesion', '', time() - 3600, '/');
        wp_redirect(home_url());
        exit;
    }
}
add_action("init", "cerrarSesion");

function agregarProfesor() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre-profesor'], $_POST['apellidos-profesor'], $_POST['email-profesor'], $_POST['usuario-profesor'], $_POST['rol-profesor'], $_POST['password-profesor'])) {

        $nombre = sanitize_text_field($_POST['nombre-profesor']);
        $apellidos = sanitize_text_field($_POST['apellidos-profesor']);
        $email = filter_var($_POST['email-profesor'], FILTER_SANITIZE_EMAIL);
        $usuario = sanitize_text_field($_POST['usuario-profesor']);
        $rol = $_POST['rol-profesor'];
        $password = $_POST['password-profesor'];

        if(empty($nombre) || empty($apellidos) || empty($email) || empty($usuario) || empty($rol) || empty($password)) {
            echo "<p class='error'>Todos los campos son obligatorios</p>";
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<p class='error'>El email no es válido</p>";
            return;
        }

        if (strlen($password) < 8) {
            echo "<p class='error'>La contraseña debe tener al menos 8 caracteres</p>";
            return;
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $conexion = conexionBD();

        if ($conexion->connect_error) {
            die("Error en la conexión a la base de datos");
        }

        $sql = "INSERT INTO profesores (nombre, apellidos, email, nombre_usuario, rol, password) VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $conexion->prepare($sql);

        if ($stmt === false) {
            die("Error en la consulta SQL: " . $conexion->error);
        }

        $stmt->bind_param("ssssss", $nombre, $apellidos, $email, $usuario, $rol, $passwordHash);

        if ($stmt->execute()) {
            echo "<p class='exito'>Profesor añadido correctamente</p>";
        } else {
            echo "<p class='error'>Error al añadir el profesor: " . $stmt->error . "</p>";
        }

        $stmt->close();
        $conexion->close();
    }
}
add_action('init', 'agregarProfesor');

function eliminarProfesor() {
    $conexion = conexionBD();
    if (isset($_POST['id_profesor'])) {
        $id_profesor = intval($_POST['id_profesor']);
        
        $sql = "DELETE FROM profesores WHERE id = $id_profesor";
        
        if ($conexion->query($sql) === true) {
            echo "<p class='exito'>Profesor eliminado correctamente.</p>";
        } else {
            echo "<p class='error'>Error al eliminar el profesor: " . $conexion->error . "</p>";
        }
    }
}
add_action('init', 'eliminarProfesor');

function agregarAlumno() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre'], $_POST['apellidos'], $_POST['email'], $_POST['telefono'])) {

        $nombre = sanitize_text_field($_POST['nombre']);
        $apellidos = sanitize_text_field($_POST['apellidos']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $telefono = sanitize_text_field($_POST['telefono']);
        
        if(empty($nombre) || empty($apellidos) || empty($email) || empty($telefono)) {
            echo "<p class='error'>Todos los campos son obligatorios</p>";
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<p class='error'>El email no es válido</p>";
            return;
        }
        
        $ASIR = isset($_POST['ASIR']) ? true : false;
        $DAW = isset($_POST['DAW']) ? true : false;
        $DAM = isset($_POST['DAM']) ? true : false;
        $SMR = isset($_POST['SMR']) ? true : false;
        $videojuegos = isset($_POST['videojuegos']) ? true : false; 
        $OTROS = isset($_POST['OTROS']) && $_POST['OTROS'] !== '' ? $_POST['OTROS'] : NULL;
        
        $conexion = conexionBD();

        if ($conexion->connect_error) {
            die("Error en la conexión a la base de datos");
        }

        $sql = "INSERT INTO alumnos (nombre, apellidos, email, ASIR, DAW, DAM, SMR, videojuegos, OTROS, telefono) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conexion->prepare($sql);

        if ($stmt === false) {
            die("Error en la consulta SQL: " . $conexion->error);
        }

        $stmt->bind_param("ssssiissss", $nombre, $apellidos, $email, $ASIR, $DAW, $DAM, $SMR, $videojuegos, $OTROS, $telefono);

        if ($stmt->execute()) {
            echo "<p class='exito'>Alumno añadido correctamente</p>";
        } else {
            echo "<p class='error'>Error al añadir el alumno: " . $stmt->error . "</p>";
        }

        $stmt->close();
        $conexion->close();
    }
}
add_action('init', 'agregarAlumno');

function eliminarAlumno() {
    $conexion = conexionBD();
    if (isset($_POST['id_alumno'])) {
        $id_alumno = intval($_POST['id_alumno']);
        
        $sql = "DELETE FROM alumnos WHERE id = $id_alumno";
        
        if ($conexion->query($sql) === true) {
            echo "<p class='exito'>Alumno eliminado correctamente.</p>";
        } else {
            echo "<p class='error'>Error al eliminar el alumno: " . $conexion->error . "</p>";
        }
    }
}
add_action('init', 'eliminarAlumno');

function agregarEmpresa() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre-empresa'], $_POST['direccion-empresa'], $_POST['telefono-empresa'], $_POST['email-empresa'], $_POST['telefono-contacto'], $_POST['codigo-empresa'])) {

        $nombre_empresa = sanitize_text_field($_POST['nombre-empresa']);
        $direccion_empresa = sanitize_text_field($_POST['direccion-empresa']);
        $telefono_empresa = sanitize_text_field($_POST['telefono-empresa']);
        $email_empresa = filter_var($_POST['email-empresa'], FILTER_SANITIZE_EMAIL);
        $telefono_contacto = sanitize_text_field($_POST['telefono-contacto']);
        $codigo_empresa = sanitize_text_field($_POST['codigo-empresa']);
        
        if(empty($nombre_empresa) || empty($direccion_empresa) || empty($telefono_empresa) || empty($email_empresa) || empty($telefono_contacto) || empty($codigo_empresa)) {
            echo "<p class='error'>Todos los campos son obligatorios</p>";
            return;
        }

        if (!filter_var($email_empresa, FILTER_VALIDATE_EMAIL)) {
            echo "<p class='error'>El email no es válido</p>";
            return;
        }
        
        $conexion = conexionBD();

        if ($conexion->connect_error) {
            die("Error en la conexión a la base de datos");
        }

        $sql = "INSERT INTO empresas (nombre, direccion, telefono, email, telefono_contacto, codigo_empresa) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conexion->prepare($sql);

        if ($stmt === false) {
            die("Error en la consulta SQL: " . $conexion->error);
        }

        $stmt->bind_param("ssssss", $nombre_empresa, $direccion_empresa, $telefono_empresa, $email_empresa, $telefono_contacto, $codigo_empresa);

        if ($stmt->execute()) {
            echo "<p class='exito'>Empresa añadida correctamente</p>";
        } else {
            echo "<p class='error'>Error al añadir la empresa: " . $stmt->error . "</p>";
        }

        $stmt->close();
        $conexion->close();
    }
}
add_action('init', 'agregarEmpresa');

function eliminarEmpresa() {
    $conexion = conexionBD();

    if (isset($_POST['id_empresa'])) {
        $id_empresa = intval($_POST['id_empresa']);

        $sql = "SELECT codigo_empresa FROM empresas WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_empresa);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $empresa = $resultado->fetch_assoc();
        $stmt->close();

        if ($empresa) {
            $codigo_empresa = $empresa['codigo_empresa'];

            $sqlEliminarOfertas = "DELETE FROM ofertas WHERE codigo_empresa = ?";
            $stmtEliminarOfertas = $conexion->prepare($sqlEliminarOfertas);
            $stmtEliminarOfertas->bind_param("s", $codigo_empresa);

            if ($stmtEliminarOfertas->execute()) {
                $sqlEliminarEmpresa = "DELETE FROM empresas WHERE id = ?";
                $stmtEliminarEmpresa = $conexion->prepare($sqlEliminarEmpresa);
                $stmtEliminarEmpresa->bind_param("i", $id_empresa);

                if ($stmtEliminarEmpresa->execute()) {
                    echo "<p class='exito'>Empresa y ofertas relacionadas eliminadas correctamente.</p>";
                } else {
                    echo "<p class='error'>Error al eliminar la empresa: " . $conexion->error . "</p>";
                }

                $stmtEliminarEmpresa->close();
            } else {
                echo "<p class='error'>Error al eliminar las ofertas: " . $conexion->error . "</p>";
            }

            $stmtEliminarOfertas->close();
        } else {
            echo "<p class='error'>Empresa no encontrada.</p>";
        }
    }
}
add_action('init', 'eliminarEmpresa');

function agregarOferta() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['codigo_empresa'], $_POST['titulo'], $_POST['descripcion'])) {
        $codigo_empresa = sanitize_text_field($_POST['codigo_empresa']);
        $titulo_oferta = sanitize_text_field($_POST['titulo']);
        $descripcion_oferta = sanitize_textarea_field($_POST['descripcion']);
        $caducidad = isset($_POST['caducidad']) ? $_POST['caducidad'] : null;

        $asir = isset($_POST['ASIR']) ? true : false;
        $daw = isset($_POST['DAW']) ? true : false;
        $dam = isset($_POST['DAM']) ? true : false;
        $smr = isset($_POST['SMR']) ? true : false;
        $videojuegos = isset($_POST['videojuegos']) ? true : false; 
        $otros = sanitize_text_field($_POST['otros']); 

        if (empty($codigo_empresa) || empty($titulo_oferta) || empty($descripcion_oferta)) {
            echo "<p class='error'>Todos los campos son obligatorios</p>";
            return;
        }

        if(empty($caducidad)) $caducidad = null;

        $conexion = conexionBD();

        if ($conexion->connect_error) {
            die("Error en la conexión a la base de datos");
        }

        $sql = "INSERT INTO ofertas (codigo_empresa, titulo, descripcion, asir, daw, dam, smr, videojuegos, otros, fecha_caducidad) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conexion->prepare($sql);

        if ($stmt === false) {
            die("Error en la consulta SQL: " . $conexion->error);
        }

        $stmt->bind_param("ssssssssss", $codigo_empresa, $titulo_oferta, $descripcion_oferta, $asir, $daw, $dam, $smr, $videojuegos, $otros, $caducidad);

        if ($stmt->execute()) {
            echo "<p class='exito'>Oferta añadida correctamente</p>";
        } else {
            echo "<p class='error'>Error al añadir la oferta: " . $stmt->error . "</p>";
        }

        $stmt->close();
        $conexion->close();
    }
}
add_action('init', 'agregarOferta');

function eliminarOferta() {
    $conexion = conexionBD();
    if (isset($_POST['id_oferta'])) {
        $id_oferta = intval($_POST['id_oferta']);
        
        $sql = "DELETE FROM ofertas WHERE id = $id_oferta";
        
        if ($conexion->query($sql) === true) {
            echo "<p class='exito'>Oferta eliminada correctamente.</p>";
        } else {
            echo "<p class='error'>Error al eliminar la empresa: " . $conexion->error . "</p>";
        }
    }
}
add_action('init', 'eliminarOferta');