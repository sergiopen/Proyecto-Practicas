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

function comprobarSesion() {
	if (is_admin()) {
        return;
    }

    if (!isset($_COOKIE['sesion']) && is_front_page()) {
        wp_redirect(get_site_url() . '/login');
        exit;
    }

    if (isset($_COOKIE['sesion']) && is_page('login')) {
		wp_redirect(home_url());
        exit;
    }
}

add_action('template_redirect', 'comprobarSesion');

function conexionBD() {
    $conexion = new mysqli('localhost', 'root', '', 'bd_proyecto');
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }
	return $conexion;
}

function iniciarSesion() {
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['usuario'], $_POST['password'])) {
        $usuario = sanitize_text_field($_POST['usuario']);
        $password = sanitize_text_field($_POST['password']);

        $conexion = conexionBD();

        if ($conexion->connect_error) {
            die("Algo ha ido mal");
        }

        $sql = "SELECT * FROM profesores WHERE nombre_usuario = ? AND password = ?";
        $queryFormateada = $conexion->prepare($sql);
        $queryFormateada->bind_param('is', $usuario, $password);
        $queryFormateada->execute();
        $resultadoLogin = $queryFormateada->get_result();

        if ($resultadoLogin->num_rows == 1) {
			$usuarioDB = $resultadoLogin->fetch_assoc();
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
        $conexion->close();
    }
}

add_action("init", "iniciarSesion");

function mostrarInformacion() {
    if (isset($_COOKIE['sesion'])) {
        $datos = json_decode(stripslashes($_COOKIE['sesion']), true);
        
        if (isset($datos['nombre_usuario'])) {
            $usuario = $datos['nombre_usuario'];
            
            if (is_front_page()) {
				echo '<main>';
                echo '<div class="bienvenida-usuario">';
                echo '<h2 style="text-align: center;">¡Bienvenido, ' . esc_html($usuario) . '!</h2>';
                echo '</div>';
				mostrar_tabla_profesores();
				echo '</main>';
            }
        }
    }
}
add_action('wp_footer', 'mostrarInformacion');

function mostrar_tabla_profesores() {
	$conexion = conexionBD();
	
    $sql = "SELECT id, nombre_usuario, nombre, apellidos, email, rol FROM profesores";
    $resultado = $conexion->query($sql);
	
    if ($resultado->num_rows > 0) {
		echo '<div class="tabla-profesores-container">';
		echo '<h3>Lista de Profesores</h3>';
		echo '<a href="' . home_url() . '/nuevo-profesor">Añadir profesor</a>';
		echo '<table border="1" class="tabla-profesores">';
        echo '<thead><tr><th>ID</th><th>Nombre de usuario</th><th>Nombre</th><th>Apellido</th><th>Email</th><th>Rol</th></tr></thead>';
        echo '<tbody>';
        
        while ($row = $resultado->fetch_assoc()) {
			echo '<tr>';
            echo '<td>' . esc_html($row['id']) . '</td>';
            echo '<td>' . esc_html($row['nombre_usuario']) . '</td>';
            echo '<td>' . esc_html($row['nombre']) . '</td>';
            echo '<td>' . esc_html($row['apellidos']) . '</td>';
			echo '<td>' . esc_html($row['email']) . '</td>';
			echo '<td>' . esc_html($row['rol']) . '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
		echo '</div>';
    } else {
		echo '<p>No se encontraron profesores.</p>';
    }
	
    $conexion->close();
}
