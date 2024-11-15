async function removerProduto(btnExcluir, id) {
    if (!confirm('Deseja realmente remover esse produto?')) {
        return;
    }
    
    btnExcluir.setAttribute('disabled', true);
    const html = btnExcluir.innerHTML;
    btnExcluir.innerHTML = 'Removendo';
    
    try {
        const formData = new FormData();
        formData.append('id', id);
        const resposta = await fetch('api.php?route=produto/excluir', {
            method: 'POST',
            body: formData
        });

        const json = await resposta.json();
        if (json.status === 'ok') {
            Alerta.sucesso('#alertaProduto', 'Produto removido com sucesso!');
            btnExcluir.closest('tr').remove();
        } else {
            Alerta.erro('#alertaProduto', json.mensagem || 'Erro ao remover produto.');
        }
    } catch (e) {
        console.error(e)
        Alerta.erro('#alertaProduto', 'Erro ao remover produto.');
    }

    btnExcluir.removeAttribute('disabled');
    btnExcluir.innerHTML = html;
}