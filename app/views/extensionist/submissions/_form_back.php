<?php
$basic = $form_data['basic_info'] ?? [];
$budget = $form_data['budget_breakdown'] ?? [];
$detailed = $form_data['detailed_proposal'] ?? [];
$line_items = $form_data['budget_line_items'] ?? [];
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
        <input type="text" name="project_leader" class="submission-input" value="<?= htmlspecialchars($basic['project_leader'] ?? '') ?>" required>
    </div>

    <div class="submission-group full">
        <label class="submission-label">2. Project Component Title</label>
        <input type="text" name="component_title" class="submission-input" value="<?= htmlspecialchars($basic['component_title'] ?? '') ?>">
    </div>

    <div class="submission-group full">
        <label class="submission-label">Project Component Leader</label>
        <input type="text" name="component_leader" class="submission-input" value="<?= htmlspecialchars($basic['component_leader'] ?? '') ?>">
    </div>

    <div class="submission-group full">
        <label class="submission-label">3. Implementing Campus <span style="color:red;">*</span></label>
        <input type="text" name="implementing_campus" class="submission-input" value="<?= htmlspecialchars($basic['implementing_campus'] ?? 'ISU') ?>" required>
    </div>
    <div class="submission-group">
        <label class="submission-label">a. Lead Unit/College <span style="color:red;">*</span></label>
        <!-- Auto-filled from user's college -->
        <input type="text" name="lead_unit" class="submission-input" value="<?= htmlspecialchars($user_college ?? '') ?>" readonly style="background:#f0f0f0;">
        <p style="font-size:12px; color:#6c757d; margin-top:5px;">Auto-filled from your assigned college.</p>
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

    <!-- BUDGET BREAKDOWN TABLE -->
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

    <!-- SECTION B: DETAILED PROPOSAL -->
    <div class="submission-group full" style="background:#f8fafc; font-weight:700; font-size:18px; padding:15px 22px; margin-top:20px;">
        <i class="fas fa-file-alt"></i> B. DETAILED PROPOSAL
    </div>

    <div class="submission-group full">
        <label class="submission-label">a. Rationale</label>
        <textarea name="rationale" class="submission-textarea" rows="5"><?= htmlspecialchars($detailed['rationale'] ?? '') ?></textarea>
    </div>

    <div class="submission-group full">
        <label class="submission-label">b. General Objective</label>
        <textarea name="general_objective" class="submission-textarea" rows="3"><?= htmlspecialchars($detailed['general_objective'] ?? '') ?></textarea>
    </div>

    <div class="submission-group full">
        <label class="submission-label">Specific Objectives</label>
        <textarea name="specific_objectives" class="submission-textarea" rows="4"><?= htmlspecialchars($detailed['specific_objectives'] ?? '') ?></textarea>
    </div>

    <div class="submission-group full">
        <label class="submission-label">c. Expected Outputs/Target</label>
        <textarea name="expected_outputs" class="submission-textarea" rows="4"><?= htmlspecialchars($detailed['expected_outputs'] ?? '') ?></textarea>
    </div>

    <div class="submission-group full">
        <label class="submission-label">d. Project Components/Strategies of Implementation</label>
        <textarea name="components" class="submission-textarea" rows="5"><?= htmlspecialchars($detailed['components'] ?? '') ?></textarea>
    </div>

    <div class="submission-group full">
        <label class="submission-label">e. Methodology</label>
        <textarea name="methodology" class="submission-textarea" rows="5"><?= htmlspecialchars($detailed['methodology'] ?? '') ?></textarea>
    </div>

    <div class="submission-group full">
        <label class="submission-label">f. Conceptual/Operational Framework/Extension Program Framework</label>
        <textarea name="framework" class="submission-textarea" rows="4"><?= htmlspecialchars($detailed['framework'] ?? '') ?></textarea>
    </div>

    <div class="submission-group full">
        <label class="submission-label">g. Monitoring and Evaluation</label>
        <textarea name="monitoring" class="submission-textarea" rows="4"><?= htmlspecialchars($detailed['monitoring'] ?? '') ?></textarea>
    </div>

    <div class="submission-group full">
        <label class="submission-label">h. Sustainability Plan/Way Forward</label>
        <textarea name="sustainability" class="submission-textarea" rows="4"><?= htmlspecialchars($detailed['sustainability'] ?? '') ?></textarea>
    </div>

    <!-- LINE ITEM BUDGET -->
    <div class="submission-group full" style="background:#f8fafc; font-weight:700; font-size:16px; padding:15px 22px; margin-top:20px;">
        <i class="fas fa-coins"></i> i. Budgetary Breakdown By Line Item Budget
    </div>

    <div class="submission-group full" style="padding:0; border:none;">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:12px; border:1px solid #dfe5ec; text-align:left;">Particular/Item Budget</th>
                    <th style="padding:12px; border:1px solid #dfe5ec;">Budget</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding:12px; border:1px solid #dfe5ec; font-weight:600;">A. Personal Services (PS)</td>
                    <td style="padding:12px; border:1px solid #dfe5ec;"><input type="number" name="ps_budget" class="submission-input" style="width:100%;" value="<?= $line_items['ps'] ?? 0 ?>" step="0.01"></td>
                </tr>
                <tr>
                    <td style="padding:12px; border:1px solid #dfe5ec; font-weight:600;">B. Maintenance and Operating Expenses (MOOE)</td>
                    <td style="padding:12px; border:1px solid #dfe5ec;"></td>
                </tr>
                <tr>
                    <td style="padding:12px; border:1px solid #dfe5ec; padding-left:30px;">- Travel</td>
                    <td style="padding:12px; border:1px solid #dfe5ec;"><input type="number" name="mooe_travel" class="submission-input" style="width:100%;" value="<?= $line_items['mooe_travel'] ?? 0 ?>" step="0.01"></td>
                </tr>
                <tr>
                    <td style="padding:12px; border:1px solid #dfe5ec; padding-left:30px;">- Supplies and Materials</td>
                    <td style="padding:12px; border:1px solid #dfe5ec;"><input type="number" name="mooe_supplies" class="submission-input" style="width:100%;" value="<?= $line_items['mooe_supplies'] ?? 0 ?>" step="0.01"></td>
                </tr>
                <tr>
                    <td style="padding:12px; border:1px solid #dfe5ec; padding-left:30px;">- Meals and Snack</td>
                    <td style="padding:12px; border:1px solid #dfe5ec;"><input type="number" name="mooe_meals" class="submission-input" style="width:100%;" value="<?= $line_items['mooe_meals'] ?? 0 ?>" step="0.01"></td>
                </tr>
                <tr>
                    <td style="padding:12px; border:1px solid #dfe5ec; padding-left:30px;">- Communication</td>
                    <td style="padding:12px; border:1px solid #dfe5ec;"><input type="number" name="mooe_communication" class="submission-input" style="width:100%;" value="<?= $line_items['mooe_communication'] ?? 0 ?>" step="0.01"></td>
                </tr>
                <tr>
                    <td style="padding:12px; border:1px solid #dfe5ec; padding-left:30px;">- Other Services (Wages)</td>
                    <td style="padding:12px; border:1px solid #dfe5ec;"><input type="number" name="mooe_services" class="submission-input" style="width:100%;" value="<?= $line_items['mooe_services'] ?? 0 ?>" step="0.01"></td>
                </tr>
                <tr>
                    <td style="padding:12px; border:1px solid #dfe5ec; font-weight:600;">C. Capital Outlay (CO)</td>
                    <td style="padding:12px; border:1px solid #dfe5ec;"><input type="number" name="co_budget" class="submission-input" style="width:100%;" value="<?= $line_items['co'] ?? 0 ?>" step="0.01"></td>
                </tr>
                <tr>
                    <td style="padding:12px; border:1px solid #dfe5ec; font-weight:700;">TOTAL</td>
                    <td style="padding:12px; border:1px solid #dfe5ec; font-weight:700; text-align:center;">Auto</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- GANTT CHART -->
    <!-- GANTT CHART -->
   
    <!-- GANTT CHART -->
    <div class="submission-group full" style="background:#f8fafc; font-weight:700; font-size:16px; padding:15px 22px; margin-top:20px;">
        <i class="fas fa-chart-bar"></i> j. Schedule of Activities / Work Plan (Gantt Chart)
    </div>

    <div class="submission-group full" style="padding:0; border:none;">
        <div style="overflow-x:auto;">
            <table id="ganttTable" style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:10px; border:1px solid #dfe5ec; min-width:150px;">Activities</th>
                        <th style="padding:10px; border:1px solid #dfe5ec; min-width:150px;">Deliverables</th>
                        <th style="padding:10px; border:1px solid #dfe5ec; min-width:150px;">Outputs</th>
                        <th style="padding:10px; border:1px solid #dfe5ec; text-align:center;" colspan="12">Months</th>
                        <th style="padding:10px; border:1px solid #dfe5ec; min-width:60px;">Action</th>
                    </tr>
                    <tr>
                        <th style="padding:5px; border:1px solid #dfe5ec;"></th>
                        <th style="padding:5px; border:1px solid #dfe5ec;"></th>
                        <th style="padding:5px; border:1px solid #dfe5ec;"></th>
                        <?php foreach (['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $month): ?>
                            <th style="padding:5px; border:1px solid #dfe5ec; text-align:center; font-weight:600; font-size:11px;"><?= $month ?></th>
                        <?php endforeach; ?>
                        <th style="padding:5px; border:1px solid #dfe5ec;"></th>
                    </tr>
                </thead>
                <tbody id="ganttBody">
                    <?php
                    $ganttData = $form_data['gantt'] ?? [];
                    if (!empty($ganttData)):
                        foreach ($ganttData as $index => $row):
                    ?>
                            <tr class="gantt-row">
                                <td style="padding:8px; border:1px solid #dfe5ec;">
                                    <input type="text" name="gantt_activity[<?= $index ?>]" class="submission-input" style="width:100%; height:40px;" value="<?= htmlspecialchars($row['activity'] ?? '') ?>">
                                </td>
                                <td style="padding:8px; border:1px solid #dfe5ec;">
                                    <input type="text" name="gantt_deliverables[<?= $index ?>]" class="submission-input" style="width:100%; height:40px;" value="<?= htmlspecialchars($row['deliverables'] ?? '') ?>">
                                </td>
                                <td style="padding:8px; border:1px solid #dfe5ec;">
                                    <input type="text" name="gantt_outputs[<?= $index ?>]" class="submission-input" style="width:100%; height:40px;" value="<?= htmlspecialchars($row['outputs'] ?? '') ?>">
                                </td>
                                <?php foreach (['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $month): ?>
                                    <td style="padding:8px; border:1px solid #dfe5ec; text-align:center;">
                                        <input type="checkbox" name="gantt_months[<?= $index ?>][]" value="<?= $month ?>" <?= in_array($month, $row['months'] ?? []) ? 'checked' : '' ?> style="width:20px; height:20px; cursor:pointer;">
                                    </td>
                                <?php endforeach; ?>
                                <td style="padding:8px; border:1px solid #dfe5ec; text-align:center;">
                                    <button type="button" class="remove-row-btn" onclick="removeRow(this)" style="background:#dc3545; color:#fff; border:none; border-radius:4px; padding:6px 12px; cursor:pointer;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach;
                    else: ?>
                        <!-- Empty row placeholder -->
                        <tr class="gantt-row">
                            <td style="padding:8px; border:1px solid #dfe5ec;"><input type="text" name="gantt_activity[0]" class="submission-input" style="width:100%; height:40px;"></td>
                            <td style="padding:8px; border:1px solid #dfe5ec;"><input type="text" name="gantt_deliverables[0]" class="submission-input" style="width:100%; height:40px;"></td>
                            <td style="padding:8px; border:1px solid #dfe5ec;"><input type="text" name="gantt_outputs[0]" class="submission-input" style="width:100%; height:40px;"></td>
                            <?php foreach (['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $month): ?>
                                <td style="padding:8px; border:1px solid #dfe5ec; text-align:center;">
                                    <input type="checkbox" name="gantt_months[0][]" value="<?= $month ?>" style="width:20px; height:20px; cursor:pointer;">
                                </td>
                            <?php endforeach; ?>
                            <td style="padding:8px; border:1px solid #dfe5ec; text-align:center;">
                                <button type="button" class="remove-row-btn" onclick="removeRow(this)" style="background:#dc3545; color:#fff; border:none; border-radius:4px; padding:6px 12px; cursor:pointer;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div style="margin-top:15px;">
            <button type="button" onclick="addGanttRow()" style="padding:10px 20px; background:#2563eb; color:#fff; border:none; border-radius:6px; cursor:pointer;">
                <i class="fas fa-plus"></i> Add Row
            </button>
        </div>
    </div>


   
    <!-- FILE ATTACHMENT -->
    <div class="submission-group full">
        <label class="submission-label"><i class="fas fa-paperclip"></i> Attach File</label>
        <input type="file" name="attachment" class="submission-input file-upload">
        <p style="font-size:12px; color:#6c757d; margin-top:5px;">Upload supporting documents (PDF, DOC, DOCX, max 10MB)</p>
    </div>

</div>

<script>
    let rowCounter = <?= count($ganttData ?? []) > 0 ? count($ganttData) - 1 : 0 ?>;

    function addGanttRow() {
        rowCounter++;
        const tbody = document.getElementById('ganttBody');
        const newRow = document.createElement('tr');
        newRow.className = 'gantt-row';
        newRow.innerHTML = `
        <td style="padding:8px; border:1px solid #dfe5ec;">
            <input type="text" name="gantt_activity[${rowCounter}]" class="submission-input" style="width:100%; height:40px;">
        </td>
        <td style="padding:8px; border:1px solid #dfe5ec;">
            <input type="text" name="gantt_deliverables[${rowCounter}]" class="submission-input" style="width:100%; height:40px;">
        </td>
        <td style="padding:8px; border:1px solid #dfe5ec;">
            <input type="text" name="gantt_outputs[${rowCounter}]" class="submission-input" style="width:100%; height:40px;">
        </td>
        ${['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'].map(m => `
            <td style="padding:8px; border:1px solid #dfe5ec; text-align:center;">
                <input type="checkbox" name="gantt_months[${rowCounter}][]" value="${m}" style="width:20px; height:20px; cursor:pointer;">
            </td>
        `).join('')}
        <td style="padding:8px; border:1px solid #dfe5ec; text-align:center;">
            <button type="button" class="remove-row-btn" onclick="removeRow(this)" style="background:#dc3545; color:#fff; border:none; border-radius:4px; padding:6px 12px; cursor:pointer;">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
        tbody.appendChild(newRow);
    }

    function removeRow(btn) {
        const row = btn.closest('tr');
        if (document.getElementById('ganttBody').children.length > 1) {
            row.remove();
        } else {
            alert('You must have at least one row.');
        }
    }
</script>