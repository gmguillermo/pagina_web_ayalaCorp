<?php

function default_properties(): array
{
    return [
        [
            'id' => 'demo-1',
            'title' => 'Departamento nuevo con entrega inmediata',
            'type' => 'Departamento',
            'operation' => 'Venta',
            'commune' => 'La Cisterna',
            'price_label' => 'UF 2.450',
            'price_value' => 2450,
            'bedrooms' => 2,
            'bathrooms' => 2,
            'area' => '52 m2',
            'status' => 'Entrega inmediata',
            'image' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=900&q=80',
            'video_url' => '',
            'video_embed_url' => '',
            'description' => 'Ideal para primera vivienda o inversion con alta conectividad a Metro y servicios.',
        ],
        [
            'id' => 'demo-2',
            'title' => 'Proyecto en verde para inversion',
            'type' => 'Departamento',
            'operation' => 'Venta',
            'commune' => 'Santiago Centro',
            'price_label' => 'Desde UF 2.100',
            'price_value' => 2100,
            'bedrooms' => 1,
            'bathrooms' => 1,
            'area' => '38 m2',
            'status' => 'En verde',
            'image' => 'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=900&q=80',
            'video_url' => '',
            'video_embed_url' => '',
            'description' => 'Oportunidad con pie en cuotas y buen potencial de arriendo mensual.',
        ],
        [
            'id' => 'demo-3',
            'title' => 'Departamento familiar cercano a servicios',
            'type' => 'Departamento',
            'operation' => 'Venta',
            'commune' => 'Ñuñoa',
            'price_label' => 'UF 4.200',
            'price_value' => 4200,
            'bedrooms' => 3,
            'bathrooms' => 2,
            'area' => '78 m2',
            'status' => 'Disponible',
            'image' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=900&q=80',
            'video_url' => '',
            'video_embed_url' => '',
            'description' => 'Buena distribucion, barrio consolidado y excelente demanda de compra y arriendo.',
        ],
        [
            'id' => 'demo-4',
            'title' => 'Local comercial para patente provisoria',
            'type' => 'Local comercial',
            'operation' => 'Arriendo',
            'commune' => 'Providencia',
            'price_label' => '$ 850.000/mes',
            'price_value' => 999999,
            'bedrooms' => 0,
            'bathrooms' => 1,
            'area' => '42 m2',
            'status' => 'Apto comercial',
            'image' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=900&q=80',
            'video_url' => '',
            'video_embed_url' => '',
            'description' => 'Espacio funcional para emprendedores que necesitan validar factibilidad municipal.',
        ],
    ];
}

function load_properties(array $config): array
{
    $csv_url = trim($config['properties_sheet_csv_url'] ?? '');
    $cache_file = dirname(__DIR__) . '/storage/cache/properties.csv';
    $cache_ttl = (int) ($config['properties_cache_seconds'] ?? 900);

    if ($csv_url !== '') {
        $csv = fetch_properties_csv($csv_url, $cache_file, $cache_ttl);
        $properties = parse_properties_csv($csv);

        if ($properties !== []) {
            return $properties;
        }
    }

    return array_map('normalize_property', default_properties());
}

function fetch_properties_csv(string $csv_url, string $cache_file, int $cache_ttl): string
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
        $csv = fetch_properties_csv_with_curl($csv_url);
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

function fetch_properties_csv_with_curl(string $csv_url): string
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

function parse_properties_csv(string $csv): array
{
    if (trim($csv) === '') {
        return [];
    }

    $handle = fopen('php://temp', 'r+');
    fwrite($handle, $csv);
    rewind($handle);

    $headers = [];
    while (($candidate_headers = fgetcsv($handle, 0, ',', '"', '')) !== false) {
        $headers = normalize_property_headers($candidate_headers);
        if (array_filter($headers, fn($header) => $header !== '') !== []) {
            break;
        }
    }

    if ($headers === [] || array_filter($headers, fn($header) => $header !== '') === []) {
        fclose($handle);
        return [];
    }

    $properties = [];

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

        if (!property_row_has_content($item)) {
            continue;
        }

        if (($item['published'] ?? '1') === '0') {
            continue;
        }

        $properties[] = normalize_property($item);
    }

    fclose($handle);

    return $properties;
}

function property_row_has_content(array $item): bool
{
    foreach (['title', 'type', 'commune', 'layout', 'video_url', 'image', 'description', 'price_value'] as $key) {
        if (trim((string) ($item[$key] ?? '')) !== '') {
            return true;
        }
    }

    return false;
}

function normalize_property_headers(array $headers): array
{
    $normalized = array_map(fn($header) => normalize_property_key((string) $header), $headers);

    if (($normalized[0] ?? '') === '' && in_array('type', $normalized, true) && in_array('commune', $normalized, true)) {
        $normalized[0] = 'operation';
    }

    if (($normalized[3] ?? '') === '' && ($normalized[1] ?? '') === 'commune' && ($normalized[2] ?? '') === 'type') {
        $normalized[3] = 'layout';
    } elseif (($normalized[3] ?? '') === '' && in_array('type', $normalized, true) && in_array('commune', $normalized, true)) {
        $normalized[3] = 'video_url';
    }

    if (($normalized[4] ?? '') === '' && ($normalized[3] ?? '') === 'layout') {
        $normalized[4] = 'video_url';
    }

    return $normalized;
}

