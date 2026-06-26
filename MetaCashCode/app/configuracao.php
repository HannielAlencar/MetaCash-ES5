<?php
// LINHA 1: Inicia a sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. IMPORTA A CONEXÃO COM O BANCO DE DADOS
require_once '../config.php'; 

// Trava de segurança
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['nivel_permissao']) || ($_SESSION['nivel_permissao'] !== 'Gerente' && $_SESSION['nivel_permissao'] !== 'Admin')) {
    header("Location: dashboardUsuario.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];
$id_empresa = $_SESSION['id_empresa'] ?? null;
$mensagem_erro = '';

// ==========================================
// LÓGICA NOVO: REMOVER LOGO
// ==========================================
if (isset($_POST['btn_remover_logo']) && $id_empresa) {
    try {
        $stmt = $pdo->prepare("SELECT logo_path FROM empresas WHERE id_empresa = ?");
        $stmt->execute([$id_empresa]);
        $emp = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($emp && !empty($emp['logo_path']) && file_exists($emp['logo_path'])) {
            unlink($emp['logo_path']);
        }

        $stmt = $pdo->prepare("UPDATE empresas SET logo_path = NULL WHERE id_empresa = ?");
        $stmt->execute([$id_empresa]);
        
        $_SESSION['logo_path'] = null;
        header("Location: " . $_SERVER['PHP_SELF'] . "?sucesso=logo_removida");
        exit;
    } catch (PDOException $e) {
        $mensagem_erro = "Erro ao remover logo: " . $e->getMessage();
    }
}

// ==========================================
// LÓGICA NOVO: RESTAURAR LOGO PADRÃO
// ==========================================
if (isset($_POST['btn_reset_padrao_logo']) && $id_empresa) {
    try {
        $stmt = $pdo->prepare("SELECT logo_path FROM empresas WHERE id_empresa = ?");
        $stmt->execute([$id_empresa]);
        $emp = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($emp && !empty($emp['logo_path']) && file_exists($emp['logo_path'])) {
            unlink($emp['logo_path']);
        }

        $stmt = $pdo->prepare("UPDATE empresas SET logo_path = NULL WHERE id_empresa = ?");
        $stmt->execute([$id_empresa]);
        
        $_SESSION['logo_path'] = null;
        header("Location: " . $_SERVER['PHP_SELF'] . "?sucesso=logo_restaurada");
        exit;
    } catch (PDOException $e) {
        $mensagem_erro = "Erro ao restaurar logo padrão: " . $e->getMessage();
    }
}

// ==========================================
// LÓGICA 1: REMOVER CATEGORIA
// ==========================================
if (isset($_GET['delete_categoria']) && $id_empresa) {
    $id_del = (int) $_GET['delete_categoria'];
    try {
        $stmt = $pdo->prepare("DELETE FROM categoria WHERE id_categoria = ? AND id_empresa = ?");
        $stmt->execute([$id_del, $id_empresa]);
        
        if (function_exists('registrarHistorico')) {
            registrarHistorico($pdo, $id_usuario, 'EXCLUIR', 'Categoria', "Removeu a categoria ID: $id_del");
        }
        header("Location: " . $_SERVER['PHP_SELF'] . "?sucesso=cat_removida");
        exit;
    } catch (PDOException $e) {
        $mensagem_erro = "Erro ao remover categoria. Ela pode estar em uso nas transações.";
    }
}

// ==========================================
// LÓGICA 2: ADICIONAR NOVA CATEGORIA
// ==========================================
if (isset($_POST['btn_add_categoria']) && $id_empresa) {
    $nome_cat = trim($_POST['nome_categoria']);
    $tipo_input = $_POST['tipo_categoria']; 
    $tipo_cat = ($tipo_input === 'e') ? 'Receita' : 'Despesa';

    if (!empty($nome_cat)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO categoria (id_empresa, nome_categoria, tipo_categoria) VALUES (?, ?, ?)");
            $stmt->execute([$id_empresa, $nome_cat, $tipo_cat]);
            
            if (function_exists('registrarHistorico')) {
                registrarHistorico($pdo, $id_usuario, 'INSERIR', 'Categoria', "Adicionou a categoria: $nome_cat");
            }
            header("Location: " . $_SERVER['PHP_SELF'] . "?sucesso=cat_adicionada");
            exit;
        } catch (PDOException $e) {
            $mensagem_erro = "Erro ao adicionar categoria: " . $e->getMessage();
        }
    } else {
        $mensagem_erro = "O nome da categoria não pode ficar vazio.";
    }
}

// ==========================================
// LÓGICA 3: ATUALIZAR EMPRESA (COM DATA INÍCIO)
// ==========================================
if (isset($_POST['btn_update_empresa']) && $id_empresa) {
    $nome_emp = trim($_POST['nome_empresa'] ?? '');
    $cnpj_emp = preg_replace('/[^0-9]/', '', $_POST['cnpj'] ?? '');
    $data_inicio = !empty($_POST['data_inicio']) ? $_POST['data_inicio'] : null; // Captura a data
    
    try {
        // Atualiza inclusive a data_inicio
        $stmt = $pdo->prepare("UPDATE empresas SET nome_empresa = ?, cnpj = ?, data_inicio = ? WHERE id_empresa = ?");
        $stmt->execute([$nome_emp, $cnpj_emp, $data_inicio, $id_empresa]);
        
        if (isset($_FILES['logo_empresa']) && $_FILES['logo_empresa']['error'] === UPLOAD_ERR_OK) {
            $extensoes_permitidas = ['png', 'jpg', 'jpeg', 'svg'];
            $extensao = strtolower(pathinfo($_FILES['logo_empresa']['name'], PATHINFO_EXTENSION));
            
            if (in_array($extensao, $extensoes_permitidas)) {
                $diretorio_destino = '../uploads/';
                if (!is_dir($diretorio_destino)) {
                    mkdir($diretorio_destino, 0777, true); 
                }
                
                $nome_arquivo = 'logo_empresa_' . $id_empresa . '_' . time() . '.' . $extensao;
                $caminho_completo = $diretorio_destino . $nome_arquivo;
                
                if (move_uploaded_file($_FILES['logo_empresa']['tmp_name'], $caminho_completo)) {
                    $stmtLogo = $pdo->prepare("UPDATE empresas SET logo_path = ? WHERE id_empresa = ?");
                    $stmtLogo->execute([$caminho_completo, $id_empresa]);
                    $_SESSION['logo_path'] = $caminho_completo;
                }
            } else {
                $mensagem_erro = "Formato de imagem inválido. Use PNG, JPG ou SVG.";
            }
        }

        if (function_exists('registrarHistorico') && empty($mensagem_erro)) {
            registrarHistorico($pdo, $id_usuario, 'ALTERAR', 'Empresa', "Atualizou configurações da empresa.");
        }
        
        if(empty($mensagem_erro)) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?sucesso=empresa_atualizada");
            exit;
        }
    } catch (PDOException $e) {
        $mensagem_erro = "Erro ao atualizar dados da empresa: " . $e->getMessage();
    }
}

