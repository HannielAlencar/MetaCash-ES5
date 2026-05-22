<?php
// O arquivo logica_dados.php deve estar na mesma pasta que este index.php
include 'logica_dados.php'; 
// Define o fuso horário para o padrão de Brasília/São Paulo
date_default_timezone_set('America/Sao_Paulo');
$receitas_mes = $dados_financeiros['receitas_mes'] ?? 0;
$despesas_mes = $dados_financeiros['despesas_mes'] ?? 0;
$saldo_total = $dados_financeiros['saldo_total'] ?? 0;
$transacoes_count = count($transacoes);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Transações</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../transacoes.css/style.css"> 
    <style>
        /* CSS para remover o spinner do input number */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
        
        /* Animação do Popup */
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
            20% { opacity: 1; transform: translateX(-50%) translateY(0); }
            80% { opacity: 1; transform: translateX(-50%) translateY(0); }
            100% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
        }
        .fade-in-out {
            animation: fadeInOut 3s ease-in-out forwards;
        }
    </style>
</head>
<body class="bg-gray-50">

    <div class="flex min-h-screen">
        <!-- SIDEBAR FIXA -->
        <aside class="w-64 bg-[#0f172a] text-white p-4 flex flex-col fixed h-screen shrink-0 z-40">
            <div class="flex items-center gap-3 mb-10 px-2 pt-2">
                <img src="./img/logoCyano.png" alt="MetaCash Logo" class="w-11 h-11 rounded-lg object-cover">
                <div class="flex flex-col">
                    <span class="font-bold text-xl leading-tight text-white">MetaCash</span>
                    <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Gestão Empresarial</span>
                </div>
            </div>

            <nav class="flex-1 space-y-2">
                <a href="../Dashboard/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    <i class="fas fa-th-large"></i><span class="font-medium">Dashboard</span>
                </a>
                
                <a href="index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#2dd4bf] text-white shadow-lg transition">
                    <i class="fas fa-exchange-alt"></i><span class="font-medium">Transações</span>
                </a>

                <button onclick="toggleRelatorioModal()" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition border border-transparent hover:border-slate-700 text-left">
                    <i class="fas fa-file-pdf"></i><span class="font-medium">Baixar Relatório</span>
                </button>
            </nav>

            <div class="mt-auto pt-6 border-t border-slate-800 space-y-4 pb-2">
                <a href="../Perfil.php/index.php" class="bg-[#1e3a5f]/40 p-3 rounded-2xl flex items-center gap-3 border border-slate-700/50 hover:bg-[#1e3a5f]/60 transition block group">
                    <div class="w-10 h-10 bg-[#2dd4bf] rounded-full flex items-center justify-center text-[#0f172a] font-bold text-lg shrink-0 group-hover:scale-105 transition-transform">U</div>
                    <div class="flex flex-col overflow-hidden">
                        <span class="text-sm font-bold truncate text-white">Usuário</span>
                        <span class="text-[10px] text-gray-400 truncate">usuario@exemplo.com</span>
                    </div>
                </a>
                <a href="/MetaCashCode/Login/Login.php/index.php" class="flex items-center gap-3 px-4 py-2 text-gray-400 hover:text-white transition group">
                    <i class="fas fa-sign-out-alt rotate-180 group-hover:text-red-400 transition-colors"></i>
                    <span class="font-medium">Sair</span>
                </a>
            </div>
        </aside>

        <main class="flex-1 p-10 ml-64">
            <header class="mb-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-4xl font-extrabold text-[#0f172a] tracking-tight">Transações</h1>
                        <p class="text-lg text-[#334155] mt-2">Gerencie suas finanças</p>
                    </div>
                    <button onclick="toggleModal()" class="bg-[#2dd4bf] text-white px-6 py-3 rounded-lg font-bold hover:bg-teal-600 shadow-md transition transform active:scale-95">
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
                            <option value="Vendas">Vendas</option>
                            <option value="Administrativo">Administrativo</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Salários">Salários</option>
                            <option value="Geral">Geral</option>
                        </select>
                        <i class="fas fa-filter absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <!-- CARDS DE RESUMO -->
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
                    $transacoes_lista = array_reverse((array)$transacoes, true);
                    foreach ($transacoes_lista as $id => $tr): 
                        $isEntrada = ($tr['tipo'] == 'e');
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
                                <p class="text-xs text-slate-400 uppercase font-semibold"><?= $tr['cat'] ?? 'Geral' ?> • <?= $tr['data'] ?> <?= date('H:i') ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="font-bold text-lg <?= $isEntrada ? 'text-[#2dd4bf]' : 'text-red-400' ?>">
                                <?= ($isEntrada ? '+' : '-') . ' ' . formatarMoeda($tr['valor']) ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL NOVA TRANSAÇÃO -->
    <div id="modalTransacao" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-50 p-4">
         <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6">
            <h3 class="text-xl font-bold mb-4 text-slate-800 border-b pb-4">Nova Transação</h3>
            <form id="formTransacao" action="../Dashboard/salvar_transacao.php" method="POST" class="space-y-4">
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
                        <option value="e">Entrada (+)</option>
                        <option value="s">Saída (-)</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Data</label>
                    <input type="date" name="data" value="<?php echo date('Y-m-d'); ?>" required class="w-full border rounded-xl px-4 py-2 mt-1 outline-none focus:ring-2 focus:ring-teal-500 transition">
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="toggleModal()" class="flex-1 py-3 text-slate-500 font-medium hover:bg-slate-50 rounded-xl transition">Cancelar</button>
                    <button type="button" onclick="adicionarEConfirmar()" class="flex-1 py-3 bg-[#2dd4bf] text-white font-bold rounded-xl hover:bg-teal-600 shadow-lg transition">Adicionar</button>
                </div>
            </form>
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
            
            <form action="gerar_pdf.php" method="GET" target="_blank" class="space-y-6">
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

    <!-- POPUP SUCESSO -->
    <div id="popupSucesso" class="fixed top-5 left-1/2 -translate-x-1/2 bg-teal-500 text-white px-6 py-3 rounded-xl shadow-lg hidden z-[100] font-bold">
        Transação cadastrada com sucesso
    </div>

    <script>
        // Função para mostrar popup ao recarregar a página
        window.onload = function() {
            if (localStorage.getItem('transacaoSucesso') === 'true') {
                const popup = document.getElementById('popupSucesso');
                popup.classList.remove('hidden');
                popup.classList.add('fade-in-out');
                
                setTimeout(() => {
                    popup.classList.add('hidden');
                    popup.classList.remove('fade-in-out');
                    localStorage.removeItem('transacaoSucesso');
                }, 3000);
            }
        };

        function adicionarEConfirmar() {
            // Marca no navegador que a transação foi iniciada
            localStorage.setItem('transacaoSucesso', 'true');
            document.getElementById('formTransacao').submit();
        }

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

        // Fecha modais ao clicar fora
        window.onclick = function(event) {
            const mRel = document.getElementById('modalRelatorio');
            const mTra = document.getElementById('modalTransacao');
            if (event.target == mRel) toggleRelatorioModal();
            if (event.target == mTra) toggleModal();
        }
    </script>
    <script src="transacoes.js/script.js"></script> 
</body>
</html>