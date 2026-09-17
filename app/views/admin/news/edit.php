<div class="post-card">
    <div class="post-title">
        <i class="fas fa-edit"></i> Edit News
    </div>
    <form method="POST" action="/admin/news/update" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $news['id'] ?>">
        <div class="post-grid">
            <div class="post-group full">
                <label class="post-label">News Title</label>
                <input type="text" name="title" class="post-input" value="<?= htmlspecialchars($news['title']) ?>" required>
            </div>
            <div class="post-group full">
                <label class="post-label">News Content</label>
                <textarea name="content" class="post-textarea" required><?= htmlspecialchars($news['content']) ?></textarea>
            </div>
            <div class="post-group full">
                <label class="post-label">Status</label>
                <select name="status" class="post-input">
                    <option value="draft" <?= $news['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= $news['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                </select>
            </div>
            <div class="post-group full">
                <label class="post-label">Current Image</label>
                <?php if ($news['image']): ?>
                    <img src="/<?= $news['image'] ?>" width="200" style="border-radius:8px; margin-bottom:10px; display:block;">
                <?php else: ?>
                    <p style="color:#999;">No image</p>
                <?php endif; ?>
                <label class="post-label" style="margin-top:10px;">Replace Image (leave empty to keep current)</label>
                <input type="file" name="image" class="post-input" accept="image/*">
            </div>
        </div>
        <div class="post-btns">
            <button type="submit" class="update-btn"><i class="fas fa-save"></i> Update</button>
            <a href="/admin/news" class="delete-post-btn" style="text-decoration:none; text-align:center; line-height:40px;">Cancel</a>
        </div>
    </form>
</div>