<?php
$notifications = $notifications ?? [];
$unreadCount = $unreadCount ?? 0;
$academicYearLabel = $_SESSION['academic_year_label'] ?? 'No Academic Year Set';

// Show only unread notifications
$unreadNotifications = array_filter($notifications, function ($n) {
    return !$n['is_read'];
});
?>

<div class="topbar">

    <!-- Current Academic Year Badge -->
    <div class="academic-year-badge">
        <span style="color:gray;">&nbsp;Current A.Y. <?= htmlspecialchars($academicYearLabel) ?></span>
    </div>

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

            <?php if (empty($unreadNotifications)): ?>
                <p style="color:#999; text-align:center; padding:15px;">No new notifications.</p>
            <?php else: ?>
                <?php foreach ($unreadNotifications as $n): ?>
                    <?php if (!empty($n['link'])): ?>
                        <form method="POST" action="/notifications/mark-read" style="margin:0;">
                            <input type="hidden" name="id" value="<?= $n['id'] ?>">
                            <input type="hidden" name="redirect" value="<?= htmlspecialchars($n['link']) ?>">
                            <button type="submit" style="display:block; width:100%; text-align:left; background:none; border:none; padding:0; cursor:pointer;">
                                <div class="notification-item">
                                    <h4 style="display:flex; justify-content:space-between; align-items:center;">
                                        <?= htmlspecialchars($n['title']) ?>
                                        <span style="width:8px; height:8px; background:#2563eb; border-radius:50%;"></span>
                                    </h4>
                                    <p><?= htmlspecialchars($n['message']) ?></p>
                                    <span style="font-size:11px; color:#999; display:block; margin-top:4px;">
                                        <?= date('M d, Y H:i', strtotime($n['created_at'])) ?>
                                    </span>
                                </div>
                            </button>
                        </form>
                    <?php else: ?>
                        <!-- No link: mark as read on click, no navigation -->
                        <form method="POST" action="/notifications/mark-read" style="margin:0;">
                            <input type="hidden" name="id" value="<?= $n['id'] ?>">
                            <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                            <button type="submit" style="display:block; width:100%; text-align:left; background:none; border:none; padding:0; cursor:pointer;">
                                <div class="notification-item">
                                    <h4 style="display:flex; justify-content:space-between; align-items:center;">
                                        <?= htmlspecialchars($n['title']) ?>
                                        <span style="width:8px; height:8px; background:#2563eb; border-radius:50%;"></span>
                                    </h4>
                                    <p><?= htmlspecialchars($n['message']) ?></p>
                                    <span style="font-size:11px; color:#999; display:block; margin-top:4px;">
                                        <?= date('M d, Y H:i', strtotime($n['created_at'])) ?>
                                    </span>
                                </div>
                            </button>
                        </form>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
</div>