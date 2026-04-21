<?php
/**
 * HubChat Settings Page
 */

if (!defined('ABSPATH')) {
    exit;
}

$user_id = get_current_user_id();
$email_digest = get_user_meta($user_id, 'sourcehub_email_digest', true);
$mute_notifications = get_user_meta($user_id, 'sourcehub_mute_notifications', true);
$notification_duration = get_user_meta($user_id, 'sourcehub_notification_duration', true);

// Default to enabled if not set
if ($email_digest === '') {
    $email_digest = '1';
}
if ($mute_notifications === '') {
    $mute_notifications = '0';
}
if ($notification_duration === '') {
    $notification_duration = '5'; // Default 5 seconds
}
?>

<style>
.hubchat-settings-wrap {
    background: #f0f0f1;
    margin: 20px 20px 0 0;
    padding: 0;
}

.hubchat-header {
    background: linear-gradient(135deg, #46b450 0%, #2c8e35 100%);
    color: white;
    padding: 40px;
    margin: 0;
    border-radius: 8px 8px 0 0;
    box-shadow: 0 2px 8px rgba(70, 180, 80, 0.2);
}

.hubchat-header h1 {
    margin: 0 0 10px 0;
    font-size: 32px;
    font-weight: 600;
    color: white;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.hubchat-header p {
    margin: 0;
    font-size: 16px;
    opacity: 0.95;
}

.hubchat-content {
    background: white;
    padding: 30px 40px;
    border-radius: 0 0 8px 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.hubchat-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.hubchat-card {
    background: #f9f9f9;
    border: 2px solid #e5e5e5;
    border-radius: 8px;
    padding: 25px;
    transition: all 0.3s ease;
}

.hubchat-card:hover {
    border-color: #46b450;
    box-shadow: 0 4px 12px rgba(70, 180, 80, 0.1);
    transform: translateY(-2px);
}

.hubchat-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
}

.hubchat-card-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #46b450 0%, #2c8e35 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.hubchat-card-title {
    font-size: 18px;
    font-weight: 600;
    color: #1d2327;
    margin: 0;
}

.hubchat-card-description {
    color: #646970;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 15px;
}

.hubchat-action-btn {
    background: linear-gradient(135deg, #46b450 0%, #2c8e35 100%);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    box-shadow: 0 2px 4px rgba(70, 180, 80, 0.2);
}

.hubchat-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(70, 180, 80, 0.3);
    color: white;
}

.hubchat-settings-section {
    background: #f9f9f9;
    border: 2px solid #e5e5e5;
    border-radius: 8px;
    padding: 30px;
    margin-bottom: 20px;
}

.hubchat-settings-section h2 {
    margin: 0 0 20px 0;
    font-size: 20px;
    color: #1d2327;
    display: flex;
    align-items: center;
    gap: 10px;
}

.hubchat-settings-section h2:before {
    content: "";
    width: 4px;
    height: 24px;
    background: linear-gradient(180deg, #46b450 0%, #2c8e35 100%);
    border-radius: 2px;
}

.hubchat-setting-item {
    padding: 20px;
    background: white;
    border-radius: 6px;
    margin-bottom: 15px;
    border: 1px solid #e5e5e5;
    transition: all 0.3s ease;
}

.hubchat-setting-item:hover {
    border-color: #46b450;
    box-shadow: 0 2px 8px rgba(70, 180, 80, 0.1);
}

.hubchat-setting-item:last-child {
    margin-bottom: 0;
}

.hubchat-setting-label {
    font-size: 16px;
    font-weight: 600;
    color: #1d2327;
    margin-bottom: 8px;
    display: block;
}

.hubchat-setting-description {
    color: #646970;
    font-size: 14px;
    line-height: 1.6;
    margin-top: 8px;
}

.hubchat-checkbox-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
}

.hubchat-checkbox-wrapper input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.hubchat-number-input {
    display: flex;
    align-items: center;
    gap: 10px;
}

.hubchat-number-input input[type="number"] {
    width: 80px;
    padding: 8px 12px;
    font-size: 16px;
    border: 2px solid #e5e5e5;
    border-radius: 6px;
    transition: border-color 0.3s ease;
}

.hubchat-number-input input[type="number"]:focus {
    border-color: #46b450;
    outline: none;
}

.hubchat-save-btn {
    background: linear-gradient(135deg, #46b450 0%, #2c8e35 100%);
    color: white;
    border: none;
    padding: 14px 32px;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(70, 180, 80, 0.2);
}

.hubchat-save-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(70, 180, 80, 0.3);
}
</style>

<div class="wrap hubchat-settings-wrap">
    <div class="hubchat-header">
        <h1>💬 HubChat Settings</h1>
        <p>Customize your messaging experience and notification preferences</p>
    </div>
    
    <div class="hubchat-content">
        <!-- Quick Actions Card -->
        <div class="hubchat-cards">
            <div class="hubchat-card">
                <div class="hubchat-card-header">
                    <div class="hubchat-card-icon">💬</div>
                    <h3 class="hubchat-card-title">Open Chat</h3>
                </div>
                <p class="hubchat-card-description">
                    Launch the HubChat messaging panel to start conversations with your team.
                </p>
                <a href="<?php echo add_query_arg('open_chat', '1'); ?>" class="hubchat-action-btn">
                    Open Chat Window
                </a>
            </div>
        </div>
        
        <!-- Settings Form -->
        <form method="post" action="">
            <?php wp_nonce_field('sourcehub_hubchat_settings', 'sourcehub_hubchat_settings_nonce'); ?>
            
            <div class="hubchat-settings-section">
                <h2>Notification Preferences</h2>
                
                <div class="hubchat-setting-item">
                    <label class="hubchat-setting-label" for="sourcehub_email_digest">
                        📧 Email Digest
                    </label>
                    <div class="hubchat-checkbox-wrapper">
                        <input 
                            type="checkbox" 
                            name="sourcehub_email_digest" 
                            id="sourcehub_email_digest" 
                            value="1" 
                            <?php checked($email_digest, '1'); ?>
                        />
                        <span>Receive email digest of missed messages</span>
                    </div>
                    <p class="hubchat-setting-description">
                        Get an email summary of unread messages after 1 hour of inactivity. Never miss important conversations!
                    </p>
                </div>
                
                <div class="hubchat-setting-item">
                    <label class="hubchat-setting-label" for="sourcehub_mute_notifications">
                        🔔 Sound Notifications
                    </label>
                    <div class="hubchat-checkbox-wrapper">
                        <input 
                            type="checkbox" 
                            name="sourcehub_mute_notifications" 
                            id="sourcehub_mute_notifications" 
                            value="1" 
                            <?php checked($mute_notifications, '1'); ?>
                        />
                        <span>Mute notification sounds</span>
                    </div>
                    <p class="hubchat-setting-description">
                        Disable sound alerts for new direct messages and group messages. Visual notifications will still appear.
                    </p>
                </div>
                
                <div class="hubchat-setting-item">
                    <label class="hubchat-setting-label" for="sourcehub_notification_duration">
                        ⏱️ Notification Duration
                    </label>
                    <div class="hubchat-number-input">
                        <input 
                            type="number" 
                            name="sourcehub_notification_duration" 
                            id="sourcehub_notification_duration" 
                            value="<?php echo esc_attr($notification_duration); ?>" 
                            min="1" 
                            max="30" 
                            step="1"
                        />
                        <span>seconds</span>
                    </div>
                    <p class="hubchat-setting-description">
                        Control how long notification popups stay visible on your screen (1-30 seconds).
                    </p>
                </div>
            </div>
            
            <button type="submit" class="hubchat-save-btn">
                💾 Save Settings
            </button>
        </form>
    </div>
</div>
