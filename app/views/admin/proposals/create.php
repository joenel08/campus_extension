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
            <div class="proposal-title"><i class="fas fa-plus-circle"></i> New Call for Proposals</div>
            <div class="proposal-subtitle">Setup a new proposal announcement and submission deadline</div>
        </div>
    </div>

    <form method="POST" action="/admin/proposal/store" enctype="multipart/form-data">
        <div class="proposal-grid">
            <div class="proposal-group">
                <label class="proposal-label">College</label>
                <select name="college_id" class="proposal-select" required>
                    <option value="">Select College</option>
                    <?php foreach ($colleges as $college): ?>
                        <option value="<?= $college['id'] ?>"><?= htmlspecialchars($college['abbreviation']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="proposal-group">
                <label class="proposal-label">Proposal Title</label>
                <input type="text" name="title" class="proposal-input" placeholder="Enter proposal title" required>
            </div>


            <div class="proposal-group">
                <label class="proposal-label">Proposal Category</label>
                <select name="category" class="proposal-select">
                    <option value="internally_funded">Internally Funded</option>
                    <option value="externally_funded">Externally Funded</option>
                </select>
            </div>

            <div class="proposal-group">
                <label class="proposal-label">Status</label>
                <select name="status" class="proposal-select">
                    <option value="open">Open</option>
                    <option value="closed">Closed</option>
                </select>
            </div>

            <div class="proposal-group">
                <label class="proposal-label">Opening Date</label>
                <input type="date" name="opening_date" class="proposal-input" required>
            </div>

            <div class="proposal-group">
                <label class="proposal-label">Submission Deadline</label>
                <input type="date" name="closing_date" class="proposal-input" required>
            </div>

            <div class="proposal-group full">
                <label class="proposal-label">Upload Proposal Guidelines</label>
                <input type="file" name="file_path" class="proposal-input">
                <p style="font-size:13px; color:#6c757d; margin-top:5px;">Upload PDF, DOC, or DOCX files.</p>
            </div>

            <div class="proposal-group full">
                <label class="proposal-label">Proposal Description</label>
                <textarea name="description" class="proposal-textarea" placeholder="Enter proposal announcement details..."></textarea>
            </div>
        </div>

        <div class="proposal-buttons">
            <a href="/admin/proposal" class="reset-btn" style="text-decoration:none; text-align:center;">Cancel</a>
            <button type="submit" class="publish-btn"><i class="fas fa-paper-plane"></i> Publish Proposal</button>
        </div>
    </form>
</div>