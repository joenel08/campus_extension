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
        margin-top: auto;
    }

    .download-btn:hover {
        background: #ecfdf5;
        border-color: #16a34a;
    }

    .download-btn.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        border-color: #d1d5db;
        color: #9ca3af;
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

    <?php if (empty($templates)): ?>
        <p style="grid-column:1/-1; text-align:center; color:#777; padding:40px 0;">
            No templates available yet.
        </p>
    <?php else: ?>
        <?php foreach ($templates as $t): ?>
            <?php
            // Pick the icon based on file type
            $icon = 'fa-file';
            $badgeClass = '';
            $type = strtolower($t['file_type'] ?? '');

            if ($type === 'word')       $icon = 'fa-file-word';
            elseif ($type === 'excel')  { $icon = 'fa-file-excel'; $badgeClass = 'excel'; }
            elseif ($type === 'powerpoint') $icon = 'fa-file-powerpoint';
            elseif ($type === 'pdf')    $icon = 'fa-file-pdf';
            ?>
            <div class="form-template-card">
                <div class="template-title"><?= htmlspecialchars($t['title']) ?></div>
                <!-- <div class="template-code">Template Code: <?= htmlspecialchars($t['template_code']) ?></div> -->
                <div class="template-desc"><?= htmlspecialchars($t['description'] ?? '') ?></div>

                <div class="template-file">
                    <div class="file-left">
                        <i class="fas <?= $icon ?>"></i>
                        <span class="file-name"><?= htmlspecialchars($t['title']) ?></span>
                    </div>
                    <?php if (!empty($t['file_type'])): ?>
                        <div class="template-badge <?= $badgeClass ?>">
                            <?= htmlspecialchars($t['file_type']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="template-info">
                    <span><?= htmlspecialchars($t['file_size'] ?? '—') ?></span>
                    <span><?= htmlspecialchars($t['file_type'] ?? '—') ?></span>
                </div>

                <?php if (!empty($t['attached_file'])): ?>
                    <a href="/<?= htmlspecialchars($t['attached_file']) ?>" download class="download-btn">
                        <i class="fas fa-download"></i> Download
                    </a>
                <?php else: ?>
                    <button class="download-btn disabled" disabled>
                        <i class="fas fa-ban"></i> No File
                    </button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>