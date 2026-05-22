# Airuska Ayala Web

Guia completa para levantar, configurar y publicar este sitio.

Este documento está escrito para una persona que nunca ha desarrollado un proyecto web. Sigue los pasos en orden.

## Guía rápida para principiantes

1. Descarga o copia el proyecto en tu equipo.
2. Abre una terminal en la carpeta del proyecto.
3. Configura `app/config.php` con tus datos de correo y Google Sheets.
4. Ejecuta `php init_db.php` para crear la base de datos.
5. Ejecuta `php -S 127.0.0.1:8000`.
6. Abre el navegador en `http://127.0.0.1:8000/index.php`.

> Este mismo flujo funciona en macOS y Windows si tienes PHP disponible.

## 1. Que es este proyecto

Este proyecto es un sitio web PHP para Airuska Ayala.

Tiene estas paginas:

- Inicio: `index.php`
- Blog inmobiliario: `blog.php`
- Portal de propiedades: `propiedades.php`
- Formulario de contacto: `mailer.php`

El sitio usa:

- PHP para mostrar las paginas.
- SQLite como base de datos local: `database.sqlite`
- Google Sheets para manejar publicaciones de propiedades.
- Google Sheets para manejar articulos del blog.
- PHPMailer para enviar correos desde el formulario.

## 2. Lo mas importante antes de empezar

Este proyecto está diseñado para ejecutarse en una computadora con PHP instalado. No necesitas saber programar, no necesitas Node, npm ni MySQL.

Sigue estos pasos en orden:

1. Copia o descarga el proyecto en tu equipo.
2. Abre una terminal en la carpeta del proyecto.
3. Configura los datos de Google Sheets y correo en `app/config.php`.
4. Ejecuta `php init_db.php` para crear la base de datos.
5. Ejecuta `php -S 127.0.0.1:8000` para iniciar el servidor.
6. Abre el navegador en `http://127.0.0.1:8000/index.php`.

> Si quieres, puedes usar `app/config.php` con valores directos mientras aprendes. Es la forma más simple para principiantes.

## 3. Qué necesitas antes de todo

Antes de ejecutar el sitio, revisa estos elementos:

### 3.1 Copia del proyecto

Debes tener la carpeta del proyecto en tu equipo. Dentro deben estar los archivos:

- `index.php`
- `blog.php`
- `propiedades.php`
- `mailer.php`
- `init_db.php`
- `app/config.php`
- `app/properties.php`
- `app/blogs.php`
- `app/database.php`

Si no tienes la carpeta, descárgala desde el repositorio o copia todo el contenido a una carpeta nueva.

### 3.2 Qué ID y URL usar en Google Sheets

El proyecto puede leer datos desde Google Sheets si publicas tus hojas como CSV.

Para usar tus propios datos, necesitas dos URLs de Google Sheets:

- `PROPERTIES_SHEET_CSV_URL` para la hoja de propiedades.
- `BLOG_SHEET_CSV_URL` para la hoja de blog.

Cómo obtener el ID y la URL CSV:

1. Abre tu hoja en Google Sheets.
2. Ve a `Archivo` → `Publicar en la web`.
3. Selecciona la pestaña de datos.
4. Elige el formato `Valores separados por comas (.csv)`.
5. Copia el enlace generado.

La URL debe verse así:

```text
https://docs.google.com/spreadsheets/d/<TU_ID>/gviz/tq?tqx=out:csv&sheet=Sheet1
```

- `<TU_ID>` es el identificador largo de tu hoja.
- `sheet=Sheet1` puede cambiar según el nombre de la pestaña.

> Si no configuras estas URLs, el sitio mostrará contenido de ejemplo que ya viene incluido.

### 3.3 Variables de correo necesarias

Para que el formulario funcione y envíe correos, necesitas configurar estas variables:

- `MAIL_TO`
- `SMTP_HOST`
- `SMTP_USERNAME`
- `SMTP_PASSWORD`
- `SMTP_PORT`
- `MAIL_FROM_NAME`

Si no sabes qué poner, lo más fácil es editar `app/config.php` y reemplazar los valores de ejemplo por los tuyos.

Ejemplo sencillo en `app/config.php`:

