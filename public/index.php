<?php
require_once __DIR__ . '/../database.php';
ensure_session_started();

$pdo = db();
$clubs = $pdo->query('SELECT id, name, description FROM clubs ORDER BY name')->fetchAll();

$student_membership = null;
if (is_logged_in()) {
    $stmt = $pdo->prepare('
        SELECT m.id AS membership_id, c.name AS club_name, m.joined_at
        FROM memberships m
        JOIN clubs c ON c.id = m.club_id
        WHERE m.student_id = ?
        LIMIT 1
    ');
    $stmt->execute([ current_student_id() ]);
    $student_membership = $stmt->fetch();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ULAB Clubs</title>
  <link rel="stylesheet" href="../assets/styles.css">

  <!-- Force white background + dark text -->
  <style>
  /* === Color Variables & Existing Styles === */
  :root {
    --page-bg: #f0f8ff;          /* Page background */
    --text-color: #0514ebff;     /* Default text */
    --header-bg: #ffffff;        /* Header background */
    --header-text: #0514ebff;    /* Header text color */
    --nav-link: #1127e7ff;       /* Nav links */
    --nav-link-hover: #11110aff;   /* Nav link hover */
    --card-bg: #f8e90eff;        /* Card background */
    --card-text: #3413f1ff;      /* Card text */
    --btn-bg: #3a11f3ff;           /* Button background */
    --btn-text: #bceb12ff;          /* Button text */
    --btn-hover: #f6f6f7ff;        /* Button hover */
    --success-bg: #e2e6f1ff;       /* Success card background */
    --info-bg: #f5f4f0ff;          /* Info card background */
  }

  body {
    background: var(--page-bg);
    color: var(--text-color);
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
  }

  /* Header, nav, cards, grid, footer ... existing styles here ... */
  </style>

  <!-- === Slideshow Background Styles === -->
  <style>
  body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      opacity: 0.4; /* semi-transparent for readability */
      transition: background-image 1s ease-in-out;
  }

  body::after {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.25); /* overlay to darken background */
      z-index: -1;
  }
  .header-box {
    background-color: rgba(0, 0, 0, 0.6); /* semi-transparent dark box */
    color: #ffffff;                       /* text color */
    padding: 0.25rem 0.75rem;             /* space inside box */
    border-radius: 6px;                    /* rounded corners */
    font-weight: bold;
    font-size: 1.6rem;
}
.header-box-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    background-color: rgba(0, 0, 0, 0.6); /* semi-transparent dark background */
    padding: 0.25rem 0.75rem;
    border-radius: 8px;
}

.header-box-wrapper .header-logo {
    height: 50px;
}

.header-box-text {
    color: #ffffff;
    font-weight: bold;
    font-size: 1.6rem;
}

  </style>

</head>


<body>
  <header class="container">
  <div class="header-box-wrapper">
    <img src="../images/ortfolio-ulab-460x295.png" alt="ULAB Logo" class="header-logo">
    <span class="header-box-text">ULAB Club Membership</span>
  </div>

  <nav>
    <a href="index.php">Home</a>
    <?php if (is_logged_in()): ?>
      <a href="dashboard.php">Dashboard</a>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
      <a class="btn" href="register.php">Register</a>
    <?php endif; ?>
  </nav>
</header>


  <main class="container">
    <?php if ($student_membership): ?>
      <div class="card success">
        <h3>Your Membership</h3>
        <p>You are a member of <strong><?= htmlspecialchars($student_membership['club_name']) ?></strong> since <?= htmlspecialchars($student_membership['joined_at']) ?>.</p>
        <a class="btn" href="dashboard.php">View Dashboard</a>
      </div>
    <?php else: ?>
      <div class="card info">
        <p>You are not a member yet. Create an account and pick a club!</p>
        <a class="btn" href="register.php">Register Now</a>
      </div>
    <?php endif; ?>

    <?php
// Map club names to logo paths
$club_logos = [
    'ULAB Computer Club' => '../images/ucpc logo.png',
    'ULAB Debate Club' => '../images/Screenshot 2025-08-30 230921.jpg',
    'ULAB Photography Club' => '../images/uapc logo edit(3) - Md. Mahinur Hasan (192011101).png',
];
?>
<section class="grid">
  <?php foreach ($clubs as $club): ?>
    <article class="card">
      <h2 style="display:flex; align-items:center; gap:10px;">
        <?php
          $logo = $club_logos[$club['name']] ?? '';
          if ($logo):
        ?>
          <img src="<?= htmlspecialchars($logo) ?>" alt="<?= htmlspecialchars($club['name']) ?> Logo" style="height:40px;">
        <?php endif; ?>
        <?= htmlspecialchars($club['name']) ?>
      </h2>
      <p><?= nl2br(htmlspecialchars($club['description'] ?? '')) ?></p>
      <div class="actions">
        <a class="btn outline" href="register.php?club=<?= (int)$club['id'] ?>">Join this club</a>
      </div>
    </article>
  <?php endforeach; ?>
</section>

  </main>

  <footer class="container muted">
    <small>&copy; <?php echo date('Y'); ?> ULAB – Open-Ended Lab</small>
  </footer>
   <script>
  const images = [
      '../images/2222.jpeg',
      '../images/488493048_1217561443702228_8513169703201219345_n.jpg',
      '../images/497589450_1258362872959723_6216860577255493752_n.jpg',
      '../images/465892507_8978449168865974_7025289894063229431_n.jpg'
  ];

  let current = 0;
  const bodyBefore = document.body;

  function changeBackground() {
      bodyBefore.style.setProperty('--bg-image', `url('${images[current]}')`);
      document.body.style.backgroundImage = `url('${images[current]}')`;
      current = (current + 1) % images.length;
  }

  // Initial background
  changeBackground();

  // Change every 5 seconds
  setInterval(changeBackground, 5000);
  </script>
</body>
</html>
