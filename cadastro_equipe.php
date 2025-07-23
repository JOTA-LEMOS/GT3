<?php
// Conexão com o banco
$conn = new mysqli("localhost", "root", "", "a");
if ($conn->connect_error) {
  die("Erro na conexão: " . $conn->connect_error);
}

// Dados do formulário
$nome = $_POST['nome'];
$numero = $_POST['numero'];
$pais = strtoupper($_POST['pais']);
$campeonato_id = $_POST['campeonato_id'];
$modelo_id = $_POST['modelo_id'];
$classe_id = $_POST['classe_id'];
$pilotos = $_POST['pilotos'] ?? [];

// Upload da foto
$foto_nome = null;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $foto_nome = uniqid() . '.' . $extensao;
    move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads/' . $foto_nome);
}

// Inserir equipe
$stmt = $conn->prepare("INSERT INTO equipes (nome, numero, pais, foto, campeonato_id, modelo_id, classe_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sissiii", $nome, $numero, $pais, $foto_nome, $campeonato_id, $modelo_id, $classe_id);
$stmt->execute();

$equipe_id = $stmt->insert_id;
$stmt->close();

// Inserir pilotos (até 5)
if ($equipe_id && !empty($pilotos)) {
    $stmt = $conn->prepare("INSERT INTO pilotos (nome, equipe_id) VALUES (?, ?)");
    foreach ($pilotos as $piloto) {
        if (!empty(trim($piloto))) {
            $piloto_nome = trim($piloto);
            $stmt->bind_param("si", $piloto_nome, $equipe_id);
            $stmt->execute();
        }
    }
    $stmt->close();
}

// Redirecionar ou mensagem
 echo "<script>
            alert('Equipe cadastrada com sucesso!');
            window.location.href = 'form_equipe.php';
          </script>";
exit;
?>