```php
return [
    'mail_to' => 'tu-correo@ejemplo.com',
    'smtp_host' => 'smtp.gmail.com',
    'smtp_username' => 'tu-correo@gmail.com',
    'smtp_password' => 'TU_PASSWORD_DE_APLICACION',
    'smtp_port' => 465,
    'from_name' => 'Airuska Ayala',
    'properties_sheet_csv_url' => 'https://docs.google.com/spreadsheets/d/<TU_ID>/gviz/tq?tqx=out:csv&sheet=propiedades',
    'properties_cache_seconds' => 900,
    'blog_sheet_csv_url' => 'https://docs.google.com/spreadsheets/d/<TU_ID>/gviz/tq?tqx=out:csv&sheet=blog',
    'blog_cache_seconds' => 900,
];
```

### 3.4 Complementos de PHP

Este sitio necesita PHP con soporte para:

- `sqlite3`
- `mbstring`
- `curl`
- `xml`

En macOS normalmente estos módulos vienen con PHP. En Windows, si usas XAMPP o Laragon, suelen venir incluidos.

Si no estás seguro, abre una terminal y ejecuta:

```bash
php -m
```

Luego busca en la lista `sqlite3`, `mbstring`, `curl` y `xml`.

### 3.5 Uso de las plantillas CSV

Las plantillas están en:

- `templates/google_sheets_propiedades_template.csv`
- `templates/google_sheets_blog_template.csv`

Puedes abrir estos archivos en Excel o importarlos a Google Sheets para usar el formato correcto.

## 4. Estructura real del proyecto

```text
.
├── index.php
├── blog.php
├── propiedades.php
├── mailer.php
├── init_db.php
├── logout.php
├── database.sqlite
├── .htaccess
├── app/
│   ├── config.php
│   ├── database.php
│   ├── blogs.php
│   └── properties.php
├── phpmailer/
│   ├── Exception.php
│   ├── PHPMailer.php
│   └── SMTP.php
├── storage/
│   ├── .htaccess
│   └── cache/
├── templates/
│   ├── google_sheets_blog_template.csv
│   └── google_sheets_propiedades_template.csv
├── uploads/
│   └── leads/
└── README_HOSTING.md
```

## 5. Para que sirve cada archivo

### Paginas visibles

- `index.php`: pagina principal del sitio.
- `blog.php`: pagina del blog.
- `propiedades.php`: pagina donde se ven las propiedades.

### Backend del sitio

- `mailer.php`: recibe el formulario de contacto, envia correo y guarda el lead.
- `init_db.php`: crea la estructura inicial de la base de datos.
- `logout.php`: cierre de sesion. Hoy no es clave para el flujo principal.

### Configuracion y logica

- `app/config.php`: aqui se configuran correo SMTP y links de Google Sheets.
- `app/database.php`: conexion a `database.sqlite`.
- `app/properties.php`: lee propiedades desde Google Sheets o usa datos de respaldo.
- `app/blogs.php`: lee articulos desde Google Sheets o usa SQLite como respaldo.

### Datos y archivos

- `database.sqlite`: base de datos local.
- `storage/cache/`: cache de Google Sheets.
- `uploads/leads/`: documentos adjuntos enviados por usuarios.
- `templates/google_sheets_propiedades_template.csv`: plantilla para Google Sheets de propiedades.
- `templates/google_sheets_blog_template.csv`: plantilla para Google Sheets del blog.

## 6. Instalar PHP en local

### Requisitos generales

Para levantar este proyecto solo necesitas PHP. No se requiere Node, npm ni compilación. El sitio usa SQLite, por lo que también necesitas PHP con soporte para SQLite.

### Si usas macOS

1. Abre la app Terminal.
2. Si no tienes Homebrew, instálalo desde:

```text
https://brew.sh/
```

3. Instala PHP con:

```bash
brew install php
```

4. Confirma la instalación:

```bash
php -v
```

Deberías ver algo como:

```text
PHP 8.x.x
```

5. Si quieres, puedes instalar Composer también:

```bash
brew install composer
```

### Si usas Windows

1. Instala una distribución de PHP o un paquete que incluya PHP como:

