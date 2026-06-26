<?php
// LINHA 1: Inicia a sessão com segurança
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. IMPORTA A CONEXÃO COM O BANCO DE DADOS
require_once '../config.php'; 

// Trava de segurança
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['nivel_permissao']) || ($_SESSION['nivel_permissao'] !== 'Gerente' && $_SESSION['nivel_permissao'] !== 'Admin')) {
    header("Location: dashboardUsuario.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];
$id_empresa = $_SESSION['id_empresa'] ?? 0;
$transacoes = [];
$receita = 0;
$despesa = 0;

// Busca categorias personalizadas
$categorias_banco = [];
try {
    $stmtCat = $pdo->prepare("SELECT nome_categoria FROM categoria WHERE id_empresa = ? OR id_empresa IS NULL OR id_empresa = 0 ORDER BY nome_categoria ASC");
    $stmtCat->execute([$id_empresa]);
    $categorias_banco = $stmtCat->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {}

date_default_timezone_set('America/Sao_Paulo');

// =========================================================================
// BUSCA E CONFIGURAÇÃO DE VARIÁVEIS DO BANCO
// =========================================================================
$configs_pagina = [];
try {
    $stmtConf = $pdo->prepare("SELECT chave_config, valor_config FROM configs_paginas WHERE id_empresa = :empresa");
    $stmtConf->execute([':empresa' => $id_empresa]);
    while ($row = $stmtConf->fetch(PDO::FETCH_ASSOC)) {
        $configs_pagina[$row['chave_config']] = $row['valor_config'];
    }
} catch (PDOException $e) {
    error_log("Erro ao buscar configurações: " . $e->getMessage());
}

// Valores padrão (Default) caso não existam no banco
$padroes = [
    'titulo_pagina' => 'Transações',
    'subtitulo_pagina' => 'Gerencie suas finanças',
    'texto_botao' => '+ Adicionar Transação',
    'placeholder_busca' => 'Buscar transações...',
    'tamanho_fonte' => 'medio',
    'fonte_pagina' => 'Inter',
    'vis_receitas' => '1',
    'vis_despesas' => '1',
    'vis_saldo' => '1',
    'vis_lista' => '1'
];

// Mescla o que veio do banco com os padrões
$cfg = array_merge($padroes, $configs_pagina);

// Mapeamento de tamanho para classes Tailwind
$tamanho_map = [
    'pequeno' => 'text-sm',
    'medio'   => 'text-base',
    'grande'  => 'text-lg',
    'extra'   => 'text-xl'
];
$classe_tamanho_fonte = $tamanho_map[$cfg['tamanho_fonte']] ?? 'text-base';
$fonte_ativa = ($cfg['fonte_pagina'] === 'Roboto') ? 'Roboto' : 'Inter';

// =========================================================================

try {
    $sql = "SELECT 
                t.id_transacao,
                t.descricao_transacao AS titulo, 
                t.valor_transacao AS valor, 
                t.tipo_transacao AS tipo, 
                c.nome_categoria AS cat,
                t.data_transacao,
                t.id_transacao AS id
            FROM transacoes t
            LEFT JOIN categoria c ON t.id_categoria = c.id_categoria
            WHERE t.id_empresa = :empresa
            ORDER BY t.data_transacao DESC, t.id_transacao DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':empresa' => $id_empresa]);
    $transacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($transacoes as &$tr) {
        $tr['data'] = date('d/m/Y', strtotime($tr['data_transacao']));
    }
    unset($tr);

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

// ... após o seu require_once e verificação de sessão

$id_empresa = $_SESSION['id_empresa'] ?? 0;
$saldo_inicial = 0;

// 1. BUSCAR SALDO INICIAL NO BANCO
try {
    $stmtSaldo = $pdo->prepare("SELECT saldo_inicial FROM empresas WHERE id_empresa = ?");
    $stmtSaldo->execute([$id_empresa]);
    $dadosEmpresa = $stmtSaldo->fetch(PDO::FETCH_ASSOC);
    
    if ($dadosEmpresa) {
        $saldo_inicial = (float)$dadosEmpresa['saldo_inicial'];
    }
} catch (PDOException $e) {
    error_log("Erro ao buscar saldo inicial: " . $e->getMessage());
}

// ... (resto do seu código que busca as transações e calcula $receita e $despesa)

// 2. CALCULAR O SALDO TOTAL CORRETAMENTE
// Aqui está o segredo: somar o inicial com o saldo do período
$saldoPeriodo = $receita - $despesa;
$saldo_total = $saldo_inicial + $saldoPeriodo; 

// ... (resto do código)
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Transações Gerente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                // Isso vai forçar o Tailwind a usar a sua fonte como padrão
                fontFamily: {
                    sans: ['<?= $fonte_ativa ?>', 'sans-serif'],
                },
                colors: {
                    meta: {
                        menu: 'var(--meta-menu)',
                        btn1: 'var(--meta-btn1)',
                        destaque: 'var(--meta-destaque)',
                        btn2: 'var(--meta-btn2)',
                        clara: 'var(--meta-clara)',
                        fundo: 'var(--meta-fundo)',
                    }
                }
            }
        }
    }
    </script>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap');
    
    :root {
        --meta-menu: #0F2440;
        --meta-btn1: #204C73;
        --meta-destaque: #24A6B6;
        --meta-btn2: #35C59A;
        --meta-clara: #5DA4C0;
        --meta-fundo: #FDFEFB;
        /* Definimos a variável da fonte aqui */
        --meta-font: '<?= $fonte_ativa ?>', sans-serif;
    }

    /* Forçamos a aplicação da fonte em todos os elementos.
       O !important aqui é necessário porque o Tailwind injeta 
       estilos diretamente nos elementos (botões, inputs, etc).
    */
    body, h1, h2, h3, h4, h5, h6, button, input, select, textarea, p, span, div {
        font-family: var(--meta-font) !important;
    }
