<?php

require_once __DIR__.'/../Conexao.php';

class Promocao 
{
    protected Conexao $conexao;

    /**
     * Constroi um novo objeto Promocao.
     * 
     * Instancia uma nova conex o com o banco de dados.
     */
    public function __construct() {
        $this->conexao = new Conexao();
    }


    /**
     * Cadastra ou atualiza uma promoção no banco de dados.
     *
     * Insere uma nova promoção se o ID não for fornecido, ou atualiza uma promoção existente
     * se o ID estiver presente. Após a operação, retorna os detalhes da promoção atualizada ou inserida.
     *
     * @param int|null $id O ID da promoção (opcional para inserção).
     * @param int $produto O ID do produto.
     * @param string $data_inicio A data de início da promoção.
     * @param string $data_fim A data de fim da promoção.
     * @param float $desconto O percentual de desconto aplicado.
     * @return array Os detalhes da promoção cadastrada ou atualizada.
     */
    public function cadastrar(
        int $id = null, 
        int $produto, 
        string $data_inicio, 
        string $data_fim, 
        float $desconto
    ): array {
        $query = 'INSERT INTO promocoes (IdProduto, DataInicio, DataFim, Desconto) 
                  VALUES (:produto, :data_inicio, :data_fim, :desconto)';
        $params = [
            'produto' => $produto,
            'data_inicio' => $data_inicio,
            'data_fim' => $data_fim,
            'desconto' => $desconto
        ];
    
        if (!empty($id)) {
            $query = 'UPDATE promocoes 
                      SET IdProduto = :produto, 
                          DataInicio = :data_inicio, 
                          DataFim = :data_fim, 
                          Desconto = :desconto 
                      WHERE IdPromocao = :id';
            $params['id'] = $id;
        }
    
        $stmt = $this->conexao->prepare($query);
        $stmt->execute($params);
        $id_promocao = max($this->conexao->lastInsertId(), $id);
    
        $query = 'SELECT * FROM VW_PROMOCOES_PRODUTO_ATIVAS WHERE IdPromocao = :id';
        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['id' => $id_promocao]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Marca uma promoção como excluída no banco de dados.
     * 
     * Atualiza o campo DataExclusao da promoção com a data e hora atuais.
     * 
     * @param int $id O ID da promoção a ser excluída.
     * 
     * @return void
     */
    public function excluir(int $id): void {
        $query = <<<SQL
        UPDATE promocoes
        SET DataExclusao = CURRENT_TIMESTAMP()
        WHERE IdPromocao = :id
    SQL;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute(['id' => $id]);
    }

    /**
     * Lista as promoções ativas para a semana atual.
     *
     * Consulta e retorna as promoções que estão ativas, possuem estoque disponível,
     * e cujas datas de início e fim incluem a data atual.
     *
     * @return array Retorna um array com as promoções ativas da semana.
     */
    public function listarPromocoesSemana(): array {
        $query = <<<SQL

        SELECT * FROM VW_PROMOCOES_PRODUTO_ATIVAS
        WHERE Status = 'Ativo' AND Estoque > 0 AND CURDATE() BETWEEN DataInicio AND DataFim 
        ORDER BY DataInicio, DataFim
SQL;

        $stmt = $this->conexao->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    

    /**
     * Lista as promo es ativas.
     *
     * Consulta e retorna as promo es que est o ativas, ou seja, cujas datas de in cio e fim incluem a data atual.
     *
     * @return array Retorna um array com as promo es ativas.
     */
    public function listarPromocoesAtivas(): array {
        $query = <<<SQL

        SELECT * FROM VW_PROMOCOES_PRODUTO_ATIVAS
        WHERE Status = 'Ativo'
        ORDER BY DataInicio, DataFim
SQL;

        $stmt = $this->conexao->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Lista as promo es expiradas.
     *
     * Consulta e retorna as promo es que est o expiradas, ou seja, cujas datas de in cio e fim
     * s o menores que a data atual.
     *
     * @return array Retorna um array com as promo es expiradas.
     */
    public function listarPromocoesExpiradas(): array {
        $query = <<<SQL

        SELECT * FROM VW_PROMOCOES_PRODUTO_ATIVAS
        WHERE Status = 'Expirado'
        ORDER BY DataInicio, DataFim
SQL;

        $stmt = $this->conexao->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
