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
            <div class="proposal-subtitle">
                This call will be opened immediately and visible to all extensionists.
            </div>
        </div>
    </div>

    <form method="POST" action="/admin/proposal/store" enctype="multipart/form-data">
        <div class="proposal-grid">

            <!-- ACADEMIC YEAR (display only) -->
            <div class="proposal-group">
                <label class="proposal-label">Academic Year</label>
                <input type="text" class="proposal-input"
                    value="<?= htmlspecialchars($currentAcademicYear['year_label'] ?? 'No Academic Year Set') ?>"
                    readonly style="background:#f0f0f0; font-weight:600; color:#2563eb;">
            </div>

            <!-- TITLE -->
            <div class="proposal-group">
                <label class="proposal-label">Proposal Title</label>
                <input type="text" name="title" class="proposal-input" 
                       placeholder="Enter proposal title" required>
            </div>

            <!-- DEADLINE -->
            <div class="proposal-group full">
                <label class="proposal-label">Submission Deadline</label>
                <input type="date" name="closing_date" class="proposal-input" 
                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                <p style="font-size:12px; color:#6c757d; margin-top:5px;">
                    Must be after today. The call will open immediately and close on this date.
                </p>
            </div>

            <!-- FILE -->
            <div class="proposal-group full">
                <label class="proposal-label">Upload Proposal Guidelines</label>
                <input type="file" name="file_path" class="proposal-input">
                <p style="font-size:13px; color:#6c757d; margin-top:5px;">
                    Upload PDF, DOC, or DOCX files.
                </p>
            </div>

            <!-- DESCRIPTION -->
            <div class="proposal-group full">
                <label class="proposal-label">Proposal Description</label>
                <textarea name="description" class="proposal-textarea" 
                          placeholder="Enter proposal announcement details..."></textarea>
            </div>
        </div>

        <div class="proposal-buttons">
            <a href="/admin/proposal" class="reset-btn" style="text-decoration:none; text-align:center;">Cancel</a>
            <button type="submit" class="publish-btn"><i class="fas fa-paper-plane"></i> Publish Proposal</button>
        </div>
    </form>
</div>