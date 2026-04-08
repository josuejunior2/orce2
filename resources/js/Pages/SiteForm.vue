<template>
  <v-snackbar v-model="showSuccess" color="success" timeout="7000">
    Site criado com sucesso!
  </v-snackbar>

  <v-snackbar v-model="showError" color="error" timeout="7000">
    {{ errorMessage }}
  </v-snackbar>
  
  <v-row no-gutters>
    <v-col cols="12" class="tabler-datagrid">
      <v-card class="ma-3 mt-4 mb-1" elevation="0" rounded="lg" border>
        <!-- Cabeçalho -->
        <v-card-item class="border-b tabler-datagrid pt-6 pb-6 pl-5 pr-8">
          <v-card-title style="font-weight: 400; font-size: 16px;">{{ orcamento.titulo }}</v-card-title>
        </v-card-item>

        <!-- Corpo com data-grid -->
        <v-card-text>
          <v-row dense class="tabler-datagrid mt-2">
            <v-col cols="12" md="4" class="p-2">
                <div class="text-caption text-grey-darken-1 font-weight-bold">RAZÃO SOCIAL DO CLIENTE</div>
                <div>
                  <a href="#" class="text-primary font-weight-regular">{{ orcamento.cliente.nome }}</a>
                </div>
            </v-col>
            <v-col cols="12" md="4" class="p-2">
              <div class="text-caption text-grey-darken-1 font-weight-bold">TEMPO DO CONTRATO</div>
              <div class="font-weight-regular">{{ orcamento.tempo_contrato }} meses</div>
            </v-col>
            <v-col cols="12" md="4" class="p-2">
              <div class="text-caption text-grey-darken-1 font-weight-bold">STATUS</div>
              <div class="font-weight-regular">{{ orcamento.status }}</div>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-col>

    <v-col cols="12">
      <v-card class="ma-3" elevation="0" rounded="lg" border>
        <v-card-item class="border-b mb-4 px-5 py-4">
          <v-card-title style="font-weight: 400; font-size: 16px;">Cadastro de Site</v-card-title>
        </v-card-item>
        <v-card-text>
          <FormSiteOrcamento
            ref="formRef"
            :cidades="props.cidades"
            :servicos="props.servicos"
            :orcamento="props.orcamento"
          />
        </v-card-text>

        <!-- card-footer -->
        <v-card-actions class="bg-grey-lighten-4 justify-end border-t">
          <v-btn type="submit" variant="flat" class="ms-2" color="primary" @click="submit">
            <v-icon start>mdi-plus</v-icon>
            Cadastrar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-col>
  </v-row>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import { useForm } from '@inertiajs/vue3'
import FormSiteOrcamento from '@/Components/FormSiteOrcamento.vue'

onMounted(async () => {
  await nextTick()

  if (formRef.value) {
    formRef.value.setDataFromOrcamento(
      props.siteOrcamento,
      props.pontas
    )
  }
})
const showSuccess = ref(false);
const showError = ref(false);
const errorMessage = ref('');
const props = defineProps({
  orcamento: Object,
  cidades: Array,
  servicos: Array,
  errors: Object,
  siteOrcamento: { type: Object, default: null },
  pontas: { type: Object, default: null },
})

const formRef = ref(null)

async function submit() {
  const valid = await formRef.value.validateForm()
  if (!valid) return
  
  const novoSite = formRef.value.novoSite

  novoSite.servicos = formRef.value.getData().servicos
  novoSite.site_id = formRef.value.getData().site_id
  novoSite.pontas = formRef.value.getData().pontas

  if (props.siteOrcamento) {
    novoSite.post(route('siteOrcamento.update', props.siteOrcamento.id), {
      onSuccess: () => {
        showSuccess.value = true
        formRef.value.reset()
      },
      onError: tratarErros
    })
  } else {
    novoSite.post(route('siteOrcamento.store'), {
      onSuccess: () => {
        showSuccess.value = true
        formRef.value.reset()
      },
      onError: tratarErros
    })
  }
}

const tratarErros = (errors) => {
  if (Object.keys(errors).length > 0) {
    const firstError = Object.values(errors)[0]
    errorMessage.value = Array.isArray(firstError) ? firstError[0] : firstError
    showError.value = true
  }
}
</script>