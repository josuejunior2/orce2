<template>
  <v-dialog v-model="dialogFormCotacao" max-width="2000" max-height="5000" scrollable>
    <v-card>
      <v-card-title>{{ isEditingCotacao ? 'Editar' : 'Cadastrar' }} Cotação</v-card-title>
      <v-card-text class="pt-4">
        <FormCotacao
          ref="formCotacao"
          v-if="formMontado"
          :fornecedoresDisponiveis="fornecedoresDisponiveis"
          :tecnologia-opcoes="tecnologiaOpcoes"
          :status-opcoes="statusOpcoes"
          :pode-precificar="podePrecificar"
          :servicos="servicos"
          :gear-noc="gearNoc"
          :custo-fixo-percent="custoFixoPercent"
          :imposto="imposto"
          :lucro-mensal-total="lucroMensalTotal"
          :adesao-total="adesaoTotal"
        />
      </v-card-text>

      <v-card-actions class="bg-surface-light">
        <v-btn text="Fechar" variant="plain" @click="fechar" />
        <v-spacer />
        <v-btn
          text="Salvar"
          color="primary"
          :loading="formCotacao?.formCotacao?.processing"
          @click="submitFormCotacao"
        />
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { ref, nextTick } from 'vue'
import FormCotacao from '../Components/FormCotacao.vue'
import axios from 'axios'

const props = defineProps({
  tecnologiaOpcoes: { type: Array, required: true },
  statusOpcoes:     { type: Array, required: true },
  podePrecificar:   { type: Boolean, default: false },
  servicos:         { type: Array, required: true },
  gearNoc:          { type: Number, required: true },
  custoFixoPercent: { type: Number, required: true },
  imposto:          { type: Number, required: true },
  lucroMensalTotal: { type: Number, required: false },
  adesaoTotal:      { type: Number, required: false },
})

const dialogFormCotacao  = ref(false)
const isEditingCotacao   = ref(false)
const formCotacao        = ref(null) // ref do componente <FormCotacao>
const fornecedoresDisponiveis = ref([])
const formMontado = ref(false)

// -------------------------------------------------------
// Expõe para o pai chamar via template ref
// -------------------------------------------------------
async function abrirCadastro(siteOrcamentoId, cidadeId) {
  isEditingCotacao.value = false
  try {
    const { data } = await axios.get(route('fornecedor.getFornecedoresDisponiveis'), {
      params: { cidade_id: cidadeId }
    })
    fornecedoresDisponiveis.value = data.fornecedores
  } catch (e) {
    console.error('Erro ao buscar fornecedores:', e)
  }
  dialogFormCotacao.value = true
  // popula o filho depois que o diálogo (e o componente) já estão montados
  await nextTick()
  formMontado.value = true
  await nextTick()
  formCotacao.value.populate({ site_orcamento_id: siteOrcamentoId })
}

function abrirEdicao(cotacao) {
  isEditingCotacao.value = true
  dialogFormCotacao.value = true
  nextTick(() => formCotacao.value.populate(cotacao))
}

async function submitFormCotacao() {
  const valid = await formCotacao.value.validateForm()
  if (!valid) return

  const dados = formCotacao.value.getData()
  const inertiaForm = formCotacao.value.formCotacao

  inertiaForm.fornecedor_id = dados.fornecedor_id
  inertiaForm.servicos      = dados.servicos_adicionais

  if (isEditingCotacao.value) {
    inertiaForm.put(route('cotacao.update', inertiaForm.id), {
      preserveScroll: true,
      onSuccess: fechar,
    })
  } else {
    inertiaForm.post(route('cotacao.store'), {
      preserveScroll: true,
      onSuccess: fechar,
      onError: (errors) => {
        console.error('Erro ao salvar cotação:', errors)
      }
    })
  }
}

function fechar() {
  dialogFormCotacao.value = false
  formCotacao.value?.reset()
}

defineExpose({ abrirCadastro, abrirEdicao })
</script>