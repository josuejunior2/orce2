<template>
  <v-snackbar v-model="showSuccess" color="success" timeout="7000">
    Site criado com sucesso!
  </v-snackbar>

  <v-snackbar v-model="showError" color="error" timeout="7000">
    {{ errorMessage }}
  </v-snackbar>
  
  <v-row no-gutters>
    <v-col cols="12">
      <v-card class="ma-3 mt-4" elevation="0" rounded="lg" border>
        <!-- Cabeçalho -->
        <v-card-item class="border-b">
          <v-card-title class="text-h6">{{ orcamento.titulo }}</v-card-title>
        </v-card-item>

        <!-- Corpo com data-grid -->
        <v-card-text>
          <v-row dense class="tabler-datagrid mt-2">
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
                    autocomplete="off"
                >
                    <template #no-data>
                        <v-list-item
                          v-if="searchInput && searchInput.length >= 2"
                          @click="selecionarCriarNovoSite"
                          title="Criar novo site"
                        />
                        <v-list-item
                          v-else
                          title="Digite pelo menos 2 caracteres para pesquisar"
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
                    class="no-border-radius-right"
                    :rules="[v => !!v || 'Nome é obrigatório']"
                    required
                    autocomplete="off"
                    :error-messages="novoSite.errors.nome"
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
                  :hint="latConverted"
                  autocomplete="off"
                  :error-messages="novoSite.errors.latitude"
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
                  :hint="lonConverted"
                  autocomplete="off"
                  :error-messages="novoSite.errors.longitude"
                />
              </v-col>
              <v-col v-if="criandoNovoSite" cols="4" md="4">
                <v-autocomplete
                  v-model="novoSite.cidade_id"
                  :items="props.cidades"
                  item-title="nome"
                  item-value="id"
                  label="Pesquisar cidade"
                  clearable
                  :return-object="false"
                  variant="outlined"
                  density="comfortable"
                  class="no-border-radius-right"
                  :rules="[v => !!v || 'Cidade é obrigatório']"
                  required
                  :error-messages="novoSite.errors.cidade_id"
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
                    autocomplete="off"
                  :error-messages="novoSite.errors.endereco"
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
                  autocomplete="off"
                  :error-messages="novoSite.errors.servicos"
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
                      autocomplete="off"
                  :error-messages="novoSite.errors.vel_solicitada_down"
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
                      autocomplete="off"
                  :error-messages="novoSite.errors.vel_solicitada_up"
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
                      autocomplete="off"
                  :error-messages="novoSite.errors.barra"
                    />
                  </div>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>

        <!-- card-footer -->
        <v-card-actions class="bg-grey-lighten-4 justify-end border-t">
          <v-btn type="submit" variant="flat" class="ms-2" color="primary" @click="submitForm">
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
import { useForm } from '@inertiajs/vue3'
import axios from 'axios'

const showSuccess = ref(false);
const showError = ref(false);
const errorMessage = ref('');
const siteSelecionado = ref(null)
const servicoSelecionado = ref(null)
const sites = ref([])
const loading = ref(false)
const criandoNovoSite = ref(false)
const searchInput = ref('')
const latConverted = ref('')
const lonConverted = ref('')
const form = ref(null);

const novoSite = useForm({
  nome: '',
  latitude: '',
  longitude: null,
  endereco: null,
  cidade_id: null,
  vel_solicitada_down: null,
  vel_solicitada_up: null,
  barra: null,
  orcamento_id: null,
  servicos: null,
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
  if (!valor || typeof valor !== 'string') return true;

  const decimalRegex = /^-?\d+(?:\.\d+)?$/;

  if (decimalRegex.test(valor.trim())) {
    const num = parseFloat(valor);
    if (tipo === 'lat') return num >= -90 && num <= 90;
    if (tipo === 'lon') return num >= -180 && num <= 180;
    return false;
  }

  const dmsRegexLat = /^(\d{1,3})\s*[°º]\s*(\d{1,2})\s*'\s*(\d{1,2}(?:\.\d+)?)\s*["”]\s*([NS])$/i;
  const dmsRegexLon = /^(\d{1,3})\s*[°º]\s*(\d{1,2})\s*'\s*(\d{1,2}(?:\.\d+)?)\s*["”]\s*([OWE])$/i;

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
  errors: Object
});

const submitForm = async () => {
  novoSite.orcamento_id = props.orcamento.id
  novoSite.servicos = servicoSelecionado.value
    
  const valid = await form.value.validate()

  if (valid.valid) {
    novoSite.post(route('site.store'), {
      onSuccess: (page) => {
        showSuccess.value = true
        
        novoSite.reset()
        
        criandoNovoSite.value = false
        siteSelecionado.value = null
        servicoSelecionado.value = null
        latConverted.value = ''
        lonConverted.value = ''
        console.log(response.data.redirect_url, response.data, response)
        if (response.data.redirect_url) {
            window.location.href = response.data.redirect_url;
        }
      },
      onError: (errors) => {
        if (Object.keys(errors).length > 0) {
          const firstError = Object.values(errors)[0]
          errorMessage.value = Array.isArray(firstError) ? firstError[0] : firstError
          showError.value = true
        }
      }
    })
  } else {
    console.log('Validação do frontend falhou')
  }
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
