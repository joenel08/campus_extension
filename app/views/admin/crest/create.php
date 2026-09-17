<style>

.crest-manage{
  background:#fff;
  border-radius:22px;
  padding:35px;
  border:1px solid #e5e7eb;
  box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.crest-top{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:35px;
}

.crest-title{
  font-size:30px;
  font-weight:700;
  color:#183153;
}

.crest-subtitle{
  margin-top:8px;
  color:#6b7280;
  font-size:15px;
}

.add-publication-btn{
  background:#16a34a;
  color:#fff;
  border:none;
  padding:15px 24px;
  border-radius:14px;
  cursor:pointer;
  font-size:15px;
  font-weight:600;
}

.add-publication-btn:hover{
  background:#15803d;
}

.crest-form-grid{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:22px;
}

.crest-group{
  display:flex;
  flex-direction:column;
}

.crest-group.full{
  grid-column:1 / span 2;
}

.crest-label{
  font-size:14px;
  font-weight:700;
  margin-bottom:10px;
  color:#111827;
}

.crest-input,
.crest-select,
.crest-textarea{
  width:100%;
  border:1px solid #d1d5db;
  border-radius:14px;
  padding:15px;
  font-size:14px;
  outline:none;
}

.crest-input:focus,
.crest-select:focus,
.crest-textarea:focus{
  border-color:#2563eb;
}

.crest-textarea{
  height:150px;
  resize:none;
}

.crest-preview{
  margin-top:35px;
}

.preview-title{
  font-size:22px;
  font-weight:700;
  color:#183153;
  margin-bottom:22px;
}

.preview-grid{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:22px;
}

.preview-card{
  background:#fff;
  border:1px solid #e5e7eb;
  border-radius:18px;
  overflow:hidden;
  transition:0.3s;
}

.preview-card:hover{
  transform:translateY(-6px);
  box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

.preview-image{
  width:100%;
  height:320px;
  object-fit:cover;
}

.preview-content{
  padding:18px;
}

.preview-content h3{
  font-size:18px;
  color:#111827;
  line-height:1.5;
  margin-bottom:12px;
}

.preview-year{
  display:inline-block;
  background:#e8f0ff;
  color:#2563eb;
  padding:6px 14px;
  border-radius:30px;
  font-size:13px;
  font-weight:700;
}

.preview-actions{
  display:flex;
  gap:10px;
  margin-top:18px;
}

.preview-btn{
  flex:1;
  border:none;
  padding:12px;
  border-radius:12px;
  cursor:pointer;
  font-size:14px;
  font-weight:600;
  color:#fff;
}

.edit-btn{
  background:#2563eb;
}

.delete-btn{
  background:#ef4444;
}

.publish-actions{
  display:flex;
  justify-content:flex-end;
  gap:14px;
  margin-top:35px;
}

.publish-btn{
  background:#16a34a;
  color:#fff;
  border:none;
  padding:15px 26px;
  border-radius:14px;
  cursor:pointer;
  font-size:15px;
  font-weight:600;
}

.reset-btn{
  background:#ef4444;
  color:#fff;
  border:none;
  padding:15px 26px;
  border-radius:14px;
  cursor:pointer;
  font-size:15px;
  font-weight:600;
}

@media(max-width:1200px){

  .preview-grid{
    grid-template-columns:repeat(2,1fr);
  }

}

@media(max-width:768px){

  .crest-form-grid{
    grid-template-columns:1fr;
  }

  .crest-group.full{
    grid-column:auto;
  }

  .preview-grid{
    grid-template-columns:1fr;
  }

}

</style>

<div class="crest-manage">
    <div class="crest-top">
        <div>
            <div class="crest-title"><i class="fas fa-plus-circle"></i> Add Publication</div>
            <div class="crest-subtitle">Add a new digital publication to the CREST repository</div>
        </div>
    </div>

    <form method="POST" action="/admin/crest/store" enctype="multipart/form-data">
        <div class="crest-form-grid">
            <div class="crest-group full">
                <label class="crest-label">Publication Title</label>
                <input type="text" name="title" class="crest-input" placeholder="Enter publication title" required>
            </div>

            <div class="crest-group">
                <label class="crest-label">Publication Year</label>
                <select name="year" class="crest-select">
                    <?php for ($y = date('Y'); $y >= 2000; $y--): ?>
                        <option value="<?= $y ?>" <?= $y == date('Y') ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="crest-group">
                <label class="crest-label">Category</label>
                <select name="category" class="crest-select">
                    <option value="research_journal">Research Journal</option>
                    <option value="extension_publication">Extension Publication</option>
                    <option value="terminal_report">Terminal Report</option>
                    <option value="community_development">Community Development</option>
                </select>
            </div>

            <div class="crest-group">
                <label class="crest-label">Upload Book Cover</label>
                <input type="file" name="cover_image" class="crest-input" accept="image/*">
            </div>

            <div class="crest-group">
                <label class="crest-label">Upload PDF</label>
                <input type="file" name="pdf_file" class="crest-input" accept=".pdf">
            </div>

            <div class="crest-group full">
                <label class="crest-label">Description</label>
                <textarea name="description" class="crest-textarea" placeholder="Enter publication description..."></textarea>
            </div>

            <div class="crest-group full">
                <label class="crest-label">Status</label>
                <select name="status" class="crest-select">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>
        </div>

        <div class="publish-actions">
            <a href="/admin/crest" class="reset-btn" style="text-decoration:none; text-align:center;">Cancel</a>
            <button type="submit" class="publish-btn"><i class="fas fa-cloud-upload-alt"></i> Publish Repository</button>
        </div>
    </form>
</div>