<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<style>

.evaluation-container{
  background:#fff;
  padding:35px;
  border-radius:18px;
  border:1px solid #e5e7eb;
  box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.evaluation-header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:30px;
}

.evaluation-title{
  font-size:28px;
  font-weight:700;
  color:#183153;
}

.generate-btn{
  background:#2563eb;
  color:#fff;
  border:none;
  padding:14px 22px;
  border-radius:12px;
  cursor:pointer;
  font-size:15px;
  font-weight:600;
}

.generate-btn:hover{
  background:#1d4ed8;
}

.eval-form{
  margin-bottom:30px;
}

.eval-row{
  display:flex;
  align-items:center;
  margin-bottom:18px;
  gap:12px;
}

.eval-label{
  width:190px;
  font-weight:700;
  color:#111827;
}

.eval-line{
  flex:1;
  border:none;
  border-bottom:1px solid #444;
  outline:none;
  padding:8px;
  font-size:14px;
}

.criteria-title{
  font-size:22px;
  font-weight:700;
  margin-bottom:18px;
  color:#183153;
}

.eval-table{
  width:100%;
  border-collapse:collapse;
}

.eval-table th,
.eval-table td{
  border:1px solid #555;
  padding:16px;
  text-align:center;
}

.eval-table th{
  background:#f8fafc;
  font-size:15px;
  font-weight:700;
}

.eval-table td:first-child{
  text-align:left;
  font-weight:600;
  width:280px;
}

.eval-table input{
  width:18px;
  height:18px;
  cursor:pointer;
}

.comment-box{
  margin-top:30px;
}

.comment-title{
  font-size:18px;
  font-weight:700;
  margin-bottom:12px;
}

.comment-textarea{
  width:100%;
  height:120px;
  border:none;
  border-bottom:1px solid #555;
  outline:none;
  resize:none;
  font-size:15px;
  padding:10px;
}

</style>
<div class="card">
    <div class="table-header">
        <div>
            <h2><i class="fas fa-check-circle"></i> Evaluation Criteria</h2>
            <p class="table-subtitle">Manage groups and criteria for evaluation forms</p>
        </div>
        <div>
            <a href="/admin/evaluation/create-group" class="btn btn-primary"><i class="fas fa-plus"></i> Add Group</a>
            <a href="/admin/evaluation/create-criteria" class="btn btn-secondary"><i class="fas fa-plus"></i> Add Criteria</a>
        </div>
    </div>

    <?php foreach ($groupedCriteria as $item): ?>
        <h3 style="margin:30px 0 10px; color:#183153; border-bottom:1px solid #e5e7eb; padding-bottom:8px;">
            <?= htmlspecialchars($item['group']['name']) ?>
            <span style="float:right; font-size:14px; font-weight:400;">
                <a href="/admin/evaluation/edit-group?id=<?= $item['group']['id'] ?>" class="btn btn-sm btn-edit"><i class="fas fa-edit"></i></a>
                <form method="POST" action="/admin/evaluation/delete-group" style="display:inline;" onsubmit="return confirm('Delete this group and all its criteria?')">
                    <input type="hidden" name="id" value="<?= $item['group']['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </form>
            </span>
        </h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Criteria</th>
                    <th>Order</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($item['criteria'])): ?>
                    <tr><td colspan="4" style="text-align:center;color:#999;">No criteria in this group.</td></tr>
                <?php else: ?>
                    <?php foreach ($item['criteria'] as $index => $crit): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($crit['criteria_text']) ?></td>
                            <td><?= $crit['display_order'] ?></td>
                            <td>
                                <a href="/admin/evaluation/edit-criteria?id=<?= $crit['id'] ?>" class="btn btn-sm btn-edit"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="/admin/evaluation/delete-criteria" style="display:inline;" onsubmit="return confirm('Delete this criteria?')">
                                    <input type="hidden" name="id" value="<?= $crit['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
</div>