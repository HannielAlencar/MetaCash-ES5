<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Gestão Financeira Empresarial</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>

    <div class="main-content">
        <header>
            <a href="index.php" class="logo-container">
                <img src="assets/img/logoCyano.png" alt="MetaCash" style="width: 28px; height: 28px; object-fit: contain;">
                MetaCash
            </a>
            <div class="header-actions">
                <form action="auth/login.php" method="GET" class="hidden-form">
                    <button type="submit" class="btn-link">Entrar</button>
                </form>
                
                <form action="auth/cadastro.php" method="GET" class="hidden-form">
                    <button type="submit" class="btn-header-register">Cadastro</button>
                </form>
            </div>
        </header>

        <section class="hero-section">
            <div class="badge">Customizável e Flexível</div>
            <h1>Gestão Financeira Empresarial <span class="gradient-text">Completamente Personalizável</span></h1>
            <p class="hero-subtitle">MetaCash é a plataforma de gestão financeira que se adapta ao seu negócio. Customize cores, layouts, functionalities e crie a ferramenta perfeita para sua empresa.</p>
            
            <form action="auth/cadastro.php" method="GET">
                <button type="submit" class="btn-primary-cta">
                    Cadastro sua empresa 
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </button>
            </form>

            <div class="stats-container">
                <div class="stat-item">
                    <h3>100%</h3>
                    <p>Customizável</p>
                </div>
                <div class="stat-item">
                    <h3 class="infinity-stat">∞</h3>
                    <p>Possibilidades</p>
                </div>
            </div>
        </section>

        <section class="custom-section">
            <h2 class="section-title">Totalmente Customizável Para Sua Empresa</h2>
            <p class="section-subtitle">Adapte cada aspecto do MetaCash às necessidades específicas do seu negócio</p>
            
            <div class="cards-grid-two">
                <div class="dark-card">
                    <div class="card-icon-container">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="3" y1="9" x2="21" y2="9"/></svg>
                    </div>
                    <h4>Layouts Flexíveis</h4>
                    <p>Reorganize módulos, dashboards e relatórios da forma que melhor atende seu fluxo de trabalho.</p>
                </div>
                <div class="dark-card">
                    <div class="card-icon-container">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/><path d="M3 12c0 1.66 4 3 9 3s9-1.34 9-3"/></svg>
                    </div>
                    <h4>Dados Sob Controle</h4>
                    <p>Configure campos personalizados, categorias e métricas específicas para seu setor de atuação.</p>
                </div>
            </div>
        </section>

        <section class="features-section">
            <h2 class="section-title">Funcionalidades Completas</h2>
            <p class="section-subtitle">Tudo que sua empresa precisa para gestão financeira em uma única plataforma</p>
            
            <div class="cards-grid-three">
                <div class="light-card">
                    <div class="card-icon-container">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    </div>
                    <h4>Dashboard Inteligente</h4>
                    <p>Visualize todas as métricas importantes com gráficos interativos e personalizáveis.</p>
                </div>
                <div class="light-card">
                    <div class="card-icon-container">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <h4>Gestão de Equipe</h4>
                    <p>Adicione membros e defina permissions para cada colaborador.</p>
                </div>
                <div class="light-card">
                    <div class="card-icon-container">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                    </div>
                    <h4>Gestão de Transações</h4>
                    <p>Controle completo de receitas e despesas, contas a pagar e receber com categorização automática.</p>
                </div>
            </div>

            <div class="cta-container-wrapper">
                <div class="cta-gradient-box">
                    <h2>Pronto para transformar sua gestão financeira?</h2>
                    <p>Junte-se às empresas que já otimizaram suas finanças com o MetaCash. Customize, adapte e cresça com a plataforma mais flexível do mercado.</p>
                    <div class="cta-actions">
                        <form action="auth/cadastro.php" method="GET">
                            <button type="submit" class="btn-cta-white">
                                Cadastre sua empresa Agora
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </button>
                        </form>

                        <form action="auth/login.php" method="GET">
                            <button type="submit" class="btn-cta-outline">Fazer Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer>
        <p>&copy; 2026 MetaCash. Todos os direitos reservados.</p>
    </footer>

</body>
</html>