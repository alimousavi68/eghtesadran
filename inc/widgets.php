<?php
/**
 * Custom widgets for the Eghtesadran theme.
 *
 * @package Eghtesadran
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the default fallback image URL for widget cards.
 *
 * @return string
 */
function eghtesadran_get_widget_fallback_image() {
	return eghtesadran_asset_uri( 'assets/images/bours-18.jpg' );
}

/**
 * Returns the default market dashboard items.
 *
 * @return string
 */
function eghtesadran_get_default_market_items() {
	return implode(
		"\n",
		array(
			'فلزات اساسی|#|layers|#2563eb',
			'پتروشیمی|#|droplet|#0f766e',
			'ارز و طلا|#|coins|#ca8a04',
			'بورس و اوراق|#|line-chart|#dc2626',
			'خودرو|#|car|#7c3aed',
			'انرژی|#|zap|#ea580c',
		)
	);
}

/**
 * Parses dashboard items from textarea input.
 *
 * Format: label|url|icon|color
 *
 * @param string $raw_items Raw textarea content.
 * @return array<int, array<string, string>>
 */
function eghtesadran_parse_market_items( $raw_items ) {
	$items = array();
	$lines = preg_split( '/\r\n|\r|\n/', (string) $raw_items );

	if ( ! is_array( $lines ) ) {
		return $items;
	}

	foreach ( $lines as $line ) {
		$line = trim( $line );

		if ( '' === $line ) {
			continue;
		}

		$parts = array_map( 'trim', explode( '|', $line ) );
		$label = isset( $parts[0] ) ? sanitize_text_field( $parts[0] ) : '';
		$url   = isset( $parts[1] ) ? esc_url_raw( $parts[1] ) : '';
		$icon  = isset( $parts[2] ) ? sanitize_key( $parts[2] ) : 'circle';
		$color = isset( $parts[3] ) ? sanitize_hex_color( $parts[3] ) : '';

		if ( '' === $label ) {
			continue;
		}

		$items[] = array(
			'label' => $label,
			'url'   => $url ? $url : '#',
			'icon'  => $icon ? $icon : 'circle',
			'color' => $color ? $color : '#dc2626',
		);
	}

	return $items;
}

/**
 * Renders a widget card header.
 *
 * @param string $title      Header title.
 * @param string $icon       Lucide icon name.
 * @param string $more_url   Optional link URL.
 * @param string $more_label Optional link label.
 * @return void
 */