- Laragon: `https://laragon.org/download/`
- XAMPP: `https://www.apachefriends.org/es/index.html`

2. Abre una terminal de Windows que tenga acceso a `php`:

- PowerShell
- Símbolo del sistema (cmd)
- Terminal de Laragon

3. Ve a la carpeta del proyecto y verifica PHP:

```powershell
php -v
```

4. Si `php` no se reconoce:

- Con XAMPP, usa el PHP incluido:

```powershell
C:\xampp\php\php.exe -v
```

Y para ejecutar el sitio:

```powershell
cd C:\ruta\a\tu\proyecto
C:\xampp\php\php.exe -S 127.0.0.1:8000
```

- Con Laragon, abre la terminal de Laragon y utiliza `php -v` desde allí.

Si `php` ya está en tu `PATH`, solo usa `php -S 127.0.0.1:8000`.

5. Si usas XAMPP, también puedes copiar el proyecto a `C:\xampp\htdocs\tu_proyecto` y abrirlo en el navegador con `http://localhost/tu_proyecto/index.php`, pero preferimos usar el servidor de PHP incorporado para desarrollo.

### Comando común para Mac y Windows

Desde la carpeta del proyecto, puedes levantar el servidor local con el mismo comando en macOS y Windows:

```bash
php -S 127.0.0.1:8000
```

Esto funciona si el comando `php` está disponible en tu terminal.

## 7. Cómo abrir una terminal en la carpeta del proyecto

Debes estar dentro de la carpeta donde están estos archivos:

```text
index.php
blog.php
propiedades.php
```

Ejemplo en macOS:

```bash
cd /Users/coderslab/Downloads/pagwewairuskaayala
```

Ejemplo en Windows PowerShell:

```powershell
cd C:\Users\tu_usuario\Downloads\pagwewairuskaayala
```

Si el proyecto está en otra carpeta, cambia la ruta.

Ejemplo:

```bash
cd /Users/tu_usuario/Desktop/airuska-web
```

## 8. Verificar que los archivos PHP no tienen errores

Ejecuta estos comandos:

```bash
php -l index.php
php -l blog.php
php -l propiedades.php
php -l mailer.php
php -l app/config.php
php -l app/database.php
php -l app/properties.php
php -l app/blogs.php
```

Si todo esta bien, veras mensajes como:

```text
No syntax errors detected in index.php
```

## 9. Inicializar la base de datos

Este proyecto usa SQLite. La base esta en:

```text
database.sqlite
```

Para crear o asegurar las tablas necesarias, ejecuta:

```bash
php init_db.php
```

Debe responder:

```text
SUCCESS
```

Si ves `SUCCESS`, puedes continuar.

## 10. Levantar el sitio en local

En la terminal, dentro de la carpeta del proyecto, ejecuta:

```bash
php -S 127.0.0.1:8000
```

Deja esa terminal abierta.

Ahora abre el navegador y visita:

```text
http://127.0.0.1:8000/index.php
```

Tambien puedes abrir:

```text
http://127.0.0.1:8000/blog.php
http://127.0.0.1:8000/propiedades.php
```

Importante:

`127.0.0.1` significa "esta computadora". Ese link no funciona para otras personas.

Para que otra persona vea el sitio, hay que subirlo a hosting o usar un tunel temporal.

## 11. Que configurar en `app/config.php`

Abre este archivo:

```text
app/config.php
```

Ese archivo tiene esta forma:

```php
<?php

return [
    'mail_to' => getenv('MAIL_TO') ?: 'correo-destino@example.com',
    'smtp_host' => getenv('SMTP_HOST') ?: 'smtp.gmail.com',
    'smtp_username' => getenv('SMTP_USERNAME') ?: 'correo-envio@example.com',
    'smtp_password' => getenv('SMTP_PASSWORD') ?: 'TU_PASSWORD_DE_APLICACION',
    'smtp_port' => (int) (getenv('SMTP_PORT') ?: 465),
    'from_name' => getenv('MAIL_FROM_NAME') ?: 'Notificacion Web - Airuska Ayala',
    'properties_sheet_csv_url' => getenv('PROPERTIES_SHEET_CSV_URL') ?: '',
    'properties_cache_seconds' => (int) (getenv('PROPERTIES_CACHE_SECONDS') ?: 900),
    'blog_sheet_csv_url' => getenv('BLOG_SHEET_CSV_URL') ?: '',
    'blog_cache_seconds' => (int) (getenv('BLOG_CACHE_SECONDS') ?: 900),
];
```

