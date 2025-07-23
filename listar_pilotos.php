<?php
// 1. Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "a");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$sql = "
  SELECT
    p.nome           AS piloto_nome,
    p.graduacao      AS graduacao,
    p.nacionalidade AS nacionalidade,
    e.id             AS equipe_id,
    e.nome           AS equipe_nome
  FROM pilotos p
  INNER JOIN equipes e
    ON p.equipe_id = e.id
  ORDER BY equipe_nome, piloto_nome
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Lista de Pilotos</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
  >
</head>
<body class="bg-light">
  <?php include('navbar.php'); ?>

  <div class="container py-5">
    <h2 class="mb-4 text-center">Lista de Pilotos Cadastrados</h2>

    <?php if ($result->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>Nome do Piloto</th>
              <th>Equipe</th>
              <th>nacionalidade</th>
              <th>graduacao</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
              <?php
                // 3. Monta a URL da bandeira dentro do loop
                $code     = strtolower($row['nacionalidade']);
                $bandeira = "https://flagcdn.com/w40/{$code}.png";
              ?>
              <tr>
                <td><?= htmlspecialchars($row['piloto_nome']) ?></td>
                <td><?= htmlspecialchars($row['equipe_nome']) ?></td>
                <td>
                  <img
                    src="<?= htmlspecialchars($bandeira) ?>"
                    alt="<?= htmlspecialchars(strtoupper($code)) ?>"
                    title="<?= strtoupper($code) ?>"
                    loading="lazy"
                  >
                </td>
                <td><?= htmlspecialchars($row['graduacao']) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info text-center">
        Nenhum piloto cadastrado ainda.
      </div>
    <?php endif; ?>
  </div>
</body>
</html>