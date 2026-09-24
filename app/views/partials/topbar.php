<?php
// Notification data must be set by the controller before rendering
// If not set, fallback to empty
$notifications = $notifications ?? [];
$unreadCount = $unreadCount ?? 0;
?>

<div class="topbar">

    <input class="search" placeholder="Search...">

    <div class="notification-wrapper">

        <button class="notification-btn" onclick="toggleNotification()">
            <i class="fas fa-bell"></i>
            <?php if ($unreadCount > 0): ?>
                <div class="notification-count"><?= $unreadCount ?></div>
            <?php endif; ?>
        </button>

        <div class="notification-panel" id="notificationPanel">

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                <h3 style="margin:0;">Notifications</h3>
                <?php if ($unreadCount > 0): ?>
                    <form method="POST" action="/notifications/mark-all-read" style="margin:0;">
                        <button type="submit" style="background:none; border:none; color:#2563eb; font-size:12px; cursor:pointer; font-weight:600;">Mark all read</button>
                    </form>
                <?php endif; ?>
            </div>

            <?php if (empty($notifications)): ?>
                <p style="color:#999; text-align:center; padding:15px;">No notifications.</p>
            <?php else: ?>
                <?php foreach ($notifications as $n): ?>
                    <div class="notification-item" style="<?= $n['is_read'] ? 'opacity:0.6;' : '' ?>">
                        <h4 style="display:flex; justify-content:space-between; align-items:center;">
                            <?= htmlspecialchars($n['title']) ?>
                            <?php if (!$n['is_read']): ?>
                                <span style="width:8px; height:8px; background:#2563eb; border-radius:50%;"></span>
                            <?php endif; ?>
                        </h4>
                        <p><?= htmlspecialchars($n['message']) ?></p>
                        <?php if (!empty($n['link'])): ?>
                            <a href="<?= htmlspecialchars($n['link']) ?>" style="color:#2563eb; font-size:12px; text-decoration:none;">View →</a>
                        <?php endif; ?>
                        <span style="font-size:11px; color:#999; display:block; margin-top:4px;">
                            <?= date('M d, Y H:i', strtotime($n['created_at'])) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
</div>