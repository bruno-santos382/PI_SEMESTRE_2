<?php

class bancoDados{
    private $conn;

    public function __construct(){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "testedefinitivo";

        try {
            $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
        echo "A conexão falhou: " . $e->getMessage();
        }
    }

    public function fecharPedido($idCli){
        try{
            $stmt = $this->conn->prepare ("CALL PRC_FECHAR_PEDIDO(:idCliente)");
            $stmt->bindParam(':idCliente', $idCli, PDO::PARAM_INT);

            $stmt->execute();
        }
        catch(PDOException $e)
        {
            echo "Erro: " . $e->getMessage();
        }
    }

    public function adicionarAoCarrinho($idprod, $qtde, $idCli){
        try{
            $stmt = $this->conn->prepare ("CALL PRC_ADICIONAR_AO_CARRINHO(:idProduto, :quantidade, :idCliente)");
            $stmt->bindParam(':idProduto', $idprod, PDO::PARAM_INT);
            $stmt->bindParam(':quantidade', $qtde, PDO::PARAM_INT);
            $stmt->bindParam(':idCliente', $idCli, PDO::PARAM_INT);

            $stmt->execute();
        }
        catch(PDOException $e)
        {
            echo "Erro: " . $e->getMessage();
        }
    }

    public function removerDoCarrinho($idprod, $idCli){
        try{
            $stmt = $this->conn->prepare ("CALL PRC_REMOVER_DO_CARRINHO(:idProduto, :idCliente)");
            $stmt->bindParam(':idProduto', $idprod, PDO::PARAM_INT);
            $stmt->bindParam(':idCliente', $idCli, PDO::PARAM_INT);

            $stmt->execute();
        }
        catch(PDOException $e)
        {
            echo "Erro: " . $e->getMessage();
        }                        
    }

    public function cadastrarCliente($nom, $cont, $logi, $senh){
        try{
            $stmt = $this->conn->prepare ("CALL PRC_CADASTRAR_CLIENTE(:nome, :contato, :login, :senha)");
            $stmt->bindParam(':nome', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':contato', $cont, PDO::PARAM_STR);
            $stmt->bindParam(':login', $logi, PDO::PARAM_STR);
            $stmt->bindParam(':senha', $senh, PDO::PARAM_STR);

            $stmt->execute();
        }
        catch(PDOException $e)
        {
            echo "Erro: " . $e->getMessage();
        }
    }

    public function cadastrarProduto($nom, $prec, $marc, $estoqu){
        try{
            $stmt = $this->conn->prepare ("CALL PRC_CADASTRAR_PRODUTO(:nome, :preco, :marca, :estoque)");
            $stmt->bindParam(':nome', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':preco', $prec, PDO::PARAM_STR);
            $stmt->bindParam(':marca', $marc, PDO::PARAM_STR);
            $stmt->bindParam(':estoque', $estoqu, PDO::PARAM_INT);

            $stmt->execute();
        }
        catch(PDOException $e)
        {
            echo "Erro: " . $e->getMessage();
        }
    }

    public function __destruct(){
        $this->conn = null;
    }
}
?>


