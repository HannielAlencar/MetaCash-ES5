<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. IMPORTA O SEU ARQUIVO DE CONFIGURAÇÃO (config.php)
require_once '../config.php'; 

// IDs vindos da sessão ativa
$id_usuario = $_SESSION['id_usuario'] ?? $_SESSION['usuario_id'] ?? 23; 
$id_empresa = $_SESSION['id_empresa'] ?? 27; 

$alerta_js = ""; // Variável para exibir alertas

// VERIFICAÇÃO PARA O POPUP DE SUCESSO (Adicionado)
if (isset($_GET['sucesso']) && $_GET['sucesso'] === 'perfil_atualizado') {
    $alerta_js = "<script>alert('Informações pessoais atualizadas com sucesso!');</script>";
}

// ==========================================
// LÓGICA 1: ATUALIZAR INFORMAÇÕES PESSOAIS (PERFIL)
// ==========================================
if (isset($_POST['btn_atualizar_perfil'])) {
    $nome      = trim($_POST['nome']);
    $matricula = trim($_POST['matricula']);
    $cpf       = trim($_POST['cpf']);
    $email     = trim($_POST['email']);

    try {
        // CORRIGIDO: Utilizando os nomes exatos do seu banco: nome_completo e cpf
        $updatePerfil = $pdo->prepare("UPDATE usuarios SET nome_completo = ?, matricula = ?, cpf = ?, email = ? WHERE id_usuario = ?");
        $updatePerfil->execute([$nome, $matricula, $cpf, $email, $id_usuario]);

        // Atualiza as variáveis de sessão para refletirem na interface imediatamente
        $_SESSION['nome'] = $nome;
        $_SESSION['matricula'] = $matricula;
        $_SESSION['cpf_usuario'] = $cpf;
        $_SESSION['email'] = $email;

        // Grava no histórico (função do seu config.php)
        if (function_exists('registrarHistorico')) {
            registrarHistorico($pdo, $id_usuario, 'ALTERAR', 'Perfil', "Atualizou suas informações pessoais via painel.");
        }

        // Redireciona para limpar o POST e evitar reenvio ao fazer F5
        header("Location: " . $_SERVER['PHP_SELF'] . "?sucesso=perfil_atualizado");
        exit;
    } catch (PDOException $e) {
        error_log("Erro ao atualizar perfil: " . $e->getMessage());
        echo "<h3>Erro SQL ao atualizar perfil:</h3>" . $e->getMessage();
        exit;
    }
}

// ==========================================
// LÓGICA 2: ATUALIZAR DADOS DA EMPRESA
// ==========================================
if (isset($_POST['btn_atualizar_empresa'])) {
    $nome_empresa_input = trim($_POST['nome_empresa']);
    $cnpj_input         = trim($_POST['cnpj']);

    try {
        $updateDados = $pdo->prepare("UPDATE empresas SET nome_empresa = ?, cnpj = ? WHERE id_empresa = ?");
        $updateDados->execute([$nome_empresa_input, $cnpj_input, $id_empresa]);

        if (function_exists('registrarHistorico')) {
            registrarHistorico($pdo, $id_usuario, 'ALTERAR', 'Empresa', "Atualizou os dados da empresa para: $nome_empresa_input");
        }

        header("Location: " . $_SERVER['PHP_SELF'] . "?sucesso=empresa_atualizada");
        exit;
    } catch (PDOException $e) {
        error_log("Erro ao atualizar empresa: " . $e->getMessage());
        echo "<h3>Erro SQL ao atualizar empresa:</h3>" . $e->getMessage();
        exit;
    }
}

