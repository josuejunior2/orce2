<template>
  <v-dialog v-model="dialogFormSite" max-width="900" scrollable>
    <v-card :title="`${isEditing ? 'Editar' : 'Cadastrar'} Site`">
      <template v-slot:text>
        <FormSiteOrcamento
          ref="formRef"
          :cidades="props.cidades"
          :servicos="props.servicos"
          :orcamento="props.orcamento"
        />
      </template>

      <v-card-actions class="bg-surface-light">
        <v-btn text="Fechar" variant="plain" @click="fechar" />
        <v-spacer />
        <v-btn
          text="Salvar"
          color="primary"
          :loading="formRef?.novoSite?.processing"
          @click="submit"
        />
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { ref, computed } from 'vue'
import FormSiteOrcamento from '../Components/FormSiteOrcamento.vue'

const props = defineProps({
  cidades: { type: Array, required: true },
  servicos: { type: Array, required: true },
  orcamento: Object,
})

const emit = defineEmits(['salvo'])

const dialogFormSite = ref(false)
const formRef = ref(null)
const siteParaEditar = ref(null)
const isEditing = computed(() => !!siteParaEditar.value)

function abrirCadastro() {
  siteParaEditar.value = null
  dialogFormSite.value = true
}

function abrirEdicao(site) {
  siteParaEditar.value = site
  dialogFormSite.value = true
}

function fechar() {
  dialogFormSite.value = false
  siteParaEditar.value = null
  formRef.value?.reset()
}

const tratarErros = (errors) => {
  if (Object.keys(errors).length > 0) {
    const firstError = Object.values(errors)[0]
    errorMessage.value = Array.isArray(firstError) ? firstError[0] : firstError
    showError.value = true
  }
}

async function submit() {
  const valid = await formRef.value.validateForm()
  if (!valid) {
    return
  }

  const novoSite = formRef.value.novoSite
  novoSite.servicos = formRef.value.getData().servicos
  novoSite.site_id = formRef.value.getData().site_id
  novoSite.pontas = formRef.value.getData().pontas

  if (isEditing.value) {
    novoSite.post(route('siteOrcamento.update', novoSite.id), {
      onSuccess: () => {
        fechar()
      },
      onError: tratarErros
    })
  } else {
    novoSite.post(route('siteOrcamento.store'), {
      onSuccess: () => {
        fechar()
      },
      onError: tratarErros
    })
  }
}

defineExpose({ abrirCadastro, abrirEdicao })
</script>