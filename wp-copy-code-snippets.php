<?php
/**
 * Plugin Name: WP Copy Code Snippets
 * Description: Menambahkan kotak kode dengan tombol "Copy" mirip ChatGPT untuk code snippets.
 * Version:     1.0.0
 * Author:      Lanang
 * Text Domain: wp-copy-code-snippets
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WP_Copy_Code_Snippets {

    public function __construct() {
        // Enqueue asset front-end
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );

        // Shortcode [copy_code lang="php"]...[/copy_code]
        add_shortcode( 'copy_code', [ $this, 'render_copy_code_shortcode' ] );
    }

    /**
     * Enqueue CSS & JS
     */
    public function enqueue_assets() {
        $plugin_url = plugin_dir_url( __FILE__ );

        wp_enqueue_style(
            'wpcc-style',
            $plugin_url . 'assets/css/copy-code.css',
            [],
            '1.0.0'
        );

        wp_enqueue_script(
            'wpcc-script',
            $plugin_url . 'assets/js/copy-code.js',
            [ 'jquery' ],
            '1.0.0',
            true
        );
    }

    /**
     * Shortcode handler: [copy_code lang="php"]...[/copy_code]
     */
    public function render_copy_code_shortcode( $atts, $content = null ) {
        $atts = shortcode_atts(
            [
                'lang' => '', // misal: php, js, html, css
            ],
            $atts,
            'copy_code'
        );

        $code = trim( (string) $content );

        // Escape untuk keamanan
        $code_escaped = esc_html( $code );
        $lang_class   = $atts['lang'] ? ' language-' . esc_attr( $atts['lang'] ) : '';

        ob_start();
        ?>
        <div class="wpcc-snippet">
            <button class="wpcc-copy-button" type="button" aria-label="Copy code">
                <span class="wpcc-copy-icon">
                    <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
                        <rect x="9" y="9" width="10" height="10" rx="2" ry="2"></rect>
                        <path d="M5 15V5a2 2 0 0 1 2-2h10"></path>
                    </svg>
                </span>
                <span class="wpcc-copy-label">Copy</span>
            </button>
            <pre class="wpcc-code-wrapper">
<code class="wpcc-code<?php echo $lang_class; ?>"><?php echo $code_escaped; ?></code>
            </pre>
        </div>
        <?php
        return ob_get_clean();
    }
}

// Inisiasi class
new WP_Copy_Code_Snippets();
