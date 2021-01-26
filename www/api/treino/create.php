<?php
// Headers
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Methods: OPTIONS,POST');
header('Access-Control-Allow-Headers: *');
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == 'POST' || $_SERVER["REQUEST_METHOD"] == 'OPTIONS' ) {

  if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 ");
    exit;}

  include_once '../../config/Database.php';
  include_once '../../models/Treino.php';
  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate object
  $treino = new Treino($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  // Verify and set NAME to CREATE or exit with error
  if (!isset($data->descricao)) {
    echo json_encode(
      array(
        'message' => 'Parâmetro não encontrado',
        'code' => '9'
      )
    );
    exit;
  } else {
    $treino->aluno_id = $data->aluno_id;
    $treino->descricao = $data->descricao;
    $treino->ativo = $data->ativo;

    // Criar Treino
    if ($treino->create()) {
      echo json_encode(
        array(
          'message' => 'Treino criado',
          'code' => '1'
        )
      );
    } else {
      echo json_encode(
        array(
          'message' => 'Não foi possível criar Treino',
          'code' => '0'
        )
      );
    }
  }
} else {
  http_response_code(405);
}
