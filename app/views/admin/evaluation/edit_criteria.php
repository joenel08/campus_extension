<div class="post-card">
    <div class="post-title"><i class="fas fa-edit"></i> Edit Criteria</div>
    <form method="POST" action="/admin/evaluation/update-criteria">
        <input type="hidden" name="id" value="<?= $criteria['id'] ?>">
        <div class="post-grid">
            <div class="post-group full">
                <label class="post-label">Group</label>
                <select name="group_id" class="post-input" required>
                    <?php foreach ($groups as $g): ?>
                        <option value="<?= $g['id'] ?>" <?= ($g['id'] == $criteria['group_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($g['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="post-group full">
                <label class="post-label">Criteria Text</label>
                <textarea name="criteria_text" class="post-textarea" required><?= htmlspecialchars($criteria['criteria_text']) ?></textarea>
            </div>
            <div class="post-group full">
                <label class="post-label">Display Order</label>
                <input type="number" name="display_order" class="post-input" value="<?= $criteria['display_order'] ?>">
            </div>
        </div>
        <div class="post-btns">
            <button type="submit" class="update-btn"><i class="fas fa-save"></i> Update</button>
            <a href="/admin/evaluation" class="delete-post-btn" style="text-decoration:none; text-align:center; line-height:40px;">Cancel</a>
        </div>
    </form>
</div>