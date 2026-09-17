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
            <h2><i class="fas fa-file-upload"></i> My Submissions</h2>
            <p class="table-subtitle">All your submitted proposals, progress reports, and terminal reports</p>
        </div>
        <button onclick="openProposalModal()" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Submission
        </button>
    </div>

    <!-- PROPOSAL SELECTION MODAL -->
    <div id="proposalModal" class="modal" style="display:none;">
        <div class="modal-content" style="max-width:600px;">
            <div class="modal-header">
                <h3>Select Proposal</h3>
                <button class="close-modal" onclick="closeProposalModal()">&times;</button>
            </div>
            <p style="color:#6b7280; margin-bottom:20px;">Choose the proposal you want to submit for:</p>
            <?php if (empty($proposals)): ?>
                <p style="color:#999; text-align:center; padding:20px;">No open proposals available for submission.</p>
            <?php else: ?>
                <?php foreach ($proposals as $p): ?>
                    <div class="proposal-option" onclick="selectProposal(<?= $p['id'] ?>)" style="padding:15px; border:1px solid #e5e7eb; border-radius:8px; margin-bottom:10px; cursor:pointer; transition:0.3s;">
                        <h4 style="color:#183153;"><?= htmlspecialchars($p['title']) ?></h4>
                        <p style="color:#6b7280; font-size:14px;">
                            Deadline: <?= date('M d, Y', strtotime($p['closing_date'])) ?> |
                            Category: <?= str_replace('_', ' ', ucfirst($p['category'])) ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <div style="margin-top:15px;">
                <button onclick="closeProposalModal()" style="padding:10px 20px; background:#6c757d; color:#fff; border:none; border-radius:6px; cursor:pointer;">Cancel</button>
            </div>
        </div>
    </div>

    <!-- SUBMISSIONS TABLE -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Proposal Title</th>
                <th>Status</th>
                <th>Submitted</th>
                <th>Progress Reports</th>
                <th>Terminal Report</th>

            </tr>
        </thead>
        <tbody>
            <?php if (empty($grouped)): ?>
                <tr>
                    <td colspan="7" style="text-align:center;color:#777;">No submissions yet.</td>
                </tr>
            <?php else: ?>
                <?php $counter = 1; ?>
                <?php foreach ($grouped as $pid => $data): ?>
                    <?php $proposal = $data['proposal_submission']; ?>
                    <?php if (!$proposal) continue; // should not happen 
                    ?>
                    <tr>
                        <td><?= $counter++ ?></td>
                        <td><?= htmlspecialchars($data['proposal_title']) ?></td>
                        <td>
                            <?php
                            $statusClass = [
                                'draft' => 'badge-pending',
                                'submitted' => 'badge-approved',
                                'approved' => 'badge-approved',
                                'rejected' => 'badge-declined',
                                'revision' => 'badge-pending'
                            ];
                            ?>
                            <span class="badge <?= $statusClass[$proposal['status']] ?? 'badge-pending' ?>">
                                <?= ucfirst($proposal['status']) ?>
                            </span>


                            <a href="/extensionist/submissions/show?id=<?= $proposal['id'] ?>" class="btn btn-sm btn-edit"><i class="fas fa-eye"></i></a>
                            <?php if ($proposal['status'] === 'draft' || $proposal['status'] === 'revision'): ?>
                                <a href="/extensionist/submissions/edit?id=<?= $proposal['id'] ?>" class="btn btn-sm btn-edit"><i class="fas fa-edit"></i></a>
                                <?php if ($proposal['status'] === 'draft'): ?>
                                    <form method="POST" action="/extensionist/submissions/delete" style="display:inline;" onsubmit="return confirm('Delete this submission?')">
                                        <input type="hidden" name="id" value="<?= $proposal['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td><?= date('M d, Y', strtotime($proposal['created_at'])) ?></td>
                        <td style="align-items: center;">

                            <?php if (!empty($data['progress_reports'])): ?>
                                <?php foreach ($data['progress_reports'] as $idx => $pr): ?>
                                    <div style="display:flex; align-items:center; gap:5px; margin:2px 0;">
                                        <span style="font-size:12px; min-width:50px;">Progress Report<?= $idx + 1 ?></span>

                                        <span class="badge <?= $pr['status'] === 'approved' ? 'badge-approved' : ($pr['status'] === 'submitted' ? 'badge-approved' : 'badge-pending') ?>">
                                            <?= ucfirst($pr['status']) ?>
                                        </span>

                                        <button class="btn btn-xs btn-edit" onclick="viewReport(<?= $pr['id'] ?>, 'progress')">
                                            <i class="fas fa-eye" style="font-size:11px;"></i>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span style="color:#999; font-size:12px;">None</span>
                            <?php endif; ?>

                            <?php if ($proposal['status'] === 'approved'): ?>
                                <a href="/extensionist/submissions/create?submission_id=<?= $proposal['id'] ?>&type=progress" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Submit Progress
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($proposal['status'] === 'approved'): ?>
                                <?php if ($data['terminal_report']): ?>

                                    <span class="badge <?= $data['terminal_report']['status'] === 'approved' ? 'badge-approved' : ($data['terminal_report']['status'] === 'submitted' ? 'badge-approved' : 'badge-pending') ?>">
                                        <?= ucfirst($data['terminal_report']['status']) ?>
                                    </span>
                                   <button class="btn btn-xs btn-edit" onclick="viewReport(<?= $data['terminal_report']['id'] ?>, 'terminal')">
    <i class="fas fa-eye"></i>
