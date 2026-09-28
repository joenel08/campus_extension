<style>
    .forms-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        padding: 20px;
    }

    .form-template-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e5e7eb;
        min-width: 0;
        display: flex;
        flex-direction: column;
        transition: 0.25s;
    }

    .form-template-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        transform: translateY(-3px);
    }

    .template-title {
        font-size: 16px;
        font-weight: 700;
        color: #183153;
        margin-bottom: 6px;
        line-height: 1.3;
    }

    .template-code {
        color: #6b7280;
        font-size: 11px;
        margin-bottom: 12px;
        letter-spacing: 0.5px;
    }

    .template-desc {
        font-size: 13px;
        color: #4b5563;
        line-height: 1.5;
        margin-bottom: 15px;
        min-height: 40px;
    }

    .template-file {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 12px;
        padding: 10px;
        background: #f8fafc;
        border-radius: 8px;
    }

    .file-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .file-left i {
        font-size: 20px;
        color: #2563eb;
    }

    .file-name {
        font-size: 12px;
        font-weight: 500;
        color: #374151;
    }

    .template-badge {
        background: #22c55e;
        color: #fff;
        padding: 4px 10px;
        border-radius: 14px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .template-badge.excel {
        background: #16a34a;
    }

    .template-info {
        display: flex;
        justify-content: space-between;
        color: #6b7280;
        font-size: 11px;
        margin-bottom: 15px;
    }

    .download-btn {
        width: 100%;
        height: 40px;
        border: 1.5px solid #22c55e;
        border-radius: 8px;
        background: #fff;
        color: #16a34a;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: 0.25s;
    }

    .download-btn:hover {
        background: #ecfdf5;
        border-color: #16a34a;
    }

    @media (max-width: 1200px) {
        .forms-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 700px) {
        .forms-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="forms-grid">

    <!-- CARD 1: Proposal Capsule Template -->
    <div class="form-template-card">
        <div class="template-title">Proposal Report</div>
        <div class="template-code">Template Code: 25-c34a7</div>
        <div class="template-desc">Standard proposal report format.</div>
        <div class="template-file">
            <div class="file-left">
                <i class="fas fa-file-word"></i>
                <span class="file-name">Proposal Template</span>
            </div>
            <div class="template-badge">Capsule</div>
        </div>
        <div class="template-info">
            <span>0.6 mb</span>
            <span>Word</span>
        </div>
        <a href="/templates/proposal_template.docx" download class="download-btn">
            <i class="fas fa-download"></i> Download
        </a>
    </div>

   

    <!-- CARD 3: Progress Report Template -->
    <div class="form-template-card">
        <div class="template-title">Progress Report</div>
        <div class="template-code">Template Code: PRG-2026</div>
        <div class="template-desc">Standard progress report format for approved proposals.</div>
        <div class="template-file">
            <div class="file-left">
                <i class="fas fa-file-word"></i>
                <span class="file-name">Progress Template</span>
            </div>
            <div class="template-badge">Progress</div>
        </div>
        <div class="template-info">
            <span>0.05 mb</span>
            <span>Word</span>
        </div>
        <a href="/templates/progress_report_template.docx" download class="download-btn">
            <i class="fas fa-download"></i> Download
        </a>
    </div>

    <!-- CARD 4: Terminal Report Template -->
    <div class="form-template-card">
        <div class="template-title">Terminal Report</div>
        <div class="template-code">Template Code: TRM-2026</div>
        <div class="template-desc">Final terminal report format for completed projects.</div>
        <div class="template-file">
            <div class="file-left">
                <i class="fas fa-file-word"></i>
                <span class="file-name">Terminal Template</span>
            </div>
            <div class="template-badge">Terminal</div>
        </div>
        <div class="template-info">
            <span>0.04 mb</span>
            <span>Word</span>
        </div>
        <a href="/templates/terminal_report_template.docx" download class="download-btn">
            <i class="fas fa-download"></i> Download
        </a>
    </div>

    <!-- CARD 5: HDGD Scoresheet -->
    <!-- <div class="form-template-card">
        <div class="template-title">2026 HDGD</div>
        <div class="template-code">Template Code: HDGD-001</div>
        <div class="template-desc">Evaluation scoring template.</div>
        <div class="template-file">
            <div class="file-left">
                <i class="fas fa-file-excel"></i>
                <span class="file-name">Evaluation Sheet</span>
            </div>
            <div class="template-badge excel">Scoresheet</div>
        </div>
        <div class="template-info">
            <span>0.61 mb</span>
            <span>Excel</span>
        </div>
        <a href="/templates/evaluation_scoresheet_hdgd.xlsx" download class="download-btn">
            <i class="fas fa-download"></i> Download
        </a>
    </div> -->

    <!-- CARD 6: HDGD PIMME Scoresheet -->
    <!-- <div class="form-template-card">
        <div class="template-title">HDGD PIMME</div>
        <div class="template-code">Template Code: HDGD</div>
        <div class="template-desc">HDGD-PIMME evaluation sheet.</div>
        <div class="template-file">
            <div class="file-left">
                <i class="fas fa-file-excel"></i>
                <span class="file-name">PIMME Sheet</span>
            </div>
            <div class="template-badge excel">Scoresheet</div>
        </div>
        <div class="template-info">
            <span>0.61 mb</span>
            <span>Excel</span>
        </div>
        <a href="/templates/hdgd_pimme_scoresheet.xlsx" download class="download-btn">
            <i class="fas fa-download"></i> Download
        </a>
    </div> -->

</div>