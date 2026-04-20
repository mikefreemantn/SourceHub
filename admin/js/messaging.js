/**
 * SourceHub Messaging - Frontend JavaScript
 * Handles real-time messaging UI and Heartbeat integration
 * 
 * @package SourceHub
 * @since 2.1.0
 */

(function($) {
    'use strict';

    const SourceHubMessaging = {
        
        // State
        isOpen: false,
        currentView: 'inbox', // inbox, conversation, group, new-group
        currentConversation: null,
        currentGroup: null,
        unreadCount: 0,
        loadedPreviews: new Set(), // Track which URLs have been fetched
        ogDataCache: {}, // Cache OG data by URL
        
        /**
         * Initialize messaging
         */
        init: function() {
            this.bindEvents();
            this.initHeartbeat();
            this.loadInitialData();
            this.updateActivity(); // Update activity on page load
        },
        
        /**
         * Bind UI events
         */
        bindEvents: function() {
            // Chat icon click
            $(document).on('click', '#sourcehub-chat-icon', this.togglePanel.bind(this));
            
            // Close panel
            $(document).on('click', '#sourcehub-chat-close', this.closePanel.bind(this));
            
            // View switches - use data-view attribute
            $(document).on('click', '.sh-nav-btn', function() {
                const view = $(this).data('view');
                SourceHubMessaging.switchView(view);
            });
            
            // Start conversation
            $(document).on('click', '.sh-user-item', this.startConversation.bind(this));
            
            // Open conversation from Inbox
            $(document).on('click', '.sh-conversation-item', this.startConversation.bind(this));
            
            // Open group
            $(document).on('click', '.sh-group-item', this.openGroup.bind(this));
            
            // Group menu button
            $(document).on('click', '.sh-group-menu-btn', function(e) {
                e.stopPropagation(); // Prevent opening the group
                const groupId = $(this).data('group-id');
                const groupName = $(this).data('group-name');
                SourceHubMessaging.showGroupMenu(groupId, groupName, e);
            });
            
            // Edit group modal - close
            $(document).on('click', '.sh-modal-close, #sh-cancel-edit-group', function() {
                $('#sh-edit-group-modal').removeClass('show');
            });
            
            // Edit group modal - save
            $(document).on('click', '#sh-save-edit-group', function() {
                const newName = $('#sh-edit-group-name').val().trim();
                if (newName && SourceHubMessaging.currentEditGroupId) {
                    SourceHubMessaging.updateGroupName(SourceHubMessaging.currentEditGroupId, newName);
                    $('#sh-edit-group-modal').removeClass('show');
                }
            });
            
            // Add member to group
            $(document).on('click', '#sh-add-member-btn', function() {
                const userId = $('#sh-add-member-select').val();
                if (userId && SourceHubMessaging.currentEditGroupId) {
                    SourceHubMessaging.addGroupMember(SourceHubMessaging.currentEditGroupId, userId);
                }
            });
            
            // Remove member from group
            $(document).on('click', '.sh-remove-member-btn', function() {
                const userId = $(this).data('user-id');
                if (userId && SourceHubMessaging.currentEditGroupId) {
                    if (confirm('Remove this member from the group?')) {
                        SourceHubMessaging.removeGroupMember(SourceHubMessaging.currentEditGroupId, userId);
                    }
                }
            });
            
            // Leave group
            $(document).on('click', '#sh-leave-group', function() {
                if (SourceHubMessaging.currentEditGroupId) {
                    if (confirm('Are you sure you want to leave this group?')) {
                        SourceHubMessaging.leaveGroup(SourceHubMessaging.currentEditGroupId);
                    }
                }
            });
            
            // Close modal on background click
            $(document).on('click', '.sh-modal', function(e) {
                if ($(e.target).hasClass('sh-modal')) {
                    $(this).removeClass('show');
                }
            });
            
            // Infinite scroll - load older messages when scrolling to top
            // Note: Need to bind to scroll event directly on the elements, not through delegation
            $('#sh-messages-list, #sh-group-messages-list').on('scroll', function() {
                const $list = $(this);
                if ($list.scrollTop() < 100 && !SourceHubMessaging.isLoadingMore && SourceHubMessaging.hasMoreMessages) {
                    SourceHubMessaging.loadOlderMessages();
                }
            });
            
            // Send message
            $(document).on('click', '#sh-send-message', this.sendMessage.bind(this));
            $(document).on('keypress', '#sh-message-input', function(e) {
                if (e.which === 13 && !e.shiftKey) {
                    e.preventDefault();
                    SourceHubMessaging.sendMessage();
                }
            });
            
            // File upload
            $(document).on('change', '#sh-file-upload', this.handleFileUpload.bind(this));
            
            // Create group
            $(document).on('click', '.sh-create-group', () => this.switchView('new-group'));
            $(document).on('click', '#sh-save-group', this.createGroup.bind(this));
            $(document).on('click', '#sh-cancel-group', () => this.switchView('groups'));
            
            // Back button
            $(document).on('click', '.sh-back-btn', this.goBack.bind(this));
        },
        
        /**
         * Initialize WordPress Heartbeat API
         */
        initHeartbeat: function() {
            // Send check on every heartbeat
            $(document).on('heartbeat-send', function(e, data) {
                data.sourcehub_check_messages = true;
            });
            
            // Receive new messages
            $(document).on('heartbeat-tick', function(e, data) {
                if (data.sourcehub_messages) {
                    SourceHubMessaging.handleNewMessages(data.sourcehub_messages);
                }
            });
        },
        
        /**
         * Load initial data
         */
        loadInitialData: function() {
            this.loadConversations();
            this.loadGroups();
            this.loadOnlineUsers();
            this.checkUnreadCount();
        },
        
        /**
         * Load conversations
         */
        loadConversations: function() {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_get_conversations',
                    nonce: sourcehubMessaging.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.renderConversations(response.data.conversations);
                    }
                }
            });
        },
        
        /**
         * Render conversations in Inbox (both direct messages and groups)
         */
        renderConversations: function(items) {
            const $list = $('#sh-conversations-list');
            $list.empty();
            
            if (!items || items.length === 0) {
                $list.html('<div class="sh-empty">No conversations yet. Start chatting with someone from the Users tab!</div>');
                return;
            }
            
            items.forEach(item => {
                const unreadBadge = item.unread_count > 0 ? 
                    `<span class="sh-unread-badge">${item.unread_count}</span>` : '';
                
                if (item.type === 'conversation') {
                    // Direct conversation
                    const $item = $('<div class="sh-conversation-item"></div>');
                    $item.html(`
                        <div class="sh-user-avatar">
                            <img src="${item.avatar_url}" alt="${this.escapeHtml(item.name)}" style="width: 40px; height: 40px; border-radius: 50%;">
                        </div>
                        <div class="sh-conversation-content">
                            <div class="sh-conversation-name">${this.escapeHtml(item.name)}</div>
                            <div class="sh-conversation-time">${this.formatTime(item.last_activity)}</div>
                        </div>
                        ${unreadBadge}
                    `);
                    $item.data('userId', item.user_id);
                    $item.data('userName', item.name);
                    $list.append($item);
                } else if (item.type === 'group') {
                    // Group conversation
                    const $item = $('<div class="sh-group-item"></div>');
                    $item.html(`
                        <div class="sh-group-icon">
                            <span class="dashicons dashicons-groups"></span>
                        </div>
                        <div class="sh-group-content">
                            <div class="sh-group-name">${this.escapeHtml(item.name)}</div>
                            <div class="sh-conversation-time">${this.formatTime(item.last_activity)}</div>
                        </div>
                        ${unreadBadge}
                    `);
                    $item.data('groupId', item.group_id);
                    $item.data('groupName', item.name);
                    $list.append($item);
                }
            });
        },
        
        /**
         * Toggle chat panel
         */
        togglePanel: function() {
            if (this.isOpen) {
                this.closePanel();
            } else {
                this.openPanel();
            }
        },
        
        /**
         * Open chat panel
         */
        openPanel: function() {
            $('#sourcehub-chat-panel').addClass('open');
            this.isOpen = true;
            this.switchView('inbox');
        },
        
        /**
         * Close chat panel
         */
        closePanel: function() {
            $('#sourcehub-chat-panel').removeClass('open');
            this.isOpen = false;
        },
        
        /**
         * Switch view
         */
        switchView: function(view) {
            this.currentView = view;
            
            // Update active tab
            $('.sh-nav-btn').removeClass('active');
            $('.sh-nav-btn[data-view="' + view + '"]').addClass('active');
            
            // Hide all views
            $('.sh-view').hide();
            
            // Show selected view
            switch(view) {
                case 'inbox':
                    $('#sh-inbox-view').show();
                    this.loadInbox();
                    break;
                case 'groups':
                    $('#sh-groups-view').show();
                    this.loadGroups();
                    break;
                case 'online':
                    $('#sh-online-view').show();
                    this.loadOnlineUsers();
                    break;
                case 'conversation':
                    $('#sh-conversation-view').show();
                    this.loadConversation();
                    break;
                case 'group':
                    $('#sh-group-view').show();
                    this.loadGroupMessages();
                    break;
                case 'new-group':
                    $('#sh-new-group-view').show();
                    this.loadAllUsers();
                    break;
            }
        },
        
        /**
         * Load inbox (recent conversations)
         */
        loadInbox: function() {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_get_messages',
                    nonce: sourcehubMessaging.nonce,
                    unread_only: false
                },
                success: (response) => {
                    if (response.success) {
                        this.renderInbox(response.data.messages);
                    }
                }
            });
        },
        
        /**
         * Render inbox
         */
        renderInbox: function(messages) {
            const $list = $('#sh-inbox-list');
            $list.empty();
            
            if (!messages || messages.length === 0) {
                $list.html('<div class="sh-empty">No messages yet</div>');
                return;
            }
            
            // Group by conversation
            const conversations = {};
            messages.forEach(msg => {
                const key = msg.to_user_id ? `user_${msg.from_user_id}` : `group_${msg.group_id}`;
                if (!conversations[key]) {
                    conversations[key] = [];
                }
                conversations[key].push(msg);
            });
            
            // Render each conversation
            Object.keys(conversations).forEach(key => {
                const msgs = conversations[key];
                const latest = msgs[0];
                const unread = msgs.filter(m => !m.is_read && !m.read_at).length;
                
                const $item = $('<div class="sh-conversation-item"></div>');
                $item.html(`
                    <div class="sh-conv-avatar">
                        <span class="dashicons dashicons-${latest.group_id ? 'groups' : 'admin-users'}"></span>
                    </div>
                    <div class="sh-conv-content">
                        <div class="sh-conv-name">${latest.group_id ? 'Group' : latest.from_user_name}</div>
                        <div class="sh-conv-preview">${this.truncate(latest.message, 50)}</div>
                    </div>
                    ${unread > 0 ? `<div class="sh-conv-badge">${unread}</div>` : ''}
                `);
                
                $item.data('userId', latest.from_user_id);
                $item.data('groupId', latest.group_id);
                
                $item.on('click', () => {
                    if (latest.group_id) {
                        this.openGroup(latest.group_id);
                    } else {
                        this.startConversation(latest.from_user_id);
                    }
                });
                
                $list.append($item);
            });
        },
        
        /**
         * Start conversation with user
         */
        startConversation: function(e) {
            const userId = $(e.currentTarget).data('userId') || e;
            const userName = $(e.currentTarget).data('userName') || 'User';
            
            this.currentConversation = userId;
            this.currentGroup = null;
            
            $('#sh-conversation-title').text(userName);
            this.switchView('conversation');
        },
        
        /**
         * Load conversation messages
         */
        loadConversation: function() {
            this.messageOffset = 0;
            this.hasMoreMessages = true;
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_get_messages',
                    nonce: sourcehubMessaging.nonce,
                    conversation_user_id: this.currentConversation,
                    offset: 0
                },
                success: (response) => {
                    if (response.success) {
                        this.renderMessages(response.data.messages);
                        this.markAsRead(null, this.currentConversation, null);
                        this.messageOffset = response.data.messages.length;
                        if (response.data.messages.length < 50) {
                            this.hasMoreMessages = false;
                        }
                    }
                }
            });
        },
        
        /**
         * Open group
         */
        openGroup: function(groupIdOrEvent, groupName) {
            let groupId, name;
            
            // Check if it's an event object or direct parameters
            if (typeof groupIdOrEvent === 'object' && groupIdOrEvent.currentTarget) {
                groupId = $(groupIdOrEvent.currentTarget).data('groupId');
                name = $(groupIdOrEvent.currentTarget).data('groupName') || 'Group';
            } else {
                groupId = groupIdOrEvent;
                name = groupName || 'Group';
            }
            
            this.currentGroup = groupId;
            this.currentConversation = null;
            
            $('#sh-group-title').text(name);
            this.switchView('group');
        },
        
        /**
         * Load group messages
         */
        loadGroupMessages: function() {
            this.messageOffset = 0;
            this.hasMoreMessages = true;
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_get_messages',
                    nonce: sourcehubMessaging.nonce,
                    group_id: this.currentGroup,
                    offset: 0
                },
                success: (response) => {
                    if (response.success) {
                        this.renderMessages(response.data.messages);
                        this.markAsRead(null, null, this.currentGroup);
                        this.messageOffset = response.data.messages.length;
                        if (response.data.messages.length < 50) {
                            this.hasMoreMessages = false;
                        }
                    }
                }
            });
        },
        
        /**
         * Load older messages (infinite scroll)
         */
        loadOlderMessages: function() {
            if (this.isLoadingMore || !this.hasMoreMessages) {
                return;
            }
            
            this.isLoadingMore = true;
            const $list = this.currentGroup ? $('#sh-group-messages-list') : $('#sh-messages-list');
            const oldScrollHeight = $list[0].scrollHeight;
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_get_messages',
                    nonce: sourcehubMessaging.nonce,
                    conversation_user_id: this.currentConversation,
                    group_id: this.currentGroup,
                    offset: this.messageOffset
                },
                success: (response) => {
                    if (response.success && response.data.messages.length > 0) {
                        // Prepend older messages
                        response.data.messages.reverse().forEach(msg => {
                            const isOwn = msg.from_user_id == sourcehubMessaging.currentUserId;
                            const $msg = $('<div class="sh-message"></div>');
                            $msg.addClass(isOwn ? 'sh-message-own' : 'sh-message-other');
                            
                            const messageText = this.linkify(this.escapeHtml(msg.message));
                            
                            let html = `
                                <div class="sh-message-header">
                                    <span class="sh-message-author">${this.escapeHtml(msg.from_user_name)}</span>
                                    <span class="sh-message-time">${this.formatTime(msg.created_at)}</span>
                                </div>
                                <div class="sh-message-body">${messageText}</div>
                            `;
                            
                            if (msg.attachment) {
                                if (msg.attachment.type.startsWith('image/')) {
                                    html += `<div class="sh-message-attachment">
                                        <a href="${msg.attachment.url}" target="_blank">
                                            <img src="${msg.attachment.url}" alt="${msg.attachment.filename}" style="max-width: 300px; max-height: 300px; cursor: pointer;">
                                        </a>
                                    </div>`;
                                } else {
                                    html += `<div class="sh-message-attachment">
                                        <a href="${msg.attachment.url}" target="_blank">
                                            <span class="dashicons dashicons-media-default"></span>
                                            ${msg.attachment.filename}
                                        </a>
                                    </div>`;
                                }
                            }
                            
                            $msg.html(html);
                            $list.prepend($msg);
                            
                            // Don't auto-fetch link previews for older messages to avoid scroll jumps
                            // Users can scroll down to see the full conversation with previews
                        });
                        
                        // Maintain scroll position
                        const newScrollHeight = $list[0].scrollHeight;
                        $list.scrollTop(newScrollHeight - oldScrollHeight);
                        
                        this.messageOffset += response.data.messages.length;
                        if (response.data.messages.length < 50) {
                            this.hasMoreMessages = false;
                        }
                    } else {
                        this.hasMoreMessages = false;
                    }
                    this.isLoadingMore = false;
                },
                error: () => {
                    this.isLoadingMore = false;
                }
            });
        },
        
        /**
         * Render messages
         */
        renderMessages: function(messages, skipScroll = false) {
            // Select the correct list based on current view
            const $list = this.currentGroup ? $('#sh-group-messages-list') : $('#sh-messages-list');
            $list.empty();
            
            if (!messages || messages.length === 0) {
                $list.html('<div class="sh-empty">No messages yet</div>');
                return;
            }
            
            // Reverse to show oldest first (top) and newest last (bottom)
            messages.reverse().forEach(msg => {
                const isOwn = msg.from_user_id == sourcehubMessaging.currentUserId;
                const $msg = $('<div class="sh-message"></div>');
                $msg.addClass(isOwn ? 'sh-message-own' : 'sh-message-other');
                
                // Escape HTML first, then linkify URLs
                const messageText = this.linkify(this.escapeHtml(msg.message));
                
                let html = `
                    <div class="sh-message-header">
                        <span class="sh-message-author">${msg.from_user_name}</span>
                        <span class="sh-message-time">${this.formatTime(msg.created_at)}</span>
                    </div>
                    <div class="sh-message-body">${messageText}</div>
                `;
                
                // Add attachment if present
                if (msg.attachment) {
                    if (msg.attachment.type.startsWith('image/')) {
                        html += `<div class="sh-message-attachment">
                            <a href="${msg.attachment.url}" target="_blank">
                                <img src="${msg.attachment.url}" alt="${msg.attachment.filename}" style="max-width: 300px; max-height: 300px; cursor: pointer;">
                            </a>
                        </div>`;
                    } else {
                        html += `<div class="sh-message-attachment">
                            <a href="${msg.attachment.url}" target="_blank">
                                <span class="dashicons dashicons-media-default"></span>
                                ${msg.attachment.filename}
                            </a>
                        </div>`;
                    }
                }
                
                // Add reactions
                html += this.renderReactions(msg.id, msg.reactions || {});
                
                $msg.html(html);
                $msg.attr('data-message-id', msg.id);
                $list.append($msg);
                
                // Auto-fetch link previews for URLs in message
                const urls = this.extractUrls(msg.message);
                urls.forEach(url => {
                    const $preview = $msg.find(`.sh-link-preview[data-url="${url}"]`);
                    if ($preview.length) {
                        // Check if we have cached OG data for this URL
                        if (this.ogDataCache[url]) {
                            // Render from cache immediately
                            this.renderLinkPreview(this.ogDataCache[url], $preview);
                            $preview.data('loaded', true);
                        } else if (!$preview.data('loaded')) {
                            // Fetch if not in cache and not already loaded
                            this.autoFetchLinkPreview(url, $preview);
                        }
                    }
                });
            });
            
            // Auto-scroll to bottom ONLY on initial load (not during infinite scroll)
            if (!skipScroll) {
                const scrollToBottom = () => {
                    const element = $list[0];
                    if (element) {
                        element.scrollTop = element.scrollHeight;
                    }
                };
                
                // Immediate scroll
                scrollToBottom();
                
                // Delayed scrolls to ensure images/previews are loaded
                setTimeout(scrollToBottom, 50);
                setTimeout(scrollToBottom, 150);
                setTimeout(scrollToBottom, 300);
                setTimeout(scrollToBottom, 500);
            }
        },
        
        /**
         * Send message
         */
        sendMessage: function() {
            // Find the visible message input (conversation or group view)
            let $input;
            if (this.currentConversation) {
                $input = $('#sh-conversation-view #sh-message-input');
            } else if (this.currentGroup) {
                $input = $('#sh-group-view #sh-message-input');
            } else {
                alert('Please select a user or group to message first');
                return;
            }
            
            const message = $input.val().trim();
            const attachmentId = $input.data('attachmentId');
            
            // Require either message or attachment
            if (!message && !attachmentId) {
                alert('Please enter a message or attach a file');
                return;
            }
            
            const data = {
                action: 'sourcehub_send_message',
                nonce: sourcehubMessaging.nonce,
                message: message || ''
            };
            
            if (this.currentConversation) {
                data.to_user_id = this.currentConversation;
            } else if (this.currentGroup) {
                data.group_id = this.currentGroup;
            }
            
            // Add attachment if uploaded
            if (attachmentId) {
                data.attachment_id = attachmentId;
            }
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: data,
                success: (response) => {
                    if (response.success) {
                        // Clear input and attachment data
                        $input.val('').data('attachmentId', null);
                        
                        // Clear attachment preview in the correct view
                        if (this.currentConversation) {
                            $('#sh-conversation-view #sh-attachment-preview').hide().empty();
                            $('#sh-conversation-view #sh-file-upload').val('');
                        } else if (this.currentGroup) {
                            $('#sh-group-view #sh-attachment-preview').hide().empty();
                            $('#sh-group-view #sh-file-upload').val('');
                        }
                        
                        // Reload messages
                        if (this.currentConversation) {
                            this.loadConversation();
                        } else if (this.currentGroup) {
                            this.loadGroupMessages();
                        }
                    }
                }
            });
        },
        
        /**
         * Handle file upload
         */
        handleFileUpload: function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            // Determine which view we're in
            let $input, $preview, $fileInput;
            if (this.currentConversation) {
                $input = $('#sh-conversation-view #sh-message-input');
                $preview = $('#sh-conversation-view #sh-attachment-preview');
                $fileInput = $('#sh-conversation-view #sh-file-upload');
            } else if (this.currentGroup) {
                $input = $('#sh-group-view #sh-message-input');
                $preview = $('#sh-group-view #sh-attachment-preview');
                $fileInput = $('#sh-group-view #sh-file-upload');
            } else {
                return;
            }
            
            const formData = new FormData();
            formData.append('file', file);
            formData.append('action', 'sourcehub_upload_attachment');
            formData.append('nonce', sourcehubMessaging.nonce);
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: (response) => {
                    console.log('Upload response:', response);
                    if (response.success) {
                        const attachmentId = response.data.id;
                        const attachmentUrl = response.data.url;
                        
                        $input.data('attachmentId', attachmentId);
                        
                        // Show preview
                        $preview.html(`
                            <img src="${attachmentUrl}" style="max-width: 100px; max-height: 100px;">
                            <button type="button" class="sh-remove-attachment">×</button>
                        `).show();
                        
                        $('.sh-remove-attachment').on('click', function() {
                            $input.data('attachmentId', null);
                            $preview.hide().empty();
                            $fileInput.val('');
                        });
                    } else {
                        console.error('Upload failed:', response);
                        const errorMsg = response.data && response.data.message ? response.data.message : 'File upload failed. Please try again.';
                        alert(errorMsg);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Upload error:', xhr.responseText, status, error);
                    alert('File upload failed: ' + error);
                }
            });
        },
        
        /**
         * Load groups
         */
        loadGroups: function() {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_get_groups',
                    nonce: sourcehubMessaging.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.renderGroups(response.data.groups);
                    }
                }
            });
        },
        
        /**
         * Render groups
         */
        renderGroups: function(groups) {
            const $list = $('#sh-groups-list');
            $list.empty();
            
            if (!groups || groups.length === 0) {
                $list.html('<div class="sh-empty">No groups yet. <a href="#" class="sh-create-group">Create one!</a></div>');
                return;
            }
            
            groups.forEach(group => {
                const $item = $('<div class="sh-group-item"></div>');
                const unreadBadge = group.unread_count > 0 ? `<div class="sh-conv-badge">${group.unread_count}</div>` : '';
                
                $item.html(`
                    <div class="sh-group-icon">
                        <span class="dashicons dashicons-groups"></span>
                    </div>
                    <div class="sh-group-content">
                        <div class="sh-group-name">${this.escapeHtml(group.name)}</div>
                        <div class="sh-group-meta">${group.member_count} members</div>
                    </div>
                    ${unreadBadge}
                    <button class="sh-group-menu-btn" data-group-id="${group.id}" data-group-name="${this.escapeHtml(group.name)}">
                        <span class="dashicons dashicons-ellipsis"></span>
                    </button>
                `);
                
                $item.data('groupId', group.id);
                $item.data('groupName', group.name);
                
                $list.append($item);
            });
        },
        
        /**
         * Load online users
         */
        loadOnlineUsers: function() {
            console.log('SourceHub: Loading online users...');
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_get_online_users',
                    nonce: sourcehubMessaging.nonce
                },
                success: (response) => {
                    console.log('SourceHub: Online users response:', response);
                    if (response.success) {
                        console.log('SourceHub: Found', response.data.users.length, 'online users');
                        this.renderOnlineUsers(response.data.users);
                    } else {
                        console.error('SourceHub: Failed to load online users');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('SourceHub: Error loading online users', error);
                }
            });
        },
        
        /**
         * Render online users
         */
        renderOnlineUsers: function(users) {
            const $list = $('#sh-online-list');
            $list.empty();
            
            if (!users || users.length === 0) {
                $list.html('<div class="sh-empty">No users available</div>');
                return;
            }
            
            // Separate online and offline users
            const onlineUsers = users.filter(u => u.is_online);
            const offlineUsers = users.filter(u => !u.is_online);
            
            // Add ONLINE section
            if (onlineUsers.length > 0) {
                $list.append('<div class="sh-user-section-header">ONLINE</div>');
                onlineUsers.forEach(user => {
                    const $item = $('<div class="sh-user-item"></div>');
                    $item.html(`
                        <div class="sh-user-avatar">
                            <img src="${user.avatar_url}" alt="${this.escapeHtml(user.display_name)}" style="width: 40px; height: 40px; border-radius: 50%;">
                            <span class="sh-online-dot"></span>
                        </div>
                        <div class="sh-user-name">${this.escapeHtml(user.display_name)}</div>
                    `);
                    $item.data('userId', user.ID);
                    $item.data('userName', user.display_name);
                    $list.append($item);
                });
            }
            
            // Add OFFLINE section
            if (offlineUsers.length > 0) {
                $list.append('<div class="sh-user-section-header">OFFLINE</div>');
                offlineUsers.forEach(user => {
                    const $item = $('<div class="sh-user-item"></div>');
                    $item.html(`
                        <div class="sh-user-avatar sh-user-avatar-offline">
                            <img src="${user.avatar_url}" alt="${this.escapeHtml(user.display_name)}" style="width: 40px; height: 40px; border-radius: 50%;">
                        </div>
                        <div class="sh-user-name sh-user-name-offline">${this.escapeHtml(user.display_name)}</div>
                    `);
                    $item.data('userId', user.ID);
                    $item.data('userName', user.display_name);
                    $list.append($item);
                });
            }
        },
        
        /**
         * Load all users for group creation
         */
        loadAllUsers: function() {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_get_all_users',
                    nonce: sourcehubMessaging.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.renderUserCheckboxes(response.data.users);
                    }
                }
            });
        },
        
        /**
         * Render user checkboxes for group creation
         */
        renderUserCheckboxes: function(users) {
            const $list = $('#sh-group-members-list');
            $list.empty();
            
            users.forEach(user => {
                const $item = $('<label class="sh-user-checkbox"></label>');
                $item.html(`
                    <input type="checkbox" name="group_members[]" value="${user.ID}">
                    <span>${this.escapeHtml(user.display_name)}</span>
                `);
                $list.append($item);
            });
        },
        
        /**
         * Create group
         */
        createGroup: function() {
            console.log('SourceHub: createGroup called');
            const name = $('#sh-group-name').val().trim();
            const description = $('#sh-group-description').val().trim();
            const memberIds = [];
            
            $('input[name="group_members[]"]:checked').each(function() {
                memberIds.push($(this).val());
            });
            
            console.log('SourceHub: Group data:', { name, description, memberIds });
            
            if (!name) {
                alert('Please enter a group name');
                return;
            }
            
            console.log('SourceHub: Sending create group AJAX request');
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_create_group',
                    nonce: sourcehubMessaging.nonce,
                    name: name,
                    description: description,
                    member_ids: memberIds
                },
                success: (response) => {
                    console.log('SourceHub: Create group response:', response);
                    if (response.success) {
                        this.switchView('groups');
                        this.loadGroups();
                        $('#sh-group-name, #sh-group-description').val('');
                    } else {
                        console.error('SourceHub: Create group failed:', response);
                        alert('Failed to create group: ' + (response.data?.message || 'Unknown error'));
                    }
                },
                error: (xhr, status, error) => {
                    console.error('SourceHub: Create group AJAX error:', { xhr, status, error });
                    alert('Failed to create group. Please try again.');
                }
            });
        },
        
        /**
         * Mark messages as read
         */
        markAsRead: function(messageId, conversationUserId, groupId) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_mark_read',
                    nonce: sourcehubMessaging.nonce,
                    message_id: messageId,
                    conversation_user_id: conversationUserId,
                    group_id: groupId
                },
                success: (response) => {
                    if (response.success && response.data.unread_count !== undefined) {
                        // Update unread count
                        this.unreadCount = response.data.unread_count;
                        
                        // Update badge
                        const $badge = $('#sourcehub-chat-badge');
                        if (this.unreadCount > 0) {
                            $badge.text(this.unreadCount).show();
                        } else {
                            $badge.hide();
                        }
                        
                        // Refresh conversations list to update badges
                        if (this.currentView === 'inbox') {
                            this.loadConversations();
                        }
                    }
                }
            });
        },
        
        /**
         * Check unread count
         */
        checkUnreadCount: function() {
            // Will be updated via heartbeat
        },
        
        /**
         * Handle new messages from heartbeat
         */
        handleNewMessages: function(data) {
            console.log('SourceHub: handleNewMessages called', data);
            const oldCount = this.unreadCount;
            this.unreadCount = data.unread_count;
            
            // Update badge
            const $badge = $('#sourcehub-chat-badge');
            if (this.unreadCount > 0) {
                $badge.text(this.unreadCount).show();
            } else {
                $badge.hide();
            }
            
            // Show notification if new messages
            console.log('SourceHub: Checking notification conditions:', {
                hasNewMessages: data.new_messages && data.new_messages.length > 0,
                unreadCount: this.unreadCount,
                oldCount: oldCount,
                shouldShow: data.new_messages && data.new_messages.length > 0 && this.unreadCount > oldCount
            });
            
            if (data.new_messages && data.new_messages.length > 0 && this.unreadCount > oldCount) {
                console.log('SourceHub: Showing notification for message:', data.new_messages[0]);
                this.showNotification(data.new_messages[0]);
            }
            
            // Refresh current view if open
            if (this.isOpen) {
                if (this.currentView === 'conversation' && this.currentConversation) {
                    this.loadConversation();
                } else if (this.currentView === 'group' && this.currentGroup) {
                    this.loadGroupMessages();
                } else if (this.currentView === 'inbox') {
                    this.loadInbox();
                }
            }
        },
        
        /**
         * Show notification
         */
        showNotification: function(message) {
            try {
                console.log('SourceHub: showNotification called with:', message);
                console.log('SourceHub: group_id:', message.group_id);
                console.log('SourceHub: group_name:', message.group_name);
                
                // Play sound based on message type
                const isGroup = message.group_id ? true : false;
                this.playSound(isGroup);
                
                const $notification = $('<div class="sh-notification"></div>');
                
                // Build header with group name if it's a group message
                let header = `<strong>${this.escapeHtml(message.from_user_name)}</strong>`;
                if (isGroup && message.group_name) {
                    header += ` <span style="color: #646970;">in ${this.escapeHtml(message.group_name)}</span>`;
                } else if (isGroup && !message.group_name) {
                    console.warn('SourceHub: Group message but no group_name!');
                }
                
                console.log('SourceHub: Notification header:', header);
                
                // Build notification HTML with optional image
                let notificationHTML = `
                    <div class="sh-notif-header">
                        ${header}
                    </div>
                `;
                
                // Add image thumbnail if message has attachment
                if (message.attachment && message.attachment.url) {
                    notificationHTML += `
                        <div class="sh-notif-image">
                            <img src="${message.attachment.url}" alt="Attachment">
                        </div>
                    `;
                }
                
                // Add message text
                notificationHTML += `
                    <div class="sh-notif-body">${this.truncate(this.escapeHtml(message.message), 100)}</div>
                `;
                
                $notification.html(notificationHTML);
                
                $notification.on('click', () => {
                    // Open panel first
                    this.openPanel();
                    
                    // Then open the specific group or conversation
                    if (message.group_id) {
                        this.openGroup(message.group_id, message.group_name);
                    } else {
                        this.startConversation(message.from_user_id);
                    }
                    
                    $notification.fadeOut(() => $notification.remove());
                });
                
                console.log('SourceHub: Appending notification to body');
                $('body').append($notification);
                
                setTimeout(() => {
                    $notification.addClass('show');
                    console.log('SourceHub: Notification shown');
                }, 100);
                
                setTimeout(() => {
                    $notification.fadeOut(() => $notification.remove());
                }, 5000);
            } catch (error) {
                console.error('SourceHub: Error showing notification:', error);
            }
        },
        
        /**
         * Update user activity
         */
        updateActivity: function() {
            console.log('SourceHub: Updating user activity...');
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_update_activity',
                    nonce: sourcehubMessaging.nonce
                },
                success: function(response) {
                    console.log('SourceHub: Activity updated successfully', response);
                },
                error: function(xhr, status, error) {
                    console.error('SourceHub: Activity update failed', error);
                }
            });
        },
        
        /**
         * Show group menu
         */
        showGroupMenu: function(groupId, groupName, e) {
            // Show modal for editing group
            this.currentEditGroupId = groupId;
            $('#sh-edit-group-name').val(groupName);
            
            // Load group members
            this.loadGroupMembers(groupId);
            
            // Load all users for add member dropdown
            this.loadUsersForAddMember(groupId);
            
            $('#sh-edit-group-modal').addClass('show');
        },
        
        /**
         * Load group members for editing
         */
        loadGroupMembers: function(groupId) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_get_group_members',
                    nonce: sourcehubMessaging.nonce,
                    group_id: groupId
                },
                success: (response) => {
                    if (response.success) {
                        this.renderGroupMembers(response.data.members);
                    }
                }
            });
        },
        
        /**
         * Render group members in edit modal
         */
        renderGroupMembers: function(members) {
            const $list = $('#sh-edit-group-members-list');
            $list.empty();
            
            if (!members || members.length === 0) {
                $list.html('<div class="sh-empty">No members</div>');
                return;
            }
            
            members.forEach(member => {
                const $item = $('<div class="sh-member-item"></div>');
                $item.html(`
                    <span class="sh-member-name">${this.escapeHtml(member.display_name)}</span>
                    <button type="button" class="sh-remove-member-btn" data-user-id="${member.user_id}">Remove</button>
                `);
                $list.append($item);
            });
        },
        
        /**
         * Load users for add member dropdown
         */
        loadUsersForAddMember: function(groupId) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_get_all_users',
                    nonce: sourcehubMessaging.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.populateAddMemberDropdown(response.data.users, groupId);
                    }
                }
            });
        },
        
        /**
         * Populate add member dropdown
         */
        populateAddMemberDropdown: function(allUsers, groupId) {
            const $select = $('#sh-add-member-select');
            $select.empty().append('<option value="">Select a user to add...</option>');
            
            // Get current members to filter them out
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_get_group_members',
                    nonce: sourcehubMessaging.nonce,
                    group_id: groupId
                },
                success: (response) => {
                    if (response.success) {
                        const memberIds = response.data.members.map(m => m.ID);
                        
                        allUsers.forEach(user => {
                            if (!memberIds.includes(user.ID)) {
                                $select.append(`<option value="${user.ID}">${this.escapeHtml(user.display_name)}</option>`);
                            }
                        });
                    }
                }
            });
        },
        
        /**
         * Update group name
         */
        updateGroupName: function(groupId, newName) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_update_group_name',
                    nonce: sourcehubMessaging.nonce,
                    group_id: groupId,
                    name: newName
                },
                success: (response) => {
                    if (response.success) {
                        this.loadGroups();
                        this.loadConversations();
                        this.showNotification('Group Updated', 'Group name has been updated successfully.');
                    } else {
                        this.showNotification('Error', 'Failed to update group name: ' + (response.data.message || 'Unknown error'));
                    }
                },
                error: () => {
                    this.showNotification('Error', 'Failed to update group name. Please try again.');
                }
            });
        },
        
        /**
         * Add member to group
         */
        addGroupMember: function(groupId, userId) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_add_group_member',
                    nonce: sourcehubMessaging.nonce,
                    group_id: groupId,
                    user_id: userId
                },
                success: (response) => {
                    if (response.success) {
                        this.loadGroupMembers(groupId);
                        this.loadUsersForAddMember(groupId);
                        this.showNotification('Member Added', 'Member has been added to the group.');
                    } else {
                        this.showNotification('Error', 'Failed to add member: ' + (response.data.message || 'Unknown error'));
                    }
                },
                error: () => {
                    this.showNotification('Error', 'Failed to add member. Please try again.');
                }
            });
        },
        
        /**
         * Remove member from group
         */
        removeGroupMember: function(groupId, userId) {
            console.log('Removing member:', { groupId, userId });
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_remove_group_member',
                    nonce: sourcehubMessaging.nonce,
                    group_id: groupId,
                    user_id: userId
                },
                success: (response) => {
                    console.log('Remove member response:', response);
                    if (response.success) {
                        this.loadGroupMembers(groupId);
                        this.loadUsersForAddMember(groupId);
                        this.showNotification('Member Removed', 'Member has been removed from the group.');
                    } else {
                        console.error('Remove failed:', response);
                        this.showNotification('Error', 'Failed to remove member: ' + (response.data.message || 'Unknown error'));
                    }
                },
                error: (xhr, status, error) => {
                    console.error('Remove member AJAX error:', { xhr, status, error });
                    this.showNotification('Error', 'Failed to remove member. Please try again.');
                }
            });
        },
        
        /**
         * Leave group
         */
        leaveGroup: function(groupId) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_leave_group',
                    nonce: sourcehubMessaging.nonce,
                    group_id: groupId
                },
                success: (response) => {
                    if (response.success) {
                        $('#sh-edit-group-modal').removeClass('show');
                        this.loadGroups();
                        this.loadConversations();
                        this.switchView('inbox');
                        this.showNotification('Left Group', 'You have left the group.');
                    } else {
                        this.showNotification('Error', 'Failed to leave group: ' + (response.data.message || 'Unknown error'));
                    }
                },
                error: () => {
                    this.showNotification('Error', 'Failed to leave group. Please try again.');
                }
            });
        },
        
        /**
         * Auto-fetch link preview (OG tags) - no button required
         */
        autoFetchLinkPreview: function(url, $preview) {
            // Check if we've already fetched this URL
            if (this.loadedPreviews.has(url)) {
                return;
            }
            
            // Mark as being fetched
            this.loadedPreviews.add(url);
            
            // Show loading state
            $preview.html('<div class="sh-preview-loading">Loading preview...</div>').addClass('show');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_fetch_og_tags',
                    nonce: sourcehubMessaging.nonce,
                    url: url
                },
                success: (response) => {
                    if (response.success && response.data.og_data) {
                        // Cache the OG data for this URL
                        this.ogDataCache[url] = response.data.og_data;
                        this.renderLinkPreview(response.data.og_data, $preview);
                        $preview.data('loaded', true);
                    } else {
                        // If no OG data, just hide the preview
                        $preview.removeClass('show').empty();
                    }
                },
                error: () => {
                    // On error, just hide the preview
                    $preview.removeClass('show').empty();
                }
            });
        },
        
        /**
         * Render link preview card
         */
        renderLinkPreview: function(ogData, $preview) {
            let html = '<div class="sh-preview-card">';
            
            if (ogData.image) {
                html += `<div class="sh-preview-image">
                    <img src="${ogData.image}" alt="${this.escapeHtml(ogData.title || '')}" />
                </div>`;
            }
            
            html += '<div class="sh-preview-content">';
            
            if (ogData.title) {
                html += `<div class="sh-preview-title">${this.escapeHtml(ogData.title)}</div>`;
            }
            
            if (ogData.description) {
                html += `<div class="sh-preview-description">${this.escapeHtml(ogData.description)}</div>`;
            }
            
            if (ogData.site_name) {
                html += `<div class="sh-preview-site">${this.escapeHtml(ogData.site_name)}</div>`;
            } else if (ogData.url) {
                // Extract domain from URL
                try {
                    const domain = new URL(ogData.url).hostname;
                    html += `<div class="sh-preview-site">${domain}</div>`;
                } catch (e) {
                    // Invalid URL, skip
                }
            }
            
            html += '</div></div>';
            
            $preview.html(html).addClass('show');
        },
        
        /**
         * Go back
         */
        goBack: function() {
            this.currentConversation = null;
            this.currentGroup = null;
            this.switchView('inbox');
        },
        
        /**
         * Utility: Truncate text
         */
        truncate: function(text, length) {
            if (text.length <= length) return text;
            return text.substring(0, length) + '...';
        },
        
        /**
         * Play notification sound
         */
        playSound: function(isGroup) {
            const soundFile = isGroup ? 'groupmessage.mp3' : 'individualmessage.mp3';
            const soundUrl = sourcehubMessaging.pluginUrl + '/admin/sounds/' + soundFile;
            
            const audio = new Audio(soundUrl);
            audio.volume = 0.5; // 50% volume
            audio.play().catch(err => {
                console.log('Could not play sound:', err);
            });
        },
        
        /**
         * Utility: Escape HTML
         */
        escapeHtml: function(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        },
        
        /**
         * Utility: Convert URLs to clickable links with preview container
         */
        linkify: function(text) {
            const urlRegex = /(https?:\/\/[^\s]+)/g;
            return text.replace(urlRegex, (url) => {
                return `<div class="sh-link-container">
                    <a href="${url}" target="_blank" rel="noopener noreferrer">${url}</a>
                    <div class="sh-link-preview" data-url="${url}"></div>
                </div>`;
            });
        },
        
        /**
         * Extract URLs from text
         */
        extractUrls: function(text) {
            const urlRegex = /(https?:\/\/[^\s]+)/g;
            return text.match(urlRegex) || [];
        },
        
        /**
         * Utility: Format time
         */
        formatTime: function(datetime) {
            const date = new Date(datetime);
            const now = new Date();
            const diff = now - date;
            
            // Less than 1 minute
            if (diff < 60000) {
                return 'Just now';
            }
            
            // Less than 1 hour
            if (diff < 3600000) {
                const mins = Math.floor(diff / 60000);
                return mins + 'm ago';
            }
            
            // Less than 24 hours
            if (diff < 86400000) {
                const hours = Math.floor(diff / 3600000);
                return hours + 'h ago';
            }
            
            // Format as date
            return date.toLocaleDateString();
        },
        
        /**
         * Render reactions for a message
         */
        renderReactions: function(messageId, reactions) {
            const reactionEmojis = {
                'thumbs_up': '👍',
                'thumbs_down': '👎',
                'heart': '❤️',
                'laugh': '😂',
                'celebrate': '🎉',
                'thinking': '🤔'
            };
            
            let html = '<div class="sh-message-reactions">';
            
            // Reaction picker (shows on hover)
            html += '<div class="sh-reaction-picker">';
            Object.keys(reactionEmojis).forEach(type => {
                html += `<span class="sh-reaction-option" data-reaction="${type}">${reactionEmojis[type]}</span>`;
            });
            // Add 3-dot menu button
            html += `<span class="sh-message-menu-btn" data-message-id="${messageId}">⋯</span>`;
            html += '</div>';
            
            // Message actions dropdown (hidden by default)
            html += `<div class="sh-message-menu" data-message-id="${messageId}">
                <div class="sh-message-menu-item sh-copy-text" data-message-id="${messageId}">
                    <span class="dashicons dashicons-admin-page"></span> Copy Text
                </div>
                <div class="sh-message-menu-item sh-delete-message" data-message-id="${messageId}">
                    <span class="dashicons dashicons-trash"></span> Delete Message
                </div>
            </div>`;
            
            // Existing reactions display
            html += '<div class="sh-reactions-display">';
            if (reactions && Object.keys(reactions).length > 0) {
                Object.keys(reactions).forEach(type => {
                    const reaction = reactions[type];
                    const emoji = reactionEmojis[type] || type;
                    const currentUserId = sourcehubMessaging.currentUserId;
                    const userReacted = reaction.users.some(u => u.user_id == currentUserId);
                    const className = userReacted ? 'sh-reaction-bubble sh-user-reacted' : 'sh-reaction-bubble';
                    const userNames = reaction.users.map(u => u.display_name).join(', ');
                    
                    html += `<span class="${className}" data-reaction="${type}" data-message-id="${messageId}" title="${userNames}">
                        ${emoji} ${reaction.count}
                    </span>`;
                });
            }
            html += '</div>';
            html += '</div>';
            
            return html;
        },
        
        /**
         * Add reaction to message
         */
        addReaction: function(messageId, reactionType) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_add_reaction',
                    nonce: sourcehubMessaging.nonce,
                    message_id: messageId,
                    reaction_type: reactionType
                },
                success: (response) => {
                    if (response.success) {
                        this.updateMessageReactions(messageId, response.data.reactions);
                    }
                }
            });
        },
        
        /**
         * Remove reaction from message
         */
        removeReaction: function(messageId, reactionType) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_remove_reaction',
                    nonce: sourcehubMessaging.nonce,
                    message_id: messageId,
                    reaction_type: reactionType
                },
                success: (response) => {
                    if (response.success) {
                        this.updateMessageReactions(messageId, response.data.reactions);
                    }
                }
            });
        },
        
        /**
         * Update reactions display for a message
         */
        updateMessageReactions: function(messageId, reactions) {
            const $message = $(`.sh-message[data-message-id="${messageId}"]`);
            if ($message.length) {
                const $reactionsContainer = $message.find('.sh-message-reactions');
                const newHtml = this.renderReactions(messageId, reactions);
                $reactionsContainer.replaceWith(newHtml);
            }
        },
        
        /**
         * Delete message
         */
        deleteMessage: function(messageId) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sourcehub_delete_message',
                    nonce: sourcehubMessaging.nonce,
                    message_id: messageId
                },
                success: (response) => {
                    if (response.success) {
                        // Remove message from UI
                        $(`.sh-message[data-message-id="${messageId}"]`).fadeOut(300, function() {
                            $(this).remove();
                        });
                        
                        // Reload conversation to update counts
                        if (this.currentConversation) {
                            this.loadConversations();
                        } else if (this.currentGroup) {
                            this.loadConversations();
                        }
                    } else {
                        alert('Failed to delete message: ' + (response.data.message || 'Unknown error'));
                    }
                },
                error: () => {
                    alert('Failed to delete message. Please try again.');
                }
            });
        }
    };
    
    // Event delegation for reactions
    $(document).on('click', '.sh-reaction-option', function(e) {
        e.stopPropagation();
        const reactionType = $(this).data('reaction');
        const $message = $(this).closest('.sh-message');
        const messageId = $message.data('messageId');
        
        if (messageId && reactionType) {
            SourceHubMessaging.addReaction(messageId, reactionType);
        }
    });
    
    $(document).on('click', '.sh-reaction-bubble', function(e) {
        e.stopPropagation();
        const reactionType = $(this).data('reaction');
        const messageId = $(this).data('messageId');
        
        if (messageId && reactionType) {
            // If user already reacted, remove it; otherwise add it
            if ($(this).hasClass('sh-user-reacted')) {
                SourceHubMessaging.removeReaction(messageId, reactionType);
            } else {
                SourceHubMessaging.addReaction(messageId, reactionType);
            }
        }
    });
    
    // Toggle message menu
    $(document).on('click', '.sh-message-menu-btn', function(e) {
        e.stopPropagation();
        const messageId = $(this).data('messageId');
        const $menu = $(`.sh-message-menu[data-message-id="${messageId}"]`);
        
        // Close all other menus
        $('.sh-message-menu').not($menu).removeClass('show');
        
        // Toggle this menu
        $menu.toggleClass('show');
    });
    
    // Close menu when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.sh-message-menu, .sh-message-menu-btn').length) {
            $('.sh-message-menu').removeClass('show');
        }
    });
    
    // Copy text
    $(document).on('click', '.sh-copy-text', function(e) {
        e.stopPropagation();
        const messageId = $(this).data('messageId');
        
        console.log('Copy text clicked for message ID:', messageId);
        
        const $message = $(`.sh-message[data-message-id="${messageId}"]`);
        console.log('Found message element:', $message.length);
        
        const $messageBody = $message.find('.sh-message-body');
        console.log('Found message body:', $messageBody.length, $messageBody.html());
        
        if (!$messageBody.length) {
            alert('Could not find message text');
            return;
        }
        
        // Get text content, stripping HTML but preserving line breaks
        let messageText = $messageBody.html() || '';
        
        if (messageText) {
            // Convert <br> to newlines
            messageText = messageText.replace(/<br\s*\/?>/gi, '\n');
            // Strip remaining HTML tags
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = messageText;
            messageText = tempDiv.textContent || tempDiv.innerText || '';
        }
        
        if (!messageText || messageText.trim() === '') {
            alert('No text to copy');
            return;
        }
        
        console.log('Copying text:', messageText);
        
        // Copy to clipboard using fallback method (more reliable)
        const textArea = document.createElement('textarea');
        textArea.value = messageText;
        textArea.style.position = 'fixed';
        textArea.style.left = '-9999px';
        textArea.style.top = '0';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            const successful = document.execCommand('copy');
            console.log('Copy successful:', successful);
            if (successful) {
                // Close menu
                $('.sh-message-menu').removeClass('show');
                // Show brief success indicator
                const $btn = $(`.sh-message-menu-btn[data-message-id="${messageId}"]`);
                const originalText = $btn.text();
                $btn.text('✓').css('color', '#46b450');
                setTimeout(() => {
                    $btn.text(originalText).css('color', '');
                }, 1000);
            } else {
                alert('Failed to copy text');
            }
        } catch (err) {
            console.error('Copy failed:', err);
            alert('Failed to copy text: ' + err.message);
        }
        
        document.body.removeChild(textArea);
    });
    
    // Delete message
    $(document).on('click', '.sh-delete-message', function(e) {
        e.stopPropagation();
        const messageId = $(this).data('messageId');
        
        if (messageId && confirm('Are you sure you want to delete this message?')) {
            SourceHubMessaging.deleteMessage(messageId);
        }
    });
    
    // Initialize when ready
    $(document).ready(function() {
        SourceHubMessaging.init();
    });
    
})(jQuery);
