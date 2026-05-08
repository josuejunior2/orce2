<template>
  <v-form ref="form">
    <v-row>

      <!-- Fornecedor -->
      <v-col cols="12" md="3" class="py-0 my-0">
        <v-autocomplete
          v-model="fornecedorSelecionado"
          :items="fornecedores"
          item-title="nome"
          item-value="id"
          label="Fornecedor"
          :return-object="true"
          variant="outlined"
          density="comfortable"
          :rules="[v => !!v || 'Fornecedor é obrigatório']"
          :error-messages="formCotacao.errors.fornecedor_id"
          required
          :loading="loadingFornecedores"
          @update:search="buscarFornecedores"
        />
      </v-col>

      <!-- Status -->
      <v-col cols="12" md="1" class="py-0 my-0">
        <v-select
          v-model="formCotacao.status"
          :items="statusOpcoes"
          item-title="label"
          item-value="value"
          label="Status"
          variant="outlined"
          density="comfortable"
          :rules="[v => !!v || 'Status é obrigatório']"
          :error-messages="formCotacao.errors.status"
          required
        />
      </v-col>

      <!-- Prazo instalação fornecedor -->
      <v-col cols="12" md="2" class="py-0 my-0">
        <v-text-field
          v-model="formCotacao.prazo_instalacao_fornecedor"
          label="Prazo de Instalação Fornecedor"
          variant="outlined"
          density="comfortable"
          :rules="[v => !!v || 'Campo obrigatório']"
          :error-messages="formCotacao.errors.prazo_instalacao_fornecedor"
          autocomplete="off"
        />
      </v-col>

      <!-- Tecnologia -->
      <v-col cols="12" md="3" class="py-0 my-0">
        <v-radio-group
          v-model="formCotacao.tecnologia"
          inline
          :rules="[v => !!v || 'Tecnologia é obrigatória']"
          :error-messages="formCotacao.errors.tecnologia"
        >
          <v-radio
            v-for="tec in tecnologiaOpcoes"
            :key="tec.value"
            :label="tec.label"
            :value="tec.value"
            class="mr-3"
          />
        </v-radio-group>
      </v-col>

      <!-- Velocidades -->
      <v-col cols="12" md="3" class="py-0 my-0 mt-2">
        <div class="d-flex">
          <v-number-input
            v-model="formCotacao.vel_down"
            :min="0"
            placeholder="Mbps"
            label="Down"
            variant="outlined"
            density="comfortable"
            hide-details
            control-variant="hidden"
            class="no-border-radius-right"
            autocomplete="off"
            :error-messages="formCotacao.errors.vel_down"
          />
          <v-number-input
            v-model="formCotacao.vel_up"
            :min="0"
            placeholder="Mbps"
            label="Up"
            variant="outlined"
            density="comfortable"
            hide-details
            control-variant="hidden"
            class="no-border-radius"
            autocomplete="off"
            :error-messages="formCotacao.errors.vel_up"
          />
          <v-number-input
            v-model="formCotacao.barra"
            :min="0"
            :max="32"
            label="/"
            variant="outlined"
            density="comfortable"
            hide-details
            control-variant="hidden"
            class="no-border-radius-left"
            autocomplete="off"
            :error-messages="formCotacao.errors.barra"
          />
        </div>
      </v-col>

      <!-- Divisor Serviços Adicionais -->
      <v-col cols="12" class="py-0 my-0 mt-3">
        <v-divider>
          <span class="text-caption text-medium-emphasis px-2">Serviços Adicionais</span>
        </v-divider>
      </v-col>

      <!-- Lista de serviços adicionais -->
      <v-col cols="12" class="py-0 my-0">
        <v-card
          v-for="(servico, index) in servicosAdicionais"
          :key="index"
          class="mb-2"
          elevation="0"
          rounded="lg"
          border
        >
          <v-card-text class="pa-3">
            <v-row dense>
              <v-col cols="12" md="3">
                <v-autocomplete
                  v-model="servico.servico_id"
                  :items="servicos"
                  item-title="nome"
                  item-value="id"
                  label="Serviço"
                  :return-object="false"
                  variant="outlined"
                  density="compact"
                  hide-details
                />
              </v-col>

              <v-col cols="12" md="3">
                <div class="d-flex">
                  <v-number-input
                    v-model="servico.vel_down"
                    :min="0"
                    placeholder="Mbps"
                    label="Down"
                    variant="outlined"
                    density="compact"
                    hide-details
                    control-variant="hidden"
                    class="no-border-radius-right"
                  />
                  <v-number-input
                    v-model="servico.vel_up"
                    :min="0"
                    placeholder="Mbps"
                    label="Up"
                    variant="outlined"
                    density="compact"
                    hide-details
                    control-variant="hidden"
                    class="no-border-radius"
                  />
                  <v-number-input
                    v-model="servico.barra"
                    :min="0"
                    :max="32"
                    label="/"
                    variant="outlined"
                    density="compact"
                    hide-details
                    control-variant="hidden"
                    class="no-border-radius-left"
                  />
                </div>
              </v-col>

              <v-col cols="12" md="2">
                <v-number-input
                  v-model="servico.adesao"
                  :min="0"
                  label="Adesão"
                  prefix="R$"
                  variant="outlined"
                  density="compact"
                  hide-details
                  control-variant="hidden"
                  @update:modelValue="recalcularTotais"
                />
              </v-col>

              <v-col cols="12" md="2">
                <v-number-input
                  v-model="servico.mensalidade"
                  :min="0"
                  label="Mensalidade"
                  prefix="R$"
                  variant="outlined"
                  density="compact"
                  hide-details
                  control-variant="hidden"
                  @update:modelValue="recalcularTotais"
                />
              </v-col>

              <v-col cols="12" md="2">
                <v-text-field
                  v-model="servico.obs"
                  label="Observação"
                  variant="outlined"
                  density="compact"
                  hide-details
                />
              </v-col>

              <v-col cols="12" md="1" class="d-flex align-center">
                <v-btn
                  icon="mdi-minus"
                  size="small"
                  color="error"
                  variant="text"
                  @click="removerServico(index)"
                />
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>

        <div class="d-flex gap-2 mt-1">
          <v-btn
            variant="outlined"
            color="primary"
            prepend-icon="mdi-plus"
            size="small"
            @click="adicionarServico"
          >
            Adicionar Serviço
          </v-btn>

          <v-btn
            v-if="servicosAdicionais.length > 0"
            variant="tonal"
            color="secondary"
            size="small"
            @click="somarServicosAdesao"
          >
            Somar adesões
          </v-btn>

          <v-btn
            v-if="servicosAdicionais.length > 0"
            variant="tonal"
            color="secondary"
            size="small"
            @click="somarServicosMensal"
          >
            Somar mensalidades
          </v-btn>
        </div>
      </v-col>

      <!-- Divisor Valores Fornecedor -->
      <v-col cols="12" class="py-0 my-0 mt-3">
        <v-divider>
          <span class="text-caption text-medium-emphasis px-2">Valores do Fornecedor</span>
        </v-divider>
      </v-col>

      <v-col cols="12" md="3" class="py-0 my-0">
        <v-number-input
          v-model="formCotacao.adesao_fornecedor"
          :min="0"
          label="Adesão do Fornecedor"
          prefix="R$"
          variant="outlined"
          density="comfortable"
          hide-details
          control-variant="hidden"
          autocomplete="off"
          :error-messages="formCotacao.errors.adesao_fornecedor"
          @update:modelValue="recalcularTotais"
        />
      </v-col>

      <v-col cols="12" md="3" class="py-0 my-0">
        <v-number-input
          v-model="formCotacao.mensal_fornecedor"
          :min="0"
          label="Mensalidade do Fornecedor"
          prefix="R$"
          variant="outlined"
          density="comfortable"
          hide-details
          control-variant="hidden"
          autocomplete="off"
          :error-messages="formCotacao.errors.mensal_fornecedor"
          @update:modelValue="recalcularTotais"
        />
      </v-col>

      <v-col cols="12" md="3" class="py-0 my-0">
        <v-number-input
          v-model="formCotacao.custo_ativacao"
          :min="0"
          label="Custo de Ativação"
          prefix="R$"
          variant="outlined"
          density="comfortable"
          hide-details
          control-variant="hidden"
          autocomplete="off"
          :error-messages="formCotacao.errors.custo_ativacao"
          @update:modelValue="recalcularTotais"
        />
      </v-col>

      <!-- Seção Precificação (pode precificar) -->
      <template v-if="podePrecificar">

        <v-col cols="12" class="py-0 my-0 mt-3">
          <v-divider>
            <span class="text-caption text-medium-emphasis px-2">Precificação IMP</span>
          </v-divider>
        </v-col>

        <v-col cols="12" md="4" class="py-0 my-0">
          <v-number-input
            v-model="formCotacao.mensal_imp"
            :min="0"
            label="Cobrança Mensal c/ Imposto"
            prefix="R$"
            variant="outlined"
            density="comfortable"
            hide-details
            control-variant="hidden"
            autocomplete="off"
            :error-messages="formCotacao.errors.mensal_imp"
            @update:modelValue="recalcularTotais"
          />
        </v-col>

        <v-col cols="12" md="4" class="py-0 my-0">
          <v-number-input
            v-model="formCotacao.custo_instalacao_imp"
            :min="0"
            label="Custo Instalação c/ Imposto"
            prefix="R$"
            variant="outlined"
            density="comfortable"
            hide-details
            control-variant="hidden"
            autocomplete="off"
            :error-messages="formCotacao.errors.custo_instalacao_imp"
            @update:modelValue="recalcularTotais"
          />
        </v-col>

        <v-col cols="12" md="4" class="py-0 my-0">
          <v-text-field
            v-model="formCotacao.prazo_instalacao"
            label="Prazo de Instalação"
            variant="outlined"
            density="comfortable"
            hide-details
            autocomplete="off"
            :error-messages="formCotacao.errors.prazo_instalacao"
          />
        </v-col>

        <!-- Calculados (readonly) -->
        <v-col cols="12" class="py-0 my-0 mt-3">
          <v-divider>
            <span class="text-caption text-medium-emphasis px-2">Calculados</span>
          </v-divider>
        </v-col>

        <v-col cols="12" md="2" class="py-0 my-0">
          <v-number-input
            v-model="formCotacao.imposto_mensal"
            label="Imposto Mensal"
            prefix="R$"
            variant="outlined"
            density="comfortable"
            hide-details
            control-variant="hidden"
            readonly
          />
        </v-col>

        <v-col cols="12" md="2" class="py-0 my-0">
          <v-number-input
            v-model="formCotacao.imposto_adesao"
            label="Imposto Adesão"
            prefix="R$"
            variant="outlined"
            density="comfortable"
            hide-details
            control-variant="hidden"
            readonly
          />
        </v-col>

        <v-col cols="12" md="2" class="py-0 my-0">
          <v-number-input
            v-model="formCotacao.custo_operacional"
            label="Custo Operacional"
            prefix="R$"
            variant="outlined"
            density="comfortable"
            hide-details
            control-variant="hidden"
            readonly
          />
        </v-col>

        <v-col cols="12" md="2" class="py-0 my-0">
          <v-number-input
            v-model="formCotacao.custo_fixo"
            label="Custo Fixo"
            prefix="R$"
            variant="outlined"
            density="comfortable"
            hide-details
            control-variant="hidden"
            readonly
          />
        </v-col>

        <v-col cols="12" md="2" class="py-0 my-0">
          <v-number-input
            v-model="formCotacao.lucro_adesao"
            label="Lucro Adesão"
            prefix="R$"
            variant="outlined"
            density="comfortable"
            hide-details
            control-variant="hidden"
            readonly
          />
        </v-col>

        <v-col cols="12" md="2" class="py-0 my-0">
          <v-number-input
            v-model="formCotacao.lucro_liquido"
            label="Lucro Líquido"
            prefix="R$"
            variant="outlined"
            density="comfortable"
            hide-details
            control-variant="hidden"
            readonly
          />
        </v-col>

      </template>

    </v-row>
  </v-form>