Debes configurar principalmente:

- `mail_to`: correo que recibira los formularios.
- `smtp_username`: correo que enviara los mensajes.
- `smtp_password`: contrasena de aplicacion SMTP.
- `properties_sheet_csv_url`: link CSV de Google Sheets para propiedades.
- `blog_sheet_csv_url`: link CSV de Google Sheets para blog.

## 10.1. Como funciona Google Sheets como "base de datos"

En este proyecto Google Sheets se usa como una base de datos simple para contenido editable.

Eso significa:

- Las propiedades se escriben en una hoja de Google Sheets.
- Los articulos del blog se escriben en otra hoja de Google Sheets.
- El sitio lee esas hojas como archivos CSV publicados.
- El sitio convierte cada fila del Sheet en una publicacion visible.

No necesitas entrar al codigo para agregar una propiedad o un articulo. Solo editas Google Sheets.

### Que partes usan Google Sheets

Propiedades:

```text
propiedades.php
```

usa:

```text
app/properties.php
```

y lee el link configurado en:

```php
'properties_sheet_csv_url' => getenv('PROPERTIES_SHEET_CSV_URL') ?: '',
```

Blog:

```text
blog.php
```

usa:

```text
app/blogs.php
```

y lee el link configurado en:

```php
'blog_sheet_csv_url' => getenv('BLOG_SHEET_CSV_URL') ?: '',
```

Ambas configuraciones estan en:

```text
app/config.php
```

### Que debes crear en Google Sheets

Debes tener dos hojas publicadas como CSV:

1. Una hoja para propiedades.
2. Una hoja para articulos del blog.

Puedes usar las plantillas incluidas:

```text
templates/google_sheets_propiedades_template.csv
templates/google_sheets_blog_template.csv
```

### Como se actualiza el sitio

Cuando alguien abre:

```text
propiedades.php
```

el sitio hace esto:

1. Revisa si hay un link en `properties_sheet_csv_url`.
2. Descarga el CSV de Google Sheets.
3. Lee cada fila.
4. Oculta filas con `published = 0`.
5. Muestra filas con `published = 1`.
6. Guarda una copia temporal en `storage/cache/properties.csv`.

Cuando alguien abre:

```text
blog.php
```

el sitio hace esto:

1. Revisa si hay un link en `blog_sheet_csv_url`.
2. Descarga el CSV de Google Sheets.
3. Lee cada fila.
4. Oculta filas con `published = 0`.
5. Ordena por `sort_order`.
6. Muestra el primer articulo como destacado.
7. Guarda una copia temporal en `storage/cache/blog_posts.csv`.

### Por que existe cache

El cache evita pedir datos a Google Sheets en cada visita.

Por defecto el cache dura 15 minutos.

Configuracion:

```php
'properties_cache_seconds' => (int) (getenv('PROPERTIES_CACHE_SECONDS') ?: 900),
'blog_cache_seconds' => (int) (getenv('BLOG_CACHE_SECONDS') ?: 900),
```

Si quieres que los cambios se vean casi de inmediato, puedes poner:

```php
'properties_cache_seconds' => (int) (getenv('PROPERTIES_CACHE_SECONDS') ?: 60),
'blog_cache_seconds' => (int) (getenv('BLOG_CACHE_SECONDS') ?: 60),
```

Eso deja el cache en 60 segundos.

### Si Google Sheets falla

El proyecto tiene respaldo:

- Si falla Google Sheets de propiedades, usa ejemplos internos de `app/properties.php`.
- Si falla Google Sheets del blog, usa los articulos guardados en `database.sqlite`.

Esto evita que la pagina quede completamente vacia.

### Importante sobre privacidad

Para que el sitio lea Google Sheets, la hoja debe estar publicada en la web como CSV.

No pongas datos privados en esas hojas.

