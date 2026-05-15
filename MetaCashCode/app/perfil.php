<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Meu Perfil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc] flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-[#0f172a] text-white p-4 flex flex-col sticky top-0 h-screen shrink-0">
        <!-- LOGO ATUALIZADA -->
        <div class="flex items-center gap-3 mb-10 px-2">
            <div class="w-10 h-10 rounded-lg overflow-hidden flex items-center justify-center shrink-0">
                <img src="/MetaCashCode/Usuario/Dashboard/img/logoCyano.png" alt="MetaCash Logo" class="w-full h-full object-contain" onerror="this.src='https://via.placeholder.com/40?text=MC'">
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-xl leading-tight">MetaCash</span>
                <span class="text-[10px] text-gray-400">Gestão Empresarial</span>
            </div>
        </div>

        <nav class="flex-1 space-y-3">
            <a href="../Dashboard/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 transition">
                <i class="fas fa-th-large"></i><span class="font-medium">Dashboard</span>
            </a>
            <a href="transacoes.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 transition">
                <i class="fas fa-exchange-alt"></i><span class="font-medium">Transações</span>
            </a>
        </nav>

        <!-- RODAPÉ DA SIDEBAR -->
        <div class="mt-auto pt-6 border-t border-slate-800 space-y-4">
            <a href="perfil.php" class="bg-[#1e3a5f]/60 p-3 rounded-2xl flex items-center gap-3 border border-[#2dd4bf]/50 transition hover:bg-[#1e3a5f]/80 block">
                <div class="w-10 h-10 bg-[#2dd4bf] rounded-full flex items-center justify-center text-[#0f172a] font-bold text-lg shrink-0">
                    U
                </div>
                <div class="flex flex-col overflow-hidden">
                    <span class="text-sm font-bold truncate">Usuário</span>
                    <span class="text-[10px] text-gray-400 truncate">usuario@exemplo.com</span>
                </div>
            </a>
            <a href="../logout.php" class="flex items-center gap-3 px-4 py-2 text-gray-400 hover:text-white transition group">
                <i class="fas fa-sign-out-alt rotate-180 group-hover:text-red-400"></i>
                <span class="font-medium">Sair</span>
            </a>
        </div>
    </aside>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="flex-1 p-10 w-full">
        <!-- Cabeçalho da Página -->
        <header class="mb-8">
            <a href="javascript:history.back()" class="text-sm text-slate-500 hover:text-teal-600 flex items-center gap-2 mb-4 transition">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <h1 class="text-3xl font-extrabold text-[#0f172a]">Meu Perfil</h1>
            <p class="text-slate-500 mt-1">Gerencie suas informações pessoais e segurança</p>
        </header>

        <div class="max-w-5xl space-y-8">
            
            <!-- CARD: INFORMAÇÕES PESSOAIS -->
            <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <h2 class="font-bold text-slate-800">Informações Pessoais</h2>
                </div>
                
                <div class="p-8">
                    <!-- Header do Perfil (Avatar) -->
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-20 h-20 bg-[#2dd4bf] rounded-full flex items-center justify-center text-[#0f172a] text-3xl font-bold">
                            U
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800">Usuário</h3>
                            <span class="inline-block bg-blue-100 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase mb-1">
                                <i class="fas fa-shield-alt mr-1"></i> Membro
                            </span>
                            <p class="text-sm text-slate-400">usuario@exemplo.com</p>
                        </div>
                    </div>

                    <form class="space-y-6">
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Nome -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nome Completo</label>
                                <div class="relative">
                                    <i class="far fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" value="Usuário" disabled class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-500 cursor-not-allowed">
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1 italic">Este campo não pode ser alterado</p>
                            </div>

                            <!-- Matrícula -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Matrícula</label>
                                <div class="relative">
                                    <i class="far fa-id-badge absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" value="2024001" disabled class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-500 cursor-not-allowed">
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1 italic">Este campo não pode ser alterado</p>
                            </div>

                            <!-- CPF -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">CPF</label>
                                <div class="relative">
                                    <i class="far fa-address-card absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" value="123.456.789-00" disabled class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-500 cursor-not-allowed">
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1 italic">Este campo não pode ser alterado</p>
                            </div>

                            <!-- Papel -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Papel</label>
                                <div class="w-full px-4 py-3 rounded-xl bg-sky-100 border border-sky-200 text-sky-700 flex items-center gap-3">
                                    <i class="fas fa-user-tag text-sky-500"></i>
                                    <span class="font-bold text-sm">Membro</span>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1">Definido pelo gerente da empresa</p>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Email</label>
                                <div class="relative">
                                    <i class="far fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="email" value="usuario@exemplo.com" class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 outline-none transition">
                                </div>
                                <p class="text-[10px] text-teal-600 mt-1 italic"><i class="fas fa-check"></i> Este campo pode ser alterado</p>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="bg-[#0f172a] text-white px-8 py-3 rounded-xl font-bold hover:bg-slate-800 transition shadow-lg flex items-center gap-2">
                                <i class="fas fa-save"></i> Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <!-- CARD: SEGURANÇA -->
            <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-lock text-sm"></i>
                    </div>
                    <h2 class="font-bold text-slate-800">Segurança</h2>
                </div>

                <div class="p-8">
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Senha Atual <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <i class="fas fa-unlock-alt absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                    <input type="password" placeholder="Digite sua senha atual" class="w-full pl-11 pr-12 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 outline-none transition">
                                    <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nova Senha <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <i class="fas fa-unlock-alt absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                    <input type="password" placeholder="Digite sua nova senha" class="w-full pl-11 pr-12 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 outline-none transition">
                                    <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Confirmar Nova Senha <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <i class="fas fa-unlock-alt absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                    <input type="password" placeholder="Confirme sua nova senha" class="w-full pl-11 pr-12 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 outline-none transition">
                                    <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row justify-between items-end gap-6 pt-4">
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 w-full md:w-fit">
                                <h4 class="text-xs font-bold text-slate-600 mb-3">Requisitos de Senha:</h4>
                                <div class="grid grid-cols-2 gap-x-8 gap-y-2">
                                    <div class="text-[10px] text-slate-500 flex items-center gap-2"><i class="fas fa-times text-red-400"></i> Mínimo de 8 caracteres</div>
                                    <div class="text-[10px] text-slate-500 flex items-center gap-2"><i class="fas fa-times text-red-400"></i> Letra maiúscula (A-Z)</div>
                                    <div class="text-[10px] text-slate-500 flex items-center gap-2"><i class="fas fa-times text-red-400"></i> Letra minúscula (a-z)</div>
                                    <div class="text-[10px] text-slate-500 flex items-center gap-2"><i class="fas fa-times text-red-400"></i> Número (0-9)</div>
                                    <div class="text-[10px] text-slate-500 flex items-center gap-2"><i class="fas fa-times text-red-400"></i> Caractere especial (!@#$...)</div>
                                </div>
                            </div>
                            <!-- BOTÃO ATUALIZADO -->
                            <button type="submit" class="bg-slate-100 text-slate-600 px-6 py-2.5 rounded-lg text-sm hover:bg-red-50 hover:text-red-600 transition duration-300 flex items-center gap-2 border border-slate-200 hover:border-red-200 shadow-sm">
                                <i class="fas fa-key text-xs"></i> Alterar Senha
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>

</body>
</html>