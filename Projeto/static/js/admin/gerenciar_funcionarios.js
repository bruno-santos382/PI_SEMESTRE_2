async function removerFuncionario(btnExcluir, id) {
    // Confirm removal action
    if (!confirm('Deseja realmente remover esse funcionário?')) {
        return;
    }
    
    // Disable the button and change its text
    btnExcluir.setAttribute('disabled', true);
    const originalHtml = btnExcluir.innerHTML;
    btnExcluir.innerHTML = 'Removendo';
    
    try {
        // Prepare form data for the request
        const formData = new FormData();
        formData.append('id', id);

        // Send the request to remove the employee
        const resposta = await fetch('api.php?route=funcionario/excluir', {
            method: 'POST',
            body: formData
        });

        // Parse the JSON response
        const json = await resposta.json();
        
        // Handle the response based on status
        if (json.status === 'ok') {
            if (json.dados.logout === true) {
                window.location.replace('login.php');
            } else {
                Alerta.sucesso('#alertaFuncionario', 'Funcionário excluído com sucesso!');
                btnExcluir.closest('tr').remove();  // Remove the row from the table
            }
        } else {
            Alerta.erro('#alertaFuncionario', json.mensagem || 'Erro ao excluir Funcionário.');
        }
    } catch (e) {
        console.error(e);
        Alerta.erro('#alertaFuncionario', 'Erro ao excluir funcionário.');
    }

    // Reset button state
    btnExcluir.removeAttribute('disabled');
    btnExcluir.innerHTML = originalHtml;
}