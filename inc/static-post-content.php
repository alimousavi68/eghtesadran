<?php
/**
 * Static Post Content Admin Settings Page and Integration for Eghtesadran Theme.
 *
 * @package Eghtesadran
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register settings page.
 */
add_action( 'admin_menu', 'eghtesadran_register_static_content_menu' );
function eghtesadran_register_static_content_menu() {
	add_options_page(
		__( 'تنظیمات محتوای ثابت نوشته‌ها', 'eghtesadran' ),
		__( 'محتوای ثابت نوشته‌ها', 'eghtesadran' ),
		'manage_options',
		'eghtesadran-static-content',
		'eghtesadran_static_content_page_html'
	);
}

/**
 * Register settings in option table.
 */
add_action( 'admin_init', 'eghtesadran_register_static_content_settings' );
function eghtesadran_register_static_content_settings() {
	register_setting(
		'eghtesadran_static_content_group',
		'eghtesadran_static_content_enabled',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'eghtesadran_sanitize_toggle',
			'default'           => '0',
		)
	);

	register_setting(
		'eghtesadran_static_content_group',
		'eghtesadran_static_content_text',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'wp_kses_post',
			'default'           => '',
		)
	);
}

/**
 * Sanitize the toggle switch input.
 *
 * @param mixed $input Input value.
 * @return string '1' or '0'.
 */
function eghtesadran_sanitize_toggle( $input ) {
	return ( '1' === $input || 'on' === $input || true === $input ) ? '1' : '0';
}

/**
 * Renders the custom admin page HTML.
 */
function eghtesadran_static_content_page_html() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Show settings updated message if settings were saved.
	if ( isset( $_GET['settings-updated'] ) ) {
		add_settings_error(
			'eghtesadran_messages',
			'eghtesadran_message',
			__( 'تنظیمات با موفقیت ذخیره شدند.', 'eghtesadran' ),
			'updated'
		);
	}

	settings_errors( 'eghtesadran_messages' );

	$enabled = get_option( 'eghtesadran_static_content_enabled', '0' );
	$content = get_option( 'eghtesadran_static_content_text', '' );
	?>
	<div class="wrap">
		<h1 style="margin-bottom: 20px;"><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<form action="options.php" method="post" style="margin-top: 20px; max-width: 900px; background: #fff; padding: 25px; border: 1px solid #ccd0d4; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,.05);">
			<?php
			settings_fields( 'eghtesadran_static_content_group' );
			?>

			<table class="form-table" role="presentation" style="margin-top: 0;">
				<tbody>
					<tr style="border-bottom: 1px solid #f0f0f1;">
						<th scope="row" style="padding: 20px 0; width: 250px;">
							<label for="eghtesadran_static_content_enabled" style="font-weight: 600; font-size: 14px;"><?php esc_html_e( 'وضعیت نمایش محتوای ثابت', 'eghtesadran' ); ?></label>
						</th>
						<td style="padding: 20px 0;">
							<label class="eghtesadran-switch" style="position: relative; display: inline-block; width: 50px; height: 26px;">
								<input type="hidden" name="eghtesadran_static_content_enabled" value="0">
								<input type="checkbox" id="eghtesadran_static_content_enabled" name="eghtesadran_static_content_enabled" value="1" <?php checked( $enabled, '1' ); ?> style="opacity: 0; width: 0; height: 0;">
								<span class="eghtesadran-slider" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px;"></span>
							</label>
							<p class="description" style="margin-top: 8px;"><?php esc_html_e( 'در صورت فعال‌بودن، محتوای ثابت زیر به صورت خودکار در ابتدای هر نوشته جدید قرار می‌گیرد.', 'eghtesadran' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row" style="padding: 20px 0;">
							<label for="eghtesadran_static_content_text" style="font-weight: 600; font-size: 14px;"><?php esc_html_e( 'محتوای متنی ثابت', 'eghtesadran' ); ?></label>
						</th>
						<td style="padding: 20px 0;">
							<?php
							$settings = array(
								'textarea_name' => 'eghtesadran_static_content_text',
								'media_buttons' => true,
								'textarea_rows' => 12,
								'tinymce'       => array(
									'theme_advanced_buttons1' => 'bold,italic,underline,strikethrough,forecolor,backcolor,link,unlink,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,undo,redo',
								),
								'quicktags'     => true,
							);
							wp_editor( $content, 'eghtesadran_static_content_text', $settings );
							?>
							<p class="description" style="margin-top: 8px;"><?php esc_html_e( 'محتوای ثابت دلخواه خود (شامل متن، رنگ‌ها و پیوندها) را وارد کنید.', 'eghtesadran' ); ?></p>
						</td>
					</tr>
				</tbody>
			</table>

			<div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #f0f0f1;">
				<?php submit_button( __( 'ذخیره تنظیمات', 'eghtesadran' ), 'primary', 'submit', false ); ?>
			</div>
		</form>
	</div>

	<style>
		/* Modern toggle switch styling */
		.eghtesadran-switch input:checked + .eghtesadran-slider {
			background-color: #2271b1;
		}
		.eghtesadran-slider:before {
			position: absolute;
			content: "";
			height: 18px;
			width: 18px;
			left: 4px;
			bottom: 4px;
			background-color: white;
			transition: .4s;
			border-radius: 50%;
		}
		.eghtesadran-switch input:checked + .eghtesadran-slider:before {
			transform: translateX(24px);
		}
		/* RTL support for toggle slider */
		.rtl .eghtesadran-switch input:checked + .eghtesadran-slider:before {
			transform: translateX(-24px);
		}
	</style>
	<?php
}

/**
 * Filter to insert static content into new post editor.
 *
 * @param string  $content Default post content.
 * @param WP_Post $post    Post object.
 * @return string Filtered post content.
 */
add_filter( 'default_content', 'eghtesadran_insert_static_content', 10, 2 );
function eghtesadran_insert_static_content( $content, $post ) {
	if ( isset( $post->post_type ) && 'post' === $post->post_type ) {
		$enabled = get_option( 'eghtesadran_static_content_enabled', '0' );
		if ( '1' === $enabled ) {
			$static_content = get_option( 'eghtesadran_static_content_text', '' );
			if ( ! empty( $static_content ) ) {
				return $static_content;
			}
		}
	}
	return $content;
}
