<?php
use App\Database;

// Conectar ao banco
$db = new Database();
$result = $db->query("SELECT * FROM plantas");
?>

<body class="catalogo">

    <?php require_once('./app/template/header.php'); ?>

    <h1 id="titulo">Catálogo</h1>

    <section class="plant-grid" id="plantas">

        <?php while ($row = $result->fetch_assoc()): ?>
            
            <?php
                // Nome comum
                $nome = $row['nome'];
                
                // Nome científico
                $nomeCientifico = $row['nome_cientifico'] ?? "";

                // Descrição
                $descricao = $row['descricao'] ?? "Sem descrição cadastrada.";

                // Gerar hash do nome para localizar a imagem (como seu sistema usa)
                $hash = md5($nome);
                $imgPath = "assets/img/plantas/{$hash}.jpg";

                // Se imagem não existir → imagem padrão
                if (!file_exists($imgPath)) {
                    $imgPath = "assets/img/default_plant.jpg";
                }
            ?>

            <!-- CARD DA PLANTA (com as mesmas classes e estrutura do HTML estático) -->
            <div class="plant-card" data-name="<?= htmlspecialchars($nome) ?>">

                <div class="plant-image" 
                     style="background-image: url('<?= $imgPath ?>')">
                </div>

                <div class="plant-info">
                    <h3 class="plant-name"><?= htmlspecialchars($nome) ?></h3>

                    <?php if (!empty($nomeCientifico)): ?>
                        <p class="plant-scientific"><?= htmlspecialchars($nomeCientifico) ?></p>
                    <?php endif; ?>

                    <p class="plant-description"><?= nl2br(htmlspecialchars($descricao)) ?></p>
                </div>

            </div>

        <?php endwhile; ?>

    </section>

	<?php require_once('./app/template/footer.php'); ?>
</body>
