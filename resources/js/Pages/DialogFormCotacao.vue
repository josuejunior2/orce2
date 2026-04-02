<template>
  <v-dialog v-model="dialogFormCotacao" max-width="2000" max-height="5000" scrollable>
    <v-card>
      <v-card-title>{{ isEditingCotacao ? 'Editar' : 'Cadastrar' }} Cotação</v-card-title>
      <v-card-text class="pt-4">
        <v-form v-if="form" ref="formCotacao">
          <v-row>

            <!-- Fornecedor -->
            <v-col cols="12" md="2" class="py-0 my-0">
              <v-autocomplete
                v-model="form.fornecedor_id"
                :items="props.fornecedores"
                item-title="nome"
                item-value="id"
                label="Fornecedor"
                :return-object="false"
                variant="outlined"
                density="comfortable"
                :rules="[v => !!v || 'Fornecedor é obrigatório']"
                :error-messages="form.errors.fornecedor_id"
                required
              />
            </v-col>

            <!-- Status -->
            <v-col cols="12" md="2" class="py-0 my-0">
              <v-select
                v-model="form.status"
                :items="props.statusOpcoes"
                item-title="label"
                item-value="value"
                label="Status da Cotação"
                variant="outlined"
                density="comfortable"
                :rules="[v => !!v || 'Status é obrigatório']"
                :error-messages="form.errors.status"
                required
              />
            </v-col>

            <!-- Prazo instalação fornecedor -->
            <v-col cols="12" md="2" class="py-0 my-0">
              <v-text-field
                v-model="form.prazo_instalacao_fornecedor"
                label="Prazo de Instalação Fornecedor"
                variant="outlined"
                density="comfortable"
                :rules="[v => !!v || 'Campo obrigatório']"
                :error-messages="form.errors.prazo_instalacao_fornecedor"
                autocomplete="off"
              />
            </v-col>

            <!-- Tecnologia -->
            <v-col cols="12" md="3" class="py-0 my-0 align-self-center">
              <v-radio-group
                v-model="form.tecnologia"
                inline
                hide-details
                :rules="[v => !!v || 'Tecnologia é obrigatória']"
                :error-messages="form.errors.tecnologia"
              >
                <v-radio
                  v-for="tec in props.tecnologiaOpcoes"
                  :key="tec.value"
                  :label="tec.label"
                  :value="tec.value"
                  class="mr-4"
                />
              </v-radio-group>
            </v-col>

            <!-- Velocidades -->
            <v-col cols="12" md="3" class="py-0 my-0 mt-3">
              <div class="d-flex">
                <v-number-input
                  v-model="form.vel_down"
                  :min="0"
                  placeholder="Mbps"
                  label="Down"
                  variant="outlined"
                  density="comfortable"
                  hide-details
                  control-variant="hidden"
                  class="no-border-radius-right"
                  autocomplete="off"
                  :error-messages="form.errors.vel_down"
                />
                <v-number-input
                  v-model="form.vel_up"
                  :min="0"
                  placeholder="Mbps"
                  label="Up"
                  variant="outlined"
                  density="comfortable"
                  hide-details
                  control-variant="hidden"
                  class="no-border-radius"
                  autocomplete="off"
                  :error-messages="form.errors.vel_up"
                />
                <v-number-input
                  v-model="form.barra"
                  :min="0"
                  :max="32"
                  label="/"
                  variant="outlined"
                  density="comfortable"
                  hide-details
                  control-variant="hidden"
                  class="no-border-radius-left"
                  autocomplete="off"
                  :error-messages="form.errors.barra"
                />
              </div>
            </v-col>

            <!-- Divisor Valores Fornecedor -->
            <v-col cols="12" class="py-0 my-0 mt-3">
              <v-divider>
                <span class="text-caption text-medium-emphasis px-2">Valores do Fornecedor</span>
              </v-divider>
            </v-col>

            <!-- Adesão Fornecedor -->
            <v-col cols="12" md="4" class="py-0 my-0">
              <v-number-input
                v-model="form.adesao_fornecedor"
                :min="0"
                label="Adesão do Fornecedor"
                prefix="R$"
                variant="outlined"
                density="comfortable"
                hide-details
                control-variant="hidden"
                autocomplete="off"
                :error-messages="form.errors.adesao_fornecedor"
              />
            </v-col>

            <!-- Mensalidade Fornecedor -->
            <v-col cols="12" md="4" class="py-0 my-0">
              <v-number-input
                v-model="form.mensal_fornecedor"
                :min="0"
                label="Mensalidade do Fornecedor"
                prefix="R$"
                variant="outlined"
                density="comfortable"
                hide-details
                control-variant="hidden"
                autocomplete="off"
                :error-messages="form.errors.mensal_fornecedor"
              />
            </v-col>

            <!-- Custo Ativação -->
            <v-col cols="12" md="4" class="py-0 my-0">
              <v-number-input
                v-model="form.custo_ativacao"
                :min="0"
                label="Custo de Ativação"
                prefix="R$"
                variant="outlined"
                density="comfortable"
                hide-details
                control-variant="hidden"
                autocomplete="off"
                :error-messages="form.errors.custo_ativacao"
              />
            </v-col>

            <!-- Divisor Precificação (só aparece se pode precificar) -->
            <template v-if="props.podePrecificar">

              <v-col cols="12" class="py-0 my-0 mt-3">
                <v-divider>
                  <span class="text-caption text-medium-emphasis px-2">Precificação IMP</span>
                </v-divider>
              </v-col>

              <!-- Mensal IMP -->
              <v-col cols="12" md="4" class="py-0 my-0">
                <v-number-input
                  v-model="form.mensal_imp"
                  :min="0"
                  label="Cobrança Mensal c/ Imposto"
                  prefix="R$"
                  variant="outlined"
                  density="comfortable"
                  hide-details
                  control-variant="hidden"
                  autocomplete="off"
                  :error-messages="form.errors.mensal_imp"
                />
              </v-col>

              <!-- Custo Instalação IMP -->
              <v-col cols="12" md="4" class="py-0 my-0">
                <v-number-input
                  v-model="form.custo_instalacao_imp"
                  :min="0"
                  label="Custo Instalação c/ Imposto"
                  prefix="R$"
                  variant="outlined"
                  density="comfortable"
                  hide-details
                  control-variant="hidden"
                  autocomplete="off"
                  :error-messages="form.errors.custo_instalacao_imp"
                />
              </v-col>

              <!-- Prazo instalação IMP -->
              <v-col cols="12" md="4" class="py-0 my-0">
                <v-text-field
                  v-model="form.prazo_instalacao"
                  label="Prazo de Instalação"
                  variant="outlined"
                  density="comfortable"
                  hide-details
                  autocomplete="off"
                  :error-messages="form.errors.prazo_instalacao"
                />
              </v-col>

              <!-- Divisor Calculados -->
              <v-col cols="12" class="py-0 my-0 mt-3">
                <v-divider>
                  <span class="text-caption text-medium-emphasis px-2">Calculados (somente leitura)</span>
                </v-divider>
              </v-col>

              <!-- Imposto Mensal -->
              <v-col cols="12" md="4" class="py-0 my-0">
                <v-number-input
                  v-model="form.imposto_mensal"
                  label="Imposto Mensal"
                  prefix="R$"
                  variant="outlined"
                  density="comfortable"
                  hide-details
                  control-variant="hidden"
                  readonly
                  bg-color="surface-variant"
                />
              </v-col>

              <!-- Imposto Adesão -->
              <v-col cols="12" md="4" class="py-0 my-0">
                <v-number-input
                  v-model="form.imposto_adesao"
                  label="Imposto Adesão"
                  prefix="R$"
                  variant="outlined"
                  density="comfortable"
                  hide-details
                  control-variant="hidden"
                  readonly
                  bg-color="surface-variant"
                />
              </v-col>

              <!-- Custo Operacional -->
              <v-col cols="12" md="4" class="py-0 my-0">
                <v-number-input
                  v-model="form.custo_operacional"
                  label="Custo Operacional"
                  prefix="R$"
                  variant="outlined"
                  density="comfortable"
                  hide-details
                  control-variant="hidden"
                  readonly
                  bg-color="surface-variant"
                />
              </v-col>

              <!-- Custo Fixo -->
              <v-col cols="12" md="4" class="py-0 my-0">
                <v-number-input
                  v-model="form.custo_fixo"
                  label="Custo Fixo"
                  prefix="R$"
                  variant="outlined"
                  density="comfortable"
                  hide-details
                  control-variant="hidden"
                  readonly
                  bg-color="surface-variant"
                />
              </v-col>

              <!-- Lucro Adesão -->
              <v-col cols="12" md="4" class="py-0 my-0">
                <v-number-input
                  v-model="form.lucro_adesao"
                  label="Lucro Adesão"
                  prefix="R$"
                  variant="outlined"
                  density="comfortable"
                  hide-details
                  control-variant="hidden"
                  readonly
                  bg-color="surface-variant"
                />
              </v-col>

              <!-- Lucro Líquido -->
              <v-col cols="12" md="4" class="py-0 my-0">
                <v-number-input
                  v-model="form.lucro_liquido"
                  label="Lucro Líquido"
                  prefix="R$"
                  variant="outlined"
                  density="comfortable"
                  hide-details
                  control-variant="hidden"
                  readonly
                  bg-color="surface-variant"
                />
              </v-col>

            </template>

          </v-row>
        </v-form>
      </v-card-text>

      <v-card-actions class="bg-surface-light">
        <v-btn text="Fechar" variant="plain" @click="fechar" />
        <v-spacer />
        <v-btn
          v-if="form"
          text="Salvar"
          color="primary"
          :loading="form.processing"
          @click="submitFormCotacao"
        />
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

