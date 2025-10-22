/**
 * SourceHub Calendar JavaScript
 */

(function($) {
    'use strict';

    let calendar;
    let currentEvents = [];

    $(document).ready(function() {
        // Wait for FullCalendar to load
        if (typeof FullCalendar !== 'undefined') {
            initializeCalendar();
            bindEvents();
        } else {
            // Retry after a short delay
            setTimeout(function() {
                if (typeof FullCalendar !== 'undefined') {
                    initializeCalendar();
                    bindEvents();
                } else {
                    console.error('FullCalendar library not loaded');
                    $('.calendar-loading').hide();
                    $('.calendar-fallback').show();
                    // Still bind basic events even without calendar
                    bindBasicEvents();
                }
            }, 1000);
        }
    });

    /**
     * Initialize FullCalendar
     */
    function initializeCalendar() {
        try {
            const calendarEl = document.getElementById('sourcehub-calendar');
            
            if (!calendarEl) {
                console.error('Calendar element not found');
                return;
            }
            
            console.log('Initializing FullCalendar...');
            
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listDay'
                },
                views: {
                    listDay: {
                        type: 'list',
                        duration: { days: 1 },
                        buttonText: 'List'
                    }
                },
                height: 'auto',
                slotMinTime: '00:00:00',
                slotMaxTime: '24:00:00',
                slotDuration: '01:00:00',
                slotLabelInterval: '01:00:00',
                expandRows: true,
                timeZone: 'local',
                eventTimeFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    meridiem: 'short'
                },
                eventOverlap: true,
                slotEventOverlap: false,
                events: loadCalendarEvents,
                eventClick: function(info) {
                    console.log('Event clicked:', info.event.title, info.event.extendedProps);
                    info.jsEvent.preventDefault();
                    info.jsEvent.stopPropagation();
                    
                    // Get the edit URL from the event
                    const editUrl = info.event.extendedProps.edit_url;
                    console.log('Edit URL:', editUrl);
                    
                    if (editUrl) {
                        // Open in same window to go to post editor
                        console.log('Navigating to:', editUrl);
                        window.location.href = editUrl;
                    } else {
                        console.log('No edit URL found, showing modal');
                        // Fallback to showing details modal
                        showEventDetails(info.event);
                    }
                },
                loading: function(isLoading) {
                    console.log('Calendar loading:', isLoading);
                    if (isLoading) {
                        $('.calendar-loading').show();
                    } else {
                        $('.calendar-loading').hide();
                    }
                },
                eventContent: function(arg) {
                    return {
                        html: formatEventContent(arg.event)
                    };
                },
                eventDidMount: function(info) {
                    console.log('Event mounted:', info.event.title, 'Start:', info.event.start, 'All Day:', info.event.allDay);
                    console.log('Event element:', info.el);
                    console.log('Event position:', info.el.style.top, info.el.style.left);
                    
                    // Add event ID to the element for global handler
                    const $el = $(info.el);
                    $el.attr('data-event-id', info.event.id);
                    
                    // Add multiple click handlers as backup
                    // Handler on the main event element
                    $el.on('click.sourcehub', function(e) {
                        console.log('Direct click handler triggered for:', info.event.title);
                        e.preventDefault();
                        e.stopPropagation();
                        
                        const editUrl = info.event.extendedProps.edit_url;
                        if (editUrl) {
                            console.log('Direct navigation to:', editUrl);
                            window.location.href = editUrl;
                        }
                    });
                    
                    // Handler on the card content
                    $el.find('.sourcehub-event-card').on('click.sourcehub', function(e) {
                        console.log('Card click handler triggered for:', info.event.title);
                        e.preventDefault();
                        e.stopPropagation();
                        
                        const editUrl = info.event.extendedProps.edit_url;
                        if (editUrl) {
                            console.log('Card navigation to:', editUrl);
                            window.location.href = editUrl;
                        }
                    });
                },
                eventsSet: function(events) {
                    console.log('Events loaded:', events.length);
                    // Add scroll indicators after events are rendered
                    setTimeout(function() {
                        addScrollIndicators();
                        
                        // Debug: Log all event elements and adjust heights
                        $('.fc-timegrid-event').each(function(index) {
                            const $el = $(this);
                            const eventId = $el.attr('data-event-id');
                            console.log('Event element', index, 'ID:', eventId, 'Element:', this);
                            
                            // Add a very simple click handler directly to each element
                            $el.off('click.simple').on('click.simple', function(e) {
                                console.log('SIMPLE CLICK HANDLER - Event ID:', eventId);
                                
                                if (currentEvents && currentEvents.length) {
                                    const event = currentEvents.find(function(evt) {
                                        return evt.id == eventId;
                                    });
                                    
                                    if (event && event.extendedProps && event.extendedProps.edit_url) {
                                        console.log('SIMPLE HANDLER - Navigating to:', event.extendedProps.edit_url);
                                        e.preventDefault();
                                        e.stopPropagation();
                                        window.open(event.extendedProps.edit_url, '_blank');
                                        return false;
                                    }
                                }
                            });
                        });
                        
                        // Adjust time slot heights based on content
                        adjustTimeSlotHeights();
                    }, 100);
                }
            });

            calendar.render();
            console.log('FullCalendar rendered successfully');
            
            // Hide loading initially
            $('.calendar-loading').hide();
            
            // Test if basic clicks work on the calendar container
            setTimeout(function() {
                $('#sourcehub-calendar').off('click.test').on('click.test', function(e) {
                    console.log('BASIC CALENDAR CLICK TEST - Target:', e.target.tagName, e.target.className);
                    
                    // If click hit the events container, find which event was clicked
                    if ($(e.target).hasClass('fc-timegrid-col-events')) {
                        console.log('Click hit events container at coordinates:', e.clientX, e.clientY);
                        
                        // Find the event at the click position
                        const $events = $(e.target).find('.fc-timegrid-event');
                        let clickedEvent = null;
                        
                        $events.each(function() {
                            const rect = this.getBoundingClientRect();
                            if (e.clientX >= rect.left && e.clientX <= rect.right &&
                                e.clientY >= rect.top && e.clientY <= rect.bottom) {
                                clickedEvent = $(this);
                                return false; // Break the loop
                            }
                        });
                        
                        if (clickedEvent) {
                            const eventId = clickedEvent.attr('data-event-id');
                            console.log('Found clicked event with ID:', eventId);
                            
                            if (currentEvents && currentEvents.length) {
                                const event = currentEvents.find(function(evt) {
                                    return evt.id == eventId;
                                });
                                
                                if (event && event.extendedProps && event.extendedProps.edit_url) {
                                    console.log('Container click navigating to:', event.extendedProps.edit_url);
                                    window.open(event.extendedProps.edit_url, '_blank');
                                }
                            }
                        } else {
                            console.log('No event found at click position');
                        }
                    }
                });
                console.log('Basic click test handler added to calendar');
            }, 500);
            
        } catch (error) {
            console.error('Error initializing calendar:', error);
            $('.calendar-loading').hide();
            $('.calendar-fallback').show();
        }
    }

    /**
     * Load calendar events via AJAX
     */
    function loadCalendarEvents(info, successCallback, failureCallback) {
        const filters = getActiveFilters();
        
        console.log('Loading calendar events with filters:', filters);
        
        $.ajax({
            url: sourcehub_calendar.ajax_url,
            type: 'POST',
            data: {
                action: 'sourcehub_get_calendar_events',
                nonce: sourcehub_calendar.nonce,
                start: info.startStr,
                end: info.endStr,
                filters: filters
            },
            success: function(response) {
                console.log('Calendar AJAX response:', response);
                if (response.success) {
                    currentEvents = response.data;
                    
                    // Debug: Log ALL events to see their start times
                    if (response.data.length > 0) {
                        console.log('ALL EVENTS WITH TIMES:');
                        response.data.forEach(function(event, index) {
                            console.log('Event', index + 1, ':', {
                                title: event.title,
                                start: event.start,
                                allDay: event.allDay,
                                id: event.id,
                                extendedProps: event.extendedProps
                            });
                        });
                    }
                    
                    updateStats(response.data);
                    successCallback(response.data);
                } else {
                    console.error('Calendar error:', response.data);
                    $('.calendar-loading p').text('Error loading calendar data: ' + (response.data || 'Unknown error'));
                    failureCallback();
                }
            },
            error: function(xhr, status, error) {
                console.error('Calendar AJAX error:', status, error);
                $('.calendar-loading p').text('Network error loading calendar data');
                failureCallback();
            }
        });
    }

    /**
     * Get active filters
     */
    function getActiveFilters() {
        const filters = {
            status: $('#post-status-filter').val() || [],
            post_type: $('#post-type-filter').val() || ['post'],
            category: $('#category-filter').val() || [],
            spoke: $('#spoke-filter').val() || []
        };
        
        // Remove empty string values from spoke filter
        if (Array.isArray(filters.spoke)) {
            filters.spoke = filters.spoke.filter(function(value) {
                return value !== '' && value !== null && value !== undefined;
            });
        }
        
        console.log('Active filters:', filters);
        return filters;
    }

    /**
     * Format event content for display
     */
    function formatEventContent(event) {
        const props = event.extendedProps;
        const spokes = props.spokes || [];
        const categories = props.categories || [];
        
        let html = '<div class="sourcehub-event-card">';
        
        // Header with title and status
        html += '<div class="event-card-header">';
        html += '<div class="event-title">' + event.title + '</div>';
        html += '<div class="event-status">' + getStatusBadge(props.post_status) + '</div>';
        html += '</div>';
        
        // Content area
        html += '<div class="event-card-content">';
        
        // Categories
        if (categories.length > 0) {
            html += '<div class="event-meta-row">';
            html += '<span class="event-meta-icon">📁</span>';
            html += '<span class="event-meta-text">' + categories.slice(0, 2).join(', ') + '</span>';
            html += '</div>';
        }
        
        // Author
        if (props.author) {
            html += '<div class="event-meta-row">';
            html += '<span class="event-meta-icon">👤</span>';
            html += '<span class="event-meta-text">' + props.author + '</span>';
            html += '</div>';
        }
        
        // Spoke sites
        if (spokes.length > 0) {
            html += '<div class="event-meta-row">';
            html += '<span class="event-meta-icon">🌐</span>';
            html += '<div class="event-spokes-container">';
            spokes.forEach(function(spoke) {
                const statusClass = spoke.syndicated ? 'syndicated' : 'pending';
                const statusIcon = spoke.syndicated ? '✓' : '⏳';
                html += '<span class="spoke-badge-card ' + statusClass + '">' + statusIcon + ' ' + spoke.name + '</span>';
            });
            html += '</div>';
            html += '</div>';
        } else {
            html += '<div class="event-meta-row">';
            html += '<span class="event-meta-icon">⚠️</span>';
            html += '<span class="event-meta-text no-syndication">No syndication configured</span>';
            html += '</div>';
        }
        
        html += '</div>'; // Close content
        html += '</div>'; // Close card
        
        return html;
    }

    /**
     * Get status badge HTML
     */
    function getStatusBadge(status) {
        const badges = {
            'publish': '<span class="status-badge status-published">Published</span>',
            'future': '<span class="status-badge status-scheduled">Scheduled</span>',
            'draft': '<span class="status-badge status-draft">Draft</span>',
            'pending': '<span class="status-badge status-pending">Pending</span>',
            'private': '<span class="status-badge status-private">Private</span>'
        };
        
        return badges[status] || '<span class="status-badge status-unknown">' + status + '</span>';
    }

    /**
     * Get event tooltip content
     */
    function getEventTooltip(event) {
        const props = event.extendedProps;
        const spokes = props.spokes || [];
        const categories = props.categories || [];
        
        let tooltip = '<div class="event-tooltip">';
        tooltip += '<strong>' + event.title + '</strong><br>';
        tooltip += '<em>Status: ' + sourcehub_calendar.strings.post_status[props.post_status] + '</em><br>';
        
        if (categories.length > 0) {
            tooltip += 'Categories: ' + categories.join(', ') + '<br>';
        }
        
        if (props.author) {
            tooltip += 'Author: ' + props.author + '<br>';
        }
        
        if (spokes.length > 0) {
            tooltip += 'Spoke Sites: ';
            const spokeNames = spokes.map(function(spoke) {
                return spoke.name + (spoke.syndicated ? ' ✓' : '');
            });
            tooltip += spokeNames.join(', ');
        }
        
        tooltip += '</div>';
        return tooltip;
    }

    /**
     * Show event details modal
     */
    function showEventDetails(event) {
        const props = event.extendedProps;
        const modal = $('#event-details-modal');
        
        // Populate modal content
        $('#modal-title').text(event.title);
        $('#modal-status').text(sourcehub_calendar.strings.post_status[props.post_status])
            .removeClass().addClass('status-badge status-' + props.post_status);
        $('#modal-author').text(props.author || 'Unknown');
        $('#modal-categories').text(props.categories.join(', ') || 'None');
        $('#modal-date').text(formatDate(event.start));
        $('#modal-excerpt').text(props.excerpt || 'No excerpt available');
        $('#modal-edit-link').attr('href', props.edit_url);
        
        // Populate spoke sites
        const spokesContainer = $('#modal-spokes');
        spokesContainer.empty();
        
        if (props.spokes && props.spokes.length > 0) {
            props.spokes.forEach(function(spoke) {
                const spokeEl = $('<div class="spoke-item">');
                const statusClass = spoke.syndicated ? 'syndicated' : 'pending';
                const statusText = spoke.syndicated ? 'Syndicated' : 'Pending';
                
                spokeEl.html(
                    '<span class="spoke-name">' + spoke.name + '</span>' +
                    '<span class="spoke-status ' + statusClass + '">' + statusText + '</span>'
                );
                spokesContainer.append(spokeEl);
            });
        } else {
            spokesContainer.html('<em>No spoke sites selected</em>');
        }
        
        // Show modal
        modal.show();
    }

    /**
     * Update statistics
     */
    function updateStats(events) {
        const stats = {
            total: events.length,
            scheduled: 0,
            draft: 0,
            syndicated: 0
        };
        
        events.forEach(function(event) {
            const props = event.extendedProps;
            
            if (props.post_status === 'future') {
                stats.scheduled++;
            }
            if (props.post_status === 'draft') {
                stats.draft++;
            }
            if (props.spokes && props.spokes.length > 0) {
                stats.syndicated++;
            }
        });
        
        $('#total-posts').text(stats.total);
        $('#scheduled-posts').text(stats.scheduled);
        $('#draft-posts').text(stats.draft);
        $('#syndicated-posts').text(stats.syndicated);
    }

    /**
     * Format date for display
     */
    function formatDate(date) {
        return new Date(date).toLocaleString();
    }

    /**
     * Bind event handlers
     */
    function bindEvents() {
        // View selector
        $('#calendar-view-select').on('change', function() {
            const selectedView = $(this).val();
            
            // Show/hide date picker based on view
            if (selectedView === 'listDay') {
                $('#list-date-picker-group').show();
            } else {
                $('#list-date-picker-group').hide();
            }
            
            if (calendar) {
                calendar.changeView(selectedView);
            }
        });

        // Date picker for list view
        $('#list-date-picker').on('change', function() {
            const selectedDate = $(this).val();
            if (calendar && selectedDate) {
                calendar.gotoDate(selectedDate);
            }
        });

        // Apply filters
        $('#apply-filters').on('click', function(e) {
            e.preventDefault();
            console.log('Apply filters clicked');
            if (calendar) {
                calendar.refetchEvents();
            }
        });

        // Reset filters
        $('#reset-filters').on('click', function(e) {
            e.preventDefault();
            console.log('Reset filters clicked');
            $('#post-status-filter').val(['publish', 'future', 'draft']);
            $('#post-type-filter').val(['post']);
            $('#category-filter').val([]);
            $('#spoke-filter').val([]);
            if (calendar) {
                calendar.refetchEvents();
            }
        });

        // Refresh calendar
        $('#refresh-calendar').on('click', function(e) {
            e.preventDefault();
            console.log('Refresh calendar clicked');
            if (calendar) {
                calendar.refetchEvents();
            }
        });

        // Modal close handlers
        $('.sourcehub-modal-close').on('click', function() {
            $('#event-details-modal').hide();
        });

        // Close modal on outside click
        $('#event-details-modal').on('click', function(e) {
            if (e.target === this) {
                $(this).hide();
            }
        });

        // Escape key to close modal
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $('#event-details-modal').hide();
            }
        });

        // Filter change handlers
        $('.calendar-filter').on('change', function() {
            // Auto-apply filters after a short delay
            clearTimeout(window.filterTimeout);
            window.filterTimeout = setTimeout(function() {
                if (calendar) {
                    calendar.refetchEvents();
                }
            }, 500);
        });

        // Test AJAX button (always bind this)
        $('#test-ajax').on('click', function() {
            console.log('Testing AJAX call...');
            $('#ajax-result').html('<div class="spinner is-active"></div> Testing...');
            
            $.ajax({
                url: sourcehub_calendar.ajax_url,
                type: 'POST',
                data: {
                    action: 'sourcehub_get_calendar_events',
                    nonce: sourcehub_calendar.nonce,
                    start: '2024-01-01',
                    end: '2024-12-31',
                    filters: {
                        status: ['publish', 'future', 'draft'],
                        post_type: ['post']
                    }
                },
                success: function(response) {
                    console.log('AJAX Success:', response);
                    if (response.success) {
                        $('#ajax-result').html('<div style="color: green;">✓ AJAX working! Found ' + response.data.length + ' events</div>');
                    } else {
                        $('#ajax-result').html('<div style="color: red;">✗ AJAX Error: ' + response.data + '</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error:', status, error);
                    $('#ajax-result').html('<div style="color: red;">✗ Network Error: ' + error + '</div>');
                }
            });
        });

        // Initialize select2 for better multi-select experience
        if ($.fn.select2) {
            $('.calendar-filter[multiple]').select2({
                placeholder: 'Select options...',
                allowClear: true,
                width: '100%'
            });
        }

        // Global click handler for any part of an event
        $(document).on('click.sourcehub', '.fc-timegrid-event, .fc-timegrid-event *, .sourcehub-event-card, .sourcehub-event-card *', function(e) {
            console.log('Global click handler triggered on:', this.tagName, this.className);
            
            // Find the closest event element to get the event data
            const $eventEl = $(this).closest('.fc-event');
            if ($eventEl.length) {
                const eventId = $eventEl.attr('data-event-id') || $eventEl.data('event-id');
                console.log('Found event ID:', eventId);
                
                // Try to find the event in current events
                if (currentEvents && currentEvents.length) {
                    const event = currentEvents.find(function(evt) {
                        return evt.id == eventId;
                    });
                    
                    if (event && event.extendedProps && event.extendedProps.edit_url) {
                        console.log('Global handler navigating to:', event.extendedProps.edit_url);
                        e.preventDefault();
                        e.stopPropagation();
                        window.location.href = event.extendedProps.edit_url;
                        return false;
                    }
                } else {
                    console.log('No current events available');
                }
            } else {
                console.log('No event element found');
            }
        });

        // Additional fallback - click anywhere on calendar and check if it's an event
        $(document).on('click.sourcehub', '#sourcehub-calendar', function(e) {
            const $target = $(e.target);
            const $eventEl = $target.closest('.fc-event');
            
            if ($eventEl.length) {
                console.log('Calendar click detected event element');
                const eventId = $eventEl.attr('data-event-id') || $eventEl.data('event-id');
                
                if (currentEvents && currentEvents.length) {
                    const event = currentEvents.find(function(evt) {
                        return evt.id == eventId;
                    });
                    
                    if (event && event.extendedProps && event.extendedProps.edit_url) {
                        console.log('Calendar click navigating to:', event.extendedProps.edit_url);
                        e.preventDefault();
                        window.location.href = event.extendedProps.edit_url;
                    }
                }
            }
        });
    }

    /**
     * Bind basic events (without calendar)
     */
    function bindBasicEvents() {
        // Test AJAX button
        $('#test-ajax').on('click', function() {
            console.log('Testing AJAX call...');
            $('#ajax-result').html('<div class="spinner is-active"></div> Testing...');
            
            $.ajax({
                url: sourcehub_calendar.ajax_url,
                type: 'POST',
                data: {
                    action: 'sourcehub_get_calendar_events',
                    nonce: sourcehub_calendar.nonce,
                    start: '2024-01-01',
                    end: '2024-12-31',
                    filters: {
                        status: ['publish', 'future', 'draft'],
                        post_type: ['post']
                    }
                },
                success: function(response) {
                    console.log('AJAX Success:', response);
                    if (response.success) {
                        $('#ajax-result').html('<div style="color: green;">✓ AJAX working! Found ' + response.data.length + ' events</div>');
                    } else {
                        $('#ajax-result').html('<div style="color: red;">✗ AJAX Error: ' + response.data + '</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error:', status, error);
                    $('#ajax-result').html('<div style="color: red;">✗ Network Error: ' + error + '</div>');
                }
            });
        });

        // Modal close handlers
        $('.sourcehub-modal-close').on('click', function() {
            $('#event-details-modal').hide();
        });

        // Close modal on outside click
        $('#event-details-modal').on('click', function(e) {
            if (e.target === this) {
                $(this).hide();
            }
        });

        // Escape key to close modal
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $('#event-details-modal').hide();
            }
        });

        console.log('Basic events bound (calendar not available)');
    }

    /**
     * Add scroll indicators to time grid columns with scrollable content
     */
    function addScrollIndicators() {
        // Let FullCalendar handle event positioning naturally
        console.log('Events positioned by FullCalendar');
    }

    /**
     * Adjust time slot heights based on content
     */
    function adjustTimeSlotHeights() {
        if (!calendar || calendar.view.type.indexOf('timeGrid') === -1) {
            return; // Only apply to time grid views
        }

        // Group events by time slot
        const timeSlotHeights = {};
        
        $('.fc-timegrid-event').each(function() {
            const $event = $(this);
            const $container = $event.closest('.fc-timegrid-col-events');
            const $slot = $container.closest('.fc-timegrid-slot-lane').prev('.fc-timegrid-slot');
            
            if ($slot.length) {
                const slotTime = $slot.data('time') || $slot.find('.fc-timegrid-slot-label-cushion').text();
                
                if (!timeSlotHeights[slotTime]) {
                    timeSlotHeights[slotTime] = {
                        slot: $slot,
                        lane: $container.closest('.fc-timegrid-slot-lane'),
                        maxHeight: 60 // minimum height
                    };
                }
                
                // Calculate required height for this slot
                const containerHeight = $container.outerHeight();
                if (containerHeight > timeSlotHeights[slotTime].maxHeight) {
                    timeSlotHeights[slotTime].maxHeight = containerHeight + 10; // Add padding
                }
            }
        });
        
        // Apply calculated heights
        Object.keys(timeSlotHeights).forEach(function(slotTime) {
            const slotData = timeSlotHeights[slotTime];
            const height = slotData.maxHeight + 'px';
            
            slotData.slot.css('height', height);
            slotData.lane.css('height', height);
        });
        
        console.log('Adjusted heights for', Object.keys(timeSlotHeights).length, 'time slots');
    }

})(jQuery);
