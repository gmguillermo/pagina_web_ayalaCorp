<?php
require __DIR__ . '/app/database.php';

// Conexión a la base de datos y obtención de datos dinámicos (Protocolo TecnoFox)
try {
    $db = get_db();
    
    // Obtener sección Sobre Mí
    $stmt_about = $db->query("SELECT * FROM about_section LIMIT 1");
    $about = $stmt_about->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Si la DB falla, no mostramos el error crudo por seguridad.
    die("Error interno de la aplicación. Por favor contacte a soporte.");
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airuska Ayala | Corredora y Gestora de Proyectos Inmobiliarios en Santiago</title>
    <meta name="description" content="Airuska Ayala: corredora de propiedades y gestora de proyectos en Santiago de Chile.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; }
        .sticky-header { position: sticky; top: 0; z-index: 50; }
        .whatsapp-float { position: fixed; width: 60px; height: 60px; bottom: 40px; right: 40px; background-color: #25D366; color: #FFF; border-radius: 50px; box-shadow: 2px 2px 10px rgba(0,0,0,0.3); z-index: 100; display: flex; align-items: center; justify-content: center; transition: all 0.3s; }
        .whatsapp-float:hover { background-color: #20b358; transform: scale(1.1); }
        .modal-active { overflow: hidden; }
        .highlight-area { transition: all 0.5s ease-in-out; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    <header class="sticky-header bg-white shadow-sm">
        <nav class="container mx-auto px-6 py-5 flex justify-between items-center">
            <a href="#" class="text-2xl font-bold text-gray-900">Airuska Ayala</a>
            <ul class="hidden md:flex space-x-8 text-gray-700 font-medium">
                <li><a href="#hero" class="hover:text-emerald-600 transition">Inicio</a></li>
                <li><a href="#sobre-mi" class="hover:text-emerald-600 transition">Sobre Mí</a></li>
                <li><a href="#servicios" class="hover:text-emerald-600 transition">Servicios</a></li>
                <li><a href="propiedades.php" class="hover:text-emerald-600 transition">Propiedades</a></li>
                <li><a href="blog.php" class="hover:text-emerald-600 transition">Blog</a></li>
                <li><a href="#contacto" class="hover:text-emerald-600 transition">Contacto</a></li>
            </ul>
        </nav>
    </header>

    <section id="hero" class="relative min-h-screen flex items-center bg-gray-900 text-white overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center opacity-40 scale-105" style="background-image: url('TU_URL_FOTO_HERO_AQUI');"></div>
        <div class="container mx-auto px-6 relative z-10 text-center md:text-left">
            <div class="max-w-3xl">
                <span class="inline-block bg-emerald-500/20 text-emerald-400 px-4 py-1 rounded-full text-sm font-bold mb-6 border border-emerald-500/30 uppercase tracking-widest">Inversión Inteligente 2026</span>
                <h1 class="text-5xl md:text-7xl font-extrabold leading-[1.1] mb-6">Gestión Inmobiliaria de Alto Impacto</h1>
                <p class="text-xl md:text-2xl mb-10 text-gray-300 font-light leading-relaxed">No solo busques propiedades, construye un activo financiero con nuestra asesoría personalizada.</p>
                <button onclick="openModal('modal-gratis')" class="bg-emerald-500 hover:bg-emerald-600 text-white px-10 py-5 rounded-2xl font-bold text-xl transition-all shadow-lg shadow-emerald-500/20 hover:scale-105 active:scale-95 text-center">
                    Gana tu Asesoría Gratuita
                </button>
            </div>
        </div>
    </section>

    <section id="sobre-mi" class="py-24 bg-white text-left">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-12 max-w-6xl mx-auto">
                <img src="<?= htmlspecialchars($about['profile_img_path'] ?? '') ?>" alt="Airuska Ayala" class="rounded-2xl shadow-xl w-full md:w-96 object-cover h-[450px]">
                
                <div class="space-y-6 text-lg leading-relaxed font-light text-slate-700">
                    <?= $about['content_html'] ?? '' ?>
                </div>
            </div>
        </div>
    </section>

    <section id="servicios" class="py-24 bg-gray-50 text-center">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl md:text-5xl font-bold mb-16">Servicios Premium</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-7xl mx-auto text-left text-sm">
                <div class="bg-white p-8 rounded-3xl shadow-md border border-purple-100 flex flex-col justify-between h-full">
                    <div>
                        <h3 class="text-xl font-bold mb-4 text-purple-700">Asesoría Personalizada Inversión Estratégica</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed text-left">Identifica activos financieros con alta plusvalía y rentabilidad real en Santiago de Chile.</p>
                    </div>
                    <button onclick="openModal('modal-inversion')" class="text-purple-600 font-bold hover:underline">Ver más →</button>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-md border border-gray-100 flex flex-col justify-between h-full">
                    <div>
                        <h3 class="text-xl font-bold mb-4">Reformas, Proyectos & Remodelaciones</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed text-left">Potencia el valor de tu propiedad o simplemente diseña ese espacio especial.</p>
                    </div>
                    <button onclick="openModal('modal-reformas')" class="text-gray-900 font-bold hover:underline">Ver más →</button>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-md border border-gray-100 flex flex-col justify-between h-full">
                    <div>
                        <h3 class="text-xl font-bold mb-4 text-gray-900">Administración Integral de Activos</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed text-left">Gestiono tu patrimonio profesionalmente, asegurando rentabilidad y cuidado impecable.</p>
                    </div>
                    <button onclick="openModal('modal-admin')" class="text-gray-900 font-bold hover:underline">Ver más →</button>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-md border border-gray-100 flex flex-col justify-between h-full">
                    <div>
                        <h3 class="text-xl font-bold mb-4 text-orange-700">Gestión de Patentes Comerciales</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed text-left">Acelera la apertura de tu negocio cumpliendo la normativa municipal en la RM.</p>
                    </div>
                    <button onclick="openModal('modal-patentes')" class="text-orange-600 font-bold hover:underline">Ver más →</button>
                </div>
            </div>
        </div>
    </section>

    <section id="propiedades" class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-[1fr_0.9fr] gap-10 items-center">
                <div class="text-left">
                    <span class="inline-block text-emerald-600 font-bold text-xs uppercase tracking-widest mb-4">Propiedades disponibles</span>
                    <h2 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">Explora el portal de propiedades</h2>
                    <p class="text-gray-600 text-lg leading-relaxed mb-8">Las publicaciones ahora viven en una pagina dedicada, conectada a Google Sheets para que puedas actualizar propiedades sin tocar codigo.</p>
                    <a href="propiedades.php" class="inline-flex items-center justify-center bg-black text-white px-8 py-4 rounded-2xl font-bold hover:bg-emerald-600 transition">
                        Ver propiedades
                    </a>
                </div>
                <div class="bg-gray-900 text-white rounded-[2rem] p-8 md:p-10 shadow-2xl">
                    <span class="inline-block bg-emerald-500/20 text-emerald-300 px-4 py-1 rounded-full text-xs font-bold mb-6 border border-emerald-500/30 uppercase tracking-widest">Portal inmobiliario</span>
                    <h3 class="text-3xl font-extrabold mb-4">Filtra por comuna, tipo y presupuesto.</h3>
                    <p class="text-gray-300 leading-relaxed">Centraliza tus oportunidades en un solo lugar y permite que los clientes consulten por cada propiedad desde el formulario.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="contacto" class="py-24 bg-gray-50">
        <div class="container mx-auto px-6 max-w-3xl text-center">
            <h2 class="text-4xl font-bold mb-4">Inicia tu precalificación gratis</h2>
            <p class="text-center text-gray-600 mb-12 text-left md:text-center">Completa tus datos para abordar tu caso de forma específica.</p>
            <form id="contact-form" class="space-y-5 bg-white p-8 md:p-10 rounded-[2.5rem] shadow-xl text-left border border-gray-100" enctype="multipart/form-data">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-left">
                    <input type="text" name="nombre" placeholder="Nombre y Apellido" class="w-full p-4 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500" required>
                    <input type="tel" name="whatsapp" placeholder="WhatsApp" class="w-full p-4 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500" required>
                </div>
                <input type="email" name="correo" placeholder="Correo Electrónico" class="w-full p-4 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500" required>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-left">
                    <input type="text" name="comuna" placeholder="Comuna de interés" class="w-full p-4 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500" required>
                    <select name="residencia" class="w-full p-4 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500 bg-white" required>
                        <option value="" disabled selected>¿Residencia definitiva?</option>
                        <option value="si">Sí, cuento con ella</option>
                        <option value="no">No, en trámite / otro</option>
                    </select>
                </div>
                <div class="relative text-left">
                    <span class="absolute left-4 top-4 text-gray-400">$</span>
                    <input type="number" name="renta" placeholder="Renta líquida aprox." class="w-full p-4 pl-8 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500" required>
                </div>
                <div id="file-upload-area" class="highlight-area bg-emerald-50/50 p-6 rounded-2xl border-2 border-dashed border-emerald-200 text-center text-left">
                    <label class="block text-sm font-bold text-emerald-800 mb-2 text-left">Adjuntar documentos (Precalificación GRATIS):</label>
                    <p class="text-xs text-emerald-600 mb-4 text-left italic">Sube tus liquidaciones y cotizaciones (PDF o JPG).</p>
                    <input type="file" name="adjuntos[]" multiple accept=".pdf,.jpg,.jpeg,.png" class="w-full text-sm text-gray-500 cursor-pointer">
                </div>
                <textarea name="mensaje" placeholder="Cuéntame sobre tu proyecto..." class="w-full p-4 border border-gray-200 rounded-xl h-32 outline-none focus:ring-2 focus:ring-emerald-500" required></textarea>
                
                <!-- Status Message Area -->
                <div id="form-status" class="hidden rounded-xl p-4 text-center font-medium mt-4"></div>
                
                <button type="submit" id="submit-btn" class="w-full bg-black text-white py-5 rounded-2xl font-bold hover:bg-emerald-600 transition shadow-lg text-lg text-center flex items-center justify-center mt-2">
                    <span>Enviar y Agendar</span>
                </button>
            </form>
        </div>
    </section>

    <div id="modal-container" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/70 backdrop-blur-md">

        <div id="modal-gratis" class="modal-content hidden bg-white rounded-[2.5rem] max-w-2xl w-full p-10 shadow-2xl relative text-left overflow-y-auto max-h-[95vh]">
            <h3 class="text-3xl font-extrabold text-emerald-600 mb-4">¡Gana tu Asesoría 100% Gratuita!</h3>
            <div class="bg-emerald-50 p-6 rounded-3xl mb-8 border border-emerald-100 text-sm text-emerald-900">
                <h4 class="font-bold mb-2 italic text-left">Análisis crediticio previo</h4>
                <p class="text-left">Para abordar tu caso de forma específica y efectiva, realizamos un <strong>análisis crediticio previo</strong>. Si adjuntas tus 3 últimas liquidaciones y cotizaciones en el formulario, realizaremos una precalificación bancaria previa que nos ayudará a definir los próximos pasos hasta la compra de tu propiedad.</p>
            </div>
            <div class="space-y-4 mb-8 text-gray-700 text-sm">
                <div class="flex items-start text-left"><span class="w-8 h-8 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center mr-3 font-bold text-xs flex-shrink-0">1</span>Análisis técnico de tu perfil financiero.</div>
                <div class="flex items-start text-left"><span class="w-8 h-8 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center mr-3 font-bold text-xs flex-shrink-0">2</span>Recomendación de zonas estratégicas en Santiago.</div>
                <div class="flex items-start text-left"><span class="w-8 h-8 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center mr-3 font-bold text-xs flex-shrink-0">3</span>Sesión de <strong>20 min</strong> para definir tu hoja de ruta.</div>
            </div>
            <button onclick="irASubirDocumentos()" class="w-full bg-black text-white py-5 rounded-2xl font-bold hover:bg-emerald-600 transition shadow-lg text-lg text-center">Cerrar y subir documentos</button>
        </div>

        <div id="modal-inversion" class="modal-content hidden bg-white rounded-[2.5rem] max-w-2xl w-full p-10 shadow-2xl relative text-left overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-2xl font-bold text-purple-700">Asesoría Personalizada de Inversión Estratégica</h3>
                <span class="bg-purple-100 text-purple-700 px-4 py-2 rounded-full font-bold">$50.000</span>
            </div>
            <p class="text-gray-600 mb-8 leading-relaxed italic text-sm text-left">Aprenderás no solo a buscar propiedades, aprenderás a identificar activos financieros. Evaluación de zonas potenciales y mercado de <strong>Santiago de Chile</strong> para identificar oportunidades con alta plusvalía y proyecciones de rentabilidad reales.</p>
            <ul class="space-y-4 mb-10 text-gray-700 text-sm">
                <li class="flex items-start text-left"><span class="text-purple-500 mr-3 font-bold">✔</span> Sesión personalizada profunda de <strong>50 min</strong>.</li>
                <li class="flex items-start text-left"><span class="text-purple-500 mr-3 font-bold">✔</span> Análisis comercial de mercado basado en datos reales.</li>
                <li class="flex items-start text-left"><span class="text-purple-500 mr-3 font-bold">✔</span> Estrategias de salida y retorno (ROI).</li>
                <li class="bg-purple-50 p-4 rounded-2xl border-l-4 border-purple-500 font-medium"><strong>Plus:</strong> Pasa por una preaprobación bancaria y descubre cuánto te prestaría el banco.</li>
            </ul>
            <button onclick="closeModal()" class="w-full bg-purple-700 text-white py-4 rounded-2xl font-bold text-center">Cerrar</button>
        </div>

        <div id="modal-reformas" class="modal-content hidden bg-white rounded-[2.5rem] max-w-2xl w-full p-10 shadow-2xl relative text-left overflow-y-auto max-h-[90vh]">
            <h3 class="text-3xl font-bold mb-4">Reformas, Proyectos & Remodelaciones</h3>
            <p class="text-gray-600 mb-8 leading-relaxed text-left text-sm">Potencia el valor de tu propiedad antes de comercializarla o simplemente diseña y has realidad ese espacio especial de tu casa o tu negocio.</p>
            <ul class="space-y-4 mb-10 text-gray-700 text-sm">
                <li class="flex items-start text-left"><span class="text-gray-900 mr-3 font-bold">✔</span> Remodelaciones "llave en mano" con enfoque en rentabilidad.</li>
                <li class="flex items-start text-left"><span class="text-gray-900 mr-3 font-bold">✔</span> Optimización de espacios para el mercado moderno.</li>
                <li class="flex items-start text-left"><span class="text-gray-900 mr-3 font-bold">✔</span> Supervisión técnica y control de presupuestos.</li>
            </ul>
            <button onclick="closeModal()" class="w-full bg-black text-white py-4 rounded-2xl font-bold text-center">Cerrar</button>
        </div>

        <div id="modal-admin" class="modal-content hidden bg-white rounded-[2.5rem] max-w-2xl w-full p-10 shadow-2xl relative text-left overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-2xl font-bold text-gray-900 leading-tight">Administración Integral de Activos</h3>
                <span class="bg-gray-100 text-gray-900 px-4 py-2 rounded-full font-bold">7% + IVA</span>
            </div>
            <p class="text-gray-600 mb-8 leading-relaxed text-sm text-left">Tu tranquilidad es mi prioridad. Gestiono tu patrimonio de forma profesional, asegurando cobros puntuales y mantenimiento óptimo de la propiedad.</p>
            <ul class="space-y-4 mb-10 text-gray-700 text-sm text-left">
                <li class="flex items-start text-left"><span class="text-gray-900 mr-3 font-bold">✔</span> Selección rigurosa de arrendatarios y verificación de antecedentes.</li>
                <li class="flex items-start text-left"><span class="text-gray-900 mr-3 font-bold">✔</span> Gestión de pagos, cuentas y reportes mensuales.</li>
                <li class="flex items-start text-left"><span class="text-gray-900 mr-3 font-bold">✔</span> Solución inmediata de incidencias.</li>
            </ul>
            <button onclick="closeModal()" class="w-full bg-black text-white py-4 rounded-2xl font-bold text-center">Cerrar</button>
        </div>

        <div id="modal-patentes" class="modal-content hidden bg-white rounded-[2.5rem] max-w-2xl w-full p-10 shadow-2xl relative text-left overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-2xl font-bold text-orange-700 leading-tight">Gestión de Patentes Comerciales</h3>
                <span class="bg-orange-100 text-orange-700 px-4 py-2 rounded-full font-bold">$250.000</span>
            </div>
            <p class="text-gray-700 mb-6 leading-relaxed text-sm text-left">
                <strong>Fase 1 Provisoria:</strong> Acelera la apertura de tu negocio. Me encargo de la tramitación técnica y administrativa para obtener tu Patente Comercial Provisoria, asegurando que tu proyecto cumpla con las normativas municipales en Santiago de Chile y toda la Región Metropolitana desde el primer momento.
            </p>
            <div class="bg-orange-50 p-6 rounded-2xl mb-8 border border-orange-200">
                <p class="text-orange-900 text-sm font-bold italic text-left">✨ Beneficio Exclusivo: Si decides avanzar con la gestión completa de la patente, el valor de esta asesoría se descontará del monto total de la gestión.</p>
            </div>
            <ul class="space-y-4 mb-10 text-gray-700 text-sm">
                <li class="flex items-start text-left"><span class="text-orange-500 mr-3 font-bold">✔</span> Guía y acompañamiento en la búsqueda de local.</li>
                <li class="flex items-start text-left"><span class="text-orange-500 mr-3 font-bold">✔</span> Factibilidad de Uso de Suelo previo.</li>
                <li class="flex items-start text-left"><span class="text-orange-500 mr-3 font-bold">✔</span> Gestión Documental Ante la DOM.</li>
                <li class="flex items-start text-left"><span class="text-orange-500 mr-3 font-bold">✔</span> Tramitación agilizada en tiempo récord.</li>
            </ul>
            <button onclick="closeModal()" class="w-full bg-black text-white py-4 rounded-2xl font-bold text-center text-lg">Cerrar</button>
        </div>
    </div>

    <a href="https://wa.me/56940174702?text=Hola%20Airuska%2C%20vengo%20de%20tu%20web" class="whatsapp-float" target="_blank">
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.198-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.297-.497.099-.198.05-.371-.025-.52-.074-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    </a>

    <script>
        function openModal(modalId) {
            document.getElementById('modal-container').classList.remove('hidden');
            document.body.classList.add('modal-active');
            document.querySelectorAll('.modal-content').forEach(m => m.classList.add('hidden'));
            const target = document.getElementById(modalId);
            if (target) target.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal-container').classList.add('hidden');
            document.body.classList.remove('modal-active');
        }

        function irASubirDocumentos() {
            closeModal();
            setTimeout(() => {
                const form = document.getElementById('contacto');
                form.scrollIntoView({ behavior: 'smooth' });
                const uploadArea = document.getElementById('file-upload-area');
                uploadArea.classList.add('ring-4', 'ring-emerald-500', 'ring-opacity-50', 'bg-emerald-100');
                setTimeout(() => {
                    uploadArea.classList.remove('ring-4', 'ring-emerald-500', 'ring-opacity-50', 'bg-emerald-100');
                }, 2000);
            }, 300);
        }

        window.onclick = function(event) {
            const container = document.getElementById('modal-container');
            if (event.target == container) closeModal();
        }

        // --- Lógica de Envío de Formulario ---
        document.getElementById('contact-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('submit-btn');
            const status = document.getElementById('form-status');
            const form = this;
            
            // Estado de carga visual
            btn.disabled = true;
            btn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Procesando...`;
            
            // Resetear estado
            status.classList.add('hidden');
            status.className = "rounded-xl p-4 text-center font-medium mt-4";
            
            const formData = new FormData(form);
            
            try {
                // Enviar datos hacia el nuevo backend PHP
                const response = await fetch('mailer.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                status.classList.remove('hidden');
                if(result.status === 'success') {
                    status.classList.add('bg-emerald-100', 'text-emerald-800', 'border', 'border-emerald-200');
                    status.innerText = "¡Solicitud enviada con éxito! Revisaremos tu perfil a la brevedad.";
                    form.reset();
                } else {
                    status.classList.add('bg-red-100', 'text-red-800', 'border', 'border-red-200');
                    status.innerText = "Error: " + result.message;
                }
            } catch (error) {
                status.classList.remove('hidden');
                status.classList.add('bg-red-100', 'text-red-800', 'border', 'border-red-200');
                status.innerText = "Error de red. Por favor verifica tu conexión y vuelve a intentar.";
            } finally {
                // Restaurar botón a estado original
                btn.disabled = false;
                btn.innerHTML = `<span>Enviar y Agendar</span>`;
            }
        });
    </script>
</body>
</html>
