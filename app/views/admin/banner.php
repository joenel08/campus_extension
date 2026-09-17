<div class="post-card">

  <div class="post-title">
    <i class="fas fa-image"></i>
    Banner Management
  </div>

  <div class="post-grid">

    <div class="post-group full">
      <label class="post-label">
        Banner Title
      </label>

      <input type="text"
             class="post-input"
             placeholder="Enter banner title">
    </div>

    <div class="post-group full">
      <label class="post-label">
        Upload Banner Video
      </label>

      <input type="file"
             class="post-input"
             accept="video/*">
    </div>

  </div>

  <div class="post-btns">

    <button class="update-btn">
      <i class="fas fa-pen"></i>
      Update
    </button>

    <button class="delete-post-btn">
      <i class="fas fa-trash"></i>
      Delete
    </button>

  </div>

</div>

`;
    }

    /* LATEST NEWS */
    else if (page === 'news') {

        content.innerHTML = `

<div class="post-card">

  <div class="post-title">
    <i class="fas fa-bullhorn"></i>
    Latest News
  </div>

  <div class="post-grid">

    <div class="post-group">
      <label class="post-label">
        News Title
      </label>

      <input type="text"
             class="post-input">
    </div>

    <div class="post-group">
      <label class="post-label">
        News Date
      </label>

      <input type="date"
             class="post-input">
    </div>

    <div class="post-group full">
      <label class="post-label">
        Upload News Image
      </label>

      <input type="file"
             class="post-input"
             accept="image/*">
    </div>

    <div class="post-group full">
      <label class="post-label">
        News Description
      </label>

      <textarea class="post-textarea"></textarea>
    </div>

  </div>

  <div class="post-btns">

    <button class="update-btn">
      <i class="fas fa-pen"></i>
      Update
    </button>

    <button class="delete-post-btn">
      <i class="fas fa-trash"></i>
      Delete
    </button>

  </div>

</div>
