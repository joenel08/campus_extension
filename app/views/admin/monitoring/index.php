<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<style>
@media print {
    .sidebar, .sidebar-top, .sidebar-nav, .sidebar-bottom, .topbar,
    .notification-wrapper, .filter-bar, .table-header button, .btn, .modal,
    .alert, form, .notification-panel { display: none !important; }
    body { background: #fff !important; display: block !important; }
    .main { margin-left: 0 !important; padding: 0 !important; width: 100% !important; }
    .card { box-shadow: none !important; border: none !important; padding: 0 !important; margin: 0 !important; border-radius: 0 !important; }
    .print-header { display: block !important; text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #000; }
    .print-header h1 { font-size: 20px; margin: 0 0 5px; color: #000; }
    .print-header p { font-size: 13px; color: #333; margin: 2px 0; }
    table { width: 100% !important; border-collapse: collapse !important; font-size: 11px !important; page-break-inside: auto; }
    thead { display: table-header-group; }
    tr { page-break-inside: avoid; }
    th, td { border: 1px solid #000 !important; padding: 6px !important; text-align: left !important; color: #000 !important; }
    th { background: #f0f0f0 !important; font-weight: 700 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .badge { padding: 2px 6px !important; border-radius: 0 !important; font-size: 10px !important; border: 1px solid #000; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    td a.btn, td button.btn { display: none !important; }
    @page { size: A4 landscape; margin: 15mm; }
}
.print-header { display: none; }
</style>

<div class="card">
    <div class="print-header">
        <h1>Isabela State University — Extension & Training Services</h1>
        <p>Submission Monitoring Report</p>
        <p>Generated: <?= date('F d, Y h:i A') ?></p>
    </div>

    <div class="table-header">
        <div>
            <h2><i class="fas fa-folder-open"></i> Submission Monitoring</h2>
            <p class="table-subtitle">View proposals, detailed proposals, progress, and terminal reports grouped by proposal</p>
        </div>
    </div>

    <form method="GET" action="/admin/monitoring" style="display:flex; gap:15px; flex-wrap:wrap; margin-bottom:20px; padding:15px; background:#f8fafc; border-radius:8px;">
        <div>
            <label>Status</label>
            <select name="status" class="form-input">
                <option value="">All</option>
                <option value="approved" <?= ($filters['status'] ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
                <option value="revision" <?= ($filters['status'] ?? '') === 'revision' ? 'selected' : '' ?>>Revision</option>
            </select>
        </div>
        <div>
            <label>College</label>
            <select name="college_id" class="form-input">
                <option value="">All</option>
                <?php foreach ($colleges as $c): ?>
                    <option value="<?= (int)$c['id'] ?>" <?= ($filters['college_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['abbreviation']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Academic Year</label>
            <select name="academic_year_id" class="form-input">
                <option value="">All Years</option>
                <?php foreach ($years as $y): ?>
                    <option value="<?= (int)$y['id'] ?>" <?= ($filters['academic_year_id'] ?? '') == $y['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($y['year_label']) ?><?= $y['is_current'] ? ' (Current)' : '' ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="display:flex; align-items:flex-end; gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
            <a href="/admin/monitoring" class="btn btn-secondary">Clear</a>
        </div>
    </form>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Academic Year</th>
                <th>Extensionist</th>
                <th>Proposal Title</th>
                <th>College</th>
                <th>Evaluators</th>
                <th>Proposal Status</th>
                <th>Detailed Proposal</th>
                <th>Progress Reports</th>
                <th>Terminal Report</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($grouped)): ?>
                <tr><td colspan="10" style="text-align:center;color:#777;">No submissions found.</td></tr>
            <?php else: ?>
                <?php $counter = 1; ?>
                <?php foreach ($grouped as $pid => $data): ?>
                    <?php
                    $proposal = $data['proposal_submission'];
                    if (!$proposal) continue;

                    $dp = $data['detailed_proposal'] ?? null;
                    $dpApproved = $dp && $dp['status'] === 'approved';

                    $statusClass = [
                        'draft'              => 'badge-pending',
                        'submitted'          => 'badge-approved',
                        'approved'           => 'badge-approved',
                        'rejected'           => 'badge-declined',
                        'revision'           => 'badge-pending',
                        'pending_evaluation' => 'badge-pending',
                        'under_evaluation'   => 'badge-approved',
                    ];
                    ?>
                    <tr>
                        <td><?= $counter++ ?></td>
                        <td><?= htmlspecialchars($data['academic_year_label'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($data['extensionist_name']) ?></td>
                        <td><strong><?= htmlspecialchars($data['proposal_title']) ?></strong></td>
                        <td><?= htmlspecialchars($data['college_abbr']) ?></td>
                        <td><?= htmlspecialchars($data['evaluators'] ?: 'None assigned') ?></td>

                        <!-- PROPOSAL STATUS -->
                        <td>
                            <span class="badge <?= $statusClass[$proposal['status']] ?? 'badge-pending' ?>">
                                <?= safe_ucfirst($proposal['status']) ?>
                            </span>
                            <a href="/admin/submissions/show?id=<?= (int)$proposal['id'] ?>&type=proposal" class="btn btn-sm btn-edit">
                                <i class="fas fa-eye"></i>
                            </a>

                            <?php if ($proposal['status'] === 'submitted'): ?>
                                <button class="btn btn-sm btn-success" onclick="openActionModal(<?= (int)$proposal['id'] ?>, 'approve')"><i class="fas fa-check"></i></button>
                                <button class="btn btn-sm btn-warning" onclick="openActionModal(<?= (int)$proposal['id'] ?>, 'revise')"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" onclick="openActionModal(<?= (int)$proposal['id'] ?>, 'decline')"><i class="fas fa-times"></i></button>
                            <?php endif; ?>
                        </td>

                        <!-- DETAILED PROPOSAL  ← this cell was missing -->
                        <td>
                            <?php if ($proposal['status'] !== 'approved'): ?>
                                <span style="color:#999; font-size:12px;">—</span>
                            <?php elseif (!$dp): ?>
                                <span style="color:#999; font-size:12px;">Awaiting submission</span>
                            <?php else: ?>
                                <span class="badge <?= $statusClass[$dp['status']] ?? 'badge-pending' ?>">
                                    <?= safe_ucfirst($dp['status']) ?>
                                </span>
                                <a href="/admin/submissions/show?id=<?= (int)$dp['id'] ?>&type=detailed_proposal" class="btn btn-sm btn-edit">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <?php if ($dp['status'] === 'submitted'): ?>
                                    <button class="btn btn-sm btn-success" onclick="openActionModal(<?= (int)$dp['id'] ?>, 'approveDetailed')"><i class="fas fa-check"></i></button>
                                    <button class="btn btn-sm btn-warning" onclick="openActionModal(<?= (int)$dp['id'] ?>, 'reviseDetailed')"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" onclick="openActionModal(<?= (int)$dp['id'] ?>, 'declineDetailed')"><i class="fas fa-times"></i></button>
                                <?php endif; ?>

                                <?php if ($dpApproved): ?>
                                    <button class="btn btn-sm btn-primary" onclick="openAssignModal(<?= (int)$proposal['id'] ?>)" style="margin-top:4px;">
                                        <i class="fas fa-user-plus"></i> Assign Evaluators
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>

                        <!-- PROGRESS REPORTS -->
                        <td>
                            <?php if (!empty($data['progress_reports'])): ?>
                                <?php foreach ($data['progress_reports'] as $idx => $pr): ?>
                                    <div style="display:flex; align-items:center; gap:4px; margin:2px 0;">
                                        <span style="font-size:12px; min-width:50px;">R<?= $idx + 1 ?></span>
                                        <span class="badge <?= $statusClass[$pr['status']] ?? 'badge-pending' ?>">
                                            <?= safe_ucfirst($pr['status']) ?>
                                        </span>
                                        <a href="/admin/submissions/show?id=<?= (int)$pr['id'] ?>&type=progress" class="btn btn-sm btn-edit">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span style="color:#999; font-size:12px;">None</span>
                            <?php endif; ?>
                        </td>

                        <!-- TERMINAL REPORT -->
                        <td>
                            <?php if ($data['terminal_report']): ?>
                                <span class="badge <?= $statusClass[$data['terminal_report']['status']] ?? 'badge-pending' ?>">
                                    <?= safe_ucfirst($data['terminal_report']['status']) ?>
                                </span>
                                <a href="/admin/submissions/show?id=<?= (int)$data['terminal_report']['id'] ?>&type=terminal" class="btn btn-sm btn-edit">
                                    <i class="fas fa-eye"></i>
                                </a>
                            <?php else: ?>
                                <span style="color:#999; font-size:12px;">Not submitted</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Action Modal (Approve/Revise/Decline — for proposal AND detailed proposal) -->
<div id="actionModal" class="modal" style="display:none;">
    <div class="modal-content" style="max-width:500px;">
        <div class="modal-header">
            <h3 id="modalTitle">Action</h3>
            <button class="close-modal" onclick="closeActionModal()">&times;</button>
        </div>
        <form method="POST" id="actionForm">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="actionId">
            <div style="margin-bottom:15px;">
                <label>Remarks (optional)</label>
                <textarea name="remarks" class="form-input" rows="4" placeholder="Provide feedback to the extensionist..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary" id="actionSubmitBtn">Confirm</button>
            <button type="button" class="btn btn-secondary" onclick="closeActionModal()">Cancel</button>
        </form>
    </div>
</div>

<!-- Assign Evaluators Modal -->
<div id="assignModal" class="modal" style="display:none;">
    <div class="modal-content" style="max-width:600px;">
        <div class="modal-header">
            <h3>Assign Evaluators</h3>
            <button class="close-modal" onclick="closeAssignModal()">&times;</button>
        </div>
        <div id="assignModalBody">
            <p style="text-align:center; padding:20px;">Loading...</p>
        </div>
    </div>
</div>

<script>
    const actionMap = {
        'approve':         { url: '/admin/monitoring/approve',          title: 'Approve Proposal',          btnClass: 'btn-success' },
        'revise':          { url: '/admin/monitoring/revise',           title: 'Request Proposal Revision', btnClass: 'btn-warning' },
        'decline':         { url: '/admin/monitoring/decline',          title: 'Decline Proposal',          btnClass: 'btn-danger'  },
        'approveDetailed': { url: '/admin/monitoring/approve-detailed', title: 'Approve Detailed Proposal', btnClass: 'btn-success' },
        'reviseDetailed':  { url: '/admin/monitoring/revise-detailed',  title: 'Revise Detailed Proposal',  btnClass: 'btn-warning' },
        'declineDetailed': { url: '/admin/monitoring/decline-detailed', title: 'Decline Detailed Proposal', btnClass: 'btn-danger'  },
    };

    function openActionModal(id, action) {
        const data = actionMap[action];
        if (!data) return;
        document.getElementById('actionId').value = id;
        const form = document.getElementById('actionForm');
        form.action = data.url;
        document.getElementById('modalTitle').textContent = data.title;
        const btn = document.getElementById('actionSubmitBtn');
        btn.className = 'btn ' + data.btnClass;
        btn.textContent = data.title;
        document.getElementById('actionModal').style.display = 'flex';
    }

    function closeActionModal() { document.getElementById('actionModal').style.display = 'none'; }

    function openAssignModal(submissionId) {
        const modal = document.getElementById('assignModal');
        const body = document.getElementById('assignModalBody');
        body.innerHTML = '<p style="text-align:center; padding:20px;">Loading evaluators...</p>';
        modal.style.display = 'flex';

        fetch('/admin/monitoring/assign-modal?submission_id=' + submissionId)
            .then(r => r.text())
            .then(html => {
                body.innerHTML = html;
                const form = document.getElementById('assignForm');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const formData = new FormData(form);
                        fetch(form.action, { method: 'POST', body: formData })
                            .then(r => r.text())
                            .then(html => {
                                body.innerHTML = html;
                                if (html.includes('success')) {
                                    setTimeout(() => { closeAssignModal(); location.reload(); }, 1200);
                                }
                            })
                            .catch(() => { body.innerHTML = '<p style="color:red;">Error saving.</p>'; });
                    });
                }
            })
            .catch(() => { body.innerHTML = '<p style="color:red; text-align:center; padding:20px;">Error loading.</p>'; });
    }

    function closeAssignModal() { document.getElementById('assignModal').style.display = 'none'; }

    document.addEventListener('click', function(e) {
        const am = document.getElementById('actionModal');
        if (e.target === am) am.style.display = 'none';
        const asm = document.getElementById('assignModal');
        if (e.target === asm) asm.style.display = 'none';
    });
</script>