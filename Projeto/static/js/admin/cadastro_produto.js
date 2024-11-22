function atualizarGaleria(imagem) {
    const { IdImagem, NomeImagem, Caminho } = imagem;

    const div = document.createElement('div');
    div.classList.add('card');
    div.classList.add('img-card');
    div.dataset.idImagem = IdImagem;
    div.dataset.caminhoImagem = Caminho;
    div.setAttribute('title', NomeImagem);

    div.innerHTML = `
        <div class="card-header text-center bg-light">
            <div class="text-muted small text-truncate">${NomeImagem}</div>
        </div>
        <div class="card-body d-flex flex-column justify-content-between">
            <img src="${Caminho}" alt="${NomeImagem}" class="card-img-top mx-auto" style="width: 100px;">
            <button type="button" class="btn btn-danger btn-sm btn-excluir-img" data-id-imagem="${IdImagem}">
                Remover
            </button>
        </div>
    `;

    document.querySelector('#galeriaImagens').appendChild(div);
    div.addEventListener('click', selecionarImagem);
    div.querySelector('.btn-excluir-img').addEventListener('click', removerImagem);
}

async function uploadImagem(event) {
    event.preventDefault();
    
    const btnUpload = event.target.querySelector('button[type="submit"]');
    btnUpload.setAttribute('disabled', true);
    const html = btnUpload.innerHTML;
    btnUpload.innerHTML = 'Carregando';

    try {
        const dadosForm = new FormData(this);

        const resposta = await fetch('api.php?route=imagem/upload', {
            method: 'POST',
            body: dadosForm
        });

        const json = await resposta.json();
        if (json.status === 'ok') {
            Alerta.sucesso('#alertaImagem', 'Upload efetuado com sucesso!');
            this.reset();
            
            atualizarGaleria(json.dados);
        } else {
            Alerta.erro('#alertaImagem', json.mensagem || 'Erro ao realizar upload.');
        }
        
    } catch (e) {
        console.error(e)
        Alerta.erro('#alertaImagem', 'Erro ao realizar upload.');
    }

    btnUpload.removeAttribute('disabled');
    btnUpload.innerHTML = html;
};

async function cadastrarProduto(event) {
    event.preventDefault();

    try {
        const dadosForm = new FormData(this);
        const atualizar = Boolean(dadosForm.get('id') != '');

        const resposta = await fetch(atualizar ? 
            'api.php?route=produto/atualizar' : 
            'api.php?route=produto/cadastrar', {
                method: 'POST',
                body: dadosForm
            } );

        const json = await resposta.json();
        if (json.status === 'ok') {
            if (atualizar) {
                Alerta.sucesso('#alertaProduto', 'Produto atualizado com sucesso!');
            } else {
                Alerta.sucesso('#alertaProduto', 'Produto cadastrado com sucesso!');
                this.reset(); // Limpar formulário
            }
        } else {
            Alerta.erro('#alertaProduto', json.mensagem || 'Erro ao realizar cadastro.');
        }
        
    } catch (e) {
        console.error(e)
        Alerta.erro('#alertaProduto', 'Erro ao realizar cadastro.');
    }
};

async function removerImagem(event) {
    event.stopPropagation();
    
    if (!confirm('Deseja realmente excluír essa imagem?')) {
        return;
    }
    
    const btnExcluir = event.target;
    const idImagem = btnExcluir.dataset.idImagem;

    btnExcluir.setAttribute('disabled', true);
    const html = btnExcluir.innerHTML;
    btnExcluir.innerHTML = 'Removendo';
    
    try {
        const formData = new FormData();
        formData.append('id', idImagem);
        const resposta = await fetch('api.php?route=imagem/excluir', {
            method: 'POST',
            body: formData
        });

        const json = await resposta.json();
        if (json.status === 'ok') {
            Alerta.sucesso('#alertaImagem', 'Imagem excluída com sucesso!');
            btnExcluir.closest('.img-card').remove();

            const inputIdImagem = document.querySelector('#idImagem');
            const imagemProduto = document.querySelector('#imagemProduto');
            if (inputIdImagem.value === idImagem) {
                imagemProduto.src = imagemProduto.dataset.defaultSrc;
                inputIdImagem.value = null;
            }
        } else {
            Alerta.erro('#alertaImagem', json.mensagem || 'Erro ao excluír imagem.');
        }
    } catch (e) {
        console.error(e)
        Alerta.erro('#alertaImagem', 'Erro ao excluír imagem.');
    }

    btnExcluir.removeAttribute('disabled');
    btnExcluir.innerHTML = html;
};

function selecionarImagem(event) {
    document.querySelector('#imagemProduto').src = this.dataset.caminhoImagem;
    document.querySelector('#idImagem').value = this.dataset.idImagem;
    bootstrap.Modal.getOrCreateInstance('#modalImagens').hide();
};

document.addEventListener('DOMContentLoaded', function() {
    const formCadastro = document.getElementById('formCadastro')
    formCadastro.addEventListener('submit', cadastrarProduto);
    formCadastro.addEventListener('reset', function() {
        const imagemProduto = document.querySelector('#imagemProduto');
        imagemProduto.src = imagemProduto.dataset.defaultSrc;
    });

    const formUploadImagem = document.getElementById('formUploadImagem');
    formUploadImagem.addEventListener('submit', uploadImagem);

    document.querySelector('#modalImagens').addEventListener('show.bs.modal', function () {
        this.querySelector('.slide-down').style.maxHeight = '0px';
    }); 

    for (const btn of document.querySelectorAll('.btn-excluir-img')) {
        btn.addEventListener('click', removerImagem)
    }

    for (const btn of document.querySelectorAll('.img-card')) {
        btn.addEventListener('click', selecionarImagem)
    }
});