</template>

<script setup>
import { ref, watch, nextTick } from 'vue'
import { useForm } from '@inertiajs/vue3'
import axios from 'axios'

// -------------------------------------------------------
// Props
// -------------------------------------------------------
const props = defineProps({
  servicos:         { type: Array,   required: true },
  tecnologiaOpcoes: { type: Array,   required: true },
  statusOpcoes:     { type: Array,   required: true },
  podePrecificar:   { type: Boolean, default: false },
  imposto:          { type: Number,  default: 0      }, // orcamento.imposto (%)
  gearNoc:          { type: Number,  default: 0      }, // empresa.gear_noc
  custoFixoPercent: { type: Number,  default: 0      }, // empresa.custo_fixo_percent
  // cotação inicial para edição (opcional)
  cotacao:          { type: Object,  default: null   },
  fornecedoresDisponiveis: { type: Array, default: () => [] },
  gearNoc:          { type: Number, required: true,  },
  custoFixoPercent: { type: Number, required: true,  },
})

// -------------------------------------------------------
// Form
// -------------------------------------------------------
const form = ref(null) // ref do v-form para validação

const formCotacao = useForm({
  id:                          props.cotacao?.id                          ?? null,
  site_orcamento_id:           props.cotacao?.site_orcamento_id           ?? null,
  fornecedor_id:               props.cotacao?.fornecedor_id               ?? null,
  status:                      props.cotacao?.status                      ?? null,
  tecnologia:                  props.cotacao?.tecnologia                  ?? null,
  vel_down:                    props.cotacao?.vel_down                    ?? 0,
  vel_up:                      props.cotacao?.vel_up                      ?? 0,
  barra:                       props.cotacao?.barra                       ?? 0,
  adesao_fornecedor:           props.cotacao?.adesao_fornecedor           ?? 0,
  mensal_fornecedor:           props.cotacao?.mensal_fornecedor           ?? 0,
  custo_ativacao:              props.cotacao?.custo_ativacao              ?? 8500,
  prazo_instalacao_fornecedor: props.cotacao?.prazo_instalacao_fornecedor ?? '',
  // precificação
  mensal_imp:                  props.cotacao?.mensal_imp                  ?? 0,
  custo_instalacao_imp:        props.cotacao?.custo_instalacao_imp        ?? 0,
  prazo_instalacao:            props.cotacao?.prazo_instalacao            ?? '',
  // calculados
  imposto_mensal:              props.cotacao?.imposto_mensal              ?? 0,
  imposto_adesao:              props.cotacao?.imposto_adesao              ?? 0,
  custo_operacional:           props.cotacao?.custo_operacional           ?? 0,
  custo_fixo:                  props.cotacao?.custo_fixo                  ?? 0,
  lucro_adesao:                props.cotacao?.lucro_adesao                ?? 0,
  lucro_liquido:               props.cotacao?.lucro_liquido               ?? 0,
})

