<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

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

            <div class="post-group">
                <label class="post-label">Category</label>
                <select name="category" id="categorySelect" class="post-input" onchange="toggleCollegeField()" required>
                    <option value="ceo" <?= $official['category'] === 'ceo' ? 'selected' : '' ?>>CEO (Cluster Executive Officer)</option>
                    <option value="director" <?= $official['category'] === 'director' ? 'selected' : '' ?>>Director, Community Engagement Services</option>
                    <option value="college_coordinator" <?= $official['category'] === 'college_coordinator' ? 'selected' : '' ?>>College Coordinator Officer</option>
                    <option value="heads" <?= $official['category'] === 'heads' ? 'selected' : '' ?>>Head</option>
                    <option value="staffs" <?= $official['category'] === 'staffs' ? 'selected' : '' ?>>Staff</option>
                </select>
            </div>

            <div class="post-group" id="collegeField" style="<?= $official['category'] === 'college_coordinator' ? 'display:block;' : 'display:none;' ?>">
                <label class="post-label">College</label>
                <select name="college_id" id="collegeSelect" class="post-input" <?= $official['category'] === 'college_coordinator' ? 'required' : '' ?>>
                    <option value="">Select College</option>
                    <?php foreach ($colleges as $college): ?>
                        <option value="<?= $college['id'] ?>" <?= $official['college_id'] == $college['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($college['abbreviation']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="post-group">
                <label class="post-label">Status</label>
                <select name="status" class="post-input">
                    <option value="draft" <?= $official['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= $official['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                </select>
            </div>

            <div class="post-group">
                <label class="post-label">Display Order</label>
                <input type="number" name="display_order" class="post-input" value="<?= $official['display_order'] ?? 0 ?>">
            </div>

            <div class="post-group full">
                <label class="post-label">Current Photo</label>
                <?php if ($official['image']): ?>
                    <img src="/<?= $official['image'] ?>" width="100" height="100" style="border-radius:50%; object-fit:cover; margin-bottom:10px; display:block;">
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

<script>
function toggleCollegeField() {
    var category = document.getElementById('categorySelect').value;
    var collegeField = document.getElementById('collegeField');
    var collegeSelect = document.getElementById('collegeSelect');

    if (category === 'college_coordinator') {
        collegeField.style.display = 'block';
        collegeSelect.required = true;
    } else {
        collegeField.style.display = 'none';
        collegeSelect.required = false;
        collegeSelect.value = '';
    }
}
</script>