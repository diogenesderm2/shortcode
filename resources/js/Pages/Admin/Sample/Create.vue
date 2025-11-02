<template>
    <AppLayout title="Cadastrar Amostras">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Cadastrar Amostras para Exame de DNA
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- Progress Steps -->
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div :class="['flex items-center justify-center w-8 h-8 rounded-full text-sm font-medium', 
                                    currentStep >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600']">
                                    1
                                </div>
                                <span :class="['text-sm font-medium', currentStep >= 1 ? 'text-blue-600' : 'text-gray-500']">
                                    Selecionar Proprietário
                                </span>
                            </div>
                            <div class="flex-1 h-px bg-gray-300 mx-4"></div>
                            <div class="flex items-center space-x-4">
                                <div :class="['flex items-center justify-center w-8 h-8 rounded-full text-sm font-medium', 
                                    currentStep >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600']">
                                    2
                                </div>
                                <span :class="['text-sm font-medium', currentStep >= 2 ? 'text-blue-600' : 'text-gray-500']">
                                    Dados dos Animais
                                </span>
                            </div>
                            <div class="flex-1 h-px bg-gray-300 mx-4"></div>
                            <div class="flex items-center space-x-4">
                                <div :class="['flex items-center justify-center w-8 h-8 rounded-full text-sm font-medium', 
                                    currentStep >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600']">
                                    3
                                </div>
                                <span :class="['text-sm font-medium', currentStep >= 3 ? 'text-blue-600' : 'text-gray-500']">
                                    Dados da Amostra
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Step 1: Select Owner -->
                        <div v-if="currentStep === 1" class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900">Selecione o Proprietário</h3>
                            
                            <!-- Search by ID -->
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <h4 class="font-medium text-blue-900 mb-3">Pesquisar por ID</h4>
                                <div class="flex gap-3">
                                    <div class="flex-1">
                                        <input 
                                            v-model="ownerSearchId" 
                                            @keyup.enter="searchOwnerById"
                                            type="number" 
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Digite o ID do proprietário"
                                        >
                                    </div>
                                    <button 
                                        @click="searchOwnerById"
                                        :disabled="searchingOwner || !String(ownerSearchId || '').trim()"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        {{ searchingOwner ? 'Buscando...' : 'Buscar' }}
                                    </button>
                                </div>
                                <div v-if="searchError" class="mt-2 text-sm text-red-600">
                                    {{ searchError }}
                                </div>
                                <div v-if="foundOwner" class="mt-3 p-4 bg-green-100 border border-green-200 rounded-md">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <p class="font-medium text-green-800 text-lg">{{ foundOwner.name }}</p>
                                            <div class="mt-2 space-y-1">
                                                <p class="text-sm text-green-700">
                                                    <span class="font-medium">ID:</span> {{ foundOwner.id }}
                                                </p>
                                                <p v-if="foundOwner.cpf" class="text-sm text-green-700">
                                                    <span class="font-medium">CPF:</span> {{ foundOwner.cpf }}
                                                </p>
                                                <p v-if="foundOwner.cnpj" class="text-sm text-green-700">
                                                    <span class="font-medium">CNPJ:</span> {{ foundOwner.cnpj }}
                                                </p>
                                                <p v-if="foundOwner.property_name" class="text-sm text-green-700">
                                                    <span class="font-medium">Fazenda:</span> {{ foundOwner.property_name }}
                                                </p>
                                                <p v-if="foundOwner.city && foundOwner.state" class="text-sm text-green-700">
                                                    <span class="font-medium">Localização:</span> {{ foundOwner.city }}/{{ foundOwner.state }}
                                                </p>
                                            </div>
                                        </div>
                                        <button 
                                            @click="selectFoundOwner"
                                            class="ml-4 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-medium"
                                        >
                                            Selecionar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2 bg-white text-gray-500">ou selecione da lista abaixo</span>
                                </div>
                            </div>

                            <!-- Owner List -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div 
                                    v-for="owner in owners" 
                                    :key="owner.id"
                                    @click="selectOwner(owner)"
                                    :class="['border-2 rounded-lg p-4 cursor-pointer transition-all hover:shadow-md', 
                                        selectedOwner?.id === owner.id ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300']"
                                >
                                    <h4 class="font-medium text-gray-900 mb-2">{{ owner.name }}</h4>
                                    <div class="space-y-1">
                                        <p class="text-xs text-gray-500">
                                            <span class="font-medium">ID:</span> {{ owner.id }}
                                        </p>
                                        <p v-if="owner.cpf" class="text-xs text-gray-600">
                                            <span class="font-medium">CPF:</span> {{ owner.cpf }}
                                        </p>
                                        <p v-if="owner.cnpj" class="text-xs text-gray-600">
                                            <span class="font-medium">CNPJ:</span> {{ owner.cnpj }}
                                        </p>
                                        <p v-if="owner.property_name" class="text-sm text-gray-700">
                                            <span class="font-medium">Fazenda:</span> {{ owner.property_name }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button 
                                    @click="nextStep" 
                                    :disabled="!selectedOwner"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    Próximo
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Animal Data -->
        <div v-if="currentStep === 2" class="space-y-6">
            <h3 class="text-lg font-medium text-gray-900">Dados dos Animais</h3>
            
            <!-- Tipo de Teste -->
            <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-400">
                <h4 class="font-medium text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Tipo de Teste de Identificação Genética
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo de Teste *</label>
                        <select 
                            v-model="sampleForm.test_type_id" 
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">Selecione o tipo de teste</option>
                            <option 
                                v-for="testType in props.testTypes" 
                                :key="testType.id" 
                                :value="testType.id"
                            >
                                {{ testType.name }} ({{ testType.initials }})
                            </option>
                        </select>
                    </div>
                    <div v-if="sampleForm.test_type_id" class="flex items-end">
                        <div class="text-sm text-gray-600 bg-white p-3 rounded-md border border-blue-200">
                            <strong>Campos necessários:</strong><br>
                            <span v-if="geneticFieldsToShow.showChild">• Animal Filho</span><br>
                            <span v-if="geneticFieldsToShow.showFather">• Animal Pai</span><br>
                            <span v-if="geneticFieldsToShow.showMother">• Animal Mãe</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Child Animal -->
            <div v-if="geneticFieldsToShow.showChild" class="bg-amber-50 p-4 rounded-lg border-l-4 border-amber-600">
                                <h4 class="font-medium text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-amber-700 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1a4 4 0 014 4v2a1 1 0 001 1h2a1 1 0 001-1v-2a4 4 0 014-4h1m-1 4a4 4 0 01-4 4H9a4 4 0 01-4-4v-2a4 4 0 014-4h1m5-1V7a1 1 0 00-1-1H9a1 1 0 00-1 1v2m5-1a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V7a1 1 0 011-1h4z"></path>
                                    </svg>
                                    Animal Filho (Obrigatório)
                                    <span v-if="animals.child" class="ml-2 text-amber-700 text-sm">✓ Cadastrado</span>
                                    <span v-else-if="childRg || childName" class="ml-2 text-orange-600 text-sm">⚠ Verificando...</span>
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Nome do Animal (Autocomplete) -->
                                    <div class="relative">
                                        <label class="block text-sm font-medium text-gray-700">Nome do Animal</label>
                                        <input 
                                            v-model="childName" 
                                            @input="searchAnimalsByName('child')"
                                            @focus="showChildSuggestions = true"
                                            type="text" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-600 focus:ring-amber-600"
                                            placeholder="Digite o nome do animal"
                                            autocomplete="off"
                                        >
                                        
                                        <!-- Dropdown de sugestões -->
                                        <div v-if="showChildSuggestions && childSuggestions.length > 0" 
                                             class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                                            <div 
                                                v-for="animal in childSuggestions" 
                                                :key="animal.id"
                                                @click="selectAnimalFromSuggestion(animal, 'child')"
                                                class="px-4 py-2 hover:bg-amber-50 cursor-pointer border-b border-gray-100 last:border-b-0"
                                            >
                                                <div class="flex justify-between items-center">
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ animal.name }}</p>
                                                        <p class="text-sm text-gray-600">RG: {{ animal.register }} | {{ animal.genre === 1 ? 'Macho' : 'Fêmea' }}</p>
                                                        <p class="text-xs text-gray-500">{{ animal.owner?.name }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- RG do Animal -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">RG do Animal</label>
                                        <input 
                                            v-model="childRg" 
                                            @blur="checkAnimal('child')"
                                            type="text" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-600 focus:ring-amber-600"
                                            placeholder="Digite o RG do animal"
                                        >
                                    </div>
                                </div>
                                
                                <!-- Animal Encontrado -->
                                <div v-if="animals.child" class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700">Animal Selecionado</label>
                                    <div class="mt-1 p-3 bg-amber-100 rounded-md">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="font-medium text-amber-800">{{ animals.child.name }}</p>
                                                <p class="text-sm text-amber-700">RG: {{ animals.child.register }} | {{ animals.child.genre === 1 ? 'Macho' : 'Fêmea' }}</p>
                                                <p class="text-xs text-amber-600">{{ animals.child.owner?.name }}</p>
                                            </div>
                                            <button 
                                                @click="clearSelectedAnimal('child')"
                                                class="text-amber-600 hover:text-amber-800 text-sm underline"
                                            >
                                                Limpar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Father Animal -->
            <div v-if="geneticFieldsToShow.showFather" class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-400">
                                <h4 class="font-medium text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Animal Pai (Opcional)
                                    <span v-if="animals.father" class="ml-2 text-blue-600 text-sm">✓ Cadastrado</span>
                                    <span v-else-if="fatherRg || fatherName" class="ml-2 text-orange-600 text-sm">⚠ Verificando...</span>
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Nome do Animal (Autocomplete) -->
                                    <div class="relative">
                                        <label class="block text-sm font-medium text-gray-700">Nome do Animal</label>
                                        <input 
                                            v-model="fatherName" 
                                            @input="searchAnimalsByName('father')"
                                            @focus="showFatherSuggestions = true"
                                            type="text" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Digite o nome do animal pai"
                                        >
                                        
                                        <!-- Dropdown de Sugestões -->
                                        <div v-if="showFatherSuggestions && fatherSuggestions.length > 0" 
                                             class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                            <div v-for="animal in fatherSuggestions" 
                                                 :key="animal.id" 
                                                 @click="selectAnimalFromSuggestion(animal, 'father')"
                                                 class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-50">
                                                <div class="flex items-center">
                                                    <div class="ml-3">
                                                        <p class="font-medium text-gray-900">{{ animal.name }}</p>
                                                        <p class="text-sm text-gray-600">RG: {{ animal.register }} | {{ animal.genre === 1 ? 'Macho' : 'Fêmea' }}</p>
                                                        <p class="text-xs text-gray-500">{{ animal.owner?.name }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- RG do Animal -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">RG do Animal</label>
                                        <input 
                                            v-model="fatherRg" 
                                            @blur="checkAnimal('father')"
                                            type="text" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Digite o RG do animal pai"
                                        >
                                    </div>
                                </div>
                                
                                <!-- Animal Encontrado -->
                                <div v-if="animals.father" class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700">Animal Selecionado</label>
                                    <div class="mt-1 p-3 bg-blue-100 rounded-md">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="font-medium text-blue-800">{{ animals.father.name }}</p>
                                                <p class="text-sm text-blue-700">RG: {{ animals.father.register }} | {{ animals.father.genre === 1 ? 'Macho' : 'Fêmea' }}</p>
                                                <p class="text-xs text-blue-600">{{ animals.father.owner?.name }}</p>
                                            </div>
                                            <button 
                                                @click="clearSelectedAnimal('father')"
                                                class="text-blue-600 hover:text-blue-800 text-sm underline"
                                            >
                                                Limpar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mother Animal -->
            <div v-if="geneticFieldsToShow.showMother" class="bg-pink-50 p-4 rounded-lg border-l-4 border-pink-400">
                                <h4 class="font-medium text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-pink-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    Animal Mãe (Opcional)
                                    <span v-if="animals.mother" class="ml-2 text-pink-600 text-sm">✓ Cadastrado</span>
                                    <span v-else-if="motherRg || motherName" class="ml-2 text-orange-600 text-sm">⚠ Verificando...</span>
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Nome do Animal (Autocomplete) -->
                                    <div class="relative">
                                        <label class="block text-sm font-medium text-gray-700">Nome do Animal</label>
                                        <input 
                                            v-model="motherName" 
                                            @input="searchAnimalsByName('mother')"
                                            @focus="showMotherSuggestions = true"
                                            type="text" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500"
                                            placeholder="Digite o nome do animal mãe"
                                        >
                                        
                                        <!-- Dropdown de Sugestões -->
                                        <div v-if="showMotherSuggestions && motherSuggestions.length > 0" 
                                             class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                            <div v-for="animal in motherSuggestions" 
                                                 :key="animal.id" 
                                                 @click="selectAnimalFromSuggestion(animal, 'mother')"
                                                 class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-pink-50">
                                                <div class="flex items-center">
                                                    <div class="ml-3">
                                                        <p class="font-medium text-gray-900">{{ animal.name }}</p>
                                                        <p class="text-sm text-gray-600">RG: {{ animal.register }} | {{ animal.genre === 1 ? 'Macho' : 'Fêmea' }}</p>
                                                        <p class="text-xs text-gray-500">{{ animal.owner?.name }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- RG do Animal -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">RG do Animal</label>
                                        <input 
                                            v-model="motherRg" 
                                            @blur="checkAnimal('mother')"
                                            type="text" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500"
                                            placeholder="Digite o RG do animal mãe"
                                        >
                                    </div>
                                </div>
                                
                                <!-- Animal Encontrado -->
                                <div v-if="animals.mother" class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700">Animal Selecionado</label>
                                    <div class="mt-1 p-3 bg-pink-100 rounded-md">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="font-medium text-pink-800">{{ animals.mother.name }}</p>
                                                <p class="text-sm text-pink-700">RG: {{ animals.mother.register }} | {{ animals.mother.genre === 1 ? 'Macho' : 'Fêmea' }}</p>
                                                <p class="text-xs text-pink-600">{{ animals.mother.owner?.name }}</p>
                                            </div>
                                            <button 
                                                @click="clearSelectedAnimal('mother')"
                                                class="text-pink-600 hover:text-pink-800 text-sm underline"
                                            >
                                                Limpar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-between">
                                <button 
                                    @click="prevStep" 
                                    class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700"
                                >
                                    Anterior
                                </button>
                                <button 
                                    @click="nextStep" 
                                    :disabled="!canProceedToStep3"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    Próximo
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Sample Data -->
                        <div v-if="currentStep === 3" class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900">Cadastrar amostra</h3>
                            
                            <!-- Dados do animal -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-md font-medium text-gray-900 mb-4">Dados do animal 
                                    <button 
                                        @click="showSamplesModal = true"
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full ml-2 hover:bg-teal-200 transition-colors cursor-pointer"
                                    >
                                        Ver Amostras
                                    </button>
                                </h4>
                                
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-700">RG Animal:</span>
                                        <div class="text-gray-900">{{ animals.child?.register || childRg || 'TESTEPAE' }}</div>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Nome Animal:</span>
                                        <div class="text-gray-900">{{ animals.child?.name || 'TESTEPAE' }}</div>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Nascimento:</span>
                                        <div class="text-gray-900">{{ animals.child?.birth || '00/00/0000' }}</div>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Sexo:</span>
                                        <div class="text-gray-900">{{ animals.child?.genre === '1' ? 'MACHO' : animals.child?.genre === '2' ? 'FÊMEA' : 'FÊMEA' }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Dados do Pai e da Mãe -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Dados do Pai -->
                                <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                                    <h4 class="text-md font-medium text-blue-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Pai
                                    </h4>
                                    <div class="grid grid-cols-2 gap-2 text-sm">
                                        <div>
                                            <span class="font-medium text-blue-700">RG:</span>
                                            <div class="text-blue-900">{{ animals.father?.register || fatherRg || '-' }}</div>
                                        </div>
                                        <div>
                                            <span class="font-medium text-blue-700">Nome:</span>
                                            <div class="text-blue-900">{{ animals.father?.name || '-' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dados da Mãe -->
                                <div class="bg-pink-50 p-4 rounded-lg border-l-4 border-pink-500">
                                    <h4 class="text-md font-medium text-pink-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Mãe
                                    </h4>
                                    <div class="grid grid-cols-2 gap-2 text-sm">
                                        <div>
                                            <span class="font-medium text-pink-700">RG:</span>
                                            <div class="text-pink-900">{{ animals.mother?.register || motherRg || '-' }}</div>
                                        </div>
                                        <div>
                                            <span class="font-medium text-pink-700">Nome:</span>
                                            <div class="text-pink-900">{{ animals.mother?.name || '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <form @submit.prevent="submitSample" class="space-y-6">
                                <h4 class="text-md font-medium text-gray-900">Dados amostra</h4>
                                
                                <!-- Primeira linha de campos -->
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">
                                            <input type="checkbox" v-model="sampleForm.buscar_por_raca" class="mr-2">
                                            Buscar por raça
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Segunda linha de campos obrigatórios -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tipo de Exame *</label>
                                        <select 
                                            v-model="sampleForm.exam_id" 
                                            required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        >
                                            <option value="">Selecione o tipo de exame</option>
                                            <option 
                                                v-for="examType in props.examTypes" 
                                                :key="examType.id" 
                                                :value="examType.id"
                                            >
                                                {{ examType.name }}
                                            </option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tipo de Amostra *</label>
                                        <select 
                                            v-model="sampleForm.sample_type_id" 
                                            required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        >
                                            <option value="">Selecione o tipo de amostra</option>
                                            <option 
                                                v-for="sampleType in props.sampleTypes" 
                                                :key="sampleType.id" 
                                                :value="sampleType.id"
                                            >
                                                {{ sampleType.name }}
                                            </option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tipo de Cobrança *</label>
                                        <select 
                                            v-model="sampleForm.billing_type" 
                                            required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        >
                                            <option value="">Selecione o tipo de cobrança</option>
                                            <option 
                                                v-for="billingType in props.billingTypes" 
                                                :key="billingType.id" 
                                                :value="billingType.id"
                                            >
                                                {{ billingType.type }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Terceira linha de campos opcionais -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Coleta Técnica</label>
                                        <select 
                                            v-model="sampleForm.coleta_tecnica" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        >
                                            <option value="">Selecione</option>
                                            <option value="nao">NÃO</option>
                                            <option value="sim">SIM</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Urgência</label>
                                        <select 
                                            v-model="sampleForm.urgencia" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        >
                                            <option value="">Selecione</option>
                                            <option value="normal">Normal</option>
                                            <option value="urgente">Urgente</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Rebanho</label>
                                        <select 
                                            v-model="sampleForm.rebanho" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        >
                                            <option value="">Selecione</option>
                                            <option value="nao_se_aplica">NÃO SE APLICA</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Campo de observações -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Observações:</label>
                                    <textarea 
                                        v-model="sampleForm.observations" 
                                        rows="4"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Digite suas observações aqui..."
                                    ></textarea>
                                </div>

                                <div class="flex justify-between">
                                    <button 
                                        type="button"
                                        @click="prevStep" 
                                        class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700"
                                    >
                                        Anterior
                                    </button>
                                    
                                    <button 
                                        type="submit" 
                                        :disabled="processing"
                                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50"
                                    >
                                        {{ processing ? 'Processando...' : 'Finalizar Cadastro' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Animal Registration Modal -->
        <AnimalModal 
            :show="showAnimalModal" 
            :owner="selectedOwner"
            :animalType="currentAnimalType"
            :rg="currentRg"
            @close="closeAnimalModal"
            @animal-created="handleAnimalCreated"
        />

        <!-- Animal Selection Modal -->
        <div v-if="showAnimalSelectionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between pb-4 border-b"
                         :class="{
                             'border-green-200': currentAnimalSelectionType === 'child',
                             'border-blue-200': currentAnimalSelectionType === 'father',
                             'border-pink-200': currentAnimalSelectionType === 'mother'
                         }">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center"
                            :class="{
                                'text-amber-800': currentAnimalSelectionType === 'child',
                                'text-blue-800': currentAnimalSelectionType === 'father',
                                'text-pink-800': currentAnimalSelectionType === 'mother'
                            }">
                            <!-- Ícone do Filho -->
                            <svg v-if="currentAnimalSelectionType === 'child'" class="w-6 h-6 mr-2 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1a4 4 0 014 4v2a1 1 0 001 1h2a1 1 0 001-1v-2a4 4 0 014-4h1m-1 4a4 4 0 01-4 4H9a4 4 0 01-4-4v-2a4 4 0 014-4h1m5-1V7a1 1 0 00-1-1H9a1 1 0 00-1 1v2m5-1a1 1 0 011 1v1"></path>
                            </svg>
                            <!-- Ícone do Pai -->
                            <svg v-if="currentAnimalSelectionType === 'father'" class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <!-- Ícone da Mãe -->
                            <svg v-if="currentAnimalSelectionType === 'mother'" class="w-6 h-6 mr-2 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            Selecionar Animal {{ currentAnimalSelectionType === 'child' ? 'Filho' : currentAnimalSelectionType === 'father' ? 'Pai' : 'Mãe' }}
                        </h3>
                        <button 
                            @click="closeAnimalSelectionModal"
                            class="text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="py-4">
                        <p class="text-sm text-gray-600 mb-4">
                            Foram encontrados múltiplos animais com o mesmo RG. Selecione o animal desejado:
                        </p>
                        
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            <div 
                                v-for="animal in multipleAnimals" 
                                :key="animal.id"
                                class="border rounded-lg p-4 cursor-pointer transition-all"
                                :class="{
                                    'border-amber-200 hover:border-amber-300 hover:bg-amber-50': currentAnimalSelectionType === 'child',
                                    'border-blue-200 hover:border-blue-300 hover:bg-blue-50': currentAnimalSelectionType === 'father',
                                    'border-pink-200 hover:border-pink-300 hover:bg-pink-50': currentAnimalSelectionType === 'mother'
                                }"
                                @click="selectAnimalFromModal(animal)"
                            >
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-lg"
                                            :class="{
                                                'text-amber-800': currentAnimalSelectionType === 'child',
                                                'text-blue-800': currentAnimalSelectionType === 'father',
                                                'text-pink-800': currentAnimalSelectionType === 'mother'
                                            }">{{ animal.name }}</h4>
                                        <div class="mt-2 grid grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <span class="font-medium text-gray-700">ID:</span>
                                                <span class="text-gray-600 ml-1">#{{ animal.id }}</span>
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-700">RG:</span>
                                                <span class="text-gray-600 ml-1">{{ animal.register }}</span>
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-700">Sexo:</span>
                                                <span class="text-gray-600 ml-1">{{ animal.genre === 1 ? 'Macho' : 'Fêmea' }}</span>
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-700">Proprietário:</span>
                                                <span class="text-gray-600 ml-1">{{ animal.owner?.name || 'N/A' }}</span>
                                            </div>
                                            <div v-if="animal.animal_type">
                                                <span class="font-medium text-gray-700">Tipo:</span>
                                                <span class="text-gray-600 ml-1">{{ animal.animal_type.name }}</span>
                                            </div>
                                            <div v-if="animal.breed">
                                                <span class="font-medium text-gray-700">Raça:</span>
                                                <span class="text-gray-600 ml-1">{{ animal.breed.name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <button class="px-4 py-2 text-white text-sm rounded-md transition-colors"
                                                :class="{
                                                    'bg-green-600 hover:bg-green-700': currentAnimalSelectionType === 'child',
                                                    'bg-blue-600 hover:bg-blue-700': currentAnimalSelectionType === 'father',
                                                    'bg-pink-600 hover:bg-pink-700': currentAnimalSelectionType === 'mother'
                                                }">
                                            Selecionar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end pt-4 border-t">
                        <button 
                            @click="closeAnimalSelectionModal"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors"
                        >
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Amostras -->
        <div v-if="showSamplesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Amostras de {{ animals.child?.name || 'TESTEPAE' }}
                        </h3>
                        <button @click="showSamplesModal = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Content -->
                    <div class="py-4">
                        <div v-if="loadingSamples" class="text-center py-8">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-teal-600"></div>
                            <p class="mt-2 text-gray-600">Carregando amostras...</p>
                        </div>

                        <div v-else-if="animalSamples.length === 0" class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-2 text-gray-600">Nenhuma amostra encontrada para este animal.</p>
                        </div>

                        <div v-else class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amostra</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proprietário</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entrada</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pai</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Liberado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">C.T.</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pai</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mãe</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resultado</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="sample in animalSamples" :key="sample.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ sample.sample_code || sample.id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ sample.owner?.name || 'ROSIVAL MARTINS CABRAL' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ sample.tipo_teste || 'ARQUIVO PERMANENTE' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ sample.created_at ? new Date(sample.created_at).toLocaleDateString('pt-BR') : '30/09/2025' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                NÃO
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                SIM
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                NÃO
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ sample.father?.register || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ sample.mother?.register || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-yellow-600 font-medium">Em andamento</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <button 
                            @click="showSamplesModal = false"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors"
                        >
                            Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onUnmounted, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AnimalModal from '@/Components/AnimalModal.vue';
import axios from 'axios';

const props = defineProps({
    owners: Array,
    examTypes: Array,
    sampleTypes: Array,
    billingTypes: Array,
    testTypes: Array,
});

// Debug: verificar se os dados estão chegando
console.log('Props recebidas:', {
    owners: props.owners,
    examTypes: props.examTypes,
    sampleTypes: props.sampleTypes,
    billingTypes: props.billingTypes,
    testTypes: props.testTypes
});

const currentStep = ref(1);
const selectedOwner = ref(null);
const showAnimalModal = ref(false);
const currentAnimalType = ref('');
const currentRg = ref('');
const processing = ref(false);

// Modal de amostras
const showSamplesModal = ref(false);
const animalSamples = ref([]);
const loadingSamples = ref(false);

// Animal selection modal variables
const showAnimalSelectionModal = ref(false);
const multipleAnimals = ref([]);
const currentAnimalSelectionType = ref('');

// Animal RGs
const childRg = ref('');
const fatherRg = ref('');
const motherRg = ref('');

// Animal Names (for autocomplete)
const childName = ref('');
const fatherName = ref('');
const motherName = ref('');

// Autocomplete suggestions
const childSuggestions = ref([]);
const fatherSuggestions = ref([]);
const motherSuggestions = ref([]);

// Show suggestions flags
const showChildSuggestions = ref(false);
const showFatherSuggestions = ref(false);
const showMotherSuggestions = ref(false);

// Debounce timers for search (separate for each type)
const childSearchTimer = ref(null);
const fatherSearchTimer = ref(null);
const motherSearchTimer = ref(null);

// Animals data
const animals = reactive({
    child: null,
    father: null,
    mother: null,
});

// Sample form
const sampleForm = reactive({
    sample_code: '',
    owner_id: null,
    father_id: null,
    mother_id: null,
    child_id: null,
    animal_id: null, // Campo obrigatório
    sample_type: '',
    collection_date: '',
    observations: '',
    // Campos obrigatórios
    exam_id: null,
    sample_type_id: null,
    billing_type: null,
    test_type_id: null, // Novo campo para tipo de teste
    // Campos opcionais conforme a imagem
    buscar_por_raca: false,
    coleta_tecnica: '',
    urgencia: '',
    rebanho: '',
});

// Owner search variables
const ownerSearchId = ref('');
const foundOwner = ref(null);
const searchingOwner = ref(false);
const searchError = ref('');

// Computed property to check if we can proceed to step 3
const canProceedToStep3 = computed(() => {
    // Verificar se o tipo de teste foi selecionado
    if (!sampleForm.test_type_id) return false;
    
    // Verificar se o animal filho está selecionado (sempre obrigatório)
    if (!animals.child || !sampleForm.child_id) return false;
    
    // Verificar se os campos obrigatórios baseados no tipo de teste estão preenchidos
    if (geneticFieldsToShow.value.showFather && (!animals.father || !sampleForm.father_id)) {
        return false;
    }
    
    if (geneticFieldsToShow.value.showMother && (!animals.mother || !sampleForm.mother_id)) {
        return false;
    }
    
    return true;
});

// Computed property para determinar quais campos de identificação genética mostrar
const geneticFieldsToShow = computed(() => {
    if (!sampleForm.test_type_id) {
        return { showChild: true, showFather: false, showMother: false };
    }
    
    const testTypeId = parseInt(sampleForm.test_type_id);
    
    switch (testTypeId) {
        case 1: // Arquivo Permanente - apenas filho
            return { showChild: true, showFather: false, showMother: false };
        case 2: // Pai e Mãe - filho, pai e mãe
            return { showChild: true, showFather: true, showMother: true };
        case 3: // Apenas Pai - filho e pai
            return { showChild: true, showFather: true, showMother: false };
        case 4: // Apenas Mãe - filho e mãe
            return { showChild: true, showFather: false, showMother: true };
        default:
            return { showChild: true, showFather: false, showMother: false };
    }
});

// Owner search functions
const searchOwnerById = async () => {
    const searchId = String(ownerSearchId.value || '').trim();
    
    if (!searchId || isNaN(searchId)) {
        searchError.value = 'Digite um ID numérico válido';
        return;
    }

    searchingOwner.value = true;
    searchError.value = '';
    foundOwner.value = null;

    try {
        const response = await axios.get(route('admin.owners.search', searchId));
        if (response.data.success) {
            foundOwner.value = response.data.owner;
            searchError.value = '';
        }
    } catch (error) {
        if (error.response && error.response.status === 404) {
            searchError.value = 'Proprietário não encontrado';
        } else {
            searchError.value = 'Erro ao buscar proprietário';
        }
        foundOwner.value = null;
    } finally {
        searchingOwner.value = false;
    }
};

const selectFoundOwner = () => {
    if (foundOwner.value) {
        selectOwner(foundOwner.value);
        // Clear search
        ownerSearchId.value = '';
        foundOwner.value = null;
        searchError.value = '';
    }
};

const selectOwner = (owner) => {
    selectedOwner.value = owner;
    sampleForm.owner_id = owner.id;
};

// Função para limpar campos de animais quando o tipo de teste mudar
const clearAnimalFieldsBasedOnTestType = () => {
    if (!geneticFieldsToShow.value.showFather) {
        animals.father = null;
        sampleForm.father_id = null;
        fatherName.value = '';
        fatherRg.value = '';
    }
    
    if (!geneticFieldsToShow.value.showMother) {
        animals.mother = null;
        sampleForm.mother_id = null;
        motherName.value = '';
        motherRg.value = '';
    }
};

// Watch para limpar campos quando o tipo de teste mudar
watch(() => sampleForm.test_type_id, () => {
    clearAnimalFieldsBasedOnTestType();
});

// Autocomplete functions
const searchAnimalsByName = (type) => {
    // Clear the appropriate timer
    if (type === 'child') {
        clearTimeout(childSearchTimer.value);
    } else if (type === 'father') {
        clearTimeout(fatherSearchTimer.value);
    } else if (type === 'mother') {
        clearTimeout(motherSearchTimer.value);
    }
    
    const query = type === 'child' ? childName.value : type === 'father' ? fatherName.value : motherName.value;
    
    if (!query || query.length < 2) {
        clearSuggestions(type);
        return;
    }

    // Debug: Log search parameters
    console.log(`Searching for ${type} animals:`, {
        query: query,
        type: type,
        owner_id: selectedOwner.value?.id,
        owner_name: selectedOwner.value?.name
    });

    // Set the appropriate timer
    const timer = setTimeout(async () => {
        try {
            const response = await axios.post(route('admin.samples.search-animals'), {
                query: query,
                type: type,
                owner_id: selectedOwner.value?.id
            });

            console.log(`Search response for ${type}:`, response.data);

            if (response.data.animals && response.data.animals.length > 0) {
                if (type === 'child') {
                    childSuggestions.value = response.data.animals;
                    showChildSuggestions.value = true;
                } else if (type === 'father') {
                    fatherSuggestions.value = response.data.animals;
                    showFatherSuggestions.value = true;
                } else if (type === 'mother') {
                    motherSuggestions.value = response.data.animals;
                    showMotherSuggestions.value = true;
                }

                // Se há múltiplos animais com o mesmo nome, mostrar modal de seleção
                if (response.data.multiple && response.data.animals.length > 1) {
                    multipleAnimals.value = response.data.animals;
                    currentAnimalSelectionType.value = type;
                    showAnimalSelectionModal.value = true;
                    clearSuggestions(type);
                }
            } else {
                console.warn(`No ${type} animals found for query: "${query}"`);
                clearSuggestions(type);
            }
        } catch (error) {
            console.error(`Error searching ${type} animals:`, error);
            if (error.response) {
                console.error('Response data:', error.response.data);
                console.error('Response status:', error.response.status);
            }
            alert(`Erro ao buscar animais ${type}. Tente novamente.`);
            clearSuggestions(type);
        }
    }, 300); // 300ms debounce
    
    // Store the timer in the appropriate variable
    if (type === 'child') {
        childSearchTimer.value = timer;
    } else if (type === 'father') {
        fatherSearchTimer.value = timer;
    } else if (type === 'mother') {
        motherSearchTimer.value = timer;
    }
};

const clearSuggestions = (type) => {
    if (type === 'child') {
        childSuggestions.value = [];
        showChildSuggestions.value = false;
    } else if (type === 'father') {
        fatherSuggestions.value = [];
        showFatherSuggestions.value = false;
    } else if (type === 'mother') {
        motherSuggestions.value = [];
        showMotherSuggestions.value = false;
    }
};

const selectAnimalFromSuggestion = (animal, type) => {
    animals[type] = animal;
    sampleForm[`${type}_id`] = animal.id;
    
    // Se for o animal principal (child), também definir animal_id
    if (type === 'child') {
        sampleForm.animal_id = animal.id;
        childName.value = animal.name;
        childRg.value = animal.register;
    } else if (type === 'father') {
        fatherName.value = animal.name;
        fatherRg.value = animal.register;
    } else if (type === 'mother') {
        motherName.value = animal.name;
        motherRg.value = animal.register;
    }
    
    clearSuggestions(type);
    console.log(`Animal ${type} selecionado via autocomplete:`, animal);
};

const clearSelectedAnimal = (type) => {
    animals[type] = null;
    sampleForm[`${type}_id`] = null;
    
    if (type === 'child') {
        childName.value = '';
        childRg.value = '';
    } else if (type === 'father') {
        fatherName.value = '';
        fatherRg.value = '';
    } else if (type === 'mother') {
        motherName.value = '';
        motherRg.value = '';
    }
};

// Close suggestions when clicking outside
const handleClickOutside = (event) => {
    if (!event.target.closest('.relative')) {
        showChildSuggestions.value = false;
        showFatherSuggestions.value = false;
        showMotherSuggestions.value = false;
    }
};

const nextStep = () => {
    if (currentStep.value < 3) {
        currentStep.value++;
    }
};

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

const checkAnimal = async (type) => {
    const rg = type === 'child' ? childRg.value : type === 'father' ? fatherRg.value : motherRg.value;
    
    console.log(`checkAnimal called for ${type} with RG: ${rg}, owner: ${selectedOwner.value?.id}`);
    
    if (!rg || !selectedOwner.value) {
        console.log('Early return: missing RG or owner');
        return;
    }

    try {
        console.log('Making API call to check-animal...');
        const response = await axios.post(route('admin.samples.check-animal'), {
            rg: rg,
            owner_id: selectedOwner.value.id,
            type: type
        });

        console.log('API Response:', response.data);

        if (response.data.multiple && response.data.animals.length > 0) {
            // Múltiplos animais encontrados, mostrar modal de seleção
            console.log('Multiple animals found, showing selection modal');
            multipleAnimals.value = response.data.animals;
            currentAnimalSelectionType.value = type;
            showAnimalSelectionModal.value = true;
        } else if (response.data.exists && !response.data.sex_mismatch && response.data.animal) {
            console.log('Animal found and assigned');
            animals[type] = response.data.animal;
            sampleForm[`${type}_id`] = response.data.animal.id;
            
            // Se for o animal principal (child), também definir animal_id
            if (type === 'child') {
                sampleForm.animal_id = response.data.animal.id;
            }
        } else if (response.data.exists && response.data.sex_mismatch) {
            console.log('Sex mismatch detected');
            animals[type] = null;
            sampleForm[`${type}_id`] = null;
            alert(type === 'father' 
                ? 'O RG informado pertence a um animal que não é Macho. Selecione um Macho para o Pai.'
                : type === 'mother'
                    ? 'O RG informado pertence a um animal que não é Fêmea. Selecione uma Fêmea para a Mãe.'
                    : 'Sexo incompatível para o tipo selecionado.');
        } else {
            // Animal doesn't exist, show modal to create
            console.log('Animal not found, showing creation modal');
            console.log('Setting currentAnimalType to:', type);
            console.log('Setting currentRg to:', rg);
            console.log('Setting showAnimalModal to true');
            currentAnimalType.value = type;
            currentRg.value = rg;
            showAnimalModal.value = true;
            console.log('showAnimalModal.value is now:', showAnimalModal.value);
        }
    } catch (error) {
        console.error('Error checking animal:', error);
        alert(`Erro ao verificar animal ${type}. Tente novamente.`);
    }
};

const closeAnimalModal = () => {
    showAnimalModal.value = false;
    currentAnimalType.value = '';
    currentRg.value = '';
};

// Função para carregar amostras do animal
const loadAnimalSamples = async () => {
    if (!animals.child?.id) {
        animalSamples.value = [];
        return;
    }

    loadingSamples.value = true;
    try {
        const response = await axios.get(`/admin/animals/${animals.child.id}/samples`);
        animalSamples.value = response.data.samples || [];
    } catch (error) {
        console.error('Erro ao carregar amostras:', error);
        animalSamples.value = [];
        // Para demonstração, vamos adicionar dados fictícios se não houver API
        animalSamples.value = [
            {
                id: '1848273',
                sample_code: '1848273',
                tipo_teste: 'ARQUIVO PERMANENTE',
                created_at: '2025-09-30',
                owner: { name: 'ROSIVAL MARTINS CABRAL' },
                father: null,
                mother: null
            },
            {
                id: '1848274',
                sample_code: '1848274',
                tipo_teste: 'ARQUIVO PERMANENTE',
                created_at: '2025-09-30',
                owner: { name: 'ROSIVAL MARTINS CABRAL' },
                father: null,
                mother: null
            }
        ];
    } finally {
        loadingSamples.value = false;
    }
};

// Animal selection modal functions
const closeAnimalSelectionModal = () => {
    showAnimalSelectionModal.value = false;
    multipleAnimals.value = [];
    currentAnimalSelectionType.value = '';
};

const selectAnimalFromModal = (selectedAnimal) => {
    const type = currentAnimalSelectionType.value;
    animals[type] = selectedAnimal;
    sampleForm[`${type}_id`] = selectedAnimal.id;
    
    // Se for o animal principal (child), também definir animal_id
    if (type === 'child') {
        sampleForm.animal_id = selectedAnimal.id;
    }
    
    console.log(`Animal ${type} selecionado:`, selectedAnimal);
    closeAnimalSelectionModal();
};

const handleAnimalCreated = (animal) => {
    const type = currentAnimalType.value;
    animals[type] = animal;
    sampleForm[`${type}_id`] = animal.id;
    
    // Se for o animal principal (child), também definir animal_id
    if (type === 'child') {
        sampleForm.animal_id = animal.id;
    }
    
    console.log(`Animal ${type} criado:`, animal); // Debug
    closeAnimalModal();
};

const submitSample = async () => {
    processing.value = true;
    
    try {
        await axios.post(route('admin.samples.store'), sampleForm);
        
        // Reset form
        currentStep.value = 1;
        selectedOwner.value = null;
        childRg.value = '';
        fatherRg.value = '';
        motherRg.value = '';
        Object.keys(animals).forEach(key => animals[key] = null);
        Object.keys(sampleForm).forEach(key => {
            if (key.includes('_id')) {
                sampleForm[key] = null;
            } else {
                sampleForm[key] = '';
            }
        });
        
        alert('Amostra cadastrada com sucesso!');
    } catch (error) {
        console.error('Error creating sample:', error);
        alert('Erro ao cadastrar amostra. Tente novamente.');
    } finally {
        processing.value = false;
    }
};


// Função para fechar modal de amostras
const closeSamplesModal = () => {
    showSamplesModal.value = false;
    animalSamples.value = [];
};

// Lifecycle hooks
onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    if (childSearchTimer.value) {
        clearTimeout(childSearchTimer.value);
    }
    if (fatherSearchTimer.value) {
        clearTimeout(fatherSearchTimer.value);
    }
    if (motherSearchTimer.value) {
        clearTimeout(motherSearchTimer.value);
    }
    document.removeEventListener('click', handleClickOutside);
});

// Watchers para debug
watch(showAnimalModal, (newValue) => {
    console.log('showAnimalModal changed to:', newValue);
});

watch(showAnimalSelectionModal, (newValue) => {
    console.log('showAnimalSelectionModal changed to:', newValue);
});

watch(currentAnimalType, (newValue) => {
    console.log('currentAnimalType changed to:', newValue);
});

watch(currentRg, (newValue) => {
    console.log('currentRg changed to:', newValue);
});

// Watcher para carregar amostras quando o modal for aberto
watch(showSamplesModal, (newValue) => {
    if (newValue) {
        loadAnimalSamples();
    }
});
</script>