const fornecedores = ref([...props.fornecedoresDisponiveis])
const fornecedorSelecionado = ref(props.cotacao?.fornecedor_id ?? null)
const loadingFornecedores = ref(false)

async function buscarFornecedores(val) {
  if (!val || val.length < 2) return
  loadingFornecedores.value = true
  const { data } = await axios.get(route('fornecedor.getFornecedores'), { params: { nome: val } })
  fornecedores.value = data
  loadingFornecedores.value = false
}

// -------------------------------------------------------
// Serviços adicionais
// -------------------------------------------------------
const servicosAdicionais = ref(
  props.cotacao?.servicos_adicionais ?? []
)

function adicionarServico() {
  servicosAdicionais.value.push({
    servico_id:  null,
    vel_down:    0,
    vel_up:      0,
    barra:       0,
    adesao:      0,
    mensalidade: 0,
    obs:         '',
  })
}

function removerServico(index) {
  servicosAdicionais.value.splice(index, 1)
  recalcularTotais()
}

function somarServicosAdesao() {
  const soma = servicosAdicionais.value.reduce((acc, s) => acc + (Number(s.adesao) || 0), 0)
  formCotacao.adesao_fornecedor = parseFloat(soma.toFixed(2))
  recalcularTotais()
}

function somarServicosMensal() {
  const soma = servicosAdicionais.value.reduce((acc, s) => acc + (Number(s.mensalidade) || 0), 0)
  formCotacao.mensal_fornecedor = parseFloat(soma.toFixed(2))
  recalcularTotais()
}

