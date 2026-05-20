<!-- MODAL BAIXAR RELATÓRIO -->
<div id="modalRelatorio" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-[60] p-4">
    <div class="bg-white rounded-[2rem] w-full max-w-md shadow-2xl p-8 border border-slate-100">
        <div class="flex items-center gap-3 mb-6">
            <div class="bg-teal-100 p-2 rounded-lg text-teal-600">
                <i class="fas fa-file-export text-xl"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800 tracking-tighter">Exportar Relatório</h3>
        </div>

        <div class="space-y-6">
            <!-- SELEÇÃO DE PERÍODO (CRUCIAL) -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">Mês</label>
                    <select id="export_mes" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-slate-700 font-bold outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="01">Janeiro</option>
                        <option value="02">Fevereiro</option>
                        <option value="03">Março</option>
                        <option value="04">Abril</option>
                        <option value="05" selected>Maio</option>
                        <option value="06">Junho</option>
                        <option value="07">Julho</option>
                        <option value="08">Agosto</option>
                        <option value="09">Setembro</option>
                        <option value="10">Outubro</option>
                        <option value="11">Novembro</option>
                        <option value="12">Dezembro</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">Ano</label>
                    <select id="export_ano" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-slate-700 font-bold outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026" selected>2026</option>
                    </select>
                </div>
            </div>

            <!-- OPÇÕES DE FILTRO -->
            <div>
                <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">Tipo de Registro</label>
                <select id="export_tipo" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-slate-700 font-bold outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="ambos">Entradas e Saídas</option>
                    <option value="receita">Apenas Receitas</option>
                    <option value="despesa">Apenas Despesas</option>
                </select>
            </div>

            <!-- FORMATO -->
            <div>
                <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">Formato de Saída</label>
                <div class="flex gap-4">
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="formato" value="pdf" checked class="hidden peer">
                        <div class="text-center p-3 rounded-xl border-2 border-slate-100 peer-checked:border-teal-500 peer-checked:bg-teal-50 text-slate-500 peer-checked:text-teal-600 font-bold transition">
                            <i class="fas fa-file-pdf mb-1 block"></i> PDF
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="formato" value="csv" class="hidden peer">
                        <div class="text-center p-3 rounded-xl border-2 border-slate-100 peer-checked:border-teal-500 peer-checked:bg-teal-50 text-slate-500 peer-checked:text-teal-600 font-bold transition">
                            <i class="fas fa-file-csv mb-1 block"></i> Excel/CSV
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="toggleModal('modalRelatorio')" class="flex-1 py-4 text-slate-400 font-bold hover:text-slate-600 transition">Fechar</button>
                <button type="button" onclick="gerarRelatorio()" class="flex-[2] py-4 bg-[#0f172a] text-[#2dd4bf] font-black rounded-2xl shadow-xl hover:scale-105 transition active:scale-95">
                    GERAR AGORA
                </button>
            </div>
        </div>
    </div>
</div>