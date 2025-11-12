/**
 * SourceHub Sync Status Polling
 * Monitors syndication status and updates UI in real-time
 */

(function($) {
    'use strict';

    let pollInterval = null;
    let pollCount = 0;
    const MAX_POLLS = 30; // 30 polls x 2 seconds = 60 seconds max

    /**
     * Start polling for sync status
     */
    function startPolling() {
        const postId = $('#post_ID').val();
        if (!postId) {
            console.log('SourceHub: No post ID found, skipping sync status polling');
            return;
        }

        console.log('SourceHub: Starting sync status polling for post', postId);
        pollCount = 0;

        // Poll immediately
        checkSyncStatus(postId);

        // Then poll every 2 seconds
        pollInterval = setInterval(function() {
            pollCount++;
            
            if (pollCount >= MAX_POLLS) {
                console.log('SourceHub: Max polls reached, stopping');
                stopPolling();
                updateBadge('timeout', null);
                return;
            }

            checkSyncStatus(postId);
        }, 2000);
    }

    /**
     * Stop polling
     */
    function stopPolling() {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }
    }

    /**
     * Check sync status via AJAX
     */
    function checkSyncStatus(postId) {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'sourcehub_check_sync_status',
                post_id: postId,
                nonce: sourcehubAdmin.nonce
            },
            success: function(response) {
                if (response.success && response.data) {
                    const status = response.data.status;
                    const count = response.data.count || 0;
                    const perSpokeStatus = response.data.per_spoke_status || {};

                    console.log('SourceHub: Sync status:', status, 'Count:', count, 'Per-spoke:', perSpokeStatus);

                    // Update overall badge
                    updateBadge(status, count);

                    // Update individual spoke badges
                    updateSpokesBadges(perSpokeStatus);

                    // Check if all spokes are done (not processing)
                    let allSpokesDone = true;
                    for (let spokeId in perSpokeStatus) {
                        if (perSpokeStatus[spokeId].status === 'processing') {
                            allSpokesDone = false;
                            break;
                        }
                    }

                    // Stop polling if overall status is done AND all spokes are done
                    if ((status === 'completed' || status === 'failed' || status === 'none') && allSpokesDone) {
                        console.log('SourceHub: All spokes completed, stopping polling');
                        stopPolling();
                    } else if (status === 'processing' || status === 'syncing' || !allSpokesDone) {
                        console.log('SourceHub: Still syncing, continuing to poll...');
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('SourceHub: Sync status check failed:', error);
            }
        });
    }

    /**
     * Update individual spoke badges
     */
    function updateSpokesBadges(perSpokeStatus) {
        console.log('SourceHub: Updating spoke badges with status:', perSpokeStatus);
        
        // Loop through each connection item
        $('.sourcehub-connection-item').each(function() {
            const $item = $(this);
            const $checkbox = $item.find('input[name="sourcehub_selected_spokes[]"]');
            const spokeId = $checkbox.val();
            
            console.log('SourceHub: Checking spoke ID:', spokeId, 'Status:', perSpokeStatus[spokeId]);
            
            if (!spokeId) {
                return;
            }
            
            // Try both string and integer versions of the ID
            const spokeStatus = perSpokeStatus[spokeId] || perSpokeStatus[parseInt(spokeId)];
            
            if (!spokeStatus) {
                console.log('SourceHub: No status found for spoke', spokeId);
                return;
            }
            
            console.log('SourceHub: Updating spoke', spokeId, 'to status:', spokeStatus.status);
            
            const $infoDiv = $item.find('.syndication-info');
            
            // Remove existing badges
            $infoDiv.find('.sync-status-badge').remove();
            
            let badgeHtml = '';
            
            if (spokeStatus.status === 'processing') {
                badgeHtml = '<span class="sync-status-badge sync-processing">' +
                    '<span class="dashicons dashicons-update"></span>' +
                    '<small>Processing...</small>' +
                    '</span>';
            } else if (spokeStatus.status === 'success') {
                badgeHtml = '<span class="sync-status-badge sync-success">' +
                    '<span class="dashicons dashicons-yes-alt"></span>' +
                    '<small>Synced</small>' +
                    '</span>';
            } else if (spokeStatus.status === 'failed') {
                const errorMsg = spokeStatus.error || 'Unknown error';
                badgeHtml = '<span class="sync-status-badge sync-failed" title="' + errorMsg + '">' +
                    '<span class="dashicons dashicons-warning"></span>' +
                    '<small>Failed</small>' +
                    '</span>';
            }
            
            if (badgeHtml) {
                $infoDiv.prepend(badgeHtml);
            }
        });
    }

    /**
     * Update the syndication badge
     */
    function updateBadge(status, count) {
        const $badge = $('.sourcehub-syndication-status');
        if (!$badge.length) {
            console.log('SourceHub: Badge element not found');
            return;
        }

        let badgeClass = '';
        let badgeText = '';
        let badgeIcon = '';

        switch (status) {
            case 'processing':
                badgeClass = 'processing';
                badgeIcon = '⏳';
                badgeText = 'Processing...';
                break;
            case 'syncing':
                badgeClass = 'syncing';
                badgeIcon = '🔄';
                badgeText = 'Syndicating...';
                break;
            case 'completed':
                badgeClass = 'synced';
                badgeIcon = '✓';
                badgeText = count > 0 ? 'Synced to ' + count + ' spoke' + (count !== 1 ? 's' : '') : 'Synced';
                break;
            case 'failed':
                badgeClass = 'error';
                badgeIcon = '✗';
                badgeText = 'Sync Failed';
                break;
            case 'timeout':
                badgeClass = 'warning';
                badgeIcon = '⚠';
                badgeText = 'Sync Timeout - Refresh to check';
                break;
            case 'none':
                badgeClass = 'not-synced';
                badgeIcon = '';
                badgeText = 'Not Syndicated';
                break;
            default:
                return;
        }

        // Update badge
        $badge
            .removeClass('processing syncing synced error warning not-synced')
            .addClass(badgeClass)
            .html(badgeIcon + ' ' + badgeText);

        console.log('SourceHub: Badge updated to:', badgeText);
    }

    /**
     * Initialize on page load
     */
    $(document).ready(function() {
        // Check if we're on post edit screen
        if ($('#post_ID').length === 0) {
            return;
        }

        console.log('SourceHub: Sync status monitor initialized');

        // Start polling after post save
        $(document).on('click', '#publish, #save-post', function() {
            console.log('SourceHub: Post save detected, will start polling after save');
            
            // Wait a moment for the save to complete
            setTimeout(function() {
                startPolling();
            }, 1000);
        });

        // Also check status on page load (in case we're returning to a post that's syncing)
        const postId = $('#post_ID').val();
        if (postId) {
            checkSyncStatus(postId);
        }
    });

})(jQuery);
