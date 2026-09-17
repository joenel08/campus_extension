<div class="post-card">
    <div class="post-title"><i class="fas fa-plus-circle"></i> Add Evaluation Criteria</div>
    <form method="POST" action="/admin/evaluation/store-criteria">
        <div class="post-grid">
            <div class="post-group full">
                <label class="post-label">Group</label>
                <select name="group_id" class="post-input" required>
                    <option value="">Select Group</option>
                    <?php foreach ($groups as $g): ?>
                        <option value="<?= $g['id'] ?>" <?= ($selected_group == $g['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($g['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="post-group full">
                <label class="post-label">Criteria Text</label>
                <textarea name="criteria_text" class="post-textarea" required></textarea>
            </div>
            <div class="post-group full">
                <label class="post-label">Display Order</label>
                <input type="number" name="display_order" class="post-input" value="0">
            </div>
        </div>
        <div class="post-btns">
            <button type="submit" class="update-btn"><i class="fas fa-save"></i> Save</button>
            <a href="/admin/evaluation" class="delete-post-btn" style="text-decoration:none; text-align:center; line-height:40px;">Cancel</a>
        </div>
    </form>
</div>