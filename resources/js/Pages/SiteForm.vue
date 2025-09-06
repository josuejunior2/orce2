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
              <v-col v-if="!exibeCampos || siteSelecionado != null" :cols="siteSelecionado != null ? '3' : '12'" :md="siteSelecionado != null ? '3' : '12'">
                <v-autocomplete
                    v-model="siteSelecionado"
                    v-model:search="searchInput"
                    :items="sites"
                    item-title="nomeDisplay"
                    item-value="id"
                    label="Pesquisar site"
                    :loading="loading"
                    @update:search="buscarSites"
                    @update:modelValue="selecionaSite"
                    variant="outlined"
                    density="comfortable"
                    class="no-border-radius-right"
                    id="selectSites"
                    :return-object="true"
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
                          title="Digite pelo menos 2 caracteres para pesquisar pelo nome"
                        />
                    </template>
                    <template #item="{ props, item }">
                      <v-list-item v-bind="props" :disabled="item.raw.orcado">
                        <template #append>
                          <span v-if="item.raw.orcado" class="text-red-500 text-xs">
                            (Já foi incluído no orçamento)
                          </span>
                        </template>
                      </v-list-item>
                    </template>
                </v-autocomplete>
              </v-col>
              <v-col v-if="exibeCampos && siteSelecionado == null" cols="3" md="3">
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
              <v-col v-if="exibeCampos" cols="1" md="1">
                <v-checkbox
                  v-model="novoSite.subestacao"
                  label="Subestação"
                  density="compact"
                  hide-details
                />
              </v-col>
              <v-col v-if="exibeCampos" cols="2" md="2">
                <v-text-field
                  v-model="novoSite.latitude"
                  label="Latitude do novo site"
                  variant="outlined"
                  density="comfortable"
                  hide-details="auto"
                  class="no-border-radius-right"
                  :rules="[v => validateCoords(v, 'lat', novoSite) || 'Formato inválido']"
                  :hint="latConverted"
                  autocomplete="off"
                  :error-messages="novoSite.errors.latitude"
                  :readonly="siteSelecionado != null"
                />
              </v-col>

              <v-col v-if="exibeCampos" cols="2" md="2">
                <v-text-field
                  v-model="novoSite.longitude"
                  label="Longitude do novo site"
                  variant="outlined"
                  density="comfortable"
                  hide-details="auto"
                  class="no-border-radius-right"
                  :rules="[v => validateCoords(v, 'lon', novoSite) || 'Formato inválido']"
                  :hint="lonConverted"
                  autocomplete="off"
                  :error-messages="novoSite.errors.longitude"
                  :readonly="siteSelecionado != null"
                />
              </v-col>
              <v-col v-if="exibeCampos" cols="4" md="4">
                <v-autocomplete
                  v-model="novoSite.cidade_id"
                  :items="props.cidades"
                  item-title="nome"
                  item-value="id"
                  label="Pesquisar cidade"
                  :clearable="siteSelecionado == null"
                  :return-object="false"
                  variant="outlined"
                  density="comfortable"
                  class="no-border-radius-right"
                  :rules="[v => !!v || 'Cidade é obrigatório']"
                  required
                  :error-messages="novoSite.errors.cidade_id"
                  :readonly="siteSelecionado != null"
                />
              </v-col>
              <v-col v-if="exibeCampos" cols="4" md="4">
                  <v-text-field
                    v-model="novoSite.endereco"
                    label="Endereço"
                    variant="outlined"
                    density="comfortable"
                    hide-details
                    class="no-border-radius-right"
                    autocomplete="off"
                    :error-messages="novoSite.errors.endereco"
                    :readonly="siteSelecionado != null"
                  />
              </v-col>
              <v-col v-if="exibeCampos" cols="4" md="4">
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
              <v-col v-if="exibeCampos" cols="12" sm="4">
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

            <v-col v-if="novoSite.subestacao" cols="12">
              <v-card
                v-for="(ponta, index) in pontas"
                :key="index"
                class="mb-3"
                elevation="0"
                rounded="lg"
                border
              >
                <v-card-title class="d-flex justify-space-between align-center">
                  <span>Ponta {{ index + 1 }}</span>
                  <v-btn
                    icon="mdi-close"
                    size="small"
                    color="red"
                    variant="text"
                    @click="removerPonta(index)"
                  />
                </v-card-title>

                <v-card-text>
                  <v-row dense>
                    <!-- Nome + Subestação -->
                    <v-col cols="12" md="3">
                      <v-text-field
                        v-model="ponta.nome"
                        label="Nome"
                        density="compact"
                        variant="outlined"
                        :rules="[v => !!v || 'Nome é obrigatório']"
                      />
                    </v-col>

                    <!-- Latitude + Longitude -->
                    <v-col cols="12" md="2">
                      <v-text-field
                        v-model="ponta.latitude"
                        label="Latitude da ponta"
                        variant="outlined"
                        density="compact"
                        hide-details="auto"
                        class="no-border-radius-right"
                        :rules="[v => validateCoords(v, 'lat', ponta) || 'Formato inválido']"
                        :hint="latConverted"
                        autocomplete="off"
                        :error-messages="novoSite.errors.latitude"
                      />
                    </v-col>

                    <v-col cols="12" md="2">
                      <v-text-field
                        v-model="ponta.longitude"
                        label="Longitude da ponta"
                        variant="outlined"
                        density="compact"
                        hide-details="auto"
                        class="no-border-radius-right"
                        :rules="[v => validateCoords(v, 'lon', ponta) || 'Formato inválido']"
                        :hint="lonConverted"
                        autocomplete="off"
                        :error-messages="novoSite.errors.longitude"
                      />
                    </v-col>

                    <!-- Cidade + Endereço -->
                    <v-col cols="12" md="4">
                        <v-autocomplete
                          v-model="ponta.cidade_id"
                          :items="props.cidades"
                          item-title="nome"
                          item-value="id"
                          label="Pesquisar cidade"
                          :clearable="siteSelecionado == null"
                          :return-object="false"
                          variant="outlined"
                          density="compact"
                          class="no-border-radius-right"
                          :rules="[v => !!v || 'Cidade é obrigatório']"
                          required
                          :error-messages="novoSite.errors.cidade_id"
                        />
                    </v-col>

                    <v-col cols="12" md="3">
                      <v-text-field
                        v-model="ponta.endereco"
                        label="Endereço"
                        density="compact"
                        variant="outlined"
                      />
                    </v-col>

                    <v-col cols="4" md="4">
                        <v-autocomplete
                          v-model="ponta.servicos"
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
                          density="compact"
                          hide-details
                          class="no-border-radius-right"
                          autocomplete="off"
                          :error-messages="novoSite.errors.servicos"
                        />
                    </v-col>

                    <v-col cols="12" md="4">
                      <div class="d-flex">
                        <v-text-field
                          v-model="ponta.vel_solicitada_up"
                          :min="0"
                          placeholder="Mbps"
                          label="Down"
                          variant="outlined"
                          density="compact"
                          hide-details
                          control-variant="hidden"
                          class="no-border-radius-right"
                          autocomplete="off"
                          :error-messages="novoSite.errors.vel_solicitada_down"
                        />
                        <v-text-field
                          v-model="ponta.vel_solicitada_down"
                          :min="0"
                          placeholder="Mbps"
                          label="Up"
                          variant="outlined"
                          density="compact"
                          hide-details
                          control-variant="hidden"
                          class="no-border-radius"
                          autocomplete="off"
                          :error-messages="novoSite.errors.vel_solicitada_up"
                        />
                        <v-text-field
                          v-model="ponta.barra"
                          :min="0"
                          :max="32"
                          label="/"
                          variant="outlined"
                          density="compact"
                          hide-details
                          class="no-border-radius-left"
                          control-variant="hidden"
                          autocomplete="off"
                          :error-messages="novoSite.errors.barra"
                        />
                      </div>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>

              <!-- Botão para adicionar mais subestações -->
              <v-btn
                variant="outlined"
                color="primary"
                prepend-icon="mdi-plus"
                @click="adicionarPonta"
              >
                Adicionar Ponta
              </v-btn>
            </v-col>
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
const exibeCampos = ref(false)
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
  site_id: false,
  subestacao: false,
  pontas: []
})

