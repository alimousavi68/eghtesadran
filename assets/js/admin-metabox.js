jQuery(document).ready(function($) {
    
    // Main Content Type Logic
    var $contentType = $('#ns_content_type');
    var $sections = $('.ns-conditional-section');

    function updateFields() {
        var selectedType = $contentType.val();
        
        // Hide all conditional sections first
        $sections.hide();
        
        // Show the relevant section
        $('.ns-conditional-section[data-show-if="' + selectedType + '"]').show();
    }

    // Video Source Type Logic
    var $videoSourceRadios = $('input[name="_news_video_source_type"]');
    var $videoSubSections = $('.ns-sub-conditional');

    function updateVideoFields() {
        var selectedSource = $('input[name="_news_video_source_type"]:checked').val();
        
        $videoSubSections.hide();
        $('.ns-sub-conditional[data-show-sub-if="' + selectedSource + '"]').show();
    }

    // Initialize
    if ($contentType.length) {
        updateFields();
        $contentType.on('change', updateFields);
    }

    if ($videoSourceRadios.length) {
        updateVideoFields();
        $videoSourceRadios.on('change', updateVideoFields);
    }

    // Media Uploader Logic (Audio)
    var audioFrame;
    var $uploadAudioBtn = $('#ns_upload_audio_btn');
    var $removeAudioBtn = $('#ns_remove_audio_btn');
    var $audioUrlInput = $('#ns_audio_file_url');
    var $audioIdInput = $('#ns_audio_file_id');

    function toggleRemoveAudioButton() {
        if ($audioIdInput.val()) {
            $removeAudioBtn.removeClass('hidden');
            $uploadAudioBtn.text('تغییر فایل صوتی');
        } else {
            $removeAudioBtn.addClass('hidden');
            $uploadAudioBtn.text('انتخاب / آپلود فایل صوتی');
        }
    }
    toggleRemoveAudioButton();

    $uploadAudioBtn.on('click', function(e) {
        e.preventDefault();

        if (audioFrame) {
            audioFrame.open();
            return;
        }

        audioFrame = wp.media({
            title: 'انتخاب فایل صوتی',
            button: {
                text: 'استفاده از این صوت'
            },
            library: {
                type: 'audio' // Limit to audio
            },
            multiple: false
        });

        audioFrame.on('select', function() {
            var attachment = audioFrame.state().get('selection').first().toJSON();
            
            if (attachment.filesizeInBytes > 52428800) {
                alert('حجم فایل انتخاب شده بیشتر از ۵۰ مگابایت است.');
                return;
            }

            $audioUrlInput.val(attachment.url);
            $audioIdInput.val(attachment.id);
            toggleRemoveAudioButton();
        });

        audioFrame.open();
    });

    $removeAudioBtn.on('click', function(e) {
        e.preventDefault();
        $audioUrlInput.val('');
        $audioIdInput.val('');
        toggleRemoveAudioButton();
    });

    // Gallery Logic
    var galleryFrame;
    var $galleryBtn = $('#ns_add_gallery_btn');
    var $galleryInput = $('#ns_gallery_images');
    var $galleryPreview = $('#ns_gallery_preview');

    $galleryBtn.on('click', function(e) {
        e.preventDefault();

        // If the media frame already exists, reopen it.
        if (galleryFrame) {
            galleryFrame.open();
            return;
        }

        // Create a new media frame
        galleryFrame = wp.media({
            title: 'انتخاب تصاویر گالری',
            button: {
                text: 'افزودن به گالری'
            },
            library: {
                type: 'image'
            },
            multiple: true
        });

        // Pre-select existing images
        galleryFrame.on('open', function() {
            var selection = galleryFrame.state().get('selection');
            var ids = $galleryInput.val().split(',');
            ids.forEach(function(id) {
                if(id) {
                    var attachment = wp.media.attachment(id);
                    attachment.fetch();
                    selection.add( attachment ? [ attachment ] : [] );
                }
            });
        });

        // When images are selected...
        galleryFrame.on('select', function() {
            var selection = galleryFrame.state().get('selection');
            var ids = [];
            $galleryPreview.empty();

            selection.map(function(attachment) {
                attachment = attachment.toJSON();
                ids.push(attachment.id);
                
                // Add to preview
                var imgUrl = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                var item = $('<div class="ns-gallery-item" data-id="' + attachment.id + '" style="position: relative; width: 80px; height: 80px; display: inline-block; margin-left: 10px;">' +
                             '<img src="' + imgUrl + '" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">' +
                             '<span class="remove-image" style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; text-align: center; line-height: 16px; cursor: pointer; font-size: 12px;">×</span>' +
                             '</div>');
                $galleryPreview.append(item);
            });

            $galleryInput.val(ids.join(','));
        });

        galleryFrame.open();
    });

    // Remove Image Logic
    $galleryPreview.on('click', '.remove-image', function() {
        var item = $(this).parent();
        var idToRemove = item.data('id');
        var currentIds = $galleryInput.val().split(',');
        
        // Remove id
        currentIds = currentIds.filter(function(id) {
            return id != idToRemove;
        });
        
        $galleryInput.val(currentIds.join(','));
        item.remove();
    });

});
