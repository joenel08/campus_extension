<div class="container" style="max-width:900px; margin:40px auto;margin-top:100px; padding:0 20px;">
    <h1 style="text-transform: uppercase;"><?= htmlspecialchars($eventItem['title']) ?></h1>
    <p style="color:#6b7280; margin-bottom:20px;"><?= date('F d, Y', strtotime($eventItem['event_date'])) ?></p>
    <?php if ($eventItem['image']): ?>
        <img src="/<?= $eventItem['image'] ?>" alt="<?= htmlspecialchars($eventItem['title']) ?>" style="width:100%; max-height:500px; object-fit:cover; border-radius:12px; margin-bottom:30px;">
    <?php endif; ?>
    <div style="font-size:18px; line-height:1.8;">
        <?= nl2br(htmlspecialchars($eventItem['description'])) ?>
    </div>
    <a href="/events" style="display:inline-block; margin-top:30px; padding:12px 24px; background:#0b7a33; color:#fff; border-radius:8px; text-decoration:none;">← Back to All Events</a>
</div>