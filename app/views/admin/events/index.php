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
            <h2><i class="fas fa-calendar"></i> Manage Upcoming Events</h2>
            <p class="table-subtitle">Create, edit, publish or delete events</p>
        </div>
        <a href="/admin/events/create" class="btn btn-primary"><i class="fas fa-plus"></i> Add Event</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Event Date</th>
                <th>Image</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($events)): ?>
                <tr><td colspan="6" style="text-align:center;color:#777;">No events found.</td></tr>
            <?php else: ?>
                <?php foreach ($events as $index => $item): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($item['title']) ?></td>
                    <td><?= date('M d, Y', strtotime($item['event_date'])) ?></td>
                    <td>
                        <?php if ($item['image']): ?>
                            <img src="/<?= $item['image'] ?>" width="80" style="border-radius:4px;">
                        <?php else: ?>
                            <span style="color:#999;">No image</span>
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
                        <a href="/admin/events/edit?id=<?= $item['id'] ?>" class="btn btn-sm btn-edit"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="/admin/events/toggle" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <input type="hidden" name="status" value="<?= $item['status'] === 'published' ? 'draft' : 'published' ?>">
                            <button type="submit" class="btn btn-sm <?= $item['status'] === 'published' ? 'btn-warning' : 'btn-success' ?>">
                                <?= $item['status'] === 'published' ? 'Unpublish' : 'Publish' ?>
                            </button>
                        </form>
                        <form method="POST" action="/admin/events/delete" style="display:inline;" onsubmit="return confirm('Delete this event?')">
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