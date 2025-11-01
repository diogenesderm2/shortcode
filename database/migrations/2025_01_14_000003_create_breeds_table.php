<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('breeds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_type_id')->constrained('animal_types')->onDelete('cascade');
            $table->string('name', 50);
            $table->softDeletes();
            $table->timestamps();
            
            $table->index('animal_type_id');
        });

        // Inserir todas as raças conforme especificado
        $breeds = [
            // Bovinos (animal_type_id = 1)
            [1, 1, 'NELORE'], [2, 1, 'GIR'], [3, 1, 'ABERDEEN ANGUS'], [4, 1, 'BELGIAN BLUE'],
            [5, 1, 'BLONDE D AQUITAINE'], [6, 1, 'BRAVON'], [7, 1, 'CANCHIM'], [8, 1, 'CHIANINA'],
            [9, 1, 'CARACU'], [10, 1, 'LIMOUSIN'], [11, 1, 'MARCHIGIANA'], [12, 1, 'PIEMONTES'],
            [13, 1, 'BONSMARA'], [14, 1, 'CHAROLES MOCHO'], [15, 1, 'PARDO-SUICO CORTE'],
            [16, 1, 'PARDO SUICO LEITE'], [17, 1, 'RED ANGUS'], [18, 1, 'MONTANA'],
            [19, 1, 'SANTA GERTRUDES'], [20, 1, 'SIMBRASIL'], [21, 1, 'SENEPOL'],
            [22, 1, 'CARACU MOCHO'], [23, 1, 'CANCHIM MOCHO'], [24, 1, 'NELORE MOCHO'],
            [25, 1, 'HOLANDES'], [26, 1, 'JERSEY'], [27, 1, 'BRANGUS'], [28, 1, 'GIROLANDO'],
            [29, 1, 'TABAPUA'], [30, 1, 'BRAHMAN'], [31, 1, 'SRD'], [32, 1, 'JERSOLANDO'],
            [33, 1, 'GUZOLANDO'], [34, 1, 'SEM RACA DEFINIDA'], [35, 1, 'SINDI'], [36, 1, 'GUZERA'],
            [37, 1, 'DEVON'], [38, 1, 'CHAROLES'], [39, 1, 'SIMENTAL'], [40, 1, 'HEREFORD'],
            [41, 1, 'INDUBRASIL'], [42, 1, 'BRAFORD'], [43, 1, 'ANGUS'], [44, 1, 'RED ANGUS'],
            [45, 1, 'WAGYU'], [46, 1, 'TROPICANA'], [47, 1, 'MESTICO PARDO SUICO/HPB'],
            [48, 1, 'ARAGUAIA'], [49, 1, 'AQUITANICA'], [50, 1, 'NELORE X AQUITANICA'],
            [51, 1, 'NELORE x ANGUS'], [52, 1, 'PARDO-SUICO'], [53, 1, 'SIMANGUS'],
            [54, 1, 'DROUGHTMASTER'], [55, 1, 'CURRALEIRO'], [56, 1, 'GALLOWAY'],
            [57, 1, 'GIRSEY'], [58, 1, 'LINCOLN'], [59, 1, 'MERTICO'], [60, 1, 'NINI BOVINO'],
            [61, 1, 'MONTBELIRAD'], [62, 1, 'POLLED HEREFORD'], [63, 1, 'PURUNA'],
            [64, 1, 'RED POLL'], [65, 1, 'SHORTON'], [66, 1, 'ULTRA BLACK'], [67, 1, 'VIOLEIRA'],
            [68, 1, 'SINDOLANDO'],
            
            // Equinos (animal_type_id = 2)
            [69, 2, 'Sem Raça'], [70, 2, 'QUARTO DE MILHA'], [71, 2, 'PAINT HORSE'],
            [72, 2, 'CRIOULO'], [73, 2, 'PANTANEIRO'], [74, 2, 'MANGALARGA MACHADOR'],
            [75, 2, 'ANDALUZ'], [76, 2, 'PURO SANGUE ARABE'], [77, 2, 'BERBERE'],
            [78, 2, 'BRETAO'], [79, 2, 'BRUMBY'], [80, 2, 'CABARDINO'], [81, 2, 'CLYDESDALE'],
            [83, 2, 'DANISH WARMBLOOD'], [84, 2, 'PURO SANGUE INGLES']
        ];

        foreach ($breeds as $breed) {
            DB::table('breeds')->insert([
                'id' => $breed[0],
                'animal_type_id' => $breed[1],
                'name' => $breed[2],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breeds');
    }
};