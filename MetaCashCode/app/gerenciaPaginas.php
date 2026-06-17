<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config.php'; 
?>

<!DOCTYPE html>
<html lang="pt-br" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Gerenciamento de Páginas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/gerenciaPaginas.css">
    
</head>
<body class="flex min-h-screen bg-meta-fundo transition-colors duration-200">
    <?php require_once '../includes/sidebarGerente.php'; ?>
    
    <!-- Conteúdo Principal -->
    <main class="flex-1 ml-64 p-8">
        <header class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900">Gerenciamento de Páginas</h2>
            <p class="text-slate-500">Configure a visibilidade e ordem das páginas do sistema</p>
        </header>

        <!-- Seção Como Funciona -->
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

        <!-- Páginas Editáveis -->
        <section class="bg-white border border-gray-200 rounded-2xl p-6 mb-6 shadow-sm">
            <h3 class="font-bold text-lg mb-6">Páginas Editáveis</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:bg-slate-50">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-meta-clara/10 flex items-center justify-center rounded-lg text-meta-clara"><i class="fas fa-home"></i></div>
                        <div><p class="font-bold text-slate-800">Home</p><p class="text-xs text-slate-400">Página inicial da empresa com logo e apresentação</p></div>
                    </div>
                    <a href="../app/edicaoHome.php" class="inline-flex items-center justify-center px-4 py-2 bg-meta-destaque text-white rounded-lg text-sm font-semibold hover:opacity-90 transition"><i class="fas fa-pen mr-2"></i> Editar</a>
                </div>
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

        <!-- Paleta de Cores -->
        <section class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <h3 class="font-bold text-lg mb-6"><i class="fas fa-palette mr-2 text-meta-destaque"></i> Paleta de Cores</h3>
            
            <!-- Temas Prontos Clicáveis -->
            <div class="grid grid-cols-4 gap-4 mb-8">
                <div onclick="aplicarPreset('#0F2440', '#204C73', '#24A6B6', '#35C59A', '#5DA4C0', '#FDFEFB')" class="border rounded-xl p-4 text-center cursor-pointer hover:border-slate-400 transition bg-slate-50">
                    <div class="flex justify-center gap-1 mb-2">
                        <div class="w-3 h-3 rounded-full bg-[#0F2440]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#24A6B6]"></div>
                    </div>
                    <p class="text-xs font-bold text-slate-700">MetaCash Original</p>
                </div>
                <div onclick="aplicarPreset('#0F172A', '#1E40AF', '#0891B2', '#06B6D4', '#67E8F9', '#F8FAFC')" class="border rounded-xl p-4 text-center cursor-pointer hover:border-slate-400 transition bg-slate-50">
                    <div class="flex justify-center gap-1 mb-2">
                        <div class="w-3 h-3 rounded-full bg-[#0F172A]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#0891B2]"></div>
                    </div>
                    <p class="text-xs font-bold text-slate-700">Oceano Profundo</p>
                </div>
                <div onclick="aplicarPreset('#064E3B', '#047857', '#10B981', '#34D399', '#A7F3D0', '#F0FDF4')" class="border rounded-xl p-4 text-center cursor-pointer hover:border-slate-400 transition bg-slate-50">
                    <div class="flex justify-center gap-1 mb-2">
                        <div class="w-3 h-3 rounded-full bg-[#064E3B]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#10B981]"></div>
                    </div>
                    <p class="text-xs font-bold text-slate-700">Floresta Moderna</p>
                </div>
                <div onclick="aplicarPreset('#450A0A', '#991B1B', '#F59E0B', '#FBBF24', '#FDE68A', '#FFF7ED')" class="border rounded-xl p-4 text-center cursor-pointer hover:border-slate-400 transition bg-slate-50">
                    <div class="flex justify-center gap-1 mb-2">
                        <div class="w-3 h-3 rounded-full bg-[#450A0A]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#F59E0B]"></div>
                    </div>
                    <p class="text-xs font-bold text-slate-700">Sunset Corporativo</p>
                </div>
            </div>

            <!-- Formulário com Inputs -->
            <div class="grid grid-cols-2 gap-6 mb-4">
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Cor do menu</label>
                    <div class="flex items-center mt-1 border rounded-lg overflow-hidden bg-white">
                        <input type="color" id="pickerMenu" class="w-12 h-12 border-r cursor-pointer bg-transparent">
                        <input type="text" id="txtMenu" class="w-full p-3 font-mono text-sm uppercase focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Cor de botão 1</label>
                    <div class="flex items-center mt-1 border rounded-lg overflow-hidden bg-white">
                        <input type="color" id="pickerBtn1" class="w-12 h-12 border-r cursor-pointer bg-transparent">
                        <input type="text" id="txtBtn1" class="w-full p-3 font-mono text-sm uppercase focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Cor de Destaque</label>
                    <div class="flex items-center mt-1 border rounded-lg overflow-hidden bg-white">
                        <input type="color" id="pickerDestaque" class="w-12 h-12 border-r cursor-pointer bg-transparent">
                        <input type="text" id="txtDestaque" class="w-full p-3 font-mono text-sm uppercase focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Cor de botão 2</label>
                    <div class="flex items-center mt-1 border rounded-lg overflow-hidden bg-white">
                        <input type="color" id="pickerBtn2" class="w-12 h-12 border-r cursor-pointer bg-transparent">
                        <input type="text" id="txtBtn2" class="w-full p-3 font-mono text-sm uppercase focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Cor Clara</label>
                    <div class="flex items-center mt-1 border rounded-lg overflow-hidden bg-white">
                        <input type="color" id="pickerClara" class="w-12 h-12 border-r cursor-pointer bg-transparent">
                        <input type="text" id="txtClara" class="w-full p-3 font-mono text-sm uppercase focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Cor de Fundo</label>
                    <div class="flex items-center mt-1 border rounded-lg overflow-hidden bg-white">
                        <input type="color" id="pickerFundo" class="w-12 h-12 border-r cursor-pointer bg-transparent">
                        <input type="text" id="txtFundo" class="w-full p-3 font-mono text-sm uppercase focus:outline-none">
                    </div>
                </div>
            </div>
            
            <div class="bg-slate-50 border p-3 rounded-lg text-xs text-slate-500 italic">Dica: Clique no quadrado colorido para abrir o seletor visual ou digite o código hexadecimal diretamente (ex: #0F2440).</div>
            
            <div class="text-right mt-6">
                <button id="btnSalvarCores" class="bg-meta-destaque text-white px-8 py-3 rounded-lg font-bold hover:opacity-90 shadow-md transition-all">
                    <i class="fas fa-sync mr-2"></i> Salvar Alterações
                </button>
            </div>
        </section>
    </main>
    <script src="../assets/js/gerenciaPaginas.js"></script>
</body>
</html>