function eghtesadran_render_widget_header( $title, $icon = '', $more_url = '', $more_label = '' ) {
	?>
	<div class="flex items-center justify-between mb-4 border-b border-slate-100 dark:border-slate-700 pb-3">
		<h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
			<?php if ( $icon ) : ?>
				<i data-lucide="<?php echo esc_attr( $icon ); ?>" class="w-5 h-5 text-primary" aria-hidden="true"></i>
			<?php endif; ?>
			<?php echo esc_html( $title ); ?>
		</h3>
		<?php if ( $more_url && $more_label ) : ?>
			<a href="<?php echo esc_url( $more_url ); ?>" class="group flex text-[10px] font-bold text-slate-500 dark:text-slate-400 hover:text-primary transition-colors gap-0.5 items-center">
				<?php echo esc_html( $more_label ); ?>
				<i data-lucide="chevron-left" class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-0.5" aria-hidden="true"></i>
			</a>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Base widget helpers.
 */
abstract class Eghtesadran_Widget_Base extends WP_Widget {
	/**
	 * Returns a normalized checkbox value.
	 *
	 * @param mixed $value Checkbox value.
	 * @return string
	 */
	protected function sanitize_checkbox( $value ) {
		return ! empty( $value ) ? '1' : '';
	}

	/**
	 * Returns the widget fallback image URL.
	 *
	 * @param string $url Optional custom URL.
	 * @return string
	 */
	protected function normalize_image_url( $url ) {
		$url = esc_url_raw( trim( (string) $url ) );

		return $url ? $url : eghtesadran_get_widget_fallback_image();
	}
}

class Eghtesadran_Widget_Market_Dashboard extends Eghtesadran_Widget_Base {
	/**
	 * Sets up the widget.
	 */
	public function __construct() {
		parent::__construct(
			'eghtesadran_market_dashboard',
			__( 'اقتصادران: پیشخوان بازار', 'eghtesadran' ),
			array(
				'description' => __( 'نمایش داشبورد لینک‌های بازار با آیکن و چیدمان قابل‌تنظیم.', 'eghtesadran' ),
			)
		);
	}

	/**
	 * Outputs the widget form.
	 *
	 * @param array $instance Current settings.
	 * @return void
	 */
	public function form( $instance ) {
		$defaults = array(
			'title'   => 'پیشخوان بازار',
			'columns' => 3,
			'items'   => array(),
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$items = $instance['items'];
		// Backward compatibility for string format
		if ( is_string( $items ) ) {
			$items = eghtesadran_parse_market_items( $items );
		}
		if ( empty( $items ) || ! is_array( $items ) ) {
			$items = array();
		}

		$lucide_icons = array(
			'trending-up'  => 'Trending Up (پربازدید)',
			'clock'        => 'Clock (آخرین اخبار)',
			'flame'        => 'Flame (داغ)',
			'zap'          => 'Zap (ویژه)',
			'newspaper'    => 'Newspaper (اخبار)',
			'star'         => 'Star (ستاره)',
			'heart'        => 'Heart (محبوب)',
			'eye'          => 'Eye (مشاهده)',
			'bar-chart-2'  => 'Chart (آمار)',
			'award'        => 'Award (برگزیده)',
			'bell'         => 'Bell (اطلاعیه)',
			'briefcase'    => 'Briefcase (اقتصادی)',
			'coins'        => 'Coins (ارز و طلا)',
			'shopping-bag' => 'Shopping Bag (بازار)',
			'home'         => 'Home (مسکن)',
			'car'          => 'Car (خودرو)',
			'layers'       => 'Layers (لایه‌ها)',
			'droplet'      => 'Droplet (قطره)',
			'line-chart'   => 'Line Chart (نمودار)',
			'circle'       => 'Circle (دایره)',
		);

		$categories = get_categories( array( 'hide_empty' => false ) );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'عنوان', 'eghtesadran' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>"><?php esc_html_e( 'تعداد ستون', 'eghtesadran' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>">
				<option value="2" <?php selected( (int) $instance['columns'], 2 ); ?>>2</option>
				<option value="3" <?php selected( (int) $instance['columns'], 3 ); ?>>3</option>
			</select>
		</p>
		
		<div class="eghtesadran-widget-items-container" style="margin-top: 20px;">
			<label><strong><?php esc_html_e( 'آیتم‌ها', 'eghtesadran' ); ?></strong></label>
			<div class="eghtesadran-items-wrapper" style="margin-top: 10px;">
				<?php foreach ( $items as $index => $item ) : 
					$link_type = isset( $item['link_type'] ) ? $item['link_type'] : 'custom';
					$cat_id    = isset( $item['category'] ) ? $item['category'] : '';
					$url       = isset( $item['url'] ) ? $item['url'] : '';
					$label     = isset( $item['label'] ) ? $item['label'] : '';
					$icon      = isset( $item['icon'] ) ? $item['icon'] : 'circle';
				?>
					<div class="eghtesadran-item" style="border: 1px solid #e5e5e5; padding: 10px; margin-bottom: 10px; background: #fafafa;">
						<p>
							<label><?php esc_html_e( 'عنوان:', 'eghtesadran' ); ?></label>
							<input type="text" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[<?php echo $index; ?>][label]" value="<?php echo esc_attr( $label ); ?>">
						</p>
						<p>
							<label><?php esc_html_e( 'آیکن:', 'eghtesadran' ); ?></label>
							<select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[<?php echo $index; ?>][icon]">
								<?php foreach ( $lucide_icons as $key => $icon_label ) : ?>
									<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $icon, $key ); ?>><?php echo esc_html( $icon_label ); ?></option>
								<?php endforeach; ?>
							</select>
						</p>
						<p>
							<label><?php esc_html_e( 'نوع لینک:', 'eghtesadran' ); ?></label>
							<select class="widefat eghtesadran-link-type" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[<?php echo $index; ?>][link_type]">
								<option value="custom" <?php selected( $link_type, 'custom' ); ?>><?php esc_html_e( 'لینک دلخواه', 'eghtesadran' ); ?></option>
								<option value="category" <?php selected( $link_type, 'category' ); ?>><?php esc_html_e( 'آرشیو دسته‌بندی', 'eghtesadran' ); ?></option>
							</select>
						</p>
						<p class="eghtesadran-url-field" style="<?php echo 'category' === $link_type ? 'display:none;' : ''; ?>">
							<label><?php esc_html_e( 'لینک:', 'eghtesadran' ); ?></label>
							<input type="text" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[<?php echo $index; ?>][url]" value="<?php echo esc_attr( $url ); ?>" dir="ltr">
						</p>
						<p class="eghtesadran-cat-field" style="<?php echo 'custom' === $link_type ? 'display:none;' : ''; ?>">
							<label><?php esc_html_e( 'دسته‌بندی:', 'eghtesadran' ); ?></label>
							<select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[<?php echo $index; ?>][category]">
								<option value=""><?php esc_html_e( '— انتخاب کنید —', 'eghtesadran' ); ?></option>
								<?php foreach ( $categories as $cat ) : ?>
									<option value="<?php echo esc_attr( $cat->term_id ); ?>" <?php selected( $cat_id, $cat->term_id ); ?>><?php echo esc_html( $cat->name ); ?></option>
								<?php endforeach; ?>
							</select>
						</p>
						<p style="text-align: left; margin-bottom: 0;">
							<button type="button" class="button eghtesadran-remove-item" style="color: #dc3232; border-color: #dc3232;"><?php esc_html_e( 'حذف این آیتم', 'eghtesadran' ); ?></button>
						</p>
					</div>
				<?php endforeach; ?>
			</div>
			<p>
				<button type="button" class="button button-primary eghtesadran-add-item"><?php esc_html_e( 'افزودن آیتم جدید', 'eghtesadran' ); ?></button>
			</p>
			
			<script type="text/template" class="eghtesadran-item-template">
				<div class="eghtesadran-item" style="border: 1px solid #e5e5e5; padding: 10px; margin-bottom: 10px; background: #fafafa;">
					<p>
						<label><?php esc_html_e( 'عنوان:', 'eghtesadran' ); ?></label>
						<input type="text" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[__i__][label]" value="">
					</p>
					<p>
						<label><?php esc_html_e( 'آیکن:', 'eghtesadran' ); ?></label>
						<select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[__i__][icon]">
							<?php foreach ( $lucide_icons as $key => $icon_label ) : ?>
								<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $icon_label ); ?></option>
							<?php endforeach; ?>
						</select>
					</p>
					<p>
						<label><?php esc_html_e( 'نوع لینک:', 'eghtesadran' ); ?></label>
						<select class="widefat eghtesadran-link-type" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[__i__][link_type]">
							<option value="custom"><?php esc_html_e( 'لینک دلخواه', 'eghtesadran' ); ?></option>
							<option value="category"><?php esc_html_e( 'آرشیو دسته‌بندی', 'eghtesadran' ); ?></option>
						</select>
					</p>
					<p class="eghtesadran-url-field">
						<label><?php esc_html_e( 'لینک:', 'eghtesadran' ); ?></label>
						<input type="text" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[__i__][url]" value="" dir="ltr">
					</p>
					<p class="eghtesadran-cat-field" style="display:none;">
						<label><?php esc_html_e( 'دسته‌بندی:', 'eghtesadran' ); ?></label>
						<select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[__i__][category]">
							<option value=""><?php esc_html_e( '— انتخاب کنید —', 'eghtesadran' ); ?></option>
							<?php foreach ( $categories as $cat ) : ?>
								<option value="<?php echo esc_attr( $cat->term_id ); ?>"><?php echo esc_html( $cat->name ); ?></option>
							<?php endforeach; ?>
						</select>
					</p>
					<p style="text-align: left; margin-bottom: 0;">
						<button type="button" class="button eghtesadran-remove-item" style="color: #dc3232; border-color: #dc3232;"><?php esc_html_e( 'حذف این آیتم', 'eghtesadran' ); ?></button>
					</p>
				</div>
			</script>
			<script>
				if ( typeof window.eghtesadranMarketWidgetInit === 'undefined' ) {
					window.eghtesadranMarketWidgetInit = true;
					jQuery(document).on('click', '.eghtesadran-add-item', function(e) {
						e.preventDefault();
						var $container = jQuery(this).closest('.eghtesadran-widget-items-container');
						var $wrapper = $container.find('.eghtesadran-items-wrapper');
						var template = $container.find('.eghtesadran-item-template').html();
						var index = $wrapper.children().length;
						// Find max index to avoid conflicts
						$wrapper.children().each(function() {
							var name = jQuery(this).find('input[type="text"]').first().attr('name');
							if (name) {
								var match = name.match(/\[(\d+)\]/);
								if (match && parseInt(match[1]) >= index) {
									index = parseInt(match[1]) + 1;
								}
							}
						});
						template = template.replace(/__i__/g, index);
						$wrapper.append(template);
						
						// Trigger change to set default visibility
						$wrapper.children().last().find('.eghtesadran-link-type').trigger('change');
					});

					jQuery(document).on('click', '.eghtesadran-remove-item', function(e) {
						e.preventDefault();
						jQuery(this).closest('.eghtesadran-item').remove();
					});

					jQuery(document).on('change', '.eghtesadran-link-type', function() {
						var val = jQuery(this).val();
						var $item = jQuery(this).closest('.eghtesadran-item');
						if (val === 'category') {
							$item.find('.eghtesadran-url-field').hide();
							$item.find('.eghtesadran-cat-field').show();
						} else {
							$item.find('.eghtesadran-url-field').show();
							$item.find('.eghtesadran-cat-field').hide();
						}
					});
				}
			</script>
		</div>
		<?php
	}

	/**
	 * Saves widget settings.
	 *
	 * @param array $new_instance New settings.
	 * @param array $old_instance Old settings.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$items = array();
		if ( isset( $new_instance['items'] ) && is_array( $new_instance['items'] ) ) {
			foreach ( $new_instance['items'] as $item ) {
				$items[] = array(
					'label'     => sanitize_text_field( $item['label'] ?? '' ),
					'icon'      => sanitize_key( $item['icon'] ?? 'circle' ),
					'link_type' => in_array( $item['link_type'] ?? 'custom', array( 'custom', 'category' ), true ) ? $item['link_type'] : 'custom',
					'url'       => esc_url_raw( $item['url'] ?? '' ),
					'category'  => absint( $item['category'] ?? 0 ),
				);
			}
		}

		return array(
			'title'   => sanitize_text_field( $new_instance['title'] ?? '' ),
			'columns' => in_array( (int) ( $new_instance['columns'] ?? 3 ), array( 2, 3 ), true ) ? (int) $new_instance['columns'] : 3,
			'items'   => $items,
		);
	}

	/**
	 * Outputs the widget content.
	 *
	 * @param array $args Sidebar args.
	 * @param array $instance Widget settings.
	 * @return void
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'   => 'پیشخوان بازار',
				'columns' => 3,
				'items'   => array(),
			)
		);

		$items = $instance['items'];
		if ( is_string( $items ) ) {
			$items = eghtesadran_parse_market_items( $items );
		}

		if ( empty( $items ) || ! is_array( $items ) ) {
			return;
		}

		$grid_class = 2 === (int) $instance['columns'] ? 'grid-cols-2' : 'grid-cols-3';

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
		<div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm">
			<?php eghtesadran_render_widget_header( $instance['title'], 'bar-chart-2' ); ?>

			<div class="grid <?php echo esc_attr( $grid_class ); ?> gap-3">
				<?php foreach ( $items as $item ) : 
					$link = '#';
					if ( isset( $item['link_type'] ) && 'category' === $item['link_type'] && ! empty( $item['category'] ) ) {
						$link = get_category_link( $item['category'] );
					} elseif ( ! empty( $item['url'] ) ) {
						$link = $item['url'];
					}
				?>
					<a href="<?php echo esc_url( $link ); ?>" class="group flex flex-col items-center justify-center pt-5 pb-4 px-1 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg transition-all duration-300 hover:bg-white dark:hover:bg-slate-800 hover:border-primary hover:-translate-y-[2px] outline-none">
						<i data-lucide="<?php echo esc_attr( $item['icon'] ?? 'circle' ); ?>" class="w-[26px] h-[26px] text-slate-600 dark:text-slate-300 transition-colors duration-300 group-hover:text-primary" stroke-width="1.5" aria-hidden="true"></i>
						<span class="text-[11px] sm:text-[11.5px] font-bold mt-5 text-center text-slate-700 dark:text-slate-200 transition-colors duration-300 leading-relaxed group-hover:text-primary">
							<?php echo esc_html( $item['label'] ?? '' ); ?>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Promotional banner widget.
 */
class Eghtesadran_Widget_Promo_Banner extends Eghtesadran_Widget_Base {
	/**
	 * Sets up the widget.
	 */
	public function __construct() {
		parent::__construct(
			'eghtesadran_promo_banner',
			__( 'اقتصادران: بنر تبلیغاتی', 'eghtesadran' ),
			array(
				'description' => __( 'نمایش بنر تبلیغاتی یا پروموشن با تصویر، متن، لینک و نسبت تصویر دلخواه.', 'eghtesadran' ),
			)
		);
	}

	/**
	 * Outputs the widget form.
	 *
	 * @param array $instance Current settings.
	 * @return void
	 */
	public function form( $instance ) {
		$defaults = array(
			'widget_type'      => 'image',
			'placeholder_size' => '100x300',
			'badge_text'       => 'تبلیغات',
			'image_url'        => eghtesadran_asset_uri( 'assets/images/bours-26.jpg' ),
			'alt_text'         => 'تبلیغات',
			'heading'          => 'صندوق‌های نوین سرمایه‌گذاری املاک',
			'subheading'       => 'مدیریت ریسک هوشمند مسکن',
			'link_url'         => '#',
			'aspect_ratio'     => '16/11',
			'new_tab'          => '',
			'placeholder_text' => 'جایگاه تبلیغات ۱۰۰×۳۰۰',
			'loading_strategy' => 'lazy',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'widget_type' ) ); ?>"><?php esc_html_e( 'نوع ویجت', 'eghtesadran' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'widget_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_type' ) ); ?>">
				<option value="image" <?php selected( $instance['widget_type'], 'image' ); ?>><?php esc_html_e( 'بنر تصویری', 'eghtesadran' ); ?></option>
				<option value="placeholder" <?php selected( $instance['widget_type'], 'placeholder' ); ?>><?php esc_html_e( 'جایگاه خالی (پلی‌سхолدر)', 'eghtesadran' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'placeholder_size' ) ); ?>"><strong><?php esc_html_e( 'ابعاد جایگاه خالی', 'eghtesadran' ); ?></strong></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'placeholder_size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'placeholder_size' ) ); ?>">
				<option value="100x300" <?php selected( $instance['placeholder_size'], '100x300' ); ?>><?php esc_html_e( 'عمودی (۱۰۰×۳۰۰)', 'eghtesadran' ); ?></option>
				<option value="600x60" <?php selected( $instance['placeholder_size'], '600x60' ); ?>><?php esc_html_e( 'افقی هدر (۶۰۰×۶۰)', 'eghtesadran' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'placeholder_text' ) ); ?>"><strong><?php esc_html_e( 'متن جایگاه خالی', 'eghtesadran' ); ?></strong></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'placeholder_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'placeholder_text' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['placeholder_text'] ); ?>">
		</p>
		<hr style="margin: 15px 0;">
		<p><strong><?php esc_html_e( 'تنظیمات بنر تصویری:', 'eghtesadran' ); ?></strong></p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'badge_text' ) ); ?>"><?php esc_html_e( 'برچسب گوشه بنر', 'eghtesadran' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'badge_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'badge_text' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['badge_text'] ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'image_url' ) ); ?>"><?php esc_html_e( 'آدرس تصویر', 'eghtesadran' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'image_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_url' ) ); ?>" type="url" value="<?php echo esc_attr( $instance['image_url'] ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'alt_text' ) ); ?>"><?php esc_html_e( 'متن جایگزین تصویر', 'eghtesadran' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'alt_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'alt_text' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['alt_text'] ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'heading' ) ); ?>"><?php esc_html_e( 'تیتر', 'eghtesadran' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'heading' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'heading' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['heading'] ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'subheading' ) ); ?>"><?php esc_html_e( 'زیرتیتر', 'eghtesadran' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'subheading' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'subheading' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['subheading'] ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'link_url' ) ); ?>"><?php esc_html_e( 'لینک مقصد', 'eghtesadran' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link_url' ) ); ?>" type="url" value="<?php echo esc_attr( $instance['link_url'] ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'aspect_ratio' ) ); ?>"><?php esc_html_e( 'نسبت تصویر', 'eghtesadran' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'aspect_ratio' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'aspect_ratio' ) ); ?>">
				<option value="16/11" <?php selected( $instance['aspect_ratio'], '16/11' ); ?>>16/11</option>
				<option value="16/9" <?php selected( $instance['aspect_ratio'], '16/9' ); ?>>16/9</option>
				<option value="1/1" <?php selected( $instance['aspect_ratio'], '1/1' ); ?>>1/1</option>
				<option value="600/60" <?php selected( $instance['aspect_ratio'], '600/60' ); ?>>600/60 (ویژه هدر)</option>
				<option value="auto" <?php selected( $instance['aspect_ratio'], 'auto' ); ?>><?php esc_html_e( 'خودکار', 'eghtesadran' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'loading_strategy' ) ); ?>"><?php esc_html_e( 'استراتژی لود تصویر', 'eghtesadran' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'loading_strategy' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'loading_strategy' ) ); ?>">
				<option value="lazy" <?php selected( $instance['loading_strategy'], 'lazy' ); ?>><?php esc_html_e( 'لود تنبل (مناسب فوتر/سایدبار)', 'eghtesadran' ); ?></option>
				<option value="eager" <?php selected( $instance['loading_strategy'], 'eager' ); ?>><?php esc_html_e( 'لود فوری (eager + fetchpriority="high" ویژه هدر)', 'eghtesadran' ); ?></option>
			</select>
		</p>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'new_tab' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['new_tab'], '1' ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>"><?php esc_html_e( 'باز شدن لینک در تب جدید', 'eghtesadran' ); ?></label>
		</p>
		<?php
	}

	/**
	 * Saves widget settings.
	 *
	 * @param array $new_instance New settings.
	 * @param array $old_instance Old settings.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		return array(
			'widget_type'      => in_array( $new_instance['widget_type'] ?? '', array( 'image', 'placeholder' ), true ) ? $new_instance['widget_type'] : 'image',
			'placeholder_size' => in_array( $new_instance['placeholder_size'] ?? '', array( '100x300', '600x60' ), true ) ? $new_instance['placeholder_size'] : '100x300',
			'badge_text'       => sanitize_text_field( $new_instance['badge_text'] ?? '' ),
			'image_url'        => $this->normalize_image_url( $new_instance['image_url'] ?? '' ),
			'alt_text'         => sanitize_text_field( $new_instance['alt_text'] ?? '' ),
			'heading'          => sanitize_text_field( $new_instance['heading'] ?? '' ),
			'subheading'       => sanitize_text_field( $new_instance['subheading'] ?? '' ),
			'link_url'         => esc_url_raw( $new_instance['link_url'] ?? '' ),
			'aspect_ratio'     => in_array( $new_instance['aspect_ratio'] ?? '', array( '16/11', '16/9', '1/1', '600/60', 'auto' ), true ) ? $new_instance['aspect_ratio'] : '16/11',
			'new_tab'          => $this->sanitize_checkbox( $new_instance['new_tab'] ?? '' ),
			'placeholder_text' => sanitize_text_field( $new_instance['placeholder_text'] ?? '' ),
			'loading_strategy' => in_array( $new_instance['loading_strategy'] ?? '', array( 'lazy', 'eager' ), true ) ? $new_instance['loading_strategy'] : 'lazy',
		);
	}

	/**
	 * Outputs the widget content.
	 *
	 * @param array $args Sidebar args.
	 * @param array $instance Widget settings.
	 * @return void
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'widget_type'      => 'image',
				'placeholder_size' => '100x300',
				'badge_text'       => 'تبلیغات',
				'image_url'        => eghtesadran_asset_uri( 'assets/images/bours-26.jpg' ),
				'alt_text'         => 'تبلیغات',
				'heading'          => 'صندوق‌های نوین سرمایه‌گذاری املاک',
				'subheading'       => 'مدیریت ریسک هوشمند مسکن',
				'link_url'         => '#',
				'aspect_ratio'     => '16/11',
				'new_tab'          => '',
				'placeholder_text' => 'جایگاه تبلیغات ۱۰۰×۳۰۰',
				'loading_strategy' => 'lazy',
			)
		);

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( 'placeholder' === $instance['widget_type'] ) {
			if ( '600x60' === $instance['placeholder_size'] ) {
				?>
				<div class="hidden md:flex w-full max-w-[600px] h-[60px] rounded-lg border border-dashed border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/20 items-center justify-center group cursor-pointer mx-auto">
					<span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 tracking-wide">
						<?php echo esc_html( $instance['placeholder_text'] ); ?>
					</span>
				</div>
				<?php
			} else {
				?>
				<div class="bg-white w-full max-w-[100px] h-[300px] border border-slate-200 dark:border-slate-700 rounded-xl flex items-center justify-center p-2 text-center group cursor-pointer mx-auto">
					<span class="text-xs text-slate-400 dark:text-slate-500 font-semibold select-none tracking-wider inline-block transform -rotate-90 whitespace-nowrap">
						<?php echo esc_html( $instance['placeholder_text'] ); ?>
					</span>
				</div>
				<?php
			}
		} else {
			$tag_name     = $instance['link_url'] ? 'a' : 'div';
			$target_attr  = ( $instance['link_url'] && '1' === $instance['new_tab'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
			$href_attr    = ( 'a' === $tag_name ) ? ' href="' . esc_url( $instance['link_url'] ) . '"' : '';
			$aspect_class = 'auto' === $instance['aspect_ratio'] ? 'min-h-[220px]' : 'aspect-[' . $instance['aspect_ratio'] . ']';
			if ( '600/60' === $instance['aspect_ratio'] ) {
				$aspect_class = 'aspect-[10/1] max-w-[600px] mx-auto hidden md:flex'; // 600/60 = 10/1
			}

			$loading_attr = 'lazy' === $instance['loading_strategy'] ? 'loading="lazy"' : 'loading="eager" fetchpriority="high"';

			?>
			<<?php echo esc_html( $tag_name ); ?><?php echo $href_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $target_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="relative bg-slate-200 rounded-2xl overflow-hidden <?php echo esc_attr( $aspect_class ); ?> flex items-center justify-center group shadow-sm border border-slate-100 dark:border-slate-700 w-full">
				<?php if ( '' !== $instance['badge_text'] ) : ?>
					<div class="absolute top-3 left-3 bg-black/55 backdrop-blur text-white text-[9px] px-2.5 py-1 rounded-md z-10 font-bold">
						<?php echo esc_html( $instance['badge_text'] ); ?>
					</div>
				<?php endif; ?>
				<img src="<?php echo esc_url( $instance['image_url'] ); ?>" alt="<?php echo esc_attr( $instance['alt_text'] ); ?>" <?php echo $loading_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="w-full h-full object-cover opacity-85 group-hover:scale-105 transition-all duration-700" />
				<div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/40 to-transparent flex flex-col justify-end p-5">
					<?php if ( '' !== $instance['heading'] ) : ?>
						<span class="text-white font-extrabold text-base md:text-lg mb-1.5 leading-snug">
							<?php echo esc_html( $instance['heading'] ); ?>
						</span>
					<?php endif; ?>
					<?php if ( '' !== $instance['subheading'] ) : ?>
						<span class="text-red-300 text-xs font-bold flex items-center gap-1">
							<?php echo esc_html( $instance['subheading'] ); ?>
							<i data-lucide="arrow-left" class="w-3.5 h-3.5" aria-hidden="true"></i>
						</span>
					<?php endif; ?>
				</div>
			</<?php echo esc_html( $tag_name ); ?>>
			<?php
		}

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Universal posts widget.
 */
