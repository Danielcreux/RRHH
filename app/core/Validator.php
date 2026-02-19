<?php
final class Validator {
  public static function email(string $v): bool {
    return (bool)filter_var($v, FILTER_VALIDATE_EMAIL);
  }

  public static function required(string $v): bool {
    return trim($v) !== '';
  }

  public static function date(?string $v): bool {
    if ($v === null || $v === '') return true;
    $d = DateTime::createFromFormat('Y-m-d', $v);
    return $d && $d->format('Y-m-d') === $v;
  }

  public static function int($v): bool {
    return filter_var($v, FILTER_VALIDATE_INT) !== false;
  }

  public static function money($v): bool {
    return is_numeric($v);
  }
}
