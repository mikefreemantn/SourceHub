<?php
/**
 * SourceHub Messaging Panel Template
 * 
 * @package SourceHub
 * @since 2.1.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<!-- Chat Panel -->
<div id="sourcehub-chat-panel">
    <!-- Header -->
    <div class="sh-panel-header">
        <h2 class="sh-panel-title">Messages</h2>
        <button id="sourcehub-chat-close" type="button">×</button>
    </div>
    
    <!-- Navigation -->
    <nav class="sh-nav">
        <button class="sh-nav-btn active" data-view="inbox">
            <span class="dashicons dashicons-email"></span> Inbox
        </button>
        <button class="sh-nav-btn" data-view="groups">
            <span class="dashicons dashicons-groups"></span> Groups
        </button>
        <button class="sh-nav-btn" data-view="online">
            <span class="dashicons dashicons-admin-users"></span> Users
        </button>
    </nav>
    
    <!-- Content -->
    <div class="sh-panel-content">
        
        <!-- Inbox View -->
        <div id="sh-inbox-view" class="sh-view">
            <div id="sh-conversations-list" class="sh-list"></div>
        </div>
        
        <!-- Groups View -->
        <div id="sh-groups-view" class="sh-view">
            <button type="button" class="sh-create-group-btn sh-create-group">
                <span class="dashicons dashicons-plus-alt"></span>
                Create New Group
            </button>
            <div id="sh-groups-list" class="sh-list"></div>
        </div>
        
        <!-- Online Users View -->
        <div id="sh-online-view" class="sh-view">
            <div id="sh-online-list" class="sh-list"></div>
        </div>
        
        <!-- Conversation View -->
        <div id="sh-conversation-view" class="sh-view">
            <div class="sh-conversation-header">
                <button type="button" class="sh-back-btn">
                    <span class="dashicons dashicons-arrow-left-alt2"></span>
                </button>
                <span id="sh-conversation-title" class="sh-conversation-title"></span>
            </div>
            
            <div id="sh-messages-list"></div>
            
            <div class="sh-message-composer">
                <div id="sh-attachment-preview"></div>
                <div class="sh-composer-actions">
                    <textarea id="sh-message-input" placeholder="Type a message..." rows="2"></textarea>
                    <label for="sh-file-upload" class="sh-file-upload-btn">
                        <span class="dashicons dashicons-paperclip"></span>
                    </label>
                    <input type="file" id="sh-file-upload" accept="image/*,.pdf,.doc,.docx">
                    <button type="button" id="sh-send-message">
                        <span class="dashicons dashicons-arrow-right-alt2"></span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Group View -->
        <div id="sh-group-view" class="sh-view">
            <div class="sh-group-header">
                <button type="button" class="sh-back-btn">
                    <span class="dashicons dashicons-arrow-left-alt2"></span>
                </button>
                <span id="sh-group-title" class="sh-group-title"></span>
            </div>
            
            <div id="sh-group-messages-list"></div>
            
            <div class="sh-message-composer">
                <div id="sh-attachment-preview"></div>
                <div class="sh-composer-actions">
                    <textarea id="sh-message-input" placeholder="Type a message..." rows="2"></textarea>
                    <label for="sh-file-upload" class="sh-file-upload-btn">
                        <span class="dashicons dashicons-paperclip"></span>
                    </label>
                    <input type="file" id="sh-file-upload" accept="image/*,.pdf,.doc,.docx">
                    <button type="button" id="sh-send-message">
                        <span class="dashicons dashicons-arrow-right-alt2"></span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- New Group View -->
        <div id="sh-new-group-view" class="sh-view">
            <div class="sh-conversation-header">
                <button type="button" class="sh-back-btn">
                    <span class="dashicons dashicons-arrow-left-alt2"></span>
                </button>
                <span class="sh-conversation-title">Create Group</span>
            </div>
            
            <div class="sh-form-group">
                <label for="sh-group-name">Group Name</label>
                <input type="text" id="sh-group-name" placeholder="Enter group name">
            </div>
            
            <div class="sh-form-group">
                <label for="sh-group-description">Description (optional)</label>
                <textarea id="sh-group-description" placeholder="Enter group description"></textarea>
            </div>
            
            <div class="sh-form-group">
                <label>Members</label>
                <div id="sh-group-members-list"></div>
            </div>
            
            <div class="sh-form-actions">
                <button type="button" id="sh-save-group">Create Group</button>
                <button type="button" id="sh-cancel-group">Cancel</button>
            </div>
        </div>
        
    </div>
</div>

<!-- Edit Group Modal -->
<div id="sh-edit-group-modal" class="sh-modal">
    <div class="sh-modal-content">
        <div class="sh-modal-header">
            <h3>Edit Group</h3>
            <button type="button" class="sh-modal-close">&times;</button>
        </div>
        <div class="sh-modal-body">
            <div class="sh-form-group">
                <label for="sh-edit-group-name">Group Name</label>
                <input type="text" id="sh-edit-group-name" class="sh-form-control" placeholder="Enter group name">
            </div>
            
            <div class="sh-form-group">
                <label>Current Members</label>
                <div id="sh-edit-group-members-list" class="sh-members-list"></div>
            </div>
            
            <div class="sh-form-group">
                <label>Add Members</label>
                <select id="sh-add-member-select" class="sh-form-control">
                    <option value="">Select a user to add...</option>
                </select>
                <button type="button" id="sh-add-member-btn" class="sh-btn sh-btn-secondary" style="margin-top: 8px;">Add Member</button>
            </div>
        </div>
        <div class="sh-modal-footer">
            <button type="button" id="sh-leave-group" class="sh-btn sh-btn-danger" style="margin-right: auto;">Leave Group</button>
            <button type="button" id="sh-cancel-edit-group" class="sh-btn sh-btn-secondary">Cancel</button>
            <button type="button" id="sh-save-edit-group" class="sh-btn sh-btn-primary">Save Changes</button>
        </div>
    </div>
</div>
