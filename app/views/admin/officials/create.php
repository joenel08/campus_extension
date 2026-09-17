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
            <div class="post-group full">
                <label class="post-label">Email</label>
                <input type="email" name="email" class="post-input" required>
            </div>
            <div class="post-group">
                <label class="post-label">Category</label>
                <select name="category" class="post-input">
                    <option value="administrative">Administrative Officials</option>
                    <option value="research_extension">Research & Development, Extension & Training</option>
                    <option value="deans">Deans</option>
                </select>
            </div>
            <div class="post-group">
                <label class="post-label">Status</label>
                <select name="status" class="post-input">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>
            <div class="post-group full">
                <label class="post-label">Display Order (lower numbers appear first within category)</label>
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