Google Sheets debe contener solo contenido publico:

- Titulos
- Descripciones
- Precios
- Comunas
- Links de imagenes
- Links de YouTube
- Articulos del blog

No pongas:

- Datos de clientes
- Telefonos privados
- Correos personales
- Documentos
- Claves
- Informacion bancaria

## 11. Configurar Google Sheets para propiedades

Las propiedades se muestran en:

```text
propiedades.php
```

La logica esta en:

```text
app/properties.php
```

La plantilla esta en:

```text
templates/google_sheets_propiedades_template.csv
```

### Paso A: crear la hoja

1. Entra a Google Sheets.
2. Crea una hoja nueva.
3. Importa el archivo:

```text
templates/google_sheets_propiedades_template.csv
```

En Google Sheets:

1. Ve a `Archivo`.
2. Selecciona `Importar`.
3. Sube el archivo CSV.
4. Elige insertar en hoja nueva o reemplazar hoja actual.

### Paso B: columnas obligatorias

La primera fila debe ser exactamente:

```csv
published,title,type,operation,commune,price_label,price_value,bedrooms,bathrooms,area,status,image,video_url,description
```

No cambies esos nombres.

### Paso C: que significa cada columna

- `published`: escribe `1` para mostrar la propiedad y `0` para ocultarla.
- `title`: titulo de la propiedad.
- `type`: tipo. Ejemplo: `Departamento`, `Casa`, `Local comercial`.
- `operation`: operacion. Ejemplo: `Venta`, `Arriendo`.
- `commune`: comuna. Ejemplo: `Providencia`, `Ñuñoa`, `La Cisterna`.
- `price_label`: precio visible. Ejemplo: `UF 2.450`.
- `price_value`: numero para filtro. Ejemplo: `2450`.
- `bedrooms`: dormitorios.
- `bathrooms`: banos.
- `area`: superficie visible. Ejemplo: `52 m2`.
- `status`: estado. Ejemplo: `Disponible`, `En verde`, `Reservada`.
- `image`: URL publica de imagen.
- `video_url`: URL de YouTube.
- `description`: descripcion corta.

### Paso D: videos de YouTube

En `video_url` puedes pegar cualquiera de estos formatos:

```text
https://www.youtube.com/watch?v=VIDEO_ID
https://youtu.be/VIDEO_ID
https://www.youtube.com/embed/VIDEO_ID
```

El sitio convierte el enlace y muestra el video dentro de la tarjeta.

No pegues codigo completo tipo `<iframe ...></iframe>` en columnas de texto. Si por error lo pegas en `video_url`, el sistema intentara extraer el link, pero lo recomendado es pegar solo la URL.

Si `video_url` esta vacio, se muestra la imagen de `image`.

### Paso E: publicar la hoja como CSV

Esto es lo mas importante.

1. En Google Sheets ve a `Archivo`.
2. Selecciona `Compartir`.
3. Selecciona `Publicar en la web`.
4. En el primer selector, elige la hoja correcta.
5. En el segundo selector, elige:

```text
Valores separados por comas (.csv)
```

6. Haz clic en `Publicar`.
7. Copia el link generado.

### Paso F: pegar el link en el proyecto

Abre:

```text
app/config.php
```

Busca esta linea:

```php
'properties_sheet_csv_url' => getenv('PROPERTIES_SHEET_CSV_URL') ?: '',
```

Pegala asi:

```php
'properties_sheet_csv_url' => getenv('PROPERTIES_SHEET_CSV_URL') ?: 'https://docs.google.com/spreadsheets/d/e/TU_LINK/pub?output=csv',
```

Guarda el archivo.

Luego abre:

```text
http://127.0.0.1:8000/propiedades.php
```

## 12. Configurar Google Sheets para blog

El blog se muestra en:

```text
blog.php
```

La logica esta en:

```text
app/blogs.php
```

La plantilla esta en:

```text
templates/google_sheets_blog_template.csv
```

### Paso A: crear la hoja

1. Entra a Google Sheets.
2. Crea una hoja nueva.
3. Importa:

```text
templates/google_sheets_blog_template.csv
```

### Paso B: columnas obligatorias

La primera fila debe ser:

