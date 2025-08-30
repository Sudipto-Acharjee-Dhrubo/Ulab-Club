<?php
require_once __DIR__ . '/../database.php';
ensure_session_started();

$errors = $_SESSION['login_errors'] ?? [];
unset($_SESSION['login_errors']);

if (is_logged_in()) {
    redirect('/dashboard.php');
}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
  <header class="container">
    <h1>Login</h1>
    <nav>
      <a href="index.php">Home</a>
      <a href="register.php">Register</a>
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
    <form class="card" method="post" action="login.php">
      <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div class="actions">
        <button class="btn" type="submit">Login</button>
      </div>
    </form>
  </main>

  <footer class="container muted">
    <small>&copy; <?php echo date('Y'); ?> ULAB – Open-Ended Lab</small>
  </footer>
</body>
</html>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $pdo = db();
    $stmt = $pdo->prepare('SELECT id, password_hash FROM students WHERE email = ?');
    $stmt->execute([$email]);
    $student = $stmt->fetch();

    if (!$student || !password_verify($password, $student['password_hash'])) {
        $_SESSION['login_errors'] = ['Invalid email or password.'];
        redirect('/login.php');
    }

    $_SESSION['student_id'] = (int)$student['id'];
    redirect('/dashboard.php');
}
?>
