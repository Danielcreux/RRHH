<?php
final class Ollama {
  public static function generate(string $prompt): string {
    $cfg = require __DIR__ . '/../config/config.php';
    $endpoint = $cfg['ollama_endpoint'] ?? '';
    $model = $cfg['ollama_model'] ?? '';

    if ($endpoint === '' || $model === '') return "Configuración de IA no definida.";

    $data = ["model"=>$model, "prompt"=>$prompt, "stream"=>false];

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    if (curl_errno($ch)) return "Error Ollama: " . curl_error($ch);
    curl_close($ch);

    $decoded = json_decode($response, true);
    return $decoded["response"] ?? "Error procesando respuesta de Ollama";
  }
}
