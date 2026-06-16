<?php
// theme_loader.php
?>
<style id="theme-variables">
    :root {
        --color-primary: #2dd4bf;
        --color-primary-hover: #14b8a6;
        --color-sidebar: #0f172a;
        --color-background: #f8fafc;
        --color-card: #ffffff;
        --color-text: #0f172a;
        --color-success: #10b981;
        --color-danger: #f43f5e;
    }
</style>

<script>
    // Aplica o tema salvo no localStorage imediatamente para evitar "flicker"
    (function() {
        try {
            const theme = JSON.parse(localStorage.getItem('theme'));
            if (theme) {
                const root = document.documentElement;
                if (theme.primary) root.style.setProperty('--color-primary', theme.primary);
                if (theme.sidebar) root.style.setProperty('--color-sidebar', theme.sidebar);
                if (theme.background) root.style.setProperty('--color-background', theme.background);
                if (theme.card) root.style.setProperty('--color-card', theme.card);
                if (theme.text) root.style.setProperty('--color-text', theme.text);
            }
        } catch (e) {
            console.warn('Tema não definido, usando padrão.');
        }
    })();
</script>

<script>
    // Configuração do Tailwind com as variáveis CSS
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: 'var(--color-primary)',
                    'primary-hover': 'var(--color-primary-hover)',
                    sidebar: 'var(--color-sidebar)',
                    background: 'var(--color-background)',
                    card: 'var(--color-card)',
                    text: 'var(--color-text)',
                    success: 'var(--color-success)',
                    danger: 'var(--color-danger)'
                }
            }
        }
    }
</script>