class Eghtesadran_Widget_Universal_Posts extends Eghtesadran_Widget_Base {
	/**
	 * Sets up the widget.
	 */
	public function __construct() {
		parent::__construct(
			'eghtesadran_universal_posts',
			__( 'اقتصادران: ویجت هوشمند خبر', 'eghtesadran' ),
			array(
				'description' => __( 'ویجت همه‌کاره برای نمایش اخبار با استایل‌های مختلف و تنظیمات پیشرفته.', 'eghtesadran' ),
			)
		);
	}

	/**
	 * Outputs the widget form.
	 *
	 * @param array $instance Current settings.
	 * @return void
	 */
	public function form( $instance ) {
		$defaults = array(
			'style'           => 'most-viewed',
			'title'           => 'آخرین اخبار',
			'icon'            => 'trending-up',
			'post_type'       => 'post',
			'query_type'      => 'latest',
			'category'        => 0,
			'orderby'         => 'date',
			'order'           => 'DESC',
			'posts_count'     => 5,
			'offset'          => 0,
			'exclude_current' => '1',
			'show_view_all'   => '1',
			'more_label'      => 'مشاهده همه',
			'more_url'        => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$lucide_icons = array(
			'trending-up'  => 'Trending Up (پربازدید)',
			'clock'        => 'Clock (آخرین اخبار)',
			'flame'        => 'Flame (داغ)',
			'zap'          => 'Zap (ویژه)',
			'newspaper'    => 'Newspaper (اخبار)',
			'star'         => 'Star (ستاره)',
			'heart'        => 'Heart (محبوب)',
			'eye'          => 'Eye (مشاهده)',
			'bar-chart-2'  => 'Chart (آمار)',
			'award'        => 'Award (برگزیده)',
			'bell'         => 'Bell (اطلاعیه)',
			'briefcase'    => 'Briefcase (اقتصادی)',
			'coins'        => 'Coins (ارز و طلا)',
			'shopping-bag' => 'Shopping Bag (بازار)',
			'home'         => 'Home (مسکن)',
			'car'          => 'Car (خودرو)',
		);

		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'استایل نمایش:', 'eghtesadran' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>">
				<option value="card" <?php selected( $instance['style'], 'card' ); ?>><?php esc_html_e( 'کارتی (دارای تصویر شاخص برای اولین پست)', 'eghtesadran' ); ?></option>
				<option value="list" <?php selected( $instance['style'], 'list' ); ?>><?php esc_html_e( 'لیستی (نمایش ساده به همراه زمان)', 'eghtesadran' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'عنوان:', 'eghtesadran' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"><?php esc_html_e( 'آیکن:', 'eghtesadran' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon' ) ); ?>">
				<?php foreach ( $lucide_icons as $key => $label ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $instance['icon'], $key ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_type' ) ); ?>"><?php esc_html_e( 'نوع پست:', 'eghtesadran' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_type' ) ); ?>">
				<?php foreach ( $post_types as $pt ) : ?>
					<option value="<?php echo esc_attr( $pt->name ); ?>" <?php selected( $instance['post_type'], $pt->name ); ?>><?php echo esc_html( $pt->label ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'query_type' ) ); ?>"><?php esc_html_e( 'نوع کوئری:', 'eghtesadran' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'query_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'query_type' ) ); ?>">
				<option value="latest" <?php selected( $instance['query_type'], 'latest' ); ?>><?php esc_html_e( 'آخرین مطالب', 'eghtesadran' ); ?></option>
				<option value="popular" <?php selected( $instance['query_type'], 'popular' ); ?>><?php esc_html_e( 'پربازدیدترین‌ها (بر اساس دیدگاه)', 'eghtesadran' ); ?></option>
				<option value="category" <?php selected( $instance['query_type'], 'category' ); ?>><?php esc_html_e( 'بر اساس دسته بندی خاص', 'eghtesadran' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'دسته بندی:', 'eghtesadran' ); ?></label>
			<?php
			wp_dropdown_categories(
				array(
					'show_option_all' => __( 'همه دسته‌ها', 'eghtesadran' ),
					'hide_empty'      => 0,
					'name'            => $this->get_field_name( 'category' ),
					'id'              => $this->get_field_id( 'category' ),
					'selected'        => (int) $instance['category'],
					'class'           => 'widefat',
				)
			);
			?>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'ترتیب نمایش:', 'eghtesadran' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
				<option value="DESC" <?php selected( $instance['order'], 'DESC' ); ?>><?php esc_html_e( 'نزولی (جدیدترین)', 'eghtesadran' ); ?></option>
				<option value="ASC" <?php selected( $instance['order'], 'ASC' ); ?>><?php esc_html_e( 'صعودی (قدیمی‌ترین)', 'eghtesadran' ); ?></option>
				<option value="rand" <?php selected( $instance['order'], 'rand' ); ?>><?php esc_html_e( 'تصادفی', 'eghtesadran' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'posts_count' ) ); ?>"><?php esc_html_e( 'تعداد:', 'eghtesadran' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'posts_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_count' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $instance['posts_count'] ); ?>" size="3">
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>"><?php esc_html_e( 'افست (پرش از چند مورد اول):', 'eghtesadran' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'offset' ) ); ?>" type="number" step="1" min="0" value="<?php echo esc_attr( $instance['offset'] ); ?>" size="3">
		</p>

		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'exclude_current' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'exclude_current' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['exclude_current'], '1' ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'exclude_current' ) ); ?>"><?php esc_html_e( 'در صفحه داخلی، پست جاری حذف شود؟', 'eghtesadran' ); ?></label>
		</p>

		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_view_all' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_view_all' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['show_view_all'], '1' ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_view_all' ) ); ?>"><?php esc_html_e( 'نمایش لینک مشاهده همه؟', 'eghtesadran' ); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'more_label' ) ); ?>"><?php esc_html_e( 'متن لینک مشاهده همه:', 'eghtesadran' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'more_label' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'more_label' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['more_label'] ); ?>">
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'more_url' ) ); ?>"><?php esc_html_e( 'آدرس لینک مشاهده همه (خالی = خودکار):', 'eghtesadran' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'more_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'more_url' ) ); ?>" type="url" value="<?php echo esc_attr( $instance['more_url'] ); ?>">
		</p>
		<?php
	}

	/**
	 * Saves widget settings.
	 *
	 * @param array $new_instance New settings.
	 * @param array $old_instance Old settings.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['style']           = ( ! empty( $new_instance['style'] ) ) ? sanitize_key( $new_instance['style'] ) : 'card';
		$instance['title']           = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['icon']            = ( ! empty( $new_instance['icon'] ) ) ? sanitize_key( $new_instance['icon'] ) : 'trending-up';
		$instance['post_type']       = ( ! empty( $new_instance['post_type'] ) ) ? sanitize_key( $new_instance['post_type'] ) : 'post';
		$instance['query_type']      = ( ! empty( $new_instance['query_type'] ) ) ? sanitize_key( $new_instance['query_type'] ) : 'latest';
		$instance['category']        = ( ! empty( $new_instance['category'] ) ) ? absint( $new_instance['category'] ) : 0;
		$instance['order']           = ( ! empty( $new_instance['order'] ) ) ? sanitize_text_field( $new_instance['order'] ) : 'DESC';
		$instance['posts_count']     = ( ! empty( $new_instance['posts_count'] ) ) ? absint( $new_instance['posts_count'] ) : 5;
		$instance['offset']          = ( ! empty( $new_instance['offset'] ) ) ? absint( $new_instance['offset'] ) : 0;
		$instance['exclude_current'] = ( ! empty( $new_instance['exclude_current'] ) ) ? '1' : '';
		$instance['show_view_all']   = ( ! empty( $new_instance['show_view_all'] ) ) ? '1' : '';
		$instance['more_label']      = ( ! empty( $new_instance['more_label'] ) ) ? sanitize_text_field( $new_instance['more_label'] ) : '';
		$instance['more_url']        = ( ! empty( $new_instance['more_url'] ) ) ? esc_url_raw( $new_instance['more_url'] ) : '';

		return $instance;
	}

	/**
	 * Outputs the widget content.
	 *
	 * @param array $args Sidebar args.
	 * @param array $instance Widget settings.
	 * @return void
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'style'           => 'card',
			'title'           => '',
			'icon'            => 'trending-up',
			'post_type'       => 'post',
			'query_type'      => 'latest',
			'category'        => 0,
			'order'           => 'DESC',
			'posts_count'     => 5,
			'offset'          => 0,
			'exclude_current' => '1',
			'show_view_all'   => '1',
			'more_label'      => 'مشاهده همه',
			'more_url'        => '',
		) );

		// Query args
		$query_args = array(
			'post_type'           => $instance['post_type'],
			'post_status'         => 'publish',
			'posts_per_page'      => $instance['posts_count'],
			'offset'              => $instance['offset'],
			'ignore_sticky_posts' => true,
		);

		// Order
		if ( 'rand' === $instance['order'] ) {
			$query_args['orderby'] = 'rand';
		} else {
			$query_args['order'] = $instance['order'];
			if ( 'popular' === $instance['query_type'] ) {
				$query_args['orderby'] = 'comment_count';
			} else {
				$query_args['orderby'] = 'date';
			}
		}

		// Category
		if ( 'category' === $instance['query_type'] && ! empty( $instance['category'] ) ) {
			$query_args['cat'] = $instance['category'];
		}

		// Exclude current
		if ( '1' === $instance['exclude_current'] && is_singular() ) {
			$query_args['post__not_in'] = array( get_queried_object_id() );
		}

		$query = new WP_Query( $query_args );

		if ( ! $query->have_posts() ) {
			return;
		}

		// View All URL
		$more_url = $instance['more_url'];
		if ( empty( $more_url ) && '1' === $instance['show_view_all'] ) {
			if ( 'category' === $instance['query_type'] && ! empty( $instance['category'] ) ) {
				$more_url = get_category_link( $instance['category'] );
			} else {
				$more_url = get_post_type_archive_link( $instance['post_type'] );
			}
			if ( ! $more_url ) {
				$more_url = home_url( '/' );
			}
		}

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		
		if ( 'card' === $instance['style'] ) : ?>
			<div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
				<?php eghtesadran_render_widget_header( $instance['title'], $instance['icon'], ( '1' === $instance['show_view_all'] ? $more_url : '' ), $instance['more_label'] ); ?>

				<div class="flex flex-col gap-4 relative z-10">
					<?php 
					$first = true;
					while ( $query->have_posts() ) : $query->the_post(); 
						if ( $first ) : $first = false; ?>
							<a href="<?php the_permalink(); ?>" class="group relative block rounded-2xl overflow-hidden shadow-sm">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'eghtesadran-card-md', array( 'class' => 'w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500' ) ); ?>
								<?php else : ?>
									<img src="<?php echo esc_url( eghtesadran_get_widget_fallback_image() ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
								<?php endif; ?>
								<div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent flex items-end p-5">
									<div class="absolute top-3 right-3 bg-red-600 text-white text-xs font-bold px-2.5 py-1 rounded-md shadow-lg flex items-center gap-1">
										<i data-lucide="flame" class="w-3.5 h-3.5"></i> <?php esc_html_e( 'داغ', 'eghtesadran' ); ?>
									</div>
									<h4 class="text-sm md:text-base font-black text-white leading-relaxed group-hover:text-primary transition-colors">
										<?php the_title(); ?>
									</h4>
								</div>
							</a>
						<?php else : ?>
							<a href="<?php the_permalink(); ?>" class="group flex gap-4 items-center bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-transparent hover:border-slate-200 dark:hover:border-slate-700 transition-colors">
								<div class="relative w-20 h-20 rounded-lg overflow-hidden shrink-0 shadow-sm">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-500' ) ); ?>
									<?php else : ?>
										<img src="<?php echo esc_url( eghtesadran_get_widget_fallback_image() ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
									<?php endif; ?>
								</div>
								<div class="flex-1">
									<h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 group-hover:text-primary dark:group-hover:text-red-400 transition-colors leading-relaxed">
										<?php the_title(); ?>
									</h4>
								</div>
							</a>
						<?php endif; 
					endwhile; ?>
				</div>
			</div>
		<?php else : // list ?>
			<div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm">
				<?php eghtesadran_render_widget_header( $instance['title'], $instance['icon'], ( '1' === $instance['show_view_all'] ? $more_url : '' ), $instance['more_label'] ); ?>

				<div class="flex flex-col gap-3.5">
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
						<div class="flex gap-3 items-start group cursor-pointer border-b border-slate-50 dark:border-slate-750 pb-3 last:border-0 last:pb-0">
							<a href="<?php the_permalink(); ?>" class="flex gap-3 items-start w-full">
								<?php
								$badge = get_post_meta( get_the_ID(), '_eghtesadran_badge', true );
								$badge_label = '';
								$badge_class = 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400';
								if ( 'featured' === $badge ) {
									$badge_label = __( 'ویژه', 'eghtesadran' );
									$badge_class = 'bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 border border-blue-100 dark:border-blue-900/50';
								} elseif ( 'breaking' === $badge ) {
									$badge_label = __( 'فوری', 'eghtesadran' );
									$badge_class = 'bg-red-50 text-primary dark:bg-red-950/30 dark:text-red-400 border border-red-100 dark:border-red-900/50';
								} elseif ( 'trending' === $badge ) {
									$badge_label = __( 'داغ', 'eghtesadran' );
									$badge_class = 'bg-orange-50 text-orange-600 dark:bg-orange-950/40 dark:text-orange-400 border border-orange-100 dark:border-orange-900/50';
								} else {
									$badge_label = get_the_time( 'H:i' );
									$badge_class = 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400';
								}
								?>
								<div class="<?php echo esc_attr( $badge_class ); ?> font-extrabold text-[10px] px-2 py-0.5 rounded min-w-12 flex items-center justify-center gap-1 shrink-0">
									<?php echo esc_html( $badge_label ); ?>
								</div>
								<h4 class="text-xs font-bold text-slate-700 dark:text-slate-350 group-hover:text-primary dark:group-hover:text-red-400 transition-colors leading-relaxed">
									<?php the_title(); ?>
								</h4>
							</a>
						</div>
					<?php endwhile; ?>
				</div>
			</div>
		<?php endif;

		wp_reset_postdata();
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Multi-purpose posts widget.
 */
