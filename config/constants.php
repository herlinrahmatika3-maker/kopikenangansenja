<?php
/**
 * constants.php
 * ----------------------------------------------------------------
 * Mendeteksi otomatis BASE_URL (path URL menuju folder root project).
 * Dipakai di semua halaman untuk:
 *  - link antar halaman (pages/, actions/)
 *  - link CSS/JS (assets/)
 *  - link gambar produk (uploads/)
 *  - redirect header('Location: ...')
 *
 * Dengan begini, project tetap berfungsi normal baik diakses di:
 *  - http://localhost/kopikenangansenja/
 *  - http://localhost/ (root)
 *  - atau domain/folder lain, tanpa perlu edit manual satu-satu.
 */

if (!defined('BASE_URL')) {
    $documentRoot = isset($_SERVER['DOCUMENT_ROOT'])
        ? str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/'))
        : '';

    // dirname(__DIR__) = folder root project (tempat index.php utama berada)
    $projectRoot = str_replace('\\', '/', rtrim(dirname(__DIR__), '/'));

    $base = '';
    if ($documentRoot !== '' && strpos($projectRoot, $documentRoot) === 0) {
        $base = substr($projectRoot, strlen($documentRoot));
    }

    define('BASE_URL', $base); // contoh hasil: "/kopikenangansenja" atau ""
}
