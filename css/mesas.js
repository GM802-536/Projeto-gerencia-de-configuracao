document.addEventListener('DOMContentLoaded', () => {
    const mesas = document.querySelectorAll('.mesa');

    mesas.forEach(mesa => {
        mesa.addEventListener('click', () => {
            const status = mesa.classList.contains('livre')
                ? 'livre'
                : mesa.classList.contains('reservada')
                ? 'reservada'
                : 'ocupada';

            if (status === 'livre') {
                const mesaId = mesa.dataset.id;
                const mesaNome = mesa.dataset.nome;

                document.getElementById('mesa_id').value = mesaId;
                document.getElementById('mesaSelecionada').innerText = `Você está reservando a ${mesaNome}`;
                document.getElementById('popup-reserva').style.display = 'flex';
            } else if (status === 'reservada') {
                alert('Esta mesa já está reservada!');
            } else if (status === 'ocupada') {
                alert('Esta mesa está ocupada!');
            }
        });
    });
});

function fecharPopup() {
    document.getElementById('popup-reserva').style.display = 'none';
}
