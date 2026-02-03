<?php
session_start();

define('BLOG_DATA_FILE', __DIR__ . '/data/blogs.json');

define('BLOG_ENV_FILE', __DIR__ . '/.env');

function load_env($path)
{
    $vars = [];
    if (!file_exists($path)) {
        return $vars;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) {
            continue;
        }
        if (!str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $vars[trim($key)] = trim($value);
    }

    return $vars;
}

function get_admin_credentials()
{
    $env = load_env(BLOG_ENV_FILE);
    return [
        'username' => $env['BLOG_ADMIN_USERNAME'] ?? 'admin',
        'password' => $env['BLOG_ADMIN_PASSWORD'] ?? 'blogger',
    ];
}

function get_posts()
{
    if (!file_exists(BLOG_DATA_FILE)) {
        return [];
    }

    $contents = file_get_contents(BLOG_DATA_FILE);
    $data = json_decode($contents, true);
    if (!is_array($data)) {
        return [];
    }

    return $data;
}

function save_posts($posts)
{
    $json = json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $handle = fopen(BLOG_DATA_FILE, 'c+');
    if ($handle === false) {
        return false;
    }
    if (!flock($handle, LOCK_EX)) {
        fclose($handle);
        return false;
    }
    ftruncate($handle, 0);
    rewind($handle);
    fwrite($handle, $json);
    fflush($handle);
    flock($handle, LOCK_UN);
    fclose($handle);

    return true;
}

function is_logged_in()
{
    return !empty($_SESSION['logged_in']);
}

function require_auth()
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function format_tags($tags)
{
    if (empty($tags)) {
        return [];
    }

    $normalized = [];
    foreach ($tags as $tag) {
        $trimmed = trim($tag);
        if ($trimmed === '') {
            continue;
        }
        $normalized[] = $trimmed;
    }

    return array_values(array_unique($normalized));
}

function collect_tags($posts)
{
    $tags = [];
    foreach ($posts as $post) {
        foreach ($post['tags'] ?? [] as $tag) {
            $tags[$tag] = true;
        }
    }

    $list = array_keys($tags);
    sort($list, SORT_NATURAL | SORT_FLAG_CASE);
    return $list;
}

function find_post($posts, $id)
{
    foreach ($posts as $index => $post) {
        if ($post['id'] === $id) {
            return [$index, $post];
        }
    }

    return [null, null];
}

function generate_id()
{
    return bin2hex(random_bytes(8));
}
