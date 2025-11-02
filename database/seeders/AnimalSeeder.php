<?php

namespace Database\Seeders;

use App\Models\Admin\Animal\Animal;
use App\Models\Admin\Owner\Owner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AnimalSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Iniciando importação dos animais...');
        
        // Verificar se o arquivo animals.sql existe
        $sqlFile = database_path('animals.sql');
        
        if (file_exists($sqlFile)) {
            $this->importFromSqlFile($sqlFile);
        } else {
            $this->command->info('Arquivo animals.sql não encontrado. Criando animais de teste...');
            $this->createTestAnimals();
        }
    }

    private function importFromSqlFile($sqlFile)
    {
        // Desabilitar verificação de chaves estrangeiras temporariamente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $this->command->info('Lendo arquivo animals.sql...');
        
        // Ler o conteúdo do arquivo
        $sql = file_get_contents($sqlFile);
        
        // Substituir o nome do banco de dados para usar o atual
        $currentDatabase = config('database.connections.mysql.database');
        $sql = str_replace('`db-dna`.animals', "`{$currentDatabase}`.animals", $sql);
        
        // Dividir em statements individuais
        $statements = explode('INSERT INTO', $sql);
        $totalStatements = count($statements) - 1; // -1 porque o primeiro elemento é vazio
        $processedStatements = 0;
        $errors = 0;
        
        $this->command->info("Processando {$totalStatements} statements...");
        
        foreach ($statements as $index => $statement) {
            $statement = trim($statement);
            
            if (!empty($statement) && !str_starts_with($statement, '`')) {
                $statement = 'INSERT INTO ' . $statement;
                
                // Remover ponto e vírgula no final se existir
                $statement = rtrim($statement, ';');
                
                try {
                    DB::unprepared($statement);
                    $processedStatements++;
                    
                    // Mostrar progresso a cada 100 statements
                    if ($processedStatements % 100 === 0) {
                        $this->command->info("Processados: {$processedStatements}/{$totalStatements}");
                    }
                    
                } catch (\Exception $e) {
                    $errors++;
                    Log::warning('Erro ao executar statement: ' . $e->getMessage());
                    
                    // Mostrar apenas os primeiros 5 erros para não poluir o console
                    if ($errors <= 5) {
                        $this->command->warn("Erro no statement {$index}: " . $e->getMessage());
                    }
                }
            }
        }
        
        // Reabilitar verificação de chaves estrangeiras
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info("Importação concluída!");
        $this->command->info("Statements processados com sucesso: {$processedStatements}");
        
        if ($errors > 0) {
            $this->command->warn("Total de erros: {$errors}");
            $this->command->info("Verifique os logs para mais detalhes dos erros.");
        }
    }

    private function createTestAnimals()
    {
        // Verificar se existem proprietários
        $ownersCount = Owner::count();
        if ($ownersCount === 0) {
            $this->command->warn('Nenhum proprietário encontrado. Criando alguns proprietários de teste...');
            Owner::factory(5)->create();
        }

        // Criar 20 animais de teste
        $this->command->info('Criando 20 animais de teste...');
        
        $owners = Owner::all();
        
        for ($i = 1; $i <= 20; $i++) {
            Animal::create([
                'name' => "Animal Teste {$i}",
                'register' => "RG" . str_pad($i, 6, '0', STR_PAD_LEFT),
                'birth' => now()->subYears(rand(1, 10))->subDays(rand(1, 365)),
                'genre' => rand(1, 2), // 1 = Macho, 2 = Fêmea
                'animal_type' => 1, // Bovino
                'breed_id' => rand(1, 68), // Raças bovinas
                'owner_id' => $owners->random()->id,
                'protocol' => 'PROT' . str_pad($i, 4, '0', STR_PAD_LEFT),
            ]);
        }

        // Criar alguns animais com RGs duplicados para testar a funcionalidade de seleção
        $this->command->info('Criando animais com RGs duplicados para teste...');
        
        // Criar 3 animais com o mesmo RG "TESTE001"
        for ($j = 1; $j <= 3; $j++) {
            Animal::create([
                'name' => "Animal Duplicado {$j}",
                'register' => "TESTE001",
                'birth' => now()->subYears(rand(2, 8))->subDays(rand(1, 365)),
                'genre' => $j <= 2 ? 1 : 2, // 2 machos e 1 fêmea
                'animal_type' => 1, // Bovino
                'breed_id' => rand(1, 68), // Raças bovinas
                'owner_id' => $owners->random()->id,
                'protocol' => 'DUP' . str_pad($j, 3, '0', STR_PAD_LEFT),
            ]);
        }

        // Criar 2 animais com o mesmo RG "TESTE002"
        for ($k = 1; $k <= 2; $k++) {
            Animal::create([
                'name' => "Animal Duplicado B{$k}",
                'register' => "TESTE002",
                'birth' => now()->subYears(rand(1, 5))->subDays(rand(1, 365)),
                'genre' => $k === 1 ? 2 : 1, // 1 fêmea e 1 macho
                'animal_type' => 1, // Bovino
                'breed_id' => rand(1, 68), // Raças bovinas
                'owner_id' => $owners->random()->id,
                'protocol' => 'DUP2' . str_pad($k, 2, '0', STR_PAD_LEFT),
            ]);
        }

        // Criar animais com nomes similares para testar autocomplete
        $this->command->info('Criando animais com nomes similares para teste de autocomplete...');
        
        $similarNames = [
            'Bella', 'Bela', 'Belinha', 'Beleza',
            'Thor', 'Touro', 'Tornado', 'Trovão',
            'Luna', 'Luana', 'Lulu', 'Luz',
            'Max', 'Maxi', 'Maximus', 'Máximo'
        ];

        foreach ($similarNames as $index => $name) {
            Animal::create([
                'name' => $name,
                'register' => "AUTO" . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'birth' => now()->subYears(rand(1, 8))->subDays(rand(1, 365)),
                'genre' => rand(1, 2), // Aleatório
                'animal_type' => 1, // Bovino
                'breed_id' => rand(1, 68), // Raças bovinas
                'owner_id' => $owners->random()->id,
                'protocol' => 'AUTO' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
            ]);
        }

        // Criar animais específicos para testar pai e mãe
        $this->command->info('Criando animais específicos para teste de pai e mãe...');
        
        // Animais machos para teste de pai
        $fatherNames = [
            'Zeus', 'Zeus Forte', 'Zeus Bravo',
            'Apolo', 'Apolo Real', 'Apolo Grande',
            'Hércules', 'Hércules Forte', 'Hércules Bravo'
        ];

        foreach ($fatherNames as $index => $name) {
            Animal::create([
                'name' => $name,
                'register' => "PAI" . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'birth' => now()->subYears(rand(3, 10))->subDays(rand(1, 365)),
                'genre' => 1, // Macho
                'animal_type' => 1, // Bovino
                'breed_id' => rand(1, 68), // Raças bovinas
                'owner_id' => $owners->random()->id,
                'protocol' => 'PAI' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
            ]);
        }

        // Animais fêmeas para teste de mãe
        $motherNames = [
            'Afrodite', 'Afrodite Bela', 'Afrodite Linda',
            'Atena', 'Atena Sábia', 'Atena Grande',
            'Hera', 'Hera Rainha', 'Hera Majestosa'
        ];

        foreach ($motherNames as $index => $name) {
            Animal::create([
                'name' => $name,
                'register' => "MAE" . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'birth' => now()->subYears(rand(2, 8))->subDays(rand(1, 365)),
                'genre' => 2, // Fêmea
                'animal_type' => 1, // Bovino
                'breed_id' => rand(1, 68), // Raças bovinas
                'owner_id' => $owners->random()->id,
                'protocol' => 'MAE' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
            ]);
        }
        
        $this->command->info('Animais de teste criados com sucesso!');
        
        // Mostrar estatísticas finais
        $totalAnimals = DB::table('animals')->count();
        $this->command->info("Total de animais na base: {$totalAnimals}");
    }
}
