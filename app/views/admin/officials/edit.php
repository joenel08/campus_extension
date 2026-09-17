<div class="post-card">
    <div class="post-title">
        <i class="fas fa-edit"></i> Edit Official
    </div>
    <form method="POST" action="/admin/officials/update" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $official['id'] ?>">
        <div class="post-grid">
            <div class="post-group full">
                <label class="post-label">Full Name</label>
                <input type="text" name="name" class="post-input" value="<?= htmlspecialchars($official['name']) ?>" required>
            </div>
            <div class="post-group full">
                <label class="post-label">Position</label>
                <input type="text" name="position" class="post-input" value="<?= htmlspecialchars($official['position']) ?>" required>
            </div>
            <div class="post-group full">
                <label class="post-label">Email</label>
                <input type="email" name="email" class="post-input" value="<?= htmlspecialchars($official['email']) ?>" required>
            </div>
            <div class="post-group">
                <label class="post-label">Category</label>
                <select name="category" class="post-input">
                    <option value="administrative" <?= $official['category'] === 'administrative' ? 'selected' : '' ?>>Administrative Officials</option>
                    <option value="research_extension" <?= $official['category'] === 'research_extension' ? 'selected' : '' ?>>Research & Development, Extension & Training</option>
                    <option value="deans" <?= $official['category'] === 'deans' ? 'selected' : '' ?>>Deans</option>
                </select>
            </div>
            <div class="post-group">
                <label class="post-label">Status</label>
                <select name="status" class="post-input">
                    <option value="draft" <?= $official['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= $official['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                </select>
            </div>
            <div class="post-group full">
                <label class="post-label">Display Order (lower numbers appear first)</label>
                <input type="number" name="display_order" class="post-input" value="<?= $official['display_order'] ?? 0 ?>">
            </div>
            <div class="post-group full">
                <label class="post-label">Current Photo</label>
                <?php if ($official['image']): ?>
                    <img src="/<?= $official['image'] ?>" width="120" height="120" style="border-radius:50%; object-fit:cover; margin-bottom:10px; display:block;">
                <?php else: ?>
                    <p style="color:#999;">No photo</p>
                <?php endif; ?>
                <label class="post-label" style="margin-top:10px;">Replace Photo (leave empty to keep current)</label>
                <input type="file" name="image" class="post-input" accept="image/*">
            </div>
        </div>
        <div class="post-btns">
            <button type="submit" class="update-btn"><i class="fas fa-save"></i> Update</button>
            <a href="/admin/officials" class="delete-post-btn" style="text-decoration:none; text-align:center; line-height:40px;">Cancel</a>
        </div>
    </form>
</div>