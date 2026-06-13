<?php
/**
 * Contact Form and Admin Inbox features.
 *
 * @package Eghtesadran
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Create custom database table for contact messages.
 */
function eghtesadran_create_message_table() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'eghtesadran_messages';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		name varchar(100) NOT NULL,
		email varchar(100) NOT NULL,
		phone varchar(20) NOT NULL,
		message text NOT NULL,
		status varchar(20) DEFAULT 'unread' NOT NULL,
		created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
}
// Run table creation on theme activation and ensure it exists.
add_action( 'after_switch_theme', 'eghtesadran_create_message_table' );

// Simple check on init to ensure table exists in case of no activation hook trigger.
function eghtesadran_ensure_table_exists() {
	if ( is_admin() && get_option( 'eghtesadran_db_version' ) !== '1.0' ) {
		eghtesadran_create_message_table();
		update_option( 'eghtesadran_db_version', '1.0' );
	}
}
add_action( 'init', 'eghtesadran_ensure_table_exists' );

/**
 * Get count of unread messages.
 */
function eghtesadran_get_unread_message_count() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'eghtesadran_messages';
	// Check if table exists first to prevent errors
	if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
		return 0;
	}
	return (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE status = 'unread'" );
}

/**
 * Register Administration Menu Page for Inbox.
 */
function eghtesadran_register_inbox_menu() {
	$unread_count = eghtesadran_get_unread_message_count();
	$badge = '';
	if ( $unread_count > 0 ) {
		$badge = ' <span class="update-plugins count-' . esc_attr( $unread_count ) . '"><span class="plugin-count">' . esc_html( $unread_count ) . '</span></span>';
	}

	add_menu_page(
		__( 'صندوق پیام‌ها', 'eghtesadran' ),
		__( 'صندوق پیام‌ها', 'eghtesadran' ) . $badge,
		'manage_options',
		'eghtesadran-inbox',
		'eghtesadran_render_inbox_page',
		'dashicons-email-alt',
		25
	);
}
add_action( 'admin_menu', 'eghtesadran_register_inbox_menu' );

/**
 * AJAX Handler for getting unread count.
 */
function eghtesadran_ajax_get_unread_count() {
	wp_send_json_success( array(
		'unread_count' => eghtesadran_get_unread_message_count(),
	) );
}
add_action( 'wp_ajax_eghtesadran_get_unread_count', 'eghtesadran_ajax_get_unread_count' );

/**
 * AJAX Handler for Contact Form Submission.
 */
function eghtesadran_submit_contact_form() {
	// Verify Nonce
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'eghtesadran_contact_nonce' ) ) {
		wp_send_json_error( array( 'message' => __( 'خطای امنیتی رخ داده است. لطفا صفحه را بروزرسانی کنید.', 'eghtesadran' ) ) );
	}

	// Extract and validate inputs
	$name    = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';

	if ( empty( $name ) || empty( $email ) || empty( $phone ) || empty( $message ) ) {
		wp_send_json_error( array( 'message' => __( 'لطفا تمامی فیلدها را پر کنید.', 'eghtesadran' ) ) );
	}

	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => __( 'آدرس ایمیل وارد شده معتبر نیست.', 'eghtesadran' ) ) );
	}

	// Basic phone format check (at least 5 digits)
	if ( ! preg_match( '/^[0-9+\-\s()]{5,20}$/', $phone ) ) {
		wp_send_json_error( array( 'message' => __( 'شماره تماس وارد شده معتبر نیست.', 'eghtesadran' ) ) );
	}

	// Insert into Database
	global $wpdb;
	$table_name = $wpdb->prefix . 'eghtesadran_messages';
	$result = $wpdb->insert(
		$table_name,
		array(
			'name'       => $name,
			'email'      => $email,
			'phone'      => $phone,
			'message'    => $message,
			'status'     => 'unread',
			'created_at' => current_time( 'mysql' ),
		),
		array( '%s', '%s', '%s', '%s', '%s', '%s' )
	);

	if ( false === $result ) {
		wp_send_json_error( array( 'message' => __( 'خطایی در ثبت پیام رخ داد. لطفا مجددا تلاش کنید.', 'eghtesadran' ) ) );
	}

	// Send Email notification to site administrator
	$admin_email = get_option( 'admin_email' );
	$subject     = sprintf( __( 'پیام جدید از فرم تماس با ما: %s', 'eghtesadran' ), $name );
	$email_body  = "یک پیام جدید از طریق فرم تماس با ما ارسال شده است:\n\n";
	$email_body .= "نام: $name\n";
	$email_body .= "ایمیل: $email\n";
	$email_body .= "شماره تماس: $phone\n\n";
	$email_body .= "متن پیام:\n$message\n\n";
	$email_body .= "--- \nاین ایمیل به صورت خودکار از سایت ارسال شده است.";

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	
	@wp_mail( $admin_email, $subject, $email_body, $headers );

	wp_send_json_success( array( 'message' => __( 'پیام شما با موفقیت ارسال شد. به زودی با شما تماس خواهیم گرفت.', 'eghtesadran' ) ) );
}
add_action( 'wp_ajax_eghtesadran_submit_contact', 'eghtesadran_submit_contact_form' );
add_action( 'wp_ajax_nopriv_eghtesadran_submit_contact', 'eghtesadran_submit_contact_form' );

