<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "a");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Consulta com JOINs e filtro por modelo_id = 2
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
WHERE e.modelo_id = 15
ORDER BY e.data_cadastro DESC
";

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
<?php include('navbar.php'); ?>
<br><br>
<br><br>
<br><br>


  <!-- Performance Card -->
    <div class="row">
      <div class="col-lg-12 mb-10">
        <div class="card">
          <div class="card-header bg-white">
            <h6 class="m-0 font-weight-bold text-primary">Estatística Básica </h6>
          </div>

          <div class="card-body">

  <!-- Peso Mínimo -->
  <div class="mb-1">
    <span class="progress-label">Peso Mínimo (kg)</span>
  </div>

  <!-- Barra de fundo: azul claro -->
  <div class="progress" style="background-color: #4cadff; height: 1rem; position: relative;">
 
  <div
    class="progress-bar"
    role="progressbar"
    aria-label="Peso Mínimo"
    aria-valuenow="20"
    aria-valuemin="0"
    aria-valuemax="100"
    style="background-color: #052c65; width: 20%; position: absolute; top: 0; left: 0; height: 100%; z-index: 1;"
  ></div>
</div>
<div class="mb-1">
  <span class="progress-label">Peso total (kg)</span>
</div>

<!-- Barra de fundo: azul claro -->
<div
  class="progress"
  style="background-color: #4cadff; height: 1rem; position: relative;"
>
  <!-- Barra de frente: azul escuro sobreposta -->
  <div
    class="progress-bar"
    role="progressbar"
    aria-label="Peso Mínimo"
    aria-valuenow="20"
    aria-valuemin="0"
    aria-valuemax="100"
    style="background-color: #052c65; width: 20%; position: absolute; top: 0; left: 0; height: 100%; z-index: 1;"
  ></div>
</div>

<div class="mb-1">
  <span class="progress-label">Restritor de ar (mm)</span>
</div>

<!-- Barra de fundo: azul claro -->
<div
  class="progress"
  style="background-color: #4cadff; height: 1rem; position: relative;"
>
  <!-- Barra de frente: azul escuro sobreposta -->
  <div
    class="progress-bar"
    role="progressbar"
    aria-label="Peso Mínimo"
    aria-valuenow="20"
    aria-valuemin="0"
    aria-valuemax="100"
    style="background-color: #052c65; width: 20%; position: absolute; top: 0; left: 0; height: 100%; z-index: 1;"
  ></div>
</div>

<div class="mb-1">
  <span class="progress-label">Altura Mínima da Dianteira (mm) </span>
</div>

<!-- Barra de fundo: azul claro -->
<div
  class="progress"
  style="background-color: #4cadff; height: 1rem; position: relative;">
  <!-- Barra de frente: azul escuro sobreposta -->
  <div
    class="progress-bar"
    role="progressbar"
    aria-label="Peso Mínimo"
    aria-valuenow="20"
    aria-valuemin="0"
    aria-valuemax="100"
    style="background-color: #052c65; width: 20%; position: absolute; top: 0; left: 0; height: 100%; z-index: 1;"
  ></div>
</div>

<div class="mb-1">
  <span class="progress-label">Altura Minima da Traseira (mm) </span>
</div>

<!-- Barra de fundo: azul claro -->
<div
  class="progress"
  style="background-color: #4cadff; height: 1rem; position: relative;">
  <!-- Barra de frente: azul escuro sobreposta -->
  <div
    class="progress-bar"
    role="progressbar"
    aria-label="Peso Mínimo"
    aria-valuenow="20"
    aria-valuemin="0"
    aria-valuemax="100"
    style="background-color: #052c65; width: 20%; position: absolute; top: 0; left: 0; height: 100%; z-index: 1;"
  ></div>
</div>
  </section>
<body class="bg-light">
<div class="container py-5">
  <legend class="mb-4 text-center">Equipes com Audi </legend>
  <div class="row justify-content-center">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <?php
          // Processa imagem da equipe
          $foto_nome = $row['foto'];
          $foto_url = file_exists("uploads/$foto_nome") ? "uploads/$foto_nome" : "https://via.placeholder.com/360x200?text=Sem+Imagem";

          // Processa bandeira do país
          $pais = strtolower($row['pais']);
          $bandeira = "https://flagcdn.com/w40/{$pais}.png";

          // Processa classe CSS
          $classe = strtoupper($row['classe']);
          $classe_css_map = [
            'GOLD' => ['box' => 'pos-box-gold', 'label' => 'gold'],
            'SILVER' => ['box' => 'pos-box-prata', 'label' => 'prata'],
            'BRONZE' => ['box' => 'pos-box-bronze', 'label' => 'bronze'],
            'PRO' => ['box' => 'pos-box-pro', 'label' => 'pro'],
            'PRO-AM' => ['box' => 'pos-box-pro-am', 'label' => 'pro-am'],
            'AM' => ['box' => 'pos-box-am', 'label' => 'am'],
          ];
          $css_classes = $classe_css_map[$classe] ?? ['box' => 'pos-box', 'label' => ''];

          // Busca pilotos da equipe
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

        <!-- Card GT3 -->
        <div class="card-gt3">
          <div class="card-header-custom">
            <div class="<?= $css_classes['box'] ?>">
              <?= htmlspecialchars($row['numero']) ?>
              <div class="<?= $css_classes['label'] ?>"><?= $classe ?></div>
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
      <div class="alert alert-info text-center">Nenhuma equipe cadastrada com modelo_id = 2.</div>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
