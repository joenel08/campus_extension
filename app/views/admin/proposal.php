
<style>

.proposal-container{
  background:#fff;
  border-radius:18px;
  padding:35px;
  border:1px solid #e5e7eb;
  box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.proposal-header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:30px;
}

.proposal-title{
  font-size:28px;
  font-weight:700;
  color:#183153;
}

.proposal-subtitle{
  color:#6b7280;
  margin-top:8px;
  font-size:15px;
}

.proposal-grid{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:20px;
}

.proposal-group{
  display:flex;
  flex-direction:column;
}

.proposal-group.full{
  grid-column:1 / span 2;
}

.proposal-label{
  font-size:14px;
  font-weight:700;
  color:#111827;
  margin-bottom:10px;
}

.proposal-input,
.proposal-select,
.proposal-textarea{
  width:100%;
  border:1px solid #d1d5db;
  border-radius:14px;
  padding:15px;
  font-size:14px;
  outline:none;
  background:#fff;
}

.proposal-input:focus,
.proposal-select:focus,
.proposal-textarea:focus{
  border-color:#2563eb;
}

.proposal-textarea{
  height:160px;
  resize:none;
}

.status-box{
  margin-top:30px;
  padding:22px;
  background:#f8fafc;
  border-radius:16px;
  border:1px solid #e5e7eb;
}

.status-title{
  font-size:18px;
  font-weight:700;
  margin-bottom:18px;
  color:#183153;
}

.status-grid{
  display:grid;
  grid-template-columns:1fr 1fr 1fr;
  gap:15px;
}

.status-card{
  background:#fff;
  border:1px solid #e5e7eb;
  border-radius:14px;
  padding:18px;
}

.status-card h4{
  font-size:14px;
  color:#6b7280;
  margin-bottom:10px;
}

.status-card p{
  font-size:18px;
  font-weight:700;
  color:#111827;
}

.proposal-buttons{
  display:flex;
  justify-content:flex-end;
  gap:12px;
  margin-top:30px;
}

.publish-btn{
  background:#16a34a;
  color:#fff;
  border:none;
  padding:14px 24px;
  border-radius:12px;
  cursor:pointer;
  font-size:15px;
  font-weight:600;
}

.publish-btn:hover{
  background:#15803d;
}

.reset-btn{
  background:#ef4444;
  color:#fff;
  border:none;
  padding:14px 24px;
  border-radius:12px;
  cursor:pointer;
  font-size:15px;
  font-weight:600;
}

.reset-btn:hover{
  background:#dc2626;
}

</style>

<div class="proposal-container">

  <div class="proposal-header">

    <div>

      <div class="proposal-title">
        <i class="fas fa-file-signature"></i>
        Call for Proposals
      </div>

      <div class="proposal-subtitle">
        Setup new proposal announcement and submission deadline
      </div>

    </div>

  </div>

  <div class="proposal-grid">

    <!-- TITLE -->
    <div class="proposal-group full">

      <label class="proposal-label">
        Proposal Title
      </label>

      <input type="text"
             class="proposal-input"
             placeholder="Enter proposal title">

    </div>

    <!-- CATEGORY -->
    <div class="proposal-group">

      <label class="proposal-label">
        Proposal Category
      </label>

      <select class="proposal-select">

        <option>Internally Funded</option>
        <option>Externally Funded</option>

      </select>

    </div>

    <!-- STATUS -->
    <div class="proposal-group">

      <label class="proposal-label">
        Status
      </label>

      <select class="proposal-select">

        <option>Open</option>
        <option>Closed</option>

      </select>

    </div>

    <!-- START DATE -->
    <div class="proposal-group">

      <label class="proposal-label">
        Opening Date
      </label>

      <input type="date"
             class="proposal-input">

    </div>

    <!-- DEADLINE -->
    <div class="proposal-group">

      <label class="proposal-label">
        Submission Deadline
      </label>

      <input type="date"
             class="proposal-input">

    </div>

    <!-- FILE -->
    <div class="proposal-group full">

      <label class="proposal-label">
        Upload Proposal Guidelines
      </label>

      <input type="file"
             class="proposal-input">

    </div>

    <!-- DESCRIPTION -->
    <div class="proposal-group full">

      <label class="proposal-label">
        Proposal Description
      </label>

      <textarea class="proposal-textarea"
                placeholder="Enter proposal announcement details..."></textarea>

    </div>

  </div>

  <!-- BUTTONS -->
  <div class="proposal-buttons">

    <button class="reset-btn">

      <i class="fas fa-trash"></i>
      Reset

    </button>

    <button class="publish-btn">

      <i class="fas fa-paper-plane"></i>
      Publish Proposal

    </button>

  </div>

</div>
