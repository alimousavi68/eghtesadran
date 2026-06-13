<?php
/**
 * Template Name: فرم تماس با ما
 *
 * @package Eghtesadran
 */

get_header();
?>

<!-- PAGE LAYOUT (Centered Content, No Sidebar) -->
<section class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
	<div class="lg:col-span-10 lg:col-start-2 space-y-6">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-5 md:p-8 mb-8 article-container relative' ); ?>>
				<!-- Breadcrumbs -->
				<nav class="flex items-center gap-2 text-xs font-bold text-slate-400 dark:text-slate-500 mb-6 pb-4 border-b border-slate-100 dark:border-slate-700 print:hidden">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-primary transition-colors flex items-center gap-1">
						<i data-lucide="home" class="w-3.5 h-3.5" aria-hidden="true"></i> <?php esc_html_e( 'صفحه اصلی', 'eghtesadran' ); ?>
					</a>
					<span>/</span>
					<span class="text-slate-600 dark:text-slate-300"><?php the_title(); ?></span>
				</nav>

				<!-- Article Header -->
				<header class="mb-8">
					<h1 class="text-2xl md:text-3xl lg:text-4xl font-black text-slate-900 dark:text-white leading-[1.8] md:leading-loose">
						<?php the_title(); ?>
					</h1>
				</header>

				<!-- Featured Image -->
				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="mb-8 rounded-2xl overflow-hidden shadow-sm">
						<?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-auto object-cover aspect-[16/9]' ) ); ?>
					</figure>
				<?php endif; ?>

				<!-- Article Body -->
				<div class="article-content text-slate-800 dark:text-slate-200 text-sm md:text-base leading-loose text-justify space-y-6">
					<?php the_content(); ?>
				</div>

				<!-- Contact Form Section -->
				<div class="mt-12 pt-8 border-t border-slate-100 dark:border-slate-700">
					<h2 class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white mb-6">
						<?php esc_html_e( 'ارسال پیام به ما', 'eghtesadran' ); ?>
					</h2>
					<form id="eghtesadran-contact-form" class="space-y-6">
						<?php wp_nonce_field( 'eghtesadran_contact_nonce', 'contact_nonce' ); ?>
						<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
							<div>
								<label for="contact-name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2"><?php esc_html_e( 'نام و نام خانوادگی', 'eghtesadran' ); ?> <span class="text-red-500">*</span></label>
								<input type="text" id="contact-name" name="name" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors text-sm" placeholder="مثال: علی رضایی">
							</div>
							<div>
								<label for="contact-email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2"><?php esc_html_e( 'آدرس ایمیل', 'eghtesadran' ); ?> <span class="text-red-500">*</span></label>
								<input type="email" id="contact-email" name="email" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors text-sm" placeholder="example@email.com">
							</div>
						</div>

						<div>
							<label for="contact-phone" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2"><?php esc_html_e( 'شماره تماس', 'eghtesadran' ); ?> <span class="text-red-500">*</span></label>
							<input type="tel" id="contact-phone" name="phone" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors text-sm" placeholder="۰۹۱۲۳۴۵۶۷۸۹">
						</div>

						<div>
							<label for="contact-message" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2"><?php esc_html_e( 'متن پیام', 'eghtesadran' ); ?> <span class="text-red-500">*</span></label>
							<textarea id="contact-message" name="message" rows="6" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors text-sm resize-none" placeholder="پیام خود را در این بخش بنویسید..."></textarea>
						</div>

						<div id="contact-form-message" class="hidden p-4 rounded-xl text-sm font-medium"></div>

						<div>
							<button type="submit" id="contact-submit-btn" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-primary hover:bg-primary-hover text-white font-bold transition-all transform hover:-translate-y-0.5 active:translate-y-0 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/50 text-sm">
								<span id="btn-text"><?php esc_html_e( 'ارسال پیام', 'eghtesadran' ); ?></span>
								<span id="btn-loader" class="hidden mr-2">
									<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
										<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
										<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
									</svg>
								</span>
							</button>
						</div>
					</form>
				</div>
			</article>
			<?php
		endwhile;
		?>
	</div>
</section>

<!-- Client Side Validation and AJAX Submission Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
	const form = document.getElementById('eghtesadran-contact-form');
	const msgDiv = document.getElementById('contact-form-message');
	const submitBtn = document.getElementById('contact-submit-btn');
	const btnText = document.getElementById('btn-text');
	const btnLoader = document.getElementById('btn-loader');

	form.addEventListener('submit', function(e) {
		e.preventDefault();

		// Basic validation
		const name = document.getElementById('contact-name').value.trim();
		const email = document.getElementById('contact-email').value.trim();
		const phone = document.getElementById('contact-phone').value.trim();
		const message = document.getElementById('contact-message').value.trim();

		if (!name || !email || !phone || !message) {
			showMessage('لطفا تمامی فیلدها را به درستی پر کنید.', 'error');
			return;
		}

		// Email regex check
		const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		if (!emailPattern.test(email)) {
			showMessage('نشانی ایمیل وارد شده نامعتبر است.', 'error');
			return;
		}

		// Phone regex check (allows digits, spaces, plus, brackets, and hyphens)
		const phonePattern = /^[0-9+\-\s()]{5,20}$/;
		if (!phonePattern.test(phone)) {
			showMessage('شماره تماس وارد شده نامعتبر است.', 'error');
			return;
		}

		// Prepare data
		const formData = new FormData(form);
		formData.append('action', 'eghtesadran_submit_contact');
		formData.append('nonce', document.getElementById('contact_nonce').value);

		// Show Loader
		submitBtn.disabled = true;
		btnText.textContent = 'در حال ارسال...';
		btnLoader.classList.remove('hidden');
		msgDiv.classList.add('hidden');

		// AJAX Request
		fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
			method: 'POST',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			submitBtn.disabled = false;
			btnText.textContent = 'ارسال پیام';
			btnLoader.classList.add('hidden');

			if (data.success) {
				showMessage(data.data.message, 'success');
				form.reset();
			} else {
				showMessage(data.data.message || 'خطایی رخ داده است. لطفا مجددا تلاش کنید.', 'error');
			}
		})
		.catch(error => {
			submitBtn.disabled = false;
			btnText.textContent = 'ارسال پیام';
			btnLoader.classList.add('hidden');
			showMessage('خطایی در ارتباط با سرور رخ داده است. لطفا دوباره تلاش کنید.', 'error');
		});
	});

	function showMessage(text, type) {
		msgDiv.textContent = text;
		msgDiv.classList.remove('hidden', 'bg-red-50', 'text-red-700', 'border-red-200', 'bg-green-50', 'text-green-700', 'border-green-200', 'dark:bg-red-900/30', 'dark:text-red-300', 'dark:bg-green-900/30', 'dark:text-green-300');
		msgDiv.classList.add('border');

		if (type === 'error') {
			msgDiv.classList.add('bg-red-50', 'text-red-700', 'border-red-200', 'dark:bg-red-900/30', 'dark:text-red-300', 'dark:border-red-800');
		} else {
			msgDiv.classList.add('bg-green-50', 'text-green-700', 'border-green-200', 'dark:bg-green-900/30', 'dark:text-green-300', 'dark:border-green-800');
		}
	}
});
</script>

<?php
get_footer();
