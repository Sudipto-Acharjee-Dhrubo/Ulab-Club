<?php
require_once __DIR__ . '/../database.php';
ensure_session_started();

if (!is_logged_in()) {
    redirect('/login.php');
}

$pdo = db();

// current student
$stmt = $pdo->prepare('SELECT id, name, email, created_at FROM students WHERE id = ?');
$stmt->execute([ current_student_id() ]);
$student = $stmt->fetch();

// membership + club
$stmt = $pdo->prepare('
    SELECT m.id AS membership_id, m.joined_at, c.id AS club_id, c.name AS club_name, c.description
    FROM memberships m
    JOIN clubs c ON c.id = m.club_id
    WHERE m.student_id = ?
    LIMIT 1
');
$stmt->execute([ current_student_id() ]);
$membership = $stmt->fetch();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
  <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
  <header class="container" style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
  <div style="display:flex; align-items:center; gap:10px;">
    <img src="../images/ortfolio-ulab-460x295.png" alt="ULAB Logo" style="height:50px;">
    <h1>Dashboard</h1>
  </div>

  <nav>
    <a href="index.php">Home</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>


  <main class="container">
    <div class="grid-2">
      <section class="card">
        <h2>Your Profile</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($student['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></p>
        <p><strong>Joined:</strong> <?= htmlspecialchars($student['created_at']) ?></p>
      </section>

      <section class="card">
        <h2>Your Club</h2>
        <?php if ($membership): ?>
          <p><strong>Club:</strong> <?= htmlspecialchars($membership['club_name']) ?></p>
          <p><strong>Member since:</strong> <?= htmlspecialchars($membership['joined_at']) ?></p>
          <p><?= nl2br(htmlspecialchars($membership['description'] ?? '')) ?></p>
        <?php else: ?>
          <p>You have not joined a club yet.</p>
          <a class="btn" href="index.php">Browse Clubs</a>
        <?php endif; ?>
      </section>
    </div>
  </main>

  <footer class="container muted">
    <small>&copy; <?php echo date('Y'); ?> ULAB – Open-Ended Lab</small>
  </footer>
</body>
</html>
