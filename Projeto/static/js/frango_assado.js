
async function realizarPedido(event) {
    event.preventDefault();

    const button = event.currentTarget.querySelector('button[type="submit"]');
    const buttonHtml = button.innerHTML;
    button.innerHTML = 'Realizando pedido...';
    button.disabled = true;

    try {
        const dadosForm = new FormData(this);
        const resposta = await fetch('api.php?route=frango_assado/novo_pedido', {
            method: 'POST',
            body: dadosForm
        });

        const json = await resposta.json();
        if (json.status === 'ok') {
            bootstrap.Modal.getOrCreateInstance('#modalPedidoRealizado').show()
            this.reset();
        } else {
            bootstrap.Modal.getOrCreateInstance('#modalErroPedido').show()
        }
    } catch (e) {
        console.error(e);
        bootstrap.Modal.getOrCreateInstance('#modalErroPedido').show()
    } finally {
        button.innerHTML = buttonHtml;
        button.disabled = false;
    }
}


document.addEventListener('DOMContentLoaded', function() {
    const formPedido = document.getElementById('formPedido')
    formPedido.addEventListener('submit', realizarPedido);
})