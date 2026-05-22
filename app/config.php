<?php

return [
    'mail_to' => getenv('MAIL_TO') ?: 'correo-destino@example.com',
    'smtp_host' => getenv('SMTP_HOST') ?: 'smtp.gmail.com',
    'smtp_username' => getenv('SMTP_USERNAME') ?: 'correo-envio@example.com',
    'smtp_password' => getenv('SMTP_PASSWORD') ?: 'CAMBIAR_POR_PASSWORD_DE_APLICACION',
    'smtp_port' => (int) (getenv('SMTP_PORT') ?: 465),
    'from_name' => getenv('MAIL_FROM_NAME') ?: 'Notificacion Web - Airuska Ayala',
    'properties_sheet_csv_url' => getenv('PROPERTIES_SHEET_CSV_URL') ?: 'https://docs.google.com/spreadsheets/d/e/2PACX-1vRB4vSaJ3heDMaYcANp27qr91-l47xAqfpgTHom-tCOpj-tKAcP4oJ7_5w6MunWMkldF-F_5s6i6Bh2/pub?gid=0&single=true&output=csv',
    'properties_cache_seconds' => (int) (getenv('PROPERTIES_CACHE_SECONDS') ?: 120),
    'blog_sheet_csv_url' => getenv('BLOG_SHEET_CSV_URL') ?: 'https://docs.google.com/spreadsheets/d/e/2PACX-1vQ7unijF5KgjgjC5iHt7VfBqe-byFJI-rJ9B1QQRRLDYkpiaHeVavocc1WQSyXv7_lQSJ75uN58qSRs/pub?gid=0&single=true&output=csv',
    'blog_cache_seconds' => (int) (getenv('BLOG_CACHE_SECONDS') ?: 120),
];
