const Alerta = {
    exibir: function(container, tipo_alerta, mensagem) {
        // Seleciona o contêiner de alertas com base no seletor passado
        const element = document.querySelector(container);
        if (element) {
            // Esconde todos os alertas
            for (const alert of element.querySelectorAll('.alert')) {
                alert.classList.add('d-none');
            }
            // Seleciona o alerta específico e define a mensagem
            const alert = element.querySelector(`[data-alerta="${tipo_alerta}"]`);
            if (alert) {
                alert.querySelector('.mensagem-alerta').innerHTML = mensagem;
                alert.classList.remove('d-none');
            }
            element.firstElementChild.style.maxHeight = '500px'; // Ajuste de altura do container, para animação
        }
    },
    erro: (container, mensagem) => Alerta.exibir(container, 'erro', mensagem),
    sucesso: (container, mensagem) => Alerta.exibir(container, 'sucesso', mensagem),
    info: (container, mensagem) => Alerta.exibir(container, 'info', mensagem),
    warning: (container, mensagem) => Alerta.exibir(container, 'warning', mensagem)
};
