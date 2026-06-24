<?php
// index.php

<<<<<<< Updated upstream
// Simulação de dados (Poderia vir de um banco de dados)
$registros = [
    ['tag' => 'Criação', 'tag_color' => 'bg-green-100 text-green-600', 'cat' => 'Transação', 'desc' => 'Nova transação de receita: Venda de Produtos', 'data' => '13/03/2026'],
    ['tag' => 'Edição', 'tag_color' => 'bg-blue-100 text-blue-600', 'cat' => 'Configurações', 'desc' => 'Atualização das configurações de empresa', 'data' => '14/03/2026'],
    ['tag' => 'Criação', 'tag_color' => 'bg-green-100 text-green-600', 'cat' => 'Membro da Equipe', 'desc' => 'Novo membro adicionado à equipe: Maria Santos', 'data' => '15/03/2026'],
    ['tag' => 'Edição', 'tag_color' => 'bg-blue-100 text-blue-600', 'cat' => 'Transação', 'desc' => 'Transação editada: Atualização de valor', 'data' => '16/03/2026'],
    ['tag' => 'Exclusão', 'tag_color' => 'bg-red-100 text-red-600', 'cat' => 'Transação', 'desc' => 'Transação excluída: Duplicada', 'data' => '17/03/2026'],
];
=======
// 2. Importa a sua conexão configurada com o Neon DB
require_once '../config.php'; 

$id_usuario = $_SESSION['id_usuario'] ?? null;
$registros = [];

try {
    // 3. Busca os dados reais diretamente da tabela do Neon DB
    // ---> ADICIONADO: 'id_historico' logo após o SELECT <---
    $sql = "SELECT id_historico, acao, categoria, descricao, data_criacao FROM historico WHERE id_usuario = ? ORDER BY data_criacao DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario]);
    $dados_banco = $stmt->fetchAll();

    // Função interna para mapear dinamicamente as cores das tags do seu front-end
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
            // ---> ADICIONADO: A linha abaixo que guarda o ID <---
            'id'        => $item['id_historico'], 
            'tag'       => $item['acao'],
            'tag_color' => obterCorTag($item['acao']),
            'cat'       => $item['categoria'],
            'desc'      => $item['descricao'],
            'data'      => date('d/m/Y', strtotime($item['data_criacao'])),
            'hora'      => date('H:i:s', strtotime($item['data_criacao']))
        ];
    }

} catch (PDOException $e) {
    // Fallback de segurança
    $registros = [];
    error_log("Erro ao carregar o histórico: " . $e->getMessage());
}
>>>>>>> Stashed changes
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Histórico de Alterações</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/historico.css">
</head>
<body class="flex min-h-screen">

<<<<<<< Updated upstream
    <aside class="w-64 sidebar text-slate-300 flex flex-col p-4 space-y-6">
        <div class="flex items-center space-x-2 px-2 py-4">
            <div class="bg-teal-500 p-2 rounded-lg text-white">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div>
                <h1 class="font-bold text-white leading-none">MetaCash</h1>
                <span class="text-xs text-slate-400">Gestão Empresarial</span>
            </div>
=======
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
                            data-id="<?= htmlspecialchars($reg['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
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
                                    <span class="flex items-center gap-1.5"><i class="far fa-user"></i> Administrador</span>
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


    <div id="modalConfirmacao" class="fixed inset-0 bg-slate-900/40 hidden items-center justify-center z-[70] p-4 backdrop-blur-sm">
        <div id="modalContentConf" class="bg-white rounded-[2rem] w-full max-w-[390px] shadow-2xl overflow-hidden border border-slate-100 transform scale-95 transition-all duration-300">
            <div class="p-8 pb-5 flex justify-between items-start">
                <h3 id="modalTitulo" class="text-xl font-bold text-[#0f2440] leading-snug tracking-tight">Tem certeza?</h3>
                <button onclick="fecharModalConf()" class="text-slate-400 hover:text-slate-600 transition-colors mt-1">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="border-b-2 border-[#0b192c] mx-8"></div>
            
            <div class="p-8 pt-6 flex gap-4">
                <button onclick="fecharModalConf()" class="flex-1 py-3 border-2 border-[#0f2440] text-[#0f2440] font-bold rounded-2xl hover:bg-slate-50 transition-all text-sm">
                    Não
                </button>
                <button id="btnConfirmarAcao" class="flex-1 py-3 bg-[#ff3b30] text-white font-bold rounded-2xl hover:bg-[#e03128] transition-all text-sm shadow-[0_4px_12px_rgba(255,59,48,0.25)]">
                    Sim
                </button>
            </div>
