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
            <h2><i class="fas fa-file-signature"></i> Call for Proposals</h2>
            <p class="table-subtitle">Manage proposal announcements and submission deadlines</p>
        </div>
        <a href="/admin/proposal/create" class="btn btn-primary"><i class="fas fa-plus"></i> Add Proposal</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>College</th>
                <th>Category</th>
                <th>Status</th>
                <th>Opening Date</th>
                <th>Closing Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($proposals)): ?>
                <tr>
                    <td colspan="7" style="text-align:center;color:#777;">No proposals found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($proposals as $index => $item): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($item['title']) ?></td>
                        <td><?= htmlspecialchars($item['college_abbr'] ?? 'N/A') ?></td>
                        <td><?= str_replace('_', ' ', ucfirst($item['category'])) ?></td>
                        <td>
                            <?php if ($item['status'] === 'open'): ?>
                                <span class="badge badge-approved">Open</span>
                            <?php else: ?>
                                <span class="badge badge-declined">Closed</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('M d, Y', strtotime($item['opening_date'])) ?></td>
                        <td><?= date('M d, Y', strtotime($item['closing_date'])) ?></td>
                        <td>
                            <a href="/admin/proposal/edit?id=<?= $item['id'] ?>" class="btn btn-sm btn-edit"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="/admin/proposal/toggle" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                <input type="hidden" name="status" value="<?= $item['status'] === 'open' ? 'closed' : 'open' ?>">
                                <button type="submit" class="btn btn-sm <?= $item['status'] === 'open' ? 'btn-warning' : 'btn-success' ?>">
                                    <?= $item['status'] === 'open' ? 'Close' : 'Open' ?>
                                </button>
                            </form>
                            <form method="POST" action="/admin/proposal/delete" style="display:inline;" onsubmit="return confirm('Delete this proposal?')">
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