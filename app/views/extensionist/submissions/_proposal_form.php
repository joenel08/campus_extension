<?php
$basic = $form_data['basic_info'] ?? [];
$budget = $form_data['budget_breakdown'] ?? [];
?>

<div class="submission-grid">

    <!-- SECTION A: BASIC INFORMATION -->
    <div class="submission-group full" style="background:#f8fafc; font-weight:700; font-size:18px; padding:15px 22px;">
        <i class="fas fa-info-circle"></i> A. BASIC INFORMATION
    </div>

    <div class="submission-group full">
        <label class="submission-label">1. Program/Project Title <span style="color:red;">*</span></label>
        <input type="text" name="project_title" class="submission-input" value="<?= htmlspecialchars($basic['project_title'] ?? '') ?>" required>
    </div>

    <div class="submission-group full">
        <label class="submission-label">Project Leader <span style="color:red;">*</span></label>
        <input type="text" name="project_leader" class="submission-input"
            value="<?= htmlspecialchars(
                        !empty($basic['project_leader']) ? $basic['project_leader'] : ($user_name ?? '')
                    ) ?>" readonly style="background:#f0f0f0;">
        <p style="font-size:12px; color:#6c757d; margin-top:5px;">Auto-filled from your account (read-only).</p>
    </div>

    <!-- COMPONENTS (Dynamic) -->
    <div class="submission-group full" style="background:#f8fafc; font-weight:700; font-size:16px; padding:15px 22px; border-top:1px solid #dfe5ec;">
        <i class="fas fa-layer-group"></i> Project Components
    </div>

    <div class="submission-group full" style="padding:0; border:none;">
        <div style="overflow-x:auto;">
            <table id="componentsTable" style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:10px; border:1px solid #dfe5ec; min-width:250px;">Component Title</th>
                        <th style="padding:10px; border:1px solid #dfe5ec; min-width:250px;">Component Leader</th>
                        <th style="padding:10px; border:1px solid #dfe5ec; min-width:60px;">Action</th>
                    </tr>
                </thead>
                <tbody id="componentsBody">
                    <?php
                    $components = $form_data['components'] ?? [];
                    if (!empty($components)):
                        foreach ($components as $index => $comp):
                    ?>
                            <tr class="component-row">
                                <td style="padding:8px; border:1px solid #dfe5ec;">
                                    <input type="text" name="component_title[]" class="submission-input" style="width:100%; height:40px;" value="<?= htmlspecialchars($comp['title'] ?? '') ?>">
                                </td>
                                <td style="padding:8px; border:1px solid #dfe5ec;">
                                    <input type="text" name="component_leader[]" class="submission-input" style="width:100%; height:40px;" value="<?= htmlspecialchars($comp['leader'] ?? '') ?>">
                                </td>
                                <td style="padding:8px; border:1px solid #dfe5ec; text-align:center;">
                                    <button type="button" class="remove-component-btn" onclick="removeComponentRow(this)" style="background:#dc3545; color:#fff; border:none; border-radius:4px; padding:6px 12px; cursor:pointer;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach;
                    else: ?>
                        <tr class="component-row">
                            <td style="padding:8px; border:1px solid #dfe5ec;">
                                <input type="text" name="component_title[]" class="submission-input" style="width:100%; height:40px;">
                            </td>
                            <td style="padding:8px; border:1px solid #dfe5ec;">
                                <input type="text" name="component_leader[]" class="submission-input" style="width:100%; height:40px;">
                            </td>
                            <td style="padding:8px; border:1px solid #dfe5ec; text-align:center;">
                                <button type="button" class="remove-component-btn" onclick="removeComponentRow(this)" style="background:#dc3545; color:#fff; border:none; border-radius:4px; padding:6px 12px; cursor:pointer;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div>
            <button type="button" onclick="addComponentRow()" style="margin-left:10px; padding:10px 20px; background:#2563eb; color:#fff; border:none; border-radius:6px; cursor:pointer;">
                <i class="fas fa-plus"></i> Add Component
            </button>
        </div>
    </div>

    <div class="submission-group full">
        <label class="submission-label">3. Implementing Campus <span style="color:red;">*</span></label>
        <input type="text" name="implementing_campus" class="submission-input" value="<?= htmlspecialchars($basic['implementing_campus'] ?? 'ISU') ?>" required>
    </div>

    <div class="submission-group">
        <label class="submission-label">a. Lead Unit/College <span style="color:red;">*</span></label>
        <input type="text" name="lead_unit" class="submission-input"
            value="<?= htmlspecialchars($user_college ?? '') ?>" readonly style="background:#f0f0f0;">
        <p style="font-size:12px; color:#6c757d; margin-top:5px;">
            Auto-filled from your assigned college.
            <?php if (empty($user_college)): ?>
                <span style="color:#dc3545;">No college assigned to your account. Please contact admin.</span>
            <?php endif; ?>
        </p>
    </div>

    <div class="submission-group">
        <label class="submission-label">b. Cooperating Unit/College</label>
        <select name="cooperating_unit" class="submission-select">
            <option value="">Select Cooperating Unit/College</option>
            <?php foreach ($colleges as $c): ?>
                <option value="<?= htmlspecialchars($c['abbreviation']) ?>" <?= ($basic['cooperating_unit'] ?? '') === $c['abbreviation'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['abbreviation']) ?> - <?= htmlspecialchars($c['description']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="submission-group full">
        <label class="submission-label">c. Project Site</label>
        <input type="text" name="project_site" class="submission-input" value="<?= htmlspecialchars($basic['project_site'] ?? '') ?>">
    </div>

    <div class="submission-group full">
        <label class="submission-label">4. Cooperating Agencies</label>
        <textarea name="cooperating_agencies" class="submission-textarea" rows="3"><?= htmlspecialchars($basic['cooperating_agencies'] ?? '') ?></textarea>
    </div>

    <div class="submission-group">
        <label class="submission-label">5. Date Started</label>
        <input type="date" name="date_started" class="submission-input" value="<?= htmlspecialchars($basic['date_started'] ?? '') ?>">
    </div>

    <div class="submission-group">
        <label class="submission-label">Date Completed</label>
        <input type="date" name="date_completed" class="submission-input" value="<?= htmlspecialchars($basic['date_completed'] ?? '') ?>">
    </div>

    <div class="submission-group full">
        <label class="submission-label">Status</label>
        <select name="project_status" class="submission-select">
            <option value="New" <?= ($basic['status'] ?? 'New') === 'New' ? 'selected' : '' ?>>New</option>
        </select>
        <p style="font-size:12px; color:#6c757d; margin-top:5px;">Status will be updated by the director once reviewed.</p>
    </div>

    <div class="submission-group full">
        <label class="submission-label">6. Project Beneficiaries</label>
        <input type="text" name="beneficiaries" class="submission-input" value="<?= htmlspecialchars($basic['beneficiaries'] ?? '') ?>">
    </div>

    <div class="submission-group">
        <label class="submission-label">7. Funding Agency/ies</label>
        <input type="text" name="funding_agency" class="submission-input" value="<?= htmlspecialchars($basic['funding_agency'] ?? '') ?>">
    </div>

    <div class="submission-group">
        <label class="submission-label">Budget</label>
        <input type="number" name="budget" class="submission-input" value="<?= htmlspecialchars($basic['budget'] ?? '') ?>" step="0.01">
    </div>

    <!-- BUDGET BREAKDOWN -->
    <div class="submission-group full" style="background:#f8fafc; font-weight:700; font-size:16px; padding:15px 22px;">
        <i class="fas fa-table"></i> 8. Budget Requirement / Budget
    </div>

    <div class="submission-group full" style="padding:0; border:none;">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:12px; border:1px solid #dfe5ec;">Fund Year</th>
                    <th style="padding:12px; border:1px solid #dfe5ec;">PS</th>
                    <th style="padding:12px; border:1px solid #dfe5ec;">MOOE</th>
                    <th style="padding:12px; border:1px solid #dfe5ec;">CO</th>
                    <th style="padding:12px; border:1px solid #dfe5ec;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding:12px; border:1px solid #dfe5ec; font-weight:600;">Year 1</td>
                    <td style="padding:12px; border:1px solid #dfe5ec;"><input type="number" name="year1_ps" class="submission-input" style="width:100%;" value="<?= $budget['year1_ps'] ?? 0 ?>" step="0.01"></td>
                    <td style="padding:12px; border:1px solid #dfe5ec;"><input type="number" name="year1_mooe" class="submission-input" style="width:100%;" value="<?= $budget['year1_mooe'] ?? 0 ?>" step="0.01"></td>
                    <td style="padding:12px; border:1px solid #dfe5ec;"><input type="number" name="year1_co" class="submission-input" style="width:100%;" value="<?= $budget['year1_co'] ?? 0 ?>" step="0.01"></td>
                    <td style="padding:12px; border:1px solid #dfe5ec; font-weight:600; text-align:center;">Auto</td>
                </tr>
                <tr>
                    <td style="padding:12px; border:1px solid #dfe5ec; font-weight:600;">Year 2</td>
                    <td style="padding:12px; border:1px solid #dfe5ec;"><input type="number" name="year2_ps" class="submission-input" style="width:100%;" value="<?= $budget['year2_ps'] ?? 0 ?>" step="0.01"></td>
                    <td style="padding:12px; border:1px solid #dfe5ec;"><input type="number" name="year2_mooe" class="submission-input" style="width:100%;" value="<?= $budget['year2_mooe'] ?? 0 ?>" step="0.01"></td>
                    <td style="padding:12px; border:1px solid #dfe5ec;"><input type="number" name="year2_co" class="submission-input" style="width:100%;" value="<?= $budget['year2_co'] ?? 0 ?>" step="0.01"></td>
                    <td style="padding:12px; border:1px solid #dfe5ec; font-weight:600; text-align:center;">Auto</td>
                </tr>
                <tr>
                    <td style="padding:12px; border:1px solid #dfe5ec; font-weight:600;">Year 3</td>
                    <td style="padding:12px; border:1px solid #dfe5ec;"><input type="number" name="year3_ps" class="submission-input" style="width:100%;" value="<?= $budget['year3_ps'] ?? 0 ?>" step="0.01"></td>
                    <td style="padding:12px; border:1px solid #dfe5ec;"><input type="number" name="year3_mooe" class="submission-input" style="width:100%;" value="<?= $budget['year3_mooe'] ?? 0 ?>" step="0.01"></td>
                    <td style="padding:12px; border:1px solid #dfe5ec;"><input type="number" name="year3_co" class="submission-input" style="width:100%;" value="<?= $budget['year3_co'] ?? 0 ?>" step="0.01"></td>
                    <td style="padding:12px; border:1px solid #dfe5ec; font-weight:600; text-align:center;">Auto</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- FILE ATTACHMENT -->
    <!-- FILE ATTACHMENT -->
<div class="submission-group full">
    <label class="submission-label"><i class="fas fa-paperclip"></i> Attach File</label>

    <!-- Download Template -->
    <div style="margin-bottom:12px; padding:12px; background:#e8f0fe; border-radius:6px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
        <div style="display:flex; align-items:center; gap:10px;">
            <i class="fas fa-file-word" style="font-size:20px; color:#2563eb;"></i>
            <div>
                <strong>Proposal Template</strong>
                <p style="font-size:12px; color:#6c757d; margin:2px 0 0 0;">Download and fill out the official proposal format.</p>
            </div>
        </div>
        <a href="/templates/proposal_template.docx" download class="btn btn-sm btn-primary" style="text-decoration:none; padding:8px 16px; background:#2563eb; color:#fff; border-radius:6px; font-weight:600; display:inline-flex; align-items:center; gap:6px;">
            <i class="fas fa-download"></i> Download Template
        </a>
    </div>

    <!-- Existing file display (if any) -->
    <?php if (!empty($form_data['attachment'])): ?>
        <div style="margin-bottom:10px; padding:12px; background:#e6f7ea; border-radius:6px; display:flex; align-items:center; gap:10px;">
            <i class="fas fa-file-alt" style="font-size:20px; color:#16a34a;"></i>
            <div style="flex:1;">
                <strong>Current File:</strong>
                <a href="/<?= htmlspecialchars($form_data['attachment']) ?>" target="_blank" style="color:#2563eb; margin-left:5px;">
                    <?= basename($form_data['attachment']) ?>
                </a>
            </div>
            <a href="/<?= htmlspecialchars($form_data['attachment']) ?>" download class="btn btn-sm btn-primary" style="padding:6px 12px; background:#16a34a; color:#fff; border-radius:6px; text-decoration:none;">
                <i class="fas fa-download"></i>
            </a>
        </div>
        <p style="font-size:12px; color:#6c757d; margin-bottom:5px;">Upload a new file to replace the current one:</p>
    <?php endif; ?>

    <input type="file" name="attachment" class="submission-input file-upload">
    <p style="font-size:12px; color:#6c757d; margin-top:5px;">
        <?= !empty($form_data['attachment']) ? 'Leave empty to keep the current file.' : 'Upload supporting documents (PDF, DOC, DOCX, max 10MB)' ?>
    </p>
</div>

</div>

<script>
    function addComponentRow() {
        const tbody = document.getElementById('componentsBody');
        const newRow = document.createElement('tr');
        newRow.className = 'component-row';
        newRow.innerHTML = `
            <td style="padding:8px; border:1px solid #dfe5ec;">
                <input type="text" name="component_title[]" class="submission-input" style="width:100%; height:40px;">
            </td>
            <td style="padding:8px; border:1px solid #dfe5ec;">
                <input type="text" name="component_leader[]" class="submission-input" style="width:100%; height:40px;">
            </td>
            <td style="padding:8px; border:1px solid #dfe5ec; text-align:center;">
                <button type="button" class="remove-component-btn" onclick="removeComponentRow(this)" style="background:#dc3545; color:#fff; border:none; border-radius:4px; padding:6px 12px; cursor:pointer;">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(newRow);
    }

    function removeComponentRow(btn) {
        const row = btn.closest('tr');
        if (document.getElementById('componentsBody').children.length > 1) {
            row.remove();
        } else {
            alert('You must have at least one component.');
        }
    }
</script>