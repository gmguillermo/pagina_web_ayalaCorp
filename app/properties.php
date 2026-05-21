<?php

function default_properties(): array
{
    return [
        [
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

    return default_properties();
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

    if (is_string($csv) && trim($csv) !== '') {
        file_put_contents($cache_file, $csv);
        return $csv;
    }

    if (is_file($cache_file)) {
        return (string) file_get_contents($cache_file);
    }

    return '';
}

function parse_properties_csv(string $csv): array
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

    $headers = array_map(fn($header) => normalize_property_key((string) $header), $headers);
    $properties = [];

    while (($row = fgetcsv($handle, 0, ',', '"', '')) !== false) {
        $item = [];
        foreach ($headers as $index => $key) {
            $item[$key] = trim((string) ($row[$index] ?? ''));
        }

        if (($item['published'] ?? '1') === '0' || ($item['title'] ?? '') === '') {
            continue;
        }

        $properties[] = normalize_property($item);
    }

    fclose($handle);

    return $properties;
}

function normalize_property_key(string $key): string
{
    $key = strtolower(trim($key));
    $key = str_replace([' ', '-', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', '_', 'a', 'e', 'i', 'o', 'u', 'n'], $key);

    return $key;
}

function normalize_property(array $item): array
{
    $video_url = $item['video_url'] ?? ($item['youtube_url'] ?? '');

    return [
        'title' => $item['title'] ?? '',
        'type' => $item['type'] ?? 'Departamento',
        'operation' => $item['operation'] ?? 'Venta',
        'commune' => $item['commune'] ?? '',
        'price_label' => $item['price_label'] ?? '',
        'price_value' => numeric_value($item['price_value'] ?? ''),
        'bedrooms' => (int) numeric_value($item['bedrooms'] ?? 0),
        'bathrooms' => (int) numeric_value($item['bathrooms'] ?? 0),
        'area' => $item['area'] ?? '',
        'status' => $item['status'] ?? 'Disponible',
        'image' => $item['image'] ?: 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=900&q=80',
        'video_url' => $video_url,
        'video_embed_url' => youtube_embed_url($video_url),
        'description' => $item['description'] ?? '',
    ];
}

function numeric_value(string|int|float $value): float
{
    $clean = preg_replace('/[^0-9.]/', '', (string) $value);

    return $clean === '' ? 0 : (float) $clean;
}

function youtube_embed_url(string $url): string
{
    $url = trim($url);

    if ($url === '') {
        return '';
    }

    if (preg_match('~youtube\.com/embed/([A-Za-z0-9_-]{6,})~', $url, $matches)) {
        return 'https://www.youtube.com/embed/' . $matches[1];
    }

    if (preg_match('~youtu\.be/([A-Za-z0-9_-]{6,})~', $url, $matches)) {
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
