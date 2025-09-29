<?php
if (!defined('ABSPATH')) {
    exit;
}

ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
error_reporting(E_ALL);

get_header(); // outputs <header>...</header> from header.php
?>
<div class="main_body">
    <div class="panel_left">
        <!-- left panel now empty (you could remove it or use for filters) -->
    </div>

    <div class="panel_center">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <h2 class="entry-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <div class="entry-content">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <article>
                <p><?php esc_html_e('No posts found.', 'twentysixteen-child'); ?></p>
            </article>
        <?php endif; ?>
    </div>

    <div class="panel_right">
        <article>
            <?php
            if (is_active_sidebar('sidebar-1')) {
                dynamic_sidebar('sidebar-1');
            }
            ?>
        </article>
    </div>
</div>

<?php
get_footer(); // outputs <footer>...</footer> from footer.php
