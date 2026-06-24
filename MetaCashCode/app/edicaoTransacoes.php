<?php
// 1. SEGURANÇA E SESSÃO NO TOPO ABSOLUTO
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config.php'; 

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id_empresa = $_SESSION['id_empresa'] ?? 0;

// Valores padrões (Fallback)
$config_titulo = "Transações";
$config_subtitulo = "Gerencie todas as receitas e despesas da empresa";
$config_botao = "Nova Transação";
$config_busca = "Buscar transações...";
$config_vazio = "Nenhuma transação encontrada";

// Busca as configurações já salvas no banco de dados
try {
    $sql_load = "SELECT chave_config, valor_config FROM configs_paginas WHERE id_empresa = :empresa";
    $stmt_load = $pdo->prepare($sql_load);
    $stmt_load->execute([':empresa' => $id_empresa]);
    $configs = $stmt_load->fetchAll(PDO::FETCH_KEY_PAIR);

    if (!empty($configs)) {
        if (isset($configs['titulo_pagina'])) $config_titulo = $configs['titulo_pagina'];
        if (isset($configs['subtitulo_pagina'])) $config_subtitulo = $configs['subtitulo_pagina'];
        if (isset($configs['texto_botao'])) $config_botao = $configs['texto_botao'];
        if (isset($configs['placeholder_busca'])) $config_busca = $configs['placeholder_busca'];
        if (isset($configs['mensagem_vazio'])) $config_vazio = $configs['mensagem_vazio'];
    }
} catch (PDOException $e) {
    error_log("Erro ao carregar configurações no editor: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Editor: Transações</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        (function() {
            const temaSalvo = localStorage.getItem('metaCashTheme');
            if (temaSalvo) {
                try {
                    const cores = JSON.parse(temaSalvo);
                    const root = document.documentElement;
                    for (const [key, value] of Object.entries(cores)) {
                        // CORREÇÃO: Adicionado as crases (backticks) ao redor da variável
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
            --meta-menu: #0F172A;
            --meta-btn1: #1E293B;
            --meta-destaque: #2D4BF0;
            --meta-btn2: #2DD4BF;
            --meta-clara: #38BDF8;
            --meta-fundo: #F8FAFC;
        }
        body { font-family: 'Inter', sans-serif; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex min-h-screen bg-meta-fundo text-slate-800 transition-colors duration-200 antialiased">

   <?php include_once '../includes/sidebarGerente.php'; ?>

    <main class="flex-1 ml-64 p-8 min-w-0 overflow-x-hidden">
        <header class="mb-6">
            <a href="../app/gerenciaPaginas.php" class="text-sm text-meta-destaque font-semibold mb-2 block hover:underline"><i class="fas fa-arrow-left mr-2"></i> Voltar ao Gerenciamento</a>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-meta-destaque flex items-center justify-center rounded-lg text-white shadow-sm"><i class="fas fa-exchange-alt"></i></div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Editor: Transações</h2>
                    <p class="text-slate-500">Personalize a aparência e funcionalidades desta página</p>
                </div>
            </div>
        </header>

        <form action="../app/salvarConfigTransacao.php" method="POST">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2 space-y-6">
                    <section class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                        <h4 class="font-bold text-slate-800 mb-4 flex items-center"><i class="fas fa-cog mr-2 text-slate-400"></i> Configurações Gerais</h4>
                        <label class="text-xs font-bold text-slate-500 uppercase">Fonte</label>
                        <select class="w-full border border-slate-200 rounded-lg p-3 mt-1 mb-4 bg-white focus:ring-2 focus:ring-meta-destaque outline-none"><option>Inter</option></select>
                        <p class="text-sm text-slate-400 mb-4">Prévia: Texto de exemplo com a fonte selecionada</p>
                        
                        <label class="text-xs font-bold text-slate-500 uppercase">Tamanho da Fonte</label>
                        <div class="grid grid-cols-2 gap-4 mt-1">
                            <button type="button" class="border border-slate-200 py-3 rounded-lg font-medium text-slate-600 hover:bg-slate-50 transition">Pequeno</button>
                            <button type="button" class="bg-meta-destaque text-white py-3 rounded-lg font-medium shadow-sm hover:opacity-95 transition">Médio</button>
                            <button type="button" class="border border-slate-200 py-3 rounded-lg font-medium text-slate-600 hover:bg-slate-50 transition">Grande</button>
                            <button type="button" class="border border-slate-200 py-3 rounded-lg font-medium text-slate-600 hover:bg-slate-50 transition">Extra Grande</button>
                        </div>
                        <p class="text-sm text-slate-400 mt-4">Prévia: Este é um exemplo de texto</p>
                    </section>

                    <section class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                        <h4 class="font-bold text-slate-800 mb-6 flex items-center"><i class="fas fa-pen mr-2 text-slate-400"></i> Textos Personalizados</h4>
                        <div class="space-y-6">
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase">Título da Página</label>
                                <input type="text" name="titulo_pagina" value="<?= htmlspecialchars($config_titulo) ?>" class="w-full border border-slate-200 rounded-lg p-3 mt-1 focus:ring-2 focus:ring-meta-destaque outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase">Subtítulo da Página</label>
                                <input type="text" name="subtitulo_pagina" value="<?= htmlspecialchars($config_subtitulo) ?>" class="w-full border border-slate-200 rounded-lg p-3 mt-1 focus:ring-2 focus:ring-meta-destaque outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase">Texto do Botão Adicionar</label>
                                <input type="text" name="texto_botao" value="<?= htmlspecialchars($config_botao) ?>" class="w-full border border-slate-200 rounded-lg p-3 mt-1 focus:ring-2 focus:ring-meta-destaque outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase">Texto de Busca (Placeholder)</label>
                                <input type="text" name="placeholder_busca" value="<?= htmlspecialchars($config_busca) ?>" class="w-full border border-slate-200 rounded-lg p-3 mt-1 focus:ring-2 focus:ring-meta-destaque outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase">Mensagem Quando Vazio</label>
                                <input type="text" name="mensagem_vazio" value="<?= htmlspecialchars($config_vazio) ?>" class="w-full border border-slate-200 rounded-lg p-3 mt-1 focus:ring-2 focus:ring-meta-destaque outline-none">
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-4 italic"><i class="fas fa-lightbulb text-meta-clara mr-1"></i> Personalize os textos para adequar ao seu contexto de negócio</p>
                    </section>
                </div>

                <div class="xl:col-span-1">
                    <section class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                        <h4 class="font-bold text-slate-800 mb-6">Colunas Visíveis (5/6)</h4>
                        <div class="space-y-3">
                            <div class="border border-slate-200 rounded-xl p-3 flex justify-between items-center hover:border-meta-destaque transition"><div class="flex items-center gap-3"><i class="fas fa-chart-line text-meta-destaque"></i><div><p class="font-bold text-sm">Card de Receitas</p><p class="text-xs text-slate-400">Tamanho: pequeno</p></div></div><div class="flex gap-2 text-slate-400"><i class="fas fa-pen text-xs cursor-pointer hover:text-slate-600"></i><i class="fas fa-eye text-xs cursor-pointer hover:text-meta-destaque"></i></div></div>
                            <div class="border border-slate-200 rounded-xl p-3 flex justify-between items-center hover:border-meta-destaque transition"><div class="flex items-center gap-3"><i class="fas fa-chart-area text-meta-destaque"></i><div><p class="font-bold text-sm">Card de Despesas</p><p class="text-xs text-slate-400">Tamanho: pequeno</p></div></div><div class="flex gap-2 text-slate-400"><i class="fas fa-pen text-xs cursor-pointer hover:text-slate-600"></i><i class="fas fa-eye text-xs cursor-pointer hover:text-meta-destaque"></i></div></div>
                            <div class="border border-slate-200 rounded-xl p-3 flex justify-between items-center hover:border-meta-destaque transition"><div class="flex items-center gap-3"><i class="fas fa-dollar-sign text-meta-destaque"></i><div><p class="font-bold text-sm">Card de Saldo</p><p class="text-xs text-slate-400">Tamanho: pequeno</p></div></div><div class="flex gap-2 text-slate-400"><i class="fas fa-pen text-xs cursor-pointer hover:text-slate-600"></i><i class="fas fa-eye text-xs cursor-pointer hover:text-meta-destaque"></i></div></div>
                            <div class="border border-slate-200 rounded-xl p-3 flex justify-between items-center bg-slate-50"><div class="flex items-center gap-3"><i class="fas fa-list text-slate-400"></i><div><p class="font-bold text-sm text-slate-500">Lista de Transações</p><p class="text-xs text-slate-400">Tamanho: médio</p></div></div><div class="flex gap-2 text-slate-400"><i class="fas fa-pen text-xs cursor-pointer hover:text-slate-600"></i><i class="fas fa-eye-slash text-xs cursor-pointer text-slate-400"></i></div></div>
                        </div>
                    </section>

                    <div class="mt-6 space-y-3">
                        <button type="submit" class="w-full bg-meta-menu text-white py-3 rounded-lg font-bold shadow-md hover:opacity-95 transition"><i class="fas fa-save mr-2"></i> Salvar Alterações</button>
                        <button type="button" onclick="window.location.reload();" class="w-full bg-white text-rose-500 border border-rose-200 py-3 rounded-lg font-bold hover:bg-rose-50 transition"><i class="fas fa-trash-alt mr-2"></i> Cancelar</button>
                    </div>
                </div>
            </div>
        </form>
    </main>
</body>
</html>