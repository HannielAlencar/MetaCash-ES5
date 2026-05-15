<?php
<<<<<<< Updated upstream
include 'logica_dados.php';
include '../../../config.php';

=======
// 1. Inicia a sessão (essencial para saber quem está logado)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'logica_dados.php';
include '../../../config.php';

// Redireciona se não estiver logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../Login.php/index.php");
    exit();
}

$id_empresa = $_SESSION['id_empresa'];

>>>>>>> Stashed changes
$transacoes = [];
$receita = 0;
$despesa = 0;

try {
<<<<<<< Updated upstream
    // Busca os dados juntando a tabela de transacoes e categoria
    // Usamos 'AS' para que as variáveis fiquem com os mesmos nomes que o seu HTML já usa
=======
    
>>>>>>> Stashed changes
    $sql = "SELECT 
                t.descricao_transacao AS titulo, 
                t.valor_transacao AS valor, 
                t.tipo_transacao AS tipo, 
                c.nome_categoria AS cat,
<<<<<<< Updated upstream
                DATE_FORMAT(t.data_transacao, '%d/%m/%Y') AS data
            FROM transacoes t
            LEFT JOIN categoria c ON t.id_categoria = c.id_categoria
            ORDER BY t.data_transacao DESC";
            
    $stmt = $pdo->query($sql);
    $transacoes = $stmt->fetchAll();

    // Lógica de cálculo baseada no ENUM do seu banco
=======
                c.id_categoria,
                DATE_FORMAT(t.data_transacao, '%d/%m/%Y') AS data
            FROM transacoes t
            LEFT JOIN categoria c ON t.id_categoria = c.id_categoria
            WHERE t.id_empresa = :empresa
            ORDER BY t.data_transacao DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':empresa' => $id_empresa]);
    $transacoes = $stmt->fetchAll();

    // Lógica de cálculo baseada no ENUM do banco
>>>>>>> Stashed changes
    foreach ($transacoes as $tr) {
        if ($tr['tipo'] === 'Receita') {
            $receita += (float)$tr['valor'];
        } else {
            $despesa += (float)$tr['valor'];
        }
    }
} catch (PDOException $e) {
<<<<<<< Updated upstream
    // Se der erro de conexão, o array fica vazio e a página não quebra
=======
>>>>>>> Stashed changes
    error_log("Erro ao buscar transações: " . $e->getMessage());
}

$saldoPeriodo = $receita - $despesa;

