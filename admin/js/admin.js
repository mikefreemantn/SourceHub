/**
 * SourceHub Admin JavaScript
 *
 * @package SourceHub
 */

(function($) {
    'use strict';
    
    // Debug flag - set to true to enable console debugging
    var SOURCEHUB_DEBUG = false;
    
    // Debug logger function - only logs if debug is enabled
    function debug() {
        if (SOURCEHUB_DEBUG && window.console && window.console.log) {
            console.log.apply(console, ['[SourceHub Debug]'].concat(Array.prototype.slice.call(arguments)));
        }
    }
    
    // Error logger function - always logs errors
    function logError() {
        if (window.console && window.console.error) {
            console.error.apply(console, ['[SourceHub Error]'].concat(Array.prototype.slice.call(arguments)));
        }
    }
    
    // Check jQuery version
    var jQueryVersion = $.fn.jquery.split('.');
    var minVersion = '1.12.4';
    var minVersionParts = minVersion.split('.');
    
    if (parseInt(jQueryVersion[0]) < parseInt(minVersionParts[0]) || 
        (parseInt(jQueryVersion[0]) === parseInt(minVersionParts[0]) && parseInt(jQueryVersion[1]) < parseInt(minVersionParts[1]))) {
        logError('SourceHub requires jQuery ' + minVersion + ' or higher. Current version: ' + $.fn.jquery);
    }
    
    // Global error handler
    window.addEventListener('error', function(event) {
        if (SOURCEHUB_DEBUG) {
            logError('Uncaught error:', event.message, 'at', event.filename, 'line', event.lineno);
        }
        return false;
    });

    // Global SourceHub Admin object
    window.SourceHubAdmin = {
        init: function() {
            this.bindEvents();
            this.initComponents();
        },

        bindEvents: function() {
            // Settings form
            $(document).on('submit', '#sourcehub-settings-form', this.saveSettings);
            $(document).on('click', '#test-api-connection', this.testApiConnection);
            
            // Connection management
            $(document).on('click', '.test-connection', this.testConnection);
            $(document).on('click', '.delete-connection', this.deleteConnection);
            $(document).on('submit', '#add-connection-form', this.addConnection);
            $(document).on('submit', '#edit-connection-form', this.editConnection);
            
            // Modal handling with improved event delegation
            $(document).off('click', '[data-modal]').on('click', '[data-modal]', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                var modalId = $(this).data('modal');
                var connectionId = $(this).data('connection-id');
                
                if (SOURCEHUB_DEBUG) {
                    debug('Modal trigger clicked:', {
                        element: this,
                        modalId: modalId,
                        connectionId: connectionId
                    });
                }
                
                // If this is an edit modal, load the connection data first
                if (modalId === 'edit-connection-modal' && connectionId) {
                    SourceHubAdmin.loadConnectionForEdit(connectionId);
                }
                
                SourceHubAdmin.openModal(modalId);
                return false;
            });
            
            $(document).off('click', '.modal-close, .sourcehub-modal').on('click', '.modal-close, .sourcehub-modal', function(e) {
                if (e.target === this || $(e.target).hasClass('modal-close')) {
                    e.preventDefault();
                    SourceHubAdmin.closeModal();
                    return false;
                }
            });
            
            // Tabs
            $(document).on('click', '.nav-tab', this.switchTab);
            
            // Auto-refresh logs
            if ($('#sourcehub-logs-table').length) {
                setInterval(this.refreshLogs, 30000); // Refresh every 30 seconds
            }
        },

        initComponents: function() {
            // Initialize tooltips
            this.initTooltips();
            
            // Initialize copy buttons
            this.initCopyButtons();
            
            // Initialize form validation
            this.initFormValidation();
        },

        saveSettings: function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $button = $form.find('button[type="submit"]');
            var originalText = $button.text();
            
            // Show loading state
            $button.text(sourcehub_admin.strings.saving).prop('disabled', true);
            
            // Convert form data to object
            var settings = {};
            $form.serializeArray().forEach(function(item) {
                if (item.name.startsWith('sourcehub_')) {
                    var key = item.name.replace('sourcehub_', '');
                    settings[key] = item.value;
                }
            });
            
            // Handle checkboxes
            $form.find('input[type="checkbox"]').each(function() {
                if (this.name.startsWith('sourcehub_')) {
                    var key = this.name.replace('sourcehub_', '');
                    settings[key] = this.checked;
                }
            });
            
            $.ajax({
                url: sourcehub_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'sourcehub_save_settings',
                    nonce: sourcehub_admin.ajax_nonce,
                    settings: settings
                },
                success: function(response) {
                    if (response.success) {
                        SourceHubAdmin.showNotice('success', response.data.message);
                        // Reload page to show updated mode-specific sections
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    } else {
                        SourceHubAdmin.showNotice('error', response.data.message || sourcehub_admin.strings.error);
                    }
                },
                error: function() {
                    SourceHubAdmin.showNotice('error', sourcehub_admin.strings.error);
                },
                complete: function() {
                    $button.text(originalText).prop('disabled', false);
                }
            });
        },

        testApiConnection: function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var $apiKeyInput = $('#sourcehub_openai_api_key');
            var apiKey = $apiKeyInput.val().trim();
            
            if (!apiKey) {
                SourceHubAdmin.showNotice('error', 'Please enter an API key first');
                return;
            }
            
            var originalText = $button.text();
            $button.text(sourcehub_admin.strings.testing_connection).prop('disabled', true);
            
            $.ajax({
                url: sourcehub_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'sourcehub_test_api',
                    api_key: apiKey,
                    nonce: sourcehub_admin.ajax_nonce
                },
                success: function(response) {
                    if (response.success) {
                        SourceHubAdmin.showNotice('success', response.data.message);
                    } else {
                        SourceHubAdmin.showNotice('error', response.data.message);
                    }
                },
                error: function() {
                    SourceHubAdmin.showNotice('error', sourcehub_admin.strings.connection_failed);
                },
                complete: function() {
                    $button.text(originalText).prop('disabled', false);
                }
            });
        },

        testConnection: function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var connectionId = $button.data('connection-id');
            var originalText = $button.text();
            
            // Check if we're in the edit modal - if so, use the form's API key
            var apiKey = null;
            var $modal = $button.closest('.sourcehub-modal');
            var inModal = $modal.length && $modal.attr('id') === 'edit-connection-modal';
            var $resultDiv = $('#edit-connection-test-result');
            
            if (inModal) {
                apiKey = $('#edit_connection_api_key').val();
                var testUrl = $('#edit_connection_url').val();
                
                if (!apiKey || apiKey.trim() === '') {
                    $resultDiv.html('<div class="test-result error" style="padding: 10px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px;">Please enter an API key first</div>');
                    return;
                }
                
                if (!testUrl || testUrl.trim() === '') {
                    $resultDiv.html('<div class="test-result error" style="padding: 10px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px;">Please enter a URL first</div>');
                    return;
                }
                
                // Clear previous result
                $resultDiv.html('');
            }
            
            $button.text(sourcehub_admin.strings.testing_connection).prop('disabled', true);
            
            var requestData = {};
            if (apiKey) {
                requestData.api_key = apiKey;
                requestData.url = testUrl;
            }
            
            $.ajax({
                url: sourcehub_admin.rest_url + 'connections/' + connectionId + '/test',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(requestData),
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', sourcehub_admin.nonce);
                },
                success: function(response) {
                    var responseTime = response.response_time || 0;
                    var speedClass, speedMessage, speedIcon, bgColor, textColor, borderColor;
                    
                    // Determine speed category and styling
                    if (responseTime < 500) {
                        // Fast - Green
                        speedClass = 'fast';
                        speedMessage = '⚡ Excellent! Lightning fast connection';
                        speedIcon = 'dashicons-yes-alt';
                        bgColor = '#d4edda';
                        textColor = '#155724';
                        borderColor = '#c3e6cb';
                    } else if (responseTime < 2000) {
                        // Moderate - Yellow/Orange
                        speedClass = 'moderate';
                        speedMessage = '⚠️ Good connection, but could be faster';
                        speedIcon = 'dashicons-warning';
                        bgColor = '#fff3cd';
                        textColor = '#856404';
                        borderColor = '#ffeaa7';
                    } else if (responseTime < 5000) {
                        // Slow - Orange/Red
                        speedClass = 'slow';
                        speedMessage = '🐌 Slow connection - may cause delays';
                        speedIcon = 'dashicons-warning';
                        bgColor = '#ffe5d0';
                        textColor = '#d63638';
                        borderColor = '#ffb380';
                    } else {
                        // Very Slow - Red
                        speedClass = 'very-slow';
                        speedMessage = '🔥 Extremely slow! High risk of timeouts';
                        speedIcon = 'dashicons-dismiss';
                        bgColor = '#f8d7da';
                        textColor = '#721c24';
                        borderColor = '#f5c6cb';
                    }
                    
                    var timeDisplay = '<strong>' + responseTime + 'ms</strong>';
                    
                    if (response.success) {
                        if (inModal) {
                            // Show result in modal with color coding
                            $resultDiv.html('<div class="test-result ' + speedClass + '" style="padding: 12px; background: ' + bgColor + '; color: ' + textColor + '; border: 2px solid ' + borderColor + '; border-radius: 6px; font-weight: 500;"><span class="dashicons ' + speedIcon + '" style="color: ' + textColor + ';"></span> ' + speedMessage + '<br><small style="opacity: 0.9; margin-top: 4px; display: inline-block;">Response time: ' + timeDisplay + '</small></div>');
                        } else {
                            // Show custom styled notice with color coding
                            SourceHubAdmin.showCustomNotice(speedMessage + ' (Response time: ' + responseTime + 'ms)', speedIcon, bgColor, textColor, borderColor);
                            
                            // Update connection status in UI
                            $button.closest('tr').find('.connection-status').html('<span class="badge badge-success">Active</span>');
                            
                            // Update response time in table with color coding
                            var $responseTimeCell = $button.closest('tr').find('.connection-response-time');
                            $responseTimeCell.html('<span style="font-weight: 600; color: ' + textColor + ';">' + responseTime + 'ms</span>');
                        }
                    } else {
                        if (inModal) {
                            // Show error in modal
                            $resultDiv.html('<div class="test-result error" style="padding: 12px; background: #f8d7da; color: #721c24; border: 2px solid #f5c6cb; border-radius: 6px; font-weight: 500;"><span class="dashicons dashicons-no" style="color: #721c24;"></span> ' + response.message + '<br><small style="opacity: 0.9; margin-top: 4px; display: inline-block;">Response time: ' + timeDisplay + '</small></div>');
                        } else {
                            SourceHubAdmin.showNotice('error', response.message + (responseTime ? ' (' + responseTime + 'ms)' : ''));
                            
                            // Update connection status in UI
                            $button.closest('tr').find('.connection-status').html('<span class="badge badge-danger">Error</span>');
                            
                            // Update response time in table (red for errors)
                            var $responseTimeCell = $button.closest('tr').find('.connection-response-time');
                            if (responseTime) {
                                $responseTimeCell.html('<span style="font-weight: 600; color: #d63638;">' + responseTime + 'ms</span>');
                            }
                        }
                    }
                },
                error: function() {
                    if (inModal) {
                        $resultDiv.html('<div class="test-result error" style="padding: 10px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px;"><span class="dashicons dashicons-no" style="color: #721c24;"></span> ' + sourcehub_admin.strings.connection_failed + '</div>');
                    } else {
                        SourceHubAdmin.showNotice('error', sourcehub_admin.strings.connection_failed);
                        $button.closest('tr').find('.connection-status').html('<span class="badge badge-danger">Error</span>');
                    }
                },
                complete: function() {
                    $button.text(originalText).prop('disabled', false);
                }
            });
        },

        deleteConnection: function(e) {
            e.preventDefault();
            
            if (!confirm(sourcehub_admin.strings.confirm_delete)) {
                return;
            }
            
            var $button = $(this);
            var connectionId = $button.data('connection-id');
            var $row = $button.closest('tr');
            
            $button.prop('disabled', true);
            
            $.ajax({
                url: sourcehub_admin.rest_url + 'connections/' + connectionId,
                type: 'DELETE',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', sourcehub_admin.nonce);
                },
                success: function(response) {
                    if (response.success) {
                        $row.fadeOut(300, function() {
                            $(this).remove();
                        });
                        SourceHubAdmin.showNotice('success', response.message);
                    } else {
                        SourceHubAdmin.showNotice('error', response.message);
                        $button.prop('disabled', false);
                    }
                },
                error: function() {
                    SourceHubAdmin.showNotice('error', sourcehub_admin.strings.error);
                    $button.prop('disabled', false);
                }
            });
        },

        addConnection: function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $button = $form.find('button[type="submit"]');
            var originalText = $button.text();
            
            $button.text(sourcehub_admin.strings.saving).prop('disabled', true);
            
            var formData = {};
            $form.serializeArray().forEach(function(item) {
                formData[item.name] = item.value;
            });
            
            // Handle checkboxes for sync and AI settings
            var syncSettings = {};
            var aiSettings = {};
            
            $form.find('input[name^="sync_"]').each(function() {
                var key = this.name.replace('sync_', '');
                syncSettings[key] = this.type === 'checkbox' ? this.checked : this.value;
            });
            
            $form.find('input[name^="ai_"], select[name^="ai_"]').each(function() {
                var key = this.name.replace('ai_', '');
                aiSettings[key] = this.type === 'checkbox' ? this.checked : this.value;
            });
            
            formData.sync_settings = syncSettings;
            formData.ai_settings = aiSettings;
            
            $.ajax({
                url: sourcehub_admin.rest_url + 'connections',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(formData),
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', sourcehub_admin.nonce);
                },
                success: function(response) {
                    if (response.success) {
                        SourceHubAdmin.showNotice('success', response.message);
                        $form[0].reset();
                        SourceHubAdmin.closeModal();
                        // Reload the page to show new connection
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        SourceHubAdmin.showNotice('error', response.message);
                    }
                },
                error: function() {
                    SourceHubAdmin.showNotice('error', sourcehub_admin.strings.error);
                },
                complete: function() {
                    $button.text(originalText).prop('disabled', false);
                }
            });
        },

        editConnection: function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $button = $form.find('button[type="submit"]');
            var originalText = $button.text();
            var connectionId = $('#edit-connection-id').val();
            
            $button.text(sourcehub_admin.strings.saving).prop('disabled', true);
            
            var formData = {};
            $form.serializeArray().forEach(function(item) {
                if (item.name !== 'connection_id') {
                    formData[item.name] = item.value;
                }
            });
            
            // Handle checkboxes for sync and AI settings
            var syncSettings = {};
            var aiSettings = {};
            
            $form.find('input[name^="sync_"]').each(function() {
                var key = this.name.replace('sync_', '');
                syncSettings[key] = this.type === 'checkbox' ? this.checked : this.value;
            });
            
            $form.find('input[name^="ai_"], select[name^="ai_"]').each(function() {
                var key = this.name.replace('ai_', '');
                aiSettings[key] = this.type === 'checkbox' ? this.checked : this.value;
            });
            
            formData.sync_settings = syncSettings;
            formData.ai_settings = aiSettings;
            
            $.ajax({
                url: sourcehub_admin.rest_url + 'connections/' + connectionId,
                type: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify(formData),
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', sourcehub_admin.nonce);
                },
                success: function(response) {
                    if (response.success) {
                        SourceHubAdmin.showNotice('success', response.message);
                        SourceHubAdmin.closeModal();
                        // Reload the page to show updated connection
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        SourceHubAdmin.showNotice('error', response.message);
                    }
                },
                error: function() {
                    SourceHubAdmin.showNotice('error', sourcehub_admin.strings.error);
                },
                complete: function() {
                    $button.text(originalText).prop('disabled', false);
                }
            });
        },

        openModal: function(e) {
            // Handle both direct calls with modalId and event-based calls
            var modalId;
            if (typeof e === 'string') {
                modalId = e;
            } else {
                if (e && e.preventDefault) e.preventDefault();
                modalId = $(this).data('modal');
            }
            
            var $modal = $('#' + modalId);
            
            if (SOURCEHUB_DEBUG) {
                debug('Opening modal:', {
                    modalId: modalId,
                    modalExists: $modal.length > 0,
                    modalElement: $modal[0]
                });
            }
            
            if ($modal.length) {
                // Create backdrop if it doesn't exist
                if ($('.modal-backdrop').length === 0) {
                    $('body').append('<div class="modal-backdrop"></div>');
                }
                
                $modal.addClass('active');
                $('.modal-backdrop').addClass('active');
                $('body').addClass('modal-open');
                
                // Ensure modal is visible by checking computed style
                if (SOURCEHUB_DEBUG) {
                    setTimeout(function() {
                        var modalStyle = window.getComputedStyle($modal[0]);
                        debug('Modal visibility check:', {
                            visibility: modalStyle.visibility,
                            display: modalStyle.display,
                            opacity: modalStyle.opacity,
                            zIndex: modalStyle.zIndex
                        });
                    }, 100);
                }
            } else {
                logError('Modal not found:', modalId);
            }
        },

        closeModal: function(e) {
            if (!e || e.target === this || $(e.target).hasClass('modal-close')) {
                if (SOURCEHUB_DEBUG) {
                    debug('Closing modal');
                }
                $('.sourcehub-modal').removeClass('active');
                $('.modal-backdrop').removeClass('active');
                $('body').removeClass('modal-open');
                
                // Remove backdrop after animation completes
                setTimeout(function() {
                    $('.modal-backdrop').not('.active').remove();
                }, 300);
            }
        },

        switchTab: function(e) {
            e.preventDefault();
            
            var $tab = $(this);
            var target = $tab.attr('href');
            
            // Update active tab
            $tab.closest('.nav-tab-wrapper').find('.nav-tab').removeClass('nav-tab-active');
            $tab.addClass('nav-tab-active');
            
            // Show target content
            $('.tab-content').hide();
            $(target).show();
        },

        refreshLogs: function() {
            var $table = $('#sourcehub-logs-table tbody');
            if (!$table.length) return;
            
            $.ajax({
                url: sourcehub_admin.rest_url + 'logs',
                type: 'GET',
                data: {
                    per_page: 20,
                    page: 1
                },
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', sourcehub_admin.nonce);
                },
                success: function(response) {
                    if (response.logs && response.logs.length > 0) {
                        var html = '';
                        response.logs.forEach(function(log) {
                            html += SourceHubAdmin.buildLogRow(log);
                        });
                        $table.html(html);
                    }
                }
            });
        },

        buildLogRow: function(log) {
            var badgeClass = 'secondary';
            switch (log.status.toUpperCase()) {
                case 'SUCCESS':
                    badgeClass = 'success';
                    break;
                case 'ERROR':
                    badgeClass = 'danger';
                    break;
                case 'WARNING':
                    badgeClass = 'warning';
                    break;
                case 'INFO':
                    badgeClass = 'info';
                    break;
            }
            
            return '<tr>' +
                '<td><span class="badge badge-' + badgeClass + '">' + log.status.toUpperCase() + '</span></td>' +
                '<td>' + log.message + '</td>' +
                '<td>' + (log.action || '-') + '</td>' +
                '<td>' + SourceHubAdmin.formatDate(log.created_at) + '</td>' +
                '</tr>';
        },

        formatDate: function(dateString) {
            var date = new Date(dateString);
            return date.toLocaleString();
        },

        showNotice: function(type, message) {
            var $notice = $('<div class="sourcehub-notice notice-' + type + '">' +
                '<span class="dashicons dashicons-' + (type === 'success' ? 'yes' : (type === 'error' ? 'no' : 'info')) + '"></span>' +
                '<p>' + message + '</p>' +
                '</div>');
            
            // Remove existing notices
            $('.sourcehub-notice').remove();
            
            // Add new notice
            $('.sourcehub-admin h1').after($notice);
            
            // Auto-remove after 20 seconds
            setTimeout(function() {
                $notice.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 20000);
            
            // Scroll to top to show notice
            $('html, body').animate({
                scrollTop: 0
            }, 300);
        },

        showCustomNotice: function(message, icon, bgColor, textColor, borderColor) {
            var $notice = $('<div class="sourcehub-notice sourcehub-notice-custom" style="background: ' + bgColor + '; color: ' + textColor + '; border-left: 4px solid ' + borderColor + ';">' +
                '<span class="dashicons ' + icon + '" style="color: ' + textColor + ';"></span>' +
                '<p>' + message + '</p>' +
                '</div>');
            
            // Remove existing notices
            $('.sourcehub-notice').remove();
            
            // Add new notice
            $('.sourcehub-admin h1').after($notice);
            
            // Auto-remove after 20 seconds
            setTimeout(function() {
                $notice.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 20000);
            
            // Scroll to top to show notice
            $('html, body').animate({
                scrollTop: 0
            }, 300);
        },

        initTooltips: function() {
            $('[data-tooltip]').each(function() {
                var $element = $(this);
                var tooltip = $element.data('tooltip');
                
                $element.on('mouseenter', function() {
                    var $tooltip = $('<div class="sourcehub-tooltip">' + tooltip + '</div>');
                    $('body').append($tooltip);
                    
                    var offset = $element.offset();
                    $tooltip.css({
                        top: offset.top - $tooltip.outerHeight() - 5,
                        left: offset.left + ($element.outerWidth() / 2) - ($tooltip.outerWidth() / 2)
                    });
                });
                
                $element.on('mouseleave', function() {
                    $('.sourcehub-tooltip').remove();
                });
            });
        },

        initCopyButtons: function() {
            $('.copy-button').on('click', function(e) {
                e.preventDefault();
                
                var $button = $(this);
                var target = $button.data('copy-target');
                var $target = $(target);
                
                if ($target.length) {
                    var text = $target.is('input, textarea') ? $target.val() : $target.text();
                    
                    if (navigator.clipboard) {
                        navigator.clipboard.writeText(text).then(function() {
                            SourceHubAdmin.showNotice('success', 'Copied to clipboard!');
                        });
                    } else {
                        // Fallback for older browsers
                        var $temp = $('<textarea>');
                        $('body').append($temp);
                        $temp.val(text).select();
                        document.execCommand('copy');
                        $temp.remove();
                        SourceHubAdmin.showNotice('success', 'Copied to clipboard!');
                    }
                }
            });
        },

        initFormValidation: function() {
            // URL validation
            $('input[type="url"]').on('blur', function() {
                var $input = $(this);
                var url = $input.val().trim();
                
                if (url && !SourceHubAdmin.isValidUrl(url)) {
                    $input.addClass('error');
                    SourceHubAdmin.showFieldError($input, 'Please enter a valid URL');
                } else {
                    $input.removeClass('error');
                    SourceHubAdmin.hideFieldError($input);
                }
            });
            
            // Required field validation
            $('input[required], select[required], textarea[required]').on('blur', function() {
                var $input = $(this);
                var value = $input.val().trim();
                
                if (!value) {
                    $input.addClass('error');
                    SourceHubAdmin.showFieldError($input, 'This field is required');
                } else {
                    $input.removeClass('error');
                    SourceHubAdmin.hideFieldError($input);
                }
            });
        },

        isValidUrl: function(string) {
            try {
                new URL(string);
                return true;
            } catch (_) {
                return false;
            }
        },

        showFieldError: function($field, message) {
            SourceHubAdmin.hideFieldError($field);
            var $error = $('<div class="field-error">' + message + '</div>');
            $field.after($error);
        },

        hideFieldError: function($field) {
            $field.siblings('.field-error').remove();
        },

        loadConnectionForEdit: function(connectionId) {
            if (SOURCEHUB_DEBUG) {
                debug('Loading connection for edit:', connectionId);
            }

            // Make AJAX request to get connection data
            $.ajax({
                url: sourcehub_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'sourcehub_get_connection',
                    connection_id: connectionId,
                    nonce: sourcehub_admin.ajax_nonce
                },
                success: function(response) {
                    if (SOURCEHUB_DEBUG) {
                        debug('Get connection response:', response);
                    }
                    if (response.success && response.data) {
                        SourceHubAdmin.populateEditForm(response.data);
                    } else {
                        var errorMsg = response.data ? response.data.message : 'Failed to load connection data';
                        SourceHubAdmin.showNotice('error', errorMsg);
                        console.error('Connection load failed:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText, status, error);
                    SourceHubAdmin.showNotice('error', 'AJAX Error: ' + error + ' - Check console for details');
                }
            });
        },

        populateEditForm: function(connection) {
            if (SOURCEHUB_DEBUG) {
                debug('Populating edit form with:', connection);
            }

            // Populate basic fields
            $('#edit-connection-id').val(connection.id);
            $('#edit_connection_name').val(connection.name);
            $('#edit_connection_url').val(connection.url);
            $('#edit_connection_api_key').val(connection.api_key);
            
            // Set connection ID on test button
            $('#edit-test-connection').data('connection-id', connection.id);

            // Parse and populate sync settings
            var syncSettings = {};
            try {
                syncSettings = JSON.parse(connection.sync_settings || '{}');
            } catch (e) {
                syncSettings = {};
            }
            
            $('#edit_sync_auto_publish').prop('checked', syncSettings.auto_publish || false);
            $('#edit_sync_categories').prop('checked', syncSettings.categories || false);
            $('#edit_sync_tags').prop('checked', syncSettings.tags || false);
            $('#edit_sync_featured_image').prop('checked', syncSettings.featured_image || false);
            $('#edit_sync_yoast_meta').prop('checked', syncSettings.yoast_meta || false);

            // Populate AI settings
            $('#edit_ai_enabled').prop('checked', connection.ai_enabled == 1);
            $('#edit_ai_rewrite_title').prop('checked', connection.ai_rewrite_title == 1);
            $('#edit_ai_rewrite_content').prop('checked', connection.ai_rewrite_content == 1);
            $('#edit_ai_rewrite_excerpt').prop('checked', connection.ai_rewrite_excerpt == 1);
            $('#edit_ai_tone').val(connection.ai_tone || 'professional');
            $('#edit_ai_length_adjustment').val(connection.ai_length_adjustment || 'maintain');

            // Show/hide AI settings based on ai_enabled
            if (connection.ai_enabled == 1) {
                $('.edit-ai-settings').show();
            } else {
                $('.edit-ai-settings').hide();
            }

            // Add event handler for AI enabled toggle in edit modal
            $('#edit_ai_enabled').off('change').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.edit-ai-settings').slideDown();
                } else {
                    $('.edit-ai-settings').slideUp();
                }
            });
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        SourceHubAdmin.init();
    });

    // Handle escape key for modals
    $(document).keyup(function(e) {
        if (e.keyCode === 27) { // Escape key
            SourceHubAdmin.closeModal(e);
        }
    });

})(jQuery);
