<div class="container" style="max-width:1200px; margin:40px auto;margin-top:100px; padding:0 20px;">
    <h1 style="margin-bottom:30px;text-transform: uppercase;">All News</h1>
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px,1fr)); gap:25px;">
        <?php foreach ($allNews as $item): ?>
            <div class="news-card" style="background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.05);">
                <img src="<?= htmlspecialchars($item['image'] ? '/' . $item['image'] : 'https://via.placeholder.com/400x240') ?>" alt="<?= htmlspecialchars($item['title']) ?>" style="width:100%; height:200px; object-fit:cover;">
                <div style="padding:20px;">
                    <h3 style="font-size:20px; margin-bottom:10px;"><?= htmlspecialchars($item['title']) ?></h3>
                    <p style="color:#6b7280; font-size:14px; margin-bottom:12px;"><?= date('M d, Y', strtotime($item['created_at'])) ?></p>
                    <p style="color:#444; margin-bottom:15px;"><?= htmlspecialchars(substr($item['content'], 0, 120)) ?>...</p>
                    <a href="/news/show?id=<?= $item['id'] ?>" style="color:#0b7a33; font-weight:600; text-decoration:none;">Read More →</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>