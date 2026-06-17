<?php
// 1. Segurança e Sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id_empresa = $_SESSION['id_empresa'];
?>
<!DOCTYPE html>
<html lang="pt-br" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - MetaCash</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/configuracao.css">

</head>
<body class="flex min-h-screen bg-meta-fundo transition-colors duration-200">
    <?php require_once '../includes/sidebarGerente.php'; ?>

    <main class="flex-1 p-10 ml-64 overflow-y-auto">
        <header class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Configurações</h1>
            <p class="text-slate-500 mt-1 text-sm">Personalize os dados operacionais da empresa no MetaCash</p>
        </header>

        <section class="bg-white border border-gray-200 p-8 rounded-3xl shadow-sm mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-meta-clara/10 flex items-center justify-center rounded-lg text-meta-clara"><i class="fa-solid fa-building"></i></div>
                <h2 class="font-bold text-slate-800 text-lg">Informações da Empresa</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nome da Empresa <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 bg-slate-50 outline-none focus:bg-white focus:border-meta-destaque focus:ring-4 focus:ring-meta-destaque/20 transition-all duration-200 mt-2" value="Minha Empresa">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">CNPJ <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 bg-slate-50 outline-none focus:bg-white focus:border-meta-destaque focus:ring-4 focus:ring-meta-destaque/20 transition-all duration-200 mt-2" placeholder="00.000.000/0000-00">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Início da Contabilidade <span class="text-red-500">*</span></label>
                    <input type="date" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 bg-slate-50 outline-none focus:bg-white focus:border-meta-destaque focus:ring-4 focus:ring-meta-destaque/20 transition-all duration-200 mt-2">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Ano Fiscal <span class="text-red-500">*</span></label>
                    <input type="number" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 bg-slate-50 outline-none focus:bg-white focus:border-meta-destaque focus:ring-4 focus:ring-meta-destaque/20 transition-all duration-200 mt-2" value="2026">
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button class="bg-meta-btn1 text-white px-5 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 hover:opacity-90 active:scale-95 transition-all shadow-md">
                    <i class="fa-solid fa-rotate text-xs"></i> Atualizar Empresa
                </button>
            </div>
        </section>

        <section class="bg-white border border-gray-200 p-8 rounded-3xl shadow-sm mb-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-meta-clara/10 flex items-center justify-center rounded-lg text-meta-clara"><i class="fa-solid fa-upload"></i></div>
                <h2 class="font-bold text-slate-800 text-lg">Sua Logo</h2>
            </div>
            <div class="border-dashed border-2 border-slate-200 rounded-2xl p-8 flex flex-col items-start bg-slate-50/50 hover:bg-slate-50 transition-colors">
                <button class="bg-meta-btn1 text-white px-5 py-2.5 rounded-xl font-bold text-sm mb-3 hover:opacity-90 transition-all shadow-sm">Escolher Arquivo</button>
                <p class="text-[11px] text-slate-400 uppercase font-semibold tracking-wider">Formatos aceitos: PNG, JPG, SVG. Tamanho máximo: 2MB</p>
            </div>
        </section>

        <section class="bg-white border border-gray-200 p-8 rounded-3xl shadow-sm mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-meta-clara/10 flex items-center justify-center rounded-lg text-meta-clara"><i class="fa-solid fa-tags"></i></div>
                <h2 class="font-bold text-slate-800 text-lg">Categorias Personalizadas</h2>
            </div>
            
            <div class="bg-slate-50 p-5 rounded-2xl flex flex-wrap md:flex-nowrap gap-3 mb-6 border border-slate-100 shadow-inner">
                <input type="text" class="flex-1 border border-slate-300 rounded-xl px-4 py-3 text-sm outline-none focus:bg-white focus:border-meta-destaque focus:ring-4 focus:ring-meta-destaque/20 transition-all duration-200" placeholder="Nome da categoria...">
                <select class="border border-slate-300 rounded-xl px-4 py-3 bg-white text-sm font-semibold text-slate-600 outline-none focus:border-meta-destaque focus:ring-4 focus:ring-meta-destaque/20 transition-all duration-200 min-w-[120px]">
                    <option>Receita</option>
                    <option>Despesa</option>
                </select>
                <button class="bg-meta-destaque text-white px-6 py-3 rounded-xl font-bold text-sm transition-all shadow-md active:scale-95 hover:opacity-90">+ Adicionar</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-xs font-bold text-meta-destaque mb-4 uppercase tracking-wider">Receitas</h3>
                    <?php 
                    $receitas = ["Venda de Produtos", "Prestação de Serviços", "Rendimentos", "Outras Receitas"];
                    foreach($receitas as $r): ?>
                        <div class="flex justify-between items-center border border-slate-200 rounded-xl px-4 py-3 mb-2.5 text-sm text-slate-600 bg-white hover:border-slate-300 transition-colors shadow-sm">
                            <span class="font-medium">• <?php echo $r; ?></span>
                            <i class="fa-solid fa-xmark text-slate-400 cursor-pointer hover:text-red-500 transition-colors"></i>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-slate-400 mb-4 uppercase tracking-wider">Despesas</h3>
                    <?php 
                    $despesas = ["Folha de Pagamento", "Despesas Operacionais", "Fornecedores", "Marketing", "Impostos e Taxas", "TI e Equipamentos"];
                    foreach($despesas as $d): ?>
                        <div class="flex justify-between items-center border border-slate-200 rounded-xl px-4 py-3 mb-2.5 text-sm text-slate-600 bg-white hover:border-slate-300 transition-colors shadow-sm">
                            <span class="font-medium">• <?php echo $d; ?></span>
                            <i class="fa-solid fa-xmark text-slate-400 cursor-pointer hover:text-red-500 transition-colors"></i>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <div class="flex justify-end pb-10">
            <button class="bg-meta-destaque text-white px-8 py-4 rounded-2xl font-bold flex items-center gap-2 hover:opacity-90 shadow-xl transition-all hover:-translate-y-0.5 active:translate-y-0">
                <i class="fa-solid fa-floppy-disk"></i> Salvar Todas as Alterações
            </button>
        </div>
    </main>

    <script src="../assets/js/configuracao.js"></script>
</body>
</html>