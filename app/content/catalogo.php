<?php
use App\Database;

// Conectar ao banco
$db = new Database();
$result = $db->query("SELECT * FROM plantas");
?>

<body class="catalogo">

    <?php require_once('./app/template/header.php'); ?>

    <h1 id="titulo">Catálogo</h1>

    <section class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
			<?php while ($row = $result->fetch_assoc()): ?>

				<?php 
					// Nome da planta
					$nomePlanta = $row['nome'];

					// Gera hash MD5 a partir do nome
					$hash = md5($nomePlanta);

					// Caminho da imagem
					$imgPath = "assets/img/plantas/{$hash}.jpg";

					if (!file_exists($imgPath)) {
						$imgPath = "assets/img/default_plant.jpg";
					}
				?>
				<div class="col">
					<div class="card h-100 text-center">

						<img src="<?= $imgPath ?>" class="plant-image">
						<div class="plant-name"><?= htmlspecialchars($nomePlanta); ?></div>

							<?php if (!empty($row['nome_cientifico'])): ?>
							<p class="text-muted" style="font-size: 0.75rem;">
								<?= htmlspecialchars($row['nome_cientifico']); ?>
							</p>
							<?php endif; ?>
						</div>

					</div>
				</div>

			<?php endwhile; ?>

    </div>
</section>