<?php

if (!function_exists('preview_article')) {
    function preview_article($html, $maxChars = 300) {
        // Hapus gambar
        $html = preg_replace('#<img.*?>#is', '', $html);

        // Ambil tag list dan p
        $allowed = '<ol><ul><li><p><br>';
        $clean = strip_tags($html, $allowed);

        // Hitung total teks
        $textOnly = strip_tags($clean);
        if (mb_strlen($textOnly) > $maxChars) {
            $textOnly = mb_substr($textOnly, 0, $maxChars) . '...';
        }

        return $clean;
    }
}
