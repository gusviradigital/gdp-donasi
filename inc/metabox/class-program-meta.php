<?php
/**
 * Program Meta Box
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

namespace GDP\Metabox;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Program Meta Box Class
 */
class Program_Meta {
    /**
     * Instance of this class.
     *
     * @var object
     */
    private static $instance;

    /**
     * Get instance of this class
     */
    public static function get_instance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'add_meta_boxes', [ $this, 'add_meta_box' ] );
        add_action( 'save_post', [ $this, 'save_meta_box' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts( $hook ) {
        global $post;

        if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
            if ( 'program' === $post->post_type ) {
                wp_enqueue_style( 'gdp-metabox', GDP_CSS . '/admin/metabox.css', [], GDP_VERSION );
            }
        }
    }

    /**
     * Add meta box
     */
    public function add_meta_box() {
        add_meta_box(
            'program_details',
            __( 'Program Details', 'gusviradigital' ),
            [ $this, 'render_meta_box' ],
            'program',
            'normal',
            'high'
        );
    }

    /**
     * Render meta box
     */
    public function render_meta_box( $post ) {
        // Add nonce for security
        wp_nonce_field( 'program_meta_box', 'program_meta_box_nonce' );

        // Get saved values
        $target = get_post_meta( $post->ID, '_donation_target', true );
        $collected = get_post_meta( $post->ID, '_donation_collected', true );
        $deadline = get_post_meta( $post->ID, '_donation_deadline', true );
        $is_featured = get_post_meta( $post->ID, '_is_featured', true );
        $form_type = get_post_meta( $post->ID, '_form_type', true );
        $form_style = get_post_meta( $post->ID, '_form_style', true );
        $suggested_amounts = get_post_meta( $post->ID, '_suggested_amounts', true );
        $custom_amount = get_post_meta( $post->ID, '_custom_amount', true );
        $zakat_type = get_post_meta( $post->ID, '_zakat_type', true );
        $zakat_percent = get_post_meta( $post->ID, '_zakat_percent', true );
        $zakat_custom_percent = get_post_meta( $post->ID, '_zakat_custom_percent', true );
        $zakat_expense = get_post_meta( $post->ID, '_zakat_expense', true );
        $zakat_expense_title = get_post_meta( $post->ID, '_zakat_expense_title', true );
        $package_amount = get_post_meta( $post->ID, '_package_amount', true );
        $package_title = get_post_meta( $post->ID, '_package_title', true );

        // Default values
        if (!$form_type) $form_type = 'donation';
        if (!$form_style) $form_style = 'card';
        if (!$suggested_amounts) $suggested_amounts = ['50000', '100000', '250000', '500000'];
        if (!$zakat_percent) $zakat_percent = 'default';
        ?>
        <div class="gdp-metabox">
            <div class="form-group">
                <label><?php esc_html_e( 'Form Type', 'gusviradigital' ); ?></label>
                <div class="form-type-selector">
                    <div class="form-type-tabs">
                        <label class="form-type-tab <?php echo $form_type === 'donation' ? 'active' : ''; ?>">
                            <input type="radio" name="form_type" value="donation" <?php checked( $form_type, 'donation' ); ?>>
                            <?php esc_html_e( 'Donation', 'gusviradigital' ); ?>
                        </label>
                        <label class="form-type-tab <?php echo $form_type === 'zakat' ? 'active' : ''; ?>">
                            <input type="radio" name="form_type" value="zakat" <?php checked( $form_type, 'zakat' ); ?>>
                            <?php esc_html_e( 'Zakat', 'gusviradigital' ); ?>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label><?php esc_html_e( 'Form Style', 'gusviradigital' ); ?></label>
                <div class="form-style-options">
                    <label class="form-style-option">
                        <input type="radio" name="form_style" value="card" <?php checked( $form_style, 'card' ); ?>>
                        <?php esc_html_e( 'Card', 'gusviradigital' ); ?>
                    </label>
                    <label class="form-style-option">
                        <input type="radio" name="form_style" value="typing" <?php checked( $form_style, 'typing' ); ?>>
                        <?php esc_html_e( 'Typing', 'gusviradigital' ); ?>
                    </label>
                    <label class="form-style-option">
                        <input type="radio" name="form_style" value="packaged" <?php checked( $form_style, 'packaged' ); ?>>
                        <?php esc_html_e( 'Packaged', 'gusviradigital' ); ?>
                    </label>
                </div>
            </div>

            <div class="form-fields donation-fields" style="display: <?php echo $form_type === 'donation' ? 'block' : 'none'; ?>">
                <div class="form-group card-fields" style="display: <?php echo $form_style === 'card' ? 'block' : 'none'; ?>">
                    <label><?php esc_html_e( 'Suggested Amounts', 'gusviradigital' ); ?></label>
                    <div class="suggested-amounts">
                        <?php foreach ($suggested_amounts as $index => $amount) : ?>
                            <input type="number" name="suggested_amounts[]" value="<?php echo esc_attr($amount); ?>" placeholder="Rp">
                        <?php endforeach; ?>
                        <input type="number" name="custom_amount" value="<?php echo esc_attr($custom_amount); ?>" placeholder="<?php esc_attr_e('OTHER NOMINAL', 'gusviradigital'); ?>">
                    </div>
                </div>

                <div class="form-group typing-fields" style="display: <?php echo $form_style === 'typing' ? 'block' : 'none'; ?>">
                    <label><?php esc_html_e( 'Typing Amount', 'gusviradigital' ); ?></label>
                    <input type="number" name="typing_amount" placeholder="Rp xx.xxx.xxx">
                </div>

                <div class="form-group packaged-fields" style="display: <?php echo $form_style === 'packaged' ? 'block' : 'none'; ?>">
                    <label><?php esc_html_e( 'Package Amount', 'gusviradigital' ); ?></label>
                    <input type="number" name="package_amount" value="<?php echo esc_attr($package_amount); ?>" placeholder="Rp">
                    <label><?php esc_html_e( 'Package Title', 'gusviradigital' ); ?></label>
                    <input type="text" name="package_title" value="<?php echo esc_attr($package_title); ?>" placeholder="<?php esc_attr_e('Paket Donasi', 'gusviradigital'); ?>">
                </div>
            </div>

            <div class="form-fields zakat-fields" style="display: <?php echo $form_type === 'zakat' ? 'block' : 'none'; ?>">
                <div class="form-group">
                    <label><?php esc_html_e( 'Zakat Type', 'gusviradigital' ); ?></label>
                    <select name="zakat_type">
                        <option value="penghasilan" <?php selected($zakat_type, 'penghasilan'); ?>><?php esc_html_e('Zakat Penghasilan', 'gusviradigital'); ?></option>
                        <option value="maal" <?php selected($zakat_type, 'maal'); ?>><?php esc_html_e('Zakat Maal', 'gusviradigital'); ?></option>
                        <option value="fitrah" <?php selected($zakat_type, 'fitrah'); ?>><?php esc_html_e('Zakat Fitrah', 'gusviradigital'); ?></option>
                    </select>
                </div>

                <div class="form-group">
                    <label><?php esc_html_e( 'Zakat Percent', 'gusviradigital' ); ?></label>
                    <div class="zakat-percent-options">
                        <label>
                            <input type="radio" name="zakat_percent" value="default" <?php checked($zakat_percent, 'default'); ?>>
                            <?php esc_html_e('Default (2.5%)', 'gusviradigital'); ?>
                        </label>
                        <label>
                            <input type="radio" name="zakat_percent" value="custom" <?php checked($zakat_percent, 'custom'); ?>>
                            <?php esc_html_e('Custom', 'gusviradigital'); ?>
                        </label>
                        <input type="number" name="zakat_custom_percent" value="<?php echo esc_attr($zakat_custom_percent); ?>" 
                               style="display: <?php echo $zakat_percent === 'custom' ? 'block' : 'none'; ?>" 
                               step="0.1" min="0" max="100" placeholder="%">
                    </div>
                </div>

                <div class="form-group">
                    <label class="checkbox-wrapper">
                        <input type="checkbox" name="zakat_expense" value="1" <?php checked($zakat_expense, '1'); ?>>
                        <?php esc_html_e('Pengeluaran', 'gusviradigital'); ?>
                    </label>
                    <input type="text" name="zakat_expense_title" value="<?php echo esc_attr($zakat_expense_title); ?>" 
                           style="display: <?php echo $zakat_expense === '1' ? 'block' : 'none'; ?>"
                           placeholder="<?php esc_attr_e('Judul Pengeluaran', 'gusviradigital'); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="donation_target">
                    <?php esc_html_e( 'Target Donasi (Rp)', 'gusviradigital' ); ?>
                </label>
                <input type="number" id="donation_target" name="donation_target" value="<?php echo esc_attr( $target ); ?>">
            </div>

            <div class="form-group">
                <label for="donation_collected">
                    <?php esc_html_e( 'Terkumpul (Rp)', 'gusviradigital' ); ?>
                </label>
                <input type="number" id="donation_collected" name="donation_collected" value="<?php echo esc_attr( $collected ); ?>">
            </div>

            <div class="form-group">
                <label for="donation_deadline">
                    <?php esc_html_e( 'Deadline', 'gusviradigital' ); ?>
                </label>
                <input type="date" id="donation_deadline" name="donation_deadline" value="<?php echo esc_attr( $deadline ); ?>">
            </div>

            <div class="form-group">
                <div class="checkbox-wrapper">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1" <?php checked( $is_featured, '1' ); ?>>
                    <label for="is_featured">
                        <?php esc_html_e( 'Program Unggulan', 'gusviradigital' ); ?>
                    </label>
                </div>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            // Form Type Toggle
            $('input[name="form_type"]').on('change', function() {
                const type = $(this).val();
                $('.donation-fields, .zakat-fields').hide();
                if (type === 'donation') {
                    $('.donation-fields').show();
                } else {
                    $('.zakat-fields').show();
                }
            });

            // Form Style Toggle
            $('input[name="form_style"]').on('change', function() {
                const style = $(this).val();
                $('.card-fields, .typing-fields, .packaged-fields').hide();
                if (style === 'card') {
                    $('.card-fields').show();
                } else if (style === 'typing') {
                    $('.typing-fields').show();
                } else {
                    $('.packaged-fields').show();
                }
            });

            // Zakat Percent Toggle
            $('input[name="zakat_percent"]').on('change', function() {
                if ($(this).val() === 'custom') {
                    $('input[name="zakat_custom_percent"]').show();
                } else {
                    $('input[name="zakat_custom_percent"]').hide();
                }
            });

            // Zakat Expense Toggle
            $('input[name="zakat_expense"]').on('change', function() {
                if ($(this).is(':checked')) {
                    $('input[name="zakat_expense_title"]').show();
                } else {
                    $('input[name="zakat_expense_title"]').hide();
                }
            });
        });
        </script>
        <?php
    }

    /**
     * Save meta box
     */
    public function save_meta_box( $post_id ) {
        // Check if nonce is set
        if ( ! isset( $_POST['program_meta_box_nonce'] ) ) {
            return;
        }

        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['program_meta_box_nonce'], 'program_meta_box' ) ) {
            return;
        }

        // If this is an autosave, don't do anything
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Check user permissions
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Save form type settings
        if ( isset( $_POST['form_type'] ) ) {
            update_post_meta( $post_id, '_form_type', sanitize_text_field( $_POST['form_type'] ) );
        }

        if ( isset( $_POST['form_style'] ) ) {
            update_post_meta( $post_id, '_form_style', sanitize_text_field( $_POST['form_style'] ) );
        }

        // Save donation fields
        if ( isset( $_POST['suggested_amounts'] ) ) {
            update_post_meta( $post_id, '_suggested_amounts', array_map( 'sanitize_text_field', $_POST['suggested_amounts'] ) );
        }

        if ( isset( $_POST['custom_amount'] ) ) {
            update_post_meta( $post_id, '_custom_amount', sanitize_text_field( $_POST['custom_amount'] ) );
        }

        if ( isset( $_POST['package_amount'] ) ) {
            update_post_meta( $post_id, '_package_amount', sanitize_text_field( $_POST['package_amount'] ) );
        }

        if ( isset( $_POST['package_title'] ) ) {
            update_post_meta( $post_id, '_package_title', sanitize_text_field( $_POST['package_title'] ) );
        }

        // Save zakat fields
        if ( isset( $_POST['zakat_type'] ) ) {
            update_post_meta( $post_id, '_zakat_type', sanitize_text_field( $_POST['zakat_type'] ) );
        }

        if ( isset( $_POST['zakat_percent'] ) ) {
            update_post_meta( $post_id, '_zakat_percent', sanitize_text_field( $_POST['zakat_percent'] ) );
        }

        if ( isset( $_POST['zakat_custom_percent'] ) ) {
            update_post_meta( $post_id, '_zakat_custom_percent', sanitize_text_field( $_POST['zakat_custom_percent'] ) );
        }

        if ( isset( $_POST['zakat_expense'] ) ) {
            update_post_meta( $post_id, '_zakat_expense', '1' );
        } else {
            delete_post_meta( $post_id, '_zakat_expense' );
        }

        if ( isset( $_POST['zakat_expense_title'] ) ) {
            update_post_meta( $post_id, '_zakat_expense_title', sanitize_text_field( $_POST['zakat_expense_title'] ) );
        }

        // Save basic fields
        if ( isset( $_POST['donation_target'] ) ) {
            update_post_meta( $post_id, '_donation_target', sanitize_text_field( $_POST['donation_target'] ) );
        }

        if ( isset( $_POST['donation_collected'] ) ) {
            update_post_meta( $post_id, '_donation_collected', sanitize_text_field( $_POST['donation_collected'] ) );
        }

        if ( isset( $_POST['donation_deadline'] ) ) {
            update_post_meta( $post_id, '_donation_deadline', sanitize_text_field( $_POST['donation_deadline'] ) );
        }

        // Save featured status
        $is_featured = isset( $_POST['is_featured'] ) ? '1' : '0';
        update_post_meta( $post_id, '_is_featured', $is_featured );
    }
}

// Initialize Program Meta Box
Program_Meta::get_instance(); 