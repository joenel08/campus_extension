<style>
    .proposal-container {
        background: #fff;
        border-radius: 18px;
        padding: 35px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .proposal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .proposal-title {
        font-size: 28px;
        font-weight: 700;
        color: #183153;
    }

    .proposal-subtitle {
        color: #6b7280;
        margin-top: 8px;
        font-size: 15px;
    }

    .proposal-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .proposal-group {
        display: flex;
        flex-direction: column;
    }

    .proposal-group.full {
        grid-column: 1 / span 2;
    }

    .proposal-label {
        font-size: 14px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 10px;
    }

    .proposal-input,
    .proposal-select,
    .proposal-textarea {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 14px;
        padding: 15px;
        font-size: 14px;
        outline: none;
        background: #fff;
    }

    .proposal-input:focus,
    .proposal-select:focus,
    .proposal-textarea:focus {
        border-color: #2563eb;
    }

    .proposal-textarea {
        height: 160px;
        resize: none;
    }

    .status-box {
        margin-top: 30px;
        padding: 22px;
        background: #f8fafc;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
    }

    .status-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 18px;
        color: #183153;
    }

    .status-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 15px;
    }

    .status-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 18px;
    }

    .status-card h4 {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 10px;
    }

    .status-card p {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
    }

    .proposal-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 30px;
    }

    .publish-btn {
        background: #16a34a;
        color: #fff;
        border: none;
        padding: 14px 24px;
        border-radius: 12px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
    }

    .publish-btn:hover {
        background: #15803d;
    }

    .reset-btn {
        background: #ef4444;
        color: #fff;
        border: none;
        padding: 14px 24px;
        border-radius: 12px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
    }

    .reset-btn:hover {
        background: #dc2626;
    }
</style>
<div class="proposal-container">
    <div class="proposal-header">
        <div>
            <div class="proposal-title"><i class="fas fa-edit"></i> Edit Call for Proposals</div>
            <div class="proposal-subtitle">Update proposal announcement details</div>
        </div>
    </div>

    <form method="POST" action="/admin/proposal/update" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $proposal['id'] ?>">
        <div class="proposal-grid">
            <div class="proposal-group">
                <label class="proposal-label">College</label>
                <select name="college_id" class="proposal-select" required>
                    <option value="">Select College</option>
                    <?php foreach ($colleges as $college): ?>
                        <option value="<?= $college['id'] ?>" <?= $college['id'] == $proposal['college_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($college['abbreviation']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="proposal-group ">
                <label class="proposal-label">Proposal Title</label>
                <input type="text" name="title" class="proposal-input" value="<?= htmlspecialchars($proposal['title']) ?>" required>
            </div>

            <div class="proposal-group">
                <label class="proposal-label">Proposal Category</label>
                <select name="category" class="proposal-select">
                    <option value="internally_funded" <?= $proposal['category'] === 'internally_funded' ? 'selected' : '' ?>>Internally Funded</option>
                    <option value="externally_funded" <?= $proposal['category'] === 'externally_funded' ? 'selected' : '' ?>>Externally Funded</option>
                </select>
            </div>

            <div class="proposal-group">
                <label class="proposal-label">Status</label>
                <select name="status" class="proposal-select">
                    <option value="open" <?= $proposal['status'] === 'open' ? 'selected' : '' ?>>Open</option>
                    <option value="closed" <?= $proposal['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
                </select>
            </div>

            <div class="proposal-group">
                <label class="proposal-label">Opening Date</label>
                <input type="date" name="opening_date" class="proposal-input" value="<?= $proposal['opening_date'] ?>" required>
            </div>

            <div class="proposal-group">
                <label class="proposal-label">Submission Deadline</label>
                <input type="date" name="closing_date" class="proposal-input" value="<?= $proposal['closing_date'] ?>" required>
            </div>

            <div class="proposal-group full">
                <label class="proposal-label">Current File</label>
                <?php if ($proposal['file_path']): ?>
                    <p style="margin-bottom:10px;"><a href="/<?= $proposal['file_path'] ?>" target="_blank">View Current File</a></p>
                <?php else: ?>
                    <p style="color:#999; margin-bottom:10px;">No file uploaded.</p>
                <?php endif; ?>
                <label class="proposal-label" style="margin-top:10px;">Replace File (leave empty to keep current)</label>
                <input type="file" name="file_path" class="proposal-input">
            </div>

            <div class="proposal-group full">
                <label class="proposal-label">Proposal Description</label>
                <textarea name="description" class="proposal-textarea" placeholder="Enter proposal announcement details..."><?= htmlspecialchars($proposal['description']) ?></textarea>
            </div>
        </div>

        <div class="proposal-buttons">
            <a href="/admin/proposal" class="reset-btn" style="text-decoration:none; text-align:center;">Cancel</a>
            <button type="submit" class="publish-btn"><i class="fas fa-save"></i> Update Proposal</button>
        </div>
    </form>
</div>