const pontas = ref([]);

const adicionarPonta = () => {
  pontas.value.push({
    nome: "",
    latitude: "",
    longitude: "",
    cidade_id: "",
    endereco: "",
    vel_solicitada_up: null,
    vel_solicitada_down: null,
    barra: "",
    servicos: [],
  });
};

const removerPonta = (index) => {
  pontas.value.splice(index, 1);
};

const buscarSites = async (nome) => {
    if (!nome || nome.length < 2) {
        sites.value = []
        exibeCampos.value = false
        return
    }
    var orcamento_id = props.orcamento.id
    loading.value = true
    try {
        const response = await axios.get('/sites/getSites', {
            params: { nome, orcamento_id },
        })
        sites.value = response.data
    } catch (error) {
        console.error('Erro ao buscar sites:', error)
    } finally {
        loading.value = false
    }
}

const selecionaSite = (site) => {
  if (site) {
    novoSite.nome = site.nome
    novoSite.latitude = site.latitude
    novoSite.longitude = site.longitude
    novoSite.endereco = site.endereco
    novoSite.cidade_id = site.cidade_id
    exibeCampos.value = true
    servicoSelecionado.value = null
    novoSite.vel_solicitada_down = null
    novoSite.vel_solicitada_up = null
    novoSite.barra = null
  }
}

