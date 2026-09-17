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
            <h2><i class="fas fa-users"></i> Administrative Officials</h2>
            <p class="table-subtitle">Manage officials for the organizational structure</p>
        </div>
        <a href="/admin/officials/create" class="btn btn-primary"><i class="fas fa-plus"></i> Add Official</a>
    </div>

    <?php
    $categoryLabels = [
        'administrative' => 'ADMINISTRATIVE OFFICIALS',
        'research_extension' => 'RESEARCH & DEVELOPMENT, EXTENSION & TRAINING',
        'deans' => 'DEANS'
    ];
    ?>

    <?php foreach ($groups as $category => $officials): ?>
        <h3 style="margin:30px 0 15px; color:#183153; border-bottom:2px solid #e5e7eb; padding-bottom:10px;">
            <?= $categoryLabels[$category] ?? ucfirst($category) ?>
        </h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Email</th>
                    <th>Photo</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($officials as $index => $item): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= htmlspecialchars($item['position']) ?></td>
                    <td><?= htmlspecialchars($item['email']) ?></td>
                    <td>
                        <?php if ($item['image']): ?>
                            <img src="/<?= $item['image'] ?>" width="60" height="60" style="border-radius:50%; object-fit:cover;">
                        <?php else: ?>
                            <span style="color:#999;">No photo</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($item['status'] === 'published'): ?>
                            <span class="badge badge-approved">Published</span>
                        <?php else: ?>
                            <span class="badge badge-pending">Draft</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="/admin/officials/edit?id=<?= $item['id'] ?>" class="btn btn-sm btn-edit"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="/admin/officials/toggle" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <input type="hidden" name="status" value="<?= $item['status'] === 'published' ? 'draft' : 'published' ?>">
                            <button type="submit" class="btn btn-sm <?= $item['status'] === 'published' ? 'btn-warning' : 'btn-success' ?>">
                                <?= $item['status'] === 'published' ? 'Unpublish' : 'Publish' ?>
                            </button>
                        </form>
                        <form method="POST" action="/admin/officials/delete" style="display:inline;" onsubmit="return confirm('Delete this official?')">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
</div>