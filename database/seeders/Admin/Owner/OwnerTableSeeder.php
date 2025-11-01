<?php

namespace Database\Seeders\Admin\Owner;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OwnerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Iniciando importação dos proprietários...');
        
        // Desabilitar verificação de chaves estrangeiras temporariamente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Ler e executar o arquivo SQL
        $sqlFile = database_path('owners.sql');
        
        if (!file_exists($sqlFile)) {
            $this->command->error('Arquivo owners.sql não encontrado em: ' . $sqlFile);
            return;
        }
        
        $this->command->info('Lendo arquivo owners.sql...');
        
        // Ler o conteúdo do arquivo
        $sql = file_get_contents($sqlFile);
        
        // Substituir o nome do banco de dados para usar o atual
        $currentDatabase = env('DB_DATABASE');
        $sql = str_replace('`db-dna`.owners', "`{$currentDatabase}`.owners", $sql);
        
        // Adicionar o campo registration aos INSERTs
        $sql = str_replace(
            'INSERT INTO `' . $currentDatabase . '`.owners (user_id,',
            'INSERT INTO `' . $currentDatabase . '`.owners (registration,user_id,',
            $sql
        );
        
        // Dividir em statements individuais
        $statements = explode('INSERT INTO', $sql);
        $totalStatements = count($statements) - 1; // -1 porque o primeiro elemento é vazio
        $processedStatements = 0;
        $errors = 0;
        $registrationCounter = 1000000000; // Começar com um número grande
        
        $this->command->info("Processando {$totalStatements} statements...");
        
        foreach ($statements as $index => $statement) {
            $statement = trim($statement);
            
            if (!empty($statement) && !str_starts_with($statement, '`')) {
                $statement = 'INSERT INTO ' . $statement;
                
                // Adicionar valor para registration no início dos VALUES
                $statement = preg_replace(
                    '/VALUES\s*\(\s*/',
                    'VALUES (' . $registrationCounter . ',',
                    $statement
                );
                
                // Remover ponto e vírgula no final se existir
                $statement = rtrim($statement, ';');
                
                try {
                    DB::unprepared($statement);
                    $processedStatements++;
                    $registrationCounter++; // Incrementar para o próximo registro
                    
                    // Mostrar progresso a cada 50 statements
                    if ($processedStatements % 50 === 0) {
                        $this->command->info("Processados: {$processedStatements}/{$totalStatements}");
                    }
                    
                } catch (\Exception $e) {
                    $errors++;
                    Log::warning('Erro ao executar statement de owners: ' . $e->getMessage());
                    
                    // Mostrar apenas os primeiros 5 erros para não poluir o console
                    if ($errors <= 5) {
                        $this->command->warn("Erro no statement {$index}: " . $e->getMessage());
                    }
                }
            }
        }
        
        // Reabilitar verificação de chaves estrangeiras
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info("Importação de proprietários concluída!");
        $this->command->info("Statements processados com sucesso: {$processedStatements}");
        
        if ($errors > 0) {
            $this->command->warn("Total de erros: {$errors}");
            $this->command->info("Verifique os logs para mais detalhes dos erros.");
        }
        
        // Mostrar estatísticas finais
        $totalOwners = DB::table('owners')->count();
        $this->command->info("Total de proprietários na base: {$totalOwners}");
    }
}
