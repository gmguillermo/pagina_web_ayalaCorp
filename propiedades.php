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
        <nav class="container mx-auto px-6 py-5 flex items-center justify-between">
            <a href="index.php" class="text-2xl font-bold text-gray-900">Airuska Ayala</a>
            <div class="flex items-center gap-4">
                <button id="mobile-menu-open" type="button" class="md:hidden inline-flex h-11 w-11 items-center justify-center rounded-full border border-gray-200 text-gray-700 transition hover:bg-gray-100" aria-label="Abrir menú" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <ul class="hidden md:flex space-x-8 text-gray-700 font-medium">
                    <li><a href="index.php#hero" class="hover:text-emerald-600 transition">Inicio</a></li>
                    <li><a href="index.php#sobre-mi" class="hover:text-emerald-600 transition">Sobre Mí</a></li>
                    <li><a href="index.php#servicios" class="hover:text-emerald-600 transition">Servicios</a></li>
                    <li><a href="propiedades.php" class="text-emerald-600">Propiedades</a></li>
                    <li><a href="blog.php" class="hover:text-emerald-600 transition">Blog</a></li>
                    <li><a href="index.php#contacto" class="hover:text-emerald-600 transition">Contacto</a></li>
                </ul>
                <a href="index.php#contacto" class="hidden sm:inline-flex bg-black text-white px-5 py-3 rounded-xl font-bold hover:bg-emerald-600 transition">Agenda</a>
            </div>
        </nav>
        <div id="mobile-navigation" class="md:hidden hidden border-t border-gray-200 bg-white">
            <div class="container mx-auto px-6 py-4 space-y-3">
                <a href="index.php#hero" class="block rounded-2xl px-4 py-3 text-gray-900 font-medium hover:bg-gray-100">Inicio</a>
                <a href="index.php#sobre-mi" class="block rounded-2xl px-4 py-3 text-gray-900 font-medium hover:bg-gray-100">Sobre Mí</a>
                <a href="index.php#servicios" class="block rounded-2xl px-4 py-3 text-gray-900 font-medium hover:bg-gray-100">Servicios</a>
                <a href="propiedades.php" class="block rounded-2xl px-4 py-3 text-gray-900 font-medium hover:bg-gray-100">Propiedades</a>
                <a href="blog.php" class="block rounded-2xl px-4 py-3 text-gray-900 font-medium hover:bg-gray-100">Blog</a>
                <a href="index.php#contacto" class="block w-full text-center bg-black text-white px-4 py-3 rounded-2xl font-bold hover:bg-emerald-600 transition">Agenda</a>
            </div>
        </div>
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
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-center">
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
                            data-id="<?= htmlspecialchars((string) ($property['id'] ?? ''), ENT_QUOTES) ?>"
                            data-search="<?= htmlspecialchars(mb_strtolower(($property['title'] ?? '') . ' ' . ($property['commune'] ?? '') . ' ' . ($property['description'] ?? '')), ENT_QUOTES) ?>"
                            data-title="<?= htmlspecialchars($property['title'] ?? '', ENT_QUOTES) ?>"
                            data-commune="<?= htmlspecialchars($property['commune'] ?? '', ENT_QUOTES) ?>"
                            data-description="<?= htmlspecialchars($property['description'] ?? '', ENT_QUOTES) ?>"
                            data-image="<?= htmlspecialchars($property['image'] ?? '', ENT_QUOTES) ?>"
                            data-price-label="<?= htmlspecialchars($property['price_label'] ?? '', ENT_QUOTES) ?>"
                            data-operation="<?= htmlspecialchars($property['operation'] ?? '', ENT_QUOTES) ?>"
                            data-status="<?= htmlspecialchars($property['status'] ?? '', ENT_QUOTES) ?>"
                            data-bedrooms="<?= htmlspecialchars((string) ($property['bedrooms'] ?? 0), ENT_QUOTES) ?>"
                            data-bathrooms="<?= htmlspecialchars((string) ($property['bathrooms'] ?? 0), ENT_QUOTES) ?>"
                            data-video-url="<?= htmlspecialchars($property['video_url'] ?? '', ENT_QUOTES) ?>"
                            data-video-embed="<?= htmlspecialchars($property['video_embed_url'] ?? '', ENT_QUOTES) ?>"
                            data-price="<?= htmlspecialchars((string) ($property['price_value'] ?? 0), ENT_QUOTES) ?>"
                            data-type="<?= htmlspecialchars($property['type'] ?? '', ENT_QUOTES) ?>"
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
                                <button type="button" class="consult-property-btn w-full inline-flex justify-center bg-black text-white px-5 py-4 rounded-2xl font-bold hover:bg-emerald-600 transition">Consultar propiedad</button>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div id="property-preview-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4 backdrop-blur-sm overflow-y-auto">
                    <div class="relative w-full max-w-5xl rounded-[2rem] bg-white shadow-2xl overflow-hidden">
                        <button id="property-preview-close" type="button" class="absolute right-4 top-4 z-10 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white text-gray-900 border border-gray-200 shadow-sm hover:bg-gray-100" aria-label="Cerrar vista previa">
                            ✕
                        </button>
                        <div class="grid grid-cols-1 lg:grid-cols-[1.1fr_0.9fr] gap-6 p-6 lg:p-8">
                            <div class="space-y-4">
                                <div id="property-preview-media" class="aspect-video w-full overflow-hidden rounded-[1.75rem] bg-gray-950"></div>
                                <div class="flex flex-wrap gap-3">
                                    <span id="property-preview-operation" class="inline-flex items-center rounded-full bg-emerald-50 px-4 py-2 text-xs font-bold uppercase tracking-widest text-emerald-700"></span>
                                    <span id="property-preview-status" class="inline-flex items-center rounded-full bg-emerald-600 px-4 py-2 text-xs font-bold uppercase tracking-widest text-white"></span>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <h2 id="property-preview-title" class="text-3xl font-extrabold text-gray-900"></h2>
                                    <p id="property-preview-commune" class="mt-3 text-sm uppercase tracking-widest text-emerald-700 font-bold"></p>
                                </div>
                                <div class="rounded-[1.5rem] border border-gray-100 bg-gray-50 p-5">
                                    <p id="property-preview-price" class="text-3xl font-extrabold text-gray-900"></p>
                                    <p id="property-preview-description" class="mt-4 text-gray-600 leading-relaxed"></p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="rounded-[1.5rem] border border-gray-100 bg-white p-5 text-center">
                                        <p class="text-xs uppercase tracking-widest text-gray-500">Dormitorios</p>
                                        <p id="property-preview-bedrooms" class="mt-3 text-xl font-bold text-gray-900"></p>
                                    </div>
                                    <div class="rounded-[1.5rem] border border-gray-100 bg-white p-5 text-center">
                                        <p class="text-xs uppercase tracking-widest text-gray-500">Baños</p>
                                        <p id="property-preview-bathrooms" class="mt-3 text-xl font-bold text-gray-900"></p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <a id="property-preview-video-link" href="#" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-2xl border border-emerald-600 px-4 py-4 text-sm font-bold text-emerald-700 hover:bg-emerald-50 transition">Ver video completo</a>
                                    <a id="property-preview-whatsapp" href="#" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-2xl bg-black text-white px-4 py-4 text-sm font-bold hover:bg-emerald-600 transition">Contactar por WhatsApp</a>
                                </div>
                            </div>
                        </div>
                    </div>
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
                const matchesSearch = !search || card.dataset.search.includes(search);
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

        function formatWhatsAppMessage(property) {
            return encodeURIComponent(
                `Hola, quiero consultar esta propiedad:\n` +
                `• ${property.title} (${property.commune})\n` +
                `• Precio: ${property.priceLabel}\n` +
                (property.videoUrl ? `• Video: ${property.videoUrl}\n` : '') +
                `Gracias.`
            );
        }

        function openPropertyPreview(card) {
            const modal = document.getElementById('property-preview-modal');
            const previewMedia = document.getElementById('property-preview-media');
            const title = card.dataset.title || '';
            const commune = card.dataset.commune || '';
            const description = card.dataset.description || '';
            const priceLabel = card.dataset.priceLabel || '';
            const bedrooms = card.dataset.bedrooms || card.dataset.bedrooms || '—';
            const bathrooms = card.dataset.bathrooms || '—';
            const operation = card.dataset.operation || '';
            const status = card.dataset.status || '';
            const image = card.dataset.image || '';
            const videoEmbed = card.dataset.videoEmbed || '';
            const videoUrl = card.dataset.videoUrl || '';

            document.getElementById('property-preview-title').textContent = title;
            document.getElementById('property-preview-commune').textContent = commune;
            document.getElementById('property-preview-description').textContent = description;
            document.getElementById('property-preview-price').textContent = priceLabel;
            document.getElementById('property-preview-bedrooms').textContent = bedrooms;
            document.getElementById('property-preview-bathrooms').textContent = bathrooms;
            document.getElementById('property-preview-operation').textContent = operation;
            document.getElementById('property-preview-status').textContent = status;

            const videoLink = document.getElementById('property-preview-video-link');
            if (videoUrl) {
                videoLink.href = videoUrl;
                videoLink.classList.remove('hidden');
            } else {
                videoLink.classList.add('hidden');
            }

            const whatsappLink = document.getElementById('property-preview-whatsapp');
            whatsappLink.href = `https://wa.me/56940174702?text=${formatWhatsAppMessage({ title, commune, priceLabel, videoUrl })}`;

            previewMedia.textContent = '';
            if (videoEmbed) {
                const iframe = document.createElement('iframe');
                iframe.className = 'w-full h-full';
                iframe.src = videoEmbed;
                iframe.title = `Video de ${title}`;
                iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
                iframe.allowFullscreen = true;
                previewMedia.appendChild(iframe);
            } else if (videoUrl) {
                const wrapper = document.createElement('div');
                wrapper.className = 'relative h-full w-full overflow-hidden rounded-[1.75rem] bg-black';
                const img = document.createElement('img');
                img.src = image;
                img.alt = title;
                img.className = 'h-full w-full object-cover opacity-70';
                const link = document.createElement('a');
                link.href = videoUrl;
                link.target = '_blank';
                link.rel = 'noopener';
                link.className = 'absolute inset-0 flex items-center justify-center text-white text-lg font-bold bg-black/40 hover:bg-black/50 transition';
                link.textContent = 'Abrir video';
                wrapper.appendChild(img);
                wrapper.appendChild(link);
                previewMedia.appendChild(wrapper);
            } else {
                const img = document.createElement('img');
                img.src = image;
                img.alt = title;
                img.className = 'h-full w-full object-cover';
                previewMedia.appendChild(img);
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePropertyPreview() {
            const modal = document.getElementById('property-preview-modal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            document.getElementById('property-preview-media').innerHTML = '';
        }

        const consultButtons = Array.from(document.querySelectorAll('.consult-property-btn'));
        consultButtons.forEach(button => {
            button.addEventListener('click', () => {
                const card = button.closest('.property-card');
                if (card) openPropertyPreview(card);
            });
        });

        const previewClose = document.getElementById('property-preview-close');
        if (previewClose) {
            previewClose.addEventListener('click', closePropertyPreview);
        }

        document.getElementById('property-preview-modal').addEventListener('click', (event) => {
            if (event.target.id === 'property-preview-modal') {
                closePropertyPreview();
            }
        });

        (function() {
            const menuButton = document.getElementById('mobile-menu-open');
            const mobileNav = document.getElementById('mobile-navigation');
            if (!menuButton || !mobileNav) return;

            menuButton.addEventListener('click', () => {
                const isHidden = mobileNav.classList.contains('hidden');
                mobileNav.classList.toggle('hidden', !isHidden);
                menuButton.setAttribute('aria-expanded', String(isHidden));
            });

            mobileNav.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    mobileNav.classList.add('hidden');
                    menuButton.setAttribute('aria-expanded', 'false');
                });
            });
        })();
    </script>
</body>
</html>
