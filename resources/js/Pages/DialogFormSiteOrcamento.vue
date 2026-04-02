<template>
  <v-dialog v-model="dialogFormSite" max-width="900" scrollable>
    <v-card :title="`${isEditing ? 'Editar' : 'Cadastrar'} Site`">
      <template v-slot:text>
        <FormSiteOrcamento
          ref="formRef"
          :cidades="cidades"
          :servicos="servicos"
          :site="siteParaEditar"
        />
      </template>

      <v-card-actions class="bg-surface-light">
        <v-btn text="Fechar" variant="plain" @click="fechar" />
        <v-spacer />
        <v-btn text="Salvar" color="primary" @click="submit" />
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import FormSiteOrcamento from '../Components/FormSiteOrcamento.vue'

const props = defineProps({
  cidades: { type: Array, required: true },
  servicos: { type: Array, required: true },
  orcamentoId: { type: Number, required: true },
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

async function submit() {
  const valid = await formRef.value.validate()
  if (!valid) return

  const dados = formRef.value.getData()
  const form = useForm({ ...dados, orcamento_id: props.orcamentoId })

  if (isEditing.value) {
    form.put(route('site.table.update', dados.id), {
      preserveScroll: true,
      onSuccess: () => { fechar(); emit('salvo') },
    })
  } else {
    form.post(route('site.table.store'), {
      preserveScroll: true,
      onSuccess: () => { fechar(); emit('salvo') },
    })
  }
}

defineExpose({ abrirCadastro, abrirEdicao })
</script>