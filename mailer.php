<?php
/**
 * Script de Envío de Formulario - Airuska Ayala Web
 * Desarrollado con Protocolo TecnoFox (Seguridad y Aislamiento)
 */

header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/app/database.php';
$config = require __DIR__ . '/app/config.php';

// Configuración de Correo Destino
$destino = $config['mail_to'];

// Dependencias de PHPMailer
require __DIR__ . '/phpmailer/Exception.php';
require __DIR__ . '/phpmailer/PHPMailer.php';
require __DIR__ . '/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Prevenir acceso directo GET
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
    exit;
}

// ----------------------------------------------------
// FASE 1: Recolección y Sanitización de Datos (XSS Protection)
// ----------------------------------------------------
$nombre = htmlspecialchars(strip_tags($_POST['nombre'] ?? ''));
$whatsapp = htmlspecialchars(strip_tags($_POST['whatsapp'] ?? ''));
$correo = filter_var($_POST['correo'] ?? '', FILTER_SANITIZE_EMAIL);
$comuna = htmlspecialchars(strip_tags($_POST['comuna'] ?? ''));
$residencia = htmlspecialchars(strip_tags($_POST['residencia'] ?? ''));
$renta = htmlspecialchars(strip_tags($_POST['renta'] ?? ''));
$mensaje = htmlspecialchars(strip_tags($_POST['mensaje'] ?? ''));

// Validación básica de campos requeridos
if (empty($nombre) || empty($whatsapp) || empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Por favor, completa todos los campos requeridos con datos válidos.']);
    exit;
}

// ----------------------------------------------------
// FASE 2: Inicialización de PHPMailer
// ----------------------------------------------------
$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8';

