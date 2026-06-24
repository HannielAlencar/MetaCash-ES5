<?php 
require_once __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $config['nome_app'] ?? 'MetaCash'; ?> - Gestão Financeira</title>
    
    <link rel="stylesheet" href="assets/css/home.css">
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="nav-logo">
            <img src="assets/img/logo.png" alt="Logo">
            <span><?php echo $config['nome_app'] ?? 'MetaCash'; ?></span>
        </a>
        <div class="nav-links">
            <!-- CORREÇÃO: Simplificação de caminhos. Removido o ".php" do nome das pastas -->
            <a href="auth/login.php" class="btn-login">Entrar</a>
            <a href="auth/cadastro.php" class="btn-cadastro">Cadastro</a>
        </div>
    </nav>

    <header class="hero">
        <span class="badge">💎 Customizável e Flexível</span>
        <h1>Gestão Financeira Empresarial <br> <span class="text-cyan">Completamente Personalizável</span></h1>
        <p>MetaCash é a plataforma que se adapta ao seu negócio. Customize cores, layouts e crie a ferramenta perfeita.</p>
        
        <!-- CORREÇÃO: Ajuste de link (removido o .php da pasta) -->
        <a href="auth/cadastro.php" class="btn-main">
            <span>Experimentar</span> <span>+</span>
        </a>

        <div class="hero-stats">
            <div class="stat-item">
                <p class="stat-number">100%</p>
                <p class="stat-label">Customizável</p>
            </div>
            <div class="stat-item">
                <p class="stat-number text-emerald">∞</p>
                <p class="stat-label">Possibilidades</p>
            </div>
        </div>
    </header>

    <section class="custom-section">
        <div class="section-header">
            <h2>Totalmente Customizável Para Sua Empresa</h2>
            <p class="subtitle">Adapte cada aspecto do MetaCash às necessidades específicas do seu negócio</p>
        </div>

        <div class="custom-grid">
            <?php if(isset($custom_features) && is_array($custom_features)): foreach ($custom_features as $cf): ?>
                <div class="custom-card">
                    <div class="icon-cyan">
                        <?php echo $cf['icon']; ?>
                    </div>
                    <h3><?php echo $cf['title']; ?></h3>
                    <p><?php echo $cf['desc']; ?></p>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </section>

    <section class="features-section">
        <div class="section-title">
            <h2>Funcionalidades Completas</h2>
            <p>Tudo que sua empresa precisa para gestão financeira em uma única plataforma</p>
        </div>
        
        <div class="features-grid">
            <?php if(isset($features) && is_array($features)): foreach ($features as $f): ?>
                <div class="feature-card">
                    <div class="icon-blue">
                        <?php echo $f['icon']; ?>
                    </div>
                    <h4><?php echo $f['title']; ?></h4>
                    <p><?php echo $f['desc']; ?></p>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </section>

    <section class="cta-section">
        <div class="cta-container">
            <h2><?php echo $cta_data['titulo'] ?? 'Pronto para começar?'; ?></h2>
            <p><?php echo $cta_data['subtitulo'] ?? ''; ?></p>
            <div class="cta-btns">
            
                <a href="auth/cadastro.php" class="btn-white">Cadastrar-se Agora →</a>
                <a href="auth/login.php" class="btn-outline">Fazer Login</a>
            </div>
        </div>
    </section>

    <footer class="footer-simple">
        <p>© <?php echo (isset($config['ano_atual']) ? $config['ano_atual'] : date('Y')) . " " . ($config['nome_app'] ?? 'MetaCash'); ?>. Todos os direitos reservados.</p>
    </footer>

</body>
</html>