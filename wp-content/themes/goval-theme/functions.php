<?php
/**
 * GOVAL VOO LIVRE - THEME FUNCTIONS
 * Padrão Clean Code: Todo o núcleo de lógica foi movido para o diretório /inc.
 */

// 1. Configurações Básicas do Tema
function goval_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus(array(
        'primary' => 'Menu Principal',
    ));
    
    // Suporte Total ao WooCommerce
    add_theme_support('woocommerce', array(
        'thumbnail_image_width' => 600,
        'single_image_width'    => 1000,
        'product_grid'          => array('default_rows' => 3, 'min_columns' => 2, 'max_columns' => 4, 'default_columns' => 3),
    ));
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'goval_theme_setup');

// 2. Enfileiramento de Scripts e Estilos
function goval_enqueue_scripts() {
    // Fontes Modernas
    wp_enqueue_style('goval-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Roboto:wght@700&display=swap', array(), null);
    
    // Estilo Principal do Tema
    wp_enqueue_style('goval-style', get_stylesheet_uri(), array(), '1.1.0');

    // Estilos Modulares de Shortcodes
    if (is_singular() || is_front_page()) {
        wp_enqueue_style('goval-news-grid', get_template_directory_uri() . '/assets/css/news-grid.css', array(), '1.0.0');
        wp_enqueue_style('goval-champions-tabs', get_template_directory_uri() . '/assets/css/champions-tabs.css', array(), '1.0.0');
        wp_enqueue_style('goval-map3d', get_template_directory_uri() . '/assets/css/map3d.css', array(), '1.0.0');
    }

    // WooCommerce Dark Mode override
    if (class_exists('WooCommerce')) {
        wp_enqueue_style('goval-woocommerce-dark', get_template_directory_uri() . '/assets/css/woocommerce-dark.css', array('woocommerce-general'), '1.0.0');
    }
}
add_action('wp_enqueue_scripts', 'goval_enqueue_scripts');

/**
 * 3. Carregamento Automático de Módulos (Core / inc)
 * Mantém a raiz do tema limpa e organizada.
 */
$goval_includes = [
    'inc/cpt.php',               // Definição de Post Types
    'inc/shortcodes/news-grid.php',
    'inc/shortcodes/champions-tabs.php',
    'inc/shortcodes/map3d.php',  // Módulo de Mapa 3D
];

// Módulo WooCommerce (apenas se ativo)
if (class_exists('WooCommerce')) {
    $goval_includes[] = 'inc/woocommerce.php';
}

foreach ($goval_includes as $file) {
    if (!$filepath = locate_template($file)) {
        trigger_error(sprintf(__('Erro ao localizar %s para inclusão', 'goval'), $file), E_USER_ERROR);
    }
    require_once $filepath;
}
