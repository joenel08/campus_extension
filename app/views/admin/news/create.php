<div class="post-card">
    <div class="post-title">
        <i class="fas fa-plus-circle"></i> Add New News
    </div>
    <form method="POST" action="/admin/news/store" enctype="multipart/form-data">
        <div class="post-grid">
            <div class="post-group full">
                <label class="post-label">News Title</label>
                <input type="text" name="title" class="post-input" required>
            </div>
            <div class="post-group full">
                <label class="post-label">News Content</label>
                <textarea name="content" class="post-textarea" required></textarea>
            </div>
            <div class="post-group full">
                <label class="post-label">Status</label>
                <select name="status" class="post-input">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>
            <div class="post-group full">
                <label class="post-label">Upload Image</label>
                <input type="file" name="image" class="post-input" accept="image/*">
            </div>
        </div>
        <div class="post-btns">
            <button type="submit" class="update-btn"><i class="fas fa-save"></i> Save</button>
            <a href="/admin/news" class="delete-post-btn" style="text-decoration:none; text-align:center; line-height:40px;">Cancel</a>
        </div>
    </form>
</div>