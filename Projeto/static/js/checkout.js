async function confirmarPedido(event) {    
    event.preventDefault();

    const form = event.currentTarget;
    const inputEndereco = form.querySelector('#endereco');
    const inputDataEntrega = form.querySelector('#dataEntrega');

    inputEndereco.setCustomValidity('');
    inputDataEntrega.setCustomValidity('');

    if (!form.querySelector('#semEntrega').checked) {
        if (inputEndereco.value.trim() === '') {
            inputEndereco.setCustomValidity('Por favor, informe o endereço de entrega.');
        }

        if (inputDataEntrega.value.trim() === '') {
            inputDataEntrega.setCustomValidity('Por favor, informe a data de entrega.');
        }
    }

    if (!form.checkValidity()) {
        // Se o formulário não for válido, exibe a mensagem de erro de validação
        form.reportValidity();  // Isso irá acionar o feedback de validação embutido no navegador
        return;  // Interrompe a execução da função
    }
    
    const button = form.querySelector('[type="submit"]');
    button.setAttribute('disabled', true);

    try {
        const dadosForm = new FormData(form);
        const resposta = await fetch('api.php?route=pedido/confirmar', {
            method: 'POST',
            body: dadosForm
        });

        const json = await resposta.json();
        if (json.status === 'ok') {
            window.location.replace(json.dados.redirecionar_url);
        } else {
            Alerta.erro('#alertaCheckout', json.mensagem || 'Erro ao confirmar pedido.');
        }
    } catch (e) {
        console.error(e);
        Alerta.erro('#alertaCheckout', 'Erro ao confirmar pedido.');
    } finally {
        button.removeAttribute('disabled');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('#endereco').addEventListener('input', function() {
        this.setCustomValidity('');
    });
    document.querySelector('#dataEntrega').addEventListener('input', function() {
        this.setCustomValidity('');
    });
    document.querySelector('#semEntrega').addEventListener('change', function() {
        if (this.checked) {
            document.querySelector('#endereco').setCustomValidity('');
            document.querySelector('#dataEntrega').setCustomValidity('');
        }
    });
    document.querySelector('#formCheckout').addEventListener('submit', confirmarPedido);
});