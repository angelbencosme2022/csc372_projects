<?php
/**
 * Shared validation helpers for 401 Thrift.
 */

/**
 * Check that a text value is within a character-length range.
 */
function validateText(string $value, int $min = 1, int $max = 255): bool {
    $len = mb_strlen(trim($value));
    return $len >= $min && $len <= $max;
}

/**
 * Check that a value is numeric and within a min/max range.
 */
function validateNumber(mixed $value, int|float $min = 0, int|float $max = PHP_INT_MAX): bool {
    if (!is_numeric($value)) return false;
    $num = (float)$value;
    return $num >= $min && $num <= $max;
}

/**
 * Check that a selected option exists in a predefined array of allowed values.
 */
function validateOption(string $value, array $allowed): bool {
    return in_array($value, $allowed, true);
}