</button>
                                <?php elseif ($data['all_progress_approved'] && !empty($data['progress_reports'])): ?>
                                    <a href="/extensionist/submissions/create?submission_id=<?= $proposal['id'] ?>&type=terminal" class="btn btn-sm btn-success">
                                        <i class="fas fa-plus"></i> Submit Terminal
                                    </a>
                                <?php else: ?>
                                    <span style="color:#999; font-size:12px;">
                                        <?= empty($data['progress_reports']) ? 'Submit progress first' : 'All progress must be approved' ?>
                                    </span>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php if ($data['terminal_report']): ?>

                                    <span class="badge <?= $data['terminal_report']['status'] === 'approved' ? 'badge-approved' : ($data['terminal_report']['status'] === 'submitted' ? 'badge-approved' : 'badge-pending') ?>">
                                        <?= ucfirst($data['terminal_report']['status']) ?>
                                    </span>
                                    <button class="btn btn-xs btn-edit" onclick="viewReport(<?= $data['terminal_report']['id'] ?>, 'terminal')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                <?php else: ?>
                                    <span style="color:#999; font-size:12px;">—</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal for Viewing Report -->
<div id="reportModal" class="modal" style="display:none;">
    <div class="modal-content" style="max-width:700px; max-height:80vh; overflow-y:auto;">
        <div class="modal-header">
            <h3 id="reportModalTitle">Report Details</h3>
            <button class="close-modal" onclick="closeReportModal()">&times;</button>
        </div>
        <div id="reportModalBody">
            <!-- Content loaded via AJAX -->
        </div>
    </div>
</div>

<script>
    function openProposalModal() {
        document.getElementById('proposalModal').style.display = 'flex';
    }

    function closeProposalModal() {
        document.getElementById('proposalModal').style.display = 'none';
    }

    function selectProposal(id) {
        window.location.href = '/extensionist/submissions/create?proposal_id=' + id;
    }
function viewReport(id, type) {
    const modal = document.getElementById('reportModal');
    const body = document.getElementById('reportModalBody');
    body.innerHTML = '<p style="text-align:center; padding:20px;">Loading...</p>';
    modal.style.display = 'flex';

    fetch('/extensionist/report-data?id=' + id + '&type=' + type)
        .then(response => response.text())
        .then(html => {
            body.innerHTML = html;
        })
        .catch(err => {
            body.innerHTML = '<p style="color:red;">Error loading report.</p>';
        });
}
    function closeReportModal() {
        document.getElementById('reportModal').style.display = 'none';
    }

    // Close modals on outside click
    document.addEventListener('click', function(e) {
        const proposalModal = document.getElementById('proposalModal');
        if (e.target === proposalModal) {
            proposalModal.style.display = 'none';
        }
        const reportModal = document.getElementById('reportModal');
        if (e.target === reportModal) {
            reportModal.style.display = 'none';
        }
    });
</script>