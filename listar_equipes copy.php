  <?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "a");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Consulta com JOINs
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
  <title>GT Stats-Equipes </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="card.css" />
</head>
<?php include('navbar.php'); ?><br><br>

<body class="bg-light">
<div class="container py-5">
  <h1 class="mb-1 text-center">Equipes</h1>
  <div class="row justify-content-center">
  <?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <?php
          $foto_nome = $row['foto'];
          $foto_url = file_exists("uploads/$foto_nome") ? "uploads/$foto_nome" : "https://via.placeholder.com/360x200?text=Sem+Imagem";

        $pais = strtolower($row['pais']);
        $bandeira = "https://flagcdn.com/w40/{$pais}.png";

        $classe = strtoupper($row['classe']);
        $classe_css_map = [
           'GOLD' => ['box' => 'pos-box-gold', 'label' => 'gold'],
            'SILVER' => ['box' => 'pos-box-prata', 'label' => 'prata'],
            'BRONZE' => ['box' => 'pos-box-bronze', 'label' => 'bronze'],
            'PRO' => ['box' => 'pos-box-pro', 'label' => 'pro'],
            'PRO-AM' => ['box' => 'pos-box-pro-am', 'label' => 'pro-am'],
            'AM' => ['box' => 'pos-box-am', 'label' => 'am'],
            'SILVER-AM' => ['box' => 'pos-box-prata', 'label' => 'prata'],

    ];
;
        $css_classes = $classe_css_map[$classe] ?? ['box' => 'pos-box', 'label' => ''];
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
          <div class="<?= $css_classes['box'] ?>">
            <?= $row['numero'] ?>
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
    <div class="alert alert-info text-center">Nenhuma equipe cadastrada ainda.</div>
  <?php endif; ?>
  </div>
</div>
</body>
</html>
