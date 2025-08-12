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
        <v-card-item class="border-b mb-4">
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
                    :return-object="false"
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
                    required
                  />
              </v-col>
              <v-col v-if="criandoNovoSite" cols="2" md="2">
                <v-text-field
                  v-model="novoSite.latitude"
                  label="Latitude do novo site"
                  variant="outlined"
                  density="comfortable"
                  hide-details="auto"
                  class="no-border-radius-right"
                  :rules="[v => validateCoords(v, 'lat') || 'Formato inválido']"
                  :messages="latConverted"
                />
              </v-col>

              <v-col v-if="criandoNovoSite" cols="2" md="2">
                <v-text-field
                  v-model="novoSite.longitude"
                  label="Longitude do novo site"
                  variant="outlined"
                  density="comfortable"
                  hide-details="auto"
                  class="no-border-radius-right"
                  :rules="[v => validateCoords(v, 'lon') || 'Formato inválido']"
                  :messages="lonConverted"
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
                  :return-object="false"
                  variant="outlined"
                  density="comfortable"
                  hide-details
                  class="no-border-radius-right"
                  required
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
                  :return-object="false"
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
                      :min="0"
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
                      :min="0"
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
                      :min="0"
                      :max="32"
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
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'

const siteSelecionado = ref(null)
const cidadeSelecionada = ref(null)
const servicoSelecionado = ref(null)
const sites = ref([])
const loading = ref(false)
const criandoNovoSite = ref(false)
const searchInput = ref('')
const latConverted = ref('')
const lonConverted = ref('')

const novoSite = reactive({
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
const validateCoords = (valor, tipo) => {
  const decimalRegex = /^-?\d+(\.\d+)?$/;
  if (!valor || typeof valor !== 'string' || valor.trim().match(decimalRegex)) return true  

  const dmsRegexLat = /^(\d{1,3})\s*°\s*(\d{1,2})\s*'\s*(\d{1,2}(?:\.\d+)?)\s*"\s*([NS])$/i
  const dmsRegexLon = /^(\d{1,3})\s*°\s*(\d{1,2})\s*'\s*(\d{1,2}(?:\.\d+)?)\s*"\s*([OWE])$/i

  const regex = tipo === 'lat' ? dmsRegexLat : dmsRegexLon
  const match = valor.trim().match(regex)

  if (match) {
    const deg = parseFloat(match[1])
    const min = parseFloat(match[2])
    const sec = parseFloat(match[3])
    const dir = match[4].toUpperCase()

    let decimal = deg + min / 60 + sec / 3600
    if ((tipo === 'lat' && dir === 'S') || (tipo === 'lon' && (dir === 'W' || dir === 'O'))) {
      decimal *= -1
    }

    const decimalFix = decimal

    if (tipo === 'lat') {
      novoSite.latitude = decimalFix
      valor = decimalFix
      latConverted.value = "Convertido em decimal."
      console.log(novoSite.latitude)
      return true
    } else {
      novoSite.longitude = decimalFix
      lonConverted.value = "Convertido em decimal."
      return true
    }
  }
}

const selecionarCriarNovoSite = () => {
  criandoNovoSite.value = true
  novoSite.nome = searchInput.value
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
  
  // if (form.value.validate() && textoLimpo && algumSelecionado) {
  //   router.post('/orientador/email/submit', {
  //     academicos: selectedAcad.value,
  //     supervisores: selectedSup.value,
  //     conteudo: html,
  //     titulo: titulo.value
  //   }, {
  //     onSuccess: (page) => {
  //       showSuccess.value = true;
  //     },
  //     onError: (error) => {
  //       console.log(page)
  //       if (error) {
  //         errorMessage.value = error[0];
  //         showError.value = true;
  //       }
  //     }
  //   });
  // }
  router.post('/site/post', novoSite)
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