class Eghtesadran_Widget_Multi_Purpose_Posts extends Eghtesadran_Widget_Base {
	/**
	 * Sets up the widget.
	 */
	public function __construct() {
		parent::__construct(
			'eghtesadran_multi_purpose_posts',
			__( 'اقتصادران: ویجت چندمنظوره مطالب', 'eghtesadran' ),
			array(
				'description' => __( 'ویجت انعطاف‌پذیر موضوعی برای نمایش اخبار در استایل‌های متنوع (بانک، بورس، خودرو و غیره) با چیدمان تک یا دو ستونه.', 'eghtesadran' ),
			)
		);
	}

	/**
	 * Outputs the widget form.
	 *
	 * @param array $instance Current settings.
	 * @return void
	 */
	public function form( $instance ) {
		$defaults = array(
			'style'           => 'style_a',
			'title'           => '',
			'icon'            => '',
			'query_type'      => 'category',
			'category'        => 0,
			'order'           => 'DESC',
			'posts_count'     => 3,
			'offset'          => 0,
			'column_mode'     => 'single',
			'show_excerpt'    => '1',
			'excerpt_length'  => 150,
			'show_view_all'   => '1',
			'more_label'      => 'مشاهده همه',
			'more_url'        => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$lucide_icons = array(
			''             => 'بدون آیکون (خط نشانگر پیش‌فرض)',
			'trending-up'  => 'Trending Up (پربازدید)',
			'clock'        => 'Clock (آخرین اخبار)',
			'flame'        => 'Flame (داغ)',
			'zap'          => 'Zap (انرژی)',
			'newspaper'    => 'Newspaper (اخبار)',
			'star'         => 'Star (ستاره)',
			'heart'        => 'Heart (محبوب)',
			'eye'          => 'Eye (مشاهده)',
			'bar-chart-2'  => 'Chart (آمار)',
			'award'        => 'Award (برگزیده)',
			'bell'         => 'Bell (اطلاعیه)',
			'briefcase'    => 'Briefcase (بانک و بیمه)',
			'coins'        => 'Coins (ارز و طلا)',
			'shopping-bag' => 'Shopping Bag (بازار)',
			'home'         => 'Home (مسکن)',
			'car'          => 'Car (خودرو)',
			'droplet'      => 'Droplet (پتروشیمی)',
			'layers'       => 'Layers (فلزات اساسی)',
			'line-chart'   => 'Line Chart (بورس)',
			'shield-check' => 'Shield Check (مجوزها)',
		);
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'عنوان سفارشی:', 'eghtesadran' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
			<small class="description"><?php esc_html_e( 'در صورت خالی بودن، نام دسته‌بندی نمایش داده می‌شود.', 'eghtesadran' ); ?></small>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"><?php esc_html_e( 'آیکن هدر:', 'eghtesadran' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon' ) ); ?>">
				<?php foreach ( $lucide_icons as $key => $label ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $instance['icon'], $key ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'query_type' ) ); ?>"><?php esc_html_e( 'نوع محتوا:', 'eghtesadran' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'query_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'query_type' ) ); ?>">
				<option value="category" <?php selected( $instance['query_type'], 'category' ); ?>><?php esc_html_e( 'دسته بندی خاص', 'eghtesadran' ); ?></option>
				<option value="latest" <?php selected( $instance['query_type'], 'latest' ); ?>><?php esc_html_e( 'آخرین مطالب (کل سایت)', 'eghtesadran' ); ?></option>
				<option value="popular" <?php selected( $instance['query_type'], 'popular' ); ?>><?php esc_html_e( 'پربازدیدترین‌ها', 'eghtesadran' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'دسته‌بندی مربوطه:', 'eghtesadran' ); ?></label>
			<?php
			wp_dropdown_categories(
				array(
					'show_option_all' => __( 'همه دسته‌ها', 'eghtesadran' ),
					'hide_empty'      => 0,
					'name'            => $this->get_field_name( 'category' ),
					'id'              => $this->get_field_id( 'category' ),
					'selected'        => (int) $instance['category'],
					'class'           => 'widefat',
				)
			);
			?>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'ترتیب نمایش:', 'eghtesadran' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
				<option value="DESC" <?php selected( $instance['order'], 'DESC' ); ?>><?php esc_html_e( 'جدیدترین', 'eghtesadran' ); ?></option>
				<option value="ASC" <?php selected( $instance['order'], 'ASC' ); ?>><?php esc_html_e( 'قدیمی‌ترین', 'eghtesadran' ); ?></option>
				<option value="rand" <?php selected( $instance['order'], 'rand' ); ?>><?php esc_html_e( 'تصادفی', 'eghtesadran' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'posts_count' ) ); ?>"><?php esc_html_e( 'تعداد مطالب:', 'eghtesadran' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'posts_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_count' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $instance['posts_count'] ); ?>" size="3">
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>"><?php esc_html_e( 'افست (پرش از ابتدا):', 'eghtesadran' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'offset' ) ); ?>" type="number" step="1" min="0" value="<?php echo esc_attr( $instance['offset'] ); ?>" size="3">
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'استایل نمایش ویجت:', 'eghtesadran' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>">
				<option value="style_a" <?php selected( $instance['style'], 'style_a' ); ?>><?php esc_html_e( 'استایل آ (عمودی ویژه بانک و انرژی)', 'eghtesadran' ); ?></option>
				<option value="style_b" <?php selected( $instance['style'], 'style_b' ); ?>><?php esc_html_e( 'استایل ب (افقی ویژه بورس، مسکن و طلا)', 'eghtesadran' ); ?></option>
				<option value="style_c" <?php selected( $instance['style'], 'style_c' ); ?>><?php esc_html_e( 'استایل ج (شبکه‌ای ویژه صنعت و خودرو)', 'eghtesadran' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'column_mode' ) ); ?>"><?php esc_html_e( 'حالت ستون در پوسته:', 'eghtesadran' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'column_mode' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'column_mode' ) ); ?>">
				<option value="single" <?php selected( $instance['column_mode'], 'single' ); ?>><?php esc_html_e( 'تک ستونه (۱۰۰٪ - تمام عرض)', 'eghtesadran' ); ?></option>
				<option value="double" <?php selected( $instance['column_mode'], 'double' ); ?>><?php esc_html_e( 'دو ستونه (۵۰٪ - چیدمان دو ستون کنار هم)', 'eghtesadran' ); ?></option>
			</select>
		</p>

		<hr style="margin: 15px 0; border: 0; border-top: 1px solid #eee;">

		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_excerpt' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['show_excerpt'], '1' ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>"><?php esc_html_e( 'نمایش خلاصه خبر (توضیحات)', 'eghtesadran' ); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>"><?php esc_html_e( 'تعداد کاراکتر خلاصه خبر:', 'eghtesadran' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'excerpt_length' ) ); ?>" type="number" step="10" min="10" value="<?php echo esc_attr( $instance['excerpt_length'] ); ?>">
		</p>

		<hr style="margin: 15px 0; border: 0; border-top: 1px solid #eee;">

		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_view_all' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_view_all' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['show_view_all'], '1' ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_view_all' ) ); ?>"><?php esc_html_e( 'نمایش دکمه مشاهده همه؟', 'eghtesadran' ); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'more_label' ) ); ?>"><?php esc_html_e( 'متن دکمه مشاهده همه:', 'eghtesadran' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'more_label' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'more_label' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['more_label'] ); ?>">
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'more_url' ) ); ?>"><?php esc_html_e( 'لینک دکمه مشاهده همه (خالی = خودکار):', 'eghtesadran' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'more_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'more_url' ) ); ?>" type="url" value="<?php echo esc_attr( $instance['more_url'] ); ?>">
		</p>
		<?php
	}

	/**
	 * Saves widget settings.
	 *
	 * @param array $new_instance New settings.
	 * @param array $old_instance Old settings.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['style']          = ( ! empty( $new_instance['style'] ) ) ? sanitize_key( $new_instance['style'] ) : 'style_a';
		$instance['title']          = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['icon']           = ( isset( $new_instance['icon'] ) ) ? sanitize_key( $new_instance['icon'] ) : '';
		$instance['query_type']     = ( ! empty( $new_instance['query_type'] ) ) ? sanitize_key( $new_instance['query_type'] ) : 'category';
		$instance['category']       = ( ! empty( $new_instance['category'] ) ) ? absint( $new_instance['category'] ) : 0;
		$instance['order']          = ( ! empty( $new_instance['order'] ) ) ? sanitize_text_field( $new_instance['order'] ) : 'DESC';
		$instance['posts_count']    = ( ! empty( $new_instance['posts_count'] ) ) ? absint( $new_instance['posts_count'] ) : 3;
		$instance['offset']         = ( ! empty( $new_instance['offset'] ) ) ? absint( $new_instance['offset'] ) : 0;
		$instance['column_mode']    = ( ! empty( $new_instance['column_mode'] ) ) ? sanitize_key( $new_instance['column_mode'] ) : 'single';
		$instance['show_excerpt']   = $this->sanitize_checkbox( $new_instance['show_excerpt'] ?? '' );
		$instance['excerpt_length'] = ( ! empty( $new_instance['excerpt_length'] ) ) ? absint( $new_instance['excerpt_length'] ) : 150;
		$instance['show_view_all']  = $this->sanitize_checkbox( $new_instance['show_view_all'] ?? '' );
		$instance['more_label']     = ( ! empty( $new_instance['more_label'] ) ) ? sanitize_text_field( $new_instance['more_label'] ) : 'مشاهده همه';
		$instance['more_url']       = ( ! empty( $new_instance['more_url'] ) ) ? esc_url_raw( $new_instance['more_url'] ) : '';

		return $instance;
	}

	/**
	 * Outputs the widget content.
	 *
	 * @param array $args Sidebar args.
	 * @param array $instance Widget settings.
	 * @return void
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'style'          => 'style_a',
			'title'          => '',
			'icon'           => '',
			'query_type'     => 'category',
			'category'       => 0,
			'order'          => 'DESC',
			'posts_count'    => 3,
			'offset'         => 0,
			'column_mode'    => 'single',
			'show_excerpt'   => '1',
			'excerpt_length' => 150,
			'show_view_all'  => '1',
			'more_label'     => 'مشاهده همه',
			'more_url'       => '',
		) );

		// Query args
		$query_args = array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => $instance['posts_count'],
			'offset'              => $instance['offset'],
			'ignore_sticky_posts' => true,
		);

		// Order
		if ( 'rand' === $instance['order'] ) {
			$query_args['orderby'] = 'rand';
		} else {
			$query_args['order'] = $instance['order'];
			if ( 'popular' === $instance['query_type'] ) {
				$query_args['orderby'] = 'comment_count';
			} else {
				$query_args['orderby'] = 'date';
			}
		}

		// Category
		if ( 'category' === $instance['query_type'] && ! empty( $instance['category'] ) ) {
			$query_args['cat'] = $instance['category'];
		}

		$query = new WP_Query( $query_args );

		if ( ! $query->have_posts() ) {
			return;
		}

		// Title setup
		$title = $instance['title'];
		if ( empty( $title ) ) {
			if ( 'category' === $instance['query_type'] && ! empty( $instance['category'] ) ) {
				$term = get_term( $instance['category'], 'category' );
				$title = $term ? $term->name : __( 'مطالب موضوعی', 'eghtesadran' );
			} else {
				$title = __( 'مطالب موضوعی', 'eghtesadran' );
			}
		}

		// View All URL
		$more_url = '';
		if ( '1' === $instance['show_view_all'] ) {
			if ( ! empty( $instance['more_url'] ) ) {
				$more_url = $instance['more_url'];
			} elseif ( 'category' === $instance['query_type'] && ! empty( $instance['category'] ) ) {
				$more_url = get_category_link( $instance['category'] );
			}
		}

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
		<div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 w-full">
			<!-- Widget Header -->
			<div class="flex items-center justify-between mb-5 border-b border-slate-100 dark:border-slate-700 pb-3">
				<div class="flex items-center gap-3">
					<?php if ( ! empty( $instance['icon'] ) ) : ?>
						<i data-lucide="<?php echo esc_attr( $instance['icon'] ); ?>" class="w-5.5 h-5.5 text-primary shrink-0" aria-hidden="true"></i>
					<?php else : ?>
						<div class="w-1.5 h-6 bg-primary rounded-full"></div>
					<?php endif; ?>
					<h2 class="text-xl font-extrabold text-slate-900 dark:text-white"><?php echo esc_html( $title ); ?></h2>
				</div>
				<?php if ( '1' === $instance['show_view_all'] && ! empty( $more_url ) ) : ?>
					<a href="<?php echo esc_url( $more_url ); ?>" class="group flex text-[11px] font-bold text-slate-400 dark:text-slate-500 hover:text-primary transition-all duration-300 gap-1 items-center px-2.5 py-1 rounded-full hover:bg-slate-50 dark:hover:bg-slate-700 border border-transparent hover:border-slate-200">
						<?php echo esc_html( $instance['more_label'] ); ?>
						<i data-lucide="chevron-left" class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-1" aria-hidden="true"></i>
					</a>
				<?php endif; ?>
			</div>

			<!-- Widget Output Styles -->
			<?php if ( 'style_a' === $instance['style'] ) : ?>
				<!-- Style A (Layout A Fallback) -->
				<div class="grid grid-cols-1 gap-5">
					<?php $count = 0; while ( $query->have_posts() ) : $query->the_post(); $count++; ?>
						<?php if ( 1 === $count ) : ?>
							<div class="group cursor-pointer">
								<a href="<?php the_permalink(); ?>" class="block">
									<div class="relative rounded-xl overflow-hidden aspect-[16/9] mb-4 shadow-sm">
										<?php if ( has_post_thumbnail() ) : ?>
											<?php the_post_thumbnail( 'eghtesadran-card-md', array( 'class' => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-103' ) ); ?>
										<?php else : ?>
											<img src="<?php echo esc_url( eghtesadran_get_widget_fallback_image() ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover" />
										<?php endif; ?>
									</div>
									<div class="flex items-center gap-2 mb-2">
										<span class="flex items-center gap-1 text-slate-400/90 dark:text-slate-500/90 text-[10px] font-medium">
											<i data-lucide="clock" class="w-3 h-3 opacity-70" aria-hidden="true"></i>
											<?php echo esc_html( get_the_date() ); ?>
										</span>
									</div>
									<?php 
									$rotiter = get_post_meta( get_the_ID(), '_news_rotiter', true );
									if ( ! empty( $rotiter ) ) :
										?>
										<span class="block text-[10px] md:text-xs text-slate-400 dark:text-slate-500 font-bold mb-2"><?php echo esc_html( $rotiter ); ?></span>
									<?php endif; ?>
									<h3 class="text-base md:text-lg font-extrabold text-slate-800 dark:text-slate-200 group-hover:text-primary dark:group-hover:text-red-400 transition-colors mb-2">
										<?php the_title(); ?>
									</h3>
									<?php if ( '1' === $instance['show_excerpt'] ) : 
										$excerpt_chars = absint( $instance['excerpt_length'] );
										$excerpt = wp_strip_all_tags( get_the_excerpt() );
										if ( mb_strlen( $excerpt ) > $excerpt_chars ) {
											$excerpt = mb_substr( $excerpt, 0, $excerpt_chars ) . '...';
										}
									?>
										<p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm leading-relaxed text-justify mb-4">
											<?php echo esc_html( $excerpt ); ?>
										</p>
									<?php endif; ?>
								</a>
							</div>
							<div class="space-y-3 pt-3 border-t border-slate-100 dark:border-slate-700">
						<?php else : ?>
							<a href="<?php the_permalink(); ?>" class="group flex items-start gap-2 text-xs md:text-sm font-medium text-slate-700 dark:text-slate-350 hover:text-primary dark:hover:text-red-400 transition-colors">
								<span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0 mt-2"></span>
								<span><?php the_title(); ?></span>
							</a>
						<?php endif; ?>
					<?php endwhile; ?>
					</div>
				</div>

			<?php elseif ( 'style_b' === $instance['style'] ) : ?>
				<!-- Style B (Layout B Fallback) -->
				<div class="flex flex-col gap-5">
					<?php $count = 0; while ( $query->have_posts() ) : $query->the_post(); $count++; ?>
						<?php if ( 1 === $count ) : ?>
							<div class="group cursor-pointer flex flex-col sm:flex-row gap-4">
								<a href="<?php the_permalink(); ?>" class="flex flex-col sm:flex-row gap-4 w-full">
									<div class="w-full sm:w-44 md:w-48 relative rounded-xl overflow-hidden aspect-[4/3] sm:aspect-auto shrink-0 shadow-sm">
										<?php if ( has_post_thumbnail() ) : ?>
											<?php the_post_thumbnail( 'eghtesadran-thumb', array( 'class' => 'w-full h-full object-cover transition-transform duration-750 group-hover:scale-105' ) ); ?>
										<?php else : ?>
											<img src="<?php echo esc_url( eghtesadran_get_widget_fallback_image() ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover" />
										<?php endif; ?>
									</div>
									<div class="flex flex-col justify-center flex-1">
										<div class="flex items-center gap-2 mb-2">
											<span class="flex items-center gap-1 text-slate-400/90 dark:text-slate-500/90 text-[10px] font-medium">
												<i data-lucide="clock" class="w-3 h-3 opacity-70" aria-hidden="true"></i>
												<?php echo esc_html( get_the_date() ); ?>
											</span>
										</div>
										<?php 
										$rotiter = get_post_meta( get_the_ID(), '_news_rotiter', true );
										if ( ! empty( $rotiter ) ) :
											?>
											<span class="block text-[10px] md:text-xs text-slate-400 dark:text-slate-500 font-bold mb-2 leading-snug"><?php echo esc_html( $rotiter ); ?></span>
										<?php endif; ?>
										<h3 class="text-base font-extrabold text-slate-800 dark:text-slate-200 group-hover:text-primary dark:group-hover:text-red-400 transition-colors mb-2 leading-snug">
											<?php the_title(); ?>
										</h3>
										<?php if ( '1' === $instance['show_excerpt'] ) : 
											$excerpt_chars = absint( $instance['excerpt_length'] );
											$excerpt = wp_strip_all_tags( get_the_excerpt() );
											if ( mb_strlen( $excerpt ) > $excerpt_chars ) {
												$excerpt = mb_substr( $excerpt, 0, $excerpt_chars ) . '...';
											}
										?>
											<p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm leading-relaxed text-justify">
												<?php echo esc_html( $excerpt ); ?>
											</p>
										<?php endif; ?>
									</div>
								</a>
							</div>
							<div class="space-y-3 pt-3 border-t border-slate-100 dark:border-slate-700">
						<?php else : ?>
							<a href="<?php the_permalink(); ?>" class="group flex items-start gap-2 text-xs md:text-sm font-medium text-slate-700 dark:text-slate-350 hover:text-primary dark:hover:text-red-400 transition-colors">
								<span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0 mt-2"></span>
								<span><?php the_title(); ?></span>
							</a>
						<?php endif; ?>
					<?php endwhile; ?>
					</div>
				</div>

			<?php elseif ( 'style_c' === $instance['style'] ) : ?>
				<!-- Style C (Layout C Fallback) -->
				<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
						<div class="group cursor-pointer bg-slate-50 dark:bg-slate-900/40 p-3 rounded-xl border border-slate-100 dark:border-slate-700 hover:shadow-sm transition-all">
							<a href="<?php the_permalink(); ?>" class="block">
								<div class="relative rounded-lg overflow-hidden aspect-[16/10] mb-3">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php the_post_thumbnail( 'eghtesadran-thumb', array( 'class' => 'w-full h-full object-cover transition-transform duration-500 group-hover:scale-105' ) ); ?>
									<?php else : ?>
										<img src="<?php echo esc_url( eghtesadran_get_widget_fallback_image() ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover" />
									<?php endif; ?>
								</div>
								<div class="flex items-center justify-end mb-2">
									<span class="flex items-center gap-1 text-slate-400/90 dark:text-slate-500/90 text-[10px] font-medium">
										<i data-lucide="clock" class="w-3 h-3 opacity-70" aria-hidden="true"></i>
										<?php echo esc_html( get_the_date() ); ?>
									</span>
								</div>
								<?php 
								$rotiter = get_post_meta( get_the_ID(), '_news_rotiter', true );
								if ( ! empty( $rotiter ) ) :
									?>
									<span class="block text-[9px] md:text-[10px] text-slate-400 dark:text-slate-500 font-bold mb-1.5 leading-snug"><?php echo esc_html( $rotiter ); ?></span>
								<?php endif; ?>
								<h4 class="text-slate-800 dark:text-slate-200 font-bold text-xs md:text-sm group-hover:text-primary dark:group-hover:text-red-400 transition-colors leading-snug">
									<?php the_title(); ?>
								</h4>
							</a>
						</div>
					<?php endwhile; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
		wp_reset_postdata();
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Notes widget.
 */