>>>>>>> Stashed changes
        </div>

        <nav class="flex-1 space-y-1">
            <a href="#" class="flex items-center space-x-3 px-3 py-2 hover:text-white"><i class="fas fa-th-large w-5"></i> <span>Dashboard</span></a>
            <a href="#" class="flex items-center space-x-3 px-3 py-2 hover:text-white"><i class="fas fa-exchange-alt w-5"></i> <span>Transações</span></a>
            <a href="#" class="flex items-center space-x-3 px-3 py-2 hover:text-white"><i class="fas fa-users w-5"></i> <span>Equipe</span></a>
            <a href="#" class="flex items-center space-x-3 px-3 py-2 hover:text-white"><i class="fas fa-file-alt w-5"></i> <span>Gerenciar Páginas</span></a>
            <a href="#" class="flex items-center space-x-3 px-3 py-2 active-nav"><i class="fas fa-history w-5"></i> <span>Histórico</span></a>
            <a href="#" class="flex items-center space-x-3 px-3 py-2 hover:text-white"><i class="fas fa-cog w-5"></i> <span>Configurações</span></a>
        </nav>

        <button class="w-full bg-slate-700 hover:bg-slate-600 text-white py-2 rounded-lg text-sm">Baixar Relatório</button>

        <div class="pt-4 border-t border-slate-700">
            <div class="bg-slate-800 p-3 rounded-xl flex items-center space-x-3">
                <div class="bg-teal-500 w-8 h-8 rounded-full flex items-center justify-center text-white"><i class="fas fa-user"></i></div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-white truncate">Usuário</p>
                    <p class="text-[10px] text-slate-400 truncate">usuario@exemplo.com</p>
                </div>
            </div>
            <a href="#" class="flex items-center space-x-2 mt-4 px-3 text-sm hover:text-white"><i class="fas fa-sign-out-alt"></i> <span>Sair</span></a>
        </div>
    </aside>

    <main class="flex-1 p-8 overflow-y-auto">
        <header class="mb-8">
            <div class="flex items-center space-x-2 text-teal-600 mb-1">
                <i class="fas fa-history text-2xl"></i>
                <h2 class="text-2xl font-bold text-slate-800">Histórico de Alterações</h2>
            </div>
            <p class="text-slate-500 text-sm">Registro completo de todas as modificações na plataforma</p>
        </header>

        <section class="card p-6 mb-6">
            <div class="flex items-center space-x-2 mb-4 text-teal-600 font-medium">
                <i class="fas fa-filter"></i> <span>Filtros</span>
            </div>
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-7">
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Buscar</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                        <input type="text" placeholder="Descrição, usuário ou e-mail..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 outline-none text-sm">
                    </div>
                </div>
                <div class="col-span-3">
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Tipo de Alteração</label>
                    <select class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 outline-none text-sm bg-white">
                        <option>Todos os tipos</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Data de Alteração</label>
                    <input type="text" placeholder="dd/mm/aaaa" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 outline-none text-sm">
                </div>
            </div>
        </section>

        <section class="card overflow-hidden">
            <div class="bg-[#1e293b] text-white px-6 py-3 flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-list-alt"></i>
                    <span class="font-bold text-sm">Registros de Alterações</span>
                </div>
                <span class="text-xs text-slate-400"><?php echo count($registros); ?> registros</span>
            </div>

<<<<<<< Updated upstream
            <div class="divide-y">
                <?php foreach ($registros as $reg): ?>
                <div class="p-4 flex justify-between items-start hover:bg-slate-50 transition-colors">
                    <div class="space-y-2">
                        <div class="flex space-x-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase <?= $reg['tag_color'] ?>"><?= $reg['tag'] ?></span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-sky-100 text-sky-600"><?= $reg['cat'] ?></span>
                        </div>
                        <p class="text-sm font-semibold text-slate-700"><?= $reg['desc'] ?></p>
                        <div class="flex items-center space-x-4 text-xs text-slate-400">
                            <span class="flex items-center space-x-1"><i class="far fa-user"></i> <span>João Silva</span></span>
                            <span class="flex items-center space-x-1"><i class="far fa-clock"></i> <span><?= $reg['data'] ?>, 18:14:13</span></span>
                        </div>
                    </div>
                    <button class="text-red-400 hover:text-red-600"><i class="fas fa-trash-alt"></i></button>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
=======
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

        // --- LÓGICA DO POP-UP DE CONFIRMAÇÃO ---
        let elementoAlvo = null; // Guarda qual linha foi clicada

        function abrirModalConf(titulo, botao) {
            elementoAlvo = botao;
            document.getElementById('modalTitulo').textContent = titulo;
            const modal = document.getElementById('modalConfirmacao');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                document.getElementById('modalContentConf').classList.remove('scale-95');
                document.getElementById('modalContentConf').classList.add('scale-100');
            }, 10);
        }

        function fecharModalConf() {
            const modal = document.getElementById('modalConfirmacao');
            document.getElementById('modalContentConf').classList.remove('scale-100');
            document.getElementById('modalContentConf').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                elementoAlvo = null;
            }, 150);
        }

        // Esta é a função chamada pelo clique no botão da lixeira do HTML
        function removerRegistro(button) {
            const row = button.closest('.item-registro');
            // Pega o texto da descrição (o que está dentro da tag <p>)
            let nomeItem = row.querySelector('p').textContent.trim();
            
            // Como a descrição pode ser longa, cortamos para não quebrar o pop-up
            if (nomeItem.length > 40) {
                nomeItem = nomeItem.substring(0, 40) + '...';
            }

            abrirModalConf(`Tem certeza que deseja remover "${nomeItem}" do histórico?`, button);
        }

        // Ação de confirmar e remover (Botão SIM)
        document.getElementById('btnConfirmarAcao').addEventListener('click', function() {
            if (!elementoAlvo) return;
            
            const row = elementoAlvo.closest('.item-registro');
            const idRegistro = row.getAttribute('data-id'); // Pega o ID que adicionamos no HTML
            
            // Fecha o modal imediatamente
            fecharModalConf();

            // Envia a ordem de exclusão para o servidor
            fetch('../app/deletarHistorico.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: idRegistro })
            })
            .then(res => res.json())
            .then(data => {
                if(data.sucesso) {
                    // Se o banco confirmou a exclusão, fazemos a animação e apagamos do front
                    row.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(30px)';
                    
                    setTimeout(() => {
                        row.remove();
                        filtrarTabela();
                    }, 300);
                } else {
                    alert('Erro ao excluir no banco: ' + data.erro);
                }
            })
            .catch(erro => {
                console.error('Erro na requisição:', erro);
                alert('Ocorreu um erro ao tentar conectar com o servidor.');
            });
        });
    </script>
>>>>>>> Stashed changes
</body>
</html>
