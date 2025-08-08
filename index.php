<?php
// index.php

// 1) starts session, outputs <head> and opening <body>
require_once __DIR__ . '/includes/header.php';
?>

<!-- 2) Main content area -->
<main class="container">
  <section class="py-5 text-center">
    <h1>Welcome to My PHP Site</h1>
    <p class="lead">Use the navigation above to register, log in, or view content.</p>
  </section>
</main>

<?php
// 3) outputs closing </body></html>
require_once __DIR__ . '/includes/footer.php';
