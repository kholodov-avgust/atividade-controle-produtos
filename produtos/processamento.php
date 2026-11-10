<?php
require_once 'conexao.php';

function formatarMoedaParaBanco($valorString) {
    $valorString = str_replace(['R$', ' ', '.'], '', $valorString);
    $valorString = str_replace(',', '.', $valorString);
    return (float) $valorString;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = isset($_POST['acao']) ? $_POST['acao'] : '';
    
    if ($acao === 'cadastrar') {
        $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';
        $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : '';

        $valor_compra = formatarMoedaParaBanco(isset($_POST['valor_compra']) ? $_POST['valor_compra'] : '0');
        $valor_venda = formatarMoedaParaBanco(isset($_POST['valor_venda']) ? $_POST['valor_venda'] : '0');
        
        $qtd_estoque = isset($_POST['qtd_estoque']) ? $_POST['qtd_estoque'] : 0;

        $sql = "INSERT INTO produtos (descricao, categoria, valor_compra, valor_venda, qtd_estoque) 
                VALUES (:descricao, :categoria, :valor_compra, :valor_venda, :qtd_estoque)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':descricao' => $descricao,
            ':categoria' => $categoria,
            ':valor_compra' => $valor_compra,
            ':valor_venda' => $valor_venda,
            ':qtd_estoque' => $qtd_estoque
        ]);
        
        header("Location: controle_produto.php");
        exit;
    }
}

if (isset($_GET['vender'])) {
    $id = $_GET['vender'];
    $stmt = $pdo->prepare("UPDATE produtos SET qtd_estoque = qtd_estoque - 1 WHERE id = :id AND qtd_estoque > 0");
    $stmt->execute([':id' => $id]);
    
    header("Location: controle_produto.php");
    exit;
}

if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    header("Location: controle_produto.php");
    exit;
}
?>