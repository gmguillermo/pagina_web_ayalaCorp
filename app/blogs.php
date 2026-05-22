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

    if ((!is_string($csv) || trim($csv) === '') && function_exists('curl_init')) {
        $csv = fetch_blog_csv_with_curl($csv_url);
    }

    if (is_string($csv) && trim($csv) !== '') {
        file_put_contents($cache_file, $csv);
        return $csv;
    }

    if (is_file($cache_file)) {
        return (string) file_get_contents($cache_file);
    }

    return '';
}

function fetch_blog_csv_with_curl(string $csv_url): string
{
    $curl = curl_init($csv_url);
    if ($curl === false) {
        return '';
    }

    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_TIMEOUT => 12,
        CURLOPT_USERAGENT => 'AiruskaAyalaWeb/1.0',
    ]);

    $csv = curl_exec($curl);
    $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl);

    if ($status >= 400 || !is_string($csv)) {
        return '';
    }

    return $csv;
}

function parse_blog_csv(string $csv): array
{
    if (trim($csv) === '') {
        return [];
    }

    $handle = fopen('php://temp', 'r+');
    fwrite($handle, $csv);
    rewind($handle);

    $headers = [];
    while (($candidate_headers = fgetcsv($handle, 0, ',', '"', '')) !== false) {
        $headers = normalize_blog_headers($candidate_headers);
        if (array_filter($headers, fn($header) => $header !== '') !== []) {
            break;
        }
    }

    if ($headers === [] || array_filter($headers, fn($header) => $header !== '') === []) {
        fclose($handle);
        return [];
    }

    $blogs = [];
    $position = 1;

    while (($row = fgetcsv($handle, 0, ',', '"', '')) !== false) {
        $item = [];
        foreach ($headers as $index => $key) {
            if ($key === '') {
                continue;
            }

            $item[$key] = trim((string) ($row[$index] ?? ''));
        }

        if (array_filter($item, fn($value) => trim((string) $value) !== '') === []) {
            continue;
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

function normalize_blog_headers(array $headers): array
{
    $normalized = array_map(fn($header) => normalize_blog_key((string) $header), $headers);

    if (($normalized[0] ?? '') === '' && in_array('category_label', $normalized, true) && in_array('title', $normalized, true)) {
        $normalized[0] = 'id';
    }

    if (($normalized[5] ?? null) === '' && in_array('call_to_action', $normalized, true)) {
        $normalized[5] = 'cta_target';
    }

    return $normalized;
}

function normalize_blog_key(string $key): string
{
    $key = strtolower(trim($key));
    $key = str_replace([' ', '-', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', '_', 'a', 'e', 'i', 'o', 'u', 'n'], $key);

    $aliases = [
        'etiqueta' => 'category_label',
        'categoria' => 'category_label',
        'titulo' => 'title',
        'desarrollo' => 'content_html',
        'contenido' => 'content_html',
        'boton' => 'call_to_action',
        'cta' => 'call_to_action',
        'orden' => 'sort_order',
    ];

    return $aliases[$key] ?? $key;
}

function normalize_blog_post(array $item): array
{
    $raw_id = clean_blog_text((string) ($item['id'] ?? ''));
    $numeric_id = numeric_blog_value($raw_id);
    $id = $numeric_id > 0 ? (string) (int) $numeric_id : $raw_id;

    return [
        'id' => $id,
        'published' => clean_blog_text((string) ($item['published'] ?? '1')),
        'sort_order' => (int) numeric_blog_value((string) ($item['sort_order'] ?? $id)),
        'category_label' => clean_blog_text($item['category_label'] ?? ($item['category'] ?? '')),
        'title' => clean_blog_text($item['title'] ?? ''),
        'content_html' => normalize_blog_content($item['content_html'] ?? ($item['content'] ?? '')),
        'call_to_action' => clean_blog_text($item['call_to_action'] ?? 'Agendar asesoria'),
    ];
}

function normalize_blog_content(string $content): string
{
    $content = trim($content);

    if ($content === '') {
        return '';
    }

    if ($content !== strip_tags($content)) {
        return $content;
    }

    $blocks = preg_split('/\R{2,}/', $content) ?: [];
    $html = [];

    foreach ($blocks as $block) {
        $lines = array_values(array_filter(array_map('trim', preg_split('/\R/', trim($block)) ?: []), fn($line) => $line !== ''));

        if ($lines === []) {
            continue;
        }

        if (count($lines) > 1 && every_blog_line_is_list_item($lines)) {
            $html[] = '<ul><li>' . implode('</li><li>', array_map('escape_blog_list_item', $lines)) . '</li></ul>';
            continue;
        }

        $html[] = '<p>' . nl2br(htmlspecialchars(implode("\n", $lines), ENT_QUOTES | ENT_HTML5, 'UTF-8'), false) . '</p>';
    }

    return implode('', $html);
}

function every_blog_line_is_list_item(array $lines): bool
{
    foreach ($lines as $line) {
        if (!preg_match('/^(\d+[\.)]\s+|[-*]\s+|[A-ZÁÉÍÓÚÑ][^:]{1,60}:\s+)/u', $line)) {
            return false;
        }
    }

    return true;
}

function escape_blog_list_item(string $line): string
{
    $line = preg_replace('/^(\d+[\.)]\s+|[-*]\s+)/u', '', $line);

    return htmlspecialchars((string) $line, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function clean_blog_text(string|int|float|null $value): string
{
    $text = html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = strip_tags($text);
    $text = preg_replace('/\s+/', ' ', $text);

    return trim((string) $text);
}

function numeric_blog_value(string|int|float $value): float
{
    $clean = preg_replace('/\D+/', '', (string) $value);

    return $clean === '' ? 0 : (float) $clean;
}

function blog_excerpt(?string $html, int $length = 220): string
{
    $text = trim(preg_replace('/\s+/', ' ', strip_tags($html ?? '')));

    if (mb_strlen($text) <= $length) {
        return $text;
    }

    return rtrim(mb_substr($text, 0, $length), " \t\n\r\0\x0B.,;:") . '...';
}
