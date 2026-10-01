jQuery(document).ready(function($) {
    var requestInFlight = false;

    // Function to poll the server for the unread message count.
    function checkUnreadMessagesCount() {
        // A slow response must not accumulate more requests from the same tab.
        if (requestInFlight) {
            return;
        }

        requestInFlight = true;
        $.ajax({
            url: eghtesadranInboxAdmin.ajax_url,
            type: 'POST',
            data: {
                action: 'eghtesadran_get_unread_count'
            },
            complete: function() {
                requestInFlight = false;
            },
            success: function(response) {
                if (response.success && typeof response.data.unread_count !== 'undefined') {
                    var count = response.data.unread_count;
                    // Find the menu badge elements (both the main one and submenus if any)
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

    // Run check every 30 seconds
    setInterval(checkUnreadMessagesCount, 30000);
});
