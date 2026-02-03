<?php
require __DIR__ . '/includes.php';

$posts = get_posts();
$tags = collect_tags($posts);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Blog Platform</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="site-header">
        <div>
            <h1>PHP Blog Platform</h1>
            <p class="subtitle">Stories, insights, and updates from our author.</p>
        </div>
        <nav>
            <a href="index.php">Home</a>
            <?php if (is_logged_in()) : ?>
                <a href="dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            <?php else : ?>
                <a href="login.php">Author Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <main class="container">
        <section class="panel">
            <h2>Latest Posts</h2>
            <?php if (empty($posts)) : ?>
                <p>No posts yet. Log in to create the first story.</p>
            <?php else : ?>
                <div class="post-grid">
                    <?php foreach ($posts as $post) : ?>
                        <article class="post-card">
                            <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                            <p class="post-description"><?php echo htmlspecialchars($post['description']); ?></p>
                            <p class="post-meta">Posted on <?php echo htmlspecialchars($post['created_at']); ?></p>
                            <div class="tag-list">
                                <?php foreach ($post['tags'] as $tag) : ?>
                                    <span class="tag">#<?php echo htmlspecialchars($tag); ?></span>
                                <?php endforeach; ?>
                            </div>
                            <details>
                                <summary>Read more</summary>
                                <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                            </details>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <aside class="panel">
            <h2>Tags</h2>
            <?php if (empty($tags)) : ?>
                <p>No tags yet.</p>
            <?php else : ?>
                <div class="tag-cloud">
                    <?php foreach ($tags as $tag) : ?>
                        <span class="tag">#<?php echo htmlspecialchars($tag); ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </aside>
    </main>

    <script src="assets/app.js"></script>
</body>
</html>