// ==========================================
// LÓGICA 3: BOTÃO "VOLTAR AO PADRÃO" (RESETAR LOGO)
// ==========================================
if (isset($_POST['btn_resetar_logo'])) {
    try {
        $updateLogo = $pdo->prepare("UPDATE empresas SET logo_path = NULL WHERE id_empresa = ?");
        $updateLogo->execute([$id_empresa]);
        
        if (function_exists('registrarHistorico')) {
            registrarHistorico($pdo, $id_usuario, 'ALTERAR', 'Empresa', 'Resetou a logo da empresa para o padrão.');
        }

        header("Location: " . $_SERVER['PHP_SELF'] . "?sucesso=logo_resetada");
        exit;
    } catch (PDOException $e) {
        error_log("Erro ao resetar logo: " . $e->getMessage());
        echo "<h3>Erro SQL ao resetar logo:</h3>" . $e->getMessage();
        exit;
    }
}

// ==========================================
// LÓGICA 4: ALTERAR SENHA
// ==========================================
if (isset($_POST['btn_alterar_senha'])) {
    $senha_atual     = $_POST['senha_atual'];
    $nova_senha      = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    if ($nova_senha !== $confirmar_senha) {
        $alerta_js = "<script>alert('As novas senhas não coincidem!');</script>";
    } else {
        // Validação dos requisitos no backend
        if (strlen($nova_senha) < 8 || !preg_match('/[A-Z]/', $nova_senha) || !preg_match('/[a-z]/', $nova_senha) || !preg_match('/[0-9]/', $nova_senha) || !preg_match('/[^A-Za-z0-9]/', $nova_senha)) {
            $alerta_js = "<script>alert('A nova senha não atende a todos os requisitos de segurança!');</script>";
        } else {
            try {
                $queryPw = $pdo->prepare("SELECT senha FROM usuarios WHERE id_usuario = ?");
                $queryPw->execute([$id_usuario]);
                $dadosPw = $queryPw->fetch();

                // Verifica a senha atual (Usando password_verify. Se seu BD usa MD5, mude para: if (md5($senha_atual) === $dadosPw['senha']))
                if ($dadosPw && password_verify($senha_atual, $dadosPw['senha'])) {
                    $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                    $updatePw = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id_usuario = ?");
                    $updatePw->execute([$nova_senha_hash, $id_usuario]);

                    if (function_exists('registrarHistorico')) {
                        registrarHistorico($pdo, $id_usuario, 'ALTERAR', 'Segurança', 'Alterou a senha de acesso.');
                    }
                    $alerta_js = "<script>alert('Senha alterada com sucesso!'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
                } else {
                    $alerta_js = "<script>alert('A senha atual está incorreta!');</script>";
                }
            } catch (PDOException $e) {
                error_log("Erro ao alterar senha: " . $e->getMessage());
                $alerta_js = "<script>alert('Erro interno ao tentar alterar a senha.');</script>";
            }
        }
    }
}

