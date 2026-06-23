<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config.php'; 

$id_empresa = $_SESSION['id_empresa'] ?? 0;
$transacoes = [];
$receita = 0;
$despesa = 0;

date_default_timezone_set('America/Sao_Paulo');

// Definições padrões (Fallback) caso não exista customização salva
$config_titulo = "Transações";
$config_subtitulo = "Gerencie suas finanças";
$config_botao = "+ Adicionar Transação";
$config_busca = "Buscar transações...";
$config_vazio = "Nenhuma transação encontrada.";

try {
    // Busca as customizações do editor primeiro
    $sql_conf = "SELECT chave_config, valor_config FROM configs_paginas WHERE id_empresa = :empresa";
    $stmt_conf = $pdo->prepare($sql_conf);
    $stmt_conf->execute([':empresa' => $id_empresa]);
    $configs = $stmt_conf->fetchAll(PDO::FETCH_KEY_PAIR);

    if (!empty($configs)) {
        if (!empty($configs['titulo_pagina'])) $config_titulo = $configs['titulo_pagina'];
        if (!empty($configs['subtitulo_pagina'])) $config_subtitulo = $configs['subtitulo_pagina'];
        if (!empty($configs['texto_botao'])) $config_botao = $configs['texto_botao'];
        if (!empty($configs['placeholder_busca'])) $config_busca = $configs['placeholder_busca'];
        if (!empty($configs['mensagem_vazio'])) $config_vazio = $configs['mensagem_vazio'];
    }

    // Busca as transações reais da empresa
    $sql = "SELECT 
                t.id_transacao,
                t.descricao_transacao AS titulo, 
                t.valor_transacao AS valor, 
                t.tipo_transacao AS tipo, 
                c.nome_categoria AS cat,
                TO_CHAR(t.data_transacao, 'DD/MM/YYYY') AS data,
                t.id_transacao AS id
            FROM transacoes t
            LEFT JOIN categoria c ON t.id_categoria = c.id_categoria
            WHERE t.id_empresa = :empresa
            ORDER BY t.data_transacao DESC, t.id_transacao DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':empresa' => $id_empresa]);
    $transacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