```csv
published,id,sort_order,category_label,title,content_html,call_to_action
```

No cambies esos nombres.

### Paso C: que significa cada columna

- `published`: `1` para mostrar, `0` para ocultar.
- `id`: identificador. Puedes usar `1`, `2`, `3`.
- `sort_order`: orden de aparicion. El articulo con orden `1` queda destacado.
- `category_label`: categoria visible. Ejemplo: `Estrategia`.
- `title`: titulo del articulo.
- `content_html`: contenido del articulo.
- `call_to_action`: texto del boton.

### Paso D: como escribir contenido en `content_html`

Puedes escribir HTML basico.

Ejemplos:

```html
<p>Este es un parrafo.</p>
<p><strong>Texto importante:</strong> explicacion.</p>
<ul><li>Punto 1</li><li>Punto 2</li></ul>
<ol><li>Paso 1</li><li>Paso 2</li></ol>
<h3>Subtitulo</h3>
```

Evita pegar codigo raro, scripts o iframes en el blog.

### Paso E: publicar la hoja del blog como CSV

1. En Google Sheets ve a `Archivo`.
2. Selecciona `Compartir`.
3. Selecciona `Publicar en la web`.
4. Elige la hoja del blog.
5. Elige formato:

```text
Valores separados por comas (.csv)
```

6. Publica.
7. Copia el link.

### Paso F: pegar el link en el proyecto

Abre:

```text
app/config.php
```

Busca:

```php
'blog_sheet_csv_url' => getenv('BLOG_SHEET_CSV_URL') ?: '',
```

Pega el link asi:

```php
'blog_sheet_csv_url' => getenv('BLOG_SHEET_CSV_URL') ?: 'https://docs.google.com/spreadsheets/d/e/TU_LINK/pub?output=csv',
```

Guarda.

Luego abre:

```text
http://127.0.0.1:8000/blog.php
```

## 13. Cache de Google Sheets

Para no pedir datos a Google Sheets en cada visita, el sitio guarda una copia temporal.

Propiedades:

```text
storage/cache/properties.csv
```

Blog:

```text
storage/cache/blog_posts.csv
```

Por defecto dura 900 segundos, o sea 15 minutos.

Configuracion en `app/config.php`:

```php
'properties_cache_seconds' => (int) (getenv('PROPERTIES_CACHE_SECONDS') ?: 900),
'blog_cache_seconds' => (int) (getenv('BLOG_CACHE_SECONDS') ?: 900),
```

Si cambias algo en Google Sheets y no aparece de inmediato, espera 15 minutos o borra los archivos de cache en:

```text
storage/cache/
```

## 14. Configurar correo del formulario

El formulario esta en:

```text
index.php
```

El envio lo procesa:

```text
mailer.php
```

La configuracion esta en:

```text
app/config.php
```

Campos importantes:

```php
'mail_to' => getenv('MAIL_TO') ?: 'correo-destino@example.com',
'smtp_host' => getenv('SMTP_HOST') ?: 'smtp.gmail.com',
'smtp_username' => getenv('SMTP_USERNAME') ?: 'correo-envio@example.com',
'smtp_password' => getenv('SMTP_PASSWORD') ?: 'TU_PASSWORD_DE_APLICACION',
'smtp_port' => (int) (getenv('SMTP_PORT') ?: 465),
'from_name' => getenv('MAIL_FROM_NAME') ?: 'Notificacion Web - Airuska Ayala',
```

### Si usas Gmail

No uses la contrasena normal.

Debes usar una "contrasena de aplicacion" de Google.

Resumen:

1. Activa verificacion en 2 pasos en la cuenta Gmail.
2. Crea una contrasena de aplicacion.
3. Copia esa clave.
4. Pegala en `smtp_password`.

## 15. Probar el formulario

1. Abre:

```text
http://127.0.0.1:8000/index.php
```

2. Baja hasta contacto.
3. Llena el formulario.
4. Envia.

Si todo esta bien, deberia llegar un correo a `mail_to`.

Tambien se guarda un lead en la tabla `contact_leads` de `database.sqlite`.

## 16. Subir a hosting

Para subirlo a hosting:

