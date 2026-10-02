<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Engagement Services</title>
    <link rel="icon" type="image/x-icon" href="/images/isu_logo.png">
    <link rel="stylesheet" href="/css/admin.css">


    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <?php include __DIR__ . "/../partials/sidebar-{$role}.php"; ?>

    <div class="main">
        <!-- Topbar -->
        <?php include __DIR__ . "/../partials/topbar.php"; ?>
        <!-- Main content -->
        <div id="content">
            <?php include $content; ?>
        </div>

    </div>



    <script>
        // Heartbeat: update last_activity every 30s
        function pingHeartbeat() {
            fetch('/heartbeat', {
                    method: 'POST'
                })
                .catch(() => {});
        }

        // Initial ping
        pingHeartbeat();

        // Every 30 seconds
        setInterval(pingHeartbeat, 30000);

        // Also ping when the tab regains focus (user comes back)
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) pingHeartbeat();
        });
    </script>
    <script src="/js/admin.js"></script>
</body>

</html>