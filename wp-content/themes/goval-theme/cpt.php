<?php
function goval_register_cpts() {
    // CPT: Campeões
    register_post_type('campeao', array(
        'labels' => array(
            'name' => 'Campeões',
            'singular_name' => 'Campeão',
            'menu_name' => 'Campeões',
            'add_new' => 'Adicionar Campeão',
            'add_new_item' => 'Adicionar Novo Campeão',
            'edit_item' => 'Editar Campeão',
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-awards',
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields', 'excerpt'),
        'show_in_rest' => true,
    ));

    // CPT: Voos (Rotas 3D)
    register_post_type('voo', array(
        'labels' => array(
            'name' => 'Voos e Rotas',
            'singular_name' => 'Voo',
            'menu_name' => 'Voos/Rotas',
            'add_new' => 'Adicionar Voo',
            'add_new_item' => 'Adicionar Novo Voo',
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-location-alt',
        'supports' => array('title', 'editor', 'custom-fields', 'author'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'goval_register_cpts');
