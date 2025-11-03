const LabForm = () => {
  return (
    <div className="w-full max-w-[1200px] mx-auto p-4 bg-white">
      <div className="border-2 border-black">
        {/* Header Section */}
        <div className="grid grid-cols-[120px_1fr_200px] border-b-2 border-black">
          {/* Logo Area */}
          <div className="border-r-2 border-black p-2 flex items-center justify-center">
            <div className="text-center">
              <div className="text-[10px] font-bold">Laboratório</div>
              <div className="text-2xl font-bold text-[#c41e3a]">Raça</div>
              <div className="text-[8px]">Analítica</div>
            </div>
          </div>

          {/* Title Area */}
          <div className="border-r-2 border-black p-2 text-center">
            <div className="text-sm font-bold mb-1">FORMULÁRIO DE REGISTRO</div>
            <div className="text-base font-bold">PLANILHA DE EXAMES - SNP</div>
          </div>

          {/* Quality Info */}
          <div className="p-2 text-[9px]">
            <div className="text-center font-bold mb-1">GARANTIA DE QUALIDADE</div>
            <div>Código: FR081</div>
            <div>Página 1 de 2</div>
            <div>Revisão: 07/02/2025 Versão: 1.1</div>
          </div>
        </div>

        {/* Sample Grid */}
        <div className="border-b-2 border-black">
          <div className="grid grid-cols-[40px_repeat(12,1fr)] text-[10px]">
            {/* Column Headers */}
            <div className="col-span-5 border-r border-b border-black p-1 text-center font-bold">
              Nº DA CORRIDA:
            </div>
            <div className="col-span-4 border-r border-b border-black p-1 text-center font-bold">
              DATA: / /
            </div>
            <div className="col-span-4 border-b border-black p-1"></div>

            {/* Header Row */}
            <div className="border-r border-b border-black p-1"></div>
            {[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12].map((num) => (
              <div key={num} className="border-r border-b border-black p-1 text-center font-bold">
                {num}
              </div>
            ))}

            {/* Rows A-H */}
            {['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'].map((letter) => (
              <>
                <div key={letter} className="border-r border-b border-black p-1 text-center font-bold">
                  {letter}
                </div>
                {[...Array(12)].map((_, i) => (
                  <div key={i} className="border-r border-b border-black p-1 min-h-[25px]"></div>
                ))}
              </>
            ))}
          </div>
        </div>

        {/* Bottom Sections */}
        <div className="grid grid-cols-4 text-[8px]">
          {/* TRIAGEM Section */}
          <div className="border-r-2 border-black p-2">
            <div className="font-bold text-center mb-2 text-[9px]">TRIAGEM</div>
            <div className="space-y-1">
              <div><span className="font-bold">Data:</span></div>
              <div><span className="font-bold">Horário:</span></div>
              <div><span className="font-bold">Observações:</span></div>
            </div>
          </div>

          {/* EXTRAÇÃO PELO Section */}
          <div className="border-r-2 border-black p-2">
            <div className="font-bold text-center mb-2 text-[9px]">EXTRAÇÃO PELO</div>
            <div className="space-y-0.5">
              <div><span className="font-bold">Data:</span></div>
              <div><span className="font-bold">Horário:</span></div>
              <div><span className="font-bold">Preparo do Tampão de Extração</span></div>
              <div>Data de preparo:</div>
              <div>Validade:</div>
              <div className="font-bold">Reagentes usados no preparo:</div>
              <div>Tampão 10X Lote: Val.:</div>
              <div>Tween 20 Lote: Val.:</div>
              <div>Etanol 100% Lote: Val.:</div>
              <div>Etanol 70% Lote: Val.:</div>
              <div>Água UltraP. Lote: Val.:</div>
              <div className="font-bold">Proteinase K 10 mg/mL</div>
              <div>Lote: Validade:</div>
              <div className="font-bold">Equipamentos utilizados</div>
              <div>Termobloco:</div>
              <div>Centrífuga:</div>
              <div>Vórtex:</div>
              <div>Micropipetas:</div>
              <div>Resp.:</div>
            </div>
          </div>

          {/* EXTRAÇÃO SANGUE Section */}
          <div className="border-r-2 border-black p-2">
            <div className="font-bold text-center mb-2 text-[9px]">EXTRAÇÃO: SANGUE (↓ SÊMEN ( )</div>
            <div className="space-y-0.5">
              <div><span className="font-bold">Data:</span></div>
              <div><span className="font-bold">Horário:</span></div>
              <div><span className="font-bold">Tampão PBS</span></div>
              <div>Lote: Val.:</div>
              <div><span className="font-bold">Tampão de Extração</span></div>
              <div>Lote: Val.:</div>
              <div>Tampão de Lise Celular (TLC) Lote: Val.:</div>
              <div>Tampão de Lise Nuclear (TLN) Lote:</div>
              <div>NaCl 5M Lote: Val.:</div>
              <div>Digoxigenol (FLC) 10 mg/mL Lote: Val.:</div>
              <div>Etanol 100% Lote: Val.:</div>
              <div>Etanol 70% Lote: Val.:</div>
              <div>Água Ultrapura Lote: Val.:</div>
              <div className="font-bold">Proteinase K 10 mg/mL</div>
              <div>Lote: Validade:</div>
              <div className="font-bold">Equipamentos utilizados</div>
              <div>Termobloco:</div>
              <div>Centrífuga:</div>
              <div>Vórtex:</div>
              <div>Micropipetas:</div>
              <div>Resp.:</div>
            </div>
          </div>

          {/* QUANTIFICAÇÃO DNA Section */}
          <div className="p-2">
            <div className="font-bold text-center mb-2 text-[9px]">QUANTIFICAÇÃO DNA</div>
            <div className="space-y-0.5">
              <div><span className="font-bold">Data:</span></div>
              <div><span className="font-bold">Horário:</span></div>
              <div><span className="font-bold">Kit dsDNA HS Assay</span></div>
              <div>Lote: Val.:</div>
              <div className="font-bold">Equipamentos utilizados</div>
              <div>Fluorímetro:</div>
              <div>Centrífuga:</div>
              <div>Vórtex:</div>
              <div>Micropipetas:</div>
              <div>Observações:</div>
              <div className="mt-8">Resp.:</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default LabForm;
