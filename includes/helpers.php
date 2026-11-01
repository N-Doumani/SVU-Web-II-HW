<?php
/**
 * View helpers used by the templates (Section 5 — Styling).
 * Loaded automatically by includes/header.php.
 */

if (!function_exists('e')) {
    /** Escape any value before printing it in HTML (prevents XSS). */
    function e($value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('initials')) {
    /** "Ahmad Khalil" → "AK" (used when a student has no profile picture). */
    function initials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];
        $first = $parts[0] ?? '';
        $last  = count($parts) > 1 ? end($parts) : '';
        $text  = mb_substr($first, 0, 1) . mb_substr($last, 0, 1);
        return $text !== '' ? mb_strtoupper($text) : '?';
    }
}

if (!function_exists('avatar_tone')) {
    /** Pick one of 6 colour tones from the name, so each student keeps the same colour. */
    function avatar_tone(string $name): string
    {
        return 'tone-' . ((crc32($name) % 6) + 1);
    }
}

if (!function_exists('avatar_html')) {
    /**
     * Prints the student avatar: the uploaded picture if there is one,
     * otherwise a coloured circle with the initials.
     */
    function avatar_html(?string $picture, string $name, string $size = ''): string
    {
        $sizeClass = $size ? " avatar-{$size}" : '';
        if (!empty($picture)) {
            return '<img src="' . e($picture) . '" alt="' . e($name) . '" class="avatar' . $sizeClass . '" loading="lazy">';
        }
        return '<span class="avatar avatar-initials ' . avatar_tone($name) . $sizeClass . '" aria-hidden="true">'
             . e(initials($name)) . '</span>';
    }
}

/*
 * Server-side validation display.
 * Backend fills:  $errors = ['email' => 'This email is already used'];
 * The form template then marks that field red and prints the message.
 */
if (!function_exists('invalid_class')) {
    /** Returns " is-invalid" when the field has an error. */
    function invalid_class(array $errors, string $field): string
    {
        return isset($errors[$field]) ? ' is-invalid' : '';
    }
}

if (!function_exists('invalid_attr')) {
    /** Extra attributes so the red state disappears as soon as the user edits the field. */
    function invalid_attr(array $errors, string $field): string
    {
        return isset($errors[$field]) ? ' data-clear-on-input aria-invalid="true"' : '';
    }
}

if (!function_exists('error_text')) {
    /** The server message if there is one, otherwise the default hint. */
    function error_text(array $errors, string $field, string $default): string
    {
        return e($errors[$field] ?? $default);
    }
}
