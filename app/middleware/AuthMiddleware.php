<?php
final class AuthMiddleware {
  public static function require(): void {
    Auth::requireLogin();
  }
}