class Eghtesadran_Widget_Note extends Eghtesadran_Widget_Base {
	/**
	 * Sets up the widget.
	 */
	public function __construct() {
		parent::__construct(
			'eghtesadran_widget_note',
			__( 'اقتصادران: یادداشت', 'eghtesadran' ),
			array(
				'description' => __( 'نمایش نوشته‌های بخش یادداشت با تصویر نویسنده و فرمت اختصاصی.', 'eghtesadran' ),
			)
		);
	}

	/**
	 * Outputs the widget form.
	 *
	 * @param array $instance Current settings.
	 * @return void
	 */
	public function form( $instance ) {
		$defaults = array(
			'title'       => __( 'یادداشت‌ها', 'eghtesadran' ),
			'posts_count' => 3,
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'عنوان:', 'eghtesadran' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'posts_count' ) ); ?>"><?php esc_html_e( 'تعداد نمایش:', 'eghtesadran' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'posts_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_count' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $instance['posts_count'] ); ?>" size="3">
		</p>
		<?php
	}

	/**
	 * Saves widget settings.
	 *
	 * @param array $new_instance New settings.
	 * @param array $old_instance Old settings.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		return array(
			'title'       => sanitize_text_field( $new_instance['title'] ?? '' ),
			'posts_count' => absint( $new_instance['posts_count'] ?? 3 ),
		);
	}

	/**
	 * Outputs the widget content.
	 *
	 * @param array $args Sidebar args.
	 * @param array $instance Widget settings.
	 * @return void
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'       => __( 'یادداشت‌ها', 'eghtesadran' ),
				'posts_count' => 3,
			)
		);

		$query_args = array(
			'post_type'      => 'post',
			'posts_per_page' => $instance['posts_count'],
			'meta_query'     => array(
				array(
					'key'   => '_news_content_type',
					'value' => 'note',
				),
			),
		);

		$notes_query = new WP_Query( $query_args );

		if ( ! $notes_query->have_posts() ) {
			return;
		}

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
		<div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
			<div class="mb-4">
				<h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
					<i data-lucide="pen-tool" class="w-5 h-5 text-primary" aria-hidden="true"></i>
					<?php echo esc_html( $instance['title'] ); ?>
				</h3>
			</div>

			<div class="flex flex-col gap-4 relative z-10">
				<?php
				while ( $notes_query->have_posts() ) :
					$notes_query->the_post();
					$author_name = get_post_meta( get_the_ID(), '_news_author_name', true );
					if ( empty( $author_name ) ) {
						$author_name = get_the_author();
					}
					$author_pos = get_post_meta( get_the_ID(), '_news_author_position', true );
					?>
					<div class="group cursor-pointer flex items-center gap-3.5 bg-slate-50 dark:bg-slate-900/40 p-3.5 rounded-xl border border-slate-100 dark:border-slate-700 hover:shadow-sm hover:border-slate-200 dark:hover:border-slate-650 transition-all relative overflow-hidden">
						<a href="<?php the_permalink(); ?>" class="flex items-center gap-3.5 w-full">
							<div class="relative shrink-0">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( array( 56, 56 ), array( 'class' => 'w-14 h-14 rounded-full object-cover border-2 border-white dark:border-slate-800 shadow-sm' ) ); ?>
								<?php else : ?>
									<div class="w-14 h-14 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
										<i data-lucide="user" class="w-6 h-6 text-slate-400"></i>
									</div>
								<?php endif; ?>
							</div>
							<div class="z-10 flex-1">
								<p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold mb-1">
									<?php echo esc_html( $author_name ); ?>
									<?php if ( $author_pos ) : ?>
										<span class="text-slate-300 dark:text-slate-700 mx-1">|</span>
										<span class="font-normal text-slate-400"><?php echo esc_html( $author_pos ); ?></span>
									<?php endif; ?>
								</p>
								<?php 
								$rotiter = get_post_meta( get_the_ID(), '_news_rotiter', true );
								if ( ! empty( $rotiter ) ) :
									?>
									<span class="block text-[10px] text-slate-400 dark:text-slate-500 font-bold mb-1.5 leading-relaxed"><?php echo esc_html( $rotiter ); ?></span>
								<?php endif; ?>
								<h4 class="font-bold text-slate-850 dark:text-slate-200 text-xs md:text-sm group-hover:text-primary dark:group-hover:text-red-400 transition-colors leading-relaxed line-clamp-2">
									<?php the_title(); ?>
								</h4>
							</div>
						</a>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
		<?php
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Interviews widget.
 */
