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
            <h2><i class="fas fa-file-alt"></i> Manage Templates & Attachments</h2>
            <p class="table-subtitle">Manage downloadable templates available to extensionists</p>
        </div>
        <button class="btn btn-primary" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Add Template
        </button>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Template Code</th>
                <th>Description</th>
                <th>File</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($templates)): ?>
                <tr><td colspan="7" style="text-align:center;color:#777;">No templates found.</td></tr>
            <?php else: ?>
                <?php foreach ($templates as $i => $t): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><strong><?= htmlspecialchars($t['title']) ?></strong></td>
                        <td><?= htmlspecialchars($t['template_code']) ?></td>
                        <td><?= htmlspecialchars($t['description'] ?? '') ?></td>
                        <td>
                            <?php if (!empty($t['attached_file'])): ?>
                                <a href="/<?= htmlspecialchars($t['attached_file']) ?>" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fas fa-download"></i> <?= htmlspecialchars($t['file_type'] ?? 'File') ?>
                                </a>
                                <div style="font-size:11px; color:#999; margin-top:3px;">
                                    <?= htmlspecialchars($t['file_size'] ?? '') ?>
                                </div>
                            <?php else: ?>
                                <span style="color:#999; font-size:12px;">No file</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($t['status'] === 'active'): ?>
                                <span class="badge badge-approved">Active</span>
                            <?php else: ?>
                                <span class="badge badge-pending">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-edit" onclick='openEditModal(<?= json_encode($t) ?>)'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="/admin/templates/toggle" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                <input type="hidden" name="status" value="<?= $t['status'] === 'active' ? 'inactive' : 'active' ?>">
                                <button type="submit" class="btn btn-sm <?= $t['status'] === 'active' ? 'btn-warning' : 'btn-success' ?>">
                                    <?= $t['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                                </button>
                            </form>
                            <form method="POST" action="/admin/templates/delete" style="display:inline;" onsubmit="return confirm('Delete this template?')">
                                <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- ADD MODAL -->
<div id="addModal" class="modal" style="display:none;">
    <div class="modal-content" style="max-width:600px;">
        <div class="modal-header">
            <h3>Add Template</h3>
            <button class="close-modal" onclick="closeAddModal()">&times;</button>
        </div>

        <form method="POST" action="/admin/templates/store" enctype="multipart/form-data">
            <div style="margin-bottom:15px;">
                <label style="font-weight:600;">Title</label>
                <input type="text" name="title" class="form-input" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;" required>
            </div>

            <div style="margin-bottom:15px;">
                <label style="font-weight:600;">Template Code</label>
                <input type="text" name="template_code" class="form-input" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;" placeholder="e.g., 25-c34a7" required>
            </div>

            <div style="margin-bottom:15px;">
                <label style="font-weight:600;">Description</label>
                <textarea name="description" class="form-input" rows="3" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;"></textarea>
            </div>

            <div style="margin-bottom:15px;">
                <label style="font-weight:600;">Attached File</label>
                <input type="file" name="attached_file" accept=".doc,.docx,.pdf,.xls,.xlsx,.ppt,.pptx" style="width:100%; padding:10px;">
                <p style="font-size:12px; color:#6c757d; margin-top:5px;">Word, PDF, Excel, PowerPoint (max 10MB)</p>
            </div>

            <div style="display:flex; gap:15px;">
                <div style="flex:1;">
                    <label style="font-weight:600;">Display Order</label>
                    <input type="number" name="display_order" class="form-input" value="0" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
                </div>
                <div style="flex:1;">
                    <label style="font-weight:600;">Status</label>
                    <select name="status" class="form-input" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div style="display:flex; gap:10px; margin-top:20px; justify-content:flex-end;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                <button type="button" class="btn btn-secondary" onclick="closeAddModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT MODAL -->
<div id="editModal" class="modal" style="display:none;">
    <div class="modal-content" style="max-width:600px;">
        <div class="modal-header">
            <h3>Edit Template</h3>
            <button class="close-modal" onclick="closeEditModal()">&times;</button>
        </div>

        <form method="POST" action="/admin/templates/update" enctype="multipart/form-data">
            <input type="hidden" name="id" id="edit_id">

            <div style="margin-bottom:15px;">
                <label style="font-weight:600;">Title</label>
                <input type="text" name="title" id="edit_title" class="form-input" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;" required>
            </div>

            <div style="margin-bottom:15px;">
                <label style="font-weight:600;">Template Code</label>
                <input type="text" name="template_code" id="edit_template_code" class="form-input" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;" required>
            </div>

            <div style="margin-bottom:15px;">
                <label style="font-weight:600;">Description</label>
                <textarea name="description" id="edit_description" class="form-input" rows="3" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;"></textarea>
            </div>

            <div style="margin-bottom:15px;">
                <label style="font-weight:600;">Current File</label>
                <div id="edit_current_file"></div>
                <label style="font-weight:600; margin-top:10px;">Replace File (leave empty to keep current)</label>
                <input type="file" name="attached_file" accept=".doc,.docx,.pdf,.xls,.xlsx,.ppt,.pptx" style="width:100%; padding:10px;">
            </div>

            <div style="display:flex; gap:15px;">
                <div style="flex:1;">
                    <label style="font-weight:600;">Display Order</label>
                    <input type="number" name="display_order" id="edit_display_order" class="form-input" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
                </div>
                <div style="flex:1;">
                    <label style="font-weight:600;">Status</label>
                    <select name="status" id="edit_status" class="form-input" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div style="display:flex; gap:10px; margin-top:20px; justify-content:flex-end;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addModal').style.display = 'flex';
    }
    function closeAddModal() {
        document.getElementById('addModal').style.display = 'none';
    }

    function openEditModal(t) {
        document.getElementById('edit_id').value = t.id;
        document.getElementById('edit_title').value = t.title || '';
        document.getElementById('edit_template_code').value = t.template_code || '';
        document.getElementById('edit_description').value = t.description || '';
        document.getElementById('edit_display_order').value = t.display_order || 0;
        document.getElementById('edit_status').value = t.status || 'active';

        const fileBox = document.getElementById('edit_current_file');
        if (t.attached_file) {
            fileBox.innerHTML = `
                <div style="padding:10px; background:#e8f0fe; border-radius:6px;">
                    <i class="fas fa-file"></i>
                    <a href="/${t.attached_file}" target="_blank" style="color:#2563eb; font-weight:600;">
                        ${t.file_type || 'File'} (${t.file_size || ''})
                    </a>
                </div>`;
        } else {
            fileBox.innerHTML = '<p style="color:#999; font-size:13px;">No file uploaded.</p>';
        }

        document.getElementById('editModal').style.display = 'flex';
    }
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    document.addEventListener('click', function(e) {
        ['addModal', 'editModal'].forEach(function(id) {
            const modal = document.getElementById(id);
            if (e.target === modal) modal.style.display = 'none';
        });
    });
</script>