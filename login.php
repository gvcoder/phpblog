<?php
require __DIR__ . '/includes.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $credentials = get_admin_credentials();
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === $credentials['username'] && $password === $credentials['password']) {
        $_SESSION['logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    }

    $error = 'Invalid credentials. Please try again.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Author Login</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="site-header">
        <div>
            <h1>Author Login</h1>
            <p class="subtitle">Sign in to manage posts.</p>
        </div>
        <nav>
            <a href="index.php">Home</a>
        </nav>
    </header>

    <main class="container">
        <section class="panel form-panel">
            <h2>Login</h2>
            <?php if ($error) : ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form method="post" class="form-grid">
                <label>
                    Username
                    <input type="text" name="username" required>
                </label>
                <label>
                    Password
                    <input type="password" name="password" required>
                </label>
                <button type="submit" class="primary">Login</button>
            </form>
        </section>
    </main>
</body>
</html>
