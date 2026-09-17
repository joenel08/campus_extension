<?php include __DIR__ . '/partials/header.php'; ?>

<style>
    /* ============ CREST SECTION ============ */
    .crest-card {
        cursor: pointer;
    }

    .crest-card img {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .crest-card:hover img {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.18);
    }

    /* ============ BOOK MODAL (base + active) ============ */
    .book-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
        animation: bookFadeIn 0.25s ease;
    }

    .book-modal.active {
        display: flex;
    }

    @keyframes bookFadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    .book-slider-container {
        background: #fff;
        border-radius: 16px;
        max-width: 1000px;
        width: 100%;
        max-height: 90vh;
        overflow: hidden;
        position: relative;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
        animation: bookPop 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes bookPop {
        0%   { transform: scale(0.9); opacity: 0; }
        100% { transform: scale(1);   opacity: 1; }
    }

    .close-book {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: none;
        background: #f3f4f6;
        color: #374151;
        font-size: 18px;
        font-weight: 700;
        cursor: pointer;
        z-index: 10;
        transition: background 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .close-book:hover {
        background: #e5e7eb;
    }

    .book-slide {
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: 520px;
    }

    .book-page {
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .book-page.left-page {
        background: #f8fafc;
        border-right: 1px solid #e5e7eb;
    }

    .book-page.left-page img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .book-page.right-page {
        padding: 40px;
        display: block;
        overflow-y: auto;
    }

    .book-content h2 {
        font-size: 24px;
        font-weight: 800;
        color: #111827;
        margin: 0 0 8px;
        line-height: 1.3;
    }

    .book-meta {
        display: inline-block;
        margin: 6px 0 18px;
        padding: 4px 12px;
        background: #e6f7ef;
        color: #0b7a33;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }

    .book-content p {
        line-height: 1.7;
        color: #444;
        white-space: pre-line;
    }

    .btn-read-pdf {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: linear-gradient(135deg, #0b7a33 0%, #086127 100%);
        color: #fff;
        text-decoration: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        box-shadow: 0 4px 14px rgba(11, 122, 51, 0.3);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-read-pdf:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(11, 122, 51, 0.4);
        color: #fff;
    }

    .btn-read-pdf i {
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .book-slide {
            grid-template-columns: 1fr;
        }
        .book-page.left-page {
            max-height: 260px;
            border-right: none;
            border-bottom: 1px solid #e5e7eb;
        }
        .book-page.right-page {
            padding: 24px;
        }
        .book-content h2 {
            font-size: 20px;
        }
    }
</style>

<!-- HERO -->
<section class="hero">
    <?php if ($banner_type === 'video'): ?>
        <video autoplay muted loop playsinline>
            <source src="<?= htmlspecialchars($banner_url) ?>" type="video/mp4">
        </video>
    <?php else: ?>
        <img src="<?= htmlspecialchars($banner_url) ?>" alt="Banner" style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover; z-index:-2;">
    <?php endif; ?>
    <div class="hero-content">
        <h1>Extension & Training Services</h1>
        <p>Bridging academic excellence with community development.</p>
    </div>
</section>

<!-- NEWS SECTION -->
<section class="latest-news" id="news">
    <div class="news-title">
        <h2>Latest News</h2>
        <p>Extension and Training Services Updates</p>
    </div>
    <div class="news-container">
        <?php if (empty($news)): ?>
            <p style="grid-column:1/-1; text-align:center; color:#777;">No news yet.</p>
        <?php else: ?>
            <?php foreach ($news as $item): ?>
                <div class="news-card">
                    <img src="<?= htmlspecialchars($item['image'] ? '/' . $item['image'] : 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="400" height="240"><rect fill="%23e5e7eb" width="400" height="240"/><text x="50%" y="50%" font-family="sans-serif" font-size="16" fill="%239ca3af" text-anchor="middle" dy=".3em">No Image</text></svg>') ?>" alt="News Image">
                    <div class="news-content">
                        <h3><?= htmlspecialchars($item['title']) ?></h3>
                        <span class="category"><?= date('M d, Y', strtotime($item['created_at'])) ?></span>
                        <p><?= htmlspecialchars(substr($item['content'], 0, 120)) ?>...</p>
                        <a href="/news/show?id=<?= $item['id'] ?>" style="color:#0b7a33; font-weight:600; text-decoration:none; display:inline-block; margin-top:10px;">Read More →</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div style="text-align:center; margin-top:30px;">
        <a href="/news" style="padding:12px 30px; background:#0b7a33; color:#fff; border-radius:8px; text-decoration:none; display:inline-block;">Read All News</a>
    </div>
</section>

<!-- UPCOMING EVENTS SECTION -->
<section class="upcoming-events" id="events">
    <div class="events-header">
        <h2>UPCOMING EVENTS</h2>
    </div>
    <div class="events-container">
        <?php if (empty($events)): ?>
            <p style="grid-column:1/-1; text-align:center; color:#777;">No upcoming events.</p>
        <?php else: ?>
            <?php foreach ($events as $item): ?>
                <div class="event-card">
                    <img src="<?= htmlspecialchars($item['image'] ? '/' . $item['image'] : 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="400" height="260"><rect fill="%23e5e7eb" width="400" height="260"/><text x="50%" y="50%" font-family="sans-serif" font-size="16" fill="%239ca3af" text-anchor="middle" dy=".3em">No Image</text></svg>') ?>" alt="Event Image">
                    <div class="event-info">
                        <h3><?= date('M d', strtotime($item['event_date'])) ?></h3>
                        <p><?= htmlspecialchars($item['title']) ?></p>
                        <a href="/events/show?id=<?= $item['id'] ?>" style="color:#0b7a33; font-weight:600; text-decoration:none; display:inline-block; margin-top:10px;">Learn More →</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div style="text-align:center; margin-top:30px;">
        <a href="/events" style="padding:12px 30px; background:#0b7a33; color:#fff; border-radius:8px; text-decoration:none; display:inline-block;">View All Events</a>
    </div>
</section>

<!-- ABOUT -->
<section class="about-extension" id="about">
    <div class="container">
        <h2>About Extension and Training Services</h2>
        <p><?= nl2br(htmlspecialchars($about['about_description'] ?? '')) ?></p>

        <h3>Objectives</h3>
        <ul>
            <?php foreach ($about['objectives'] as $obj): ?>
                <li><?= htmlspecialchars($obj) ?></li>
            <?php endforeach; ?>
        </ul>

        <h3>Services Offered</h3>
        <ul>
            <?php foreach ($about['services'] as $svc): ?>
                <li><?= htmlspecialchars($svc) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>

<!-- ================= VISION & MISSION ================= -->
<section id="vision" class="vision-section">
    <div class="vision-container">
        <div class="vision-left">
            <div class="vision-box">
                <h1>VISION</h1>
                <p><?= nl2br(htmlspecialchars($about['vision'] ?? '')) ?></p>
            </div>
            <div class="vision-box">
                <h1>MISSION</h1>
                <p><?= nl2br(htmlspecialchars($about['mission'] ?? '')) ?></p>
            </div>
        </div>
        <div class="vision-right">
            <img src="/images/isu_logo.png" alt="ISU Logo">
        </div>
    </div>
</section>

<!-- ================= ORGANIZATIONAL STRUCTURE ================= -->
<section id="org" class="org-section">
    <div class="org-title">
        <h1>Organizational Structure</h1>
        <p>Extension and Training Services Officials</p>
    </div>
    <div class="org-table">
        <?php
        $categoryLabels = [
            'administrative'     => 'ADMINISTRATIVE OFFICIALS',
            'research_extension' => 'RESEARCH & DEVELOPMENT, EXTENSION & TRAINING',
            'deans'              => 'COLLEGE DEANS',
        ];
        foreach ($categoryLabels as $catKey => $catLabel):
            if (!isset($officials[$catKey]) || empty($officials[$catKey])) continue;
        ?>
            <div class="college-header"><?= $catLabel ?></div>
            <?php foreach ($officials[$catKey] as $official): ?>
                <div class="official-card">
                    <div class="official-image">
                        <img src="<?= htmlspecialchars($official['image'] ? '/' . $official['image'] : 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200"><rect fill="%23e5e7eb" width="200" height="200"/><text x="50%" y="50%" font-family="sans-serif" font-size="14" fill="%239ca3af" text-anchor="middle" dy=".3em">No Photo</text></svg>') ?>">
                    </div>
                    <div class="official-info">
                        <h2><?= htmlspecialchars($official['name']) ?></h2>
                        <h3><?= htmlspecialchars($official['position']) ?></h3>
                        <p>Email : <?= htmlspecialchars($official['email']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
</section>

<!-- ================= CREST SECTION ================= -->
<section id="crest" class="crest-section">

    <div class="crest-header">
        <h1>CREST Repository</h1>
        <p>Community Research, Extension Services and Training Publications</p>
    </div>

    <?php if (empty($publicationsByYear)): ?>
        <p style="text-align:center; color:#777; padding:40px 0;">
            No publications available yet.
        </p>
    <?php else: ?>
        <?php foreach ($publicationsByYear as $year => $items): ?>
            <div class="crest-year">
                <h2><?= htmlspecialchars($year) ?> Publications</h2>
            </div>

            <div class="crest-grid">
                <?php foreach ($items as $pub): ?>
                    <?php
                    $cover = !empty($pub['cover_image'])
                        ? '/' . htmlspecialchars($pub['cover_image'])
                        : 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="400" height="520"><rect fill="%23e5e7eb" width="400" height="520"/><text x="50%" y="50%" font-family="sans-serif" font-size="18" fill="%239ca3af" text-anchor="middle" dy=".3em">No Cover</text></svg>';
                    ?>
                    <div class="crest-card"
                         data-title="<?= htmlspecialchars($pub['title'] ?? '', ENT_QUOTES) ?>"
                         data-category="<?= htmlspecialchars($pub['category'] ?? '', ENT_QUOTES) ?>"
                         data-year="<?= htmlspecialchars($pub['year'] ?? '', ENT_QUOTES) ?>"
                         data-description="<?= htmlspecialchars($pub['description'] ?? '', ENT_QUOTES) ?>"
                         data-cover="<?= htmlspecialchars($pub['cover_image'] ?? '', ENT_QUOTES) ?>"
                         data-pdf="<?= htmlspecialchars($pub['pdf_file'] ?? '', ENT_QUOTES) ?>"
                         onclick="openCrestBook(this)">
                        <img src="<?= $cover ?>" alt="<?= htmlspecialchars($pub['title'] ?? 'Publication') ?>">
                        <h3><?= htmlspecialchars($pub['title'] ?? 'Untitled') ?></h3>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</section>

<!-- ================= BOOK MODAL ================= -->
<div class="book-modal" id="bookModal">
    <div class="book-slider-container">

        <button class="close-book" onclick="closeCrestBook()" type="button">✕</button>

        <div class="book-slider">
            <div class="book-slide active">

                <div class="book-page left-page">
                    <img id="bookCoverImg"
                         src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='600' height='800'><rect fill='%23e5e7eb' width='600' height='800'/></svg>"
                         alt="Cover">
                </div>

                <div class="book-page right-page">
                    <div class="book-content">
                        <h2 id="bookTitle">Title</h2>
                        <span class="book-meta" id="bookMeta">—</span>
                        <p id="bookDescription">Description will appear here.</p>

                        <div id="bookActions" style="margin-top:24px; display:none;">
                            <a id="bookPdfLink" href="#" target="_blank" rel="noopener" class="btn-read-pdf">
                                <i class="fas fa-file-pdf"></i> Read Publication
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    window.openCrestBook = function (el) {
        const d = el.dataset;

        const cover = d.cover && d.cover.trim() !== ''
            ? '/' + d.cover
            : 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="600" height="800"><rect fill="%23e5e7eb" width="600" height="800"/><text x="50%" y="50%" font-family="sans-serif" font-size="24" fill="%239ca3af" text-anchor="middle" dy=".3em">No Cover</text></svg>';

        document.getElementById('bookCoverImg').src = cover;
        document.getElementById('bookTitle').textContent = d.title || 'Untitled';

        const category = d.category ? d.category.replace(/_/g, ' ').toUpperCase() : 'PUBLICATION';
        document.getElementById('bookMeta').textContent =
            category + ' • ' + (d.year || '—');

        document.getElementById('bookDescription').textContent =
            d.description && d.description.trim() !== ''
                ? d.description
                : 'No description available for this publication.';

        const actions = document.getElementById('bookActions');
        const pdfLink = document.getElementById('bookPdfLink');

        if (d.pdf && d.pdf.trim() !== '') {
            pdfLink.href = '/' + d.pdf;
            actions.style.display = 'block';
        } else {
            actions.style.display = 'none';
        }

        document.getElementById('bookModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    window.closeCrestBook = function () {
        document.getElementById('bookModal').classList.remove('active');
        document.body.style.overflow = '';
    };

    document.getElementById('bookModal').addEventListener('click', function (e) {
        if (e.target === this) closeCrestBook();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeCrestBook();
    });
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>