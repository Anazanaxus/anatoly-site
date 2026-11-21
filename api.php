<?php
// Мост /api/<имя> -> api/<имя>.json для виртуального хостинга (Apache).
// POST /api/inquiries просто подтверждает приём: письмо уходит через mailto на клиенте.
$n = preg_replace('/[^a-z_]/', '', $_GET['name'] ?? '');
if ($n === 'inquiries') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok' => true]);
    exit;
}
$f = __DIR__ . '/api/' . $n . '.json';
if ($n !== '' && is_file($f)) {
    header('Content-Type: application/json; charset=utf-8');
    readfile($f);
} else {
    http_response_code(404);
}
