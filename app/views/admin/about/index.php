<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="post-card">
    <div class="post-title">
        <i class="fas fa-circle-info"></i> Manage About Section
    </div>
    <form method="POST" action="/admin/about/update">
        <div class="post-grid">
            <!-- Description -->
            <div class="post-group full">
                <label class="post-label">Extension and Training Services Description</label>
                <textarea name="about_description" class="post-textarea" rows="5" required><?= htmlspecialchars($about_description ?? '') ?></textarea>
            </div>

            <!-- Vision -->
            <div class="post-group full">
                <label class="post-label">Vision</label>
                <textarea name="vision" class="post-textarea" rows="3" required><?= htmlspecialchars($vision ?? '') ?></textarea>
            </div>

            <!-- Mission -->
            <div class="post-group full">
                <label class="post-label">Mission</label>
                <textarea name="mission" class="post-textarea" rows="4" required><?= htmlspecialchars($mission ?? '') ?></textarea>
            </div>

            <!-- Objectives -->
            <div class="post-group full">
                <label class="post-label">Objectives (one per line)</label>
                <textarea name="objectives" class="post-textarea" rows="6"><?= htmlspecialchars(implode("\n", $objectives ?? [])) ?></textarea>
                <p style="font-size:13px; color:#6c757d; margin-top:5px;">Enter each objective on a new line.</p>
            </div>

            <!-- Services -->
            <div class="post-group full">
                <label class="post-label">Services Offered (one per line)</label>
                <textarea name="services" class="post-textarea" rows="6"><?= htmlspecialchars(implode("\n", $services ?? [])) ?></textarea>
                <p style="font-size:13px; color:#6c757d; margin-top:5px;">Enter each service on a new line.</p>
            </div>

            <!-- Contact Information -->
            <div class="post-group">
                <label class="post-label">Contact Email</label>
                <input type="email" name="contact_email" class="post-input" value="<?= htmlspecialchars($contact_email ?? '') ?>" required>
            </div>
            <div class="post-group">
                <label class="post-label">Contact Phone</label>
                <input type="text" name="contact_phone" class="post-input" value="<?= htmlspecialchars($contact_phone ?? '') ?>" required>
            </div>
            <div class="post-group full">
                <label class="post-label">Contact Address</label>
                <input type="text" name="contact_address" class="post-input" value="<?= htmlspecialchars($contact_address ?? '') ?>" required>
            </div>
        </div>

        <div class="post-btns">
            <button type="submit" class="update-btn"><i class="fas fa-save"></i> Update About</button>
            <a href="/admin/dashboard" class="delete-post-btn" style="text-decoration:none; text-align:center; line-height:40px;">Cancel</a>
        </div>
    </form>
</div>