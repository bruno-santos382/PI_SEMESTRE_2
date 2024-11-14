const Alerta = {
    exibir: function(elemento, mensagem) {
        const container = document.querySelector('#alertas');
        for (const alert of container.querySelectorAll('.alert')) {
            alert.classList.add('d-none');
        }
        const alert = container.querySelector(elemento);
        alert.querySelector('.mensagem-alerta').innerHTML = mensagem;
        alert.classList.remove('d-none');
        container.style.maxHeight = '500px';
    },
    erro: (mensagem) => Alerta.exibir('#alertaErro', mensagem),
    sucesso: (mensagem) => Alerta.exibir('#alertaSucesso', mensagem)
};