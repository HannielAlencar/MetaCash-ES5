<?php
// Define um array de fallback por padrão para evitar quebras de variáveis nulas caso o carregamento falhe
$data = [
    'transacoes' => [],
    'resumo' => ['receitas' => '0,00', 'despesas' => '0,00', 'saldo' => '0,00']
];

// Caminhos inteligentes possíveis para encontrar o arquivo api.php
$caminhos_api = [
    __DIR__ . '/api.php',                      // Mesma pasta do arquivo atual (app/)
    __DIR__ . '/../app/api.php',               // Pasta raiz e depois entrando em app/
    dirname(__DIR__) . '/app/api.php',         // Pasta pai de forma absoluta e depois app/
    __DIR__ . '/../api.php'                    // Pasta pai direta (MetaCashCode/)
];

$carregado = false;
foreach ($caminhos_api as $caminho) {
    if (file_exists($caminho)) {
        include $caminho;
        $carregado = true;
        break;
    }
}

// Se não achar api.php, tenta carregar o config.php ou data.php como plano B
if (!$carregado) {
    $caminhos_fallback = [
        __DIR__ . '/config.php',
        __DIR__ . '/../config.php',
        __DIR__ . '/shared_data.php',
        __DIR__ . '/../shared_data.php'
    ];
    foreach ($caminhos_fallback as $caminho_fb) {
        if (file_exists($caminho_fb)) {
            include $caminho_fb;
            break;
        }
    }
}

