<template>
  <v-form @submit.prevent="$emit('submit')" ref="form">
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

      <v-col v-if="exibeCampos" cols="2" md="2">
        <v-text-field
          v-model="novoSite.id_instalacao"
          label="ID da Instalação"
          variant="outlined"
          density="comfortable"
          hide-details
          class="no-border-radius-right"
          autocomplete="off"
          :error-messages="novoSite.errors.id_instalacao"
          :readonly="siteSelecionado != null"
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

      <v-col v-if="exibeCampos" cols="3" md="3">
        <v-autocomplete
          v-model="novoSite.cidade_id"
          :items="cidades"
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
          autocomplete="off"
        />
      </v-col>

      <v-col v-if="exibeCampos" cols="1" md="1">
        <v-checkbox
          v-model="novoSite.is_subestacao"
          label="Subestação"
          density="compact"
          hide-details
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

      <v-col v-if="exibeCampos" cols="3" md="3">
        <v-autocomplete
          v-model="servicoSelecionado"
          :items="servicos"
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

    <!-- Pontas (subestação) -->
    <v-col v-if="novoSite.is_subestacao" cols="12">
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
            <v-col cols="12" md="3">
              <v-text-field
                v-model="ponta.nome"
                label="Nome"
                density="compact"
                variant="outlined"
                :rules="[v => !!v || 'Nome é obrigatório']"
              />
            </v-col>

            <v-col cols="12" md="2">
              <v-text-field
                v-model="ponta.id_instalacao"
                label="ID da instalação"
                density="compact"
                variant="outlined"
              />
            </v-col>

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
              />
            </v-col>

            <v-col cols="12" md="3">
              <v-autocomplete
                v-model="ponta.cidade_id"
                :items="cidades"
                item-title="nome"
                item-value="id"
                label="Pesquisar cidade"
                :return-object="false"
                variant="outlined"
                density="compact"
                class="no-border-radius-right"
                :rules="[v => !!v || 'Cidade é obrigatório']"
                required
              />
            </v-col>

            <v-col cols="12" md="4">
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
                :items="servicos"
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
              />
            </v-col>

            <v-col cols="12" md="4">
              <div class="d-flex">
                <v-text-field
                  v-model="ponta.vel_solicitada_down"
                  :min="0"
                  placeholder="Mbps"
                  label="Down"
                  variant="outlined"
                  density="compact"
                  hide-details
                  class="no-border-radius-right"
                  autocomplete="off"
                />
                <v-text-field
                  v-model="ponta.vel_solicitada_up"
                  :min="0"
                  placeholder="Mbps"
                  label="Up"
                  variant="outlined"
                  density="compact"
                  hide-details
                  class="no-border-radius"
                  autocomplete="off"
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
                  autocomplete="off"
                />
              </div>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>

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
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import axios from 'axios'

// -------------------------------------------------------
// Props
// -------------------------------------------------------
const props = defineProps({
  cidades: { type: Array, required: true },
  servicos: { type: Array, required: true },
  orcamento: { type: Object, required: true },
  site: { type: Object, default: null },
  siteOrcamento: { type: Object, default: null },
  pontas: { type: Object, default: null },
})

const emit = defineEmits(['submit', 'update:novoSite', 'update:pontas'])

// -------------------------------------------------------
// Form
// -------------------------------------------------------
const novoSite = useForm({
  id: props.site?.id ?? null,
  nome: props.site?.nome ?? '',
  endereco: props.site?.endereco ?? '',
  cidade_id: props.site?.cidade_id ?? null,
  latitude: props.site?.latitude ?? '',
  longitude: props.site?.longitude ?? '',
  id_instalacao: props.site?.id_instalacao ?? '',
  is_subestacao: props.site?.is_subestacao ?? false,
  vel_solicitada_down: props.site?.vel_solicitada_down ?? null,
  vel_solicitada_up: props.site?.vel_solicitada_up ?? null,
  barra: props.site?.barra ?? null,
  servicos: props.site?.servicos ?? [],
  orcamento_id: props.orcamento?.id ?? null,
  pontas: []
})

// -------------------------------------------------------
// Busca de sites
// -------------------------------------------------------
const siteSelecionado = ref(null)
const searchInput = ref('')
const sites = ref([])
const loading = ref(false)
const exibeCampos = ref(props.site != null) // se veio site, já exibe campos
const servicoSelecionado = ref(props.site?.servicos ?? [])

