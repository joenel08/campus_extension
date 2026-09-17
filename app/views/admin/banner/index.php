<?php
$banner = $banner ?? [];
$type = $banner['type'] ?? 'image';
$media_path = $banner['media_path'] ?? '';
$title = $banner['title'] ?? '';
$subtitle = $banner['subtitle'] ?? '';
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="post-card">
    <div class="post-title">
        <i class="fas fa-image"></i> Banner Settings
    </div>
    <form method="POST" action="/admin/banner/update" enctype="multipart/form-data">
        <div class="post-grid">
            <div class="post-group">
                <label class="post-label">Banner Type</label>
                <select name="type" class="post-input" id="bannerType" onchange="toggleBannerType()">
                    <option value="image" <?= $type === 'image' ? 'selected' : '' ?>>Image</option>
                    <option value="video" <?= $type === 'video' ? 'selected' : '' ?>>Video</option>
                </select>
            </div>
            <div class="post-group">
                <label class="post-label">Upload File (image or video)</label>
                <input type="file" name="media" class="post-input" accept="image/*,video/mp4,video/webm">
                <p style="font-size:13px; color:#6c757d; margin-top:5px;">Leave empty to keep current banner.</p>
            </div>
            <div class="post-group full">
                <label class="post-label">Current Banner</label>
                <?php if ($media_path): ?>
                    <?php if ($type === 'image'): ?>
                        <img src="/<?= $media_path ?>" style="max-width:100%; max-height:300px; border-radius:8px; display:block; margin:10px 0;">
                    <?php else: ?>
                        <video controls style="max-width:100%; max-height:300px; border-radius:8px; margin:10px 0;">
                            <source src="/<?= $media_path ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    <?php endif; ?>
                <?php else: ?>
                    <p style="color:#999;">No banner uploaded yet.</p>
                <?php endif; ?>
            </div>
            <div class="post-group full">
                <label class="post-label">Title</label>
                <input type="text" name="title" class="post-input" value="<?= htmlspecialchars($title) ?>" placeholder="e.g. Extension & Training Services">
            </div>
            <div class="post-group full">
                <label class="post-label">Subtitle</label>
                <input type="text" name="subtitle" class="post-input" value="<?= htmlspecialchars($subtitle) ?>" placeholder="e.g. Bridging academic excellence with community development.">
            </div>
        </div>

        <div class="post-btns">
            <button type="submit" class="update-btn"><i class="fas fa-save"></i> Update Banner</button>
            <a href="/admin/dashboard" class="delete-post-btn" style="text-decoration:none; text-align:center; line-height:40px;">Cancel</a>
        </div>
    </form>
</div>

<script>
    function toggleBannerType() {
        const type = document.getElementById('bannerType').value;
        const fileInput = document.querySelector('input[name="media"]');
        if (type === 'image') {
            fileInput.setAttribute('accept', 'image/*');
        } else {
            fileInput.setAttribute('accept', 'video/mp4,video/webm');
        }
    }
</script>