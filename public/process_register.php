<?php
require_once __DIR__ . '/../database.php';
ensure_session_started();

function back_with_errors($errors, $old = []) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_old'] = $old;
    header('Location: register.php');
    exit;
}

if (($_POST['action'] ?? '') === 'register_and_join') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $club_id = (int)($_POST['club_id'] ?? 0);

    $errors = [];
    if ($name === '') $errors[] = 'Name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($club_id <= 0) $errors[] = 'Please choose a club.';

    if ($errors) {
        back_with_errors($errors, compact('name','email'));
    }

    $pdo = db();
    $pdo->beginTransaction();
    try {
        // Check if student exists
        $stmt = $pdo->prepare('SELECT id, password_hash FROM students WHERE email = ?');
        $stmt->execute([$email]);
        $student = $stmt->fetch();

        if ($student) {
            // If exists, verify password
            if (!password_verify($password, $student['password_hash'])) {
                throw new Exception('Account exists. Password is incorrect.');
            }
            $student_id = (int)$student['id'];
        } else {
            // Create new student
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO students (name, email, password_hash) VALUES (?, ?, ?)');
            $stmt->execute([$name, $email, $hash]);
            $student_id = (int)$pdo->lastInsertId();
        }

        // Enforce one membership per student (unique constraint on memberships.student_id)
        // Try insert membership
        $stmt = $pdo->prepare('INSERT INTO memberships (student_id, club_id) VALUES (?, ?)');
        $stmt->execute([$student_id, $club_id]);

        $pdo->commit();

        // set session
        ensure_session_started();
        $_SESSION['student_id'] = $student_id;

        redirect('/dashboard.php');
    } catch (Exception $e) {
        $pdo->rollBack();
        back_with_errors([$e->getMessage()], compact('name','email'));
    }
} else {
    redirect('/register.php');
}
