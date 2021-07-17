<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */


/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if (! class_exists('Timber')) {
    add_action(
        'admin_notices',
        function () {
            echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url(admin_url('plugins.php#timber')) . '">' . esc_url(admin_url('plugins.php')) . '</a></p></div>';
        }
    );

    add_filter(
        'template_include',
        function ($template) {
            return get_stylesheet_directory() . '/static/no-timber.html';
        }
    );
    return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site
{
    /** Add timber support. */
    public function __construct()
    {
        add_action('after_setup_theme', array( $this, 'theme_supports' ));
        add_action('after_setup_theme', array( $this, 'register_menus' ));
        add_filter('timber/context', array( $this, 'add_to_context' ));
        add_filter('timber/twig', array( $this, 'add_to_twig' ));
        add_action('init', array( $this, 'register_post_types' ));
        add_action('init', array( $this, 'register_taxonomies' ));
        add_action('widgets_init', array( $this, 'register_widgets' ));

        parent::__construct();
    }

    public function register_menus()
    {
        register_nav_menus(
            array(
                'primary' => 'Primary Menu',
                'footer' => 'Footer Menu',
            )
        );
    }

    /** This is where you can register custom post types. */
    public function register_post_types()
    {
    }
    /** This is where you can register custom taxonomies. */
    public function register_taxonomies()
    {
    }

    /** This is where you add some context
     *
     * @param string $context context['this'] Being the Twig's {{ this }}.
     */
    public function add_to_context($context)
    {
        $context['custom_logo_url']  = wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full');
        $context['hero'] = get_the_post_thumbnail_url(get_the_ID(), 'full');
        $context['menuPrimary']  = new Timber\Menu('primary');
        $context['menuFooter']  = new Timber\Menu('footer');
        $context['footer_form'] = Timber::get_widgets('footer_form');
        $context['site']  = $this;
        return $context;
    }

    public function theme_supports()
    {
        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        add_image_size('hero-image', 1200);

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support(
            'html5',
            array(
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
            )
        );

        /*
         * Enable support for Post Formats.
         *
         * See: https://codex.wordpress.org/Post_Formats
         */
        add_theme_support(
            'post-formats',
            array(
                'aside',
                'image',
                'video',
                'quote',
                'link',
                'gallery',
                'audio',
            )
        );

        add_theme_support('menus');

        $defaults = array(
            'height' => 100,
            'width' => 200,
            'flex-height' => false,
            'flex-with' => false,
            'unlink-homepage-logo' => true,
        );

        add_theme_support('custom-logo', $defaults);
    }


    /** This is where you can add your own functions to twig.
     *
     * @param string $twig get extension.
     */
    public function add_to_twig($twig)
    {
        $twig->addExtension(new Twig\Extension\StringLoaderExtension());
        $twig->addFilter(new Twig\TwigFilter('myfoo', array( $this, 'myfoo' )));
        return $twig;
    }

    public function register_widgets()
    {
        register_sidebar(array(
        'name' => __('Footer Sidebar', 'footer_form'),
        'id' => 'footer_form',
        'description' => __('Description', 'footer_form'),
        'before_widget' => '<li class="widget">',
        'after_widget' => '</li>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
    }
}

new StarterSite();
