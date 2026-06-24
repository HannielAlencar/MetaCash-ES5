<?php
// Inicia a sessão antes de qualquer envio de cabeçalho HTML
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Trava de segurança corrigida: impede acesso se não estiver logado OU se não possuir o nível exigido
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['nivel_permissao']) || ($_SESSION['nivel_permissao'] !== 'Gerente' && $_SESSION['nivel_permissao'] !== 'Admin')) {
    header("Location: dashboardUsuario.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="pt-br" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Gerenciamento de Páginas</title>
    
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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        :root {
            --meta-menu: #0F2440;
            --meta-btn1: #204C73;
            --meta-destaque: #24A6B6;
            --meta-btn2: #35C59A;
            --meta-clara: #5DA4C0;
            --meta-fundo: #FDFEFB;
        }

        body { font-family: 'Inter', sans-serif; }
        
        /* Ajuste fino para o input color */
        input[type="color"]::-webkit-color-swatch-wrapper { padding: 0; }
        input[type="color"]::-webkit-color-swatch { border: none; }
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
            console.error("Erro ao aplicar tema salvo:", erro);
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-meta-fundo transition-colors duration-200 min-h-screen">
    
    <div class="flex min-h-screen">

        <?php include_once '../includes/sidebarGerente.php'; ?>

        <main class="flex-1 ml-64 p-8">
            <header class="mb-8">
                <h2 class="text-2xl font-bold text-slate-900">Gerenciamento de Páginas</h2>
                <p class="text-slate-500">Configure a visibilidade e ordem das páginas do sistema</p>
            </header>

            <section class="bg-white border border-gray-200 rounded-2xl p-6 mb-6 shadow-sm">
                <div class="flex items-start gap-3">
                    <i class="fas fa-info-circle text-meta-destaque mt-1 text-lg"></i>
                    <div>
                        <h4 class="font-bold text-slate-800 mb-2">Como Funciona</h4>
                        <p class="text-sm text-slate-600 mb-1">• Use os botões de <strong>ativar/desativar</strong> para controlar quais páginas aparecem no menu</p>
                        <p class="text-sm text-slate-600">• Clique em <strong>"Editar"</strong> para ir diretamente à página e fazer alterações</p>
                    </div>
                </div>
            </section>

            <section class="bg-white border border-gray-200 rounded-2xl p-6 mb-6 shadow-sm">
                <h3 class="font-bold text-lg mb-6">Páginas Editáveis</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:bg-slate-50">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-meta-clara/10 flex items-center justify-center rounded-lg text-meta-clara"><i class="fas fa-th-large"></i></div>
                            <div><p class="font-bold text-slate-800">Dashboard</p><p class="text-xs text-slate-400">Visão geral dos dados financeiros e métricas principais</p></div>
                        </div>
                        <a href="../app/edicaoDashboard.php" class="inline-flex items-center justify-center px-4 py-2 bg-meta-destaque text-white rounded-lg text-sm font-semibold hover:opacity-90 transition"><i class="fas fa-pen mr-2"></i> Editar</a>
                    </div>
                    <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:bg-slate-50">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-meta-clara/10 flex items-center justify-center rounded-lg text-meta-clara"><i class="fas fa-file-invoice-dollar"></i></div>
                            <div><p class="font-bold text-slate-800">Transações</p><p class="text-xs text-slate-400">Registro e gerenciamento de receitas e despesas</p></div>
                        </div>
                        <a href="../app/edicaoTransacoes.php" class="inline-flex items-center justify-center px-4 py-2 bg-meta-destaque text-white rounded-lg text-sm font-semibold hover:opacity-90 transition"><i class="fas fa-pen mr-2"></i> Editar</a>
                    </div>
                </div>
            </section>

            <section class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h3 class="font-bold text-lg mb-6"><i class="fas fa-palette mr-2 text-meta-destaque"></i> Paleta de Cores</h3>
                
                <div class="grid grid-cols-4 gap-4 mb-8">
                    <?php 
                    $presets = [
                        ['n' => 'MetaCash Original', 'c' => ['#0F2440', '#204C73', '#24A6B6', '#35C59A', '#5DA4C0', '#FDFEFB']],
                        ['n' => 'Oceano Profundo', 'c' => ['#0F172A', '#1E40AF', '#0891B2', '#06B6D4', '#67E8F9', '#F8FAFC']],
                        ['n' => 'Floresta Moderna', 'c' => ['#064E3B', '#047857', '#10B981', '#34D399', '#A7F3D0', '#F0FDF4']],
                        ['n' => 'Sunset Corporativo', 'c' => ['#450A0A', '#991B1B', '#F59E0B', '#FBBF24', '#FDE68A', '#FFF7ED']]
                    ];
                    foreach($presets as $p) {
                        echo '<div onclick="aplicarPreset(\''.implode("', '", $p['c']).'\')" class="border rounded-xl p-4 text-center cursor-pointer hover:border-slate-400 transition bg-slate-50">';
                        echo '<div class="flex justify-center gap-1 mb-2"><div class="w-3 h-3 rounded-full" style="background:'.$p['c'][0].'"></div><div class="w-3 h-3 rounded-full" style="background:'.$p['c'][2].'"></div></div>';
                        echo '<p class="text-xs font-bold text-slate-700">'.$p['n'].'</p></div>';
                    }
                    ?>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-4">
                    <?php 
                    $inputs = [
                        'menu' => 'Cor do menu', 'btn1' => 'Cor de botão 1', 
                        'destaque' => 'Cor de Destaque', 'btn2' => 'Cor de botão 2', 
                        'clara' => 'Cor Clara', 'fundo' => 'Cor de Fundo'
                    ];
                    foreach($inputs as $id => $label) {
                        echo '<div><label class="text-xs font-bold text-slate-500 uppercase">'.$label.'</label>';
                        echo '<div class="flex items-center mt-1 border rounded-lg overflow-hidden bg-white">';
                        echo '<input type="color" id="picker'.ucfirst($id).'" class="w-12 h-12 border-r cursor-pointer bg-transparent">';
                        echo '<input type="text" id="txt'.ucfirst($id).'" class="w-full p-3 font-mono text-sm uppercase focus:outline-none"></div></div>';
                    }
                    ?>
                </div>
                
                <div class="bg-slate-50 border p-3 rounded-lg text-xs text-slate-500 italic">Dica: Clique no quadrado colorido para abrir o seletor visual ou digite o código hexadecimal.</div>
                
                <div class="text-right mt-6">
                    <button id="btnSalvarCores" class="bg-meta-destaque text-white px-8 py-3 rounded-lg font-bold hover:opacity-90 shadow-md transition-all">
                        <i class="fas fa-sync mr-2"></i> Salvar Alterações
                    </button>
                </div>
            </section>
        </main>
    </div>

    <div id="modalRelatorio" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-[60] p-4">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h3 class="text-xl font-bold text-slate-800">Baixar Relatório</h3>
                <button type="button" onclick="toggleModal('modalRelatorio')" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="../app/gerarPDF.php" method="GET" target="_blank" class="space-y-6">
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-3">Tipo de Transação</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="e" class="hidden peer">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition">Receita</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="s" class="hidden peer">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition">Despesa</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="todos" checked class="hidden peer">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition">Ambos</div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-3">Período</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="periodo" value="mensal" checked class="hidden peer" onclick="document.getElementById('campoMesRelatorio').style.display='block'">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition">Mensal</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="periodo" value="anual" class="hidden peer" onclick="document.getElementById('campoMesRelatorio').style.display='none'">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition">Anual</div>
                        </label>
                    </div>
                </div>

                <div id="campoMesRelatorio">
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1">Mês</label>
                    <select name="mes" class="w-full border rounded-xl px-4 py-2 outline-none focus:ring-2 focus:ring-meta-destaque transition bg-white text-slate-700">
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
                    <select name="ano" class="w-full border rounded-xl px-4 py-2 outline-none focus:ring-2 focus:ring-meta-destaque transition bg-white text-slate-700">
                        <option value="2026" selected>2026</option>
                        <option value="2025">2025</option>
                    </select>
                </div>

                <div class="flex gap-3 pt-4 border-t">
                    <button type="button" onclick="toggleModal('modalRelatorio')" class="flex-1 py-3 border border-slate-300 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition">Cancelar</button>
                    <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-meta-menu to-meta-destaque text-white font-bold rounded-xl shadow-lg hover:opacity-90 transition">
                        <i class="fas fa-download mr-2"></i> Baixar PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        const campos = {
            menu: { picker: document.getElementById('pickerMenu'), txt: document.getElementById('txtMenu') },
            btn1: { picker: document.getElementById('pickerBtn1'), txt: document.getElementById('txtBtn1') },
            destaque: { picker: document.getElementById('pickerDestaque'), txt: document.getElementById('txtDestaque') },
            btn2: { picker: document.getElementById('pickerBtn2'), txt: document.getElementById('txtBtn2') },
            clara: { picker: document.getElementById('pickerClara'), txt: document.getElementById('txtClara') },
            fundo: { picker: document.getElementById('pickerFundo'), txt: document.getElementById('txtFundo') }
        };

        const temaPadrao = { menu: '#0F2440', btn1: '#204C73', destaque: '#24A6B6', btn2: '#35C59A', clara: '#5DA4C0', fundo: '#FDFEFB' };

        let temaAtual = { ...temaPadrao };
        try {
            const salvo = localStorage.getItem('metaCashTheme');
            if (salvo) temaAtual = JSON.parse(salvo);
        } catch(e) { temaAtual = temaPadrao; }

        function inicializarInputs(cores) {
            Object.keys(campos).forEach(chave => {
                if(campos[chave] && cores[chave]) {
                    campos[chave].picker.value = cores[chave];
                    campos[chave].txt.value = cores[chave].toUpperCase();
                }
            });
        }
        
        inicializarInputs(temaAtual);

        function aplicarTema(cores) {
            const raiz = document.documentElement;
            Object.entries(cores).forEach(([key, val]) => {
                raiz.style.setProperty('--meta-' + key, val);
            });
        }

        function sincronizarTemaLive() {
            const coresAtuais = {};
            Object.keys(campos).forEach(chave => coresAtuais[chave] = campos[chave].picker.value);
            aplicarTema(coresAtuais);
        }

        Object.keys(campos).forEach(chave => {
            const par = campos[chave];
            par.picker.addEventListener('input', () => {
                par.txt.value = par.picker.value.toUpperCase();
                sincronizarTemaLive();
            });

            par.txt.maxLength = 7;
            par.txt.addEventListener('input', () => {
                let valor = par.txt.value;
                if(!valor.startsWith('#') && valor.length > 0) valor = '#' + valor;
                if(/^#[0-9A-F]{6}$/i.test(valor)) {
                    par.picker.value = valor;
                    sincronizarTemaLive();
                }
            });
        });

        function aplicarPreset(m, b1, d, b2, c, f) {
            const pacote = { menu: m, btn1: b1, destaque: d, btn2: b2, clara: c, fundo: f };
            inicializarInputs(pacote);
            aplicarTema(pacote);
        }

        window.onclick = function(event) {
        const mRel = document.getElementById('modalRelatorio');
        if (event.target === mRel) toggleModal('modalRelatorio');
        }

        document.getElementById('btnSalvarCores').addEventListener('click', () => {
            const coresParaGravar = {};
            Object.keys(campos).forEach(chave => coresParaGravar[chave] = campos[chave].picker.value);
            
            localStorage.setItem('metaCashTheme', JSON.stringify(coresParaGravar));
            
            const btn = document.getElementById('btnSalvarCores');
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Configurações Salvas!';
            btn.classList.add('bg-green-600');
            
            setTimeout(() => {
                btn.innerHTML = original;
                btn.classList.remove('bg-green-600');
            }, 2500);
        });
    </script>
</body>
</html>