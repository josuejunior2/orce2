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
              <v-col cols="4" md="4">
                <v-autocomplete
                    v-model="siteSelecionado"
                    :items="sites"
                    item-title="nome"
                    item-value="id"
                    label="Pesquisar site"
                    :loading="loading"
                    @update:search-input="buscarSites"
                    clearable
                      variant="outlined"
                      density="comfortable"
                      class="no-border-radius-right"
                >
                    <template #no-data>
                        <v-list-item
                        @click="selecionarCriarNovoSite"
                        title="Criar novo site"
                        />
                    </template>
                </v-autocomplete>
              </v-col>
              <v-col v-if="criandoNovoSite" cols="2" md="2">
                  <v-text-field
                    v-model="novoSite.latitude"
                    label="Latitude do novo site"
                      variant="outlined"
                      density="comfortable"
                      hide-details
                      class="no-border-radius-right"
                  />
              </v-col>
              <v-col v-if="criandoNovoSite" cols="2" md="2">
                  <v-text-field
                    v-model="novoSite.longitude"
                    label="Longitude do novo site"
                      variant="outlined"
                      density="comfortable"
                      hide-details
                      class="no-border-radius-right"
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
                    <v-text-field
                      v-model="velDown"
                      type="number"
                      min="0"
                      placeholder="Mbps"
                      label="Down"
                      variant="outlined"
                      density="comfortable"
                      hide-details
                      class="no-border-radius-right"
                    />

                    <v-text-field
                      v-model="velUp"
                      type="number"
                      min="0"
                      placeholder="Mbps"
                      label="Up"
                      variant="outlined"
                      density="comfortable"
                      hide-details
                      class="no-border-radius"
                    />

                    <v-text-field
                      v-model="barra"
                      type="number"
                      min="0"
                      max="32"
                      label="/"
                      variant="outlined"
                      density="comfortable"
                      hide-details
                      class="no-border-radius-left"
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
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import axios from 'axios'

const siteSelecionado = ref(null)
const cidadeSelecionada = ref(null)
const servicoSelecionado = ref(null)
const sites = ref([])
const loading = ref(false)
const criandoNovoSite = ref(false)
const termoBusca = ref('')

const novoSite = ref({
  nome: '',
  lagitude: '',
  longitude: null,
  endereco: null,
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
  novoSite.value.nome = termoBusca.value
  siteSelecionado.value = null
}

const props = defineProps({
  orcamento: Object,
  cidades: Array,
  servicos: Array,
});


const form = useForm({
  nome: '',
  descricao: '',
  valor: '',
})

const rules = {
  required: v => !!v || 'Campo obrigatório',
}

const submitForm = () => {
  form.post('/orcamentos') // ajuste conforme sua rota
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
