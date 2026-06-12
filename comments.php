<?php
/**
 * The template for displaying comments.
 *
 * @package Eghtesadran
 */

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden print:hidden">
	<!-- Header -->
	<div class="w-full flex items-center justify-between p-5 md:p-6 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700">
		<div class="flex items-center gap-3">
			<div class="bg-primary/10 text-primary p-2 rounded-xl">
				<i data-lucide="message-square" class="w-5 h-5" aria-hidden="true"></i>
			</div>
			<h3 class="text-lg font-black text-slate-900 dark:text-white">
				<?php
				$comments_number = get_comments_number();
				if ( '1' === $comments_number ) {
					printf( _x( 'یک نظر', 'comments title', 'eghtesadran' ) );
				} else {
					printf(
						/* translators: 1: number of comments, 2: post title */
						_nx(
							'نظرات کاربران (%1$s)',
							'نظرات کاربران (%1$s)',
							$comments_number,
							'comments title',
							'eghtesadran'
						),
						number_format_i18n( $comments_number )
					);
				}
				?>
			</h3>
		</div>
	</div>

	<div class="p-5 md:p-6">
		<?php if ( comments_open() ) : ?>
			<div class="mb-10 bg-slate-50 dark:bg-slate-900/50 p-5 rounded-2xl border border-slate-200 dark:border-slate-700">
				<?php
				comment_form(
					array(
						'title_reply'          => __( 'دیدگاه خود را بنویسید', 'eghtesadran' ),
						'title_reply_to'       => __( 'پاسخ به %s', 'eghtesadran' ),
						'class_form'           => 'space-y-4',
						'comment_field'        => '<div><textarea id="comment" name="comment" placeholder="' . esc_attr__( 'متن دیدگاه شما...', 'eghtesadran' ) . '" rows="4" class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm outline-none focus:border-primary transition-colors text-slate-800 dark:text-white placeholder-slate-400 resize-none" required></textarea></div>',
						'fields'               => array(
							'author' => '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
								<div><input id="author" name="author" type="text" placeholder="' . esc_attr__( 'نام و نام خانوادگی', 'eghtesadran' ) . '" class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm outline-none focus:border-primary transition-colors text-slate-800 dark:text-white placeholder-slate-400" required></div>
								<div><input id="email" name="email" type="email" placeholder="' . esc_attr__( 'ایمیل (اختیاری)', 'eghtesadran' ) . '" class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm outline-none focus:border-primary transition-colors text-slate-800 dark:text-white placeholder-slate-400 text-left" dir="ltr"></div>
							</div>',
							'email'  => '',
							'url'    => '',
							'cookies'=> '',
						),
						'submit_button'        => '<div class="flex justify-end"><button name="%1$s" type="submit" id="%2$s" class="bg-primary hover:bg-primary-hover text-white font-bold text-sm px-6 py-2.5 rounded-xl transition-colors flex items-center gap-2">%3$s <i data-lucide="send" class="w-4 h-4" aria-hidden="true"></i></button></div>',
						'submit_field'         => '%1$s %2$s',
						'label_submit'         => __( 'ارسال دیدگاه', 'eghtesadran' ),
						'comment_notes_before' => '',
					)
				);
				?>
			</div>
		<?php endif; ?>

		<?php if ( have_comments() ) : ?>
			<div class="space-y-6">
				<?php
				wp_list_comments(
					array(
						'style'      => 'div',
						'short_ping' => true,
						'callback'   => 'eghtesadran_comment_callback',
					)
				);
				?>
			</div>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
				<nav class="mt-8 text-center">
					<?php
					paginate_comments_links(
						array(
							'prev_text' => '<i data-lucide="chevron-right" class="w-4 h-4"></i>',
							'next_text' => '<i data-lucide="chevron-left" class="w-4 h-4"></i>',
						)
					);
					?>
				</nav>
			<?php endif; ?>

		<?php endif; ?>
	</div>
</div>
