<?php
require __DIR__ . '/app/blogs.php';
$config = require __DIR__ . '/app/config.php';

$blogs = load_blogs($config);
$featured_blog = $blogs[0] ?? null;
$secondary_blogs = array_slice($blogs, 1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Inmobiliario | Airuska Ayala</title>
    <meta name="description" content="Consejos inmobiliarios, inversión y plusvalía en Santiago de Chile.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .article-body p { margin-bottom: 1rem; }
        .article-body ul { margin: 1rem 0 1rem 1.25rem; list-style: disc; }
        .article-body ol { margin: 1rem 0 1rem 1.25rem; list-style: decimal; }
        .article-body li { margin-bottom: 0.5rem; }
        .article-body h3 { font-size: 1.25rem; font-weight: 800; margin: 1.5rem 0 0.75rem; color: #111827; }
        details summary { list-style: none; }
        details summary::-webkit-details-marker { display: none; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <header class="sticky top-0 z-50 bg-white shadow-sm">
        <nav class="container mx-auto px-6 py-5 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold text-gray-900">Airuska Ayala</a>
            <ul class="hidden md:flex space-x-8 text-gray-700 font-medium">
                <li><a href="index.php#hero" class="hover:text-emerald-600 transition">Inicio</a></li>
                <li><a href="index.php#servicios" class="hover:text-emerald-600 transition">Servicios</a></li>
                <li><a href="propiedades.php" class="hover:text-emerald-600 transition">Propiedades</a></li>
                <li><a href="blog.php" class="text-emerald-600">Blog</a></li>
                <li><a href="index.php#contacto" class="hover:text-emerald-600 transition">Contacto</a></li>
            </ul>
            <a href="index.php#contacto" class="hidden sm:inline-flex bg-black text-white px-5 py-3 rounded-xl font-bold hover:bg-emerald-600 transition">Agenda</a>
        </nav>
    </header>

    <main>
        <section id="top" class="relative bg-gray-900 text-white overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(16,185,129,0.18),transparent_35%),linear-gradient(135deg,#111827_0%,#030712_100%)]"></div>
            <div class="container mx-auto px-6 py-20 md:py-24 relative">
                <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-[1.05fr_0.95fr] gap-12 items-end">
                    <div>
                        <span class="inline-block bg-emerald-500/20 text-emerald-300 px-4 py-1 rounded-full text-sm font-bold mb-6 border border-emerald-500/30 uppercase tracking-widest">Blog inmobiliario</span>
                        <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-6">Invierte con información, no con intuición.</h1>
                        <p class="text-xl text-gray-300 leading-relaxed max-w-3xl">Guías claras sobre compra en verde, plusvalía, financiamiento y costos reales para tomar mejores decisiones en Santiago.</p>
                    </div>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div class="bg-white/10 border border-white/10 p-5 rounded-2xl">
                            <strong class="block text-3xl font-extrabold text-emerald-300"><?= count($blogs) ?></strong>
                            <span class="text-xs uppercase tracking-widest text-gray-300">Artículos</span>
                        </div>
                        <div class="bg-white/10 border border-white/10 p-5 rounded-2xl">
                            <strong class="block text-3xl font-extrabold text-emerald-300">20</strong>
                            <span class="text-xs uppercase tracking-widest text-gray-300">Min gratis</span>
                        </div>
                        <div class="bg-white/10 border border-white/10 p-5 rounded-2xl">
                            <strong class="block text-3xl font-extrabold text-emerald-300">RM</strong>
                            <span class="text-xs uppercase tracking-widest text-gray-300">Santiago</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 bg-white">
            <div class="container mx-auto px-6 max-w-6xl">
                <?php if ($featured_blog): ?>
                    <article class="grid grid-cols-1 lg:grid-cols-[0.9fr_1.1fr] gap-0 overflow-hidden rounded-[2rem] shadow-xl border border-gray-100 bg-gray-50">
                        <div class="bg-gray-900 text-white p-8 md:p-10 flex flex-col justify-between">
                            <div>
                                <span class="inline-block bg-emerald-500/20 text-emerald-300 px-4 py-1 rounded-full text-xs font-bold mb-6 border border-emerald-500/30 uppercase tracking-widest">Artículo destacado</span>
                                <h2 class="text-3xl md:text-4xl font-extrabold leading-tight mb-6"><?= htmlspecialchars($featured_blog['title'] ?? '') ?></h2>
                                <p class="text-gray-300 leading-relaxed">Una lectura base para entender mejor el momento de compra, comparar escenarios y decidir con más estrategia.</p>
                            </div>
                            <a href="index.php#contacto" class="mt-8 inline-flex w-fit bg-emerald-500 text-white px-7 py-4 rounded-2xl font-bold hover:bg-emerald-600 transition">
                                <?= htmlspecialchars($featured_blog['call_to_action'] ?? 'Agendar asesoria') ?>
                            </a>
                        </div>
                        <div class="p-8 md:p-10">
                            <span class="text-emerald-600 font-bold text-xs uppercase tracking-widest"><?= htmlspecialchars($featured_blog['category_label'] ?? '') ?></span>
                            <div class="article-body mt-5 text-gray-700 leading-relaxed">
                                <?= $featured_blog['content_html'] ?? '' ?>
                            </div>
                        </div>
                    </article>
                <?php endif; ?>
            </div>
        </section>

        <section class="py-20 bg-gray-50">
            <div class="container mx-auto px-6 max-w-6xl">
                <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12">
                    <div>
                        <span class="text-emerald-600 font-bold text-xs uppercase tracking-widest">Más lecturas</span>
                        <h2 class="text-4xl md:text-5xl font-extrabold mt-3 leading-tight">Consejos inmobiliarios</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed max-w-xl">Contenido breve y accionable para revisar oportunidades, costos y financiamiento antes de avanzar.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-10 items-start">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <?php foreach ($secondary_blogs as $index => $blog): ?>
                            <article id="articulo-<?= htmlspecialchars($blog['id']) ?>" class="bg-white rounded-[2rem] shadow-md border border-gray-100 p-8 hover:shadow-xl transition">
                                <div class="flex items-start justify-between gap-5 mb-5">
                                    <span class="text-emerald-600 font-bold text-xs uppercase tracking-widest"><?= htmlspecialchars($blog['category_label'] ?? '') ?></span>
                                    <span class="text-gray-300 font-extrabold text-3xl"><?= str_pad((string) ($index + 2), 2, '0', STR_PAD_LEFT) ?></span>
                                </div>
                                <h3 class="text-2xl font-extrabold leading-tight mb-5"><?= htmlspecialchars($blog['title'] ?? '') ?></h3>
                                <p class="text-gray-600 leading-relaxed text-sm mb-6"><?= htmlspecialchars(blog_excerpt($blog['content_html'] ?? '')) ?></p>
                                <details class="group border-t border-gray-100 pt-5">
                                    <summary class="cursor-pointer font-bold text-emerald-600 hover:text-emerald-700 transition">
                                        <span class="group-open:hidden">Leer artículo completo</span>
                                        <span class="hidden group-open:inline">Ocultar artículo</span>
                                    </summary>
                                    <div class="article-body text-gray-700 leading-relaxed text-sm mt-5">
                                        <?= $blog['content_html'] ?? '' ?>
                                    </div>
                                </details>
                                <a href="index.php#contacto" class="mt-7 inline-flex bg-black text-white px-6 py-3 rounded-xl font-bold hover:bg-emerald-600 transition">
                                    <?= htmlspecialchars($blog['call_to_action'] ?? 'Agendar asesoria') ?>
                                </a>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <aside class="bg-white rounded-[2rem] border border-gray-100 shadow-md p-7 sticky top-28">
                        <h3 class="text-xl font-extrabold mb-5">Temas del blog</h3>
                        <div class="space-y-4">
                            <?php foreach ($blogs as $blog): ?>
                                <a href="<?= $featured_blog && $blog['id'] === $featured_blog['id'] ? '#top' : '#articulo-' . htmlspecialchars($blog['id']) ?>" class="block border-l-4 border-emerald-500 pl-4 py-1 hover:text-emerald-600 transition">
                                    <span class="block text-xs font-bold uppercase tracking-widest text-emerald-600"><?= htmlspecialchars($blog['category_label'] ?? '') ?></span>
                                    <span class="block text-sm font-semibold leading-snug mt-1"><?= htmlspecialchars($blog['title'] ?? '') ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-8 bg-emerald-50 border border-emerald-100 rounded-2xl p-5">
                            <p class="font-bold text-emerald-900 mb-2">¿Quieres revisar tu caso?</p>
                            <p class="text-sm text-emerald-800 leading-relaxed mb-4">Agenda una precalificación para aterrizar presupuesto, zonas y próximos pasos.</p>
                            <a href="index.php#contacto" class="inline-flex w-full justify-center bg-emerald-600 text-white px-5 py-3 rounded-xl font-bold hover:bg-emerald-700 transition">Precalificar</a>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-gray-900 text-white py-10">
        <div class="container mx-auto px-6 max-w-6xl flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
            <div>
                <p class="text-xl font-bold">Airuska Ayala</p>
                <p class="text-gray-400 text-sm mt-1">Corredora y gestora de proyectos inmobiliarios en Santiago.</p>
            </div>
            <a href="index.php" class="text-emerald-300 font-bold hover:text-emerald-200 transition">Volver al inicio</a>
        </div>
    </footer>
</body>
</html>
