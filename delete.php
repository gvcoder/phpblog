<?php
require __DIR__ . '/includes.php';
require_auth();

$posts = get_posts();
$id = $_GET['id'] ?? '';
[$index, $post] = find_post($posts, $id);

if ($post !== null) {
    array_splice($posts, $index, 1);
    save_posts($posts);
}

header('Location: dashboard.php');
exit;
