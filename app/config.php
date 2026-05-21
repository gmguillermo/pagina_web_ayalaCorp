<?php

return [
    'mail_to' => getenv('MAIL_TO') ?: 'correo-destino@example.com',
    'smtp_host' => getenv('SMTP_HOST') ?: 'smtp.gmail.com',
    'smtp_username' => getenv('SMTP_USERNAME') ?: 'correo-envio@example.com',
    'smtp_password' => getenv('SMTP_PASSWORD') ?: 'CAMBIAR_POR_PASSWORD_DE_APLICACION',
    'smtp_port' => (int) (getenv('SMTP_PORT') ?: 465),
    'from_name' => getenv('MAIL_FROM_NAME') ?: 'Notificacion Web - Airuska Ayala',
    'properties_sheet_csv_url' => getenv('PROPERTIES_SHEET_CSV_URL') ?: '',
    'properties_cache_seconds' => (int) (getenv('PROPERTIES_CACHE_SECONDS') ?: 900),
    'blog_sheet_csv_url' => getenv('BLOG_SHEET_CSV_URL') ?: '',
    'blog_cache_seconds' => (int) (getenv('BLOG_CACHE_SECONDS') ?: 900),
];