function normalize_property_key(string $key): string
{
    $key = strtolower(trim($key));
    $key = str_replace([' ', '-', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', '_', 'a', 'e', 'i', 'o', 'u', 'n'], $key);

    $aliases = [
        'titulo' => 'title',
        'tipo' => 'type',
        'operacion' => 'operation',
        'comuna' => 'commune',
        'imagen' => 'image',
        'descripcion' => 'description',
        'banos' => 'bathrooms',
        'dormitorios' => 'bedrooms',
        'superficie' => 'area',
        'estado' => 'status',
        'valor' => 'price_value',
        'precio' => 'price_label',
        'criterio' => 'price_currency',
        'youtube' => 'video_url',
        'video' => 'video_url',
    ];

    return $aliases[$key] ?? $key;
}

function normalize_property(array $item): array
{
    $video_url = clean_url($item['video_url'] ?? ($item['youtube_url'] ?? ''));
    $operation = clean_text($item['operation'] ?? 'Venta');
    $type = clean_text($item['type'] ?? 'Departamento');
    $commune = clean_text($item['commune'] ?? '');
    $price_label = property_price_label($item);
    $layout = clean_text($item['layout'] ?? '');
    if (mb_strtolower($layout) === mb_strtolower($type)) {
        $layout = '';
    }
    $feature_source = $layout !== '' ? $layout : $type;
    $bedrooms = (int) numeric_value($item['bedrooms'] ?? property_feature_from_type($feature_source, 'D'));
    $bathrooms = (int) numeric_value($item['bathrooms'] ?? property_feature_from_type($feature_source, 'B'));
    $title = clean_text($item['title'] ?? '');

    if ($title === '') {
        $title = trim(ucfirst(strtolower($operation)) . ' ' . $type . ($layout !== '' ? ' ' . $layout : '') . ($commune !== '' ? ' en ' . $commune : ''));
    }

    return [
        'id' => clean_text($item['id'] ?? ''),
        'title' => $title,
        'type' => $type,
        'operation' => $operation,
        'commune' => $commune,
        'price_label' => $price_label,
        'price_value' => numeric_value($item['price_value'] ?? ''),
        'bedrooms' => $bedrooms,
        'bathrooms' => $bathrooms,
        'area' => clean_text($item['area'] ?? ($layout !== '' ? $layout : '')),
        'status' => clean_text($item['status'] ?? 'Disponible'),
        'image' => clean_url($item['image'] ?? '') ?: 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=900&q=80',
        'video_url' => $video_url,
        'video_embed_url' => youtube_embed_url($video_url),
        'description' => clean_text($item['description'] ?? ''),
    ];
}

function property_price_label(array $item): string
{
    $price_label = clean_text($item['price_label'] ?? '');
    if ($price_label !== '') {
        return $price_label;
    }

    $currency = clean_text($item['price_currency'] ?? '');
    $value = clean_text($item['price_value'] ?? '');

    if ($currency === '' && $value === '') {
        return '';
    }

    return trim($currency . ' ' . $value);
}

function property_feature_from_type(string $type, string $feature): int
{
    if (preg_match('/(\d+)\s*' . preg_quote($feature, '/') . '/i', $type, $matches)) {
        return (int) $matches[1];
    }

    return 0;
}

function numeric_value(string|int|float $value): float
{
    $clean = preg_replace('/\D+/', '', (string) $value);

    return $clean === '' ? 0 : (float) $clean;
}

function youtube_embed_url(string $url): string
{
    $url = clean_url($url);

    if ($url === '') {
        return '';
    }

    if (preg_match('~youtube\.com/embed/([A-Za-z0-9_-]{6,})~', $url, $matches)) {
        return 'https://www.youtube.com/embed/' . $matches[1];
    }

    if (preg_match('~youtu\.be/([A-Za-z0-9_-]{6,})~', $url, $matches)) {
        return 'https://www.youtube.com/embed/' . $matches[1];
    }

    if (preg_match('~youtube\.com/shorts/([A-Za-z0-9_-]{6,})~', $url, $matches)) {
        return 'https://www.youtube.com/embed/' . $matches[1];
    }

    $parts = parse_url($url);
    if (($parts['host'] ?? '') !== '' && str_contains($parts['host'], 'youtube.com')) {
        parse_str($parts['query'] ?? '', $query);
        if (!empty($query['v'])) {
            return 'https://www.youtube.com/embed/' . preg_replace('/[^A-Za-z0-9_-]/', '', $query['v']);
        }
    }

    return '';
}

function clean_text(string|int|float|null $value): string
{
    $text = html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = strip_tags($text);
    $text = preg_replace('/\s+/', ' ', $text);

    return trim((string) $text);
}

function clean_url(string|int|float|null $value): string
{
    $url = trim(html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8'));

    if ($url === '') {
        return '';
    }

    if (preg_match('/src=["\']([^"\']+)["\']/i', $url, $matches)) {
        $url = trim($matches[1]);
    } elseif (preg_match('/href=["\']([^"\']+)["\']/i', $url, $matches)) {
        $url = trim($matches[1]);
    } else {
        $url = strip_tags($url);
        $url = trim($url);
    }

    if (!preg_match('~^https?://~i', $url)) {
        return '';
    }

    return $url;
}
