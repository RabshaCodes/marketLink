<?php
session_start();
// require_once 'includes/database.php';
require_once __DIR__ . '/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action == 'login') {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['login_success'] = true;
            header("Location: ../dashboard.php");
            exit;
        } else {
            $_SESSION['auth_error'] = 'Invalid email or password.';
            header("Location: ../login.php");
            exit;
        }
    } elseif ($action == 'register') {
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
        $role = isset($_POST['role']) && $_POST['role'] == 'farmer' ? 'farmer' : 'customer';

        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $_SESSION['auth_error'] = 'Email is already registered.';
            header("Location: ../login.php?tab=register");
            exit;
        } else {
            $pdo->beginTransaction();
            try {
                $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password_hash, role) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$first_name, $last_name, $email, $password, $role]);
                $user_id = $pdo->lastInsertId();

                if ($role == 'farmer') {
                    $farm_name = trim($_POST['stall_name'] ?? '');
                    $farm_location = trim($_POST['address'] ?? '');
                    $stmt_farm = $pdo->prepare("INSERT INTO farmers (user_id, farm_name, farm_location) VALUES (?, ?, ?)");
                    $stmt_farm->execute([$user_id, $farm_name, $farm_location]);
                }

                $pdo->commit();
                $_SESSION['auth_success'] = 'Registration successful! You can now login.';
                header("Location: ../login.php");
                exit;
            } catch (Exception $e) {
                $pdo->rollBack();
                $_SESSION['auth_error'] = 'Registration failed. Please try again.';
                header("Location: ../login.php?tab=register");
                exit;
            }
        }
    }
}
header("Location: ../index.php");
exit;