1. Sube todos los archivos del proyecto.
2. Asegurate de que `index.php` quede en la carpeta publica del hosting.
3. Confirma que el hosting soporte PHP 8.1 o superior.
4. Confirma que SQLite este habilitado.
5. Ejecuta una vez:

```text
https://tudominio.com/init_db.php
```

Debe mostrar:

```text
SUCCESS
```

6. Luego elimina `init_db.php` o mantenlo bloqueado.
7. Configura `app/config.php` con:
   - SMTP
   - Google Sheet de propiedades
   - Google Sheet de blog
8. Prueba:
   - `https://tudominio.com/index.php`
   - `https://tudominio.com/blog.php`
   - `https://tudominio.com/propiedades.php`

## 17. Permisos necesarios en hosting

Estas rutas deben poder escribirse:

```text
database.sqlite
storage/cache/
uploads/leads/
```

Si algo no guarda o no actualiza, revisa permisos.

## 18. Seguridad importante

El archivo `.htaccess` bloquea acceso publico a:

- `app/`
- `phpmailer/`
- `storage/`
- `uploads/leads/`
- `database.sqlite`
- `init_db.php`

Esto funciona en servidores Apache con `.htaccess` habilitado.

Si tu hosting usa Nginx, debes pedir al hosting reglas equivalentes.

No subas credenciales reales a un repositorio publico.

## 19. Problemas comunes

### El sitio no abre localmente

Revisa que el servidor este corriendo:

```bash
php -S 127.0.0.1:8000
```

### Dice que PHP no existe

Instala PHP.

En macOS:

```bash
brew install php
```

### Error con SQLite

Verifica:

```bash
php -m
```

Busca:

```text
pdo_sqlite
sqlite3
```

### No se ven propiedades desde Google Sheets

Revisa:

- Que el link CSV este en `app/config.php`.
- Que la hoja este publicada como CSV.
- Que la primera fila tenga los nombres exactos.
- Que `published` sea `1`.
- Que `storage/cache/` tenga permisos.
- Que hayas esperado el tiempo de cache o borrado `storage/cache/properties.csv`.

### No se ven articulos del blog desde Google Sheets

Revisa:

- Que el link CSV este en `app/config.php`.
- Que la hoja este publicada como CSV.
- Que la primera fila tenga los nombres exactos.
- Que `published` sea `1`.
- Que `content_html` tenga contenido.
- Que hayas esperado el tiempo de cache o borrado `storage/cache/blog_posts.csv`.

### Las imagenes no cargan

La columna `image` debe ser una URL publica directa.

Si usas Google Drive, el enlace comun de Drive puede no funcionar como imagen directa.

Mejor usa:

- Imagen subida al hosting.
- CDN.
- URL directa de imagen.

### Los videos de YouTube no cargan

Revisa que `video_url` sea alguno de estos:

```text
https://www.youtube.com/watch?v=VIDEO_ID
https://youtu.be/VIDEO_ID
https://www.youtube.com/embed/VIDEO_ID
```

### El correo no llega

Revisa:

- `smtp_username`
- `smtp_password`
- `mail_to`
- Que Gmail tenga contrasena de aplicacion.
- Que el hosting permita SMTP saliente.

## 20. URLs principales

Local:

```text
http://127.0.0.1:8000/index.php
http://127.0.0.1:8000/blog.php
http://127.0.0.1:8000/propiedades.php
```

Produccion:

```text
https://tudominio.com/index.php
https://tudominio.com/blog.php
https://tudominio.com/propiedades.php
```

## 21. Orden recomendado para alguien nuevo

1. Instala PHP.
2. Abre terminal en la carpeta del proyecto.
3. Ejecuta `php init_db.php`.
4. Ejecuta `php -S 127.0.0.1:8000`.
5. Abre `http://127.0.0.1:8000/index.php`.
6. Importa la plantilla de propiedades en Google Sheets.
7. Publica propiedades como CSV.
8. Pega el link en `app/config.php`.
9. Importa la plantilla del blog en Google Sheets.
10. Publica blog como CSV.
11. Pega el link en `app/config.php`.
12. Configura correo SMTP.
13. Prueba formulario.
14. Sube a hosting.