// ==========================================
// BUSCA DADOS PARA PREENCHER A TELA
// ==========================================
$empresa_atual = [];
$receitas = [];
$despesas = [];

$categorias_padrao = [
    ['id_categoria' => '1000', 'nome_categoria' => 'Salário', 'tipo_categoria' => 'Receita'],
    ['id_categoria' => '1001', 'nome_categoria' => 'Aluguel', 'tipo_categoria' => 'Despesa'],
    ['id_categoria' => '1002', 'nome_categoria' => 'Alimentação', 'tipo_categoria' => 'Despesa'],
    ['id_categoria' => '1003', 'nome_categoria' => 'Transporte', 'tipo_categoria' => 'Despesa']
];

if ($id_empresa) {
    try {
        // Incluído data_inicio na busca
        $stmtEmp = $pdo->prepare("SELECT nome_empresa, cnpj, logo_path, data_inicio FROM empresas WHERE id_empresa = ?");
        $stmtEmp->execute([$id_empresa]);
        $empresa_atual = $stmtEmp->fetch(PDO::FETCH_ASSOC) ?: [];
        
        if (!empty($empresa_atual['logo_path'])) {
            $_SESSION['logo_path'] = $empresa_atual['logo_path'];
        } else {
            $_SESSION['logo_path'] = null;
        }
        
        $stmtCat = $pdo->prepare("SELECT id_categoria, nome_categoria, tipo_categoria FROM categoria WHERE id_empresa = ? ORDER BY nome_categoria ASC");
        $stmtCat->execute([$id_empresa]);
        $user_cats = $stmtCat->fetchAll(PDO::FETCH_ASSOC);
        
        $todas_cats = array_merge($user_cats, $categorias_padrao);
        
        foreach ($todas_cats as $cat) {
            if (strtolower($cat['tipo_categoria']) === 'e' || strtolower($cat['tipo_categoria']) === 'receita') {
                $receitas[] = $cat;
            } else {
                $despesas[] = $cat;
            }
        }
    } catch (PDOException $e) {
        $mensagem_erro = "Erro ao buscar dados: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - MetaCash</title>
    
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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght=300;400;500;600;700;800&display=swap');
        :root {
            --meta-menu: #0F2440;
            --meta-btn1: #204C73;
            --meta-destaque: #24A6B6;
            --meta-btn2: #35C59A;
            --meta-clara: #5DA4C0;
            --meta-fundo: #FDFEFB;
        }
        body { font-family: 'Inter', sans-serif; }
        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
        }
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
            console.error("Erro ao ler localStorage:", erro);
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-meta-fundo transition-colors duration-200 min-h-screen antialiased">

    <div class="flex min-h-screen">
        <?php include_once '../includes/sidebarGerente.php'; ?>

        <main class="flex-1 p-10 ml-64 overflow-y-auto">
            <header class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Configurações</h1>
                    <p class="text-slate-500 mt-1 text-sm">Personalize os dados operacionais e de identidade da empresa no MetaCash</p>
                </div>
                <button onclick="window.location.reload();" class="text-sm font-bold text-slate-400 hover:text-meta-destaque transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-rotate-left"></i> Restaurar Dados Originais
                </button>
            </header>

            <?php if (isset($_GET['sucesso'])): ?>
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-check-circle text-lg"></i> Suas alterações foram salvas com sucesso!
                </div>
            <?php endif; ?>
            
            <?php if (!empty($mensagem_erro)): ?>
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-exclamation-triangle text-lg"></i> <?= htmlspecialchars($mensagem_erro) ?>
                </div>
            <?php endif; ?>

            <form id="formEmpresa" method="POST" enctype="multipart/form-data">
                
                <section class="bg-white border border-gray-200 p-8 rounded-3xl shadow-sm mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-meta-clara/10 flex items-center justify-center rounded-xl text-meta-clara">
                            <i class="fa-solid fa-building text-base"></i>
                        </div>
                        <h2 class="font-bold text-slate-800 text-lg">Informações da Empresa</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nome da Empresa <span class="text-red-500">*</span></label>
                            <input type="text" name="nome_empresa" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 bg-slate-50 outline-none focus:bg-white focus:border-meta-destaque focus:ring-4 focus:ring-meta-destaque/20 transition-all duration-200 mt-2" value="<?= htmlspecialchars($empresa_atual['nome_empresa'] ?? '') ?>" required>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">CNPJ <span class="text-red-500">*</span></label>
                            <input type="text" name="cnpj" id="cnpj" maxlength="18" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 bg-slate-50 outline-none focus:bg-white focus:border-meta-destaque focus:ring-4 focus:ring-meta-destaque/20 transition-all duration-200 mt-2" placeholder="00.000.000/0000-00" value="<?= htmlspecialchars($empresa_atual['cnpj'] ?? '') ?>" required>
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
                        <button type="submit" name="btn_update_empresa" class="bg-meta-btn1 text-white px-5 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 hover:opacity-90 active:scale-95 transition-all shadow-md">
                            <i class="fa-solid fa-rotate text-xs"></i> Atualizar Empresa
                        </button>
                    </div>
                </section>

                <section class="bg-white border border-gray-200 p-8 rounded-3xl shadow-sm mb-8">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-meta-clara/10 flex items-center justify-center rounded-xl text-meta-clara">
                            <i class="fa-solid fa-upload text-base"></i>
                        </div>
                        <h2 class="font-bold text-slate-800 text-lg">Sua Logo</h2>
                    </div>
                    
                    <div class="border-dashed border-2 border-slate-200 rounded-2xl p-8 flex flex-col items-start bg-slate-50/50 hover:bg-slate-50 transition-colors">
                        
                        <?php if(!empty($empresa_atual['logo_path'])): ?>
                            <div class="mb-5 bg-white p-2 rounded-xl shadow-sm border border-slate-100">
                                <img src="<?= htmlspecialchars($empresa_atual['logo_path']) ?>" alt="Logo Atual" class="h-16 object-contain">
                            </div>
                        <?php endif; ?>

                        <div class="flex gap-3 flex-wrap">
                            <label class="bg-meta-btn1 text-white px-5 py-2.5 rounded-xl font-bold text-sm mb-3 hover:opacity-90 transition-all shadow-sm cursor-pointer inline-flex items-center gap-2">
                                <i class="fa-solid fa-image"></i> Escolher Novo Arquivo
                                <input type="file" name="logo_empresa" class="hidden" accept="image/png, image/jpeg, image/svg+xml" onchange="document.getElementById('fileNameSpan').textContent = this.files[0].name; document.getElementById('btnSubmitLogo').classList.remove('hidden');">
                            </label>
                            
                            <?php if(!empty($empresa_atual['logo_path'])): ?>
                                <button type="submit" name="btn_remover_logo" class="bg-red-50 text-red-600 px-5 py-2.5 rounded-xl font-bold text-sm mb-3 hover:bg-red-100 transition-all shadow-sm flex items-center gap-2">
                                    <i class="fa-solid fa-trash"></i> Remover Logo
                                </button>
                                <button type="submit" name="btn_reset_padrao_logo" class="bg-slate-100 text-slate-600 px-5 py-2.5 rounded-xl font-bold text-sm mb-3 hover:bg-slate-200 transition-all shadow-sm flex items-center gap-2">
                                    <i class="fa-solid fa-undo"></i> Restaurar Padrão
                                </button>
                            <?php endif; ?>
                            
                            <button type="submit" name="btn_update_empresa" id="btnSubmitLogo" class="hidden bg-meta-destaque text-white px-5 py-2.5 rounded-xl font-bold text-sm mb-3 hover:opacity-90 transition-all shadow-sm">
                                Salvar Upload
                            </button>
                        </div>

                        <span id="fileNameSpan" class="text-xs text-meta-destaque font-bold mb-2"></span>
                        <p class="text-[11px] text-slate-400 uppercase font-semibold tracking-wider">Formatos aceitos: PNG, JPG, SVG. Tamanho máximo: 2MB</p>
                    </div>
                </section>
                
            </form>

              <section class="bg-white border border-gray-200 p-8 rounded-3xl shadow-sm mb-8">
                  <div class="flex items-center gap-3 mb-6">
                  <div class="w-10 h-10 bg-meta-clara/10 flex items-center justify-center rounded-xl text-meta-clara">
                  <i class="fa-solid fa-building text-base"></i>
                  </div>
                  <h2 class="font-bold text-slate-800 text-lg">SALDO INICIAL</h2>
                  </div>
    
                  <form action="../app/salvarSaldo.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                  <div>
                  <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Saldo Total (R$) <span class="text-red-500">*</span></label>
                  <input type="number" step="0.01" name="saldo_inicial" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 bg-slate-50 outline-none focus:bg-white focus:border-meta-destaque focus:ring-4 focus:ring-meta-destaque/20 transition-all duration-200 mt-2" value="<?= htmlspecialchars($saldo_inicial) ?>" required>
                  </div>
                  <div>
                  <button type="submit" class="bg-meta-destaque text-white px-6 py-3 rounded-xl font-bold hover:bg-teal-600 transition">
                  Atualizar Saldo
                 </button>
                </div>
             </form>
            </section>

            <section class="bg-white border border-gray-200 p-8 rounded-3xl shadow-sm mb-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-meta-clara/10 flex items-center justify-center rounded-xl text-meta-clara">
                        <i class="fa-solid fa-tags text-base"></i>
                    </div>
                    <h2 class="font-bold text-slate-800 text-lg">Categorias Personalizadas</h2>
                </div>
                
                <form method="POST" class="bg-slate-50 p-5 rounded-2xl flex flex-wrap md:flex-nowrap gap-3 mb-6 border border-slate-100 shadow-inner">
                    <input type="text" name="nome_categoria" required class="flex-1 border border-slate-300 rounded-xl px-4 py-3 text-sm outline-none focus:bg-white focus:border-meta-destaque focus:ring-4 focus:ring-meta-destaque/20 transition-all duration-200" placeholder="Nome da nova categoria...">
                    <select name="tipo_categoria" class="border border-slate-300 rounded-xl px-4 py-3 bg-white text-sm font-semibold text-slate-600 outline-none focus:border-meta-destaque focus:ring-4 focus:ring-meta-destaque/20 transition-all duration-200 min-w-[140px] cursor-pointer">
                        <option value="e">Receita (Entrada)</option>
                        <option value="s">Despesa (Saída)</option>
                    </select>
                    <button type="submit" name="btn_add_categoria" class="bg-meta-destaque text-white px-6 py-3 rounded-xl font-bold text-sm transition-all shadow-md active:scale-95 hover:opacity-90">
                        + Adicionar
                    </button>
                </form>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-xs font-bold text-meta-destaque mb-4 uppercase tracking-wider">Receitas (Entradas)</h3>
                        <?php if(empty($receitas)): ?>
                            <p class="text-sm text-slate-400 italic">Nenhuma receita cadastrada.</p>
                        <?php else: ?>
                            <?php foreach($receitas as $r): ?>
                                <div class="flex justify-between items-center border border-slate-200 rounded-xl px-4 py-3 mb-2.5 text-sm text-slate-600 bg-white hover:border-slate-300 transition-colors shadow-sm">
                                    <span class="font-medium flex items-center gap-2"><i class="fas fa-circle text-[8px] text-meta-btn2"></i> <?= htmlspecialchars($r['nome_categoria'], ENT_QUOTES, 'UTF-8') ?></span>
                                    <a href="?delete_categoria=<?= $r['id_categoria'] ?>" onclick="return confirm('Excluir definitivamente esta categoria?');" class="text-slate-400 hover:text-red-500 transition-colors p-1 rounded hover:bg-red-50">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div>
                        <h3 class="text-xs font-bold text-slate-400 mb-4 uppercase tracking-wider">Despesas (Saídas)</h3>
                        <?php if(empty($despesas)): ?>
                            <p class="text-sm text-slate-400 italic">Nenhuma despesa cadastrada.</p>
                        <?php else: ?>
                            <?php foreach($despesas as $d): ?>
                                <div class="flex justify-between items-center border border-slate-200 rounded-xl px-4 py-3 mb-2.5 text-sm text-slate-600 bg-white hover:border-slate-300 transition-colors shadow-sm">
                                    <span class="font-medium flex items-center gap-2"><i class="fas fa-circle text-[8px] text-red-400"></i> <?= htmlspecialchars($d['nome_categoria'], ENT_QUOTES, 'UTF-8') ?></span>
                                    <a href="?delete_categoria=<?= $d['id_categoria'] ?>" onclick="return confirm('Excluir definitivamente esta categoria?');" class="text-slate-400 hover:text-red-500 transition-colors p-1 rounded hover:bg-red-50">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <div class="flex justify-end pb-10">
                <button onclick="document.getElementById('formEmpresa').submit();" class="bg-meta-destaque text-white px-8 py-4 rounded-2xl font-bold flex items-center gap-2 hover:opacity-90 shadow-xl transition-all hover:-translate-y-0.5 active:translate-y-0">
                    <i class="fa-solid fa-floppy-disk"></i> Salvar Todas as Alterações
                </button>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('cnpj').addEventListener('input', function (e) {
            var x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,3})(\d{0,3})(\d{0,4})(\d{0,2})/);
            e.target.value = !x[2] ? x[1] : x[1] + '.' + x[2] + (x[3] ? '.' + x[3] : '') + (x[4] ? '/' + x[4] : '') + (x[5] ? '-' + x[5] : '');
        });
    </script>

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
    </script>
</body>
</html>