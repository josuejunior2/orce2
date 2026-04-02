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
          />
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
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import FormSiteOrcamento from '@/Components/FormSiteOrcamento.vue'

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
  const valid = await formRef.value.validate()
  if (!valid) return

  const dados = formRef.value.getData()
  useForm(dados).post(route('site.table.store'), {
    preserveScroll: true,
  })
}
</script>
<!-- <script setup>
import { ref, onMounted } from 'vue'
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
  id_instalacao: null,
  longitude: null,
  endereco: null,
  cidade_id: null,
  vel_solicitada_down: null,
  vel_solicitada_up: null,
  barra: null,
  orcamento_id: null,
  servicos: null,
  site_id: false,
  is_subestacao: false,
  pontas: []
})

const pontas = ref([]);

const adicionarPonta = () => {
  pontas.value.push({
    nome: "",
    latitude: "",
    id_instalacao: "",
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
    novoSite.id_instalacao = site.id_instalacao
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
  errors: Object,
  siteOrcamento: { type: Object, default: null },
  pontas: { type: Object, default: null },
});

onMounted(() => {
  if (props.siteOrcamento) {
    exibeCampos.value = true
    const siteOrc = props.siteOrcamento
    const site = props.siteOrcamento.site
    servicoSelecionado.value = siteOrc.servicos_solicitados
    novoSite.defaults({
      nome: site.nome,
      id_instalacao: site.id_instalacao,
      latitude: site.latitude,
      longitude: site.longitude,
      endereco: site.endereco,
      cidade_id: site.cidade_id,
      vel_solicitada_down: siteOrc.vel_solicitada_down ? Number(siteOrc.vel_solicitada_down) : null,
      vel_solicitada_up: siteOrc.vel_solicitada_up ? Number(siteOrc.vel_solicitada_up) : null,
      barra: siteOrc.barra ? Number(siteOrc.barra) : null,
      is_subestacao: Boolean(site.is_subestacao),
      servicos: siteOrc.servicos_solicitados?.map(s => s.id) ?? [],
      pontas: []
    })

    novoSite.reset()
  }

  if (props.pontas) {
    const pontasFormatadas = props.pontas.map(p => ({
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
    })) ?? []

    novoSite.pontas = pontasFormatadas
    pontas.value = pontasFormatadas
    novoSite.reset()
  }
})

const submitForm = async () => {
  novoSite.orcamento_id = props.orcamento.id
  novoSite.servicos = servicoSelecionado.value
  novoSite.site_id = siteSelecionado.value != null ? siteSelecionado.value.id : null
  novoSite.pontas = pontas
  const valid = await form.value.validate()

  if (valid.valid) {
    if (props.siteOrcamento) {
      novoSite.post(route('siteOrcamento.update', props.siteOrcamento.id), {
        onSuccess: () => {
          showSuccess.value = true
          resetarFormulario()
        },
        onError: tratarErros
      })
    } else {
      novoSite.post(route('siteOrcamento.store'), {
        onSuccess: () => {
          showSuccess.value = true
          resetarFormulario()
        },
        onError: tratarErros
      })
    }
  } else {
    console.log('Validação do frontend falhou')
  }
}

const resetarFormulario = () => {
  novoSite.reset()
  exibeCampos.value = false
  siteSelecionado.value = null
  servicoSelecionado.value = null
  latConverted.value = ''
  lonConverted.value = ''
}

const tratarErros = (errors) => {
  if (Object.keys(errors).length > 0) {
    const firstError = Object.values(errors)[0]
    errorMessage.value = Array.isArray(firstError) ? firstError[0] : firstError
    showError.value = true
  }
}
</script> -->