</style>
    <script>
    try {
        const temaSalvo = localStorage.getItem('metaCashTheme');
        if (temaSalvo) {
            const cores = JSON.parse(temaSalvo);
            const raiz = document.documentElement;
            if(cores.menu) raiz.style.setProperty('--meta-menu', cores.menu);
            if(cores.btn1) raiz.style.setProperty('--meta-btn1', cores.btn1);
            if(cores.destaque) raiz.style.setProperty('--meta-destaque', cores.destaque);
            if(cores.btn2) raiz.style.setProperty('--meta-btn2', cores.btn2);
            if(cores.clara) raiz.style.setProperty('--meta-clara', cores.clara);
            if(cores.fundo) raiz.style.setProperty('--meta-fundo', cores.fundo);
        }
    } catch (erro) {
        console.error("Erro ao ler localStorage do tema:", erro);
    }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/transacoesGerente.css"> 
</head>
<body class="bg-meta-fundo transition-colors duration-200 min-h-screen antialiased flex overflow-x-hidden w-full <?= $classe_tamanho_fonte ?>">

   <?php include_once '../includes/sidebarGerente.php'; ?>

    <main class="flex-1 p-8 ml-64 min-h-screen border-box overflow-y-auto w-full">
        <div class="max-w-full w-full mx-auto">
            <header class="mb-8">
                  <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-4xl font-extrabold text-[#0f172a] tracking-tight"><?= htmlspecialchars($cfg['titulo_pagina']) ?></h1>
                        <p class="text-lg text-[#334155] mt-2"><?= htmlspecialchars($cfg['subtitulo_pagina']) ?></p>
                    </div>
                    <button onclick="toggleModal('modalTransacao')" class="bg-gradient-to-r from-meta-menu to-meta-destaque text-white px-6 py-3 rounded-lg font-bold shadow-lg hover:opacity-90 transition transform active:scale-95">
                        <?= htmlspecialchars($cfg['texto_botao']) ?>
                    </button>
               </div>

                <div class="mt-8 flex flex-col md:flex-row gap-4 p-4 bg-white rounded-2xl border border-gray-200 shadow-sm items-center">
                    <div class="relative flex-1 w-full">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" id="inputBusca" placeholder="<?= htmlspecialchars($cfg['placeholder_busca']) ?>" class="w-full pl-12 pr-4 py-3 rounded-xl border-none bg-gray-50 outline-none focus:ring-2 focus:ring-teal-500 transition">
                    </div>
                    <div class="relative w-full md:w-auto">
                        <select id="filtroCategoria" class="w-full md:w-48 pl-10 pr-8 py-3 rounded-xl border-none bg-gray-50 appearance-none outline-none focus:ring-2 focus:ring-teal-500 transition cursor-pointer">
                            <option value="todas">Todas Categorias</option>
                            <?php foreach ($categorias_banco as $cat): ?>
                                <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i class="fas fa-filter absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                    <?php if ($cfg['vis_receitas'] == '1'): ?>
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <p class="text-xs font-bold text-slate-400 uppercase">Total de Receitas</p>
                        <p class="text-2xl font-bold text-teal-500 mt-1"><?= formatarMoeda($receitas_mes) ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if ($cfg['vis_despesas'] == '1'): ?>
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <p class="text-xs font-bold text-slate-400 uppercase">Total de Despesas</p>
                        <p class="text-2xl font-bold text-red-400 mt-1"><?= formatarMoeda($despesas_mes) ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if ($cfg['vis_saldo'] == '1'): ?>
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <p class="text-xs font-bold text-slate-400 uppercase">Saldo do Período</p>
                        <p class="text-2xl font-bold text-slate-800 mt-1"><?= formatarMoeda($saldo_total) ?></p>
                    </div>
                    <?php endif; ?>

                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <p class="text-xs font-bold text-slate-400 uppercase">Transações no Período</p>
                        <p class="text-2xl font-bold text-slate-800 mt-1"><?= $transacoes_count ?></p>
                    </div>
                </div>
            </header>

            <?php if ($cfg['vis_lista'] == '1'): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <?php 
                    if (empty($transacoes)) {
                        echo "<p class='p-6 text-center text-gray-500'>Nenhuma transação encontrada no ID Empresa " . htmlspecialchars($id_empresa) . ".</p>";
                    }
                ?>
                <div id="containerTransacoes" class="divide-y divide-gray-50">
                    <?php 
                    $transacoes_lista = array_reverse((array)$transacoes, true);
                    foreach ($transacoes_lista as $id => $tr): 
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
                                <p class="font-bold text-slate-800"><?= $tr['titulo'] ?></p>
                                <p class="text-xs text-slate-400 uppercase font-semibold"><?= $tr['cat'] ?? 'Geral' ?> • <?= $tr['data'] ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="font-bold text-lg <?= $isEntrada ? 'text-[#2dd4bf]' : 'text-red-400' ?>">
                                <?= ($isEntrada ? '+' : '-') . ' ' . formatarMoeda($tr['valor']) ?>
                            </div>
                            <a href="../app/deletarTransacao.php?id=<?= $tr['id_transacao'] ?>" 
                               onclick="return confirm('Tem certeza que deseja excluir esta transação?')"
                               class="text-slate-300 hover:text-red-500 transition ml-2">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <div id="modalTransacao" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-50 p-4 backdrop-blur-sm">
         <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6">
            <div class="flex justify-between items-center mb-4 border-b pb-4">
                <h3 class="text-xl font-bold text-slate-800">Nova Transação</h3>
                <button onclick="toggleModal('modalTransacao')" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <form id="formTransacao" action="../app/salvarTransacaoGerente.php" data-url="<?= '../app/salvarTransacaoGerente.php' ?>" method="POST" class="space-y-4" onsubmit="return adicionarEConfirmar(event)">
                <input type="hidden" name="id_empresa_oculto" value="<?= $id_empresa ?>">
                <input type="hidden" name="id_usuario_oculto" value="<?= $id_usuario ?>">
                
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
                        <?php foreach ($categorias_banco as $cat): ?>
                            <?php if ($cat !== 'Geral'): ?>
                                <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Tipo</label>
                    <select name="tipo" class="w-full border rounded-xl px-4 py-2 mt-1 outline-none focus:ring-2 focus:ring-teal-500 transition">
                        <option value="e">Entrada (+)</option>
                        <option value="s">Saída (-)</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Data</label>
                    <input type="date" name="data" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" required class="w-full border rounded-xl px-4 py-2 mt-1 outline-none focus:ring-2 focus:ring-teal-500 transition">
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="toggleModal('modalTransacao')" class="flex-1 py-3 text-slate-500 font-medium hover:bg-slate-50 rounded-xl transition">Cancelar</button>
                    <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-meta-menu to-meta-destaque text-white font-bold rounded-xl shadow-lg hover:opacity-90 transition">Adicionar</button>
                </div>
            </form>
         </div>
    </div>

    <div id="modalRelatorio" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-[60] p-4 backdrop-blur-sm">
        <div class="bg-white rounded-[2rem] w-full max-w-md shadow-2xl p-8">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-extrabold text-slate-800">Baixar Relatório</h3>
                <button onclick="toggleModal('modalRelatorio')" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form action="/MetaCashCode/Usuario/Transacoes.php/gerar_pdf.php" method="GET" target="_blank" class="space-y-6">
                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase block mb-3 tracking-widest">Tipo de Transação</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="e" class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition-all">Receita</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="s" class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition-all">Despesa</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="todos" checked class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition-all">Ambos</div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase block mb-3 tracking-widest">Período</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="periodo" value="mensal" checked class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition-all">Mensal</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="periodo" value="anual" class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition-all">Anual</div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase block mb-3 tracking-widest">Mês</label>
                    <select name="mes" class="w-full p-4 rounded-2xl border border-slate-200 bg-white text-slate-700 font-medium appearance-none focus:outline-none focus:ring-2 focus:ring-meta-destaque/20 transition-all cursor-pointer">
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
                    <label class="text-[11px] font-bold text-slate-400 uppercase block mb-3 tracking-widest">Ano</label>
                    <select name="ano" class="w-full p-4 rounded-2xl border border-slate-200 bg-white text-slate-700 font-medium appearance-none focus:outline-none focus:ring-2 focus:ring-meta-destaque/20 transition-all cursor-pointer">
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026" selected>2026</option>
                    </select>
                </div>

                <div class="flex gap-4 pt-6 border-t border-slate-100">
                    <button type="button" onclick="toggleModal('modalRelatorio')" class="flex-1 py-4 border border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition-all">Cancelar</button>
                    <button type="submit" class="flex-1 py-4 bg-meta-destaque hover:opacity-90 text-white font-bold rounded-2xl shadow-lg transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-download"></i> Baixar PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="popupSucesso" class="fixed top-5 left-1/2 -translate-x-1/2 bg-teal-500 text-white px-6 py-3 rounded-xl shadow-lg hidden z-[100] font-bold" style="display: none;">
        Transação cadastrada com sucesso
    </div>

    <script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
    }
    window.onclick = function(event) {
        const mRel = document.getElementById('modalRelatorio');
        const mTrans = document.getElementById('modalTransacao');
        if (event.target === mRel) toggleModal('modalRelatorio');
        if (event.target === mTrans) toggleModal('modalTransacao');
    }
    </script>

    <script>
    window.addEventListener('load', function() {
        const novaFonte = '<?= $fonte_ativa ?>';
        // Aplica a fonte em tudo, forçando a substituição
        document.body.style.setProperty('font-family', novaFonte + ', sans-serif', 'important');
        
        // Aplica também em elementos específicos que o Tailwind costuma "sequestrar"
        const elementos = document.querySelectorAll('h1, h2, h3, h4, button, input, select, textarea, p, span, div');
        elementos.forEach(el => {
            el.style.setProperty('font-family', novaFonte + ', sans-serif', 'important');
        });
    });
    </script>

    <script src="../assets/js/transacoes.js"></script> 
</body>
</html>