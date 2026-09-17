<footer class="footer">

  <div class="footer-container">

    <!-- LEFT -->
    <div class="footer-about">

      <div class="footer-logo">

        <img src="/images/isu_logo.png" alt="ISU Logo">

        <div>
          <h2>ISABELA STATE UNIVERSITY</h2>
          <p>Extension & Training Services</p>
        </div>

      </div>

      <p class="footer-text">
        Isabela State University Extension and Training Services
        is committed to empowering communities through innovation,
        education, sustainable development, and public service.
      </p>

    </div>

    <!-- QUICK LINKS -->
    <div class="footer-links">

      <h3>Follow Us</h3>

      <a href="https://www.facebook.com/isu.universitycommunityengagement" target="_blank">ISU Univeristy Engagement</a>
      <a href="https://www.facebook.com/profile.php?id=61580487336227" target="_blank">ISUC-Extension and Training Services</a>

    </div>

    <!-- CONTACT -->
    <div class="footer-contact">

      <h3>Contact Information</h3>

      <p>📍 <?= htmlspecialchars($about['contact_address'] ?? 'Cabagan, Isabela, Philippines') ?></p>
      <p>📞 <?= htmlspecialchars($about['contact_phone'] ?? '+63 912 345 6789') ?></p>
      <p>✉️ <?= htmlspecialchars($about['contact_email'] ?? 'extension@isu.edu.ph') ?></p>

    </div>

  </div>

  <!-- BOTTOM -->
  <div class="footer-bottom">
    © 2026 Isabela State University — Extension & Training Services. All Rights Reserved.
  </div>

</footer>

 <script src="/js/app.js"></script>
 
</body>

</html>