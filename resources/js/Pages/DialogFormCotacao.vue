<template>
  <v-dialog v-model="dialogFormCotacao" max-width="2000" max-height="5000" scrollable>
    <v-card>
      <v-card-title>{{ isEditingCotacao ? 'Editar' : 'Cadastrar' }} Cotação</v-card-title>
      <v-card-text class="pt-4">
        <FormCotacao
          ref="formCotacao"
          v-model="form"
          :fornecedoresDisponiveis="fornecedoresDisponiveis"
          :tecnologia-opcoes="tecnologiaOpcoes"
          :status-opcoes="statusOpcoes"
          :pode-precificar="podePrecificar"
        />
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
import FormCotacao from '../Components/FormCotacao.vue'
import axios from 'axios'

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