<?php


/**
 * Check that a text value is within a character length range.
 *
 * @param  string $value   The text to validate.
 * @param  int    $min     Minimum character count (inclusive).
 * @param  int    $max     Maximum character count (inclusive).
 * @return bool   True if valid, false otherwise.
 */
function validateText(string $value, int $min = 1, int $max = 255): bool
{
    $len = mb_strlen(trim($value));
    return $len >= $min && $len <= $max;
}

/**
 * Check that a value is numeric and within a min/max range.
 *
 * @param  mixed     $value  The value to check.
 * @param  int|float $min    Minimum allowed value (inclusive).
 * @param  int|float $max    Maximum allowed value (inclusive).
 * @return bool  True if valid, false otherwise.
 */
function validateNumber($value, $min = 0, $max = PHP_INT_MAX): bool
{
    if (!is_numeric($value)) {
        return false;
    }
    $num = (float) $value;
    return $num >= $min && $num <= $max;
}

/**
 * Check that a selected option exists in a predefined array of allowed values.
 *
 * @param  string $value    The submitted option value.
 * @param  array  $allowed  Array of valid values.
 * @return bool   True if valid, false otherwise.
 */
function validateOption(string $value, array $allowed): bool
{
    return in_array($value, $allowed, true);
}