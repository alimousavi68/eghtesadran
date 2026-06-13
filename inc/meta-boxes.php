<?php
/**
 * Advanced News Meta Box for Eghtesadran Theme.
 *
 * @package Eghtesadran
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Eghtesadran_News_Meta_Box {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box() {
		add_meta_box(
			'eghtesadran_news_meta_box',
			__( 'تنظیمات پیشرفته خبر', 'eghtesadran' ),
			array( $this, 'render_meta_box' ),
			'post',
			'normal',
			'high'
		);
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts( $hook ) {
		if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
			return;
		}

		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( ! $screen || 'post' !== $screen->post_type ) {
			return;
		}

		wp_enqueue_media();

		wp_enqueue_script(
			'eghtesadran-admin-metabox',
			get_template_directory_uri() . '/assets/js/admin-metabox.js',
			array( 'jquery' ),
			'1.0.0',
			true
		);

		wp_enqueue_style(
			'eghtesadran-admin-metabox',
			get_template_directory_uri() . '/assets/css/admin-metabox.css',
			array(),
			'1.0.0'
		);
	}

	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box( $post ) {
		// Add a nonce field so we can check for it later.
		wp_nonce_field( 'eghtesadran_news_save_meta_box_data', 'eghtesadran_news_meta_box_nonce' );

		// Retrieve existing values
		$lead  = get_post_meta( $post->ID, '_eghtesadran_lead', true );
		$badge = get_post_meta( $post->ID, '_eghtesadran_badge', true );

		$content_type = get_post_meta( $post->ID, '_news_content_type', true );
		if ( empty( $content_type ) ) {
			$content_type = 'standard';
		}
		$rotiter = get_post_meta( $post->ID, '_news_rotiter', true );

		// Note Fields
		$author_name     = get_post_meta( $post->ID, '_news_author_name', true );
		$author_position = get_post_meta( $post->ID, '_news_author_position', true );

		// Interview Fields
		$interviewee_name     = get_post_meta( $post->ID, '_news_interviewee_name', true );
		$interviewee_position = get_post_meta( $post->ID, '_news_interviewee_position', true );

		// Video Fields
		$video_duration    = get_post_meta( $post->ID, '_news_video_duration', true );
		$video_source_type = get_post_meta( $post->ID, '_news_video_source_type', true );
		if ( empty( $video_source_type ) ) {
			$video_source_type = 'direct';
		}
		$video_hq_link    = get_post_meta( $post->ID, '_news_video_hq_link', true );
		$video_lq_link    = get_post_meta( $post->ID, '_news_video_lq_link', true );
		$video_embed_code = get_post_meta( $post->ID, '_news_video_embed_code', true );

		// Audio Fields
		$audio_duration = get_post_meta( $post->ID, '_news_audio_duration', true );
		$audio_file_id  = get_post_meta( $post->ID, '_news_audio_file_id', true );
		$audio_file_url = '';
		if ( $audio_file_id ) {
			$audio_file_url = wp_get_attachment_url( $audio_file_id );
		}

		// Photo Report Fields
		$photographer_name = get_post_meta( $post->ID, '_news_photographer_name', true );
		$gallery_images    = get_post_meta( $post->ID, '_news_gallery_images', true );

		// General Fields
		$primary_category = get_post_meta( $post->ID, '_news_primary_category', true );
		$source_name      = get_post_meta( $post->ID, '_news_source_name', true );
		$source_link      = get_post_meta( $post->ID, '_news_source_link', true );

		$badges = array(
			''         => __( 'بدون نشان', 'eghtesadran' ),
			'featured' => __( 'ویژه', 'eghtesadran' ),
			'breaking' => __( 'فوری', 'eghtesadran' ),
			'trending' => __( 'داغ', 'eghtesadran' ),
		);
		?>
		<div class="ns-metabox-wrapper">
			<!-- Rotiter Field -->
			<div class="ns-field-row">
				<label for="ns_rotiter"><strong><?php esc_html_e( 'روتیتر:', 'eghtesadran' ); ?></strong></label><br>
				<input type="text" name="_news_rotiter" id="ns_rotiter" value="<?php echo esc_attr( $rotiter ); ?>" class="widefat" maxlength="200">
			</div>

			<hr>

			<!-- Lead Field -->
			<div class="ns-field-row">
				<label for="eghtesadran_lead"><strong><?php esc_html_e( 'لید خبر (خلاصه ویژه):', 'eghtesadran' ); ?></strong></label><br>
				<textarea id="eghtesadran_lead" name="eghtesadran_lead" class="widefat" rows="3"><?php echo esc_textarea( $lead ); ?></textarea>
			</div>

			<!-- Badge Field -->
			<div class="ns-field-row">
				<label for="eghtesadran_badge"><strong><?php esc_html_e( 'نشان خبر:', 'eghtesadran' ); ?></strong></label><br>
				<select id="eghtesadran_badge" name="eghtesadran_badge" class="widefat">
					<?php foreach ( $badges as $value => $label ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $badge, $value ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<hr>

			<!-- Content Type -->
			<div class="ns-field-row">
				<label for="ns_content_type"><strong><?php esc_html_e( 'نوع محتوا:', 'eghtesadran' ); ?></strong></label><br>
				<select name="_news_content_type" id="ns_content_type" class="widefat">
					<option value="standard" <?php selected( $content_type, 'standard' ); ?>><?php esc_html_e( 'استاندارد', 'eghtesadran' ); ?></option>
					<option value="note" <?php selected( $content_type, 'note' ); ?>><?php esc_html_e( 'یادداشت', 'eghtesadran' ); ?></option>
					<option value="interview" <?php selected( $content_type, 'interview' ); ?>><?php esc_html_e( 'مصاحبه', 'eghtesadran' ); ?></option>
					<option value="video" <?php selected( $content_type, 'video' ); ?>><?php esc_html_e( 'ویدیو', 'eghtesadran' ); ?></option>
					<option value="audio" <?php selected( $content_type, 'audio' ); ?>><?php esc_html_e( 'صوتی', 'eghtesadran' ); ?></option>
					<option value="photo_report" <?php selected( $content_type, 'photo_report' ); ?>><?php esc_html_e( 'عکس / تصویری', 'eghtesadran' ); ?></option>
				</select>
			</div>

			<hr>

			<!-- Note Fields -->
			<div class="ns-conditional-section" data-show-if="note">
				<div class="ns-field-row">
					<label for="ns_author_name"><strong><?php esc_html_e( 'نام نویسنده:', 'eghtesadran' ); ?></strong></label>
					<input type="text" name="_news_author_name" id="ns_author_name" value="<?php echo esc_attr( $author_name ); ?>" class="widefat" maxlength="120">
				</div>
				<div class="ns-field-row">
					<label for="ns_author_position"><strong><?php esc_html_e( 'سمت نویسنده:', 'eghtesadran' ); ?></strong></label>
					<input type="text" name="_news_author_position" id="ns_author_position" value="<?php echo esc_attr( $author_position ); ?>" class="widefat" maxlength="120">
				</div>
			</div>

			<!-- Interview Fields -->
			<div class="ns-conditional-section" data-show-if="interview">
				<div class="ns-field-row">
					<label for="ns_interviewee_name"><strong><?php esc_html_e( 'نام مصاحبه‌شونده:', 'eghtesadran' ); ?></strong></label>
					<input type="text" name="_news_interviewee_name" id="ns_interviewee_name" value="<?php echo esc_attr( $interviewee_name ); ?>" class="widefat" maxlength="120">
				</div>
				<div class="ns-field-row">
					<label for="ns_interviewee_position"><strong><?php esc_html_e( 'سمت مصاحبه‌شونده:', 'eghtesadran' ); ?></strong></label>
					<input type="text" name="_news_interviewee_position" id="ns_interviewee_position" value="<?php echo esc_attr( $interviewee_position ); ?>" class="widefat" maxlength="120">
				</div>
			</div>

			<!-- Video Fields -->
			<div class="ns-conditional-section" data-show-if="video">
				<div class="ns-field-row">
					<label for="ns_video_duration"><strong><?php esc_html_e( 'زمان ویدیو (مثلاً ۰۵:۳۰):', 'eghtesadran' ); ?></strong></label>
					<input type="text" name="_news_video_duration" id="ns_video_duration" value="<?php echo esc_attr( $video_duration ); ?>" class="widefat">
				</div>
				
				<div class="ns-field-row">
					<label><strong><?php esc_html_e( 'نوع آدرس‌دهی:', 'eghtesadran' ); ?></strong></label><br>
					<label><input type="radio" name="_news_video_source_type" value="direct" <?php checked( $video_source_type, 'direct' ); ?>> <?php esc_html_e( 'آدرس مستقیم', 'eghtesadran' ); ?></label>
					&nbsp;&nbsp;
					<label><input type="radio" name="_news_video_source_type" value="embed" <?php checked( $video_source_type, 'embed' ); ?>> <?php esc_html_e( 'کد امبد (iframe)', 'eghtesadran' ); ?></label>
				</div>

				<div class="ns-sub-conditional" data-show-sub-if="direct">
					<div class="ns-field-row">
						<label for="ns_video_hq_link"><strong><?php esc_html_e( 'لینک ویدیو با کیفیت بالا (HQ):', 'eghtesadran' ); ?></strong></label>
						<input type="url" name="_news_video_hq_link" id="ns_video_hq_link" value="<?php echo esc_url( $video_hq_link ); ?>" class="widefat ltr-input" placeholder="https://...">
					</div>
					<div class="ns-field-row">
						<label for="ns_video_lq_link"><strong><?php esc_html_e( 'لینک ویدیو با کیفیت پایین (LQ):', 'eghtesadran' ); ?></strong></label>
						<input type="url" name="_news_video_lq_link" id="ns_video_lq_link" value="<?php echo esc_url( $video_lq_link ); ?>" class="widefat ltr-input" placeholder="https://...">
					</div>
				</div>

				<div class="ns-sub-conditional" data-show-sub-if="embed">
					<div class="ns-field-row">
						<label for="ns_video_embed_code"><strong><?php esc_html_e( 'کد امبد ویدیو (iframe):', 'eghtesadran' ); ?></strong></label>
						<textarea name="_news_video_embed_code" id="ns_video_embed_code" class="widefat ltr-input" rows="4"><?php echo esc_textarea( $video_embed_code ); ?></textarea>
						<p class="description"><?php esc_html_e( 'کدهای iframe استاندارد مجاز هستند.', 'eghtesadran' ); ?></p>
					</div>
				</div>
			</div>

			<!-- Audio Fields -->
			<div class="ns-conditional-section" data-show-if="audio">
				<div class="ns-field-row">
					<label for="ns_audio_duration"><strong><?php esc_html_e( 'مدت زمان صوت (مثلاً ۱۲:۳۰):', 'eghtesadran' ); ?></strong></label>
					<input type="text" name="_news_audio_duration" id="ns_audio_duration" value="<?php echo esc_attr( $audio_duration ); ?>" class="widefat ltr-input" placeholder="00:00">
				</div>
				<div class="ns-field-row">
					<label><strong><?php esc_html_e( 'فایل صوتی:', 'eghtesadran' ); ?></strong></label>
					<div class="flex-row">
						<input type="hidden" name="_news_audio_file_id" id="ns_audio_file_id" value="<?php echo esc_attr( $audio_file_id ); ?>">
						<input type="text" id="ns_audio_file_url" value="<?php echo esc_url( $audio_file_url ); ?>" class="widefat ltr-input" readonly placeholder="<?php esc_attr_e( 'فایلی انتخاب نشده است', 'eghtesadran' ); ?>">
						<button type="button" class="button" id="ns_upload_audio_btn"><?php esc_html_e( 'انتخاب / آپلود فایل صوتی', 'eghtesadran' ); ?></button>
						<button type="button" class="button hidden" id="ns_remove_audio_btn"><?php esc_html_e( 'حذف', 'eghtesadran' ); ?></button>
					</div>
				</div>
			</div>

			<!-- Photo Report Fields -->
			<div class="ns-conditional-section" data-show-if="photo_report">
				<div class="ns-field-row">
					<label for="ns_photographer_name"><strong><?php esc_html_e( 'نام عکاس:', 'eghtesadran' ); ?></strong></label>
					<input type="text" name="_news_photographer_name" id="ns_photographer_name" value="<?php echo esc_attr( $photographer_name ); ?>" class="widefat" maxlength="120">
				</div>
				
				<div class="ns-field-row">
					<label><strong><?php esc_html_e( 'تصاویر گالری:', 'eghtesadran' ); ?></strong></label>
					<div class="ns-gallery-wrapper">
						<input type="hidden" name="_news_gallery_images" id="ns_gallery_images" value="<?php echo esc_attr( $gallery_images ); ?>">
						<div id="ns_gallery_preview" class="ns-gallery-preview" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 10px;">
							<?php 
							if ( ! empty( $gallery_images ) ) {
								$ids = explode( ',', $gallery_images );
								foreach ( $ids as $id ) {
									$img = wp_get_attachment_image_src( $id, 'thumbnail' );
									if ( $img ) {
										echo '<div class="ns-gallery-item" data-id="' . esc_attr( $id ) . '" style="position: relative; width: 80px; height: 80px;">
												<img src="' . esc_url( $img[0] ) . '" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
												<span class="remove-image" style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; text-align: center; line-height: 16px; cursor: pointer; font-size: 12px;">×</span>
											  </div>';
									}
								}
							}
							?>
						</div>
						<button type="button" class="button" id="ns_add_gallery_btn"><?php esc_html_e( 'افزودن / ویرایش تصاویر', 'eghtesadran' ); ?></button>
					</div>
				</div>
			</div>

			<hr>

			<!-- General Source Fields -->
			<div class="ns-general-section">
				<div class="ns-field-row">
					<label for="ns_primary_category"><strong><?php esc_html_e( 'دسته‌بندی اصلی:', 'eghtesadran' ); ?></strong></label>
					<select name="_news_primary_category" id="ns_primary_category" class="widefat">
						<option value=""><?php esc_html_e( '— انتخاب دسته بندی اصلی (اختیاری) —', 'eghtesadran' ); ?></option>
						<?php
						$categories = get_categories( array( 'hide_empty' => false ) );
						foreach ( $categories as $cat ) :
							?>
							<option value="<?php echo esc_attr( $cat->term_id ); ?>" <?php selected( $primary_category, $cat->term_id ); ?>><?php echo esc_html( $cat->name ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="ns-field-row">
					<label for="ns_source_name"><strong><?php esc_html_e( 'نام منبع:', 'eghtesadran' ); ?></strong></label>
					<input type="text" name="_news_source_name" id="ns_source_name" value="<?php echo esc_attr( $source_name ); ?>" class="widefat" maxlength="200">
				</div>
				<div class="ns-field-row">
					<label for="ns_source_link"><strong><?php esc_html_e( 'لینک منبع:', 'eghtesadran' ); ?></strong></label>
					<input type="url" name="_news_source_link" id="ns_source_link" value="<?php echo esc_url( $source_link ); ?>" class="widefat ltr-input" placeholder="https://...">
				</div>
			</div>

		</div>
		<?php
	}

	/**
	 * Save meta box content.
	 *
	 * @param int $post_id Post ID.
	 */
	public function save( $post_id ) {
		// Check if our nonce is set.
		if ( ! isset( $_POST['eghtesadran_news_meta_box_nonce'] ) ) {
			return $post_id;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['eghtesadran_news_meta_box_nonce'], 'eghtesadran_news_save_meta_box_data' ) ) {
			return $post_id;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		/* Save the data */
		if ( isset( $_POST['eghtesadran_lead'] ) ) {
			update_post_meta( $post_id, '_eghtesadran_lead', sanitize_textarea_field( $_POST['eghtesadran_lead'] ) );
		}

		if ( isset( $_POST['eghtesadran_badge'] ) ) {
			update_post_meta( $post_id, '_eghtesadran_badge', sanitize_key( $_POST['eghtesadran_badge'] ) );
		}

		if ( isset( $_POST['_news_content_type'] ) ) {
			update_post_meta( $post_id, '_news_content_type', sanitize_key( $_POST['_news_content_type'] ) );
		}

		if ( isset( $_POST['_news_rotiter'] ) ) {
			update_post_meta( $post_id, '_news_rotiter', sanitize_text_field( $_POST['_news_rotiter'] ) );
		}

		// Note Fields
		if ( isset( $_POST['_news_author_name'] ) ) {
			update_post_meta( $post_id, '_news_author_name', sanitize_text_field( $_POST['_news_author_name'] ) );
		}
		if ( isset( $_POST['_news_author_position'] ) ) {
			update_post_meta( $post_id, '_news_author_position', sanitize_text_field( $_POST['_news_author_position'] ) );
		}

		// Interview Fields
		if ( isset( $_POST['_news_interviewee_name'] ) ) {
			update_post_meta( $post_id, '_news_interviewee_name', sanitize_text_field( $_POST['_news_interviewee_name'] ) );
		}
		if ( isset( $_POST['_news_interviewee_position'] ) ) {
			update_post_meta( $post_id, '_news_interviewee_position', sanitize_text_field( $_POST['_news_interviewee_position'] ) );
		}

		// Video Fields
		if ( isset( $_POST['_news_video_duration'] ) ) {
			update_post_meta( $post_id, '_news_video_duration', sanitize_text_field( $_POST['_news_video_duration'] ) );
		}
		if ( isset( $_POST['_news_video_source_type'] ) ) {
			update_post_meta( $post_id, '_news_video_source_type', sanitize_key( $_POST['_news_video_source_type'] ) );
		}
		if ( isset( $_POST['_news_video_hq_link'] ) ) {
			update_post_meta( $post_id, '_news_video_hq_link', esc_url_raw( $_POST['_news_video_hq_link'] ) );
		}
		if ( isset( $_POST['_news_video_lq_link'] ) ) {
			update_post_meta( $post_id, '_news_video_lq_link', esc_url_raw( $_POST['_news_video_lq_link'] ) );
		}
		if ( isset( $_POST['_news_video_embed_code'] ) ) {
			$allowed_html = array(
				'iframe' => array(
					'src'             => array(),
					'width'           => array(),
					'height'          => array(),
					'frameborder'     => array(),
					'allowfullscreen' => array(),
					'title'           => array(),
					'style'           => array(),
					'allow'           => array(),
				),
				'div' => array(
					'class' => array(),
					'style' => array(),
					'id'    => array(),
				),
			);
			update_post_meta( $post_id, '_news_video_embed_code', wp_kses( $_POST['_news_video_embed_code'], $allowed_html ) );
		}

		// Audio Fields
		if ( isset( $_POST['_news_audio_duration'] ) ) {
			update_post_meta( $post_id, '_news_audio_duration', sanitize_text_field( $_POST['_news_audio_duration'] ) );
		}
		if ( isset( $_POST['_news_audio_file_id'] ) ) {
			update_post_meta( $post_id, '_news_audio_file_id', absint( $_POST['_news_audio_file_id'] ) );
		}

		// Photo Report Fields
		if ( isset( $_POST['_news_photographer_name'] ) ) {
			update_post_meta( $post_id, '_news_photographer_name', sanitize_text_field( $_POST['_news_photographer_name'] ) );
		}
		if ( isset( $_POST['_news_gallery_images'] ) ) {
			update_post_meta( $post_id, '_news_gallery_images', sanitize_text_field( $_POST['_news_gallery_images'] ) );
		}

		// General Source Fields
		if ( isset( $_POST['_news_primary_category'] ) ) {
			update_post_meta( $post_id, '_news_primary_category', absint( $_POST['_news_primary_category'] ) );
		}
		if ( isset( $_POST['_news_source_name'] ) ) {
			update_post_meta( $post_id, '_news_source_name', sanitize_text_field( $_POST['_news_source_name'] ) );
		}
		if ( isset( $_POST['_news_source_link'] ) ) {
			update_post_meta( $post_id, '_news_source_link', esc_url_raw( $_POST['_news_source_link'] ) );
		}
	}
}

// Initialize the class
new Eghtesadran_News_Meta_Box();
