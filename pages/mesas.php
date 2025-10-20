<?php
$mesas = json_decode(file_get_contents(__DIR__ . '/../database/mesas.json'), true);

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Mapa de Mesas 🍽️</title>
    <link rel="stylesheet" href="../css/mesas.css">
</head>

<body>
    <div class="container">
        <h1>Mapa de Mesas 🍽️</h1>
        <?php if (isset($_GET['erro'])): ?>
            <p style="color: #b80000; background: #ffe5e5; padding: 10px; border-radius: 8px; text-align: center;">
                <?= htmlspecialchars($_GET['erro']) ?>
            </p>
        <?php elseif (isset($_GET['ok'])): ?>
            <p style="color: #145214; background: #d6f5d6; padding: 10px; border-radius: 8px; text-align: center;">
                <?= htmlspecialchars($_GET['ok']) ?>
            </p>
        <?php endif; ?>

        <!-- Legenda -->
        <div class="legenda">
            <span>
                <div class="cor livre"></div> Livre
            </span>
            <span>
                <div class="cor reservada"></div> Reservada
            </span>
            <span>
                <div class="cor ocupada"></div> Ocupada
            </span>
        </div>

        <!-- Mapa de Mesas -->
        <div class="mapa-mesas">
            <?php foreach ($mesas as $mesa): ?>
                <div class="mesa-container">
                    <div class="mesa <?= htmlspecialchars($mesa['status']) ?>" data-id="<?= $mesa['id'] ?>"
                        data-nome="<?= htmlspecialchars($mesa['nome']) ?>">
                    </div>
                    <p class="nome-mesa"><?= htmlspecialchars($mesa['nome']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Pop-up de reserva -->
    <div id="popup-reserva" class="popup">
        <div class="popup-content">
            <h2>Reservar Mesa</h2>
            <p id="mesaSelecionada"></p>
            <form id="formReserva" method="post" action="../src/mesa/reservar.php">
                <input type="hidden" name="mesa_id" id="mesa_id">
                <label>Nome do Cliente:</label>
                <input type="text" name="cliente" required>
                <div class="btns">
                    <button type="submit" class="btn reservar">Confirmar</button>
                    <button type="button" class="btn cancelar" onclick="fecharPopup()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../css/mesas.js"></script>
</body>

</html>