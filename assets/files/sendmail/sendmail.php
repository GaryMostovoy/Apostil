<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// === Серверна перевірка + валідація ===
// === Список обов'язкових полів ===
$required_fields = [
    'country', 'service-type', 'document-type', 'processing-time',
    'name-1', 'soname-1', 'city-1', 'adress-1', 'zip-1', 'tel-1', 'email-1',
    'name-2', 'soname-2', 'tel-2', 'city-2', 'adress-2', 'zip-2', 'delivery'
];

// === Очищення даних ===
function clean_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// === Honeypot ===
if (!empty($_POST['website'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Spam detected']);
    exit;
}

// === Перевірка обов'язкових полів ===
$errors = [];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || (is_array($_POST[$field]) && count(array_filter($_POST[$field])) === 0) || (!is_array($_POST[$field]) && trim($_POST[$field]) === '')) {
        $errors[] = "Поле {$field} обов'язкове для заповнення";
    } else {
        if (is_array($_POST[$field])) {
            $_POST[$field] = array_map('clean_input', $_POST[$field]);
        } else {
            $_POST[$field] = clean_input($_POST[$field]);
        }
    }
}

// === Перевірка email ===
if (!empty($_POST['email-1']) && !filter_var($_POST['email-1'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Неправильний email";
}

// === Перевірка телефону (дозволяємо тільки цифри, +, -, пробіли, дужки) ===
// $phone_pattern = '/^[0-9\+\-\s\(\)]+$/';
// if (!empty($_POST['tel-1']) && !preg_match($phone_pattern, $_POST['tel-1'])) {
//     $errors[] = "Неправильний телефон";
// }


$phones = ['tel-1', 'tel-2'];

foreach ($phones as $key) {
    $phone = $_POST[$key] ?? '';

    $phone = str_replace(
        ["\xE2\x80\x90", "\xE2\x80\x91", "\xE2\x80\x92", "\xE2\x80\x93", "\xE2\x80\x94"],
        '-',
        $phone
    );

    $phone = str_replace("\xC2\xA0", ' ', $phone);
    $phone = preg_replace('/[^\d\+\-\s\(\)]/u', '', $phone);
    $phone = trim($phone);

    $_POST[$key] = $phone;

    if (!empty($phone) && !preg_match('/^\+?[0-9\-\s\(\)]+$/', $phone)) {
        $errors[] = "Неправильний номер телефону ($key)";
    }
}

// === Якщо є помилки — повертаємо JSON ===
if (!empty($errors)) {
    http_response_code(400);
    header('Content-type: application/json');
    echo json_encode(['error' => $errors]);
    exit;
}

// === Генерація номеру замовлення
// Встановлення часового поясу
date_default_timezone_set('America/New_York');

$datePart = date('md'); 
$randomPart = mt_rand(100000, 999999); 
$orderNumber = $datePart . '-' . $randomPart;

// === Підготовка тіла листа (одне для двох адрес)
$body = "<h1>New Form Submission №{$orderNumber}</h1>";

$body .= '<h2>Step 1. Information about documents</h2>';
if (!empty($_POST['country'])) $body .= "<p><strong>Country where the documents are going to be used: </strong>" . $_POST['country'] . '</p>';
if (!empty($_POST['service-type'])) {
    $types = is_array($_POST['service-type']) ? implode(", ", $_POST['service-type']) : $_POST['service-type'];
    $body .= "<p><strong>Service type: </strong>$types</p>";
}
if (!empty($_POST['document-type'])) {
    $docs = is_array($_POST['document-type']) ? implode(", ", $_POST['document-type']) : $_POST['document-type'];
    $body .= "<p><strong>Document type: </strong>$docs</p>";
}
if (!empty($_POST['document-quantity'])) $body .= "<p><strong>Documents quantity: </strong>" . $_POST['document-quantity'] . '</p>';
if (!empty($_POST['processing-time'])) $body .= "<p><strong>Processing Time: </strong>" . $_POST['processing-time'] . '</p>';

$body .= '<h3 style="text-decoration:underline">Translates</h3>';
if (!empty($_POST['translate-from'])) $body .= "<p><strong>Translate From: </strong>" . $_POST['translate-from'] . '</p>';
if (!empty($_POST['translate-to'])) $body .= "<p><strong>Translate To: </strong>" . $_POST['translate-to'] . '</p>';
if (!empty($_POST['add-translation'])) $body .= "<p><strong>Number of pages: </strong>" . $_POST['add-translation'] . '</p>';

$body .= '<h2>Step 2: Information about client and return shipping</h2>';
if (!empty($_POST['name-1'])) $body .= "<p><strong>First Name: </strong>" . $_POST['name-1'] . '</p>';
if (!empty($_POST['soname-1'])) $body .= "<p><strong>Last Name: </strong>" . $_POST['soname-1'] . '</p>';
if (!empty($_POST['company-name-1'])) $body .= "<p><strong>Company Name: </strong>" . $_POST['company-name-1'] . '</p>';
if (!empty($_POST['state-1'])) $body .= "<p><strong>Country: </strong>" . $_POST['state-1'] . '</p>';
if (!empty($_POST['city-1'])) $body .= "<p><strong>City: </strong>" . $_POST['city-1'] . '</p>';
if (!empty($_POST['adress-1'])) $body .= "<p><strong>Address: </strong>" . $_POST['adress-1'] . '</p>';
if (!empty($_POST['st-1'])) $body .= "<p><strong>State: </strong>" . $_POST['st-1'] . '</p>';
if (!empty($_POST['zip-1'])) $body .= "<p><strong>Zip or Postal Code: </strong>" . $_POST['zip-1'] . '</p>';
if (!empty($_POST['tel-1'])) $body .= "<p><strong>Phone Number: </strong>" . $_POST['tel-1'] . '</p>';
if (!empty($_POST['email-1'])) $body .= "<p><strong>Email: </strong>" . $_POST['email-1'] . '</p>';

$body .= '<h3 style="text-decoration:underline">Return shipping address</h3>';
if (!empty($_POST['name-2'])) $body .= "<p><strong>First Name: </strong>" . $_POST['name-2'] . '</p>';
if (!empty($_POST['soname-2'])) $body .= "<p><strong>Last Name: </strong>" . $_POST['soname-2'] . '</p>';
if (!empty($_POST['company-name-2'])) $body .= "<p><strong>Company Name: </strong>" . $_POST['company-name-2'] . '</p>';
if (!empty($_POST['state-2'])) $body .= "<p><strong>Country: </strong>" . $_POST['state-2'] . '</p>';
if (!empty($_POST['city-2'])) $body .= "<p><strong>City: </strong>" . $_POST['city-2'] . '</p>';
if (!empty($_POST['adress-2'])) $body .= "<p><strong>Address: </strong>" . $_POST['adress-2'] . '</p>';
if (!empty($_POST['st-2'])) $body .= "<p><strong>State: </strong>" . $_POST['st-2'] . '</p>';
if (!empty($_POST['zip-2'])) $body .= "<p><strong>Zip or Postal Code: </strong>" . $_POST['zip-2'] . '</p>';
if (!empty($_POST['delivery'])) $body .= "<p><strong>Return Shipping: </strong>" . $_POST['delivery'] . '</p>';
if (!empty($_POST['tel-2'])) $body .= "<p><strong>Phone Number: </strong>" . $_POST['tel-2'] . '</p>';

$body .= '<h2 style="text-decoration:underline">Order Summary</h2>';
if (!empty($_POST['order-total'])) $body .= "<p><strong>Order Amount: </strong>" . $_POST['order-total'] . '</p>';

// === Підключення ядра WordPress
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

// === PLATFORM / DEVICE (тільки для сайту)
$platform = 'web';
$device   = wp_is_mobile() ? 'mobile' : 'desktop';

// === Збереження в CPT
$post_id = wp_insert_post([
    'post_type'   => 'order',
    'post_title'  => 'Order ' . $orderNumber,
    'post_status' => 'publish'
]);

if ($post_id) {
    $date = date('m/d');
    update_post_meta($post_id, 'order_date', $date);
    update_post_meta($post_id, 'order_number', $orderNumber);
    update_post_meta($post_id, 'order_name', ($_POST['name-1'] ?? '') . ' ' . ($_POST['soname-1'] ?? ''));
	update_post_meta($post_id, 'order_company', $_POST['company-name-1'] ?? '');
    update_post_meta($post_id, 'order_phone', $_POST['tel-1'] ?? '');
    update_post_meta($post_id, 'order_email', $_POST['email-1'] ?? '');
	update_post_meta($post_id, 'order_phone_return', $_POST['tel-2'] ?? '');
	update_post_meta($post_id, 'order_platform', $platform);
	update_post_meta($post_id, 'order_device', $device);

	// === АДМІНКА Адреса №1	
	$city_line_1 = trim($_POST['city-1'] ?? '');
	$st_line_1   = trim($_POST['st-1'] ?? '');
	$zip_line_1  = trim($_POST['zip-1'] ?? '');

	// Збираємо рядок: місто, штат індекс
	if ($st_line_1) {
		$city_line_full_1 = "{$city_line_1}, {$st_line_1} {$zip_line_1}";
	} else {
		$city_line_full_1 = "{$city_line_1}, {$zip_line_1}";
	}

	// Збираємо всю адресу
	$address_parts_1 = array_filter([
		$_POST['state-1'] ?? '',
		$_POST['adress-1'] ?? '',
		$city_line_full_1
	]);

	update_post_meta($post_id, 'order_adress', implode(' <br> ', $address_parts_1));
	
	// === АДМІНКА Адреса повернення
	$city_line_2 = trim($_POST['city-2'] ?? '');
	$st_line_2   = trim($_POST['st-2'] ?? '');
	$zip_line_2  = trim($_POST['zip-2'] ?? '');
	
	// Збираємо рядок: місто, штат індекс
	if ($st_line_2) {
		$city_line_full_2 = "{$city_line_2}, {$st_line_2} {$zip_line_2}";
	} else {
		$city_line_full_2 = "{$city_line_2}, {$zip_line_2}";
	}
	
	// Збираємо всю адресу
	$address_parts_2 = array_filter([
		$_POST['state-2'] ?? '',
		$_POST['adress-2'] ?? '',
		$city_line_full_2
	]);
	update_post_meta($post_id, 'order_adress_return', implode(' <br> ', $address_parts_2));
	
	update_post_meta($post_id, 'order_service', $_POST['service-type'] ?? []);
    update_post_meta($post_id, 'order_amount', $_POST['order-total'] ?? '');
    update_post_meta($post_id, 'order_translation_pages', $_POST['add-translation'] ?? '');
    update_post_meta($post_id, 'order_processing_time', $_POST['processing-time'] ?? '');
	update_post_meta($post_id, 'order_documents', $_POST['document-type'] ?? []);
	update_post_meta($post_id, 'documents_quantity', $_POST['document-quantity'] ?? '');
}

// === Завантажуємо HTML-шаблон
$templatePath = __DIR__ . '/email-template.html';
$template = file_get_contents($templatePath);

	// ДЛЯ ЛИСТА. Збираємо адресу №1, пусте поле буде видалено
	$state_1  = trim($_POST['state-1'] ?? '');
	$adress_1 = trim($_POST['adress-1'] ?? '');
	$city_1   = trim($_POST['city-1'] ?? '');
	$st_1     = trim($_POST['st-1'] ?? '');
	$zip_1    = trim($_POST['zip-1'] ?? '');

	// Формуємо частину адреси (місто, штат, індекс)
	if ($st_1) {
		$city_line_1 = "{$city_1}, {$st_1} {$zip_1}";
	} else {
		$city_line_1 = "{$city_1}, {$zip_1}";
	}
	// Ціла адреса
	$address_parts_1 = array_filter([$state_1, $adress_1, $city_line_1]);
	$address1 = implode(', ', $address_parts_1);


	// ДЛЯ ЛИСТА. Збираємо адресу №2 
	$state_2  = trim($_POST['state-2'] ?? '');
	$adress_2 = trim($_POST['adress-2'] ?? '');
	$city_2   = trim($_POST['city-2'] ?? '');
	$st_2     = trim($_POST['st-2'] ?? '');
	$zip_2    = trim($_POST['zip-2'] ?? '');

	// Формуємо частину адреси (місто, штат, індекс)
	if ($st_2) {
		$city_line_2 = "{$city_2}, {$st_2} {$zip_2}";
	} else {
		$city_line_2 = "{$city_2}, {$zip_2}";
	}
	// Ціла адреса
	$address_parts_2 = array_filter([$state_2, $adress_2, $city_line_2]);
	$address2 = implode(', ', $address_parts_2);

// === Підставляємо значення
$replacements = [
	'{{orderNumber}}'     => $orderNumber,
	'{{country}}'         => $_POST['country'] ?? '',
	'{{serviceType}}'     => is_array($_POST['service-type']) ? implode(", ", $_POST['service-type']) : ($_POST['service-type'] ?? ''),
	'{{documentType}}'    => is_array($_POST['document-type']) ? implode(", ", $_POST['document-type']) : ($_POST['document-type'] ?? ''),
	'{{documentTypeQuantity}}'=> $_POST['document-quantity'] ?? '',
	'{{processingTime}}'  => $_POST['processing-time'] ?? '',
	'{{translateFrom}}'   => $_POST['translate-from'] ?? '',
	'{{translateTo}}'     => $_POST['translate-to'] ?? '',
	'{{translationPages}}'=> $_POST['add-translation'] ?? '',
	'{{name-1}}'          => $_POST['name-1'] ?? '',
	'{{lastname-1}}'      => $_POST['soname-1'] ?? '',
	'{{companyName1}}'    => $_POST['company-name-1'] ?? '',
	'{{address1}}' 		  => $address1,
	'{{tel-1}}'           => $_POST['tel-1'] ?? '',
	'{{email}}'           => $_POST['email-1'] ?? '',
	'{{name-2}}'          => $_POST['name-2'] ?? '',
	'{{lastname-2}}'      => $_POST['soname-2'] ?? '',
	'{{companyName2}}'    => $_POST['company-name-2'] ?? '',
	'{{address2}}' 		  => $address2,
	'{{delivery}}'        => $_POST['delivery'] ?? '',
	'{{tel-2}}'           => $_POST['tel-2'] ?? '',
	'{{orderTotal}}'      => $_POST['order-total'] ?? '',
	'{{approveLink}}'     => $_POST['paypal_link'] ?? '',
];

// Функція видалення блоку з листа, якщо значення порожнє
function processConditionalBlocks(string $template, array $replacements): string {
    foreach ($replacements as $placeholder => $value) {
        // Прибираємо блок, якщо значення порожнє або 0
        if ($value === '' || $value === null || $value === '0' || $value === 0) {
            $pattern = sprintf(
                '/<!-- IF %s -->.*?<!-- ENDIF %s -->/is',
                preg_quote(trim($placeholder, '{}'), '/'),
                preg_quote(trim($placeholder, '{}'), '/')
            );
            $template = preg_replace($pattern, '', $template);
        }
    }
    return $template;
}

// Використовуємо
$template = processConditionalBlocks($template, $replacements);

// Міняємо плейсхолдери
foreach ($replacements as $placeholder => $value) {
    if ($placeholder === '{{approveLink}}') {
        $template = str_replace($placeholder, $value, $template);
    } else {
        $template = str_replace($placeholder, htmlspecialchars($value), $template);
    }
}

// Прикріплені файли
$attachments = [];
$errors = [];

if (!empty($_FILES['userfile']['name'][0])) {

    $allowed_ext = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
    $allowed_mime = [
        'image/jpeg',
        'image/png',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    foreach ($_FILES['userfile']['name'] as $i => $name) {
        if ($_FILES['userfile']['error'][$i] !== UPLOAD_ERR_OK) {
            continue;
        }

        $tmpPath = $_FILES['userfile']['tmp_name'][$i];
        $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $mime = mime_content_type($tmpPath);

        if (!in_array($ext, $allowed_ext) || !in_array($mime, $allowed_mime)) {
            $errors[] = "The file $name has an invalid format.<br>";
            continue;
        }

        $dest = tempnam(sys_get_temp_dir(), 'upload_') . '.' . $ext;

        if (move_uploaded_file($tmpPath, $dest)) {
            $attachments[] = [
                'path' => $dest,
                'name' => $name
            ];
        }
    }
}

// === Відправка листа адміністратору (з вкладеннями)
$mail = new PHPMailer(true);
try {
    $mail->CharSet = 'UTF-8';
    $mail->setLanguage('ru', 'phpmailer/language/');
    $mail->IsHTML(true);
    $mail->isSMTP();
    $mail->Host       = 'mail.apostil.co';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'order@apostil.co';
	$mail->Password   = 'sodmI7-ryvhys-xyzpib';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom('order@apostil.co', 'Apostil Inc');
    $mail->addAddress('order@apostil.co');
	$mail->Subject = "Your order #{$orderNumber}";
    $mail->Body = $template;

	foreach ($attachments as $file) {
		$mail->addAttachment($file['path'], $file['name']);
	}
	
    $mail->send();
} catch (Exception $e) {
	// log error
}

// === Відправка листа клієнту (якщо email вказано)
if (!empty($_POST['email-1']) && filter_var($_POST['email-1'], FILTER_VALIDATE_EMAIL)) {
    try {
        $clientMail = new PHPMailer(true);
        $clientMail->CharSet = 'UTF-8';
        $clientMail->setLanguage('ru', 'phpmailer/language/');
        $clientMail->IsHTML(true);
        $clientMail->isSMTP();
        $clientMail->Host       = 'mail.apostil.co';
        $clientMail->SMTPAuth   = true;
        $clientMail->Username   = 'order@apostil.co';
		$clientMail->Password   = 'sodmI7-ryvhys-xyzpib';
        $clientMail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $clientMail->Port       = 465;

        $clientMail->setFrom('order@apostil.co', 'Apostil Inc');
        $clientMail->addAddress($_POST['email-1']);
		$clientMail->Subject = "Your order #{$orderNumber}";
        $clientMail->Body = $template;
		
		foreach ($attachments as $file) {
			$clientMail->addAttachment($file['path'], $file['name']);
		}
		
        $clientMail->send();
    } catch (Exception $e) {
        // log error
    }
}

// === Відповідь клієнту
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'success' => empty($errors),
    'errors' => $errors,
    'message' => empty($errors) ? 'Дані відправлено!' : null,
    'orderNumber' => $orderNumber
]);
exit;