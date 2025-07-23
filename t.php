<?php
// Conexão com o banco (caso ainda não tenha sido feita antes deste trecho)
$conn = new mysqli("localhost", "root", "", "a");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Carregar opções de equipes para o select
function carregarEquipes(mysqli $conn): string {
    $sql = "SELECT id, nome, numero, pais FROM equipes ORDER BY nome";
    $result = $conn->query($sql);

    if (!$result) {
        return "<option value=\"\">Erro ao carregar equipes</option>";
    }

    $opcoes = "";
    while ($row = $result->fetch_assoc()) {
        $id     = htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8');
        $nome   = htmlspecialchars($row['nome'], ENT_QUOTES, 'UTF-8');
        $numero = htmlspecialchars($row['numero'], ENT_QUOTES, 'UTF-8');
        $pais   = htmlspecialchars($row['pais'], ENT_QUOTES, 'UTF-8');
        $label  = "$nome #$numero [$pais]";
        $opcoes .= "<option value=\"$id\">$label</option>\n";
    }

    return $opcoes;
}

// Carrega as opções de equipes
$opcoes_equipes = carregarEquipes($conn);

// Processar envio do formulário de piloto
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['form_piloto'])) {
    $nome          = $_POST['nome'];
    $nacionalidade = $_POST['nacionalidade'];
    $graduacao     = $_POST['graduacao'];
    $equipe_id     = (int)$_POST['equipe_id']; // Cast para int por segurança

    $stmt = $conn->prepare("INSERT INTO pilotos (nome, nacionalidade, graduacao, equipe_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $nome, $nacionalidade, $graduacao, $equipe_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success text-center'>✅ Piloto cadastrado com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>❌ Erro ao cadastrar piloto: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
?>

<div class="container py-5">
  <div class="card mb-5">
    <div class="card-body">
      <h2 class="card-title text-center mb-4">Cadastro de Piloto</h2>
      <form method="POST" action="" class="needs-validation" novalidate>
        <input type="hidden" name="form_piloto" value="1">

        <div class="mb-3">
          <label for="nome" class="form-label">Nome do Piloto</label>
          <input type="text" name="nome" id="nome" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="nacionalidade" class="form-label">Nacionalidade (sigla)</label>
          <input type="text" name="nacionalidade" id="nacionalidade" class="form-control" maxlength="2" required>
        </div>

        <div class="mb-3">
          <label for="graduacao" class="form-label">Graduação</label>
          <select name="graduacao" id="graduacao" class="form-control" required>
            <option value="" disabled selected>Selecione a graduação</option>
            <option value="Platinum">Platinum</option>
            <option value="Gold">Gold</option>
            <option value="Silver">Silver</option>
            <option value="Bronze">Bronze</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="equipe_id" class="form-label">Equipe</label>
          <select name="equipe_id" id="equipe_id" class="form-control" required>
            <option value="" disabled selected>Selecione uma equipe</option>
            <?= $opcoes_equipes ?>
          </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">Cadastrar Piloto</button>
      </form>
    </div>
  </div>
</div>

