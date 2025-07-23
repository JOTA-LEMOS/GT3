<?php
$conn = new mysqli("localhost", "root", "", "a");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Filtros selecionados
$filtro_campeonato = $_GET['campeonato'] ?? '';
$filtro_modelo = $_GET['modelo'] ?? '';
$filtro_classe = $_GET['classe'] ?? '';

// Monta consulta com JOINs e filtros
$sql = "
SELECT pistas.*
FROM pistas
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

// Executa a consulta final
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>GT Stats - Pistas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="card.css" />
</head>
<?php include('navbar.php'); ?><br><br><br>

<body class="bg-light">
<div class="container py-4">
  <h1 class="mb-4 text-center">Pistas</h1>
  <table class="table table-bordered table-striped">
    <thead>
      <tr class="table-primary">
        <th>Nome</th>
        <th>País</th> 
        <th>Km</th>
        <th>Curvas</th>
        <th>Tipo</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <?php
          $code = strtolower($row['pais']); // Ex: 'br', 'us', 'jp'
          $bandeira = "https://flagcdn.com/w40/{$code}.png";
        ?>
        <tr>
          <td><?= htmlspecialchars($row['nome']) ?></td>
          <td>
            <img
              src="<?= htmlspecialchars($bandeira) ?>"
              alt="<?= htmlspecialchars(strtoupper($code)) ?>"
              title="<?= strtoupper($code) ?>"
              loading="lazy"
            >
          </td>
          <td><?= number_format($row['comprimento_km'], 3, ',', '.') ?> km</td>
          <td><?= htmlspecialchars($row['numero_curvas']) ?></td>
          <td><?= htmlspecialchars($row['tipo_circuito']) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