const validateCoords = (valor, tipo, model) => {
  if (!valor || typeof valor !== 'string') return true;

  const decimalRegex = /^-?\d+(?:\.\d+)?$/; // decimal simples
  const decimalWithDirRegex = /^(\d{1,3}(?:\.\d+)?)\s*[°º]?\s*([NSEWO])$/i; // decimal + direção
  const dmsRegexLat = /^(\d{1,3})\s*[°º]\s*(\d{1,2})\s*'\s*(\d{1,2}(?:\.\d+)?)\s*["”]?\s*([NS])$/i;
  const dmsRegexLon = /^(\d{1,3})\s*[°º]\s*(\d{1,2})\s*'\s*(\d{1,2}(?:\.\d+)?)\s*["”]?\s*([OWE])$/i;

  // Caso 1: decimal simples
  if (decimalRegex.test(valor.trim())) {
    const num = parseFloat(valor);
    if (tipo === 'lat' && num >= -90 && num <= 90) {
      model.latitude = num;
      latConverted.value = "Convertido em decimal.";
      return true;
    }
    if (tipo === 'lon' && num >= -180 && num <= 180) {
      model.longitude = num;
      lonConverted.value = "Convertido em decimal.";
      return true;
    }
    return false;
  }

  // Caso 2: decimal com direção (ex: 15.8009° S)
  const matchDecDir = valor.trim().match(decimalWithDirRegex);
  if (matchDecDir) {
    let num = parseFloat(matchDecDir[1]);
    const dir = matchDecDir[2].toUpperCase();

    if ((tipo === 'lat' && dir === 'S') || (tipo === 'lon' && (dir === 'W' || dir === 'O'))) {
      num *= -1;
    }

    if (tipo === 'lat' && num >= -90 && num <= 90) {
      model.latitude = num;
      latConverted.value = "Convertido em decimal.";
      return true;
    }
    if (tipo === 'lon' && num >= -180 && num <= 180) {
      model.longitude = num;
      lonConverted.value = "Convertido em decimal.";
      return true;
    }
  }

  // Caso 3: DMS (graus, minutos, segundos)
  const regex = tipo === 'lat' ? dmsRegexLat : dmsRegexLon;
  const match = valor.trim().match(regex);

  if (match) {
    const deg = parseFloat(match[1]);
    const min = parseFloat(match[2]);
    const sec = parseFloat(match[3]);
    const dir = match[4].toUpperCase();

    let decimal = deg + min / 60 + sec / 3600;
    if ((tipo === 'lat' && dir === 'S') || (tipo === 'lon' && (dir === 'W' || dir === 'O'))) {
      decimal *= -1;
    }

    if (tipo === 'lat') {
      model.latitude = decimal;
      latConverted.value = "Convertido em decimal.";
      return true;
    } else {
      model.longitude = decimal;
      lonConverted.value = "Convertido em decimal.";
      return true;
    }
  }

  return false;
}

const selecionarCriarNovoSite = () => {
  novoSite.latitude = null
  novoSite.longitude = null
  novoSite.endereco = null
  novoSite.cidade_id = null
  exibeCampos.value = true
  novoSite.nome = siteSelecionado.value == null ? searchInput.value : null
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
  novoSite.site_id = siteSelecionado.value != null ? siteSelecionado.value.id : null
  novoSite.pontas = pontas
  const valid = await form.value.validate()

  if (valid.valid) {
    novoSite.post(route('site.store'), {
      onSuccess: (page) => {
        showSuccess.value = true
        
        novoSite.reset()
        
        exibeCampos.value = false
        siteSelecionado.value = null
        servicoSelecionado.value = null
        latConverted.value = ''
        lonConverted.value = ''
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
