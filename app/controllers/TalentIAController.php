<?php
final class TalentIAController extends Controller {

  public function index(): void {
    RoleMiddleware::require(['super_admin','rrhh']);

    $rows = (new TalentiaCandidato())->all();

    $this->view('talentia/index', [
      'rows' => $rows,
      'csrf' => Csrf::token(),
    ]);
  }

  // Igual que buscar.php en tu CV_IA :contentReference[oaicite:2]{index=2}
  public function buscar(): void {
    RoleMiddleware::require(['super_admin','rrhh']);
    Csrf::check($_POST['csrf'] ?? '');

    $consulta = trim($_POST['consulta'] ?? '');
    if ($consulta === '') {
      $this->redirect('?r=talentia/index');
    }

    $m = new TalentiaCandidato();
    $cvsJson = $m->allAsJson(); // imita el cv_storage.json en JSON

    $prompt = "
Actúa como Director de Recursos Humanos.

Analiza los siguientes CV en formato JSON:

$cvsJson

Consulta del reclutador:
$consulta

Devuelve la respuesta en el siguiente formato EXACTO:

RESUMEN EJECUTIVO:
(3-5 líneas con visión general del perfil ideal encontrado)

CANDIDATOS RECOMENDADOS:

1. Nombre:
Edad:
Experiencia:
Puesto actual:
Nivel de inglés:
Skills clave:
Justificación:

No agregues texto adicional.
No uses markdown.
";

    $respuesta = $this->llamarIA($prompt);
    $respuesta = trim($respuesta);
    $respuesta = preg_replace('/\*\*|##|```/', '', $respuesta);

    $this->view('talentia/report', [
      'consulta' => $consulta,
      'respuesta' => $respuesta,
      'csrf' => Csrf::token(),
    ]);
  }

  

  
  public function subir(): void {
  
  RoleMiddleware::require(['super_admin','rrhh']);


  if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    $this->redirect('?r=talentia/index');
  }

  Csrf::check($_POST['csrf'] ?? '');

  if (!isset($_FILES['cv_file']) || $_FILES['cv_file']['error'] !== UPLOAD_ERR_OK) {
    $this->view('talentia/index', [
      'rows' => (new TalentiaCandidato())->all(),
      'csrf' => Csrf::token(),
      'error' => 'No se recibió el archivo PDF (cv_file).'
    ]);
    return;
  }

  try {
    $pdfRelPath = $this->savePdf($_FILES['cv_file']);
    error_log("PDF guardado en: " . $pdfRelPath);
  } catch (Throwable $e) {
    $this->view('talentia/index', [
      'rows' => (new TalentiaCandidato())->all(),
      'csrf' => Csrf::token(),
      'error' => 'Error guardando PDF: ' . $e->getMessage()
    ]);
    return;
  }

  // Texto del PDF (si falla, seguimos)
  $pdfAbsPath = __DIR__ . '/../../public' . $pdfRelPath;
  $text = '';
  try {
    $text = $this->extractPdfText($pdfAbsPath);
  } catch (Throwable $e) {
    error_log("extractPdfText falló: " . $e->getMessage());
    $text = '';
  }

  $data = [
    'archivo_pdf' => $pdfRelPath,
    'cv_text' => $text,

    'nombre' => $text ? $this->extraerNombre($text) : '',
    'email' => $text ? $this->extraerEmail($text) : '',
    'telefono' => $text ? $this->extraerTelefono($text) : '',
    'edad' => $text ? $this->extraerEdad($text) : '',
    'experiencia_anios' => $text ? $this->extraerExperiencia($text) : '',
    'skills' => $text ? implode(', ', $this->extraerSkills($text)) : '',
    'idiomas' => $text ? implode(', ', $this->extraerIdiomas($text)) : '',
    'nivel_ingles' => $text ? ($this->extraerNivelIngles($text) ?? '') : '',
    'puesto_actual' => $text ? ($this->extraerPuestoActual($text) ?? '') : '',
  ];

