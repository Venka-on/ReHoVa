<?php
header('Content-Type: text/plain; charset=UTF-8');
$to   = 'sales@rehova.sg';
$from = 'no-reply@rehova.sg';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method not allowed'); }
if (!empty($_POST['_gotcha'])) { exit('OK'); }
function field($key, $max) {
    $v = isset($_POST[$key]) ? trim((string)$_POST[$key]) : '';
    return mb_substr($v, 0, $max);
}
$name    = str_replace(["\r", "\n"], ' ', field('Name', 100));
$phone   = str_replace(["\r", "\n"], ' ', field('Phone', 30));
$scope   = str_replace(["\r", "\n"], ' ', field('Project_Scope', 100));
$message = field('Message', 2000);
if ($name === '' || $scope === '' || !preg_match('/^[0-9+\-\s()]{6,25}$/', $phone)) { http_response_code(400); exit('Invalid input'); }
$subject = 'New quotation request - ReHoVa website';
$body = "Name: $name\nPhone: $phone\nProject scope: $scope\n\nMessage:\n$message\n";
$headers  = "From: ReHoVa Website <$from>\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
if (mail($to, $subject, $body, $headers, "-f$from")) { exit('OK'); }
http_response_code(500);
exit('Send failed');
