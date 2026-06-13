jQuery(document).ready(function($) {
    // Show message details inside modal
    $('.view-message-details').on('click', function(e) {
        e.preventDefault();
        var msgId = $(this).data('id');
        var row = $('#message-row-' + msgId);

        $.ajax({
            url: eghtesadranInbox.ajax_url,
            type: 'POST',
            data: {
                action: 'eghtesadran_admin_inbox_action',
                inbox_action: 'get_message',
                msg_id: msgId,
                nonce: eghtesadranInbox.nonce
            },
            success: function(response) {
                if (response.success) {
                    var msg = response.data.message;
                    $('#modal-sender-name').text(msg.name);
                    $('#modal-sender-email').html('<a href="mailto:' + msg.email + '">' + msg.email + '</a>');
                    $('#modal-sender-phone').text(msg.phone);
                    $('#modal-message-date').text(msg.created_at);
                    $('#modal-message-body').text(msg.message);

                    // Open thickbox modal
                    tb_show(msg.name, '#TB_inline?inlineId=eghtesadran-message-modal&width=600&height=400', null);

                    // Update row UI to read status
                    if (row.hasClass('unread-row')) {
                        row.removeClass('unread-row').css({
                            'font-weight': 'normal',
                            'background-color': ''
                        });
                        row.find('.awaiting-mod').remove();
                        
                        // Change the action link to mark unread
                        var actionCell = row.find('.column-name');
                        var markSpan = actionCell.find('.mark-read');
                        markSpan.removeClass('mark-read').addClass('mark-unread')
                            .find('a').removeClass('change-status')
                            .addClass('change-status').data('action', 'mark_unread')
                            .text('علامت‌گذاری به عنوان خوانده نشده');

                        // Update admin menu badge immediately
                        updateMenuBadgeCount();
                    }
                } else {
                    alert(response.data.message || 'خطایی رخ داده است.');
                }
            }
        });
    });

    // Handle Mark Read / Unread actions
    $(document).on('click', '.change-status', function(e) {
        e.preventDefault();
        var link = $(this);
        var msgId = link.data('id');
        var inboxAction = link.data('action');
        var row = $('#message-row-' + msgId);

        $.ajax({
            url: eghtesadranInbox.ajax_url,
            type: 'POST',
            data: {
                action: 'eghtesadran_admin_inbox_action',
                inbox_action: inboxAction,
                msg_id: msgId,
                nonce: eghtesadranInbox.nonce
            },
            success: function(response) {
                if (response.success) {
                    if (inboxAction === 'mark_read') {
                        row.removeClass('unread-row').css({
                            'font-weight': 'normal',
                            'background-color': ''
                        });
                        row.find('.awaiting-mod').remove();
                        link.parent().removeClass('mark-read').addClass('mark-unread');
                        link.data('action', 'mark_unread').text('علامت‌گذاری به عنوان خوانده نشده');
                    } else {
                        row.addClass('unread-row').css({
                            'font-weight': 'bold',
                            'background-color': '#f0f6fc'
                        });
                        if (row.find('.awaiting-mod').length === 0) {
                            row.find('.column-name strong').after('<span class="awaiting-mod" style="margin-right: 5px; background-color: #d63638; color: #fff; padding: 2px 6px; border-radius: 4px; font-size: 10px;">جدید</span>');
                        }
                        link.parent().removeClass('mark-unread').addClass('mark-read');
                        link.data('action', 'mark_read').text('علامت‌گذاری به عنوان خوانده شده');
                    }
                    updateMenuBadgeCount();
                } else {
                    alert(response.data.message || 'خطایی رخ داده است.');
                }
            }
        });
    });

    // Handle Delete Message action
    $(document).on('click', '.delete-message', function(e) {
        e.preventDefault();
        if (!confirm('آیا از حذف این پیام اطمینان دارید؟')) {
            return;
        }

        var link = $(this);
        var msgId = link.data('id');
        var row = $('#message-row-' + msgId);

        $.ajax({
            url: eghtesadranInbox.ajax_url,
            type: 'POST',
            data: {
                action: 'eghtesadran_admin_inbox_action',
                inbox_action: 'delete',
                msg_id: msgId,
                nonce: eghtesadranInbox.nonce
            },
            success: function(response) {
                if (response.success) {
                    row.fadeOut(300, function() {
                        row.remove();
                        updateMenuBadgeCount();
                    });
                } else {
                    alert(response.data.message || 'خطایی رخ داده است.');
                }
            }
        });
    });

    // Function to retrieve latest count and update the admin menu badge instantly
    function updateMenuBadgeCount() {
        $.ajax({
            url: eghtesadranInbox.ajax_url,
            type: 'POST',
            data: {
                action: 'eghtesadran_get_unread_count'
            },
            success: function(response) {
                if (response.success && typeof response.data.unread_count !== 'undefined') {
                    var count = response.data.unread_count;
                    var menuLi = $('#toplevel_page_eghtesadran-inbox');
                    var badgeSpan = menuLi.find('.update-plugins');
                    
                    if (count > 0) {
                        if (badgeSpan.length > 0) {
                            badgeSpan.removeClass(function(index, className) {
                                return (className.match(/(^|\s)count-\S+/g) || []).join(' ');
                            }).addClass('count-' + count);
                            badgeSpan.find('.plugin-count').text(count);
                        } else {
                            var titleA = menuLi.find('a.toplevel_page_eghtesadran-inbox');
                            var newBadge = ' <span class="update-plugins count-' + count + '"><span class="plugin-count">' + count + '</span></span>';
                            titleA.append(newBadge);
                        }
                    } else {
                        badgeSpan.remove();
                    }
                }
            }
        });
    }
});
