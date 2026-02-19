<?php
return [
  'app_name' => 'Portal RRHH',
  'base_url' => '/RRHH/public', 
  'timezone' => 'Europe/Madrid',

  'db_host' => '127.0.0.1',
  'db_name' => 'rrhh',
  'db_user' => 'danielcreux',
  'db_pass' => 'danielcreux',
  
  'ollama_endpoint' => 'http://localhost:11434/api/generate',
  'ollama_model' => 'qwen3-coder:480b-cloud',
  
  // Seguridad
  'session_name' => 'HRSESSID',
  'session_secure' => false, // true si HTTPS
  'session_samesite' => 'Lax', // 'Strict' si quieres más duro
];
