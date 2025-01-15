<?php
/**
 * The template for displaying the footer
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$theme_options = GDP\Core\Theme_Options::get_instance();
$is_dark_mode = $theme_options->is_dark_mode();
$logo_url = $theme_options->get_logo_url();
$logo_dimensions = $theme_options->get_logo_dimensions();
?>

</main><!-- #main -->


<?php wp_footer(); ?>
</body>
</html>