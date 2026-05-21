<?php
require __DIR__ . '/app/database.php';

try {
    $db = get_db();

    // 1. admin_users
    $db->exec("CREATE TABLE IF NOT EXISTS admin_users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        password_hash TEXT NOT NULL
    )");
    
    // Seed admin user (aiayweb1)
    $stmt = $db->query("SELECT COUNT(*) FROM admin_users");
    if ($stmt->fetchColumn() == 0) {
        $hash = password_hash('aiayweb1', PASSWORD_BCRYPT);
        $stmt = $db->prepare("INSERT INTO admin_users (username, password_hash) VALUES ('admin', ?)");
        $stmt->execute([$hash]);
    }

    // 2. about_section
    $db->exec("CREATE TABLE IF NOT EXISTS about_section (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        profile_img_path TEXT,
        content_html TEXT
    )");
    
    // Seed about me
    $stmt = $db->query("SELECT COUNT(*) FROM about_section");
    if ($stmt->fetchColumn() == 0) {
        $img = 'https://lh3.googleusercontent.com/d/1IpxvCdWuMGw2ewhZgsVLiIiFmIgyLSpP';
        $content = '<h2 class="text-4xl font-bold text-gray-900">Sobre Mí</h2>
                    <p>Soy Airuska Ayala, corredora de propiedades y gestora de proyectos en <strong class="text-black font-bold">Santiago de Chile.</strong> Mi enfoque es simple: <strong class="text-black font-bold">resultados reales sobre metros cuadrados.</strong></p>
                    <p>No solo te ayudo a comprar una propiedad; me especializo en diseñar estrategias para que cada inversión sea un <strong class="text-black font-medium text-emerald-700">activo rentable desde el primer día</strong>, combinando el análisis comercial de mercado con una gestión de proyectos integral.</p>
                    <p class="text-xl font-semibold text-slate-900 pt-4 italic">Mi meta es que inviertas con inteligencia. Si buscas una asesoría donde la rentabilidad y la transparencia sean la prioridad, hablemos.</p>';
        $stmt = $db->prepare("INSERT INTO about_section (profile_img_path, content_html) VALUES (?, ?)");
        $stmt->execute([$img, $content]);
    }

    // 3. blog_posts
    $db->exec("CREATE TABLE IF NOT EXISTS blog_posts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        category_label TEXT,
        title TEXT,
        content_html TEXT,
        call_to_action TEXT,
        btn_action TEXT
    )");
    
    // Seed blogs
    $stmt = $db->query("SELECT COUNT(*) FROM blog_posts");
    if ($stmt->fetchColumn() == 0) {
        $db->exec("INSERT INTO blog_posts (category_label, title, content_html, call_to_action, btn_action) VALUES (
            'Estrategia',
            'Guía de Inversión 2026: ¿Es mejor comprar en \"Verde\" o \"Blanco\" en Santiago?',
            '<p>Si estás pensando en dar el salto al mundo inmobiliario, seguro te has topado con estos términos. En 2026, la clave de una buena inversión no es solo qué compras, sino cuándo firmas la promesa.</p>
             <p><strong>Compra en Blanco:</strong> Estás comprando un proyecto que solo existe en planos (o están excavando el terreno). Es el momento de máxima rentabilidad. Al ser la etapa inicial, los precios son los más bajos del mercado, permitiéndote ganar una plusvalía inmediata conforme avanza la construcción.</p>
             <p><strong>Compra en Verde:</strong> El edificio ya está en construcción. El riesgo es menor porque ya ves la estructura, pero el precio suele ser un 5% a 10% más alto que en blanco. Sigue siendo excelente para pagar el pie en cuotas mientras terminan la obra.</p>
             <p><strong>¿El beneficio clave en 2026?</strong> La flexibilidad. Comprar en estas etapas te permite congelar el precio de hoy y pagar el pie hasta en 30 cuotas, mientras el valor del m² en Santiago sigue subiendo.</p>
             <p class=\"font-bold text-emerald-800\">¡Haz que los números hablen! Calcula tu retorno de inversión (ROI) con mi plantilla personalizada.</p>',
            'Agenda tu asesoría de 20 min aquí',
            'irASubirDocumentos()'
        )");
        $db->exec("INSERT INTO blog_posts (category_label, title, content_html, call_to_action, btn_action) VALUES (
            'Plusvalía',
            'Las 5 comunas de Santiago con mayor plusvalía por la extensión del Metro',
            '<p>Invertir en propiedades es, en gran medida, invertir en conectividad. Con las nuevas extensiones de la red de Metro de Santiago proyectadas para finales de la década, hay comunas que hoy están en el \"punto dulce\" de precio antes de dispararse.</p>
             <p>Aquí te dejo el Top 5 para este año:</p>
             <ul class=\"list-disc pl-6 space-y-2\">
                 <li><strong>La Cisterna:</strong> Se ha consolidado como un polo de inversión por su conectividad intermodal.</li>
                 <li><strong>San Bernardo:</strong> La llegada del Metro ha transformado zonas antes residenciales en focos de departamentos modernos.</li>
                 <li><strong>Ñuñoa (Sector Oriente):</strong> Sigue siendo la favorita de los profesionales jóvenes; la plusvalía aquí no descansa.</li>
                 <li><strong>Renca / Quilicura:</strong> Con la consolidación de la Línea 7, estas zonas están viviendo una renovación urbana sin precedentes.</li>
                 <li><strong>Cerrillos:</strong> El desarrollo del Parque Bicentenario y nuevas líneas la convierten en la \"joya escondida\" del sector surponiente.</li>
             </ul>
             <p>Invertir cerca de una futura estación de Metro garantiza una menor vacancia y un aumento del valor de tu activo por el simple hecho de estar a pasos del transporte.</p>
             <p class=\"font-bold italic text-emerald-800\">Dato Pro: Tengo acceso a proyectos exclusivos en estas zonas con beneficios de preventa antes de que suban de precio.</p>',
            'Hablemos en una asesoría',
            'irASubirDocumentos()'
        )");
        $db->exec("INSERT INTO blog_posts (category_label, title, content_html, call_to_action, btn_action) VALUES (
            'Consejos',
            'Gastos comunes en Santiago: ¿Cómo evitar sorpresas al comprar?',
            '<p>A veces el dividendo se ve perfecto, pero los gastos comunes terminan rompiendo el presupuesto. En Santiago, el promedio puede variar drásticamente según la comuna y los servicios del edificio.</p>
             <p>Para que no te lleves sorpresas, fíjate en estos tres puntos:</p>
             <p><strong>1. Amenidades vs. Uso:</strong> ¿Realmente necesitas piscina temperada, cine y 4 quinchos? Recuerda que el mantenimiento de esos espacios lo pagas tú mes a mes, los uses o no.</p>
             <p><strong>2. Eficiencia Energética:</strong> Los edificios modernos con calificación energética A o B reducen costos en calefacción y agua caliente, lo que impacta directamente en la boleta mensual.</p>
             <p><strong>3. Seguridad:</strong> Es el ítem más caro. Edificios con conserjería 24/7 en torres pequeñas suelen tener gastos comunes más altos por departamento que las torres grandes.</p>
             <p>Antes de comprar, siempre pide el histórico de los últimos 6 meses para entender el comportamiento real del edificio, especialmente en invierno.</p>
             <p class=\"font-bold text-emerald-800 italic text-left\">No compres a ciegas: Yo analizo el historial de gastos comunes de la propiedad que te interesa antes de que firmes.</p>',
            'Reserva tu sesión de 50 min aquí',
            'openModal(''modal-inversion'')'
        )");
    }

    // 4. contact_leads
    $db->exec("CREATE TABLE IF NOT EXISTS contact_leads (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombre TEXT NOT NULL,
        whatsapp TEXT NOT NULL,
        correo TEXT NOT NULL,
        comuna TEXT NOT NULL,
        residencia TEXT NOT NULL,
        renta REAL NOT NULL,
        mensaje TEXT,
        adjuntos_json TEXT,
        fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
        estado_leido INTEGER DEFAULT 0
    )");

    // Set appropriate permissions for web server to write to db file and its directory
    chmod(__DIR__ . '/database.sqlite', 0666);

    echo 'SUCCESS';
} catch (PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}
?>
