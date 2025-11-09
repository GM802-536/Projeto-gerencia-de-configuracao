<?php
$mesas = json_decode(file_get_contents(__DIR__ . '/../../database/mesas.json'), true);

$dataSelecionada = $_GET['data'] ?? date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Mapa de Mesas 🍽️</title>
    <link rel="stylesheet" href="/css/mesas.css">
</head>

<body>
    <div class="container">
        <h1>Mapa de Mesas 🍽️</h1>

        <form method="get" action="/pages/menu-sections/mesas.php">
            <div class="calendario-container">
                <label for="dataReserva">Escolha o dia da reserva:</label>
                <input type="date" id="dataReserva" name="data" value="<?= htmlspecialchars($dataSelecionada) ?>"
                    required onchange="this.form.submit()">
            </div>
        </form>

        <?php if (isset($_GET['erro'])): ?>
            <p style="color: #b80000; background: #ffe5e5; padding: 10px; border-radius: 8px; text-align: center;">
                <?= htmlspecialchars($_GET['erro']) ?>
            </p>
        <?php elseif (isset($_GET['ok'])): ?>
            <p style="color: #145214; background: #d6f5d6; padding: 10px; border-radius: 8px; text-align: center;">
                <?= htmlspecialchars($_GET['ok']) ?>
            </p>
        <?php endif; ?>

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

        <div class="mapa-mesas">
            <?php foreach ($mesas as $mesa): ?>
                <?php
                $status = 'livre';
                if ($dataSelecionada && isset($mesa['reservas'])) {
                    foreach ($mesa['reservas'] as $reserva) {
                        if ($reserva['data_reserva'] === $dataSelecionada) {
                            $status = $reserva['status'];
                            break;
                        }
                    }
                }

                ?>
                <div class="mesa-container">
                    <div class="mesa <?= htmlspecialchars($status) ?>" data-id="<?= $mesa['id'] ?>"
                        data-nome="<?= htmlspecialchars($mesa['nome']) ?>">
                    </div>
                    <p class="nome-mesa"><?= htmlspecialchars($mesa['nome']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="popup-reserva" class="popup">
        <div class="popup-content">
            <h2>Reservar Mesa</h2>
            <p id="mesaSelecionada"></p>
            <form id="formReserva" method="post" action="/src/mesa/reservar.php">
                <input type="hidden" name="mesa_id" id="mesa_id">
                <input type="hidden" name="data" id="data_escolhida">

                <label>Nome do Cliente:</label>
                <input type="text" name="cliente" required>
                <div class="btns">
                    <button type="submit" class="btn reservar">Confirmar</button>
                    <button type="button" class="btn cancelar" onclick="fecharPopup()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const dataInput = document.getElementById("dataReserva");
        const dataHidden = document.getElementById("data_escolhida");
        const mesaIdInput = document.getElementById("mesa_id");
        const popup = document.getElementById("popup-reserva");
        const mesaSelecionada = document.getElementById("mesaSelecionada");

        dataInput.addEventListener("change", () => {
            dataHidden.value = dataInput.value;
        });

        document.querySelectorAll(".mesa").forEach(mesa => {
            mesa.addEventListener("click", () => {
                if (!dataInput.value) {
                    alert("Por favor, escolha uma data antes de reservar uma mesa!");
                    return;
                }

                const id = mesa.getAttribute("data-id");
                const nome = mesa.getAttribute("data-nome");

                mesaIdInput.value = id;
                dataHidden.value = dataInput.value;
                mesaSelecionada.textContent = nome;

                popup.style.display = "flex";
            });
        });

        function fecharPopup() {
            popup.style.display = "none";
        }
    </script>
</body>

</html>