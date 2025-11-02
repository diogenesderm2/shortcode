import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { Eye, Beaker, FileText, History, MessageSquare, Send, GitCompare, Building2, CreditCard } from "lucide-react";

interface Sample {
  number: string;
  internalCode: string;
  sampleType: string;
  type: string;
  entryDate: string;
  owner: string;
  ownerCode: string;
  billing: string;
  price: string;
  standard: boolean;
  ct: boolean;
  released: boolean;
  urgency: string;
  paid: boolean;
  result: string;
  worksheet: string;
}

interface SampleTableProps {
  samples: Sample[];
  onCompare: () => void;
}

export const SampleTable = ({ samples, onCompare }: SampleTableProps) => {
  return (
    <div className="space-y-6">
      {samples.map((sample, index) => (
        <div key={index} className="card-modern overflow-hidden">
          {/* Header com gradiente */}
          <div className="relative px-8 py-6 bg-gradient-to-br from-primary/10 via-primary/5 to-transparent border-b border-border/50">
            <div className="flex items-start justify-between">
              <div className="flex gap-4">
                <div className="flex items-center justify-center w-12 h-12 rounded-xl bg-primary/20 text-primary">
                  <Building2 className="w-6 h-6" />
                </div>
                <div>
                  <p className="text-xs text-muted-foreground mb-1">Proprietário</p>
                  <p className="font-bold text-lg text-foreground">{sample.owner}</p>
                  <p className="text-sm text-muted-foreground mt-0.5">
                    Código: <span className="font-medium">{sample.ownerCode}</span>
                  </p>
                </div>
              </div>
              <div className="flex gap-4 items-start">
                <div className="flex items-center justify-center w-12 h-12 rounded-xl bg-success/20 text-success">
                  <CreditCard className="w-6 h-6" />
                </div>
                <div className="text-right">
                  <p className="text-xs text-muted-foreground mb-1">Faturamento</p>
                  <p className="font-bold text-foreground">{sample.billing}</p>
                  <p className="text-lg font-bold text-success mt-1">{sample.price}</p>
                </div>
              </div>
            </div>
          </div>

          <div className="p-8">
            {/* Informações principais */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
              <div className="space-y-1">
                <p className="text-xs font-medium text-muted-foreground uppercase tracking-wider">Nº Amostra</p>
                <p className="text-lg font-bold text-primary">{sample.number}</p>
              </div>
              <div className="space-y-1">
                <p className="text-xs font-medium text-muted-foreground uppercase tracking-wider">Código Interno</p>
                <p className="text-lg font-semibold text-foreground">{sample.internalCode}</p>
              </div>
              <div className="space-y-1">
                <p className="text-xs font-medium text-muted-foreground uppercase tracking-wider">Tipo Amostra</p>
                <Badge className="badge-modern bg-accent/10 text-accent hover:bg-accent/20 text-sm px-3 py-1">
                  {sample.sampleType}
                </Badge>
              </div>
              <div className="space-y-1">
                <p className="text-xs font-medium text-muted-foreground uppercase tracking-wider">Data de Entrada</p>
                <p className="text-lg font-semibold text-foreground">{sample.entryDate}</p>
              </div>
            </div>

            {/* Tipo e status */}
            <div className="bg-gradient-to-br from-muted/40 to-muted/20 rounded-2xl p-6 mb-8 border border-border/30">
              <div className="flex items-center gap-2 mb-5">
                <div className="w-1 h-6 bg-primary rounded-full"></div>
                <p className="text-sm font-bold text-foreground uppercase tracking-wide">{sample.type}</p>
              </div>
              
              <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
                <div className="space-y-2">
                  <p className="text-xs font-medium text-muted-foreground">Padrão</p>
                  <Badge 
                    className={`badge-modern ${sample.standard 
                      ? 'bg-success/20 text-success hover:bg-success/30' 
                      : 'bg-muted text-muted-foreground hover:bg-muted/80'
                    }`}
                  >
                    {sample.standard ? "Sim" : "Não"}
                  </Badge>
                </div>
                <div className="space-y-2">
                  <p className="text-xs font-medium text-muted-foreground">CT</p>
                  <Badge 
                    className={`badge-modern ${sample.ct 
                      ? 'bg-primary/20 text-primary hover:bg-primary/30' 
                      : 'bg-muted text-muted-foreground hover:bg-muted/80'
                    }`}
                  >
                    {sample.ct ? "Sim" : "Não"}
                  </Badge>
                </div>
                <div className="space-y-2">
                  <p className="text-xs font-medium text-muted-foreground">Liberado</p>
                  <Badge 
                    className={`badge-modern ${sample.released 
                      ? 'bg-success/20 text-success hover:bg-success/30' 
                      : 'bg-muted text-muted-foreground hover:bg-muted/80'
                    }`}
                  >
                    {sample.released ? "Sim" : "Não"}
                  </Badge>
                </div>
                <div className="space-y-2">
                  <p className="text-xs font-medium text-muted-foreground">Urgência</p>
                  <Badge className="badge-modern bg-warning/20 text-warning hover:bg-warning/30">
                    {sample.urgency}
                  </Badge>
                </div>
                <div className="space-y-2">
                  <p className="text-xs font-medium text-muted-foreground">Pago</p>
                  <Badge 
                    className={`badge-modern ${sample.paid 
                      ? 'bg-success/20 text-success hover:bg-success/30' 
                      : 'bg-destructive/20 text-destructive hover:bg-destructive/30'
                    }`}
                  >
                    {sample.paid ? "Sim" : "Não"}
                  </Badge>
                </div>
                <div className="space-y-2">
                  <p className="text-xs font-medium text-muted-foreground">Resultado</p>
                  <p className="text-sm font-semibold text-foreground">{sample.result}</p>
                </div>
                <div className="space-y-2">
                  <p className="text-xs font-medium text-muted-foreground">Planilha</p>
                  <p className="text-sm font-semibold text-foreground">{sample.worksheet}</p>
                </div>
              </div>
            </div>

            {/* Ações */}
            <div className="flex flex-wrap gap-3">
              <Button variant="outline" size="sm" className="rounded-xl hover:border-primary/50 hover:text-primary transition-all">
                <Eye className="w-4 h-4 mr-2" />
                Visualizar
              </Button>
              <Button variant="outline" size="sm" className="rounded-xl hover:border-primary/50 hover:text-primary transition-all">
                <Beaker className="w-4 h-4 mr-2" />
                Testes
              </Button>
              <Button variant="outline" size="sm" className="rounded-xl hover:border-primary/50 hover:text-primary transition-all">
                <FileText className="w-4 h-4 mr-2" />
                Preview
              </Button>
              <Button variant="outline" size="sm" className="rounded-xl hover:border-primary/50 hover:text-primary transition-all">
                <History className="w-4 h-4 mr-2" />
                Logs
              </Button>
              <Button variant="outline" size="sm" className="rounded-xl hover:border-primary/50 hover:text-primary transition-all">
                <MessageSquare className="w-4 h-4 mr-2" />
                Observações
              </Button>
              <Button variant="outline" size="sm" className="rounded-xl hover:border-primary/50 hover:text-primary transition-all">
                <Send className="w-4 h-4 mr-2" />
                Configurar Envio
              </Button>
              <Button 
                className="gradient-primary text-white rounded-xl shadow-md hover:shadow-lg transition-all ml-auto"
                size="sm"
                onClick={onCompare}
              >
                <GitCompare className="w-4 h-4 mr-2" />
                Comparar
              </Button>
            </div>
          </div>
        </div>
      ))}
    </div>
  );
};
