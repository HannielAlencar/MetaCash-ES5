<?php
// 1. Inicia a sessão antes de qualquer envio de cabeçalho HTML
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Importa a sua conexão configurada com o Neon DB
require_once '../config.php'; 

$id_usuario = $_SESSION['usuario_id'] ?? null;
// Captura o perfil do usuário da sessão
$perfil_usuario = $_SESSION['nome_completo'] ?? 'Administrador'; 
$registros = [];

try {
    // 3. Busca os dados reais diretamente da tabela do Neon DB
    $sql = "SELECT acao, categoria, descricao, data_criacao FROM historico WHERE usuario_id = ? ORDER BY data_criacao DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario]);
    $dados_banco = $stmt->fetchAll();

    // Função interna para mapear dinamicamente as cores das tags
    function obterCorTag($acao) {
        switch (mb_strtolower(trim($acao), 'UTF-8')) {
            case 'criação': 
                return 'bg-teal-100 text-teal-800 border border-teal-200';
            case 'edição': 
                return 'bg-amber-100 text-amber-800 border border-amber-200';
            case 'exclusão': 
                return 'bg-rose-100 text-rose-800 border border-rose-200';
            default: 
                return 'bg-slate-100 text-slate-700 border border-slate-200';
        }
    }

    // 4. Converte os dados do banco para o formato exato que o seu HTML/JS espera
    foreach ($dados_banco as $item) {
        $registros[] = [
            'tag'        => $item['acao'],
            'tag_color'  => obterCorTag($item['acao']),
            'cat'        => $item['categoria'],
            'desc'       => $item['descricao'],
            'data'       => date('d/m/Y', strtotime($item['data_criacao'])),
            'hora'       => date('H:i:s', strtotime($item['data_criacao'])),
            'perfil'     => $perfil_usuario // Chave corrigida para 'perfil'
        ];
    }

} catch (PDOException $e) {
    $registros = [];
    error_log("Erro ao carregar o histórico: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Histórico de Alterações</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
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
        :root {
            --meta-menu: #0F2440;
            --meta-btn1: #204C73;
            --meta-destaque: #24A6B6;
            --meta-btn2: #35C59A;
            --meta-clara: #5DA4C0;
            --meta-fundo: #FDFEFB;
        }
        body { font-family: 'Inter', sans-serif; }
        .sidebar a:hover { color: white; }
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
            console.error("Erro ao sincronizar localStorage:", erro);
        }
    </script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="historico.css/style.css">
</head>
<body class="bg-meta-fundo transition-colors duration-200 min-h-screen">
    <div class="flex min-h-screen">
        
        <?php include_once '../includes/sidebarGerente.php'; ?>

        <main class="flex-1 p-10 ml-64">
            <header class="mb-8">
                <h1 class="text-4xl font-extrabold text-meta-menu tracking-tight transition-colors duration-200">Histórico de Alterações</h1>
                <p class="text-sm text-slate-500 mt-2">Acompanhe todas as mudanças registradas no ecossistema do sistema.</p>
            </header>

            <section class="mb-6 p-6 bg-white rounded-3xl shadow-sm border border-gray-200">
                <div class="grid grid-cols-12 gap-4 items-end">
                    <div class="col-span-7">
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Buscar por palavra-chave</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" id="inputBusca" placeholder="Descrição, categoria ou detalhes..." class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-2xl outline-none focus:ring-2 focus:ring-meta-destaque text-sm transition-all">
                        </div>
                    </div>
                    <div class="col-span-3">
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Tipo de Alteração</label>
                        <select id="filtroTipo" class="w-full px-4 py-3 border border-gray-200 rounded-2xl outline-none focus:ring-2 focus:ring-meta-destaque text-sm bg-white transition-all cursor-pointer">
                            <option value="todos">Todos os tipos</option>
                            <option value="criação">Criação</option>
                            <option value="edição">Edição</option>
                            <option value="exclusão">Exclusão</option>
                            <option value="outros">Outros</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Filtrar por Data</label>
                        <input type="text" id="filtroData" placeholder="dd/mm/aaaa" class="w-full px-4 py-3 border border-gray-200 rounded-2xl outline-none focus:ring-2 focus:ring-meta-destaque text-sm bg-white transition-all">
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-meta-menu text-white px-6 py-4 flex justify-between items-center transition-colors duration-200">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-history text-meta-destaque"></i>
                        <span class="font-bold uppercase text-sm tracking-wide">Registros de Alterações</span>
                    </div>
                    <span class="text-xs text-slate-300 font-medium" id="contadorRegistros"><?php echo count($registros); ?> registros</span>
                </div>
                
                <div class="divide-y divide-gray-100" id="containerRegistros">
                    <?php foreach ($registros as $index => $reg): ?>
                        <div class="item-registro p-6 flex flex-col gap-4 lg:flex-row lg:justify-between lg:items-center hover:bg-slate-50/80 transition"
                             data-desc="<?= htmlspecialchars(strtolower($reg['desc'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                             data-tipo="<?= htmlspecialchars(strtolower($reg['tag'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                             data-data="<?= htmlspecialchars(strtolower($reg['data'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                            
                            <div class="space-y-3 flex-1">
                                <div class="flex flex-wrap gap-2 items-center">
                                    <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase <?= $reg['tag_color'] ?>">
                                        <?= htmlspecialchars($reg['tag'] ?? 'Alteração', ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                    <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase bg-slate-100 text-slate-600 border border-slate-200">
                                        <?= htmlspecialchars($reg['cat'] ?? 'Sistema', ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </div>
                                <p class="text-sm text-slate-700 font-medium leading-relaxed">
                                    <?= htmlspecialchars($reg['desc'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </p>
                                <div class="flex flex-wrap gap-4 text-xs text-slate-400">
                                    <span class="flex items-center gap-1.5 capitalize"><i class="far fa-user"></i> <?= htmlspecialchars($reg['perfil'] ?? 'Administrador', ENT_QUOTES, 'UTF-8') ?></span>
                                    <span class="flex items-center gap-1.5"><i class="far fa-clock"></i> <?= htmlspecialchars($reg['data'] ?? date('d/m/Y'), ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars($reg['hora'] ?? '00:00:00', ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                            </div>
                            
                            <button onclick="removerRegistro(this)" class="text-slate-400 hover:text-red-500 rounded-full p-2.5 hover:bg-red-50 transition-all self-end lg:self-center" title="Remover visualmente">
                                <i class="fas fa-trash-alt text-sm"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div id="msgVazio" class="<?= count($registros) === 0 ? '' : 'hidden' ?> p-20 text-center text-slate-400">
                    <i class="fas fa-search fa-3x mb-4 block opacity-30 text-meta-clara"></i>
                    <p class="font-medium text-slate-600">Nenhum registro encontrado para os filtros selecionados.</p>
                    <p class="text-xs text-slate-400 mt-1">Verifique os termos digitados ou mude os seletores.</p>
                </div>
            </section>
        </main>
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
            if (event.target == mRel) toggleModal('modalRelatorio');
        }

        const inputBusca = document.getElementById('inputBusca');
        const filtroTipo = document.getElementById('filtroTipo');
        const filtroData = document.getElementById('filtroData');
        const msgVazio = document.getElementById('msgVazio');
        const contadorRegistros = document.getElementById('contadorRegistros');

        function filtrarTabela() {
            const buscaQuery = inputBusca.value.toLowerCase().trim();
            const tipoQuery = filtroTipo.value.toLowerCase();
            const dataQuery = filtroData.value.trim();
            
            const items = document.querySelectorAll('.item-registro');
            let visiveis = 0;

            items.forEach(item => {
                const desc = item.dataset.desc || '';
                const tipo = item.dataset.tipo || '';
                const data = item.dataset.data || '';

                const matchesBusca = buscaQuery === '' || desc.includes(buscaQuery) || tipo.includes(buscaQuery) || data.includes(buscaQuery);
                
                let matchesTipo = false;
                if (tipoQuery === 'todos') {
                    matchesTipo = true;
                } else if (tipoQuery === 'outros') {
                    matchesTipo = (tipo !== 'criação' && tipo !== 'edição' && tipo !== 'exclusão');
                } else {
                    matchesTipo = (tipo === tipoQuery);
                }

                const matchesData = dataQuery === '' || data.includes(dataQuery);

                if (matchesBusca && matchesTipo && matchesData) {
                    item.classList.remove('hidden');
                    visiveis++;
                } else {
                    item.classList.add('hidden');
                }
            });

            if (visiveis === 0) {
                msgVazio.classList.remove('hidden');
            } else {
                msgVazio.classList.add('hidden');
            }

            contadorRegistros.innerText = visiveis + (visiveis === 1 ? ' registro' : ' registros');
        }

        inputBusca.addEventListener('input', filtrarTabela);
        filtroTipo.addEventListener('change', filtrarTabela);
        filtroData.addEventListener('input', filtrarTabela);

        function removerRegistro(button) {
            const row = button.closest('.item-registro');
            if (confirm('Tem certeza de que deseja remover permanentemente este registro do histórico visual?')) {
                row.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                row.style.opacity = '0';
                row.style.transform = 'translateX(30px)';
                
                setTimeout(() => {
                    row.remove();
                    filtrarTabela();
                }, 300);
            }
        }
    </script>
</body>
</html>