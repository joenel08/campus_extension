<?php
// Display session messages
if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="card">
    <div class="table-header">
        <h2><i class="fas fa-university"></i> Manage Colleges</h2>
        <p class="table-subtitle">Add, edit, or remove college records</p>
    </div>

    <!-- Add College Form -->
    <form method="POST" action="/admin/colleges/store" style="margin-bottom:30px;">
        <div style="display:flex; gap:15px; flex-wrap:wrap;">
            <div style="flex:1; min-width:150px;">
                <input type="text" name="abbreviation" placeholder="Abbreviation (e.g. CEAT)" required class="form-input">
            </div>
            <div style="flex:2; min-width:200px;">
                <input type="text" name="description" placeholder="Full Description (e.g. College of Engineering)" required class="form-input">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add College</button>
        </div>
    </form>

    <!-- College List -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Abbreviation</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($colleges)): ?>
                <tr><td colspan="4" style="text-align:center;color:#777;">No colleges found.</td></tr>
            <?php else: ?>
                <?php foreach ($colleges as $index => $college): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><strong><?= htmlspecialchars($college['abbreviation']) ?></strong></td>
                    <td><?= htmlspecialchars($college['description']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-edit" onclick="openEditModal(<?= htmlspecialchars(json_encode($college)) ?>)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <form method="POST" action="/admin/colleges/delete" style="display:inline;" onsubmit="return confirm('Delete this college?')">
                            <input type="hidden" name="id" value="<?= $college['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Edit Modal (hidden by default) -->
<div class="modal" id="editModal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit College</h3>
            <button class="close-modal" onclick="closeEditModal()">&times;</button>
        </div>
        <form method="POST" action="/admin/colleges/update" id="editForm">
            <input type="hidden" name="id" id="edit_id">
            <div style="margin-bottom:15px;">
                <label>Abbreviation</label>
                <input type="text" name="abbreviation" id="edit_abbreviation" required class="form-input">
            </div>
            <div style="margin-bottom:15px;">
                <label>Description</label>
                <input type="text" name="description" id="edit_description" required class="form-input">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
    function openEditModal(college) {
        document.getElementById('edit_id').value = college.id;
        document.getElementById('edit_abbreviation').value = college.abbreviation;
        document.getElementById('edit_description').value = college.description;
        document.getElementById('editModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }
</script>