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

// =========================================================================
// PROCESSAMENTO DO SALVAMENTO (Atomic e Seguro)
// =========================================================================
$mensagem_sucesso = '';
$mensagem_erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chaves_permitidas = [
        'titulo_pagina', 'subtitulo_pagina', 'texto_botao', 'placeholder_busca', 'mensagem_vazio',
        'fonte_pagina', 'tamanho_fonte', 'vis_receitas', 'vis_despesas', 'vis_saldo', 'vis_lista'
    ];
    
    // Lista de campos que exigem validação de tamanho máximo de 100 caracteres
    $campos_com_limite = ['titulo_pagina', 'subtitulo_pagina', 'texto_botao', 'placeholder_busca', 'mensagem_vazio'];
    
    try {
        $validacao_ok = true;
        
        // Validação no Back-end por segurança
        foreach ($campos_com_limite as $campo_validar) {
            if (isset($_POST[$campo_validar]) && mb_strlen(trim($_POST[$campo_validar])) > 100) {
                $validacao_ok = false;
                $mensagem_erro = "Erro: Os campos de texto personalizado não podem passar de 100 caracteres.";
                break;
            }
        }

        if ($validacao_ok) {
            $sql = "INSERT INTO configs_paginas (id_empresa, chave_config, valor_config) 
                    VALUES (:empresa, :chave, :valor)
                    ON CONFLICT (chave_config, id_empresa) 
                    DO UPDATE SET valor_config = EXCLUDED.valor_config";
        
            $stmt = $pdo->prepare($sql);

            foreach ($chaves_permitidas as $chave) {
                if (isset($_POST[$chave])) {
                    $valor = trim($_POST[$chave]);
                    $stmt->execute([
                        ':empresa' => $id_empresa, 
                        ':chave'   => $chave, 
                        ':valor'   => $valor
                    ]);
                }
            }
            $mensagem_sucesso = "Configurações salvas com sucesso!";
        }
    } catch (Exception $e) {
        $mensagem_erro = "Erro ao salvar: " . $e->getMessage();
    }
}

// =========================================================================
// Valores padrões (Fallback)
// =========================================================================
$config_titulo = "Transações";
$config_subtitulo = "Gerencie todas as receitas e despesas da empresa";
$config_botao = "Nova Transação";
$config_busca = "Buscar transações...";
$config_vazio = "Nenhuma transação encontrada";
$config_fonte = "Inter";
$config_tamanho = "medio";
$config_vis_receitas = "1";
$config_vis_despesas = "1";
$config_vis_saldo = "1";
$config_vis_lista = "1"; 

// Busca as configurações já salvas
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
        if (isset($configs['fonte_pagina'])) $config_fonte = $configs['fonte_pagina'];
        if (isset($configs['tamanho_fonte'])) $config_tamanho = $configs['tamanho_fonte'];
        if (isset($configs['vis_receitas'])) $config_vis_receitas = $configs['vis_receitas'];
        if (isset($configs['vis_despesas'])) $config_vis_despesas = $configs['vis_despesas'];
        if (isset($configs['vis_saldo'])) $config_vis_saldo = $configs['vis_saldo'];
        if (isset($configs['vis_lista'])) $config_vis_lista = $configs['vis_lista'];
    }
} catch (PDOException $e) {
    $mensagem_erro = "Erro ao carregar configurações: " . $e->getMessage();
}

$btn_ativo = "bg-meta-destaque text-white shadow-sm hover:opacity-95";
$btn_inativo = "border border-slate-200 text-slate-600 hover:bg-slate-50";