try {
    // Configuración del Servidor SMTP
    // IMPORTANTE: RELLENAR CREDENCIALES ANTES DE DESPLEGAR
    $mail->isSMTP();
    $mail->Host       = $config['smtp_host'];
    $mail->SMTPAuth   = true;
    
    // Cambiar esto por la cuenta de envío (ej. noreply@tecnofox.cl o la misma de assistv)
    $mail->Username   = $config['smtp_username']; 
    
    // Cambiar esto por la "Contraseña de Aplicación" generada en Google Account. NO TU PASSWORD NORMAL.
    $mail->Password   = $config['smtp_password']; 
    
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL
    $mail->Port       = $config['smtp_port'];

    // Configuración de Cabeceras
    $mail->setFrom($mail->Username, $config['from_name']);
    $mail->addAddress($destino); // Correo principal que recibe
    $mail->addReplyTo($correo, $nombre); // Para poder presionar "Responder" y hablar con el cliente directamente

    // ----------------------------------------------------
    // FASE 3: Procesamiento de Adjuntos (CRM + PHPMailer)
    // ----------------------------------------------------
    $saved_files_paths = [];
    $upload_dir = __DIR__ . '/uploads/leads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    if (isset($_FILES['adjuntos']) && is_array($_FILES['adjuntos']['name'])) {
        $allowed_mime_types = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        $max_file_size = 5 * 1024 * 1024; // 5 MB por archivo límite de seguridad
        
        $total_files = count($_FILES['adjuntos']['name']);
        for ($i = 0; $i < $total_files; $i++) {
            $tmpFilePath = $_FILES['adjuntos']['tmp_name'][$i];
            
            if ($tmpFilePath != "") {
                // Verificar extensión y tipo MIME
                $fileType = mime_content_type($tmpFilePath);
                $fileSize = $_FILES['adjuntos']['size'][$i];
                $fileName = basename($_FILES['adjuntos']['name'][$i]);
                $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                
                // Validación estricta
                if (!in_array($fileType, $allowed_mime_types)) {
                    throw new Exception("El archivo '$fileName' no está permitido. Solo sube PDF, JPG o PNG.");
                }
                if ($fileSize > $max_file_size) {
                    throw new Exception("El archivo '$fileName' es demasiado pesado. El límite es 5MB.");
                }
                
                // Guardar en CRM de forma segura
                $new_filename = time() . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
                $destination = $upload_dir . $new_filename;
                
                if (move_uploaded_file($tmpFilePath, $destination)) {
                    $saved_files_paths[] = 'uploads/leads/' . $new_filename;
                    // Adjuntar al correo usando la copia guardada
                    $mail->addAttachment($destination, $fileName);
                }
            }
        }
    }

    // ----------------------------------------------------
    // FASE 4: Cuerpo del Correo
    // ----------------------------------------------------
    $mail->isHTML(true);
    $mail->Subject = "Nuevo Lead Precalificación: $nombre - $comuna";
    
    // Formato de tabla limpio para Gmail
    $body = "
    <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; padding: 20px; border: 1px solid #eee; border-radius: 10px;'>
        <h2 style='color: #059669; border-bottom: 2px solid #059669; padding-bottom: 10px;'>Nueva Solicitud de Precalificación</h2>
        <p>Has recibido un nuevo lead desde tu sitio web.</p>
        
        <table style='width: 100%; border-collapse: collapse; margin-top: 15px;'>
            <tr><td style='padding: 8px 0; border-bottom: 1px solid #eee;'><strong>Nombre:</strong></td><td style='padding: 8px 0; border-bottom: 1px solid #eee;'>{$nombre}</td></tr>
            <tr><td style='padding: 8px 0; border-bottom: 1px solid #eee;'><strong>WhatsApp:</strong></td><td style='padding: 8px 0; border-bottom: 1px solid #eee;'><a href='https://wa.me/" . preg_replace('/[^0-9]/', '', $whatsapp) . "'>{$whatsapp}</a></td></tr>
            <tr><td style='padding: 8px 0; border-bottom: 1px solid #eee;'><strong>Correo:</strong></td><td style='padding: 8px 0; border-bottom: 1px solid #eee;'>{$correo}</td></tr>
            <tr><td style='padding: 8px 0; border-bottom: 1px solid #eee;'><strong>Comuna de interés:</strong></td><td style='padding: 8px 0; border-bottom: 1px solid #eee;'>{$comuna}</td></tr>
            <tr><td style='padding: 8px 0; border-bottom: 1px solid #eee;'><strong>Residencia definitiva:</strong></td><td style='padding: 8px 0; border-bottom: 1px solid #eee;'>{$residencia}</td></tr>
            <tr><td style='padding: 8px 0; border-bottom: 1px solid #eee;'><strong>Renta Líquida Aprox:</strong></td><td style='padding: 8px 0; border-bottom: 1px solid #eee;'>\$ {$renta}</td></tr>
        </table>
        
        <h3 style='margin-top: 25px; color: #555;'>Mensaje:</h3>
        <div style='background-color: #f9f9f9; padding: 15px; border-radius: 8px; font-style: italic;'>
            " . nl2br($mensaje) . "
        </div>
        
        <p style='margin-top: 30px; font-size: 12px; color: #999; text-align: center;'>Generado automáticamente por Airuska Ayala Web.</p>
    </div>
    ";
    
    $mail->Body = $body;

    // Enviar!
    $mail->send();

    // ----------------------------------------------------
    // FASE 5: Guardado en Base de Datos CRM
    // ----------------------------------------------------
    try {
        $db = get_db();
        
        $adjuntos_json = json_encode($saved_files_paths);
        
        $stmt = $db->prepare("INSERT INTO contact_leads (nombre, whatsapp, correo, comuna, residencia, renta, mensaje, adjuntos_json) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $whatsapp, $correo, $comuna, $residencia, $renta, $mensaje, $adjuntos_json]);
    } catch (PDOException $pdo_e) {
        // Ignorar si falla, el correo ya se envió
        error_log("Error CRM: " . $pdo_e->getMessage());
    }

    echo json_encode(['status' => 'success', 'message' => 'Solicitud enviada correctamente.']);

} catch (Exception $e) {
    // Para producción no exponemos el error crudo, solo lo registramos (Protocolo de Anomalías)
    error_log("Error de Mailer (AiruskaWeb): {$mail->ErrorInfo}");
    echo json_encode(['status' => 'error', 'message' => "No se pudo procesar la solicitud. Verifica tu conexión o inténtalo más tarde."]);
}