// Garante que a estrutura básica do array $data exista após os includes
if (!isset($data) || !is_array($data)) {
    $data = [];
}
if (!isset($data['transacoes']) || !is_array($data['transacoes'])) {
    $data['transacoes'] = isset($transacoes) && is_array($transacoes) ? $transacoes : [];
}
if (!isset($data['resumo']) || !is_array($data['resumo'])) {
    $data['resumo'] = [
        'receitas' => number_format(isset($total_receitas) ? $total_receitas : 0, 2, ',', '.'),
        'despesas' => number_format(isset($total_despesas) ? $total_despesas : 0, 2, ',', '.'),
        'saldo' => number_format(isset($saldo_real_lucro) ? $saldo_real_lucro : 0, 2, ',', '.')
    ];
}
?>
<!DOCTYPE html>
<html lang="pt-br" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Transações</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="transaçoes.css/style.css">
</head>
<body class="bg-gray-50 min-h-screen flex">

    <!-- SIDEBAR FIXA -->
    <aside class="w-64 bg-[#0f172a] text-white p-4 flex flex-col fixed h-screen shrink-0 z-40">
        <div class="flex items-center gap-3 mb-10 px-2 pt-2">
            <!-- LOGO COM PROTEÇÃO CONTRA LOOP DE ERRO -->
            <img src="../assets/img/logo_empresas.png" alt="MetaCash Logo" class="w-11 h-11 rounded-lg object-cover" onerror="this.onerror=null; this.src='../DashboardGerente/image_75793b.png'; this.onerror=function(){this.src='https://ui-avatars.com/api/?name=MetaCash&background=2dd4bf&color=0f172a';}">
            <div class="flex flex-col">
                <span class="font-bold text-xl leading-tight text-white">MetaCash</span>
                <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Gestão Empresarial</span>
            </div>
        </div>

        <!-- Navegação principal com fonte e tamanho sincronizados com o Dashboard de Usuário -->
        <nav class="flex-1 space-y-2">
            <!-- Dashboard -->
            <a href="../app/dashboardGerente.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                <i class="fas fa-th-large w-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <!-- Transações -->
            <a href="../app/transacoesGerente.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#2dd4bf] text-white shadow-lg transition">
                <i class="fas fa-exchange-alt w-5"></i>
                <span class="font-medium">Transações</span>
            </a>
            <!-- Equipe -->
            <a href="../app/gerenciaEquipe.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                <i class="fas fa-users w-5"></i>
                <span class="font-medium">Equipe</span>
            </a>
            <!-- Gerenciar Páginas -->
            <a href="../app/gerenciaPaginas.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                <i class="fas fa-file-alt w-5"></i>
                <span class="font-medium">Gerenciar Páginas</span>
            </a>
            <!-- Histórico -->
            <a href="../app/historico.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                <i class="fas fa-history w-5"></i>
                <span class="font-medium">Histórico</span>
            </a>
            <!-- Configurações -->
            <a href="../app/configuracao.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                <i class="fas fa-cog w-5"></i>
                <span class="font-medium">Configurações</span>
            </a>

            <!-- Botão de Download na Sidebar -->
            <button onclick="toggleModal('modalRelatorio')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition border border-transparent hover:border-slate-700 text-left">
                <i class="fas fa-file-pdf w-5"></i>
                <span class="font-medium">Baixar Relatório</span>
            </button>
        </nav>

        <!-- Profile Footer -->
        <div class="mt-auto pt-6 border-t border-slate-800 space-y-4 pb-2">
            <a href="../app/PerfilGerente.php" class="bg-[#1e3a5f]/40 p-3 rounded-2xl flex items-center gap-3 border border-slate-700/50 hover:bg-[#1e3a5f]/60 transition block group">
                <div class="w-10 h-10 bg-[#2dd4bf] rounded-full flex items-center justify-center text-[#0f172a] font-bold text-lg shrink-0 group-hover:scale-105 transition-transform">U</div>
                <div class="flex flex-col overflow-hidden">
                    <span class="text-sm font-bold truncate">Usuário</span>
                    <span class="text-[10px] text-gray-400 truncate">usuario@exemplo.com</span>
                </div>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-gray-400 hover:text-white transition group">
                <i class="fas fa-sign-out-alt rotate-180 group-hover:text-red-400 transition-colors"></i>
                <span class="font-medium">Sair</span>
            </a>
        </div>
    </aside>

    <main class="flex-1 p-10 ml-64 min-h-screen flex flex-col">
        <header class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-4xl font-extrabold text-[#0f172a] tracking-tight">Transações</h1>
                    <p class="text-lg text-[#334155] mt-2">Gerencie suas finanças</p>
                </div>
                <button onclick="toggleModal('modalTransacao')" class="bg-[#2dd4bf] text-white px-6 py-3 rounded-lg font-bold hover:bg-teal-600 shadow-md transition transform active:scale-95">
                    + Adicionar Transação
                </button>
            </div>

            <div class="mt-8 flex flex-col md:flex-row gap-4 p-4 bg-white rounded-2xl border border-gray-200 shadow-sm items-center">
                <div class="relative flex-1 w-full">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="inputBusca" placeholder="Buscar transações..." class="w-full pl-12 pr-4 py-3 rounded-xl border-none bg-gray-50 outline-none focus:ring-2 focus:ring-teal-500 transition">
                </div>
                <div class="relative w-full md:w-auto">
                    <select id="filtroCategoria" class="w-full md:w-48 pl-10 pr-8 py-3 rounded-xl border-none bg-gray-50 appearance-none outline-none focus:ring-2 focus:ring-teal-500 transition cursor-pointer">
                        <option value="todas">Todas Categorias</option>
                        <option value="Salário">Salário</option>
                        <option value="Compras">Compras</option>
                        <option value="Moradia">Moradia</option>
                        <option value="Alimentação">Alimentação</option>
                        <option value="Transporte">Transporte</option>
                    </select>
                    <i class="fas fa-filter absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total de Receitas</p>
                    <p class="text-2xl font-black text-teal-500">R$ <?= $data['resumo']['receitas'] ?></p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total de Despesas</p>
                    <p class="text-2xl font-black text-rose-500">R$ <?= $data['resumo']['despesas'] ?></p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Saldo do Período</p>
                    <p class="text-2xl font-black text-slate-800">R$ <?= $data['resumo']['saldo'] ?></p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Transações no Período</p>
                    <p class="text-2xl font-black text-slate-800"><?= count((array)$data['transacoes']) ?></p>
                </div>
            </div>
        </header>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex-1">
            <div id="containerTransacoes" class="divide-y divide-gray-50">
                <?php 
                $transacoes_lista = array_reverse((array)$data['transacoes'], true);
                foreach ($transacoes_lista as $id => $tr): 
                    $titulo_transacao = $tr['nome'] ?? ($tr['titulo'] ?? 'Sem título');
                    $categoria_transacao = $tr['cat'] ?? 'Geral';
                    $data_transacao = $tr['data'] ?? date('d/m/Y');
                    $valor_transacao = isset($tr['valor']) ? (float)$tr['valor'] : 0;
                    $tipo_transacao = $tr['tipo'] ?? 'saida';
                    $isEntrada = ($tipo_transacao === 'entrada' || $tipo_transacao === 'e');
                ?>
                <div class="item-transacao flex justify-between items-center p-6 hover:bg-gray-50 transition group" 
                       data-titulo="<?= strtolower($titulo_transacao) ?>" 
                       data-categoria="<?= strtolower($categoria_transacao) ?>">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas <?= $isEntrada ? 'fa-arrow-up text-teal-500' : 'fa-arrow-down text-red-400' ?>"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800"><?= htmlspecialchars($titulo_transacao) ?></p>
                            <p class="text-xs text-slate-400 uppercase font-semibold"><?= htmlspecialchars($categoria_transacao) ?> • <?= htmlspecialchars($data_transacao) ?></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="font-bold text-lg <?= $isEntrada ? 'text-[#2dd4bf]' : 'text-red-400' ?>">
                            <?= ($isEntrada ? '+' : '-') . ' R$ ' . number_format($valor_transacao, 2, ',', '.') ?>
                        </div>
                        <!-- Botão Editar -->
                        <button type="button" onclick="toggleModal('modalTransacao')" class="text-slate-400 hover:text-slate-600 p-2 transition">
                            <i class="fas fa-edit"></i>
                        </button>
                        <!-- Botão Excluir -->
                        <button type="button" data-delete-url="../DashboardGerente/excluir_transacao.php?id=<?= $id ?>&redirect=../TransaçoesGerente.php/index.php" class="btnExcluirTransacao text-red-500 hover:text-red-700 rounded-full p-2 transition" title="Excluir transação">
                            <i class="fas fa-trash-alt"></i>
                        </button>
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

    <!-- MODAL NOVA TRANSAÇÃO -->
    <div id="modalTransacao" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-50 p-4">
         <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6">
            <h3 class="text-xl font-bold mb-4 text-slate-800 border-b pb-4">Nova Transação</h3>
            <form action="../app/salvarTransacao.php" method="POST" class="space-y-4">
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
                        <option value="Salário">Salário</option>
                        <option value="Compras">Compras</option>
                        <option value="Moradia">Moradia</option>
                        <option value="Alimentação">Alimentação</option>
                        <option value="Transporte">Transporte</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Data</label>
                    <input type="date" name="data" value="<?php echo date('Y-m-d'); ?>" required class="w-full border rounded-xl px-4 py-2 mt-1 outline-none focus:ring-2 focus:ring-teal-500 transition">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Tipo</label>
                    <select name="tipo" class="w-full border rounded-xl px-4 py-2 mt-1 outline-none focus:ring-2 focus:ring-teal-500 transition">
                        <option value="entrada">Entrada (+)</option>
                        <option value="saida">Saída (-)</option>
                    </select>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="toggleModal('modalTransacao')" class="flex-1 py-3 text-slate-500 font-medium hover:bg-slate-50 rounded-xl transition">Cancelar</button>
                    <button type="submit" class="flex-1 py-3 bg-[#2dd4bf] text-white font-bold rounded-xl hover:bg-teal-600 shadow-lg transition">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DE CONFIRMAÇÃO DE EXCLUSÃO -->
    <div id="modalExcluir" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-[60] p-4 backdrop-blur-sm">
        <div class="bg-white rounded-[2rem] w-full max-w-md shadow-2xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-extrabold text-slate-800">Tem certeza que deseja excluir?</h3>
                <button type="button" onclick="toggleModal('modalExcluir')" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <p class="text-slate-500 mb-6">A exclusão não pode ser desfeita. Esta transação será removida permanentemente.</p>
            <div class="flex gap-4">
                <button type="button" onclick="toggleModal('modalExcluir')" class="flex-1 py-3 border border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition-all">Não</button>
                <a href="#" id="confirmDeleteLink" class="flex-1 py-3 bg-[#2dd4bf] text-white font-bold rounded-2xl hover:bg-teal-600 shadow-lg transition-all text-center flex items-center justify-center">Sim</a>
            </div>
        </div>
    </div>

    <!-- MODAL BAIXAR RELATÓRIO -->
    <div id="modalRelatorio" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-[60] p-4">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h3 class="text-xl font-bold text-slate-800">Baixar Relatório</h3>
                <button onclick="toggleRelatorioModal()" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="../Transacoes.php/gerar_pdf.php" method="GET" target="_blank" class="space-y-6">
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-3">Tipo de Transação</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="e" class="hidden peer">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-slate-800 peer-checked:text-white transition">Receita</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="s" class="hidden peer">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-slate-800 peer-checked:text-white transition">Despesa</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="todos" checked class="hidden peer">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-slate-800 peer-checked:text-white transition">Ambos</div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-3">Período</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="periodo" value="mensal" checked class="hidden peer" onclick="document.getElementById('campoMesRelatorio').style.display='block'">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-slate-800 peer-checked:text-white transition">Mensal</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="periodo" value="anual" class="hidden peer" onclick="document.getElementById('campoMesRelatorio').style.display='none'">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-slate-800 peer-checked:text-white transition">Anual</div>
                        </label>
                    </div>
                </div>

                <div id="campoMesRelatorio">
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1">Mês</label>
                    <select name="mes" class="w-full border rounded-xl px-4 py-2 outline-none focus:ring-2 focus:ring-teal-500 transition">
                        <option value="01">Janeiro</option><option value="02">Fevereiro</option>
                        <option value="03">Março</option><option value="04">Abril</option>
                        <option value="05" selected>Maio</option><option value="06">Junho</option>
                        <option value="07">Julho</option><option value="08">Agosto</option>
                        <option value="09">Setembro</option><option value="10">Outubro</option>
                        <option value="11">Novembro</option><option value="12">Dezembro</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1">Ano</label>
                    <select name="ano" class="w-full border rounded-xl px-4 py-2 outline-none focus:ring-2 focus:ring-teal-500 transition">
                        <option value="2026" selected>2026</option>
                        <option value="2025">2025</option>
                    </select>
                </div>

                <div class="flex gap-3 pt-4 border-t">
                    <button type="button" onclick="toggleRelatorioModal()" class="flex-1 py-3 border border-slate-300 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition">Cancelar</button>
                    <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-slate-800 to-teal-600 text-white font-bold rounded-xl shadow-lg hover:opacity-90 transition">
                        <i class="fas fa-download mr-2"></i> Baixar PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
    }

    document.getElementById('inputBusca')?.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const items = document.querySelectorAll('.item-transacao');
        let found = false;
        items.forEach(item => {
            const titulo = item.dataset.titulo || '';
            const categoria = item.dataset.categoria || '';
            const visible = titulo.includes(query) || categoria.includes(query);
            item.classList.toggle('hidden', !visible);
            if (visible) found = true;
        });
        document.getElementById('msgVazio')?.classList.toggle('hidden', found || query === '');
    });

    window.onclick = function(event) {
        const mRel = document.getElementById('modalRelatorio');
        const mTrans = document.getElementById('modalTransacao');
        const mExcluir = document.getElementById('modalExcluir');
        if (event.target == mRel) toggleModal('modalRelatorio');
        if (event.target == mTrans) toggleModal('modalTransacao');
        if (event.target == mExcluir) toggleModal('modalExcluir');
    }

    function toggleRelatorioModal() {
        const modal = document.getElementById('modalRelatorio');
        if (modal) {
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
    }

    document.querySelectorAll('.btnExcluirTransacao').forEach(button => {
        button.addEventListener('click', function() {
            const url = this.dataset.deleteUrl;
            const confirmLink = document.getElementById('confirmDeleteLink');
            if (confirmLink) {
                confirmLink.setAttribute('href', url);
                toggleModal('modalExcluir');
            }
        });
    });
    </script>
</body>
</html>