/**
 * SourceHub Post Validation JavaScript
 * 
 * Handles client-side validation before saving/publishing posts
 */

(function($) {
    'use strict';

    var SourceHubValidation = {
        
        init: function() {
            this.bindEvents();
            this.addValidationIndicator();
        },

        bindEvents: function() {
            var self = this;

            // Don't intercept publish button - just show warnings
            // Publishing will proceed normally, validation shows as warnings after

            // Intercept save draft button clicks  
            $('#save-post').on('click', function(e) {
                // Allow drafts to save without validation
                return true;
            });

            // Real-time validation on field changes
            $(document).on('change', 'input[name="sourcehub_selected_spokes[]"], #set-post-thumbnail, select[name="sourcehub_post_template"], input[name="post_category[]"], #categorychecklist input', function() {
                console.log('Field changed, updating validation...');
                self.performRealTimeValidation();
            });

            // Validation when featured image is set/removed
            $(document).on('click', '#set-post-thumbnail, #remove-post-thumbnail', function() {
                console.log('Featured image button clicked, updating validation...');
                setTimeout(function() {
                    self.performRealTimeValidation();
                }, 500);
            });

            // Watch for changes to the _thumbnail_id field (WordPress updates this when featured image is set)
            if ($('#_thumbnail_id').length) {
                // Use MutationObserver to watch for attribute changes
                var thumbnailInput = document.getElementById('_thumbnail_id');
                if (thumbnailInput) {
                    var observer = new MutationObserver(function(mutations) {
                        console.log('Featured image _thumbnail_id changed, updating validation...');
                        self.performRealTimeValidation();
                    });
                    observer.observe(thumbnailInput, { attributes: true, attributeFilter: ['value'] });
                    
                    // Also listen for direct value changes
                    $(thumbnailInput).on('change', function() {
                        console.log('Featured image _thumbnail_id change event, updating validation...');
                        self.performRealTimeValidation();
                    });
                }
            }
            
            // Fallback: Poll for _thumbnail_id changes every 2 seconds
            setInterval(function() {
                var currentThumbnailId = $('#_thumbnail_id').val();
                if (self.lastThumbnailId !== currentThumbnailId) {
                    console.log('Featured image changed (polling detected), updating validation...');
                    self.lastThumbnailId = currentThumbnailId;
                    self.performRealTimeValidation();
                }
            }, 2000);

            // Additional listeners for better coverage
            $(document).on('change', '#categorydiv input[type="checkbox"]', function() {
                console.log('Category checkbox changed, updating validation...');
                self.performRealTimeValidation();
            });

            // Listen for category changes in the category metabox
            $(document).on('click', '#categorydiv .selectit input', function() {
                console.log('Category selected, updating validation...');
                setTimeout(function() {
                    self.performRealTimeValidation();
                }, 100);
            });

            // Listen for spoke selection changes
            $(document).on('change', '.sourcehub-spoke-checkbox', function() {
                console.log('Spoke selection changed, updating validation...');
                self.performRealTimeValidation();
            });
        },

        addValidationIndicator: function() {
            // Add validation status indicator to the publish box
            var $publishBox = $('#submitdiv');
            if ($publishBox.length) {
                var indicator = '<div id="sourcehub-validation-status" style="margin: 10px 0; padding: 8px; border-radius: 4px; font-size: 12px;">' +
                              '<strong>SourceHub Status:</strong> <span id="validation-message">Checking...</span>' +
                              '</div>';
                $publishBox.find('#major-publishing-actions').before(indicator);
                
                // Perform initial validation
                this.performRealTimeValidation();
            }
        },

        performRealTimeValidation: function() {
            var self = this;
            var $status = $('#sourcehub-validation-status');
            var $message = $('#validation-message');

            if (!$status.length) return;

            $message.text('Checking...');
            $status.removeClass('validation-success validation-warning').css('background', '#f0f0f1');

            // Collect current form data
            var formData = {
                // Collect spoke selections
                sourcehub_selected_spokes: [],
                // Check if featured image is set using WordPress _thumbnail_id field
                // WordPress sets this to -1 when no image, or a positive number when set
                has_featured_image: ($('#_thumbnail_id').val() && $('#_thumbnail_id').val() !== '-1') ? 'true' : 'false',
                // Collect selected categories
                post_categories: []
            };
            
            console.log('Featured image detection: thumbnail_id =', $('#_thumbnail_id').val(), 'has_featured_image =', formData.has_featured_image);

            // Get spoke selections
            $('input[name="sourcehub_selected_spokes[]"]:checked, .sourcehub-spoke-checkbox:checked').each(function() {
                formData.sourcehub_selected_spokes.push($(this).val());
            });

            // Get category selections
            $('#categorychecklist input:checked').each(function() {
                formData.post_categories.push($(this).val());
            });

            console.log('Sending form data:', formData);

            $.ajax({
                url: sourcehub_validation.ajax_url,
                type: 'POST',
                data: {
                    action: 'sourcehub_validate_post',
                    post_id: sourcehub_validation.post_id,
                    nonce: sourcehub_validation.nonce,
                    form_data: formData
                },
                success: function(response) {
                    console.log('Validation response:', response);
                    if (response.success) {
                        if (response.data.valid) {
                            $message.text('Ready for optimal syndication');
                            $status.addClass('validation-success').css('background', '#d4edda');
                        } else {
                            var warningText = 'Warning: You should ' + response.data.errors.join(', ') + ' for optimal syndication';
                            $message.text(warningText);
                            $status.addClass('validation-warning').css('background', '#fff3cd');
                        }
                    }
                },
                error: function() {
                    $message.text('Validation check failed');
                    $status.css('background', '#fff3cd');
                }
            });
        },

        // Removed blocking validation methods - now using warning-only approach
    };

    // Initialize when document is ready
    $(document).ready(function() {
        // Only initialize on post edit pages
        if ($('body').hasClass('post-php') || $('body').hasClass('post-new-php')) {
            // Store original publish button text
            $('#publish').data('original-text', $('#publish').val());
            
            SourceHubValidation.init();
        }
    });

})(jQuery);
