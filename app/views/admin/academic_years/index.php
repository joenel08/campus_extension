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
            <h2><i class="fas fa-calendar-alt"></i> Academic Years</h2>
            <p class="table-subtitle">Manage academic years and set the current active year</p>
        </div>
        <button onclick="openAddModal()" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Academic Year
        </button>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Label</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Current</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($years)): ?>
                <tr><td colspan="6" style="text-align:center;color:#777;">No academic years found.</td></tr>
            <?php else: ?>
                <?php foreach ($years as $i => $y): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><strong><?= htmlspecialchars($y['year_label']) ?></strong></td>
                        <td><?= date('M d, Y', strtotime($y['start_date'])) ?></td>
                        <td><?= date('M d, Y', strtotime($y['end_date'])) ?></td>
                        <td>
                            <?php if ($y['is_current']): ?>
                                <span class="badge badge-approved">Current</span>
                            <?php else: ?>
                                <form method="POST" action="/admin/academic-years/set-current" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $y['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-primary">Set Current</button>
                                </form>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-edit" onclick='openEditModal(<?= json_encode($y) ?>)'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <?php if (!$y['is_current']): ?>
                                <form method="POST" action="/admin/academic-years/delete" style="display:inline;" onsubmit="return confirm('Delete this academic year?')">
                                    <input type="hidden" name="id" value="<?= $y['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="addModal" class="modal" style="display:none;">
    <div class="modal-content" style="max-width:500px;">
        <div class="modal-header">
            <h3>Add Academic Year</h3>
            <button class="close-modal" onclick="closeAddModal()">&times;</button>
        </div>
        <form method="POST" action="/admin/academic-years/store">
            <div style="margin-bottom:15px;">
                <label>Label</label>
                <input type="text" name="year_label" placeholder="e.g., Academic Year 2025-2026" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
            </div>
            <div style="margin-bottom:15px;">
                <label>Start Date</label>
                <input type="date" name="start_date" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
            </div>
            <div style="margin-bottom:15px;">
                <label>End Date</label>
                <input type="date" name="end_date" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-secondary" onclick="closeAddModal()">Cancel</button>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal" style="display:none;">
    <div class="modal-content" style="max-width:500px;">
        <div class="modal-header">
            <h3>Edit Academic Year</h3>
            <button class="close-modal" onclick="closeEditModal()">&times;</button>
        </div>
        <form method="POST" action="/admin/academic-years/update">
            <input type="hidden" name="id" id="edit_id">
            <div style="margin-bottom:15px;">
                <label>Label</label>
                <input type="text" name="year_label" id="edit_label" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
            </div>
            <div style="margin-bottom:15px;">
                <label>Start Date</label>
                <input type="date" name="start_date" id="edit_start" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
            </div>
            <div style="margin-bottom:15px;">
                <label>End Date</label>
                <input type="date" name="end_date" id="edit_end" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
    function openAddModal() { document.getElementById('addModal').style.display = 'flex'; }
    function closeAddModal() { document.getElementById('addModal').style.display = 'none'; }

    function openEditModal(y) {
        document.getElementById('edit_id').value = y.id;
        document.getElementById('edit_label').value = y.year_label;
        document.getElementById('edit_start').value = y.start_date;
        document.getElementById('edit_end').value = y.end_date;
        document.getElementById('editModal').style.display = 'flex';
    }
    function closeEditModal() { document.getElementById('editModal').style.display = 'none'; }

    document.addEventListener('click', function(e) {
        ['addModal', 'editModal'].forEach(function(id) {
            const modal = document.getElementById(id);
            if (e.target === modal) modal.style.display = 'none';
        });
    });
</script>