if (!function_exists('formatarMoeda')) {
    function formatarMoeda($valor) {
        return 'R$ ' . number_format($valor, 2, ',', '.');
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Transações</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- O CSS externo ainda pode ser usado, mas o Tailwind cuidará do layout expansivo -->
    <link rel="stylesheet" href="../transacoes.css/style.css"> 
</head>
<body class="bg-gray-50 flex min-h-screen">

    <!-- SIDEBAR FIXA -->
    <aside class="w-64 bg-[#0f172a] text-white p-4 flex flex-col sticky top-0 h-screen">
        <div class="flex items-center gap-3 mb-10 px-2">
            <div class="bg-[#2dd4bf] p-2 rounded-lg text-[#0f172a]">
                <i class="fas fa-chart-line text-xl"></i>
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-xl leading-tight">MetaCash</span>
                <span class="text-[10px] text-gray-400">Gestão Empresarial</span>
            </div>
        </div>
        <nav class="flex-1 space-y-3">
            <a href="../Dashboard/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 transition">
                <i class="fas fa-th-large"></i><span class="font-medium">Dashboard</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#29aeb0] text-white shadow-lg">
                <i class="fas fa-exchange-alt"></i><span class="font-medium">Transações</span>
            </a>
            <button onclick="toggleRelatorioModal()" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 transition border border-transparent hover:border-slate-700 text-left">
                <i class="fas fa-file-pdf"></i><span class="font-medium">Baixar Relatório</span>
            </button>
        </nav>
    </aside>

    <!-- CONTEÚDO PRINCIPAL EXPANSIVO -->
    <main class="flex-1 p-10 w-full flex flex-col">
        
        <!-- HEADER -->
        <header class="mb-8 w-full">
            <div class="flex justify-between items-start w-full">
                <div>
                    <h1 class="text-4xl font-extrabold text-[#0f172a] tracking-tight">Transações</h1>
                    <p class="text-lg text-[#334155] mt-2">Gerencie suas finanças</p>
                </div>
                <button onclick="toggleModal()" class="bg-[#2dd4bf] text-white px-6 py-3 rounded-lg font-bold hover:bg-teal-600 shadow-md transition">
                    + Adicionar Transação
                </button>
            </div>

            <!-- BARRA DE PESQUISA (OCUPANDO TODA A LARGURA) -->
            <div class="mt-8 flex flex-col md:flex-row gap-4 p-4 bg-white rounded-2xl border border-gray-200 shadow-sm items-center w-full">
                <div class="relative flex-1 w-full">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="inputBusca" placeholder="Buscar transações..." class="w-full pl-12 pr-4 py-3 rounded-xl outline-none bg-gray-50 focus:bg-white border border-transparent focus:border-teal-500 transition">
                </div>
<<<<<<< Updated upstream
                <div class="relative w-full md:w-auto">
                    <select id="filtroCategoria" class="w-full md:w-56 pl-10 pr-8 py-3 rounded-xl appearance-none border border-gray-200 bg-white cursor-pointer focus:ring-2 focus:ring-teal-500 outline-none">
                        <option value="todas">Todas Categorias</option>
=======

                <div class="mt-8 flex flex-col md:flex-row gap-4 p-4 bg-white rounded-2xl border border-gray-200 shadow-sm items-center">
                    <div class="relative flex-1 w-full">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" id="inputBusca" placeholder="Buscar transações..." class="w-full pl-12 pr-4 py-3 rounded-xl border-none bg-gray-50 outline-none focus:ring-2 focus:ring-teal-500 transition">
                    </div>
                    <div class="relative w-full md:w-auto">
                        <select id="filtroCategoria" class="w-full md:w-48 pl-10 pr-8 py-3 rounded-xl border-none bg-gray-50 appearance-none outline-none focus:ring-2 focus:ring-teal-500 transition cursor-pointer">
                            <option value="todas">Todas Categorias</option>
                            <option value="Vendas">Vendas</option>
                            <option value="Administrativo">Administrativo</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Salários">Salários</option>
                            <option value="Geral">Geral</option>
                        </select>
                        <i class="fas fa-filter absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
            </header>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div id="containerTransacoes" class="divide-y divide-gray-50">

                    <?php 
                    $transacoes_lista = array_reverse((array)$transacoes, true);
                    foreach ($transacoes_lista as $id => $tr): 
                        // Agora ele compara corretamente usando a palavra 'Receita'
                        $isEntrada = ($tr['tipo'] === 'Receita');
                    ?>
                    <div class="item-transacao flex justify-between items-center p-6 hover:bg-gray-50 transition group" 
                         data-titulo="<?= strtolower($tr['titulo']) ?>" 
                         data-categoria="<?= $tr['id_categoria'] ?>">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="fas <?= $isEntrada ? 'fa-arrow-up text-teal-500' : 'fa-arrow-down text-red-400' ?>"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-800"><?= htmlspecialchars($tr['titulo']) ?></p>
                                <p class="text-xs text-slate-400 uppercase font-semibold">
                                    <?= htmlspecialchars($tr['cat'] ?? 'Geral') ?> • <?= $tr['data'] ?>
                                </p>
                            </div>
                        </div>
                        <div class="font-bold text-lg <?= $isEntrada ? 'text-[#2dd4bf]' : 'text-red-400' ?>">
                            <?= ($isEntrada ? '+' : '-') . ' ' . formatarMoeda($tr['valor']) ?>
                        </div>
                    </div>
                    <?php endforeach; ?>

                </div>
                <div id="msgVazio" class="hidden p-20 text-center text-slate-400">
                    <i class="fas fa-search fa-3x mb-4 block opacity-20"></i>
                    Nenhum resultado encontrado.
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL NOVA TRANSAÇÃO -->
    <div id="modalTransacao" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-50 p-4">
         <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6">
            <h3 class="text-xl font-bold mb-4 text-slate-800 border-b pb-4">Nova Transação</h3>
            <form action="../Transacoes.php/salvar_transacao.php" method="POST" class="space-y-4">
                <input type="hidden" name="origem" value="transacoes">
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Título</label>
                    <input type="text" name="titulo" required class="w-full border rounded-xl px-4 py-2 mt-1 outline-none focus:ring-2 focus:ring-teal-500 transition">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Valor (R$)</label>
                    <input type="number" step="0.01" name="valor" required class="w-full border rounded-xl px-4 py-2 mt-1 outline-none focus:ring-2 focus:ring-teal-500 transition">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Categoria</label>
                    <select name="cat" class="w-full border rounded-xl px-4 py-2 mt-1 outline-none focus:ring-2 focus:ring-teal-500 transition">
                        <option value="Geral">Geral</option>
>>>>>>> Stashed changes
                        <option value="Vendas">Vendas</option>
                        <option value="Administrativo">Administrativo</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Salários">Salários</option>
                        <option value="Geral">Geral</option>
                    </select>
                    <i class="fas fa-filter absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
            </div>
        </header>

        <!-- CARDS DE RESUMO (OCUPANDO TODA A LARGURA) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 w-full">
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col justify-center">
                <p class="text-sm font-semibold text-slate-500 mb-1">Total de Receitas</p>
                <h2 class="text-3xl font-bold text-[#2dd4bf]"><?= formatarMoeda($receita) ?></h2>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col justify-center">
                <p class="text-sm font-semibold text-slate-500 mb-1">Total de Despesas</p>
                <h2 class="text-3xl font-bold text-red-400"><?= formatarMoeda($despesa) ?></h2>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col justify-center">
                <p class="text-sm font-semibold text-slate-500 mb-1">Saldo do Período</p>
                <h2 class="text-3xl font-bold text-slate-800"><?= formatarMoeda($saldoPeriodo) ?></h2>
            </div>
        </div>

        <!-- LISTA DE TRANSAÇÕES (OCUPANDO TODA A LARGURA) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden w-full mb-10">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 text-lg">Transações Recentes</h3>
                <a href="#" class="text-sm text-teal-600 font-semibold hover:underline">Ver todas</a>
            </div>
            
            <div id="containerTransacoes" class="divide-y divide-gray-50">
                <?php 
                $transacoes_lista = array_reverse((array)$transacoes, true);
                foreach ($transacoes_lista as $id => $tr): 
                    $isEntrada = ($tr['tipo'] == 'e');
                ?>
                <div class="item-transacao flex justify-between items-center p-6 hover:bg-gray-50 transition group" 
                     data-titulo="<?= strtolower($tr['titulo']) ?>" 
                     data-categoria="<?= $tr['cat'] ?? 'Geral' ?>">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center border border-gray-100">
                            <i class="fas <?= $isEntrada ? 'fa-arrow-up text-teal-500' : 'fa-arrow-down text-red-400' ?> text-lg"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800"><?= htmlspecialchars($tr['titulo']) ?></p>
                            <p class="text-xs text-slate-400 font-medium"><?= htmlspecialchars($tr['cat'] ?? 'Geral') ?> • <?= $tr['data'] ?></p>
                        </div>
                    </div>
                    <div class="text-right font-bold text-lg <?= $isEntrada ? 'text-[#2dd4bf]' : 'text-red-400' ?>">
                        <?= ($isEntrada ? '+ ' : '- ') . formatarMoeda($tr['valor']) ?>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <?php if(empty($transacoes_lista)): ?>
                    <div class="p-20 text-center text-slate-400">Nenhuma transação registrada.</div>
                <?php endif; ?>
            </div>
            <div id="msgVazio" class="hidden p-20 text-center text-slate-400">Nenhum resultado encontrado para a busca.</div>
        </div>
    </main>

    <!-- MODAL NOVA TRANSAÇÃO -->
    <div id="modalTransacao" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6">
            <h3 class="text-xl font-bold mb-4 text-slate-800">Nova Transação</h3>
            <form action="salvar_transacao.php" method="POST" class="space-y-4">
                <input type="hidden" name="origem" value="transacoes">
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Título</label>
                    <input type="text" name="titulo" required class="w-full border rounded-xl px-4 py-2 mt-1 outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
<<<<<<< Updated upstream
                    <label class="text-xs font-bold text-slate-500 uppercase">Valor (R$)</label>
                    <input type="number" step="0.01" name="valor" required class="w-full border rounded-xl px-4 py-2 mt-1 outline-none focus:ring-2 focus:ring-teal-500">
=======
                    <label class="text-xs font-bold text-slate-500 uppercase">Tipo</label>
                    <select name="tipo" class="w-full border rounded-xl px-4 py-2 mt-1 outline-none focus:ring-2 focus:ring-teal-500 transition">
                        <option value="e">Receita (+)</option>
                        <option value="s">Despesa (-)</option>
                    </select>
>>>>>>> Stashed changes
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase">Categoria</label>
                        <select name="cat" class="w-full border rounded-xl px-4 py-2 mt-1">
                            <option value="Geral">Geral</option>
                            <option value="Vendas">Vendas</option>
                            <option value="Administrativo">Administrativo</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Salários">Salários</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase">Tipo</label>
                        <select name="tipo" class="w-full border rounded-xl px-4 py-2 mt-1">
                            <option value="e">Receita (+)</option>
                            <option value="s">Despesa (-)</option>
                        </select>
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="toggleModal()" class="flex-1 py-3 text-slate-500 font-medium hover:bg-slate-50 rounded-xl transition">Cancelar</button>
                    <button type="submit" class="flex-1 py-3 bg-[#2dd4bf] text-white font-bold rounded-xl hover:bg-teal-600 shadow-lg transition">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL BAIXAR RELATÓRIO -->
    <div id="modalRelatorio" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-[60] p-4">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h3 class="text-xl font-bold text-slate-800">Baixar Relatório</h3>
                <button onclick="toggleRelatorioModal()" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>
            <form action="gerar_pdf.php" method="GET" target="_blank" class="space-y-6">
                <!-- Conteúdo omitido para brevidade, mantenha o que você já tem aqui -->
                <button type="submit" class="w-full py-3 bg-slate-800 text-white font-bold rounded-xl shadow-lg hover:bg-slate-700 transition">Baixar PDF</button>
            </form>
        </div>
    </div>

    <script>
        function toggleRelatorioModal() {
            const modal = document.getElementById('modalRelatorio');
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }

        function toggleModal() {
            const modal = document.getElementById('modalTransacao');
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
    </script>
    <script src="transacoes.js/script.js"></script> 
</body>
</html>