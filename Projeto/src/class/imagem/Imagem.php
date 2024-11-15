<?php

require_once __DIR__.'/../Conexao.php';
require_once __DIR__.'/../validacao/ValidacaoException.php';

class Imagem 
{
    protected Conexao $conexao;

    public function __construct() {
        $this->conexao = new Conexao();
    }
    
    public function upload(): array 
    {
        [$nome_imagem, $caminho] = $this->uploadArquivo();

        $query = <<<SQL

        INSERT INTO imagens (nomeimagem, caminho) 
        VALUES (:nomeimagem, :caminho);
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([
            'nomeimagem' => $nome_imagem,
            'caminho' => $caminho
        ]);

        $query = <<<SQL
        
        SELECT img.*
        FROM imagens img
        ORDER BY img.idimagem DESC
        LIMIT 1;
SQL;
        $stmt = $this->conexao->query($query);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function excluir(int $id) {
        $query = <<<SQL
        
        UPDATE imagens
        SET DataExclusao = CURRENT_TIMESTAMP()
        WHERE IdImagem = :id
SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute([
            'id' => $id
        ]);
    }

    public function lista(): array
    {
        $query = <<<SQL

        SELECT img.*
        FROM imagens img
        WHERE img.dataexclusao IS NULL
        ORDER BY img.idimagem, img.nomeimagem;
SQL;

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(Conexao::FETCH_ASSOC);
    }

    private function uploadArquivo(): array
    {
        // Verifica se um arquivo foi enviado
        
        if (!empty($_FILES['arquivo']) && $_FILES['arquivo']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['arquivo']['error'] !== UPLOAD_ERR_OK) {
                throw new ValidacaoException('Erro no upload do arquivo: ' . $_FILES['arquivo']['error']);
            }
            
            $caminho_temporario = $_FILES['arquivo']['tmp_name']; // Caminho temporário do arquivo
            $nome_arquivo = $_FILES['arquivo']['name']; // Nome original do arquivo
            $tipo_arquivo = $_FILES['arquivo']['type']; // Tipo do arquivo
    
            // Especifica o diretório onde você deseja salvar o arquivo enviado
            $diretorio_upload = './static/img/upload/';
            $caminho_destino = $diretorio_upload . basename($nome_arquivo); // Caminho completo para o destino
    
            // Valida o tipo de arquivo (opcional)
            $tipos_arquivos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($tipo_arquivo, $tipos_arquivos_permitidos)) {
                throw new ValidacaoException('Tipo de arquivo não permitido. Apenas JPEG, PNG, GIF e WEBP são aceitos.');
            }
    
            // Move o arquivo para o diretório desejado
            if (!move_uploaded_file($caminho_temporario, $caminho_destino)) {
                throw new ValidacaoException('Erro ao mover arquivo para o diretório destino.');
            }

            return [$nome_arquivo, $caminho_destino];
        }
        
        // Verifica se uma URL foi submetida
        if (isset($_POST['url'])) 
        {
            $url_imagem = filter_var($_POST['url'], FILTER_VALIDATE_URL); // Valida a URL
            
            if ($url_imagem === false) {
                throw new ValidacaoException('URL inválida.'); // Lança exceção se a URL for inválida
            }
    
            // Obtém o nome da imagem a partir da URL
            $nome_imagem = basename($url_imagem);
            
            // Valida a URL da imagem verificando seus cabeçalhos (opcional)
            $cabecalhos = get_headers($url_imagem, 1);
            
            if (strpos($cabecalhos['Content-Type'], 'image/') === false) {
                throw new ValidacaoException('A URL fornecida não aponta para uma imagem válida.'); // Lança exceção se não for uma imagem
            }

            return [$nome_imagem, $url_imagem]; 
        }

        // Nenhum arquivo
        throw new ValidacaoException('Nenhum arquivo foi selecionado.');
    }
}