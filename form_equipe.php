<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "a");
if ($conn->connect_error) {
  die("Erro de conexão: " . $conn->connect_error);
}

// Função para carregar opções
function carregarOpcoes($conn, $tabela, $campo) {
  $result = $conn->query("SELECT id, $campo AS nome FROM $tabela ORDER BY nome");
  $opcoes = "";
  while ($row = $result->fetch_assoc()) {
    $opcoes .= "<option value=\"{$row['id']}\">{$row['nome']}</option>\n";
  }
  return $opcoes;
}

// Carrega dinamicamente os <option>
$opcoes_campeonatos = carregarOpcoes($conn, "campeonatos", "nome");
$opcoes_modelos = carregarOpcoes($conn, "modelos", "nome");
$opcoes_classes = carregarOpcoes($conn, "classes", "nome");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>GT Stats - Cadastro</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include('navbar.php'); ?><br><br>

<div class="container py-5">
  <div class="card mb-5">
    <div class="card-body">
      <h2 class="card-title text-center mb-4">Entry List</h2>
      <form action="cadastro_equipe.php" method="POST" enctype="multipart/form-data">

        <div class="mb-3">
          <label for="nome" class="form-label">Nome da Equipe</label>
          <input type="text" name="nome" id="nome" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="numero" class="form-label">Número</label>
          <input type="number" name="numero" id="numero" class="form-control" maxlength="3" required>
        </div>

        <div class="mb-3">
          <label for="pais" class="form-label">País (sigla)</label>
          <input type="text" name="pais" id="pais" class="form-control" maxlength="2" required>
        </div>

        <div class="mb-3">
          <label for="foto" class="form-label">Foto da Equipe</label>
          <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
        </div>

        <div class="mb-3">
          <label class="form-label">Pilotos (até 5)</label>
          <div id="pilotos-container">
            <input type="text" name="pilotos[]" class="form-control mb-2" placeholder="Nome do Piloto 1" required>
          </div>
          <button type="button" class="btn btn-outline-secondary btn-sm" onclick="adicionarPiloto()">+ Adicionar Piloto</button>
          <button onclick="removerPiloto()" class="btn btn-outline-secondary btn-sm">- Remover Piloto</button>
        </div>

        <div class="mb-3">
          <label for="campeonato_id" class="form-label">Campeonato</label>
          <select name="campeonato_id" id="campeonato_id" class="form-control" required>
            <option value="" disabled selected>Selecione um campeonato</option>
            <?= $opcoes_campeonatos ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="modelo_id" class="form-label">Modelo do Carro</label>
          <select name="modelo_id" id="modelo_id" class="form-control" required>
            <option value="" disabled selected>Selecione um modelo</option>
            <?= $opcoes_modelos ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="classe_id" class="form-label">Classe</label>
          <select name="classe_id" id="classe_id" class="form-control" required>
            <option value="" disabled selected>Selecione uma classe</option>
            <?= $opcoes_classes ?>
          </select>
        </div>

        <button type="submit" class="btn btn-success w-100">Cadastrar Equipe</button>
      </form>
    </div>
  </div>
</div>

<script>
let pilotoCount = 1;
function adicionarPiloto() {
  if (pilotoCount >= 5) {
    alert("Máximo de 5 pilotos.");
    return;
  }
  pilotoCount++;
  const container = document.getElementById("pilotos-container");
  const input = document.createElement("input");
  input.type = "text";
  input.name = "pilotos[]";
  input.className = "form-control mb-2";
  input.placeholder = `Nome do Piloto ${pilotoCount}`;
  container.appendChild(input);
}

function removerPiloto() {
  if (pilotoCount <= 1) {
    alert("É necessário ter pelo menos um piloto.");
    return;
  }
  const container = document.getElementById("pilotos-container");
  const inputs = container.getElementsByTagName("input");
  container.removeChild(inputs[inputs.length - 1]);
  pilotoCount--;
}




</script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "a");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Consulta com JOINs (sem pilotos ainda)
$sql = "
SELECT 
  e.id,
  e.nome AS equipe_nome,
  e.numero,
  e.pais,
  e.foto,
  e.data_cadastro,
  c.nome AS campeonato,
  m.nome AS modelo,
  cl.nome AS classe
FROM equipes e
INNER JOIN campeonatos c ON e.campeonato_id = c.id
INNER JOIN modelos m ON e.modelo_id = m.id
INNER JOIN classes cl ON e.classe_id = cl.id
ORDER BY e.data_cadastro DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Equipes Cadastradas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="card.css" />
</head>
