<?php

require_once __DIR__ . '/database.php';

function load_blogs(array $config): array
{
    $csv_url = trim($config['blog_sheet_csv_url'] ?? '');
    $cache_file = dirname(__DIR__) . '/storage/cache/blog_posts.csv';
    $cache_ttl = (int) ($config['blog_cache_seconds'] ?? 900);

    if ($csv_url !== '') {
        $csv = fetch_blog_csv($csv_url, $cache_file, $cache_ttl);
        $blogs = parse_blog_csv($csv);

        if ($blogs !== []) {
            return $blogs;
        }
    }

    return load_blogs_from_database();
}

function load_blogs_from_database(): array
{
    try {
        $db = get_db();
        $stmt = $db->query("SELECT * FROM blog_posts ORDER BY id ASC");
        $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map('normalize_blog_post', $blogs ?: []);
    } catch (PDOException $e) {
        return [];
    }
}

function fetch_blog_csv(string $csv_url, string $cache_file, int $cache_ttl): string
{
    $has_fresh_cache = is_file($cache_file) && (time() - filemtime($cache_file) < $cache_ttl);

    if ($has_fresh_cache) {
        return (string) file_get_contents($cache_file);
    }

    $context = stream_context_create([
        'http' => [
            'timeout' => 8,
            'ignore_errors' => true,
        ],
    ]);
    $csv = @file_get_contents($csv_url, false, $context);

    if (is_string($csv) && trim($csv) !== '') {
        file_put_contents($cache_file, $csv);
        return $csv;
    }

    if (is_file($cache_file)) {
        return (string) file_get_contents($cache_file);
    }

    return '';
}

function parse_blog_csv(string $csv): array
{
    if (trim($csv) === '') {
        return [];
    }

    $handle = fopen('php://temp', 'r+');
    fwrite($handle, $csv);
    rewind($handle);

    $headers = fgetcsv($handle, 0, ',', '"', '');
    if (!is_array($headers)) {
        fclose($handle);
        return [];
    }

    $headers = array_map(fn($header) => normalize_blog_key((string) $header), $headers);
    $blogs = [];
    $position = 1;

    while (($row = fgetcsv($handle, 0, ',', '"', '')) !== false) {
        $item = [];
        foreach ($headers as $index => $key) {
            $item[$key] = trim((string) ($row[$index] ?? ''));
        }

        if (($item['published'] ?? '1') === '0' || ($item['title'] ?? '') === '') {
            continue;
        }

        $item['id'] = ($item['id'] ?? '') !== '' ? $item['id'] : (string) $position;
        $item['sort_order'] = ($item['sort_order'] ?? '') !== '' ? $item['sort_order'] : (string) $position;
        $blogs[] = normalize_blog_post($item);
        $position++;
    }

    fclose($handle);

    usort($blogs, fn($a, $b) => (int) ($a['sort_order'] ?? 0) <=> (int) ($b['sort_order'] ?? 0));

    return $blogs;
}

function normalize_blog_key(string $key): string
{
    $key = strtolower(trim($key));
    $key = str_replace([' ', '-', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', '_', 'a', 'e', 'i', 'o', 'u', 'n'], $key);

    return $key;
}

function normalize_blog_post(array $item): array
{
    return [
        'id' => (string) ($item['id'] ?? ''),
        'published' => (string) ($item['published'] ?? '1'),
        'sort_order' => (int) ($item['sort_order'] ?? ($item['id'] ?? 0)),
        'category_label' => $item['category_label'] ?? ($item['category'] ?? ''),
        'title' => $item['title'] ?? '',
        'content_html' => $item['content_html'] ?? ($item['content'] ?? ''),
        'call_to_action' => $item['call_to_action'] ?? 'Agendar asesoria',
    ];
}

function blog_excerpt(?string $html, int $length = 220): string
{
    $text = trim(preg_replace('/\s+/', ' ', strip_tags($html ?? '')));

    if (mb_strlen($text) <= $length) {
        return $text;
    }

    return rtrim(mb_substr($text, 0, $length), " \t\n\r\0\x0B.,;:") . '...';
}
