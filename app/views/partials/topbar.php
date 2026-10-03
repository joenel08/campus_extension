<?php
$notifications = $notifications ?? [];
$unreadCount = $unreadCount ?? 0;
$academicYearLabel = $_SESSION['academic_year_label'] ?? 'No Academic Year Set';

// Show only unread notifications
$unreadNotifications = array_filter($notifications, function ($n) {
    return !$n['is_read'];
});
?>

<style>
    /* Inner wrapper controls the flex + height, so the outer
       .notification-panel keeps its own show/hide behavior. */
    .notification-panel-inner {
        display: flex;
        flex-direction: column;
        max-height: 420px;   /* adjust as needed */
        overflow: hidden;
    }

    .notification-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        padding-bottom: 8px;
        border-bottom: 1px solid #eee;
        flex-shrink: 0;      /* header stays fixed */
    }

    .notification-list {
        flex: 1 1 auto;
        overflow-y: auto;    /* <-- the scrollable part */
        overflow-x: hidden;
        padding-right: 4px;
        margin-right: -4px;
    }

    .notification-list::-webkit-scrollbar {
        width: 6px;
    }
    .notification-list::-webkit-scrollbar-track {
        background: transparent;
    }
    .notification-list::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }
    .notification-list::-webkit-scrollbar-thumb:hover {
        background: #999;
    }
</style>

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

            <div class="notification-panel-inner">

                <!-- Fixed Header -->
                <div class="notification-panel-header">
                    <h3 style="margin:0;">Notifications</h3>
                    <?php if ($unreadCount > 0): ?>
                        <form method="POST" action="/notifications/mark-all-read" style="margin:0;">
                            <button type="submit" style="background:none; border:none; color:#2563eb; font-size:12px; cursor:pointer; font-weight:600;">Mark all read</button>
                        </form>
                    <?php endif; ?>
                </div>

                <!-- Scrollable List -->
                <div class="notification-list">
                    <?php if (empty($unreadNotifications)): ?>
                        <p style="color:#999; text-align:center; padding:15px;">No new notifications.</p>
                    <?php else: ?>
                        <?php foreach ($unreadNotifications as $n): ?>
                            <?php
                                // Use the notification's link if present, otherwise stay on current page
                                $redirect = !empty($n['link']) ? $n['link'] : $_SERVER['REQUEST_URI'];
                            ?>
                            <form method="POST" action="/notifications/mark-read" style="margin:0;">
                                <input type="hidden" name="id" value="<?= $n['id'] ?>">
                                <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">
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
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            </div>

        </div>
    </div>
</div>