/**
 * AJAX Handler for Admin Actions.
 */
function eghtesadran_ajax_admin_inbox_action() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'شما اجازه دسترسی به این بخش را ندارید.', 'eghtesadran' ) ) );
	}

	$action  = isset( $_POST['inbox_action'] ) ? sanitize_text_field( $_POST['inbox_action'] ) : '';
	$msg_id  = isset( $_POST['msg_id'] ) ? (int) $_POST['msg_id'] : 0;
	$nonce   = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';

	if ( ! wp_verify_nonce( $nonce, 'eghtesadran_inbox_admin_nonce' ) ) {
		wp_send_json_error( array( 'message' => __( 'خطای امنیتی رخ داده است.', 'eghtesadran' ) ) );
	}

	global $wpdb;
	$table_name = $wpdb->prefix . 'eghtesadran_messages';

	if ( 'delete' === $action ) {
		$wpdb->delete( $table_name, array( 'id' => $msg_id ), array( '%d' ) );
		wp_send_json_success( array( 'message' => __( 'پیام با موفقیت حذف شد.', 'eghtesadran' ) ) );
	} elseif ( 'mark_read' === $action ) {
		$wpdb->update( $table_name, array( 'status' => 'read' ), array( 'id' => $msg_id ), array( '%s' ), array( '%d' ) );
		wp_send_json_success( array( 'message' => __( 'پیام به عنوان خوانده شده علامت‌گذاری شد.', 'eghtesadran' ) ) );
	} elseif ( 'mark_unread' === $action ) {
		$wpdb->update( $table_name, array( 'status' => 'unread' ), array( 'id' => $msg_id ), array( '%s' ), array( '%d' ) );
		wp_send_json_success( array( 'message' => __( 'پیام به عنوان خوانده نشده علامت‌گذاری شد.', 'eghtesadran' ) ) );
	} elseif ( 'get_message' === $action ) {
		$message = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $msg_id ), ARRAY_A );
		if ( $message ) {
			// Mark as read immediately when viewed
			if ( 'unread' === $message['status'] ) {
				$wpdb->update( $table_name, array( 'status' => 'read' ), array( 'id' => $msg_id ), array( '%s' ), array( '%d' ) );
			}
			wp_send_json_success( array( 'message' => $message ) );
		} else {
			wp_send_json_error( array( 'message' => __( 'پیام مورد نظر یافت نشد.', 'eghtesadran' ) ) );
		}
	}

	wp_send_json_error( array( 'message' => __( 'عملیات نامعتبر است.', 'eghtesadran' ) ) );
}
add_action( 'wp_ajax_eghtesadran_admin_inbox_action', 'eghtesadran_ajax_admin_inbox_action' );

/**
 * Enqueue scripts and styles for the Admin page.
 */
