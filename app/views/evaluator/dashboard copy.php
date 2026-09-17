<div class="paper-container">

  <div class="paper-header">

    <div class="title-header">
      Title
    </div>

    <div class="action-header">
      Action
    </div>

  </div>

  <!-- ROWS -->

  <div class="paper-row">
    <span>1. Community Extension Proposal</span>
    <button class="evaluate-btn" onclick="openEvaluation(this)">
      Evaluate
    </button>
  </div>

  <div class="paper-row">
    <span>2. Agricultural Development Research</span>
    <button class="evaluate-btn" onclick="openEvaluation(this)">
      Evaluate
    </button>
  </div>

  <div class="paper-row">
    <span>3. Training Services Proposal</span>
    <button class="evaluate-btn" onclick="openEvaluation(this)">
      Evaluate
    </button>
  </div>

  <div class="paper-row">
    <span>4. Student Community Program</span>
    <button class="evaluate-btn" onclick="openEvaluation(this)">
      Evaluate
    </button>
  </div>

  <div class="paper-row">
    <span>5. Environmental Awareness Project</span>
    <button class="evaluate-btn" onclick="openEvaluation(this)">
      Evaluate
    </button>
  </div>

</div>

<div class="modal" id="evaluationModal">

  <div class="modal-content">

    <span class="close-btn" onclick="closeModal()">
      &times;
    </span>

    <style>
      .evaluation-container {
        background: #fff;
        padding: 35px;
        border-radius: 18px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
      }

      .evaluation-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
      }

      .evaluation-title {
        font-size: 28px;
        font-weight: 700;
        color: #183153;
      }

      .generate-btn {
        background: #2563eb;
        color: #fff;
        border: none;
        padding: 14px 22px;
        border-radius: 12px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
      }

      .generate-btn:hover {
        background: #1d4ed8;
      }

      .eval-form {
        margin-bottom: 30px;
      }

      .eval-row {
        display: flex;
        align-items: center;
        margin-bottom: 18px;
        gap: 12px;
      }

      .eval-label {
        width: 190px;
        font-weight: 700;
        color: #111827;
      }

      .eval-line {
        flex: 1;
        border: none;
        border-bottom: 1px solid #444;
        outline: none;
        padding: 8px;
        font-size: 14px;
      }

      .criteria-title {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 18px;
        color: #183153;
      }

      .eval-table {
        width: 100%;
      }

      .eval-table th,
      .eval-table td {
        border: 1px solid #555;
        padding: 16px;
        text-align: center;
      }

      .eval-table th {
        background: #f8fafc;
        font-size: 15px;
        font-weight: 700;
      }

      .eval-table td:first-child {
        text-align: left;
        font-weight: 600;
        width: 280px;
      }

      .eval-table input {
        width: 18px;
        height: 18px;
        cursor: pointer;
      }

      .comment-box {
        margin-top: 30px;
      }

      .comment-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 12px;
      }

      .comment-textarea {
        width: 100%;
        height: 50px;
        border: none;
        border-bottom: 1px solid #555;
        outline: none;
        resize: none;
        font-size: 15px;
        padding: 10px;
      }
    </style>


    <div class="evaluation-container">

      <div class="evaluation-header">

        <div class="evaluation-title">
          <i class="fas fa-check-circle"></i>
          Evaluation Criteria
        </div>

        <button class="generate-btn" onclick="generateEvaluationPDF()">

          <i class="fas fa-file-pdf"></i>
          Generate PDF Form

        </button>

      </div>

      <!-- PROJECT DETAILS -->

      <div class="eval-form">

        <div class="eval-row">
          <div class="eval-label">Project Title:</div>
          <input type="text" id="projectTitle" class="eval-line">
        </div>

        <div class="eval-row">
          <div class="eval-label">Project Manager:</div>
          <input type="text" id="projectManager" class="eval-line">
        </div>

        <div class="eval-row">
          <div class="eval-label">Submission Date:</div>
          <input type="date" id="submissionDate" class="eval-line">
        </div>

        <div class="eval-row">
          <div class="eval-label">Evaluator's Name:</div>
          <input type="text" id="evaluatorName" class="eval-line">
        </div>

      </div>

      <!-- TABLE -->

      <div class="criteria-title">
        Evaluation Criteria
      </div>

      <table class="eval-table">

        <thead>

          <tr>
            <th>Criteria</th>
            <th>Excellent<br>(5)</th>
            <th>Good<br>(4)</th>
            <th>Fair<br>(3)</th>
            <th>Poor<br>(2)</th>
            <th>Unsatisfactory<br>(1)</th>
          </tr>

        </thead>

        <tbody>

          <tr>
            <td>Project Objectives Clarity</td>

            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
          </tr>

          <tr>
            <td>Feasibility of Approach</td>

            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
          </tr>

          <tr>
            <td>Innovation and Creativity</td>

            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
          </tr>

          <tr>
            <td>Budget Justification</td>

            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
          </tr>

          <tr>
            <td>Team Qualifications</td>

            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
          </tr>

          <tr>
            <td>Risk Assessment</td>

            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
          </tr>

        </tbody>

      </table>

      <!-- COMMENTS -->

      <div class="comment-box">

        <div class="comment-title">
          Comments:
        </div>

        <textarea class="comment-textarea"></textarea>

      </div>

      <button class="submit-eval" onclick="submitEvaluation()">
        Submit Evaluation
      </button>

    </div>

  </div>

  <script>
    let currentButton = null;

    function openEvaluation(button) {

      currentButton = button;

      document.getElementById("evaluationModal").style.display = "flex";
    }

    function closeModal() {

      document.getElementById("evaluationModal").style.display = "none";
    }

    function submitEvaluation() {

      if (currentButton) {

        currentButton.innerHTML = "Evaluated";

        currentButton.classList.remove("evaluate-btn");

        currentButton.classList.add("evaluated-btn");

        currentButton.disabled = true;
      }

      closeModal();

      alert("Evaluation Submitted Successfully!");
    }

    window.onclick = function(event) {

      const modal = document.getElementById("evaluationModal");

      if (event.target == modal) {

        modal.style.display = "none";
      }
    }
  </script>
</div>