<?php
/*
Plugin Name: Floating Labels for Contact Form 7
Description: Adds floating labels functionality to Contact Form 7.
Version: 0.1.6
Author: Artur Nalobin
Plugin URI: https://github.com/scriptvoyager/floating-labels-for-contact-form-7/
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

define('FLCF7_DEFAULT_CSS',
	'#flcf7 > p {
	font-size: 16px;
	position: relative;
}
#flcf7 label {
	padding-left: 15px;
	position: absolute;
	top: 55px;
	transform: translateY(-50%);
	pointer-events: none;
	font-weight: 800;
	transition: top .2s;
	color: #AFAFAD;
}
#flcf7 input:not([type=submit]) {
	padding-top: 2px;
	padding-bottom: 2px;
	padding-left: 15px;
	padding-right: 5px;
	width: 100%;
	background-color: transparent;
	font-size: 16px;
}
#flcf7 textarea {
	padding-top: 2px;
	padding-bottom: 2px;
	padding-left: 15px;
	padding-right: 5px;
	width: 100%;
	background-color: transparent;
	font-size: 16px;
}
#flcf7 textarea {
	padding-top: 10px;
}
#flcf7 label.has-value {
	background-color: white;
	position: absolute;
	z-index: 999;
	top: 30px;
	font-size: 11px;
	margin-left: 15px;
	padding-left: 6px;
	padding-right: 6px;
	border: solid;
	border-width: 1px;
	border-color: #B0A1BF;
}'
);

function flcf7_enqueue_scripts() {
    wp_enqueue_script('flcf7-scripts', plugins_url('scripts.js', __FILE__), array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'flcf7_enqueue_scripts');

function flcf7_settings_menu() {
    add_options_page(
        'Floating Labels for CF7 Einstellungen',
        'Floating Labels for CF7',
        'manage_options',
        'flcf7-settings',
        'flcf7_settings_page'
    );
}
add_action('admin_menu', 'flcf7_settings_menu');

function flcf7_settings_page() {
    ?>
    <div class="wrap">
        <h2>Floating Labels for CF7</h2>

        <!-- Add note -->
        <div style="background-color: #FFF5E1; border-left: 4px solid #FFC107; padding: 10px; margin-bottom: 20px;">
            <strong>Note:</strong> Make sure you add the <code>html_id="flcf7"</code> attribute to your Contact Form 7 shortcode, e.g. <code>[contact-form-7 id="8793564" title="Contact Form" html_id="flcf7"]</code>.
        </div>
        <!-- End of the note -->

        <form action="options.php" method="post">
            <?php
            settings_fields('flcf7_options_group');
            do_settings_sections('flcf7-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}


function flcf7_register_settings() {
    register_setting('flcf7_options_group', 'flcf7_custom_css', 'flcf7_sanitize_css');
    add_settings_section('flcf7_settings_section', 'Custom CSS', null, 'flcf7-settings');
    add_settings_field('flcf7_custom_css_field', 'Add your CSS here:', 'flcf7_custom_css_display', 'flcf7-settings', 'flcf7_settings_section');
}
add_action('admin_init', 'flcf7_register_settings');

function flcf7_custom_css_display() {
    $css = get_option('flcf7_custom_css', FLCF7_DEFAULT_CSS);
    $css = stripslashes($css);

    // Start of the flex container
    echo "<div style='display: flex; gap: 20px;'>";

    // CSS input field
    echo "<div>";
    echo "<h3>Your CSS settings</h3>";
    echo "<textarea id='custom-css' name='flcf7_custom_css' rows='40' cols='80'>{$css}</textarea>"; // Gives the textarea an ID to be able to address it more easily
    echo "</div>";

    // Show default CSS
    echo "<div>";
    echo "<h3>Default CSS settings</h3>";
    echo "<pre style='background-color: #f8f8f8; padding: 10px; border: 1px solid #ddd; height: fit-content;'>".htmlspecialchars(FLCF7_DEFAULT_CSS)."</pre>";
    echo "<button type='button' id='set-default-css' style='margin-top: 10px;'>Set default CSS</button>"; // Add button
    echo "</div>";

    // End of the flex container
    echo "</div>";

    // JavaScript to set the default CSS in the textarea
    echo "
    <script type='text/javascript'>
        document.getElementById('set-default-css').addEventListener('click', function() {
            document.getElementById('custom-css').value = `".str_replace('`', '\\`', FLCF7_DEFAULT_CSS)."`; // Set the default CSS
        });
    </script>
    ";
}

function flcf7_sanitize_css($input) {
    // Here you can add more sanitization rules if necessary.
    return wp_strip_all_tags($input);
}

function flcf7_enqueue_custom_css() {
    $css = get_option('flcf7_custom_css', FLCF7_DEFAULT_CSS);
    if (!empty($css)) {
        wp_add_inline_style('wp-block-library', $css); // wp-block-library is the default stylesheet handle of WordPress
    }
}
add_action('wp_enqueue_scripts', 'flcf7_enqueue_custom_css', 20);
