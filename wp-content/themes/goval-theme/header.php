<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php bloginfo('name'); ?> - <?php bloginfo('description'); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <header class="header-site">
        <div class="logo">
            <a href="<?php echo esc_url(home_url('/')); ?>" style="font-size: 1.5em; text-transform: uppercase;">Mundial<span style="color: var(--vale-gold);">Ibituruna</span></a>
        </div>
        <nav class="nav-site">
            <?php
            if (has_nav_menu('primary')) {
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container' => false,
                    'menu_class' => 'menu-principal',
                ));
            } else {
                echo '<ul><li><a href="'.esc_url(home_url('/')).'">Início</a></li></ul>';
            }
            ?>
        </nav>
    </header>
