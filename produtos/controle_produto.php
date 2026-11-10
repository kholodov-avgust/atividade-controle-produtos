<?php
require_once 'conexao.php';

$busca = isset($_GET['busca']) ? $_GET['busca'] : '';
if ($busca) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE descricao LIKE :busca OR categoria LIKE :busca");
    $stmt->execute([':busca' => "%$busca%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM produtos");
}
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Controle de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="p-4">

    <div class="container container-main">
        
        <div class="titulo-secao">
            &#128230; Cadastro de Produto
        </div>
        
        <form action="processamento.php" method="POST">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Descrição</label>
                    <input type="text" name="descricao" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Categoria</label>
                    <select name="categoria" class="form-select" required>
                        <option value="" selected disabled>Selecione...</option>
                        <option value="Informática">Informática</option>
                        <option value="Eletrônicos">Eletrônicos</option>                        
                        <option value="Outros">Outros</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Valor Compra (R$)</label>
                    <input type="text" name="valor_compra" class="form-control" placeholder="R$ 0,00" oninput="mascaraMoeda(this)" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Valor Venda (R$)</label>
                    <input type="text" name="valor_venda" class="form-control" placeholder="R$ 0,00" oninput="mascaraMoeda(this)" required>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label">Qtd. Estoque</label>
                    <input type="number" name="qtd_estoque" class="form-control text-center" required>
                </div>
            </div>

            <div class="d-flex gap-2 mb-5">
                <button type="submit" name="acao" value="cadastrar" class="btn btn-custom">CADASTRAR PRODUTO</button>
                <button type="button" class="btn btn-custom" id="btn-toggle-venda" onclick="ativarModoVenda()">VENDER PRODUTO</button>
            </div>
        </form>

        <div class="d-flex justify-content-between align-items-end">
            <div class="titulo-secao w-100 mb-0" style="border-bottom: none;">
                &#128203; Inventário
            </div>
            
            <form action="controle_produto.php" method="GET" class="input-group mb-2" style="width: 450px;">
                <input type="text" name="busca" class="form-control" style="font-size: 0.85rem;" placeholder="pesquisar por descrição ou categoria" value="<?= htmlspecialchars($busca) ?>">
                <button type="submit" class="btn btn-light border">
                    &#128269;
                </button>
            </form>
        </div>
        <div style="border-bottom: 2px solid #4169E1; margin-bottom: 20px;"></div>

        <div class="table-responsive">
            <table class="table table-borderless align-middle text-center">
                <thead>
                    <tr>
                        <th class="text-start">Descrição</th>
                        <th>Categoria</th>
                        <th>Venda</th>
                        <th>Lucro Unit.</th>
                        <th>Estoque</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody style="border-top: 1px solid #dee2e6;">
                    <?php foreach ($produtos as $produto): 
                        $lucro_unitario = $produto['valor_venda'] - $produto['valor_compra'];
                    ?>
                        <tr>
                            <td class="text-start fw-bold" style="font-size: 0.9rem;"><?= htmlspecialchars($produto['descricao']) ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($produto['categoria']) ?></td>
                            <td class="fw-bold">R$ <?= number_format($produto['valor_venda'], 2, ',', '.') ?></td>
                            <td class="fw-bold">R$ <?= number_format($lucro_unitario, 2, ',', '.') ?></td>
                            <td class="fw-bold"><?= $produto['qtd_estoque'] ?></td>
                            <td>
                                <a href="processamento.php?excluir=<?= $produto['id'] ?>" 
                                   class="acao-item" 
                                   data-id="<?= $produto['id'] ?>"
                                   style="font-size: 1.2rem;"
                                   onclick="return confirm('Excluir este produto?');">
                                    &#10060;
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

    <script>
        function mascaraMoeda(campo) {
            let valor = campo.value.replace(/\D/g, '');
            
            if (valor === '') {
                campo.value = '';
                return;
            }
            
            valor = (parseInt(valor, 10) / 100).toFixed(2);
            
            let partes = valor.split('.');
            partes[0] = partes[0].split(/(?=(?:...)*$)/).join('.');
            if(partes[0].startsWith('.')) partes[0] = partes[0].substring(1);
            
            campo.value = 'R$ ' + partes.join(',');
        }

        let modoVendaAtivo = localStorage.getItem('modoVenda') === 'ativo';

        function atualizarInterfaceVenda() {
            const btnVender = document.getElementById('btn-toggle-venda');
            const itensAcao = document.querySelectorAll('.acao-item');

            if (modoVendaAtivo) {
                btnVender.classList.add('bg-warning');
                btnVender.textContent = 'VOLTAR';
                
                itensAcao.forEach(item => {
                    item.innerHTML = '&#128722;';
                    item.href = 'processamento.php?vender=' + item.dataset.id;
                    item.onclick = null;
                });
            } else {
                btnVender.classList.remove('bg-warning');
                btnVender.textContent = 'VENDER PRODUTO';
                
                itensAcao.forEach(item => {
                    item.innerHTML = '&#10060;';
                    item.href = 'processamento.php?excluir=' + item.dataset.id;
                    item.onclick = function() { return confirm('Excluir este produto?'); };
                });
            }
        }

        function ativarModoVenda() {
            modoVendaAtivo = !modoVendaAtivo;
            localStorage.setItem('modoVenda', modoVendaAtivo ? 'ativo' : 'inativo');
            atualizarInterfaceVenda();
        }

        atualizarInterfaceVenda();
    </script>
</body>
</html>