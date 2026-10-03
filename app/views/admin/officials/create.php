<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="post-card">
    <div class="post-title">
        <i class="fas fa-user-plus"></i> Add Official
    </div>
    <form method="POST" action="/admin/officials/store" enctype="multipart/form-data">
        <div class="post-grid">

            <div class="post-group full">
                <label class="post-label">Full Name</label>
                <input type="text" name="name" class="post-input" required>
            </div>

            <div class="post-group full">
                <label class="post-label">Position</label>
                <input type="text" name="position" class="post-input" required>
            </div>

            <div class="post-group">
                <label class="post-label">Category</label>
                <select name="category" id="categorySelect" class="post-input" onchange="toggleCollegeField()" required>
                    <option value="">Select Category</option>
                    <option value="ceo">CEO (Cluster Executive Officer)</option>
                    <option value="director">Director, Community Engagement Services</option>
                    <option value="college_coordinator">College Coordinator Officer</option>
                    <option value="heads">Head</option>
                    <option value="staffs">Staff</option>
                </select>
            </div>

            <div class="post-group" id="collegeField" style="display:none;">
                <label class="post-label">College</label>
                <select name="college_id" id="collegeSelect" class="post-input">
                    <option value="">Select College</option>
                    <?php foreach ($colleges as $college): ?>
                        <option value="<?= $college['id'] ?>"><?= htmlspecialchars($college['abbreviation']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="post-group">
                <label class="post-label">Status</label>
                <select name="status" class="post-input">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>

            <div class="post-group">
                <label class="post-label">Display Order</label>
                <input type="number" name="display_order" class="post-input" value="0">
            </div>

            <div class="post-group full">
                <label class="post-label">Upload Photo</label>
                <input type="file" name="image" class="post-input" accept="image/*">
            </div>
        </div>

        <div class="post-btns">
            <button type="submit" class="update-btn"><i class="fas fa-save"></i> Save</button>
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