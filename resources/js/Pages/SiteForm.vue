<template>
  <v-row no-gutters>
    <v-col cols="12">
      <v-card class="ma-3" elevation="0" rounded="lg" border>
        <!-- Cabeçalho -->
        <v-card-item class="border-b">
          <v-card-title class="text-h6">{{ orcamento.titulo }}</v-card-title>
        </v-card-item>

        <!-- Corpo com data-grid -->
        <v-card-text>
          <v-row dense class="tabler-datagrid">
            <v-col cols="12" md="4">
              <strong>Cliente</strong> {{ orcamento.cliente.nome }}
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-col>

    <v-col cols="12">
      <v-card class="ma-3" elevation="0" rounded="lg" border>
        <v-card-item class="border-b">
          <v-card-title class="text-h6">Cadastro de Site</v-card-title>
        </v-card-item>
        <v-card-text>
          <v-form @submit.prevent="submitForm" ref="form">
            <v-row>
              <v-col v-if="!criandoNovoSite" cols="12" md="12">
                <v-autocomplete
                    v-model="siteSelecionado"
                    v-model:search="searchInput"
                    :items="sites"
                    item-title="nome"
                    item-value="id"
                    label="Pesquisar site"
                    :loading="loading"
                    @update:search="buscarSites"
                    clearable
                    variant="outlined"
                    density="comfortable"
                    class="no-border-radius-right"
                    id="selectSites"
                    return-object="false"
                >
                    <template #no-data>
                        <v-list-item
                        @click="selecionarCriarNovoSite"
                        title="Criar novo site"
                        />
                    </template>
                </v-autocomplete>
              </v-col>
              <v-col v-if="criandoNovoSite" cols="4" md="4">
                  <v-text-field
                    v-model="novoSite.nome"
                    label="Nome do novo site"
                    variant="outlined"
                    density="comfortable"
                    hide-details
                    class="no-border-radius-right"
                  />
              </v-col>
              <v-col v-if="criandoNovoSite" cols="2" md="2">
                  <v-number-input 
                      v-model="novoSite.latitude"
                      label="Latitude do novo site"
                      :min="-90"
                      :max="90"
                      variant="outlined"
                      density="comfortable"
                      hide-details
                      class="no-border-radius-right"
                      control-variant="hidden"
                      precision=15
                      decimal-separator=","
                      inset="false"
                      @update:model-value="novoSite.latitude = $event"
                      @paste="$event.preventDefault(); $event.target.value = ($event.clipboardData.getData('text')).replace('.', ',')"
                  />
              </v-col>
              <v-col v-if="criandoNovoSite" cols="2" md="2">
                  <v-number-input 
                      v-model="novoSite.longitude"
                      label="Longitude do novo site"
                      :min="-180"
                      :max="180"
                      variant="outlined"
                      density="comfortable"
                      hide-details
                      class="no-border-radius-right"
                      control-variant="hidden"
                      precision=15
                      decimal-separator=","
                      inset="false"
                      @update:model-value="novoSite.longitude = $event"
                      @paste="$event.preventDefault(); $event.target.value = ($event.clipboardData.getData('text')).replace('.', ',')"
                  />
              </v-col>
              <v-col v-if="criandoNovoSite" cols="4" md="4">
                <v-autocomplete
                  v-model="cidadeSelecionada"
                  :items="props.cidades"
                  item-title="nome"
                  item-value="id"
                  label="Pesquisar cidade"
                  clearable
                  return-object="false"
                  variant="outlined"
                  density="comfortable"
                  hide-details
                  class="no-border-radius-right"
                />
              </v-col>
              <v-col v-if="criandoNovoSite" cols="4" md="4">
                  <v-text-field
                    v-model="novoSite.endereco"
                    label="Endereço"
                      variant="outlined"
                      density="comfortable"
                      hide-details
                      class="no-border-radius-right"
                  />
              </v-col>
              <v-col v-if="criandoNovoSite" cols="4" md="4">
                <v-autocomplete
                  v-model="servicoSelecionado"
                  :items="props.servicos"
                  item-title="nome"
                  item-value="id"
                  label="Pesquisar serviço"
                  clearable
                  return-object="false"
                  multiple
                  chips
                  closable-chips
                  variant="outlined"
                  density="comfortable"
                  hide-details
                  class="no-border-radius-right"
                />
              </v-col>
              <v-col v-if="criandoNovoSite" cols="12" sm="4">
                  <div class="d-flex">
                    <v-number-input
                      v-model="novoSite.vel_solicitada_down"
                      min="0"
                      placeholder="Mbps"
                      label="Down"
                      variant="outlined"
                      density="comfortable"
                      hide-details
                      control-variant="hidden"
                      class="no-border-radius-right"
                    />

                    <v-number-input
                      v-model="novoSite.vel_solicitada_up"
                      min="0"
                      placeholder="Mbps"
                      label="Up"
                      variant="outlined"
                      density="comfortable"
                      hide-details
                      control-variant="hidden"
                      class="no-border-radius"
                    />

                    <v-number-input
                      v-model="novoSite.barra"
                      min="0"
                      max="32"
                      label="/"
                      variant="outlined"
                      density="comfortable"
                      hide-details
                      class="no-border-radius-left"
                      control-variant="hidden"
                    />
                  </div>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>

        <!-- card-footer -->
        <v-card-actions class="bg-grey-lighten-4 justify-end border-t">
          <v-btn type="submit" color="primary" @click="submitForm">
            <v-icon start>mdi-plus</v-icon>
            Cadastrar
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

const siteSelecionado = ref(null)
const cidadeSelecionada = ref(null)
const servicoSelecionado = ref(null)
const sites = ref([])
const loading = ref(false)
const criandoNovoSite = ref(false)
const searchInput = ref('')

const novoSite = ref({
  nome: '',
  lagitude: '',
  longitude: null,
  endereco: null,
  vel_solicitada_down: null,
  vel_solicitada_up: null,
  barra: null,
})

const buscarSites = async (nome) => {
    if (!nome || nome.length < 2) {
        sites.value = []
        return
    }

    loading.value = true
    try {
        const response = await axios.get('/sites/getSites', {
            params: { nome },
        })
        sites.value = response.data
    } catch (error) {
        console.error('Erro ao buscar sites:', error)
    } finally {
        loading.value = false
    }
}

const selecionarCriarNovoSite = () => {
  criandoNovoSite.value = true
  novoSite.value.nome = searchInput.value
  siteSelecionado.value = null
}

const props = defineProps({
  orcamento: Object,
  cidades: Array,
  servicos: Array,
});


const rules = {
  required: v => !!v || 'Campo obrigatório',
}

const submitForm = () => {
  novoSite.post('/site/post') // ajuste conforme sua rota
}
</script>


<style scoped>
.tabler-datagrid {
  font-family: var(--tblr-font-sans-serif);
  font-size: 0.875rem;
  line-height: 1.4;
}

.tabler-datagrid strong {
  display: inline-block;
  min-width: 120px;
  color: #667382;
}
</style>