class Eghtesadran_Widget_Interview extends Eghtesadran_Widget_Base {
	/**
	 * Sets up the widget.
	 */
	public function __construct() {
		parent::__construct(
			'eghtesadran_widget_interview',
			__( 'اقتصادران: مصاحبه', 'eghtesadran' ),
			array(
				'description' => __( 'نمایش نوشته‌های بخش مصاحبه با تصویر مصاحبه‌شونده و فرمت اختصاصی.', 'eghtesadran' ),
			)
		);
	}

	/**
	 * Outputs the widget form.
	 *
	 * @param array $instance Current settings.
	 * @return void
	 */
	public function form( $instance ) {
		$defaults = array(
			'title'       => __( 'مصاحبه‌ها', 'eghtesadran' ),
			'posts_count' => 3,
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'عنوان:', 'eghtesadran' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'posts_count' ) ); ?>"><?php esc_html_e( 'تعداد نمایش:', 'eghtesadran' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'posts_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_count' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $instance['posts_count'] ); ?>" size="3">
		</p>
		<?php
	}

	/**
	 * Saves widget settings.
	 *
	 * @param array $new_instance New settings.
	 * @param array $old_instance Old settings.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		return array(
			'title'       => sanitize_text_field( $new_instance['title'] ?? '' ),
			'posts_count' => absint( $new_instance['posts_count'] ?? 3 ),
		);
	}

	/**
	 * Outputs the widget content.
	 *
	 * @param array $args Sidebar args.
	 * @param array $instance Widget settings.
	 * @return void
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'       => __( 'مصاحبه‌ها', 'eghtesadran' ),
				'posts_count' => 3,
			)
		);

		$query_args = array(
			'post_type'      => 'post',
			'posts_per_page' => $instance['posts_count'],
			'meta_query'     => array(
				array(
					'key'   => '_news_content_type',
					'value' => 'interview',
				),
			),
		);

		$interviews_query = new WP_Query( $query_args );

		if ( ! $interviews_query->have_posts() ) {
			return;
		}

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
		<div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
			<div class="mb-4">
				<h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
					<i data-lucide="mic" class="w-5 h-5 text-primary" aria-hidden="true"></i>
					<?php echo esc_html( $instance['title'] ); ?>
				</h3>
			</div>

			<div class="flex flex-col gap-4 relative z-10">
				<?php
				while ( $interviews_query->have_posts() ) :
					$interviews_query->the_post();
					$interviewee_name = get_post_meta( get_the_ID(), '_news_interviewee_name', true );
					if ( empty( $interviewee_name ) ) {
						$interviewee_name = __( 'مصاحبه‌شونده', 'eghtesadran' );
					}
					$interviewee_pos = get_post_meta( get_the_ID(), '_news_interviewee_position', true );
					?>
					<div class="group cursor-pointer flex items-center gap-3.5 bg-slate-50 dark:bg-slate-900/40 p-3.5 rounded-xl border border-slate-100 dark:border-slate-700 hover:shadow-sm hover:border-slate-200 dark:hover:border-slate-650 transition-all relative overflow-hidden">
						<a href="<?php the_permalink(); ?>" class="flex items-center gap-3.5 w-full">
							<i data-lucide="mic" class="absolute -left-2 -bottom-2 w-20 h-20 text-slate-300/30 dark:text-slate-700/25 opacity-50 rotate-12 transition-all group-hover:scale-105 duration-300" aria-hidden="true"></i>
							<div class="relative shrink-0">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( array( 56, 56 ), array( 'class' => 'w-14 h-14 rounded-full object-cover border-2 border-white dark:border-slate-800 shadow-sm' ) ); ?>
								<?php else : ?>
									<div class="w-14 h-14 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
										<i data-lucide="user" class="w-6 h-6 text-slate-400"></i>
									</div>
								<?php endif; ?>
							</div>
							<div class="z-10 flex-1">
								<p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold mb-1">
									<?php echo esc_html( $interviewee_name ); ?>
									<?php if ( $interviewee_pos ) : ?>
										<span class="text-slate-300 dark:text-slate-700 mx-1">|</span>
										<span class="font-normal text-slate-400"><?php echo esc_html( $interviewee_pos ); ?></span>
									<?php endif; ?>
								</p>
								<?php 
								$rotiter = get_post_meta( get_the_ID(), '_news_rotiter', true );
								if ( ! empty( $rotiter ) ) :
									?>
									<span class="block text-[10px] text-slate-400 dark:text-slate-500 font-bold mb-1.5 leading-relaxed"><?php echo esc_html( $rotiter ); ?></span>
								<?php endif; ?>
								<h4 class="font-bold text-slate-850 dark:text-slate-200 text-xs md:text-sm group-hover:text-primary dark:group-hover:text-red-400 transition-colors leading-relaxed line-clamp-2">
									<?php the_title(); ?>
								</h4>
							</div>
						</a>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
		<?php
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Registers custom widgets.
 *
 * @return void
 */
