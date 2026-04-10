<?php
function goval_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus(array(
        'primary' => 'Menu Principal',
    ));
}
add_action('after_setup_theme', 'goval_theme_setup');

function goval_enqueue_scripts() {
    wp_enqueue_style('goval-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Roboto:wght@700&display=swap', array(), null);
    wp_enqueue_style('goval-style', get_stylesheet_uri(), array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'goval_enqueue_scripts');

// Carregar Tipos de Posts Customizados
require_once get_template_directory() . '/cpt.php';

// Módulos e Shortcodes para Compatibilidade Extrema com Elementor
require_once get_template_directory() . '/shortcodes.php';

// Mapa 3D Avançado: Módulo separado com seleção Piloto+Ano e telemetria completa
require_once get_template_directory() . '/map3d-shortcode.php';
