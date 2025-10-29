<?php
/**
 * Bug Tracker - View Bug Detail
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get bug ID
$bug_id = isset($_GET['bug_id']) ? intval($_GET['bug_id']) : 0;

if (!$bug_id) {
    wp_die('Invalid bug ID');
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify nonce
    if (!isset($_POST['sourcehub_bug_nonce']) || !wp_verify_nonce($_POST['sourcehub_bug_nonce'], 'update_bug_' . $bug_id)) {
        wp_die('Security check failed');
    }
    
    // Handle note addition
    if (isset($_POST['add_note']) && !empty($_POST['note'])) {
        SourceHub_Bug_Tracker::add_note($bug_id, wp_unslash($_POST['note']));
        $success_message = __('Note added successfully', 'sourcehub');
    }
    
    // Handle status/priority update
    if (isset($_POST['update_bug'])) {
        $update_data = array();
        
        if (isset($_POST['status'])) {
            $update_data['status'] = sanitize_text_field($_POST['status']);
        }
        
        if (isset($_POST['priority'])) {
            $update_data['priority'] = sanitize_text_field($_POST['priority']);
        }
        
        if (!empty($update_data)) {
            SourceHub_Bug_Tracker::update_bug($bug_id, $update_data);
            $success_message = __('Bug updated successfully', 'sourcehub');
        }
    }
    
    // Handle subscription
    if (isset($_POST['subscribe']) && !empty($_POST['subscribe_email'])) {
        $email = sanitize_email($_POST['subscribe_email']);
        SourceHub_Bug_Tracker::subscribe($bug_id, $email);
        $success_message = __('Successfully subscribed to bug updates', 'sourcehub');
    }
    
    // Handle unsubscription
    if (isset($_POST['unsubscribe']) && !empty($_POST['unsubscribe_email'])) {
        $email = sanitize_email($_POST['unsubscribe_email']);
        SourceHub_Bug_Tracker::unsubscribe($bug_id, $email);
        $success_message = __('Successfully unsubscribed from bug updates', 'sourcehub');
    }
}

// Get bug details
$bug = SourceHub_Bug_Tracker::get_bug($bug_id);

if (!$bug) {
    wp_die('Bug not found');
}

// Get notes
$notes = SourceHub_Bug_Tracker::get_notes($bug_id);

// Get categories
$categories = SourceHub_Bug_Tracker::get_categories();
?>

<?php if (isset($_GET['submitted'])): ?>
    <div class="notice notice-success is-dismissible">
        <p><?php echo esc_html__('Bug submitted successfully! We\'ve received your report and will investigate.', 'sourcehub'); ?></p>
    </div>
<?php endif; ?>

<?php if (isset($success_message)): ?>
    <div class="notice notice-success is-dismissible">
        <p><?php echo esc_html($success_message); ?></p>
    </div>
<?php endif; ?>

<div class="sourcehub-bug-detail">
    <!-- Bug Header -->
    <div class="sourcehub-bug-header">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <h2 style="margin: 0 0 10px 0;"><?php echo esc_html($bug->title); ?></h2>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <?php echo SourceHub_Bug_Tracker::get_status_badge($bug->status); ?>
                    <?php echo SourceHub_Bug_Tracker::get_priority_badge($bug->priority); ?>
                    <span style="color: #666; font-size: 13px;">
                        Bug #<?php echo esc_html($bug->id); ?>
                    </span>
                </div>
            </div>
            <div>
                <a href="<?php echo admin_url('admin.php?page=sourcehub-bugs'); ?>" class="button">
                    <?php echo esc_html__('← Back to List', 'sourcehub'); ?>
                </a>
            </div>
        </div>
    </div>

    <!-- Bug Meta Information -->
    <div class="sourcehub-bug-meta">
        <div class="sourcehub-bug-meta-item">
            <label><?php echo esc_html__('Category', 'sourcehub'); ?></label>
            <span><?php echo esc_html($categories[$bug->category] ?? $bug->category); ?></span>
        </div>
        <div class="sourcehub-bug-meta-item">
            <label><?php echo esc_html__('Reporter', 'sourcehub'); ?></label>
            <span>
                <?php echo esc_html($bug->reporter_name); ?>
                <?php if ($bug->reporter_email): ?>
                    <br><a href="mailto:<?php echo esc_attr($bug->reporter_email); ?>"><?php echo esc_html($bug->reporter_email); ?></a>
                <?php endif; ?>
            </span>
        </div>
        <div class="sourcehub-bug-meta-item">
            <label><?php echo esc_html__('Created', 'sourcehub'); ?></label>
            <span><?php echo esc_html(date('M j, Y g:i a', strtotime($bug->created_at))); ?></span>
        </div>
        <div class="sourcehub-bug-meta-item">
            <label><?php echo esc_html__('Last Updated', 'sourcehub'); ?></label>
            <span><?php echo esc_html(date('M j, Y g:i a', strtotime($bug->updated_at))); ?></span>
        </div>
        <?php if ($bug->resolved_at): ?>
            <div class="sourcehub-bug-meta-item">
                <label><?php echo esc_html__('Resolved', 'sourcehub'); ?></label>
                <span>
                    <?php echo esc_html(date('M j, Y g:i a', strtotime($bug->resolved_at))); ?>
                    <?php if ($bug->resolved_version): ?>
                        <br><small style="color: #666;">v<?php echo esc_html($bug->resolved_version); ?></small>
                    <?php endif; ?>
                </span>
            </div>
        <?php endif; ?>
        <div class="sourcehub-bug-meta-item">
            <label><?php echo esc_html__('Plugin Version', 'sourcehub'); ?></label>
            <span><?php echo esc_html($bug->plugin_version); ?></span>
        </div>
        <div class="sourcehub-bug-meta-item">
            <label><?php echo esc_html__('WordPress', 'sourcehub'); ?></label>
            <span><?php echo esc_html($bug->wordpress_version); ?></span>
        </div>
        <div class="sourcehub-bug-meta-item">
            <label><?php echo esc_html__('PHP', 'sourcehub'); ?></label>
            <span><?php echo esc_html($bug->php_version); ?></span>
        </div>
        <?php if ($bug->reporter_url): ?>
            <div class="sourcehub-bug-meta-item">
                <label><?php echo esc_html__('Site URL', 'sourcehub'); ?></label>
                <span><a href="<?php echo esc_url($bug->reporter_url); ?>" target="_blank"><?php echo esc_html($bug->reporter_url); ?></a></span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bug Description -->
    <div style="margin: 20px 0;">
        <h3><?php echo esc_html__('Description', 'sourcehub'); ?></h3>
        <div style="background: #f9f9f9; padding: 15px; border-radius: 4px; white-space: pre-wrap; font-family: monospace;">
            <?php echo wp_kses_post($bug->description); ?>
        </div>
    </div>

    <!-- Screenshots -->
    <?php if (!empty($bug->attachments)): 
        $attachments = json_decode($bug->attachments, true);
        if (!empty($attachments)):
    ?>
        <div style="margin: 20px 0;">
            <h3><?php echo esc_html__('Screenshots', 'sourcehub'); ?></h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;">
                <?php foreach ($attachments as $attachment): ?>
                    <div style="border: 1px solid #ddd; border-radius: 4px; overflow: hidden;">
                        <a href="<?php echo esc_url($attachment['url']); ?>" target="_blank">
                            <img src="<?php echo esc_url($attachment['url']); ?>" alt="Screenshot" style="width: 100%; height: auto; display: block;">
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php 
        endif;
    endif; 
    ?>

    <!-- Subscription Section -->
    <div style="background: #e7f3ff; border-left: 4px solid #2271b1; padding: 15px; margin: 20px 0; border-radius: 4px;">
        <h3 style="margin-top: 0;">📧 <?php echo esc_html__('Email Notifications', 'sourcehub'); ?></h3>
        
        <?php 
        $current_user = wp_get_current_user();
        $user_email = $current_user->user_email;
        $is_subscribed = SourceHub_Bug_Tracker::is_subscribed($bug_id, $user_email);
        
        // Get subscriber count
        $subscribers = !empty($bug->subscribers) ? json_decode($bug->subscribers, true) : array();
        $subscriber_count = count($subscribers);
        ?>
        
        <p style="margin: 10px 0;">
            <?php if ($subscriber_count > 0): ?>
                <strong><?php echo esc_html($subscriber_count); ?></strong> <?php echo esc_html(_n('person is', 'people are', $subscriber_count, 'sourcehub')); ?> subscribed to updates for this bug.
            <?php else: ?>
                <?php echo esc_html__('No one is subscribed to this bug yet.', 'sourcehub'); ?>
            <?php endif; ?>
        </p>
        
        <?php if ($subscriber_count > 0): ?>
            <div style="background: white; padding: 10px; border-radius: 4px; margin: 10px 0;">
                <strong style="font-size: 12px; text-transform: uppercase; color: #666;">
                    <?php echo esc_html__('Subscribers:', 'sourcehub'); ?>
                </strong>
                <ul style="margin: 10px 0 0 0; padding: 0; list-style: none;">
                    <?php foreach ($subscribers as $subscriber_email): ?>
                        <li style="padding: 5px 0; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; gap: 8px;">
                            <span style="display: inline-block; width: 24px; height: 24px; background: #2271b1; color: white; border-radius: 50%; text-align: center; line-height: 24px; font-size: 12px; font-weight: bold;">
                                <?php echo esc_html(strtoupper(substr($subscriber_email, 0, 1))); ?>
                            </span>
                            <span><?php echo esc_html($subscriber_email); ?></span>
                            <?php if ($subscriber_email === $bug->reporter_email): ?>
                                <span style="background: #28a745; color: white; padding: 2px 6px; border-radius: 3px; font-size: 10px; font-weight: 600;">
                                    <?php echo esc_html__('REPORTER', 'sourcehub'); ?>
                                </span>
                            <?php endif; ?>
                            <?php if ($subscriber_email === $user_email): ?>
                                <span style="background: #2271b1; color: white; padding: 2px 6px; border-radius: 3px; font-size: 10px; font-weight: 600;">
                                    <?php echo esc_html__('YOU', 'sourcehub'); ?>
                                </span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if ($is_subscribed): ?>
            <p style="color: #28a745; margin: 10px 0;">
                ✓ <?php echo esc_html__('You are subscribed and will receive email notifications when this bug is updated.', 'sourcehub'); ?>
            </p>
            <form method="post" action="" style="display: inline;">
                <?php wp_nonce_field('update_bug_' . $bug_id, 'sourcehub_bug_nonce'); ?>
                <input type="hidden" name="unsubscribe_email" value="<?php echo esc_attr($user_email); ?>">
                <button type="submit" name="unsubscribe" class="button">
                    <?php echo esc_html__('Unsubscribe', 'sourcehub'); ?>
                </button>
            </form>
        <?php else: ?>
            <p style="margin: 10px 0;">
                <?php echo esc_html__('Subscribe to receive email notifications when:', 'sourcehub'); ?>
            </p>
            <ul style="margin-left: 20px; color: #666;">
                <li><?php echo esc_html__('Bug status changes', 'sourcehub'); ?></li>
                <li><?php echo esc_html__('Bug priority changes', 'sourcehub'); ?></li>
                <li><?php echo esc_html__('New comments are added', 'sourcehub'); ?></li>
            </ul>
            <form method="post" action="" style="margin-top: 10px;">
                <?php wp_nonce_field('update_bug_' . $bug_id, 'sourcehub_bug_nonce'); ?>
                <input type="hidden" name="subscribe_email" value="<?php echo esc_attr($user_email); ?>">
                <button type="submit" name="subscribe" class="button button-primary">
                    <?php echo esc_html__('Subscribe to Updates', 'sourcehub'); ?>
                </button>
            </form>
        <?php endif; ?>
    </div>

    <!-- Update Bug Form -->
    <div style="background: #f5f5f5; padding: 15px; border-radius: 4px; margin: 20px 0;">
        <h3><?php echo esc_html__('Update Bug', 'sourcehub'); ?></h3>
        <form method="post" action="">
            <?php wp_nonce_field('update_bug_' . $bug_id, 'sourcehub_bug_nonce'); ?>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label for="status" style="display: block; margin-bottom: 5px; font-weight: 600;">
                        <?php echo esc_html__('Status', 'sourcehub'); ?>
                    </label>
                    <select id="status" name="status" style="width: 100%;">
                        <option value="open" <?php selected($bug->status, 'open'); ?>><?php echo esc_html__('Open', 'sourcehub'); ?></option>
                        <option value="in_progress" <?php selected($bug->status, 'in_progress'); ?>><?php echo esc_html__('In Progress', 'sourcehub'); ?></option>
                        <option value="resolved" <?php selected($bug->status, 'resolved'); ?>><?php echo esc_html__('Resolved', 'sourcehub'); ?></option>
                        <option value="closed" <?php selected($bug->status, 'closed'); ?>><?php echo esc_html__('Closed', 'sourcehub'); ?></option>
                    </select>
                </div>
                
                <div>
                    <label for="priority" style="display: block; margin-bottom: 5px; font-weight: 600;">
                        <?php echo esc_html__('Priority', 'sourcehub'); ?>
                    </label>
                    <select id="priority" name="priority" style="width: 100%;">
                        <option value="low" <?php selected($bug->priority, 'low'); ?>><?php echo esc_html__('Low', 'sourcehub'); ?></option>
                        <option value="medium" <?php selected($bug->priority, 'medium'); ?>><?php echo esc_html__('Medium', 'sourcehub'); ?></option>
                        <option value="high" <?php selected($bug->priority, 'high'); ?>><?php echo esc_html__('High', 'sourcehub'); ?></option>
                        <option value="critical" <?php selected($bug->priority, 'critical'); ?>><?php echo esc_html__('Critical', 'sourcehub'); ?></option>
                    </select>
                </div>
                
                <div style="display: flex; align-items: flex-end;">
                    <button type="submit" name="update_bug" class="button button-primary">
                        <?php echo esc_html__('Update Bug', 'sourcehub'); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Notes Section -->
    <div class="sourcehub-bug-notes">
        <h3><?php echo esc_html__('Notes & Activity', 'sourcehub'); ?></h3>
        
        <!-- Add Note Form -->
        <form method="post" action="" enctype="multipart/form-data" style="margin-bottom: 20px;">
            <?php wp_nonce_field('update_bug_' . $bug_id, 'sourcehub_bug_nonce'); ?>
            
            <div style="position: relative;">
                <textarea id="bug-note-textarea" name="note" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="<?php echo esc_attr__('Add a note or comment... (use @ to mention users)', 'sourcehub'); ?>"></textarea>
                <div id="mention-autocomplete" style="display: none; position: absolute; background: white; border: 1px solid #ddd; border-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); max-height: 200px; overflow-y: auto; z-index: 1000; margin-top: 2px;"></div>
            </div>
            
            <div style="margin-top: 10px; display: flex; align-items: center; gap: 15px;">
                <button type="submit" name="add_note" class="button button-primary">
                    <?php echo esc_html__('Add Note', 'sourcehub'); ?>
                </button>
                
                <label style="cursor: pointer; color: #2271b1; display: flex; align-items: center; gap: 5px;">
                    <span style="font-size: 18px;">📎</span>
                    <span><?php echo esc_html__('Attach Images', 'sourcehub'); ?></span>
                    <input type="file" name="note_images[]" multiple accept="image/*" style="display: none;" onchange="previewNoteImages(this)">
                </label>
            </div>
            
            <div id="note-image-preview" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; margin-top: 10px;"></div>
        </form>
        
        <script>
        function previewNoteImages(input) {
            const preview = document.getElementById('note-image-preview');
            preview.innerHTML = '';
            
            if (input.files && input.files.length > 0) {
                for (let i = 0; i < input.files.length; i++) {
                    const file = input.files[i];
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.style.cssText = 'border: 2px solid #ddd; border-radius: 4px; padding: 5px; text-align: center;';
                        div.innerHTML = '<img src="' + e.target.result + '" style="width: 100%; height: auto; display: block; margin-bottom: 5px;"><small style="color: #666;">' + file.name + '</small>';
                        preview.appendChild(div);
                    };
                    
                    reader.readAsDataURL(file);
                }
            }
        }

        // @mention autocomplete
        (function() {
            const textarea = document.getElementById('bug-note-textarea');
            const autocomplete = document.getElementById('mention-autocomplete');
            let users = <?php echo json_encode(SourceHub_Bug_Tracker::get_users_for_mentions()); ?>;
            let selectedIndex = -1;
            let mentionStart = -1;
            
            textarea.addEventListener('input', function(e) {
                const cursorPos = this.selectionStart;
                const textBeforeCursor = this.value.substring(0, cursorPos);
                const lastAtSymbol = textBeforeCursor.lastIndexOf('@');
                
                if (lastAtSymbol !== -1) {
                    const textAfterAt = textBeforeCursor.substring(lastAtSymbol + 1);
                    
                    // Check if there's a space after @ (which would end the mention)
                    if (textAfterAt.indexOf(' ') === -1) {
                        mentionStart = lastAtSymbol;
                        const searchTerm = textAfterAt.toLowerCase();
                        
                        // Filter users by login, name, or email
                        const filteredUsers = users.filter(user => 
                            user.login.toLowerCase().includes(searchTerm) || 
                            user.name.toLowerCase().includes(searchTerm) ||
                            user.email.toLowerCase().includes(searchTerm)
                        );
                        
                        if (filteredUsers.length > 0) {
                            showAutocomplete(filteredUsers);
                            return;
                        }
                    }
                }
                
                hideAutocomplete();
            });
            
            textarea.addEventListener('keydown', function(e) {
                if (autocomplete.style.display === 'block') {
                    const items = autocomplete.querySelectorAll('.mention-item');
                    
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                        updateSelection(items);
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        selectedIndex = Math.max(selectedIndex - 1, 0);
                        updateSelection(items);
                    } else if (e.key === 'Enter' && selectedIndex >= 0) {
                        e.preventDefault();
                        items[selectedIndex].click();
                    } else if (e.key === 'Escape') {
                        hideAutocomplete();
                    }
                }
            });
            
            function showAutocomplete(filteredUsers) {
                autocomplete.innerHTML = '';
                selectedIndex = -1;
                
                filteredUsers.forEach((user, index) => {
                    const item = document.createElement('div');
                    item.className = 'mention-item';
                    item.style.cssText = 'padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; gap: 10px;';
                    item.innerHTML = '<img src="' + user.avatar + '" style="width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;">' +
                                    '<div style="flex: 1; min-width: 0;">' +
                                    '<strong style="display: block;">' + user.name + '</strong>' +
                                    '<small style="color: #666; display: block;">@' + user.login + '</small>' +
                                    '<small style="color: #999; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' + user.email + '</small>' +
                                    '</div>';
                    
                    item.addEventListener('mouseenter', function() {
                        selectedIndex = index;
                        updateSelection(autocomplete.querySelectorAll('.mention-item'));
                    });
                    
                    item.addEventListener('click', function() {
                        // Insert display name
                        insertMention(user.name);
                    });
                    
                    autocomplete.appendChild(item);
                });
                
                // Position autocomplete below textarea (using relative positioning from wrapper)
                autocomplete.style.display = 'block';
                autocomplete.style.width = '100%';
            }
            
            function hideAutocomplete() {
                autocomplete.style.display = 'none';
                selectedIndex = -1;
                mentionStart = -1;
            }
            
            function updateSelection(items) {
                items.forEach((item, index) => {
                    if (index === selectedIndex) {
                        item.style.backgroundColor = '#f0f0f0';
                    } else {
                        item.style.backgroundColor = 'white';
                    }
                });
            }
            
            function insertMention(username) {
                const cursorPos = textarea.selectionStart;
                const textBefore = textarea.value.substring(0, mentionStart);
                const textAfter = textarea.value.substring(cursorPos);
                
                textarea.value = textBefore + '@' + username + ' ' + textAfter;
                textarea.selectionStart = textarea.selectionEnd = mentionStart + username.length + 2;
                textarea.focus();
                
                hideAutocomplete();
            }
            
            // Hide autocomplete when clicking outside
            document.addEventListener('click', function(e) {
                if (e.target !== textarea && e.target !== autocomplete && !autocomplete.contains(e.target)) {
                    hideAutocomplete();
                }
            });
        })();
        
        // Delete note function
        function deleteNote(noteId, bugId) {
            if (!confirm('Are you sure you want to delete this note? This cannot be undone.')) {
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'sourcehub_delete_note');
            formData.append('note_id', noteId);
            formData.append('bug_id', bugId);
            formData.append('nonce', '<?php echo wp_create_nonce('sourcehub_delete_note'); ?>');
            
            fetch(ajaxurl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const noteElement = document.getElementById('note-' + noteId);
                    if (noteElement) {
                        noteElement.style.transition = 'opacity 0.3s';
                        noteElement.style.opacity = '0';
                        setTimeout(() => noteElement.remove(), 300);
                    }
                } else {
                    alert('Error deleting note: ' + (data.data || 'Unknown error'));
                }
            })
            .catch(error => {
                alert('Error deleting note: ' + error);
            });
        }
        </script>
        
        <style>
        .sourcehub-note {
            position: relative;
        }
        
        .sourcehub-note-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .sourcehub-note-header > div {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        
        .sourcehub-note-author {
            font-weight: 600;
            color: #333;
        }
        
        .sourcehub-note-delete {
            position: absolute;
            bottom: 10px;
            right: 10px;
            opacity: 0;
            transition: opacity 0.2s ease;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 4px 10px;
            font-size: 12px;
            color: #b32d2e;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .sourcehub-note:hover .sourcehub-note-delete {
            opacity: 1;
        }
        
        .sourcehub-note-delete:hover {
            background: #f8d7da;
            border-color: #b32d2e;
        }
        </style>
        
        <!-- Notes List -->
        <?php if (empty($notes)): ?>
            <p style="color: #666; font-style: italic;">
                <?php echo esc_html__('No notes yet. Add the first note above.', 'sourcehub'); ?>
            </p>
        <?php else: ?>
            <?php foreach ($notes as $note): 
                $author = get_userdata($note->author_id);
                $author_name = $author ? $author->display_name : __('Unknown User', 'sourcehub');
                $author_avatar = $author ? get_avatar_url($author->ID, array('size' => 40)) : '';
            ?>
                <div class="sourcehub-note" id="note-<?php echo esc_attr($note->id); ?>">
                    <div class="sourcehub-note-header">
                        <?php if ($author_avatar): ?>
                            <img src="<?php echo esc_url($author_avatar); ?>" alt="<?php echo esc_attr($author_name); ?>" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                        <?php endif; ?>
                        <div>
                            <span class="sourcehub-note-author"><?php echo esc_html($author_name); ?></span>
                            <span style="color: #666; font-size: 13px;"><?php echo esc_html(date('M j, Y g:i a', strtotime($note->created_at))); ?></span>
                        </div>
                    </div>
                    <div class="sourcehub-note-content">
                        <?php echo wp_kses_post($note->note); ?>
                    </div>
                    
                    <?php if (!empty($note->mentions)): 
                        $mentioned_user_ids = json_decode($note->mentions, true);
                        if (!empty($mentioned_user_ids)):
                    ?>
                        <div style="margin-top: 8px; padding: 6px 10px; background: #f0f6fc; border-left: 3px solid #0969da; border-radius: 3px;">
                            <small style="color: #0969da;">
                                <strong>👤 Mentioned:</strong>
                                <?php 
                                $mentioned_names = array();
                                foreach ($mentioned_user_ids as $user_id) {
                                    $mentioned_user = get_userdata($user_id);
                                    if ($mentioned_user) {
                                        $mentioned_names[] = $mentioned_user->display_name;
                                    }
                                }
                                echo esc_html(implode(', ', $mentioned_names));
                                ?>
                            </small>
                        </div>
                    <?php 
                        endif;
                    endif; 
                    ?>
                    
                    <?php if (!empty($note->images)): 
                        $note_images = json_decode($note->images, true);
                        if (!empty($note_images)):
                    ?>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; margin-top: 10px;">
                            <?php foreach ($note_images as $image): ?>
                                <div style="border: 1px solid #ddd; border-radius: 4px; overflow: hidden;">
                                    <a href="<?php echo esc_url($image['url']); ?>" target="_blank">
                                        <img src="<?php echo esc_url($image['url']); ?>" alt="Attached image" style="width: 100%; height: auto; display: block;">
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php 
                        endif;
                    endif; 
                    ?>
                    
                    <?php if (current_user_can('manage_options') || get_current_user_id() == $note->author_id): ?>
                        <button type="button" class="sourcehub-note-delete" onclick="deleteNote(<?php echo esc_js($note->id); ?>, <?php echo esc_js($bug_id); ?>)">
                            🗑️ Delete
                        </button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Archive/Unarchive Section -->
    <?php if ($bug->status === 'archived'): ?>
        <div style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #28a745;">
            <h3 style="color: #155724;"><?php echo esc_html__('Unarchive Bug', 'sourcehub'); ?></h3>
            <p style="color: #666; margin: 10px 0;">
                <?php echo esc_html__('This bug is currently archived. Unarchive it to restore it to the main bug list with "Open" status.', 'sourcehub'); ?>
            </p>
            <form method="post" action="">
                <?php wp_nonce_field('update_bug_' . $bug_id, 'sourcehub_bug_nonce'); ?>
                <button type="submit" name="unarchive_bug" class="button button-primary" style="background: #28a745; border-color: #28a745;">
                    <?php echo esc_html__('Unarchive Bug', 'sourcehub'); ?>
                </button>
            </form>
        </div>
    <?php else: ?>
        <div style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #ffc107;">
            <h3 style="color: #856404;"><?php echo esc_html__('Archive Bug', 'sourcehub'); ?></h3>
            <p style="color: #666; margin: 10px 0;">
                <?php echo esc_html__('Archiving will remove this bug from the main list but keep it in the database for historical records.', 'sourcehub'); ?>
            </p>
            <form method="post" action="" onsubmit="return confirm('<?php echo esc_js(__('Are you sure you want to archive this bug? It will be removed from the main list.', 'sourcehub')); ?>');">
                <?php wp_nonce_field('update_bug_' . $bug_id, 'sourcehub_bug_nonce'); ?>
                <button type="submit" name="delete_bug" class="button" style="background: #ffc107; border-color: #ffc107; color: #000;">
                    <?php echo esc_html__('Archive Bug', 'sourcehub'); ?>
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>