  // IMPORTANTÍSIMO: pasar data bajo la clave 'data'
  $this->view('talentia/editor', [
    'data' => $data,
    'csrf' => Csrf::token()
  ]);
}


  // Igual que guardar_cv.php pero guardando en MySQL :contentReference[oaicite:5]{index=5}
  public function guardar(): void {
    RoleMiddleware::require(['super_admin','rrhh']);
    Csrf::check($_POST['csrf'] ?? '');

    $nombre = trim($_POST['nombre'] ?? '');
    if ($nombre === '') {
      $this->redirect('?r=talentia/index');
    }

    (new TalentiaCandidato())->create([
      'nombre' => $nombre,
      'email' => trim($_POST['email'] ?? '') ?: null,
      'telefono' => trim($_POST['telefono'] ?? '') ?: null,
      'edad' => ($_POST['edad'] ?? '') !== '' ? (int)$_POST['edad'] : null,
      'experiencia_anios' => ($_POST['experiencia_anios'] ?? '') !== '' ? (int)$_POST['experiencia_anios'] : null,
      'skills' => trim($_POST['skills'] ?? '') ?: null,
      'idiomas' => trim($_POST['idiomas'] ?? '') ?: null,
      'nivel_ingles' => trim($_POST['nivel_ingles'] ?? '') ?: null,
      'puesto_actual' => trim($_POST['puesto_actual'] ?? '') ?: null,
      'archivo_pdf' => trim($_POST['archivo_pdf'] ?? '') ?: null,
      'cv_text' => trim($_POST['cv_text'] ?? '') ?: null,
      'creado_por_user_id' => Auth::id(),
    ]);

    $this->redirect('?r=talentia/index');
  }

  // Igual que eliminar_cv.php pero por id (MySQL) :contentReference[oaicite:6]{index=6}
  public function eliminar(): void {
    RoleMiddleware::require(['super_admin','rrhh']);
    Csrf::check($_POST['csrf'] ?? '');

    $id = (int)($_POST['id'] ?? 0);
    if (!$id) $this->redirect('?r=talentia/index');

    $m = new TalentiaCandidato();
    $row = $m->find($id);

    if ($row && !empty($row['archivo_pdf'])) {
      $abs = __DIR__ . '/../../public' . $row['archivo_pdf'];
      if (is_file($abs)) @unlink($abs);
    }

    $m->delete($id);
    $this->redirect('?r=talentia/index');
  }

  /* ===================== PDF / IA ===================== */

  private function savePdf(array $file): string {
  $ext = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
  if ($ext !== 'pdf') throw new Exception("El archivo no es PDF.");

  $dir = __DIR__ . '/../../public/uploads/talentia';
  if (!is_dir($dir) && !mkdir($dir, 0777, true)) {
    throw new Exception("No se pudo crear carpeta: $dir");
  }
  if (!is_writable($dir)) {
    throw new Exception("Sin permisos de escritura: $dir");
  }

  $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($file['name'] ?? 'cv.pdf'));
  $destAbs  = $dir . '/' . $safeName;

  if (!move_uploaded_file($file['tmp_name'], $destAbs)) {
    throw new Exception("move_uploaded_file falló a $destAbs");
  }

  return '/uploads/talentia/' . $safeName;
}



  private function extractPdfText(string $absPdfPath): string {
    // requiere composer smalot/pdfparser
    $parser = new \Smalot\PdfParser\Parser();
    $pdf = $parser->parseFile($absPdfPath);
    $text = $pdf->getText();

    $text = preg_replace('/\r/', "\n", $text);
    $text = preg_replace('/\n+/', "\n", $text);
    $text = preg_replace('/\t+/', ' ', $text);
    $text = preg_replace('/ +/', ' ', $text);
    return trim($text);
  }

  private function llamarIA(string $prompt): string {
    $cfg = require __DIR__ . '/../config/config.php';
    $endpoint = $cfg['ollama_endpoint'] ?? 'http://localhost:11434/api/generate';
    $model = $cfg['ollama_model'] ?? 'qwen3-coder:480b-cloud';

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

  /* ===================== Extractores (de tu subir_cv.php) ===================== */

  private function extraerNombre(string $text): string {
    $lineas = explode("\n", $text);
    $blacklist = ["PERFIL","CONTACTO","EXPERIENCIA","EDUCACION","HISTORIAL","CURSOS","CONOCIMIENTOS","RESUMEN","OBJETIVO","DATOS PERSONALES"];

    foreach (array_slice($lineas, 0, 12) as $linea) {
      $linea = trim($linea);
      if (mb_strlen($linea) < 6 || mb_strlen($linea) > 45) continue;
      if (in_array(mb_strtoupper($linea), $blacklist, true)) continue;

      if (preg_match('/^[A-ZÁÉÍÓÚÑ ]+$/u', $linea)) {
        $palabras = array_filter(explode(" ", $linea));
        if (count($palabras) >= 2 && count($palabras) <= 4) return ucwords(mb_strtolower($linea));
      }

      if (preg_match('/^[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+(\s+[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+){1,3}$/u', $linea)) {
        return $linea;
      }
    }
    return "No detectado";
  }

  private function extraerEmail(string $text): string {
    preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i', $text, $m);
    return $m[0] ?? "";
  }

  private function extraerTelefono(string $text): string {
    preg_match('/\+?\d[\d\s\-]{7,15}/', $text, $m);
    return $m[0] ?? "";
  }

  private function extraerEdad(string $text): ?int {
    if (preg_match('/edad\s*[:\-]?\s*(\d{2})/i', $text, $m)) {
      $edad = (int)$m[1];
      if ($edad >= 18 && $edad <= 70) return $edad;
    }
    if (preg_match('/\b(\d{2})\s*años?\b/i', $text, $m)) {
      $edad = (int)$m[1];
      if ($edad >= 18 && $edad <= 70) return $edad;
    }
    if (preg_match('/(19\d{2}|20\d{2})/', $text, $m)) {
      $anio = (int)$m[1];
      $edad = (int)date("Y") - $anio;
      if ($edad >= 18 && $edad <= 70) return $edad;
    }
    return null;
  }

  private function extraerExperiencia(string $text): int {
    preg_match_all('/(\+?\d+)\s*años?(?:\s+de\s+experiencia)?/i', $text, $matches);
    if (!empty($matches[1])) {
      $vals = array_map(fn($v) => (int)str_replace("+","",$v), $matches[1]);
      return max($vals);
    }
    return 0;
  }

  private function extraerSkills(string $text): array {
    $dic = ["PHP","Python","Java","SQL","React","Node.js","Docker","AWS","Laravel","Django","TensorFlow","Power BI","Excel","Marketing","SEO","Figma","HTML","CSS","JavaScript","TypeScript","Kubernetes","Spring Boot","Microservices","C++","C#","Angular","Vue","MongoDB","PostgreSQL","MySQL","Git","Linux"];
    $out = [];
    foreach ($dic as $s) {
      if (preg_match('/\b'.preg_quote($s,'/').'\b/i', $text)) $out[] = $s;
    }
    return array_values(array_unique($out));
  }

  private function extraerIdiomas(string $text): array {
    $idiomas = [];
    $map = ["ingl[eé]s"=>"Inglés","español"=>"Español","franc[eé]s"=>"Francés","alem[aá]n"=>"Alemán"];

    foreach ($map as $rx => $nombre) {
      if (preg_match('/'.$rx.'/i', $text)) {
        $nivel = null;
        if (preg_match('/'.$rx.'.{0,40}(A1|A2|B1|B2|C1|C2)/i', $text, $m)) $nivel = strtoupper($m[1]);
        if (!$nivel && preg_match('/(A1|A2|B1|B2|C1|C2).{0,40}'.$rx.'/i', $text, $m)) $nivel = strtoupper($m[1]);
        $idiomas[] = $nivel ? "$nombre ($nivel)" : $nombre;
      }
    }
    return $idiomas;
  }

  private function extraerNivelIngles(string $text): ?string {
    if (preg_match('/\b(A1|A2|B1|B2|C1|C2)\b/i', $text, $m)) return strtoupper($m[1]);
    return null;
  }

  private function extraerPuestoActual(string $text): ?string {
    $lineas = explode("\n", $text);
    $en = false;

    foreach ($lineas as $linea) {
      $linea = trim($linea);

      if (preg_match('/experiencia laboral/i', $linea)) {
        $en = true;
        continue;
      }

      if ($en) {
        if (mb_strlen($linea) < 5) continue;
        if (preg_match('/\d{4}/', $linea)) continue;
        if (mb_strlen($linea) > 60) continue;
        if (preg_match('/universidad|bachiller|curso|educacion/i', $linea)) continue;
        return $linea;
      }
    }
    return null;
  }
}
