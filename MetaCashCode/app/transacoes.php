<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config.php'; 

$id_empresa = $_SESSION['id_empresa'] ?? 0;
$transacoes = [];
$receita = 0;
$despesa = 0;

try {
    // Busca as transações reais APENAS da sua empresa
    $sql = "SELECT 
                t.descricao_transacao AS titulo, 
                t.valor_transacao AS valor, 
                t.tipo_transacao AS tipo, 
                c.nome_categoria AS cat,
                TO_CHAR(t.data_registro, 'DD/MM/YYYY') AS data
            FROM transacoes t
            LEFT JOIN categoria c ON t.id_categoria = c.id_categoria
            WHERE t.id_empresa = :empresa
            ORDER BY t.data_registro DESC, t.id_transacao DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':empresa' => $id_empresa]);
    $transacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Soma os totais
    foreach ($transacoes as $tr) {
        if ($tr['tipo'] === 'Receita') {
            $receita += (float)$tr['valor'];
        } else {
            $despesa += (float)$tr['valor'];
        }
    }
} catch (PDOException $e) {
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
    <link rel="stylesheet" href="../assets/css/transacoes.css"> </head>
<body class="bg-gray-50 flex min-h-screen">

    <?php require_once '../includes/sidebar.php'; ?>

    <main class="flex-1 p-10 w-full flex flex-col ml-64">
        
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

            <div class="mt-8 flex flex-col md:flex-row gap-4 p-4 bg-white rounded-2xl border border-gray-200 shadow-sm items-center w-full">
                <div class="relative flex-1 w-full">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="inputBusca" placeholder="Buscar transações..." class="w-full pl-12 pr-4 py-3 rounded-xl outline-none bg-gray-50 focus:bg-white border border-transparent focus:border-teal-500 transition">
                </div>
                <div class="relative w-full md:w-auto">
                    <select id="filtroCategoria" class="w-full md:w-56 pl-10 pr-8 py-3 rounded-xl appearance-none border border-gray-200 bg-white cursor-pointer focus:ring-2 focus:ring-teal-500 outline-none">
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

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden w-full mb-10">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 text-lg">Transações Recentes</h3>
            </div>
            
            <div id="containerTransacoes" class="divide-y divide-gray-50">
                <?php foreach ($transacoes as $tr): 
                    $isEntrada = ($tr['tipo'] === 'Receita');
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
                
                <?php if(empty($transacoes)): ?>
                    <div class="p-20 text-center text-slate-400">Nenhuma transação registrada.</div>
                <?php endif; ?>
            </div>
            <div id="msgVazio" class="hidden p-20 text-center text-slate-400">Nenhum resultado encontrado para a busca.</div>
        </div>
    </main>

    <div id="modalTransacao" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6">
            <h3 class="text-xl font-bold mb-4 text-slate-800">Nova Transação</h3>
            <form action="salvarTransacao.php" method="POST" class="space-y-4">
                <input type="hidden" name="origem" value="transacoes">
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Título</label>
                    <input type="text" name="titulo" required class="w-full border rounded-xl px-4 py-2 mt-1 outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Valor (R$)</label>
                    <input type="number" step="0.01" name="valor" required class="w-full border rounded-xl px-4 py-2 mt-1 outline-none focus:ring-2 focus:ring-teal-500">
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
                    <button type="button" onclick="toggleModal('modalTransacao')" class="flex-1 py-3 text-slate-500 font-medium hover:bg-slate-50 rounded-xl transition">Cancelar</button>
                    <button type="submit" class="flex-1 py-3 bg-[#2dd4bf] text-white font-bold rounded-xl hover:bg-teal-600 shadow-lg transition">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalRelatorio" class="fixed inset-0 bg-slate-900/40 hidden items-center justify-center z-[60] p-4 backdrop-blur-sm">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-extrabold text-slate-800">Baixar Relatório</h3>
                <button onclick="toggleModal('modalRelatorio')" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form action="../app/gerarPDF.php" method="GET" target="_blank" class="space-y-4">
                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase block mb-2 tracking-widest">Tipo de Transação</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="e" class="hidden peer">
                            <div class="text-sm font-semibold text-center py-2.5 rounded-lg border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-[#1e293b] peer-checked:text-white transition-all">Receita</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="s" class="hidden peer">
                            <div class="text-sm font-semibold text-center py-2.5 rounded-lg border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-[#1e293b] peer-checked:text-white transition-all">Despesa</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="todos" checked class="hidden peer">
                            <div class="text-sm font-semibold text-center py-2.5 rounded-lg border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-[#1e293b] peer-checked:text-white transition-all">Ambos</div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase block mb-2 tracking-widest">Período</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="periodo" value="mensal" checked class="hidden peer">
                            <div class="text-sm font-semibold text-center py-2.5 rounded-lg border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-[#1e293b] peer-checked:text-white transition-all">Mensal</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="periodo" value="anual" class="hidden peer">
                            <div class="text-sm font-semibold text-center py-2.5 rounded-lg border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-[#1e293b] peer-checked:text-white transition-all">Anual</div>
                        </label>
                    </div>
                </div>

                <div data-campo="mes">
                    <label class="text-[11px] font-bold text-slate-400 uppercase block mb-2 tracking-widest">Mês</label>
                    <select name="mes" class="w-full p-3 rounded-lg border border-slate-200 bg-white text-slate-700 font-medium text-sm appearance-none focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition-all cursor-pointer">
                        <option value="1">Janeiro</option>
                        <option value="2">Fevereiro</option>
                        <option value="3">Março</option>
                        <option value="4">Abril</option>
                        <option value="5" selected>Maio</option>
                        <option value="6">Junho</option>
                        <option value="7">Julho</option>
                        <option value="8">Agosto</option>
                        <option value="9">Setembro</option>
                        <option value="10">Outubro</option>
                        <option value="11">Novembro</option>
                        <option value="12">Dezembro</option>
                    </select>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase block mb-2 tracking-widest">Ano</label>
                    <select name="ano" class="w-full p-3 rounded-lg border border-slate-200 bg-white text-slate-700 font-medium text-sm appearance-none focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition-all cursor-pointer">
                        <?php 
                        $ano_atual = (int)date('Y');
                        for ($i = $ano_atual - 5; $i <= $ano_atual; $i++): 
                        ?>
                            <option value="<?= $i ?>" <?= $i === $ano_atual ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="toggleModal('modalRelatorio')" class="flex-1 py-3 border border-slate-200 text-slate-600 font-bold rounded-lg hover:bg-slate-50 transition-all text-sm">Cancelar</button>
                    <button type="submit" class="flex-1 py-3 bg-[#2dd4bf] text-white font-bold rounded-lg shadow-lg hover:bg-teal-600 transition-all text-sm">Baixar PDF</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modalRelatorio = document.getElementById('modalRelatorio');
            if (!modalRelatorio) return;

            const campoMes = modalRelatorio.querySelector('[data-campo="mes"]');
            const radios = modalRelatorio.querySelectorAll('input[name="periodo"]');

            const atualizarPeriodo = () => {
                const selecionado = modalRelatorio.querySelector('input[name="periodo"]:checked');
                const anual = selecionado && selecionado.value === 'anual';
                if (campoMes) {
                    campoMes.classList.toggle('hidden', anual);
                }
            };

            radios.forEach(radio => radio.addEventListener('change', atualizarPeriodo));
            atualizarPeriodo();
        });
    </script>
    <script src="../assets/js/transacoes.js"></script> </body>
</html>
