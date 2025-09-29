<?php
if (!defined('ABSPATH')) {
    exit;
}
global $is_mobile;

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
    <div class="header-inner">
        <?php
        if ($is_mobile)
        {
            echo "<div class = 'mobile-header-inner'>";
        }
        ?>
        <div class="site-branding">
            <?php if (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php endif; ?>

            <h3 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h3>
        </div>
        <?php
        if ($is_mobile)
        {
            echo '<button class="menu-toggle" aria-expanded="false">&#9776;</button>';
            echo "</div>";
        }
        $locations = get_nav_menu_locations();
        $menu_count = 0;
        if ( isset( $locations['primary'] ) )
        {
            $menu = wp_get_nav_menu_object( $locations['primary'] );
            if ($menu)
            {
                $menu_items = wp_get_nav_menu_items($menu->term_id);
                if (is_array($menu_items))
                {
                    $menu_count = count($menu_items);
                }
            }
        }
        ?>
        <style>
        <?php if ($menu_count > 0) : ?>
        .main-navigation .primary-menu {
            grid-template-columns: repeat(<?php echo $menu_count; ?>, max-content);
        }
        <?php endif; ?>
        </style>
        <nav class="main-navigation" role="navigation">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'container'      => false,
                'menu_class'     => 'primary-menu',
            ));
            ?>
        </nav>
        <?php if (!$is_mobile)
        {
            ?>
            <div class='mini-cart-container'>
                    <?php woocommerce_mini_cart(); ?>
                </div>
            </div>        
            <?php
        }
        ?>
    </div>

    <!-- Your custom product category menu -->
    <?php
    if (!$is_mobile)
    {
        $parent_categories = get_terms([
            'taxonomy'   => 'product_cat',
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => true,
            'parent'     => 0,
        ]);
        if (!empty($parent_categories) && !is_wp_error($parent_categories)) :
        ?>
        <nav class="site-navigation product-category-menu" role="navigation">
            <ul class="horizontal-menu">
                <?php foreach ($parent_categories as $parent) : ?>
                    <li class="menu-parent">
                        <a href="<?php echo esc_url(get_term_link($parent)); ?>" class="parent-item">
                            <?php echo esc_html($parent->name); ?>
                        </a>
                        <?php
                        $child_categories = get_terms([
                            'taxonomy'   => 'product_cat',
                            'orderby'    => 'name',
                            'order'      => 'ASC',
                            'hide_empty' => false,
                            'child_of'   => $parent->term_id, // returns descendants
                        ]);
                        if (!empty($child_categories) && !is_wp_error($child_categories)) :
                        ?>
                            <ul class="child-menu">
                                <?php foreach ($child_categories as $child) : ?>
                                    <li class="menu-child">
                                        <a href="<?php echo esc_url(get_term_link($child)); ?>" class="child-item">
                                            <?php echo esc_html($child->name); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <?php endif; ?>
        <?php
        }
    ?>
</header>
<div class="woocommerce-notices-wrapper"></div>
<div id="content" class="site-content">
