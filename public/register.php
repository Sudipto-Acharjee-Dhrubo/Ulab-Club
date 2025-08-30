<?php
require_once __DIR__ . '/../database.php';
ensure_session_started();

$pdo = db();
$clubs = $pdo->query('SELECT id, name FROM clubs ORDER BY name')->fetchAll();
$selected = isset($_GET['club']) ? (int)$_GET['club'] : 0;

$errors = $_SESSION['form_errors'] ?? [];
$old = $_SESSION['form_old'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_old']);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register & Join a Club</title>
  <link rel="stylesheet" href="../assets/styles.css">
  <script defer src="../assets/script.js"></script>
  <style>
    /* Set background color and text color for whole page */
    body {
      background-color: #f0f8ff; /* Page background */
      color: #0514ebff; /* Default text color */
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    /* Header logo and text */
    header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1rem 0;
    }

    header img {
      height: 60px; /* Adjust logo size */
      margin-right: 1rem;
    }

    header h1 {
      margin: 0;
      font-size: 1.8rem;
    }

    /* Navigation links */
    nav a {
      color: #1127e7ff; /* Nav text color */
      text-decoration: none;
      margin-left: 1rem;
    }

    nav a:hover {
      text-decoration: underline;
    }

    /* Form card */
    .card {
      background-color: #ffffff; /* Card background */
      padding: 1.5rem;
      margin: 2rem 0;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    /* Field container */
    .field {
      margin-bottom: 1rem;
    }

    /* Labels */
    .field label {
      display: block;
      font-weight: bold;
      margin-bottom: 0.25rem;
      color: #1127e7ff; /* Label color */
    }

    /* Inputs and selects */
    .field input,
    .field select {
      width: 100%;
      padding: 0.5rem;
      border-radius: 4px;
      border: 1px solid #1127e7ff; /* Border color matches label */
      background-color: #ecee6eff; /* Input background */
      color: #ffffff; /* Input text color */
      margin-top: 0.25rem;
      margin-bottom: 1rem;
    }

    .field input:focus,
    .field select:focus {
      outline: none;
      border-color: #0514ebff;
      box-shadow: 0 0 5px rgba(5, 20, 235, 0.5);
    }

    /* Button */
    .btn {
      background-color: #4a90e2; /* Button color */
      color: #fff;
      border: none;
      cursor: pointer;
      padding: 0.75rem;
      font-size: 1rem;
      border-radius: 4px;
    }

    .btn:hover {
      background-color: #357abd;
    }

    /* Error message */
    .error {
      background-color: #ffe6e6;
      border: 1px solid #ff4d4d;
      color: #990000;
    }

    footer {
      padding: 1rem 0;
      text-align: center;
      color: #0c0808ff;
    }
</style>

</head>
<body>
  <header class="container">
    <div style="display:flex; align-items:center;">
      <img src="../images/ortfolio-ulab-460x295.png" alt="ULAB Logo">
      <h1>Create Account & Join a Club</h1>
    </div>
    <nav>
      <a href="index.php">Home</a>
      <a href="login.php">Login</a>
    </nav>
  </header>

  <main class="container">
    <?php if ($errors): ?>
      <div class="card error">
        <ul>
          <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form class="card" id="registerForm" method="post" action="process_register.php" novalidate>
      <input type="hidden" name="action" value="register_and_join">
      <div class="field">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" required value="<?= htmlspecialchars($old['name'] ?? '') ?>">
      </div>
      <div class="field">
        <label for="email">ULAB Email</label>
        <input type="email" id="email" name="email" required value="<?= htmlspecialchars($old['email'] ?? '') ?>">
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" minlength="6" required>
      </div>
      <div class="field">
        <label for="club_id">Select Club</label>
        <select id="club_id" name="club_id" required>
          <option value="">-- Choose a club --</option>
          <?php foreach ($clubs as $c): ?>
            <option value="<?= (int)$c['id'] ?>" <?= $selected === (int)$c['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($c['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="actions">
        <button class="btn" type="submit">Create Account & Join</button>
      </div>
    </form>
  </main>

  <footer class="container muted">
    <small>&copy; <?php echo date('Y'); ?> ULAB – Open-Ended Lab</small>
  </footer>
</body>
</html>
