(function() {
    'use strict';

    // Debug logging
    console.log('SourceHub TinyMCE plugin loading... VERSION 2.0 - UPDATED!');

    // Register the TinyMCE plugin
    tinymce.PluginManager.add('sourcehub_shortcodes', function(editor, url) {
        console.log('SourceHub TinyMCE plugin initialized for editor:', editor.id);
        
        // Add Smart Link button
        editor.addButton('sourcehub_smart_link', {
            title: 'Insert Smart Link',
            text: '🔗',
            onclick: function() {
                console.log('Smart Link button clicked');
                openSmartLinkModal();
            }
        });

        // Add Custom Smart Link button
        editor.addButton('sourcehub_custom_smart_link', {
            title: 'Insert Custom Smart Link',
            text: '🌐',
            onclick: function() {
                console.log('Custom Smart Link button clicked');
                openCustomSmartLinkModal();
            }
        });

        // Smart Link Modal
        function openSmartLinkModal() {
            var dialogInstance = editor.windowManager.open({
                title: 'Insert Smart Link',
                width: 600,
                height: 350,
                body: [
                    {
                        type: 'textbox',
                        name: 'linkText',
                        label: 'Link Text:',
                        placeholder: 'e.g., Contact Us'
                    },
                    {
                        type: 'textbox',
                        name: 'path',
                        label: 'Path:',
                        placeholder: 'e.g., /contact'
                    },
                    {
                        type: 'container',
                        html: '<div style="margin: 10px 0;"><label style="font-weight: bold; display: block; margin-bottom: 5px;">Preview:</label><div id="smart-link-preview" style="background: #f9f9f9; padding: 10px; border-radius: 4px; font-family: Consolas, Monaco, monospace; font-size: 12px; border: 1px solid #ddd; word-wrap: break-word; min-height: 40px;">Enter link text and path to see preview...</div></div>'
                    }
                ],
                onPostRender: function() {
                    var dialog = this;
                    
                    // Update preview when fields change
                    function updatePreview() {
                        var linkText = dialog.find('#linkText')[0].value();
                        var path = dialog.find('#path')[0].value();
                        var preview = '';
                        
                        if (linkText && path) {
                            preview = '[smart-link path="' + path + '"]' + linkText + '[/smart-link]';
                        } else {
                            preview = 'Enter link text and path to see preview...';
                        }
                        
                        document.getElementById('smart-link-preview').innerHTML = preview;
                    }
                    
                    // Bind change events
                    dialog.find('#linkText')[0].on('change keyup', updatePreview);
                    dialog.find('#path')[0].on('change keyup', updatePreview);
                    
                    // Initial preview
                    updatePreview();
                },
                buttons: [
                    {
                        text: 'Copy Shortcode',
                        onclick: function() {
                            console.log('Copy Shortcode button clicked');
                            var formData = dialogInstance.toJSON();
                            var linkText = formData.linkText || '';
                            var path = formData.path || '';
                            
                            if (!linkText || !path) {
                                alert('Please fill in both link text and path.');
                                return;
                            }
                            
                            var shortcode = '[smart-link path="' + path + '"]' + linkText + '[/smart-link]';
                            copyToClipboard(shortcode);
                            alert('Shortcode copied to clipboard!');
                        }
                    },
                    {
                        text: 'Insert',
                        onclick: function() {
                            console.log('Insert button clicked');
                            var formData = dialogInstance.toJSON();
                            var linkText = formData.linkText || '';
                            var path = formData.path || '';
                            
                            if (!linkText || !path) {
                                alert('Please fill in both link text and path.');
                                return;
                            }
                            
                            var shortcode = '[smart-link path="' + path + '"]' + linkText + '[/smart-link]';
                            editor.insertContent(shortcode);
                            dialogInstance.close();
                        }
                    },
                    {
                        text: 'Cancel',
                        onclick: function() {
                            console.log('Smart Link Cancel button clicked');
                            dialogInstance.close();
                        }
                    }
                ]
            });
        }

        // Custom Smart Link Modal
        function openCustomSmartLinkModal() {
            // First, get spoke connections via AJAX
            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_get_spoke_connections',
                    nonce: sourcehub_admin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        showCustomSmartLinkModal(response.data);
                    } else {
                        alert('Failed to load spoke connections: ' + response.data.message);
                    }
                },
                error: function() {
                    alert('Failed to load spoke connections. Please try again.');
                }
            });
        }

        // Show the custom smart link modal with spoke connections
        function showCustomSmartLinkModal(spokeConnections) {
            // Build body items for the modal
            var bodyItems = [
                {
                    type: 'textbox',
                    name: 'linkText',
                    label: 'Link Text:',
                    value: '',
                    multiline: false,
                    size: 60
                }
            ];
            
            // Add URL fields for each spoke connection
            spokeConnections.forEach(function(connection) {
                bodyItems.push({
                    type: 'textbox',
                    name: 'url_' + connection.id,
                    label: connection.name + ' URL:',
                    value: '',
                    multiline: false,
                    size: 60
                });
            });
            
            // Add preview container with update button
            bodyItems.push({
                type: 'container',
                html: '<div style="margin: 10px 0;"><label style="font-weight: bold; display: block; margin-bottom: 5px;">Preview:</label><div id="custom-smart-link-preview" style="background: #f9f9f9; padding: 10px; border-radius: 4px; font-family: Consolas, Monaco, monospace; font-size: 12px; border: 1px solid #ddd; word-wrap: break-word; min-height: 40px;">Fill in the form to see preview...</div><button type="button" id="update-preview-btn" style="margin-top: 5px; padding: 5px 10px; background: #0073aa; color: white; border: none; border-radius: 3px; cursor: pointer;">Update Preview</button></div>'
            });

            var dialogInstance = editor.windowManager.open({
                title: 'Insert Custom Smart Link',
                width: 800,
                height: Math.min(700, 400 + (spokeConnections.length * 70)),
                body: bodyItems,
                onPostRender: function() {
                    var dialog = this;
                    
                    // Set initial preview message and bind update button
                    setTimeout(function() {
                        var previewElement = document.getElementById('custom-smart-link-preview');
                        if (previewElement) {
                            previewElement.innerHTML = 'Fill in the form and click "Update Preview" to see shortcode...';
                        }
                        
                        // Bind the Update Preview button
                        var updateBtn = document.getElementById('update-preview-btn');
                        if (updateBtn) {
                            updateBtn.addEventListener('click', function() {
                                updatePreviewDisplay();
                            });
                        }
                        
                        // Try to add automatic change listeners (may not work in all TinyMCE versions)
                        try {
                            var linkTextControl = dialog.find('textbox[name="linkText"]')[0];
                            if (linkTextControl) {
                                linkTextControl.on('change keyup', function() {
                                    updatePreviewDisplay();
                                });
                            }
                            
                            spokeConnections.forEach(function(connection) {
                                var urlControl = dialog.find('textbox[name="url_' + connection.id + '"]')[0];
                                if (urlControl) {
                                    urlControl.on('change keyup', function() {
                                        updatePreviewDisplay();
                                    });
                                }
                            });
                        } catch (e) {
                            // Automatic listeners failed, user can use Update Preview button
                        }
                    }, 100);
                    
                    function updatePreviewDisplay() {
                        setTimeout(function() {
                            // Try to get field values directly from controls
                            var linkText = '';
                            var urls = {};
                            var hasUrls = false;
                            
                            // Use toJSON as primary method since it's more reliable
                            var formData = dialog.toJSON();
                            
                            linkText = (formData.linkText || '').trim();
                            
                            spokeConnections.forEach(function(connection) {
                                var fieldName = 'url_' + connection.id;
                                var url = (formData[fieldName] || '').trim();
                                if (url && url.length > 0) { // Only add non-empty URLs
                                    urls[connection.name] = url;
                                    hasUrls = true;
                                }
                            });
                            
                            var preview = '';
                            if (linkText && hasUrls) {
                                preview = '[custom-smart-link urls=\'' + JSON.stringify(urls) + '\']' + linkText + '[/custom-smart-link]';
                            } else if (linkText || hasUrls) {
                                preview = 'Add both link text and at least one URL to see full preview...';
                            } else {
                                preview = 'Fill in the form to see preview...';
                            }
                            
                            var previewElement = document.getElementById('custom-smart-link-preview');
                            if (previewElement) {
                                previewElement.innerHTML = preview;
                            }
                        }, 50);
                    }
                },
                buttons: [
                    {
                        text: 'Copy Shortcode',
                        onclick: function() {
                            var formData = dialogInstance.toJSON();
                            var linkText = formData.linkText || '';
                            
                            var urls = {};
                            var hasUrls = false;
                            
                            spokeConnections.forEach(function(connection) {
                                var url = formData['url_' + connection.id] || '';
                                if (url) {
                                    urls[connection.name] = url;
                                    hasUrls = true;
                                }
                            });
                            
                            if (!linkText || !hasUrls) {
                                alert('Please fill in link text and at least one URL.');
                                return;
                            }
                            
                            var shortcode = '[custom-smart-link urls=\'' + JSON.stringify(urls) + '\']' + linkText + '[/custom-smart-link]';
                            
                            // Update preview
                            var previewElement = document.getElementById('custom-smart-link-preview');
                            if (previewElement) {
                                previewElement.innerHTML = shortcode;
                            }
                            
                            copyToClipboard(shortcode);
                            alert('Shortcode copied to clipboard!');
                        }
                    },
                    {
                        text: 'Insert',
                        onclick: function() {
                            var formData = dialogInstance.toJSON();
                            var linkText = formData.linkText || '';
                            
                            var urls = {};
                            var hasUrls = false;
                            
                            spokeConnections.forEach(function(connection) {
                                var url = formData['url_' + connection.id] || '';
                                if (url) {
                                    urls[connection.name] = url;
                                    hasUrls = true;
                                }
                            });
                            
                            if (!linkText || !hasUrls) {
                                alert('Please fill in link text and at least one URL.');
                                return;
                            }
                            
                            var shortcode = '[custom-smart-link urls=\'' + JSON.stringify(urls) + '\']' + linkText + '[/custom-smart-link]';
                            
                            // Update preview before inserting
                            var previewElement = document.getElementById('custom-smart-link-preview');
                            if (previewElement) {
                                previewElement.innerHTML = shortcode;
                            }
                            
                            editor.insertContent(shortcode);
                            dialogInstance.close();
                        }
                    },
                    {
                        text: 'Cancel',
                        onclick: function() {
                            dialogInstance.close();
                        }
                    }
                ]
            });
        }

        // Copy to clipboard function
        function copyToClipboard(text) {
            if (navigator.clipboard && window.isSecureContext) {
                // Modern clipboard API
                navigator.clipboard.writeText(text);
            } else {
                // Fallback for older browsers
                var textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                textArea.style.top = '-999999px';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                document.execCommand('copy');
                textArea.remove();
            }
        }
    });
})();