// -------------------------------------------------------
// Cálculo automático (espelho do jQuery do blade)
// -------------------------------------------------------
function recalcularTotais() {
  if (!props.podePrecificar) return

  const porcent           = props.imposto / 100
  const mensal_forn       = Number(formCotacao.mensal_fornecedor)   || 0
  const adesao_forn       = Number(formCotacao.adesao_fornecedor)   || 0
  const mensal_imp        = Number(formCotacao.mensal_imp)          || 0
  const custo_inst_imp    = Number(formCotacao.custo_instalacao_imp)|| 0
  const custo_ativacao    = Number(formCotacao.custo_ativacao)      || 0
  
  const imposto_mensal    = mensal_imp * porcent
  const imposto_adesao    = custo_inst_imp * porcent
  const custo_operacional = mensal_forn + props.gearNoc
  const custo_fixo        = mensal_imp * (props.custoFixoPercent / 100)
  const lucro_liquido     = mensal_imp - custo_fixo - custo_operacional - imposto_mensal
  const lucro_adesao      = custo_inst_imp - adesao_forn - imposto_adesao

  formCotacao.lucro_liquido     = parseFloat(lucro_liquido.toFixed(2))
  formCotacao.imposto_mensal    = parseFloat(imposto_mensal.toFixed(2))
  formCotacao.imposto_adesao    = parseFloat(imposto_adesao.toFixed(2))
  formCotacao.lucro_adesao      = parseFloat(lucro_adesao.toFixed(2))
  formCotacao.custo_operacional = parseFloat(custo_operacional.toFixed(2))
  formCotacao.custo_fixo        = parseFloat(custo_fixo.toFixed(2))
}

// recalcula automaticamente quando os campos de precificação mudam
watch(
  () => [
    formCotacao.mensal_imp,
    formCotacao.custo_instalacao_imp,
    formCotacao.mensal_fornecedor,
    formCotacao.adesao_fornecedor,
  ],
  recalcularTotais
)

// -------------------------------------------------------
// Interface pública (igual ao FormSiteOrcamento)
// -------------------------------------------------------
async function validateForm() {
  const { valid } = await form.value.validate()
  return valid // retorna boolean direto, igual ao FormSiteOrcamento
}

function getData() {
  return {
    ...formCotacao.data(),
    servicos_adicionais: servicosAdicionais.value,
    fornecedor_id: fornecedorSelecionado.value?.id ?? null,
  }
}

function reset() {
  form.value?.reset()
  fornecedorSelecionado.value = null
  servicosAdicionais.value = []
  formCotacao.reset() // reset do useForm do Inertia
}
function populate(dados) {
  Object.keys(dados).forEach(key => {
    if (key in formCotacao) {
      formCotacao[key] = dados[key]
    }
  })
}

defineExpose({ validateForm, getData, reset, formCotacao, populate })
</script>