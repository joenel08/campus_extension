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
            <h2><i class="fas fa-book-open"></i> CREST Repository</h2>
            <p class="table-subtitle">Manage digital publications, research journals, extension reports</p>
        </div>
        <a href="/admin/crest/create" class="btn btn-primary"><i class="fas fa-plus"></i> Add Publication</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Year</th>
                <th>Category</th>
                <th>Cover</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($publications)): ?>
                <tr><td colspan="7" style="text-align:center;color:#777;">No publications found.</td></tr>
            <?php else: ?>
                <?php foreach ($publications as $index => $item): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($item['title']) ?></td>
                    <td><?= $item['year'] ?></td>
                    <td><?= str_replace('_', ' ', ucfirst($item['category'])) ?></td>
                    <td>
                        <?php if ($item['cover_image']): ?>
                            <img src="/<?= $item['cover_image'] ?>" width="80" style="border-radius:4px;">
                        <?php else: ?>
                            <span style="color:#999;">No cover</span>
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
                        <a href="/admin/crest/edit?id=<?= $item['id'] ?>" class="btn btn-sm btn-edit"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="/admin/crest/toggle" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <input type="hidden" name="status" value="<?= $item['status'] === 'published' ? 'draft' : 'published' ?>">
                            <button type="submit" class="btn btn-sm <?= $item['status'] === 'published' ? 'btn-warning' : 'btn-success' ?>">
                                <?= $item['status'] === 'published' ? 'Unpublish' : 'Publish' ?>
                            </button>
                        </form>
                        <form method="POST" action="/admin/crest/delete" style="display:inline;" onsubmit="return confirm('Delete this publication?')">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>