function eghtesadran_register_custom_widgets() {
	register_widget( 'Eghtesadran_Widget_Market_Dashboard' );
	register_widget( 'Eghtesadran_Widget_Promo_Banner' );
	register_widget( 'Eghtesadran_Widget_Universal_Posts' );
	register_widget( 'Eghtesadran_Widget_Multi_Purpose_Posts' );
	register_widget( 'Eghtesadran_Widget_Note' );
	register_widget( 'Eghtesadran_Widget_Interview' );
}
add_action( 'widgets_init', 'eghtesadran_register_custom_widgets', 20 );

/**
 * Filter dynamic sidebar params to inject single/double column classes.
 *
 * @param array $params Sidebar params.
 * @return array
 */
function eghtesadran_dynamic_sidebar_params( $params ) {
	$widget_id = $params[0]['widget_id'];
	
	if ( strpos( $widget_id, 'eghtesadran_multi_purpose_posts' ) === 0 ) {
		$all_instances = get_option( 'widget_eghtesadran_multi_purpose_posts' );
		
		// Extract widget number from widget_id (e.g. eghtesadran_multi_purpose_posts-2)
		if ( preg_match( '/-([0-9]+)$/', $widget_id, $matches ) ) {
			$widget_num = (int) $matches[1];
			
			if ( isset( $all_instances[ $widget_num ] ) ) {
				$instance = $all_instances[ $widget_num ];
				$column_mode = isset( $instance['column_mode'] ) ? $instance['column_mode'] : 'single';
				$class_to_add = ( 'single' === $column_mode ) ? 'lg:col-span-2 col-span-1' : 'lg:col-span-1 col-span-1';
				
				// Replace before_widget class attribute with inject class
				if ( strpos( $params[0]['before_widget'], 'class="' ) !== false ) {
					$params[0]['before_widget'] = str_replace( 'class="', 'class="' . $class_to_add . ' ', $params[0]['before_widget'] );
				} else {
					$params[0]['before_widget'] = str_replace( 'class=\'', 'class=\'' . $class_to_add . ' ', $params[0]['before_widget'] );
				}
			}
		}
	}
	
	return $params;
}
add_filter( 'dynamic_sidebar_params', 'eghtesadran_dynamic_sidebar_params' );

