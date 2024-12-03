async function removerUsuario(btnExcluir, id) {
    if (!confirm('Deseja realmente remover esse usuário?')) {
        return;
    }
    
    btnExcluir.setAttribute('disabled', true);
    const html = btnExcluir.innerHTML;
    btnExcluir.innerHTML = 'Removendo';
    
    try {
        const formData = new FormData();
        formData.append('id', id);
        const resposta = await fetch('api.php?route=usuario/excluir', {
            method: 'POST',
            body: formData
        });

        const json = await resposta.json();
        if (json.status === 'ok') {
            if (json.dados.logout === true) {
                window.location.replace('login.php');
            } else {
                Alerta.sucesso('#alertaUsuario', 'Usuário excluído com sucesso!');
                btnExcluir.closest('tr').remove();
            }
        } else {
            Alerta.erro('#alertaUsuario', json.mensagem || 'Erro ao excluír Usuário.');
        }
    } catch (e) {
        console.error(e)
        Alerta.erro('#alertaUsuario', 'Erro ao excluír usuario.');
    }

    btnExcluir.removeAttribute('disabled');
    btnExcluir.innerHTML = html;
}