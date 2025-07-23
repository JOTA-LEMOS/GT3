<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GT Stats-forms</title>
  <link rel="shortcut icon" href="IMG/logo.jpg" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="navbar.css">
</head>
<body>
  <!-- NAVBAR -->
  <?php include('navbar.php'); ?>
<br><br><br><br>
<div class="container py-5">
  <div class="row g-4">
    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
      <div class="card h-100">
        <img src="IMG/BMW.png" class="card-img-top" alt="...">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Equipes</h5>
          <p class="card-text">
          informando o nome, o país de origem e a foto do carro; além disso, registre o modelo do veículo,
           o número de competição, os pilotos, a classe em que competem e o campeonato em que estão participando.
          </p>
          <a href="form_equipe.php " class="btn btn-primary mt-auto">cadastar</a>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
      <div class="card h-100">
        <img src="IMG/PILOTO.png" class="card-img-top" alt="...">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Piloto</h5>
          <p class="card-text">
            informando o nome, a nacionalidade, a graduação 
          </p>
          <a href="t.php" class="btn btn-primary mt-auto">cadastar</a>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
      <div class="card h-100">
        <img src="IMG/campeonatos.png" class="card-img-top" alt="...">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Campeonatos</h5>
          <p class="card-text">
            informando o nome do campeonato, a data de início e término, o número de rodadas e as pistas que serão utilizadas.
          </p>
          <a href="#" class="btn btn-primary mt-auto">Go somewhere</a>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
      <div class="card h-100">
        <img src="IMG/pistas.png " class="card-img-top" alt="...">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Pistas</h5>
          <p class="card-text">
            informando o nome da pista, a localização, o comprimento e o número de curvas.
          </p>
          <a href="#" class="btn btn-primary mt-auto">Go somewhere</a>
        </div>
      </div>
    </div>
  </div>
</div>
```

  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
