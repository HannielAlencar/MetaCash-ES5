<?php
/**
 * BACK-END: data.php
 * Este arquivo centraliza todas as informações da Landing Page.
 * No futuro, você pode substituir esses arrays por consultas ao Banco de Dados.
 */

// 1. Configurações Gerais
$config = [
    "nome_app" => "MetaCash",
    "ano_atual" => date("Y"),
    "contato_email" => "suporte@metacash.com"
];

// 2. Funcionalidades Principais (Cards Brancos)
$features = [
    [
        "icon"  => "📈",
        "title" => "Dashboard Inteligente",
        "desc"  => "Visualize todas as métricas importantes com gráficos intuitivos e personalizáveis."
    ],
    [
        "icon"  => "👥",
        "title" => "Gestão de Equipe",
        "desc"  => "Adicione membros e defina permissões específicas para cada colaborador da sua empresa."
    ],
    [
        "icon"  => "💳",
        "title" => "Gestão de Transações",
        "desc"  => "Controle completo de receitas e despesas, contas a pagar e receber com automação."
    ]
];

// 3. Seção de Customização (Seção Escura / Edit Mode)
$custom_features = [
    [
        "icon"  => "📐", 
        "title" => "Layouts Flexíveis", 
        "desc"  => "Reorganize módulos, dashboards e relatórios da forma que melhor atende seu fluxo de trabalho.",
        "active" => false
    ],
    [
        "icon"  => "🗄️", 
        "title" => "Dados Sob Controle", 
        "desc"  => "Configure campos personalizados, categorias e métricas específicas para seu setor de atuação.",
        "active" => true // Define qual card aparece com as bordas de "seleção" no front-end
    ]
];

// 4. Dados da Chamada para Ação (CTA) Final
$cta_data = [
    "titulo"    => "Pronto para transformar sua gestão financeira?",
    "subtitulo" => "Junte-se às empresas que já otimizaram suas finanças com o MetaCash. Customize, adapte e cresça com a plataforma mais flexível do mercado."
];

// 5. Lógica de Sessão (Simulação)
$is_logged_in = false;
?>