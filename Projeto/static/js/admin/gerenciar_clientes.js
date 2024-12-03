async function removerCliente(btnExcluir, id) {
    if (!confirm('Deseja realmente remover esse cliente?')) {
        return;
    }
    
    btnExcluir.setAttribute('disabled', true);
    const html = btnExcluir.innerHTML;
    btnExcluir.innerHTML = 'Removendo';
    
    try {
        const formData = new FormData();
        formData.append('id', id);
        const resposta = await fetch('api.php?route=cliente/excluir', {
            method: 'POST',
            body: formData
        });

        const json = await resposta.json();
        if (json.status === 'ok') {
            Alerta.sucesso('#alertaCliente', 'Cliente excluído com sucesso!');
            btnExcluir.closest('tr').remove();
        } else {
            Alerta.erro('#alertaCliente', json.mensagem || 'Erro ao excluír Cliente.');
        }
    } catch (e) {
        console.error(e)
        Alerta.erro('#alertaCliente', 'Erro ao excluír cliente.');
    }

    btnExcluir.removeAttribute('disabled');
    btnExcluir.innerHTML = html;
}