function eghtesadran_admin_inbox_scripts( $hook ) {
	if ( 'toplevel_page_eghtesadran-inbox' !== $hook ) {
		// Enqueue the live-updating script on all pages to keep the badge updated, 
		// but only if user has access.
		if ( current_user_can( 'manage_options' ) ) {
			wp_enqueue_script(
				'eghtesadran-admin-badge',
				get_template_directory_uri() . '/assets/js/contact-admin-badge.js',
				array( 'jquery' ),
				'1.0',
				true
			);
			wp_localize_script( 'eghtesadran-admin-badge', 'eghtesadranInboxAdmin', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'eghtesadran_inbox_badge_nonce' ),
			) );
		}
		return;
	}

	wp_enqueue_style( 'thickbox' );
	wp_enqueue_script( 'thickbox' );

	wp_enqueue_script(
		'eghtesadran-admin-inbox',
		get_template_directory_uri() . '/assets/js/contact-admin.js',
		array( 'jquery' ),
		'1.0',
		true
	);

	wp_localize_script( 'eghtesadran-admin-inbox', 'eghtesadranInbox', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'eghtesadran_inbox_admin_nonce' ),
	) );
}
add_action( 'admin_enqueue_scripts', 'eghtesadran_admin_inbox_scripts' );

/**
 * Render the Admin Inbox Page.
 */
