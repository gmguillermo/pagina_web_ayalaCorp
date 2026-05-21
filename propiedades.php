<?php
require __DIR__ . '/app/properties.php';
$config = require __DIR__ . '/app/config.php';

$available_properties = load_properties($config);
$property_types = array_values(array_unique(array_filter(array_column($available_properties, 'type'))));
$property_communes = array_values(array_unique(array_filter(array_column($available_properties, 'commune'))));
sort($property_types);
sort($property_communes);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Propiedades Disponibles | Airuska Ayala</title>
    <meta name="description" content="Portal de propiedades disponibles de Airuska Ayala. Filtra por comuna, tipo y presupuesto.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <header class="sticky top-0 z-50 bg-white shadow-sm">
        <nav class="container mx-auto px-6 py-5 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold text-gray-900">Airuska Ayala</a>
            <ul class="hidden md:flex space-x-8 text-gray-700 font-medium">
                <li><a href="index.php#hero" class="hover:text-emerald-600 transition">Inicio</a></li>
                <li><a href="index.php#sobre-mi" class="hover:text-emerald-600 transition">Sobre Mí</a></li>
                <li><a href="index.php#servicios" class="hover:text-emerald-600 transition">Servicios</a></li>
                <li><a href="propiedades.php" class="text-emerald-600">Propiedades</a></li>
                <li><a href="blog.php" class="hover:text-emerald-600 transition">Blog</a></li>
                <li><a href="index.php#contacto" class="hover:text-emerald-600 transition">Contacto</a></li>
            </ul>
            <a href="index.php#contacto" class="hidden sm:inline-flex bg-black text-white px-5 py-3 rounded-xl font-bold hover:bg-emerald-600 transition">Agenda</a>
        </nav>
    </header>

    <main>
        <section class="relative bg-gray-900 text-white overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(16,185,129,0.18),transparent_35%),linear-gradient(135deg,#111827_0%,#030712_100%)]"></div>
            <div class="container mx-auto px-6 py-20 md:py-24 relative">
                <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-[1.1fr_0.9fr] gap-12 items-end">
                    <div>
                        <span class="inline-block bg-emerald-500/20 text-emerald-300 px-4 py-1 rounded-full text-sm font-bold mb-6 border border-emerald-500/30 uppercase tracking-widest">Propiedades disponibles</span>
                        <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-6">Encuentra oportunidades para vivir, invertir o emprender.</h1>
                        <p class="text-xl text-gray-300 leading-relaxed max-w-3xl">Filtra por comuna, tipo de propiedad y presupuesto. Las publicaciones se pueden administrar desde Google Sheets.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div class="bg-white/10 border border-white/10 p-6 rounded-2xl">
                            <strong id="property-count" class="block text-4xl font-extrabold text-emerald-300"><?= count($available_properties) ?></strong>
                            <span class="text-xs uppercase tracking-widest text-gray-300">Disponibles</span>
                        </div>
                        <div class="bg-white/10 border border-white/10 p-6 rounded-2xl">
                            <strong class="block text-4xl font-extrabold text-emerald-300"><?= count($property_communes) ?></strong>
                            <span class="text-xs uppercase tracking-widest text-gray-300">Comunas</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-12 bg-white border-b border-gray-100">
            <div class="container mx-auto px-6 max-w-7xl">
                <div class="bg-gray-50 border border-gray-100 rounded-[2rem] p-5 md:p-6 shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <input id="property-search" type="search" placeholder="Buscar por comuna o palabra clave" class="lg:col-span-2 w-full p-4 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                        <select id="property-type" class="w-full p-4 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                            <option value="">Tipo</option>
                            <?php foreach ($property_types as $type): ?>
                                <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($type) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select id="property-commune" class="w-full p-4 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                            <option value="">Comuna</option>
                            <?php foreach ($property_communes as $commune): ?>
                                <option value="<?= htmlspecialchars($commune) ?>"><?= htmlspecialchars($commune) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select id="property-budget" class="w-full p-4 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                            <option value="">Presupuesto</option>
                            <option value="2500">Hasta UF 2.500</option>
                            <option value="3500">Hasta UF 3.500</option>
                            <option value="5000">Hasta UF 5.000</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-6 max-w-7xl">
                <div id="property-grid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-7">
                    <?php foreach ($available_properties as $property): ?>
                        <article
                            class="property-card bg-white border border-gray-100 rounded-[2rem] overflow-hidden shadow-md hover:shadow-xl transition flex flex-col"
                            data-title="<?= htmlspecialchars(mb_strtolower($property['title'] . ' ' . $property['commune'] . ' ' . $property['description'])) ?>"
                            data-type="<?= htmlspecialchars($property['type']) ?>"
                            data-commune="<?= htmlspecialchars($property['commune']) ?>"
                            data-price="<?= htmlspecialchars((string) $property['price_value']) ?>"
                        >
                            <div class="relative aspect-video bg-gray-200 overflow-hidden">
                                <?php if (!empty($property['video_embed_url'])): ?>
                                    <iframe
                                        class="w-full h-full"
                                        src="<?= htmlspecialchars($property['video_embed_url']) ?>"
                                        title="Video de <?= htmlspecialchars($property['title']) ?>"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        allowfullscreen>
                                    </iframe>
                                <?php else: ?>
                                    <img src="<?= htmlspecialchars($property['image']) ?>" alt="<?= htmlspecialchars($property['title']) ?>" class="w-full h-full object-cover hover:scale-105 transition duration-500">
                                <?php endif; ?>
                                <span class="absolute top-4 left-4 bg-white/95 text-gray-900 px-4 py-2 rounded-full text-xs font-extrabold uppercase tracking-widest"><?= htmlspecialchars($property['operation']) ?></span>
                                <span class="absolute top-4 right-4 bg-emerald-500 text-white px-4 py-2 rounded-full text-xs font-extrabold"><?= htmlspecialchars($property['status']) ?></span>
                            </div>
                            <div class="p-6 flex flex-col flex-1">
                                <span class="text-emerald-600 font-bold text-xs uppercase tracking-widest"><?= htmlspecialchars($property['commune']) ?></span>
                                <h2 class="text-xl font-extrabold leading-tight mt-2 mb-4"><?= htmlspecialchars($property['title']) ?></h2>
                                <p class="text-2xl font-extrabold text-gray-900 mb-4"><?= htmlspecialchars($property['price_label']) ?></p>
                                <div class="grid grid-cols-3 gap-3 text-center text-sm mb-5">
                                    <div class="bg-gray-50 rounded-2xl p-3">
                                        <strong class="block"><?= htmlspecialchars((string) $property['bedrooms']) ?></strong>
                                        <span class="text-gray-500">Dorm.</span>
                                    </div>
                                    <div class="bg-gray-50 rounded-2xl p-3">
                                        <strong class="block"><?= htmlspecialchars((string) $property['bathrooms']) ?></strong>
                                        <span class="text-gray-500">Baños</span>
                                    </div>
                                    <div class="bg-gray-50 rounded-2xl p-3">
                                        <strong class="block"><?= htmlspecialchars($property['area']) ?></strong>
                                        <span class="text-gray-500">Sup.</span>
                                    </div>
                                </div>
                                <p class="text-gray-600 text-sm leading-relaxed mb-6 flex-1"><?= htmlspecialchars($property['description']) ?></p>
                                <?php if (!empty($property['video_url'])): ?>
                                    <a href="<?= htmlspecialchars($property['video_url']) ?>" target="_blank" rel="noopener" class="mb-3 w-full inline-flex justify-center border border-emerald-200 text-emerald-700 px-5 py-3 rounded-2xl font-bold hover:bg-emerald-50 transition">Ver en YouTube</a>
                                <?php endif; ?>
                                <a href="index.php#contacto" class="w-full inline-flex justify-center bg-black text-white px-5 py-4 rounded-2xl font-bold hover:bg-emerald-600 transition">Consultar propiedad</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div id="property-empty" class="hidden text-center bg-white border border-dashed border-gray-300 rounded-[2rem] p-10 mt-8">
                    <h3 class="text-2xl font-bold mb-2">No hay resultados para esos filtros</h3>
                    <p class="text-gray-600 mb-6">Prueba con otra comuna o solicita una busqueda personalizada.</p>
                    <a href="index.php#contacto" class="inline-flex bg-black text-white px-7 py-4 rounded-2xl font-bold hover:bg-emerald-600 transition">Pedir busqueda personalizada</a>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-gray-900 text-white py-10">
        <div class="container mx-auto px-6 max-w-7xl flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
            <div>
                <p class="text-xl font-bold">Airuska Ayala</p>
                <p class="text-gray-400 text-sm mt-1">Propiedades conectadas a Google Sheets.</p>
            </div>
            <a href="index.php#contacto" class="text-emerald-300 font-bold hover:text-emerald-200 transition">Solicitar asesoria</a>
        </div>
    </footer>

    <script>
        const propertySearch = document.getElementById('property-search');
        const propertyType = document.getElementById('property-type');
        const propertyCommune = document.getElementById('property-commune');
        const propertyBudget = document.getElementById('property-budget');
        const propertyCards = Array.from(document.querySelectorAll('.property-card'));
        const propertyCount = document.getElementById('property-count');
        const propertyEmpty = document.getElementById('property-empty');

        function filterProperties() {
            const search = propertySearch.value.trim().toLowerCase();
            const type = propertyType.value;
            const commune = propertyCommune.value;
            const budget = propertyBudget.value ? Number(propertyBudget.value) : null;
            let visibleCount = 0;

            propertyCards.forEach(card => {
                const matchesSearch = !search || card.dataset.title.includes(search);
                const matchesType = !type || card.dataset.type === type;
                const matchesCommune = !commune || card.dataset.commune === commune;
                const matchesBudget = !budget || Number(card.dataset.price) <= budget;
                const isVisible = matchesSearch && matchesType && matchesCommune && matchesBudget;

                card.classList.toggle('hidden', !isVisible);
                if (isVisible) visibleCount++;
            });

            propertyCount.textContent = visibleCount;
            propertyEmpty.classList.toggle('hidden', visibleCount !== 0);
        }

        [propertySearch, propertyType, propertyCommune, propertyBudget].forEach(control => {
            control.addEventListener('input', filterProperties);
            control.addEventListener('change', filterProperties);
        });
    </script>
</body>
</html>