// ==========================================
// LÓGICA 5: BUSCAR DADOS DO BANCO PARA ALIMENTAR OS INPUTS
// ==========================================
try {
    // CORRIGIDO: Ajustado para buscar nome_completo e cpf
    $queryUser = $pdo->prepare("SELECT nome_completo, email, matricula, cpf FROM usuarios WHERE id_usuario = ?");
    $queryUser->execute([$id_usuario]);
    $dados_usuario = $queryUser->fetch();
    
    $nome_usuario   = $dados_usuario['nome_completo'] ?? $_SESSION['nome'];
    $email_usuario  = $dados_usuario['email'] ?? $_SESSION['email'];
    $matricula_user = $dados_usuario['matricula'] ?? $_SESSION['matricula'];
    $cpf_user       = $dados_usuario['cpf'] ?? $_SESSION['cpf_usuario'];
    $inicial_nome   = !empty($nome_usuario) ? strtoupper($nome_usuario[0]) : 'U';

    // 4.2 Busca dados atualizados da Empresa
    $queryEmp = $pdo->prepare("SELECT nome_empresa, cnpj, logo_path FROM empresas WHERE id_empresa = ?");
    $queryEmp->execute([$id_empresa]);
    $dados_empresa = $queryEmp->fetch();

    $nome_empresa = $dados_empresa['nome_empresa'] ?? 'MetaCash Finanças';
    $cnpj_empresa = $dados_empresa['cnpj'] ?? '12.345.678/0001-99';
    $logo_path    = $dados_empresa['logo_path'] ?? ''; 
} catch (PDOException $e) {
    // Fallbacks de segurança se o banco falhar
    $nome_usuario   = $_SESSION['nome'];
    $email_usuario  = $_SESSION['email'];
    $matricula_user = $_SESSION['matricula'];
    $cpf_user       = $_SESSION['cpf_usuario'];
    $inicial_nome   = 'U';
    $nome_empresa   = 'MetaCash Finanças';
    $cnpj_empresa   = '12.345.678/0001-99';
    $logo_path      = '';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Meu Perfil</title>
    
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
        console.error("Erro ao ler localStorage do tema:", erro);
    }
    </script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-meta-fundo transition-colors duration-200 min-h-screen configured flex overflow-x-hidden w-full">

    <?php include_once '../includes/sidebarGerente.php'; ?>
    
    <main class="flex-1 p-10 w-full ml-64">
        <header class="mb-8">
            <a href="javascript:history.back()" class="text-sm text-slate-500 hover:text-meta-destaque flex items-center gap-2 mb-4 transition">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <h1 class="text-3xl font-extrabold text-[#0f172a]">Meu Perfil</h1>
            <p class="text-slate-500 mt-1">Gerencie suas informações pessoais, corporativas e segurança</p>
        </header>

        <div class="max-w-5xl space-y-8">
            
            <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <h2 class="font-bold text-slate-800">Informações Pessoais</h2>
                </div>
                
                <div class="p-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-20 h-20 bg-meta-destaque rounded-full flex items-center justify-center text-white text-3xl font-bold transition-colors shadow-inner">
                            <?= htmlspecialchars($inicial_nome ?? 'G'); ?>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800"><?= htmlspecialchars($nome_usuario ?? 'Gerente'); ?></h3>
                            <span class="inline-block bg-blue-100 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase mb-1">
                                <i class="fas fa-shield-alt mr-1"></i> <?= htmlspecialchars($_SESSION['nivel_permissao'] ?? 'Gerente'); ?>
                            </span>
                            <p class="text-sm text-slate-400"><?= htmlspecialchars($email_usuario ?? 'contato@empresa.com'); ?></p>
                        </div>
                    </div>

                    <form method="POST" action="" class="space-y-6">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nome Completo</label>
                                <div class="relative">
                                    <i class="far fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" name="nome" value="<?= htmlspecialchars($nome_usuario ?? 'Usuário'); ?>" class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-700 focus:ring-2 focus:ring-meta-destaque outline-none transition">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Matrícula</label>
                                <div class="relative">
                                    <i class="far fa-id-badge absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" name="matricula" value="<?= htmlspecialchars($_SESSION['matricula'] ?? '2024001'); ?>" class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-700 focus:ring-2 focus:ring-meta-destaque outline-none transition">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">CPF</label>
                                <div class="relative">
                                    <i class="far fa-address-card absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" name="cpf" value="<?= htmlspecialchars($_SESSION['cpf_usuario'] ?? '123.456.789-00'); ?>" class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-700 focus:ring-2 focus:ring-meta-destaque outline-none transition">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Email</label>
                                <div class="relative">
                                    <i class="far fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="email" name="email" value="<?= htmlspecialchars($email_usuario ?? 'usuario@exemplo.com'); ?>" class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-meta-destaque outline-none transition">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" name="btn_atualizar_perfil" class="bg-meta-menu text-white px-8 py-3 rounded-xl font-bold hover:opacity-90 transition shadow-lg flex items-center gap-2">
                                <i class="fas fa-save"></i> Salvar Alterações Pessoais
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-lock text-sm"></i>
                    </div>
                    <h2 class="font-bold text-slate-800">Segurança</h2>
                </div>

                <div class="p-8">
                    <form method="POST" action="" class="space-y-6">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Senha Atual <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <i class="fas fa-unlock-alt absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                    <input type="password" name="senha_atual" placeholder="Digite sua senha atual" class="w-full pl-11 pr-12 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-meta-destaque outline-none transition" required>
                                    <button type="button" onclick="togglePassword(this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nova Senha <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <i class="fas fa-unlock-alt absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                    <input type="password" id="nova_senha_input" name="nova_senha" placeholder="Digite sua nova senha" class="w-full pl-11 pr-12 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-meta-destaque outline-none transition" required>
                                    <button type="button" onclick="togglePassword(this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Confirmar Nova Senha <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <i class="fas fa-unlock-alt absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                    <input type="password" name="confirmar_senha" placeholder="Confirme sua nova senha" class="w-full pl-11 pr-12 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-meta-destaque outline-none transition" required>
                                    <button type="button" onclick="togglePassword(this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row justify-between items-end gap-6 pt-4">
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 w-full md:w-fit">
                                <h4 class="text-xs font-bold text-slate-600 mb-3">Requisitos de Senha:</h4>
                                <div class="grid grid-cols-2 gap-x-8 gap-y-2">
                                    <div class="text-[10px] text-slate-500 flex items-center gap-2"><i id="req-length" class="fas fa-times text-red-400 w-3 text-center transition-colors"></i> Mínimo de 8 caracteres</div>
                                    <div class="text-[10px] text-slate-500 flex items-center gap-2"><i id="req-upper" class="fas fa-times text-red-400 w-3 text-center transition-colors"></i> Letra maiúscula (A-Z)</div>
                                    <div class="text-[10px] text-slate-500 flex items-center gap-2"><i id="req-lower" class="fas fa-times text-red-400 w-3 text-center transition-colors"></i> Letra minúscula (a-z)</div>
                                    <div class="text-[10px] text-slate-500 flex items-center gap-2"><i id="req-number" class="fas fa-times text-red-400 w-3 text-center transition-colors"></i> Número (0-9)</div>
                                    <div class="text-[10px] text-slate-500 flex items-center gap-2"><i id="req-special" class="fas fa-times text-red-400 w-3 text-center transition-colors"></i> Caractere especial (!@#$...)</div>
                                </div>
                            </div>
                            <button type="submit" name="btn_alterar_senha" class="bg-slate-100 text-slate-600 px-6 py-2.5 rounded-lg text-sm hover:bg-red-50 hover:text-red-600 transition duration-300 flex items-center gap-2 border border-slate-200 hover:border-red-200 shadow-sm">
                                <i class="fas fa-key text-xs"></i> Alterar Senha
                            </button>
                        </div>
                    </form>
                </div>
            </section>
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
        
    </main>

    <script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
    }

    function togglePassword(button) {
        const input = button.previousElementSibling;
        const icon = button.querySelector('i');
        
        if (input && input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('far', 'fa-eye');
            icon.classList.add('fas', 'fa-eye-slash');
        } else if (input) {
            input.type = 'password';
            icon.classList.remove('fas', 'fa-eye-slash');
            icon.classList.add('far', 'fa-eye');
        }
    }

    // Validação de requisitos de senha em tempo real
    document.addEventListener('DOMContentLoaded', function() {
        const novaSenhaInput = document.getElementById('nova_senha_input');
        
        if (novaSenhaInput) {
            novaSenhaInput.addEventListener('input', function(e) {
                const val = e.target.value;
                
                const updateIcon = (id, isValid) => {
                    const icon = document.getElementById(id);
                    if (icon) {
                        if (isValid) {
                            icon.classList.remove('fa-times', 'text-red-400');
                            icon.classList.add('fa-check', 'text-emerald-500'); 
                        } else {
                            icon.classList.remove('fa-check', 'text-emerald-500');
                            icon.classList.add('fa-times', 'text-red-400');
                        }
                    }
                };

                updateIcon('req-length', val.length >= 8);
                updateIcon('req-upper', /[A-Z]/.test(val));
                updateIcon('req-lower', /[a-z]/.test(val));
                updateIcon('req-number', /[0-9]/.test(val));
                updateIcon('req-special', /[^A-Za-z0-9]/.test(val));
            });
        }
    });
    </script>

    <?= $alerta_js ?>
</body>
</html>