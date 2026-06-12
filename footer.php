<?php
/**
 * Footer template.
 *
 * @package Eghtesadran
 */
?>
		</main>

		<footer class="bg-[#070b13] relative overflow-hidden text-slate-300 pt-16 pb-8 border-t-[4px] border-primary print:hidden">
			<div class="absolute top-0 right-1/4 w-80 h-80 bg-primary/5 rounded-full mix-blend-screen filter blur-[100px] opacity-30 pointer-events-none"></div>
			<div class="absolute bottom-0 left-1/4 w-80 h-80 bg-red-800/5 rounded-full mix-blend-screen filter blur-[100px] opacity-30 pointer-events-none"></div>

			<div class="max-w-[1400px] mx-auto px-4 md:px-8 relative z-10">
				<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
					<?php get_template_part( 'template-parts/footer/brand' ); ?>

					<div class="col-span-1 lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-8">
						<?php get_template_part( 'template-parts/footer/quick-links' ); ?>
						<?php get_template_part( 'template-parts/footer/contact-info' ); ?>
					</div>
				</div>

				<div class="pt-6 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4">
					<p class="text-[11px] text-slate-500 font-medium">
						<?php echo esc_html( eghtesadran_get_copyright_text() ); ?>
					</p>
					<a href="https://ihasht.ir" target="_blank" rel="noopener noreferrer" class="flex items-center gap-1.5 text-[10px] text-slate-650 font-bold bg-white/5 border border-white/10 px-3 py-1.5 rounded-full hover:bg-white/10 transition-colors" aria-label="<?php esc_attr_e( 'طراحی و توسعه: هشت بهشت (باز شدن در تب جدید)', 'eghtesadran' ); ?>">
						<span><?php esc_html_e( 'طراحی و توسعه:', 'eghtesadran' ); ?></span>
						<span class="text-slate-400">هشت بهشت</span>
					</a>
				</div>
			</div>
		</footer>

		<button id="back-to-top" class="fixed bottom-8 right-8 group z-[100] transition-all duration-700 ease-out flex items-center justify-center outline-none opacity-0 translate-y-16 scale-50 pointer-events-none" aria-label="<?php echo esc_attr__( 'بازگشت به بالای صفحه', 'eghtesadran' ); ?>">
			<div class="w-11 h-11 bg-white dark:bg-slate-800 border-2 border-primary group-hover:border-primary-hover rounded-full flex items-center justify-center transition-all duration-300 shadow-md group-hover:-translate-y-1 group-active:scale-95">
				<i data-lucide="chevron-up" class="text-primary group-hover:text-primary-hover w-5 h-5" stroke-width="2.5" aria-hidden="true"></i>
			</div>
		</button>
	</div>

	<?php wp_footer(); ?>
</body>
</html>
