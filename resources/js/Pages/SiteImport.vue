<template>
  <v-snackbar v-model="showSuccess" color="success" timeout="7000">
    Importação realizada com sucesso!
  </v-snackbar>

  <v-snackbar v-model="showError" color="error" timeout="7000">
    {{ errorMessage }}
  </v-snackbar>
  
  <v-row no-gutters>
    <v-col cols="12" class="tabler-datagrid">
      <v-card class="ma-3 mt-4 mb-1" elevation="0" rounded="lg" border>
        <!-- Cabeçalho -->
        <v-card-item class="border-b tabler-datagrid pt-6 pb-6 pl-5 pr-8">
          <v-card-title style="font-weight: 400; font-size: 16px;">Importação</v-card-title>
        </v-card-item>
        
        <v-form ref="form">
        </v-form>
      </v-card>
  <v-card class="ma-3">
    <!-- Cabeçalho -->
    <v-card-title class="d-flex justify-space-between align-center py-2">
      <span>Adicione o número de colunas</span>
      <div>
        <v-btn color="error" class="me-2" variant="flat" @click="removeCol">
          <template #prepend>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
              viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M5 12l14 0" />
            </svg>
          </template>
          Remover
        </v-btn>

        <v-btn color="info" variant="flat" @click="addCol">
          <template #prepend>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
              viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M12 5l0 14" />
              <path d="M5 12l14 0" />
            </svg>
          </template>
          Adicionar
        </v-btn>
      </div>
    </v-card-title>

    <!-- Tabela -->
    <v-card-text>
      <v-table class="bordered">
        <thead>
          <tr>
            <th v-for="(col, index) in qtdCols" :key="'head-' + index">
              {{ colunasABC[index] }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td v-for="(col, index) in qtdCols" :key="'body-' + index">
              <v-select
                v-model="colunasSelecionadas[index]"
                :items="colunas"
                density="comfortable"
                variant="outlined"
                hide-details
                placeholder="Selecione..."
              />
            </td>
          </tr>
        </tbody>
      </v-table>
    </v-card-text>

    <!-- Rodapé -->
    <v-card-actions class="d-flex justify-space-between">
      <v-file-input
        v-model="form.sites_sheet"
        label="Arquivo"
        prepend-icon="mdi-paperclip"
        variant="outlined"
        density="comfortable"
        @update:modelValue="(file) => form.sites_sheet = file"
      />

      <v-btn color="success" @click="submitForm" :loading="form.processing">
        Enviar
      </v-btn>
    </v-card-actions>
  </v-card>

    </v-col>
  </v-row>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

// Colunas fixas (vindas do backend originalmente)
const colunas = [
  'nome',
  'endereco',
  'cidade',
  'uf',
  'latitude',
  'longitude',
  'id_instalacao',
]

// Estado reativo
const qtdCols = ref(0)
const colunasSelecionadas = ref([])
const showSuccess = ref(false);
const showError = ref(false);
const errorMessage = ref('');

// Letras do cabeçalho (A, B, C, ...)
const colunasABC = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('')

// Funções de controle
const addCol = () => {
  if (qtdCols.value < colunas.length) {
    colunasSelecionadas.value.push('')
    qtdCols.value++
  }
}

const removeCol = () => {
  if (qtdCols.value > 0) {
    colunasSelecionadas.value.pop()
    qtdCols.value--
  }
}

// Formulário via Inertia
const form = useForm({
  sites_sheet: null,
  colunas: colunasSelecionadas,
})

// Submissão
const submitForm = () => {
    console.log(form.sites_sheet, form) // Deve ser File ou array de File!
  form.post(route('site.import.store'), {
    // forceFormData: true,
    onSuccess: () => {
      showSuccess.value = true
      form.reset()
    },
    onError: tratarErros
  })
}
const tratarErros = (errors) => {
  if (Object.keys(errors).length > 0) {
    const firstError = Object.values(errors)[0]
    errorMessage.value = Array.isArray(firstError) ? firstError[0] : firstError
    showError.value = true
  }
}
</script>
