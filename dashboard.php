<?php
require __DIR__ . '/includes.php';
require_auth();

$posts = get_posts();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $tags = format_tags(explode(',', $_POST['tags'] ?? ''));

    if ($title && $description && $content) {
        $posts[] = [
            'id' => generate_id(),
            'title' => $title,
            'description' => $description,
            'content' => $content,
            'tags' => $tags,
            'created_at' => date('F j, Y'),
        ];
        save_posts($posts);
        $message = 'Post created successfully.';
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
    <title>Author Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="site-header">
        <div>
            <h1>Author Dashboard</h1>
            <p class="subtitle">Create, edit, and delete blog posts.</p>
        </div>
        <nav>
            <a href="index.php">Home</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main class="container">
        <section class="panel">
            <h2>Create New Post</h2>
            <?php if ($message) : ?>
                <p class="notice"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <form method="post" class="form-grid">
                <label>
                    Blog Title
                    <input type="text" name="title" required>
                </label>
                <label>
                    Short Description
                    <input type="text" name="description" required>
                </label>
                <label>
                    Blog Text (approx. 100 words)
                    <textarea name="content" rows="6" required></textarea>
                </label>
                <label>
                    Tags (comma-separated)
                    <input type="text" name="tags" placeholder="tech, php, updates">
                </label>
                <button type="submit" class="primary">Publish Post</button>
            </form>
        </section>

        <section class="panel">
            <h2>Manage Posts</h2>
            <?php if (empty($posts)) : ?>
                <p>No posts created yet.</p>
            <?php else : ?>
                <div class="manage-list">
                    <?php foreach ($posts as $post) : ?>
                        <div class="manage-item">
                            <div>
                                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                                <p><?php echo htmlspecialchars($post['description']); ?></p>
                            </div>
                            <div class="actions">
                                <a class="button" href="edit.php?id=<?php echo urlencode($post['id']); ?>">Edit</a>
                                <a class="button danger" href="delete.php?id=<?php echo urlencode($post['id']); ?>" onclick="return confirm('Delete this post?');">Delete</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <script src="assets/app.js"></script>
</body>
</html>