function getVisibilityClasses($isActive) {
    return $isActive === "1" 
        ? ['bg-white border-slate-200 hover:border-meta-destaque', 'fas fa-eye text-meta-destaque', 'text-slate-800'] 
        : ['bg-slate-50 border-slate-200', 'fas fa-eye-slash text-slate-400', 'text-slate-400'];
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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Roboto:wght@300;400;500;700&family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@300;400;600;700&family=Lato:wght@300;400;700&family=Open+Sans:wght@300;400;600;700&display=swap');
        
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

        <div id="js-alert-container"></div>

        <?php if ($mensagem_sucesso): ?>
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center">
                <i class="fas fa-check-circle mr-3 text-emerald-500"></i> <span class="font-medium"><?= $mensagem_sucesso ?></span>
            </div>
        <?php endif; ?>
        <?php if ($mensagem_erro): ?>
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl flex items-center">
                <i class="fas fa-exclamation-circle mr-3 text-rose-500"></i> <span class="font-medium"><?= $mensagem_erro ?></span>
            </div>
        <?php endif; ?>

        <form action="" method="POST" id="formConfig" onsubmit="return validarFormulario(event)">
            <input type="hidden" name="tamanho_fonte" id="input_tamanho_fonte" value="<?= htmlspecialchars($config_tamanho) ?>">
            <input type="hidden" name="vis_receitas" id="input_vis_receitas" value="<?= htmlspecialchars($config_vis_receitas) ?>">
            <input type="hidden" name="vis_despesas" id="input_vis_despesas" value="<?= htmlspecialchars($config_vis_despesas) ?>">
            <input type="hidden" name="vis_saldo" id="input_vis_saldo" value="<?= htmlspecialchars($config_vis_saldo) ?>">
            <input type="hidden" name="vis_lista" id="input_vis_lista" value="<?= htmlspecialchars($config_vis_lista) ?>">

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2 space-y-6">
                    <section class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                        <h4 class="font-bold text-slate-800 mb-4 flex items-center"><i class="fas fa-cog mr-2 text-slate-400"></i> Configurações Gerais</h4>
                        
                        <label class="text-xs font-bold text-slate-500 uppercase">Fonte</label>
                        <select name="fonte_pagina" class="w-full border border-slate-200 rounded-lg p-3 mt-1 mb-4 bg-white focus:ring-2 focus:ring-meta-destaque outline-none">
                            <option value="Inter" <?= $config_fonte == 'Inter' ? 'selected' : '' ?>>Inter</option>
                            <option value="Roboto" <?= $config_fonte == 'Roboto' ? 'selected' : '' ?>>Roboto</option>
                        </select>
                        
                        <label class="text-xs font-bold text-slate-500 uppercase">Tamanho da Fonte</label>
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-1">
                            <button type="button" onclick="setTamanhoFonte('pequeno', this)" class="btn-fonte py-3 rounded-lg font-medium transition <?= $config_tamanho === 'pequeno' ? $btn_ativo : $btn_inativo ?>">Pequeno</button>
                            <button type="button" onclick="setTamanhoFonte('medio', this)" class="btn-fonte py-3 rounded-lg font-medium transition <?= $config_tamanho === 'medio' ? $btn_ativo : $btn_inativo ?>">Médio</button>
                            <button type="button" onclick="setTamanhoFonte('grande', this)" class="btn-fonte py-3 rounded-lg font-medium transition <?= $config_tamanho === 'grande' ? $btn_ativo : $btn_inativo ?>">Grande</button>
                            <button type="button" onclick="setTamanhoFonte('extra', this)" class="btn-fonte py-3 rounded-lg font-medium transition <?= $config_tamanho === 'extra' ? $btn_ativo : $btn_inativo ?>">Extra Grande</button>
                        </div>
                    </section>

                    <section class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                        <h4 class="font-bold text-slate-800 mb-6 flex items-center"><i class="fas fa-pen mr-2 text-slate-400"></i> Textos Personalizados (Máx. 100 caracteres)</h4>
                        <div class="space-y-6">
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase">Título da Página</label>
                                <input type="text" name="titulo_pagina" maxlength="100" value="<?= htmlspecialchars($config_titulo) ?>" class="input-valida w-full border border-slate-200 rounded-lg p-3 mt-1 focus:ring-2 focus:ring-meta-destaque outline-none">
                                <span class="text-xs text-slate-400 contador"></span>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase">Subtítulo da Página</label>
                                <input type="text" name="subtitulo_pagina" maxlength="100" value="<?= htmlspecialchars($config_subtitulo) ?>" class="input-valida w-full border border-slate-200 rounded-lg p-3 mt-1 focus:ring-2 focus:ring-meta-destaque outline-none">
                                <span class="text-xs text-slate-400 contador"></span>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase">Texto do Botão Adicionar</label>
                                <input type="text" name="texto_botao" maxlength="100" value="<?= htmlspecialchars($config_botao) ?>" class="input-valida w-full border border-slate-200 rounded-lg p-3 mt-1 focus:ring-2 focus:ring-meta-destaque outline-none">
                                <span class="text-xs text-slate-400 contador"></span>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase">Texto de Busca (Placeholder)</label>
                                <input type="text" name="placeholder_busca" maxlength="100" value="<?= htmlspecialchars($config_busca) ?>" class="input-valida w-full border border-slate-200 rounded-lg p-3 mt-1 focus:ring-2 focus:ring-meta-destaque outline-none">
                                <span class="text-xs text-slate-400 contador"></span>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase">Mensagem Quando Vazio</label>
                                <input type="text" name="mensagem_vazio" maxlength="100" value="<?= htmlspecialchars($config_vazio) ?>" class="input-valida w-full border border-slate-200 rounded-lg p-3 mt-1 focus:ring-2 focus:ring-meta-destaque outline-none">
                                <span class="text-xs text-slate-400 contador"></span>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="xl:col-span-1">
                    <section class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                        <h4 class="font-bold text-slate-800 mb-6">Colunas Visíveis</h4>
                        <div class="space-y-3">
                            <?php $cls = getVisibilityClasses($config_vis_receitas); ?>
                            <div id="card_vis_receitas" class="border rounded-xl p-3 flex justify-between items-center transition <?= $cls[0] ?>">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-chart-line text-meta-destaque"></i>
                                    <div><p id="txt_vis_receitas" class="font-bold text-sm <?= $cls[2] ?>">Card de Receitas</p><p class="text-xs text-slate-400">Tamanho: pequeno</p></div>
                                </div>
                                <div class="flex gap-2">
                                    <i onclick="toggleVisibility('vis_receitas')" id="icon_vis_receitas" class="<?= $cls[1] ?> cursor-pointer transition"></i>
                                </div>
                            </div>

                            <?php $cls = getVisibilityClasses($config_vis_despesas); ?>
                            <div id="card_vis_despesas" class="border rounded-xl p-3 flex justify-between items-center transition <?= $cls[0] ?>">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-chart-area text-rose-500"></i>
                                    <div><p id="txt_vis_despesas" class="font-bold text-sm <?= $cls[2] ?>">Card de Despesas</p><p class="text-xs text-slate-400">Tamanho: pequeno</p></div>
                                </div>
                                <div class="flex gap-2">
                                    <i onclick="toggleVisibility('vis_despesas')" id="icon_vis_despesas" class="<?= $cls[1] ?> cursor-pointer transition"></i>
                                </div>
                            </div>

                            <?php $cls = getVisibilityClasses($config_vis_saldo); ?>
                            <div id="card_vis_saldo" class="border rounded-xl p-3 flex justify-between items-center transition <?= $cls[0] ?>">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-dollar-sign text-emerald-500"></i>
                                    <div><p id="txt_vis_saldo" class="font-bold text-sm <?= $cls[2] ?>">Card de Saldo</p><p class="text-xs text-slate-400">Tamanho: pequeno</p></div>
                                </div>
                                <div class="flex gap-2">
                                    <i onclick="toggleVisibility('vis_saldo')" id="icon_vis_saldo" class="<?= $cls[1] ?> cursor-pointer transition"></i>
                                </div>
                            </div>

                            <?php $cls = getVisibilityClasses($config_vis_lista); ?>
                            <div id="card_vis_lista" class="border rounded-xl p-3 flex justify-between items-center transition <?= $cls[0] ?>">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-list text-meta-clara"></i>
                                    <div><p id="txt_vis_lista" class="font-bold text-sm <?= $cls[2] ?>">Lista de Transações</p><p class="text-xs text-slate-400">Tamanho: médio</p></div>
                                </div>
                                <div class="flex gap-2">
                                    <i onclick="toggleVisibility('vis_lista')" id="icon_vis_lista" class="<?= $cls[1] ?> cursor-pointer transition"></i>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="mt-6 space-y-3">
                        <button type="submit" class="w-full bg-meta-menu text-white py-3 rounded-lg font-bold shadow-md hover:opacity-95 transition"><i class="fas fa-save mr-2"></i> Salvar Alterações</button>
                        <button type="button" onclick="window.location.reload()" class="w-full block text-center bg-white text-rose-500 border border-rose-200 py-3 rounded-lg font-bold hover:bg-rose-50 transition"><i class="fas fa-undo-alt mr-2"></i> Descartar Alterações</button>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <script>
        // Contador regressivo de caracteres em tempo real
        document.querySelectorAll('.input-valida').forEach(input => {
            const contador = input.nextElementSibling;
            
            function atualizarContador() {
                const total = input.value.trim().length;
                const restantes = 100 - total;
                contador.textContent = `${restantes} caracteres restantes`;
                
                if(restantes < 0) {
                    contador.className = "text-xs text-rose-500 font-medium block mt-1";
                } else if(restantes <= 15) {
                    contador.className = "text-xs text-amber-500 font-medium block mt-1";
                } else {
                    contador.className = "text-xs text-slate-400 font-medium block mt-1";
                }
            }
            
            input.addEventListener('input', atualizarContador);
            atualizarContador();
        });

        function validarFormulario(event) {
            const inputs = document.querySelectorAll('.input-valida');
            let formValido = true;
            
            inputs.forEach(input => {
                if (input.value.trim().length > 100) {
                    formValido = false;
                    input.classList.add('border-rose-400', 'ring-2', 'ring-rose-200');
                } else {
                    input.classList.remove('border-rose-400', 'ring-2', 'ring-rose-200');
                }
            });

            if (!formValido) {
                event.preventDefault();
                const container = document.getElementById('js-alert-container');
                container.innerHTML = `
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl flex items-center shadow-sm">
                        <i class="fas fa-exclamation-circle mr-3 text-rose-500 text-lg"></i> 
                        <span class="font-medium">Atenção: Um ou mais campos ultrapassaram o limite máximo de 100 caracteres!</span>
                    </div>
                `;
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return false;
            }
            return true;
        }

        function setTamanhoFonte(tamanho, btnSelecionado) {
            document.getElementById('input_tamanho_fonte').value = tamanho;
            const botoes = document.querySelectorAll('.btn-fonte');
            botoes.forEach(btn => {
                btn.className = "btn-fonte py-3 rounded-lg font-medium transition border border-slate-200 text-slate-600 hover:bg-slate-50";
            });
            btnSelecionado.className = "btn-fonte py-3 rounded-lg font-medium transition bg-meta-destaque text-white shadow-sm hover:opacity-95";
        }

        function toggleVisibility(chave) {
            const input = document.getElementById('input_' + chave);
            const card = document.getElementById('card_' + chave);
            const icon = document.getElementById('icon_' + chave);
            const txt = document.getElementById('txt_' + chave);

            if (input.value === "1") {
                input.value = "0";
                icon.className = "fas fa-eye-slash text-slate-400 cursor-pointer transition";
                card.classList.remove('bg-white', 'hover:border-meta-destaque');
                card.classList.add('bg-slate-50');
                txt.classList.remove('text-slate-800');
                txt.classList.add('text-slate-400');
            } else {
                input.value = "1";
                icon.className = "fas fa-eye text-meta-destaque cursor-pointer transition";
                card.classList.remove('bg-slate-50');
                card.classList.add('bg-white', 'hover:border-meta-destaque');
                txt.classList.remove('text-slate-400');
                txt.classList.add('text-slate-800');
            }
        }
    </script>
</body>
</html>