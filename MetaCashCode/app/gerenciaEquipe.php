<?php
$equipe = [
    ['nome' => 'Ana Paula Silva', 'email' => 'ana.silva@empresa.co', 'cargo' => 'Gerente', 'sigla' => 'AP'],
    ['nome' => 'Carlos Santos', 'email' => 'carlos.santos@empresa.cc', 'cargo' => 'Gerente', 'sigla' => 'CS'],
    ['nome' => 'Mariana Costa', 'email' => 'mariana.costa@empresa.cc', 'cargo' => 'Membro', 'sigla' => 'MC'],
    ['nome' => 'Roberto Alves', 'email' => 'roberto.alves@empresa.cc', 'cargo' => 'Gerente', 'sigla' => 'RA'],
    ['nome' => 'Juliana Ferreira', 'email' => 'juliana.ferreira@empresa.co', 'cargo' => 'Membro', 'sigla' => 'JF'],
    ['nome' => 'Pedro Oliveira', 'email' => 'pedro.oliveira@empresa.co', 'cargo' => 'Membro', 'sigla' => 'PO'],
    ['nome' => 'Juandir Alves', 'email' => 'juandir.alves@empresa.com', 'cargo' => 'Gerente', 'sigla' => 'JA'],
    ['nome' => 'Claudia Ferreira', 'email' => 'claudia.ferreia@empresa.co', 'cargo' => 'Membro', 'sigla' => 'CF'],
    ['nome' => 'Fernando Dolores', 'email' => 'fernando.dolores@empresa.com', 'cargo' => 'Membro', 'sigla' => 'FD'],
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>MetaCash - Equipe</title>
    <link rel="stylesheet" href="Gerencia.css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <i class="fa-solid fa-city"></i>
                <div>
                    <strong>MetaCash</strong>
                    <span>Gestão Empresarial</span>
                </div>
            </div>
            
            <nav>
                <a href="#"><i class="fa-solid fa-border-all"></i> Dashboard</a>
                <a href="#"><i class="fa-solid fa-dollar-sign"></i> Transações</a>
                <a href="#" class="active"><i class="fa-solid fa-users-gear"></i> Equipe</a>
                <a href="#"><i class="fa-regular fa-newspaper"></i> Gerenciar Páginas</a>
                <a href="#"><i class="fa-solid fa-clock-rotate-left"></i> Histórico</a>
                <a href="#"><i class="fa-solid fa-gear"></i> Configurações</a>
            </nav>

            <div class="sidebar-footer">
                <button class="btn-report">Baixar Relatório</button>
                <div class="user-profile">
                    <div class="avatar-small">U</div>
                    <div class="user-info">
                        <strong>Usuário</strong>
                        <span>usuario@exemplo.com</span>
                    </div>
                </div>
                <a href="#" class="logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> Sair</a>
            </div>
        </aside>

        <main class="content">
            <header class="main-header">
                <div class="header-titles">
                    <h1>Equipe</h1>
                    <p>Gerencie os membros e permissões da equipe</p>
                </div>
                <button class="btn-add"><i class="fa-solid fa-plus"></i> Adicionar Membro</button>
            </header>

            <section class="filter-bar">
                <div class="search-input">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Buscar por nome ou matrícula...">
                </div>
                <button class="btn-filter">Filtrar <i class="fa-solid fa-chevron-down"></i></button>
            </section>

            <section class="cards-grid">
                <?php foreach ($equipe as $membro): ?>
                    <div class="card">
                        <div class="card-header">
                            <div class="avatar-card"><?php echo $membro['sigla']; ?></div>
                            <div class="card-title-group">
                                <h3><?php echo $membro['nome']; ?></h3>
                            </div>
                            <i class="fa-solid fa-ellipsis-vertical dot-menu"></i>
                        </div>
                        <div class="card-body">
                            <p><i class="fa-regular fa-envelope"></i> <?php echo $membro['email']; ?></p>
                            <hr>
                            <span class="badge <?php echo (strtolower($membro['cargo']) == 'gerente') ? 'gerente' : 'membro'; ?>">
                                <i class="fa-solid fa-user-gear"></i> <?php echo $membro['cargo']; ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
        </main>
    </div>
</body>
</html>