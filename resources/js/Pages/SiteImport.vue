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
          <v-card-title style="font-weight: 400; font-size: 16px;">Importação de Sites</v-card-title>
        </v-card-item>

        <!-- Corpo -->
        <v-card-text>
          <v-row dense class="mt-2">
            <v-col cols="12" md="6">
              <v-autocomplete
                v-model="orcamentoSelecionado"
                v-model:search="searchOrcamento"
                :items="orcamentos"
                item-title="tituloDisplay"
                item-value="id"
                label="Selecionar orçamento para vincular sites importados"
                :loading="loadingOrcamento"
                @update:search="buscarOrcamentos"
                variant="outlined"
                density="comfortable"
                :return-object="true"
                autocomplete="off"
                clearable
              >
                <template #no-data>
                  <v-list-item
                    v-if="searchOrcamento && searchOrcamento.length >= 2 && orcamentos.length == 0"
                    title="Nenhum orçamento encontrado"
                  />
                  <v-list-item
                    v-else
                    title="Digite pelo menos 2 caracteres para pesquisar"
                  />
                </template>
              </v-autocomplete>
            </v-col>

            <v-col cols="12" md="6">
              <v-file-input
                ref="fileInput"
                label="Arquivo"
                prepend-icon="mdi-paperclip"
                variant="outlined"
                density="comfortable"
                hide-details
                @change="onFileChange"
              />
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>

      <v-card class="ma-3" elevation="0" rounded="lg" border>
        <!-- Cabeçalho -->
        <v-card-item class="border-b mb-4 px-5 py-4">
          <v-card-title style="font-weight: 400; font-size: 16px;">Mapeamento de Colunas</v-card-title>
          <template #append>
            <div class="d-flex gap-2">
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

              <v-btn color="primary" variant="flat" @click="addCol">
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
          </template>
        </v-card-item>

        <!-- Tabela -->
        <v-card-text>
          <v-table class="bordered" style="table-layout: fixed">
            <thead>
              <tr>
                <th v-for="(col, index) in qtdCols" :key="'head-' + index" style="width: 200px; min-width: 200px; max-width: 200px;">
                  {{ colunasABC[index] }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td v-for="(col, index) in qtdCols" :key="'body-' + index" style="width: 200px; min-width: 200px; max-width: 200px; overflow: hidden;">
                  <v-select
                    v-model="colunasSelecionadas[index]"
                    :items="getColunasDisponiveis(index)"
                    item-title="text"
                    item-value="value"
                    density="comfortable"
                    variant="outlined"
                    hide-details
                    placeholder="Selecione..."
                  >
                    <template #item="{ props, item }">
                      <v-list-item
                        v-bind="props"
                        :disabled="item.raw.disabled"
                      >
                        <template #append v-if="item.raw.requerOrcamento && !orcamentoSelecionado">
                          <span class="text-caption text-grey">(Selecione um orçamento)</span>
                        </template>
                      </v-list-item>
                    </template>
                  </v-select>
                </td>
              </tr>
            </tbody>
          </v-table>
        </v-card-text>

        <!-- Rodapé -->
        <v-card-actions class="bg-grey-lighten-4 justify-end border-t px-5 py-4">
          <v-btn variant="flat" color="primary" @click="submitForm" :loading="form.processing">
            <v-icon start>mdi-upload</v-icon>
            Enviar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-col>
  </v-row>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import axios from 'axios'

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
const colunasSiteOrcamento = [
  'vel_solicitada_down',
  'vel_solicitada_up',
  'barra',
]

const qtdCols = ref(0)
const colunasSelecionadas = ref([])
const showSuccess = ref(false);
const showError = ref(false);
const errorMessage = ref('');
const fileInput = ref(null);
const orcamentos = ref([])
const loadingOrcamento = ref(false)
const orcamentoSelecionado = ref(null)
const searchOrcamento = ref('')

// Letras do cabeçalho (A, B, C, ...)
const colunasABC = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('')

const getColunasDisponiveis = (currentIndex) => {
  const todasColunas = []
  
  colunas.forEach(coluna => {
    const jaSelecionada = colunasSelecionadas.value.some((selecionada, index) => 
      index !== currentIndex && selecionada === coluna
    )
    
    todasColunas.push({
      value: coluna,
      text: coluna,
      disabled: jaSelecionada,
      requerOrcamento: false
    })
  })
  
  colunasSiteOrcamento.forEach(coluna => {
    const jaSelecionada = colunasSelecionadas.value.some((selecionada, index) => 
      index !== currentIndex && selecionada === coluna
    )
    
    const disable = !orcamentoSelecionado.value || jaSelecionada
    
    todasColunas.push({
      value: coluna,
      text: coluna,
      disabled: disable,
      requerOrcamento: true
    })
  })
  
  return todasColunas
}

const buscarOrcamentos = async (titulo) => {
  if (!titulo || titulo.length < 2) {
    orcamentos.value = []
    return
  }
  
  loadingOrcamento.value = true
  try {
    const response = await axios.get('/orcamento/getOrcamentos', {
      params: { titulo },
    })
    orcamentos.value = response.data
  } catch (error) {
    console.error('Erro ao buscar orçamentos:', error)
  } finally {
    loadingOrcamento.value = false
  }
}

// Funções de controle
const addCol = () => {
  const totalColunas = colunas.length + colunasSiteOrcamento.length
  if (qtdCols.value < totalColunas) {
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

const form = useForm({
  sites_sheet: null,
  colunas: colunasSelecionadas,
  orcamento_id: null
})

watch(colunasSelecionadas, (newVal) => {
  form.colunas = [...newVal]
}, { deep: true }) // observa mutações internas do array

function onFileChange(event) {
  form.sites_sheet = event.target.files[0];
}
// Submissão
const submitForm = () => {
  form.orcamento_id = orcamentoSelecionado.value.id ?? null;
  form.post(route('site.import.store'), {
    forceFormData: true,
    onSuccess: () => {
      showSuccess.value = true
      colunasSelecionadas.value = [];
      fileInput.value.reset()
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

<style scoped>
.v-table {
  table-layout: fixed;
}

.v-table td,
.v-table th {
  width: 200px;
  min-width: 200px;
  max-width: 200px;
  overflow: hidden;
}

/* Garante que o select não expanda */
.v-table td :deep(.v-select) {
  max-width: 100%;
}
</style>