function eghtesadran_render_inbox_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	global $wpdb;
	$table_name = $wpdb->prefix . 'eghtesadran_messages';

	// Auto-mark all messages as read when opening the main inbox page to satisfy the requirement
	// "با باز کردن بخش صندوق پیام یا علامتگذاری پیامها به عنوان خوانده شده، این نشانگر به روز شود و تعداد صفر را نشان دهد"
	$wpdb->query( "UPDATE $table_name SET status = 'read' WHERE status = 'unread'" );

	// Handle standard query params for filters
	$status_filter = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : 'all';
	$search_query  = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

	// Pagination parameters
	$per_page = 20;
	$paged    = isset( $_GET['paged'] ) ? max( 1, (int) $_GET['paged'] ) : 1;
	$offset   = ( $paged - 1 ) * $per_page;

	// Build Query
	$where = '1=1';
	$params = array();

	if ( 'unread' === $status_filter ) {
		$where .= " AND status = 'unread'";
	} elseif ( 'read' === $status_filter ) {
		$where .= " AND status = 'read'";
	}

	if ( ! empty( $search_query ) ) {
		$where .= " AND (name LIKE %s OR email LIKE %s OR phone LIKE %s OR message LIKE %s)";
		$like_search = '%' . $wpdb->esc_like( $search_query ) . '%';
		$params[] = $like_search;
		$params[] = $like_search;
		$params[] = $like_search;
		$params[] = $like_search;
	}

	// Total count query
	if ( empty( $params ) ) {
		$total_query = "SELECT COUNT(*) FROM $table_name WHERE $where";
		$total_items = (int) $wpdb->get_var( $total_query );
	} else {
		$total_query = $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE $where", $params );
		$total_items = (int) $wpdb->get_var( $total_query );
	}

	$total_pages = ceil( $total_items / $per_page );

	// Data query
	$query = "SELECT * FROM $table_name WHERE $where ORDER BY created_at DESC LIMIT %d OFFSET %d";
	$query_params = array_merge( $params, array( $per_page, $offset ) );
	$messages = $wpdb->get_results( $wpdb->prepare( $query, $query_params ), ARRAY_A );

	// Header counts
	$all_count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
	$read_count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE status = 'read'" );
	$unread_count_db = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE status = 'unread'" );
	?>
	<div class="wrap" style="direction: rtl;">
		<h1 class="wp-heading-inline"><?php esc_html_e( 'صندوق پیام‌ها (Inbox)', 'eghtesadran' ); ?></h1>
		<hr class="wp-header-end">

		<ul class="subsubsub">
			<li class="all"><a href="<?php echo esc_url( admin_url( 'admin.php?page=eghtesadran-inbox' ) ); ?>" class="<?php echo $status_filter === 'all' ? 'current' : ''; ?>"><?php esc_html_e( 'همه', 'eghtesadran' ); ?> <span class="count">(<?php echo $all_count; ?>)</span></a> |</li>
			<li class="unread"><a href="<?php echo esc_url( admin_url( 'admin.php?page=eghtesadran-inbox&status=unread' ) ); ?>" class="<?php echo $status_filter === 'unread' ? 'current' : ''; ?>"><?php esc_html_e( 'خوانده نشده', 'eghtesadran' ); ?> <span class="count">(<?php echo $unread_count_db; ?>)</span></a> |</li>
			<li class="read"><a href="<?php echo esc_url( admin_url( 'admin.php?page=eghtesadran-inbox&status=read' ) ); ?>" class="<?php echo $status_filter === 'read' ? 'current' : ''; ?>"><?php esc_html_e( 'خوانده شده', 'eghtesadran' ); ?> <span class="count">(<?php echo $read_count; ?>)</span></a></li>
		</ul>

		<form method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>">
			<input type="hidden" name="page" value="eghtesadran-inbox">
			<?php if ( ! empty( $status_filter ) ) : ?>
				<input type="hidden" name="status" value="<?php echo esc_attr( $status_filter ); ?>">
			<?php endif; ?>
			<p class="search-box">
				<label class="screen-reader-text" for="post-search-input"><?php esc_html_e( 'جستجوی پیام‌ها:', 'eghtesadran' ); ?></label>
				<input type="search" id="post-search-input" name="s" value="<?php echo esc_attr( $search_query ); ?>">
				<input type="submit" id="search-submit" class="button" value="<?php esc_attr_e( 'جستجو', 'eghtesadran' ); ?>">
			</p>
		</form>

		<table class="wp-list-table widefat fixed striped table-view-list messages-table" style="margin-top: 10px;">
			<thead>
				<tr>
					<th scope="col" class="manage-column column-name" style="width: 20%;"><?php esc_html_e( 'فرستنده', 'eghtesadran' ); ?></th>
					<th scope="col" class="manage-column column-contact" style="width: 25%;"><?php esc_html_e( 'اطلاعات تماس', 'eghtesadran' ); ?></th>
					<th scope="col" class="manage-column column-message" style="width: 40%;"><?php esc_html_e( 'خلاصه پیام', 'eghtesadran' ); ?></th>
					<th scope="col" class="manage-column column-date" style="width: 15%;"><?php esc_html_e( 'تاریخ ارسال', 'eghtesadran' ); ?></th>
				</tr>
			</thead>
			<tbody id="the-list">
				<?php if ( empty( $messages ) ) : ?>
					<tr class="no-items">
						<td class="colspanchange" colspan="4"><?php esc_html_e( 'پیامی یافت نشد.', 'eghtesadran' ); ?></td>
					</tr>
				<?php else : ?>
					<?php foreach ( $messages as $msg ) : ?>
						<tr id="message-row-<?php echo $msg['id']; ?>" class="<?php echo 'unread' === $msg['status'] ? 'comment-meta-bubble unread-row' : ''; ?>" style="<?php echo 'unread' === $msg['status'] ? 'font-weight: bold; background-color: #f0f6fc;' : ''; ?>">
							<td class="column-name">
								<strong><a href="#" class="view-message-details" data-id="<?php echo $msg['id']; ?>"><?php echo esc_html( $msg['name'] ); ?></a></strong>
								<?php if ( 'unread' === $msg['status'] ) : ?>
									<span class="awaiting-mod" style="margin-right: 5px; background-color: #d63638; color: #fff; padding: 2px 6px; border-radius: 4px; font-size: 10px;"><?php esc_html_e( 'جدید', 'eghtesadran' ); ?></span>
								<?php endif; ?>
								<div class="row-actions">
									<span class="view"><a href="#" class="view-message-details" data-id="<?php echo $msg['id']; ?>"><?php esc_html_e( 'مشاهده کامل', 'eghtesadran' ); ?></a> | </span>
									<?php if ( 'unread' === $msg['status'] ) : ?>
										<span class="mark-read"><a href="#" class="change-status" data-id="<?php echo $msg['id']; ?>" data-action="mark_read"><?php esc_html_e( 'علامت‌گذاری به عنوان خوانده شده', 'eghtesadran' ); ?></a> | </span>
									<?php else : ?>
										<span class="mark-unread"><a href="#" class="change-status" data-id="<?php echo $msg['id']; ?>" data-action="mark_unread"><?php esc_html_e( 'علامت‌گذاری به عنوان خوانده نشده', 'eghtesadran' ); ?></a> | </span>
									<?php endif; ?>
									<span class="delete"><a href="#" class="delete-message submitdelete" style="color: #b32d2e;" data-id="<?php echo $msg['id']; ?>"><?php esc_html_e( 'حذف', 'eghtesadran' ); ?></a></span>
								</div>
							</td>
							<td class="column-contact">
								<div><strong><?php esc_html_e( 'ایمیل:', 'eghtesadran' ); ?></strong> <?php echo esc_html( $msg['email'] ); ?></div>
								<div style="margin-top: 4px;"><strong><?php esc_html_e( 'تلفن:', 'eghtesadran' ); ?></strong> <span style="direction: ltr; display: inline-block;"><?php echo esc_html( $msg['phone'] ); ?></span></div>
							</td>
							<td class="column-message">
								<?php echo esc_html( wp_trim_words( $msg['message'], 15, '...' ) ); ?>
							</td>
							<td class="column-date">
								<?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $msg['created_at'] ) ) ); ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>

		<!-- Modal Container for viewing message details -->
		<div id="eghtesadran-message-modal" style="display:none; max-width: 600px; padding: 20px;">
			<h2 style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-top: 0;" id="modal-sender-name"></h2>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row" style="width: 120px; font-weight: bold;"><?php esc_html_e( 'ایمیل:', 'eghtesadran' ); ?></th>
						<td id="modal-sender-email"></td>
					</tr>
					<tr>
						<th scope="row" style="width: 120px; font-weight: bold;"><?php esc_html_e( 'شماره تماس:', 'eghtesadran' ); ?></th>
						<td id="modal-sender-phone" style="direction: ltr; text-align: right;"></td>
					</tr>
					<tr>
						<th scope="row" style="width: 120px; font-weight: bold;"><?php esc_html_e( 'تاریخ:', 'eghtesadran' ); ?></th>
						<td id="modal-message-date"></td>
					</tr>
					<tr>
						<th scope="row" style="width: 120px; font-weight: bold;"><?php esc_html_e( 'پیام:', 'eghtesadran' ); ?></th>
						<td>
							<div id="modal-message-body" style="background: #f6f7f7; padding: 15px; border-radius: 4px; border: 1px solid #dcdcde; white-space: pre-wrap; line-height: 1.6;"></div>
						</td>
					</tr>
				</tbody>
			</table>
			<div style="margin-top: 20px; text-align: left;">
				<button type="button" class="button button-primary" onclick="tb_remove();"><?php esc_html_e( 'بستن', 'eghtesadran' ); ?></button>
			</div>
		</div>

		<?php if ( $total_pages > 1 ) : ?>
			<div class="tablenav bottom">
				<div class="tablenav-pages">
					<span class="displaying-num"><?php printf( _n( '%s مورد', '%s مورد', $total_items, 'eghtesadran' ), number_format_i18n( $total_items ) ); ?></span>
					<span class="pagination-links">
						<?php if ( $paged > 1 ) : ?>
							<a class="prev-page button" href="<?php echo esc_url( add_query_arg( 'paged', $paged - 1 ) ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'برگه قبلی', 'eghtesadran' ); ?></span><span aria-hidden="true">‹</span></a>
						<?php endif; ?>
						<span class="paging-input">
							<span class="tablenav-paging-text">
								<?php printf( __( '%1$s از %2$s', 'eghtesadran' ), esc_html( $paged ), '<span class="total-pages">' . esc_html( $total_pages ) . '</span>' ); ?>
							</span>
						</span>
						<?php if ( $paged < $total_pages ) : ?>
							<a class="next-page button" href="<?php echo esc_url( add_query_arg( 'paged', $paged + 1 ) ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'برگه بعدی', 'eghtesadran' ); ?></span><span aria-hidden="true">›</span></a>
						<?php endif; ?>
					</span>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<?php
}
