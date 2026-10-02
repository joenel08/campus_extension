<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<style>
    .badge-online {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #86efac;
    }

    .badge-online i {
        color: #22c55e;
        margin-right: 4px;
    }

    .badge-offline {
        background: #f3f4f6;
        color: #6b7280;
        border: 1px solid #e5e7eb;
    }

    .badge-offline i {
        color: #9ca3af;
        margin-right: 4px;
    }
</style>

<div class="card">
    <div class="table-header">
        <div>
            <h2><i class="fas fa-users"></i> Manage User Accounts</h2>
            <p class="table-subtitle">Add, edit, or delete accounts</p>
        </div>
    </div>

    <!-- Form to create a new user -->
    <div style="background:#f8f9fa; padding:20px; border-radius:8px; margin-bottom:30px;">
        <h4><i class="fas fa-user-plus"></i> Add New User</h4>
        <form method="POST" action="/admin/accounts/store" style="display:flex; gap:15px; flex-wrap:wrap; margin-top:10px;">
            <input type="text" name="name" placeholder="Full Name" required class="form-input" style="flex:2;">
            <input type="email" name="email" placeholder="Email" required class="form-input" style="flex:2;">
            <input type="password" name="password" placeholder="Password" required class="form-input" style="flex:1;">

            <select name="role" id="roleSelect" required class="form-input" style="flex:1;" onchange="toggleCollegeField()">
                <option value="staff">Staff</option>
                <!-- <option value="extensionist">Extensionist</option>
                <option value="evaluator">Evaluator</option> -->
                <option value="admin">Admin</option>
            </select>

            <select name="college_id" id="collegeSelect" class="form-input" style="flex:1; display:none;">
                <option value="">Select College</option>
                <?php foreach ($colleges as $college): ?>
                    <option value="<?= $college['id'] ?>"><?= htmlspecialchars($college['abbreviation']) ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add User</button>
        </form>
    </div>

    <!-- User Table -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>College</th>
                <th>Activity</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="7" style="text-align:center;color:#777;">No users found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $index => $user): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td><?= htmlspecialchars($user['college_abbr'] ?? '-') ?></td>
                        <td>
                            <?php if (!empty($user['last_activity']) && (time() - strtotime($user['last_activity'])) <= 120): ?>
                                <span class="badge badge-online" title="Last active: <?= date('M d, Y H:i', strtotime($user['last_activity'])) ?>">
                                    <i class="fas fa-circle" style="font-size:8px;"></i> Online
                                </span>
                            <?php else: ?>
                                <span class="badge badge-offline" title="<?= !empty($user['last_activity']) ? 'Last active: ' . date('M d, Y H:i', strtotime($user['last_activity'])) : 'Never active' ?>">
                                    <i class="fas fa-circle" style="font-size:8px;"></i> Offline
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($user['status'] === 'approved'): ?>
                                <span class="badge badge-approved">Active</span>
                            <?php elseif ($user['status'] === 'deactivated'): ?>
                                <span class="badge badge-declined">Deactivated</span>
                            <?php elseif ($user['status'] === 'pending'): ?>
                                <span class="badge badge-pending">Pending</span>
                            <?php else: ?>
                                <span class="badge"><?= ucfirst($user['status']) ?></span>
                            <?php endif; ?>
                        </td>
                        <!-- ACTIONS -->
                        <td>
                            <?php if ($user['id'] == $_SESSION['user_id']): ?>
                                <span style="color:#999;">—</span>
                            <?php else: ?>

                                <!-- Approve / Decline for pending -->
                                <?php if ($user['status'] === 'pending'): ?>
                                    <form method="POST" action="/admin/accounts/approve" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this account?')">Approve</button>
                                    </form>
                                    <form method="POST" action="/admin/accounts/decline" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Decline this account?')">Decline</button>
                                    </form>

                                    <!-- Activate / Deactivate toggle -->
                                <?php elseif ($user['status'] === 'approved'): ?>
                                    <form method="POST" action="/admin/accounts/toggle" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <input type="hidden" name="status" value="deactivated">
                                        <button type="submit" class="btn btn-sm btn-warning"
                                            onclick="return confirm('Deactivate this account? The user will not be able to log in.')">
                                            <i class="fas fa-ban"></i> Deactivate
                                        </button>
                                    </form>
                                <?php elseif ($user['status'] === 'deactivated'): ?>
                                    <form method="POST" action="/admin/accounts/toggle" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('Activate this account?')">
                                            <i class="fas fa-check"></i> Activate
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <!-- Edit and Delete (always shown for other users) -->
                                <button class="btn btn-sm btn-edit" onclick="openEditModal(<?= htmlspecialchars(json_encode($user)) ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" action="/admin/accounts/delete" style="display:inline;"
                                    onsubmit="return confirm('Delete this account?')">
                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
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

<!-- Edit Modal -->
<div class="modal" id="editModal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit User Account</h3>
            <button class="close-modal" onclick="closeEditModal()">&times;</button>
        </div>
        <form method="POST" action="/admin/accounts/update" id="editForm">
            <input type="hidden" name="id" id="edit_id">
            <div style="margin-bottom:15px;">
                <label>Full Name</label>
                <input type="text" name="name" id="edit_name" required class="form-input">
            </div>
            <div style="margin-bottom:15px;">
                <label>Email</label>
                <input type="email" name="email" id="edit_email" required class="form-input">
            </div>
            <div style="margin-bottom:15px;">
                <label>Role</label>
                <select name="role" id="edit_role" class="form-input" onchange="toggleEditCollege()">
                    <option value="staff">Staff</option>
                    <option value="extensionist">Extensionist</option>
                    <option value="evaluator">Evaluator</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div style="margin-bottom:15px;" id="editCollegeContainer">
                <label>College</label>
                <select name="college_id" id="edit_college_id" class="form-input">
                    <option value="">None</option>
                    <?php foreach ($colleges as $college): ?>
                        <option value="<?= $college['id'] ?>"><?= htmlspecialchars($college['abbreviation']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="margin-bottom:15px;">
                <label>Status</label>
                <select name="status" id="edit_status" class="form-input">
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="declined">Declined</option>
                </select>
            </div>
            <!-- <div style="margin-bottom:15px;">
                <label>New Password (leave blank to keep current)</label>
                <input type="password" name="password" class="form-input" placeholder="Enter new password">
            </div> -->
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
    function toggleCollegeField() {
        var role = document.getElementById('roleSelect').value;
        var college = document.getElementById('collegeSelect');

        // Hide college for admin AND staff
        if (role === 'admin' || role === 'staff') {
            college.style.display = 'none';
            college.value = '';
        } else {
            college.style.display = 'inline-block';
        }
    }

    function toggleEditCollege() {
        var role = document.getElementById('edit_role').value;
        var container = document.getElementById('editCollegeContainer');

        if (role === 'admin' || role === 'staff') {
            container.style.display = 'none';
            document.getElementById('edit_college_id').value = '';
        } else {
            container.style.display = 'block';
        }
    }

    function openEditModal(user) {
        document.getElementById('edit_id').value = user.id;
        document.getElementById('edit_name').value = user.name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_role').value = user.role;
        document.getElementById('edit_college_id').value = user.college_id || '';
        document.getElementById('edit_status').value = user.status || 'approved';
        toggleEditCollege();
        document.getElementById('editModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    // Initialize on load
    toggleCollegeField();
</script>