$receitas_mes = $receita;
$despesas_mes = $despesa;
$saldo_total = $saldoPeriodo;
$transacoes_count = count($transacoes);

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
    <title>MetaCash - <?= htmlspecialchars($config_titulo) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        (function() {
            const temaSalvo = localStorage.getItem('metaCashTheme');
            if (temaSalvo) {
                try {
                    const cores = JSON.parse(temaSalvo);
                    const root = document.documentElement;
                    for (const [key, value] of Object.entries(cores)) {
                        root.style.setProperty(`--meta-${key}`, value);
                    }
                } catch(e) { console.error("Erro ao aplicar tema", e); }
            }
        })();

        tailwind.config = { theme: { extend: { colors: { 
            meta: { 
                menu: 'var(--meta-menu)', 
                btn1: 'var(--meta-btn1)', 
                destaque: 'var(--meta-destaque)', 
                btn2: 'var(--meta-btn2)', 
                clara: 'var(--meta-clara)', 
                fundo: 'var(--meta-fundo)' 
            }
        }}}};
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght=300;400;500;600;700;800&display=swap');
        :root {
            --meta-menu: #0F2440;
            --meta-btn1: #204C73;
            --meta-destaque: #24A6B6;
            --meta-btn2: #35C59A;
            --meta-clara: #5DA4C0;
            --meta-fundo: #FDFEFB;
        }
        body { font-family: 'Inter', sans-serif; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/dashboardGerente.css">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <?php require_once '../includes/sidebar.php'; ?>

        <main class="flex-1 p-10 ml-64">
            <header class="mb-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-4xl font-extrabold text-[#0f172a] tracking-tight"><?= htmlspecialchars($config_titulo) ?></h1>
                        <p class="text-lg text-[#334155] mt-2"><?= htmlspecialchars($config_subtitulo) ?></p>
                    </div>
                    <button onclick="toggleModal()" class="bg-gradient-to-r from-slate-800 to-teal-600 text-white px-6 py-3 rounded-lg font-bold shadow-lg hover:opacity-90 transition transform active:scale-95">
                        <?= htmlspecialchars($config_botao) ?>
                    </button>
                </div>

                <div class="mt-8 flex flex-col md:flex-row gap-4 p-4 bg-white rounded-2xl border border-gray-200 shadow-sm items-center">
                    <div class="relative flex-1 w-full">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" id="inputBusca" placeholder="<?= htmlspecialchars($config_busca) ?>" class="w-full pl-12 pr-4 py-3 rounded-xl border-none bg-gray-50 outline-none focus:ring-2 focus:ring-teal-500 transition">
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

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <p class="text-xs font-bold text-slate-400 uppercase">Total de Receitas</p>
                        <p class="text-2xl font-bold text-teal-500 mt-1"><?= formatarMoeda($receitas_mes) ?></p>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <p class="text-xs font-bold text-slate-400 uppercase">Total de Despesas</p>
                        <p class="text-2xl font-bold text-red-400 mt-1"><?= formatarMoeda($despesas_mes) ?></p>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <p class="text-xs font-bold text-slate-400 uppercase">Saldo do Período</p>
                        <p class="text-2xl font-bold text-slate-800 mt-1"><?= formatarMoeda($saldo_total) ?></p>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <p class="text-xs font-bold text-slate-400 uppercase">Transações no Período</p>
                        <p class="text-2xl font-bold text-slate-800 mt-1"><?= $transacoes_count ?></p>
                    </div>
                </div>
            </header>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div id="containerTransacoes" class="divide-y divide-gray-50">
                    <?php 
                    foreach ($transacoes as $tr): 
                        $isEntrada = ($tr['tipo'] === 'Receita');
                    ?>
                    <div class="item-transacao flex justify-between items-center p-6 hover:bg-gray-50 transition group" 
                           data-titulo="<?= strtolower($tr['titulo']) ?>" 
                           data-categoria="<?= $tr['cat'] ?? 'Geral' ?>">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="fas <?= $isEntrada ? 'fa-arrow-up text-teal-500' : 'fa-arrow-down text-red-400' ?>"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-800"><?= htmlspecialchars($tr['titulo']) ?></p>
                                <p class="text-xs text-slate-400 uppercase font-semibold"><?= htmlspecialchars($tr['cat'] ?? 'Geral') ?> • <?= $tr['data'] ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="font-bold text-lg <?= $isEntrada ? 'text-[#2dd4bf]' : 'text-red-400' ?>">
                                <?= ($isEntrada ? '+' : '-') . ' ' . formatarMoeda($tr['valor']) ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <?php if (empty($transacoes)): ?>
                        <div class="p-10 text-center text-gray-400"><?= htmlspecialchars($config_vazio) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <div id="modalTransacao" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-50 p-4">
         <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6">
            <h3 class="text-xl font-bold mb-4 text-slate-800 border-b pb-4">Nova Transação</h3>
            <form id="formTransacao" method="POST" class="space-y-4" onsubmit="adicionarEConfirmar(event)">
                <input type="hidden" name="origem" value="transacoes">
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Descrição</label>
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
                        <option value="Vendas">Vendas</option>
                        <option value="Administrativo">Administrativo</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Salários">Salários</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Tipo</label>
                    <select name="tipo" class="w-full border rounded-xl px-4 py-2 mt-1 outline-none focus:ring-2 focus:ring-teal-500 transition">
                        <option value="Receita">Entrada (+)</option>
                        <option value="Despesa">Saída (-)</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Data</label>
                    <input type="date" name="data" value="<?php echo date('Y-m-d'); ?>" required class="w-full border rounded-xl px-4 py-2 mt-1 outline-none focus:ring-2 focus:ring-teal-500 transition">
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="toggleModal()" class="flex-1 py-3 text-slate-500 font-medium hover:bg-slate-50 rounded-xl transition">Cancelar</button>
                    <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-slate-800 to-teal-600 text-white font-bold rounded-xl shadow-lg hover:opacity-90 transition">Adicionar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="popupSucesso" class="fixed top-5 left-1/2 -translate-x-1/2 bg-teal-500 text-white px-6 py-3 rounded-xl shadow-lg hidden z-[100] font-bold">
        Transação cadastrada com sucesso
    </div>

    <script src="../assets/js/transacoes.js"></script> 
    <script>
        function toggleModal() {
            const modal = document.getElementById('modalTransacao');
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }

        const inputBusca = document.getElementById('inputBusca');
        const filtroCategoria = document.getElementById('filtroCategoria');
        const itensTransacao = document.querySelectorAll('.item-transacao');

        function filtrar() {
            const busca = inputBusca.value.toLowerCase();
            const categoria = filtroCategoria.value;

            itensTransacao.forEach(item => {
                const tituloItem = item.getAttribute('data-titulo');
                const catItem = item.getAttribute('data-categoria');

                const bateBusca = tituloItem.includes(busca);
                const bateCategoria = (categoria === 'todas' || catItem === categoria);

                if (bateBusca && bateCategoria) {
                    item.classList.remove('hidden');
                    item.classList.add('flex');
                } else {
                    item.classList.remove('flex');
                    item.classList.add('hidden');
                }
            });
        }

        inputBusca.addEventListener('input', filtrar);
        filtroCategoria.addEventListener('change', filtrar);

        function adicionarEConfirmar(event) {
            event.preventDefault(); 
            toggleModal();
            const popup = document.getElementById('popupSucesso');
            popup.classList.remove('hidden');
            setTimeout(() => {
                popup.classList.add('hidden');
                location.reload();
            }, 1500);
        }
    </script>
</body>
</html>