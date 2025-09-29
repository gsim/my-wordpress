<?php
if (!defined('ABSPATH'))
{
    exit;
}

$is_mobile=false;

class GJGOFunctions
{
    public function __construct()
    {
        global $is_mobile;
        $is_mobile=wp_is_mobile();
        add_action('wp_enqueue_scripts', [$this,'my_child_theme_enqueue_styles']);
        add_action('after_setup_theme', [$this, 'add_woocommerce_support']);
        //add_action('wp_head', [$this,'my_childtheme_custom_color_scheme_css']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_cart_fragment_support']);
        //add_filter('twentysixteen_color_schemes',[$this,'my_childtheme_add_color_scheme']);
        add_action('wp_ajax_gavin_update_mini_cart', [$this, 'render_mini_cart']);
        add_action('wp_ajax_nopriv_gavin_update_mini_cart', [$this, 'render_mini_cart']);
        add_filter('woocommerce_add_to_cart_fragments',[$this,'gavin_header_cart_fragment']);
        add_action('wp_enqueue_scripts',[$this,'gavin_enqueue_google_fonts']);
        add_filter('the_title',[$this,'gavin_hide_page_titles'],10,2);
        //add_action('after_setup_theme',[$this,'my_remove_twenty_sixteen_inline_colors']);

        add_action('phpmailer_init',function($phpmailer)
        {
            $phpmailer->isSMTP();
            $phpmailer->Host       = $_ENV['SMTP_HOST'];
            $phpmailer->SMTPAuth   = true;
            $phpmailer->Port       = $_ENV['SMTP_PORT'];
            $phpmailer->Username   = $_ENV['SMTP_USER'];
            $phpmailer->Password   = $_ENV['SMTP_PASS'];
            $phpmailer->SMTPSecure = 'ssl';
            $phpmailer->From       = $_ENV['SMTP_FROM'];
            $phpmailer->FromName   = $_ENV['SMTP_NAME'];
            $phpmailer->SMTPOptions = [
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                ],
            ];
        });
    }
    function my_child_theme_enqueue_styles()
    {
        global $is_mobile;
        wp_enqueue_style(
            'twentysixteen-child-style', 
            get_stylesheet_directory_uri() . '/style.css',
            array('twentysixteen-parent-style') // dependency
        );
        if ($is_mobile)
        {
            wp_enqueue_style(
                'gavin-mobile-style', 
                get_stylesheet_directory_uri() . '/css/mobile.css'
            );
        }
        else
        {
            wp_enqueue_style(
                'gavin-desktop-style', 
                get_stylesheet_directory_uri() . '/css/desktop.css'
            );

        }
        if ($is_mobile)
        {
            wp_enqueue_script(
                'gavin-mobile-js',
                get_stylesheet_directory_uri() . '/js/mobile.js',
                array('jquery'), // or empty array if no dependency
                time(),
                true // load in footer
            );
        }
        else
        {
            wp_enqueue_script(
                'gavin-desktop-js',
                get_stylesheet_directory_uri() . '/js/desktop.js',
                array('jquery'), // or empty array if no dependency
                time(),
                true // load in footer
            );            
        }
        wp_enqueue_style(
            'font-awesome',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
            array(),
            '6.5.1'
        );
        wp_enqueue_style(
            'google-fonts-marcellus-sc',
            'https://fonts.googleapis.com/css2?family=Marcellus+SC&display=swap',
            false
        );
    }
    function add_woocommerce_support()
    {
        add_theme_support('woocommerce');
    }    

    function my_childtheme_add_color_scheme($schemes)
    {
        $schemes['yellow_highlight'] = array(
            'label'  => __( 'Yellow Highlight', 'twentysixteen-child' ),
            // Order: background, text, link, secondary text, border
            'colors' => array(
                '#efefef', // background
                '#333333', // text
                '#222222', // link
                '#666666', // secondary text
                '#cccccc', // border/other
            ),
        );
        return $schemes;
    }
    function my_childtheme_custom_color_scheme_css()
    {
        $scheme = get_theme_mod( 'color_scheme', 'default' );
        $schemes = twentysixteen_get_color_schemes();
        if ( ! isset( $schemes[ $scheme ] ) )
        {
            $scheme = 'default';
        }
        $colors = $schemes[ $scheme ]['colors'];
        ?>
        <style type="text/css">
            body
            {
                background-color: <?php echo esc_attr( $colors[0] ); ?>;
                color: <?php echo esc_attr( $colors[1] ); ?>;
            }
            a
            {
                color: <?php echo esc_attr( $colors[2] ); ?>;
            }
            .site-header,
            .site-footer
            {
                border-color: <?php echo esc_attr( $colors[4] ); ?>;
            }
        </style>
        <?php
    }
    function enqueue_cart_fragment_support()
    {
        //global $is_mobile;
        //if ($is_mobile) return;        
        if (class_exists('WooCommerce'))
        {
            wp_enqueue_script('wc-cart-fragments');
        }
    }
    function render_mini_cart()
    {
        global $is_mobile;
        if ($is_mobile) wp_die();
        woocommerce_mini_cart();
        wp_die();
    }
function gavin_header_cart_fragment($fragments) {
        $log = new WC_Logger();
        $log->add('gavin', 'gavin_header_cart_fragment fired at ' . current_time('mysql'));
        $log->add('gavin', print_r($fragments,true));
        // Fragment for the cart count
        ob_start();
        ?>
        <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
        <?php
        $fragments['span.cart-count'] = ob_get_clean();

        // Fragment for the mini-cart content
        ob_start();
        woocommerce_mini_cart();
        $fragments['div.mini-cart-container'] = ob_get_clean();
        $log->add('gavin', print_r($fragments,true));
        return $fragments;
    }
    function gavin_enqueue_google_fonts()
    {
        wp_enqueue_style( 'gavin-google-fonts-roboto', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap', false );
    }
    function gavin_hide_page_titles($title,$id)
    {
        if (is_page($id)&&in_the_loop()&&!is_admin())
        {
            return '';
        }
        return $title;
    }
    function my_remove_twenty_sixteen_inline_colors()
    {
        remove_action('wp_head','twentysixteen_colors_css_wrap',11);
    }    
}
new GJGOFunctions();

if (!function_exists('getUserIDByEmail'))
{
    function getUserIDByEmail($email_address)
    {
        if (empty($email_address) || !is_email($email_address))
        {
            return false;
        }
        $user = get_user_by('email', $email_address);
        if ($user && isset($user->ID))
        {
            return $user->ID;
        }
        return false;
    }
}
?>