async function buscarSites(val) {
  if (!val || val.length < 2) return
  loading.value = true
  const { data } = await axios.get(route('site.getSites'), { params: { nome: val, orcamento_id: props.orcamento?.id } })
  sites.value = data
  loading.value = false
}

function selecionaSite(siteObj) {
  if (!siteObj) return
  exibeCampos.value = true
  novoSite.nome = siteObj.nome
  novoSite.id_instalacao = siteObj.id_instalacao
  novoSite.latitude = siteObj.latitude
  novoSite.longitude = siteObj.longitude
  novoSite.endereco = siteObj.endereco
  novoSite.cidade_id = siteObj.cidade_id
  exibeCampos.value = true
  servicoSelecionado.value = null
  novoSite.vel_solicitada_down = null
  novoSite.vel_solicitada_up = null
  novoSite.barra = null
}

function selecionarCriarNovoSite() {
  siteSelecionado.value = null
  exibeCampos.value = true
  novoSite.reset()
}

// -------------------------------------------------------
// Coords
// -------------------------------------------------------
const latConverted = ref('')
const lonConverted = ref('')


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


// -------------------------------------------------------
// Pontas
// -------------------------------------------------------
const pontas = ref(props.site?.pontas ?? [])

function adicionarPonta() {
  pontas.value.push({
    nome: '',
    id_instalacao: '',
    latitude: '',
    longitude: '',
    cidade_id: null,
    endereco: '',
    servicos: [],
    vel_solicitada_down: null,
    vel_solicitada_up: null,
    barra: null,
  })
}

function removerPonta(index) {
  pontas.value.splice(index, 1)
}

// -------------------------------------------------------
// Expõe para o pai validar e acessar os dados
// -------------------------------------------------------
const form = ref(null)

async function validateForm() {
  const result = await form.value.validate()

  if (!result.valid) return false
  return true
}

function getData() {
  return {
    ...novoSite.data(),
    servicos: servicoSelecionado.value,
    site_id: siteSelecionado.value?.id ?? null,
    pontas: pontas.value,
  }
}

function reset() {
  novoSite.reset()
  siteSelecionado.value = null
  searchInput.value = ''
  exibeCampos.value = false
  servicoSelecionado.value = []
  pontas.value = []
}
function setDataFromOrcamento(siteOrcamento, pontasData) {
  if (siteOrcamento) {
    exibeCampos.value = true

    const site = siteOrcamento.site

    servicoSelecionado.value = siteOrcamento.servicos_solicitados

    novoSite.defaults({
      nome: site.nome,
      id_instalacao: site.id_instalacao,
      latitude: site.latitude,
      longitude: site.longitude,
      endereco: site.endereco,
      cidade_id: site.cidade_id,
      vel_solicitada_down: siteOrcamento.vel_solicitada_down ? Number(siteOrcamento.vel_solicitada_down) : null,
      vel_solicitada_up: siteOrcamento.vel_solicitada_up ? Number(siteOrcamento.vel_solicitada_up) : null,
      barra: siteOrcamento.barra ? Number(siteOrcamento.barra) : null,
      is_subestacao: Boolean(site.is_subestacao),
      servicos: siteOrcamento.servicos_solicitados?.map(s => s.id) ?? [],
    })

    novoSite.reset()
  }

  if (pontasData) {
    const pontasFormatadas = pontasData.map(p => ({
      site_id: p.site.id,
      site_orcamento_id: p.id,
      nome: p.site.nome,
      id_instalacao: p.site.id_instalacao,
      latitude: p.site.latitude,
      longitude: p.site.longitude,
      cidade_id: p.site.cidade_id,
      endereco: p.site.endereco,
      vel_solicitada_down: p.vel_solicitada_down ? Number(p.vel_solicitada_down) : null,
      vel_solicitada_up: p.vel_solicitada_up ? Number(p.vel_solicitada_up) : null,
      barra: p.barra ? Number(p.barra) : null,
      servicos: p.servicos_solicitados?.map(s => s.id) ?? []
    }))

    pontas.value = pontasFormatadas
  }
}

defineExpose({ validateForm, getData, reset, novoSite, setDataFromOrcamento })
</script>