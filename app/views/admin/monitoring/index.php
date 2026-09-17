<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="card">
    <div class="table-header">
        <div>
            <h2><i class="fas fa-folder-open"></i> Submission Monitoring</h2>
            <p class="table-subtitle">View proposals, progress, and terminal reports grouped by proposal</p>
        </div>
        <button class="btn btn-secondary" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
    </div>

    <!-- Filters (unchanged) -->
    <form method="GET" action="/admin/monitoring" style="display:flex; gap:15px; flex-wrap:wrap; margin-bottom:20px; padding:15px; background:#f8fafc; border-radius:8px;">
        <div>
            <label>Status</label>
            <select name="status" class="form-input">
                <option value="">All</option>
                <option value="draft" <?= ($filters['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="submitted" <?= ($filters['status'] ?? '') === 'submitted' ? 'selected' : '' ?>>Submitted</option>
                <option value="approved" <?= ($filters['status'] ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
                <option value="revision" <?= ($filters['status'] ?? '') === 'revision' ? 'selected' : '' ?>>Revision</option>
                <option value="rejected" <?= ($filters['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Declined</option>
            </select>
        </div>
        <div>
            <label>College</label>
            <select name="college_id" class="form-input">
                <option value="">All</option>
                <?php foreach ($colleges as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= ($filters['college_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['abbreviation']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Date From</label>
            <input type="date" name="date_from" class="form-input" value="<?= $filters['date_from'] ?? '' ?>">
        </div>
        <div>
            <label>Date To</label>
            <input type="date" name="date_to" class="form-input" value="<?= $filters['date_to'] ?? '' ?>">
        </div>
        <div style="display:flex; align-items:flex-end; gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
            <a href="/admin/monitoring" class="btn btn-secondary">Clear</a>
        </div>
    </form>

    <!-- TABLE grouped by proposal -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Extensionist</th>
                <th>Proposal Title</th>
                <th>College</th>
                <th>Evaluators</th>
                <th>Proposal Status</th>
                <th>Progress Reports</th>
                <th>Terminal Report</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($grouped)): ?>
                <tr>
                    <td colspan="9" style="text-align:center;color:#777;">No submissions found.</td>
                </tr>
            <?php else: ?>
                <?php $counter = 1; ?>
                <?php foreach ($grouped as $pid => $data): ?>
                    <?php $proposal = $data['proposal_submission']; ?>
                    <?php if (!$proposal) continue; ?>
                    <tr>
                        <td><?= $counter++ ?></td>
                        <td><?= htmlspecialchars($data['extensionist_name']) ?></td>
                        <td><strong><?= htmlspecialchars($data['proposal_title']) ?></strong></td>
                        <td><?= htmlspecialchars($data['college_abbr']) ?></td>
                        <td><?= htmlspecialchars($data['evaluators'] ?: 'None assigned') ?></td>
                        <td>
                            <?php
                            $statusClass = [
                                'draft' => 'badge-pending',
                                'submitted' => 'badge-approved',
                                'approved' => 'badge-approved',
                                'rejected' => 'badge-declined',
                                'revision' => 'badge-pending',
                                'pending_evaluation' => 'badge-pending',
                                'under_evaluation' => 'badge-approved',
                            ];
                            ?>
                            <span class="badge <?= $statusClass[$proposal['status']] ?? 'badge-pending' ?>">
                                <?= ucfirst($proposal['status']) ?>
                            </span>
                            <a href="/admin/submissions/show?id=<?= $proposal['id'] ?>" class="btn btn-sm btn-edit"><i class="fas fa-eye"></i></a>
                        
                           <!-- Existing action buttons -->
                            <?php if (in_array($proposal['status'], ['pending_evaluation', 'under_evaluation', 'approved'])): ?>
    <button class="btn btn-sm btn-primary" onclick="openAssignModal(<?= $proposal['id'] ?>)">
        <i class="fas fa-user-plus"></i> Assign
    </button>
<?php endif; ?>
                            <?php if ($proposal['status'] === 'submitted'): ?>
                                <button class="btn btn-sm btn-success" onclick="openActionModal(<?= $proposal['id'] ?>, 'approve')"><i class="fas fa-check"></i></button>
                                <button class="btn btn-sm btn-warning" onclick="openActionModal(<?= $proposal['id'] ?>, 'revise')"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" onclick="openActionModal(<?= $proposal['id'] ?>, 'decline')"><i class="fas fa-times"></i></button>
                          
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($data['progress_reports'])): ?>
                                <?php foreach ($data['progress_reports'] as $idx => $pr): ?>
                                    <div style="display:flex; align-items:center; gap:4px; margin:2px 0;">
                                        <span style="font-size:12px; min-width:50px;">R<?= $idx + 1 ?></span>
                                        <span class="badge <?= $statusClass[$pr['status']] ?? 'badge-pending' ?>">
                                            <?= ucfirst($pr['status']) ?>
                                        </span>
                                        <a href="/admin/submissions/show?id=<?= $pr['id'] ?>" class="btn btn-sm btn-edit"><i class="fas fa-eye"></i></a>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span style="color:#999; font-size:12px;">None</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($data['terminal_report']): ?>
                                <span class="badge <?= $statusClass[$data['terminal_report']['status']] ?? 'badge-pending' ?>">
                                    <?= ucfirst($data['terminal_report']['status']) ?>
                                </span>
                                <a href="/admin/submissions/show?id=<?= $data['terminal_report']['id'] ?>" class="btn btn-sm btn-edit"><i class="fas fa-eye"></i></a>
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

<!-- Modals (Action and Assign) – reuse the existing ones -->
<!-- Action Modal (Approve/Revise/Decline) -->
<div id="actionModal" class="modal" style="display:none;">
    <div class="modal-content" style="max-width:500px;">
        <div class="modal-header">
            <h3 id="modalTitle">Action</h3>
            <button class="close-modal" onclick="closeActionModal()">&times;</button>
        </div>
        <form method="POST" id="actionForm">
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
            <!-- Content loaded via AJAX -->
            <p style="text-align:center; padding:20px;">Loading...</p>
        </div>
    </div>
</div>

<script>
    // === Action Modal (Approve/Revise/Decline) ===
    function openActionModal(id, action) {
        document.getElementById('actionId').value = id;
        const form = document.getElementById('actionForm');
        let actionMap = {
            'approve': {
                url: '/admin/monitoring/approve',
                title: 'Approve Submission',
                btnClass: 'btn-success'
            },
            'revise': {
                url: '/admin/monitoring/revise',
                title: 'Request Revision',
                btnClass: 'btn-warning'
            },
            'decline': {
                url: '/admin/monitoring/decline',
                title: 'Decline Submission',
                btnClass: 'btn-danger'
            }
        };
        const data = actionMap[action];
        form.action = data.url;
        document.getElementById('modalTitle').textContent = data.title;
        document.getElementById('actionSubmitBtn').className = 'btn ' + data.btnClass;
        document.getElementById('actionSubmitBtn').textContent = data.title;
        document.getElementById('actionModal').style.display = 'flex';
    }

    function closeActionModal() {
        document.getElementById('actionModal').style.display = 'none';
    }

    // === Assign Evaluators Modal ===
   function openAssignModal(submissionId) {
    const modal = document.getElementById('assignModal');
    const body = document.getElementById('assignModalBody');
    body.innerHTML = '<p style="text-align:center; padding:20px;">Loading evaluators...</p>';
    modal.style.display = 'flex';

    fetch('/admin/monitoring/assign-modal?submission_id=' + submissionId)
        .then(response => response.text())
        .then(html => {
            body.innerHTML = html;

            // Attach submit handler to the newly inserted form
            const form = document.getElementById('assignForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(form);
                    fetch(form.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(html => {
                        body.innerHTML = html;
                        if (html.includes('success')) {
                            setTimeout(() => {
                                closeAssignModal();
                                location.reload();
                            }, 1200);
                        }
                    })
                    .catch(err => {
                        body.innerHTML = '<p style="color:red;">Error saving.</p>';
                    });
                });
            }
        })
        .catch(err => {
            body.innerHTML = '<p style="color:red; text-align:center; padding:20px;">Error loading. Please try again.</p>';
        });
}

    function closeAssignModal() {
        document.getElementById('assignModal').style.display = 'none';
    }

    // Close modals when clicking outside
    document.addEventListener('click', function(e) {
        const actionModal = document.getElementById('actionModal');
        if (e.target === actionModal) {
            actionModal.style.display = 'none';
        }
        const assignModal = document.getElementById('assignModal');
        if (e.target === assignModal) {
            assignModal.style.display = 'none';
        }
    });
</script>