// -------------------------------------------------------
// Props
// -------------------------------------------------------
const props = defineProps({
  fornecedores: {
    type: Array,
    required: true,
    // [{ id: 1, nome: 'Vivo' }, ...]
  },
  tecnologiaOpcoes: {
    type: Array,
    required: true,
    // [{ value: 1, label: 'Fibra Óptica' }, ...]
    // Monte isso no controller e passe via Inertia::share ou direto na page
  },
  statusOpcoes: {
    type: Array,
    required: true,
    // [{ value: 1, label: 'Em análise' }, ...]
  },
  podePrecificar: {
    type: Boolean,
    default: false,
    // equivalente ao @can('precificar orcamento') do blade
  },
})

// -------------------------------------------------------
// Estado interno
// -------------------------------------------------------
const dialogFormCotacao = ref(false)
const isEditingCotacao = ref(false)
const formCotacao = ref(null) // ref do v-form para validação

const form = ref(null)

function novoForm(siteOrcamentoId) {
  return useForm({
    site_orcamento_id: siteOrcamentoId,
    fornecedor_id: null,
    status: null,
    tecnologia: null,
    vel_down: 0,
    vel_up: 0,
    barra: 0,
    adesao_fornecedor: 0,
    mensal_fornecedor: 0,
    custo_ativacao: 8500,
    prazo_instalacao_fornecedor: '',
    // precificação
    mensal_imp: 0,
    custo_instalacao_imp: 0,
    prazo_instalacao: '',
    // calculados (readonly)
    imposto_mensal: 0,
    imposto_adesao: 0,
    custo_operacional: 0,
    custo_fixo: 0,
    lucro_adesao: 0,
    lucro_liquido: 0,
  })
}

// -------------------------------------------------------
// Expõe para o pai chamar via template ref
// -------------------------------------------------------
function abrirCadastro(siteOrcamentoId) {
  isEditingCotacao.value = false
  form.value = novoForm(siteOrcamentoId)
  dialogFormCotacao.value = true
}

function abrirEdicao(cotacao) {
  isEditingCotacao.value = true
  form.value = useForm({ ...cotacao })
  dialogFormCotacao.value = true
}

function fechar() {
  dialogFormCotacao.value = false
  form.value = null
}

async function submitFormCotacao() {
  const { valid } = await formCotacao.value.validate()
  if (!valid) return

  if (isEditingCotacao.value) {
    form.value.put(route('cotacao.update', form.value.id), {
      preserveScroll: true,
      onSuccess: fechar,
    })
  } else {
    form.value.post(route('cotacao.store'), {
      preserveScroll: true,
      onSuccess: fechar,
    })
  }
}

defineExpose({ abrirCadastro, abrirEdicao })
</script>