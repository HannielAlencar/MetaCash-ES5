        
    
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
            console.error("Erro ao ler localStorage, mantendo padrão original:", erro);
        }
    
        const campos = {
            menu: { picker: document.getElementById('pickerMenu'), txt: document.getElementById('txtMenu') },
            btn1: { picker: document.getElementById('pickerBtn1'), txt: document.getElementById('txtBtn1') },
            destaque: { picker: document.getElementById('pickerDestaque'), txt: document.getElementById('txtDestaque') },
            btn2: { picker: document.getElementById('pickerBtn2'), txt: document.getElementById('txtBtn2') },
            clara: { picker: document.getElementById('pickerClara'), txt: document.getElementById('txtClara') },
            fundo: { picker: document.getElementById('pickerFundo'), txt: document.getElementById('txtFundo') }
        };

        const temaPadrao = {
            menu: '#0F2440',
            btn1: '#204C73',
            destaque: '#24A6B6',
            btn2: '#35C59A',
            clara: '#5DA4C0',
            fundo: '#FDFEFB'
        };

        // Carrega os dados salvos para sincronizar as caixas de texto e pickers
        let temaAtual = temaPadrao;
        try {
            const salvo = localStorage.getItem('metaCashTheme');
            if (salvo) {
                temaAtual = JSON.parse(salvo);
            }
        } catch(e) {
            temaAtual = temaPadrao;
        }

        function inicializarInputs(cores) {
            Object.keys(campos).forEach(chave => {
                if(campos[chave] && cores[chave]) {
                    campos[chave].picker.value = cores[chave];
                    campos[chave].txt.value = cores[chave].toUpperCase();
                }
            });
        }
        inicializarInputs(temaAtual);

        function aplicarTema(cores) {
            const raiz = document.documentElement;
            raiz.style.setProperty('--meta-menu', cores.menu);
            raiz.style.setProperty('--meta-btn1', cores.btn1);
            raiz.style.setProperty('--meta-destaque', cores.destaque);
            raiz.style.setProperty('--meta-btn2', cores.btn2);
            raiz.style.setProperty('--meta-clara', cores.clara);
            raiz.style.setProperty('--meta-fundo', cores.fundo);
        }

        function sincronizarTemaLive() {
            const coresAtuais = {
                menu: campos.menu.picker.value,
                btn1: campos.btn1.picker.value,
                destaque: campos.destaque.picker.value,
                btn2: campos.btn2.picker.value,
                clara: campos.clara.picker.value,
                fundo: campos.fundo.picker.value
            };
            aplicarTema(coresAtuais);
        }

        Object.keys(campos).forEach(chave => {
            const par = campos[chave];
            
            par.picker.addEventListener('input', () => {
                par.txt.value = par.picker.value.toUpperCase();
                sincronizarTemaLive();
            });

            par.txt.maxLength = 7;
            par.txt.addEventListener('input', () => {
                let valor = par.txt.value;
                if(!valor.startsWith('#') && valor.length > 0) valor = '#' + valor;
                if(/^#[0-9A-F]{6}$/i.test(valor)) {
                    par.picker.value = valor;
                    sincronizarTemaLive();
                }
            });
        });

        function aplicarPreset(m, b1, d, b2, c, f) {
            const pacoteCores = { menu: m, btn1: b1, destaque: d, btn2: b2, clara: c, fundo: f };
            inicializarInputs(pacoteCores);
            aplicarTema(pacoteCores);
        }

        document.getElementById('btnSalvarCores').addEventListener('click', () => {
            const coresParaGravar = {
                menu: campos.menu.picker.value,
                btn1: campos.btn1.picker.value,
                destaque: campos.destaque.picker.value,
                btn2: campos.btn2.picker.value,
                clara: campos.clara.picker.value,
                fundo: campos.fundo.picker.value
            };
            localStorage.setItem('metaCashTheme', JSON.stringify(coresParaGravar));
            
            const btn = document.getElementById('btnSalvarCores');
            const conteudoOriginal = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Configurações Salvas!';
            btn.classList.add('bg-green-600');
            
            setTimeout(() => {
                btn.innerHTML = conteudoOriginal;
                btn.classList.remove('bg-green-600');
            }, 2500);
        });

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
    