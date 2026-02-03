<?php
require __DIR__ . '/includes.php';
require_auth();

$posts = get_posts();
$id = $_GET['id'] ?? '';
[$index, $post] = find_post($posts, $id);

if ($post === null) {
    header('Location: dashboard.php');
    exit;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $tags = format_tags(explode(',', $_POST['tags'] ?? ''));

    if ($title && $description && $content) {
        $posts[$index] = [
            'id' => $post['id'],
            'title' => $title,
            'description' => $description,
            'content' => $content,
            'tags' => $tags,
            'created_at' => $post['created_at'],
        ];
        save_posts($posts);
        $message = 'Post updated successfully.';
        $post = $posts[$index];
    } else {
        $message = 'Please fill in all required fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="site-header">
        <div>
            <h1>Edit Post</h1>
            <p class="subtitle">Update your post details.</p>
        </div>
        <nav>
            <a href="dashboard.php">Back to Dashboard</a>
        </nav>
    </header>

    <main class="container">
        <section class="panel">
            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
            <?php if ($message) : ?>
                <p class="notice"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <form method="post" class="form-grid">
                <label>
                    Blog Title
                    <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                </label>
                <label>
                    Short Description
                    <input type="text" name="description" value="<?php echo htmlspecialchars($post['description']); ?>" required>
                </label>
                <label>
                    Blog Text (approx. 100 words)
                    <textarea name="content" rows="6" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                </label>
                <label>
                    Tags (comma-separated)
                    <input type="text" name="tags" value="<?php echo htmlspecialchars(implode(', ', $post['tags'])); ?>">
                </label>
                <button type="submit" class="primary">Save Changes</button>
            </form>
        </section>
    </main>
</body>
</html>
