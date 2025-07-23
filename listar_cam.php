<?php
$conn = new mysqli("localhost", "root", "", "a");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Filtros selecionados
$filtro_campeonato = $_GET['campeonato'] ?? '';
$filtro_modelo = $_GET['modelo'] ?? '';
$filtro_classe = $_GET['classe'] ?? '';

// Combos para filtros
$campeonatos = $conn->query("SELECT id, nome FROM campeonatos ORDER BY nome");
$modelos = $conn->query("SELECT id, nome FROM modelos ORDER BY nome");
$classes = $conn->query("SELECT id, nome FROM classes ORDER BY nome");

// Monta consulta com filtros
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
WHERE 1=1
";

if (!empty($filtro_campeonato)) {
    $sql .= " AND c.id = " . intval($filtro_campeonato);
}
if (!empty($filtro_modelo)) {
    $sql .= " AND m.id = " . intval($filtro_modelo);
}
if (!empty($filtro_classe)) {
    $sql .= " AND cl.id = " . intval($filtro_classe);
}

$sql .= " ORDER BY e.numero ASC";

$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>GT Stats - Equipes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="card.css" />
</head>
<?php include('navbar.php'); ?><br><br>

<body class="bg-light">
<div class="container py-4">
  <h1 class="mb-4 text-center">Equipes</h1>

  <!-- Formulário de Filtro -->
<form method="GET" class="row g-3 mb-4 justify-content-center">
  <div class="col-md-3">
    <select name="campeonato" class="form-select">
      <option value="">Todos os Campeonatos</option>
      <?php while ($c = $campeonatos->fetch_assoc()): ?>
        <option value="<?= $c['id'] ?>" <?= $filtro_campeonato == $c['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($c['nome']) ?>
        </option>
      <?php endwhile; ?>
    </select>
  </div>
  <div class="col-md-3">
    <select name="modelo" class="form-select">
      <option value="">Todos os Modelos</option>
      <?php while ($m = $modelos->fetch_assoc()): ?>
        <option value="<?= $m['id'] ?>" <?= $filtro_modelo == $m['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($m['nome']) ?>
        </option>
      <?php endwhile; ?>
    </select>
  </div>
  <div class="col-md-3">
    <select name="classe" class="form-select">
      <option value="">Todas as Classes</option>
      <?php while ($cl = $classes->fetch_assoc()): ?>
        <option value="<?= $cl['id'] ?>" <?= $filtro_classe == $cl['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($cl['nome']) ?>
        </option>
      <?php endwhile; ?>
    </select>
  </div>
  <div class="col-md-2">
    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
  </div>
</form>

  <!-- Cards -->
  <div class="row justify-content-center">
  <?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <?php
        $foto_nome = trim($row['foto']);
        $caminho_foto = __DIR__ . "/uploads/$foto_nome";
        $foto_url = (!empty($foto_nome) && file_exists($caminho_foto) && is_file($caminho_foto))
                    ? "uploads/$foto_nome"
                    : "https://via.placeholder.com/360x200?text=Sem+Imagem";
        $pais = strtolower($row['pais']);
        $bandeira = "https://flagcdn.com/w40/{$pais}.png";
$classe = strtoupper(str_replace(' ','-',$row['classe']));
$classe_css_map = [
    'GOLD' => ['box' => 'pos-box-GOLD', 'label' => 'GOLD'],
    'SILVER' => ['box' => 'pos-box-SILVER', 'label' => 'SILVER'],
    'SILVER-AM' => ['box' => 'pos-box-SILVER-AM', 'label' => 'SILVER-AM'],
    'BRONZE' => ['box' => 'pos-box-BRONZE', 'label' => 'BRONZE'],
    'PRO' => ['box' => 'pos-box-PRO', 'label' => 'PRO'],
    'PRO-AM' => ['box' => 'pos-box-PRO-AM', 'label' => 'PRO-AM'],
    'AM' => ['box' => 'pos-box-AM', 'label' => 'AM'],
    'GTD' => ['box' => 'pos-box-GTD', 'label' => 'GTD'],
    'GTD-PRO' => ['box' => 'pos-box-GTD-PRO', 'label' => 'GTD-PRO'],
    'LMGT3' => ['box' => 'pos-box-LMGT3', 'label' => 'LMGT3'],

];

$classe_key = strtoupper(str_replace(' ', '-', $row['classe']));
$classe_info = $classe_css_map[$classe_key] ?? ['box' => 'pos-box', 'label' => strtolower($classe_key)];
$classe_label = $classe_info['label'];
$classe_box = $classe_info['box'];
        

        $pilotos = [];
        $equipe_id = $row['id'];
        $sql_pilotos = "SELECT nome FROM pilotos WHERE equipe_id = $equipe_id";
        $res_pilotos = $conn->query($sql_pilotos);
        if ($res_pilotos && $res_pilotos->num_rows > 0) {
          while ($piloto = $res_pilotos->fetch_assoc()) {
            $pilotos[] = $piloto['nome'];
          }
        }
      ?>

      <div class="card-gt3">
        <div class="card-header-custom">
          <div class="<?= $classe_box ?>">
            <?= $row['numero'] ?>
            <div class="<?= $classe_label ?>"><?= $classe_label ?></div>
          </div>
          <div class="team-name"><?= htmlspecialchars($row['equipe_nome']) ?></div>
        </div>

        <div class="car-model-bar">
          <img src="<?= $bandeira ?>" alt="<?= htmlspecialchars($row['pais']) ?>">
          <?= htmlspecialchars($row['modelo']) ?>
        </div>

        <img src="<?= $foto_url ?>" class="car-image" alt="<?= htmlspecialchars($row['modelo']) ?>">

        <div class="driver-names">
          <?= !empty($pilotos) ? implode('<br>', array_map('htmlspecialchars', $pilotos)) : 'Sem pilotos cadastrados' ?>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="alert alert-info text-center">Nenhuma equipe encontrada com os filtros selecionados.</div>
  <?php endif; ?>
  </div>

</div>
</body>
</html>
