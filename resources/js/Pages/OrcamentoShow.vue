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
          <v-card-title style="font-weight: 400; font-size: 16px;">
            Lista de Sites
          </v-card-title>
          <v-row class="d-flex justify-md-end">
            <v-col col="12" md="3" class="d-flex justify-md-end">
              <v-btn
                  class="me-2 text-right"
                  prepend-icon="mdi-plus"
                  rounded="lg"
                  text="Novo Site"
                  color="primary"
                  @click="add"
              />
            </v-col>
          </v-row>
        </v-card-item>
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="sitesOrcamento"
            item-value="id"
            show-expand
          >
            <template v-slot:expanded-row="{ columns, item }">
              <tr>
                <td :colspan="columns.length" class="pa-3 bg-grey-lighten-4">
                  <v-data-table
                    :headers="headersCotacoes"
                    :items="item.cotacoes"
                    item-value="id"
                    density="compact"
                    hide-default-footer
                    class="elevation-1"
                  >
                    <template v-slot:top>
                      <v-toolbar density="compact" color="transparent">
                        <v-toolbar-title class="text-subtitle-2">Cotações</v-toolbar-title>
                        <v-spacer />
                        <v-btn
                          size="small"
                          color="primary"
                          prepend-icon="mdi-plus"
                          @click="$refs.dialogCotacao.abrirCadastro(item.id)"
                        >
                          Nova
                        </v-btn>
                      </v-toolbar>
                    </template>
                    <template v-slot:item.selecionar="{ item: cotacao }">
                      <v-radio-group v-model="item.cotacao_selecionada_id" hide-details>
                        <v-checkbox :value="cotacao.id" />
                      </v-radio-group>
                    </template>
                  </v-data-table>
                </td>
              </tr>
            </template>
            <template v-slot:item.fornecedor="{ item }">
              {{ cotacaoSelecionada(item)?.fornecedor ?? '—' }}
            </template>
        
            <template v-slot:item.custo_ativacao="{ item }">
              {{ cotacaoSelecionada(item)?.custo_ativacao ?? '—' }}
            </template>
            <template v-slot:item.velocidade="{ item }">
              {{ cotacaoSelecionada(item)?.velocidade ?? '—' }}
            </template>
            <template v-slot:item.tecnologia="{ item }">
              {{ cotacaoSelecionada(item)?.tecnologia ?? '—' }}
            </template>
            <template v-slot:item.adesao_fornecedor="{ item }">
              {{ cotacaoSelecionada(item)?.adesao_fornecedor ?? '—' }}
            </template>
            <template v-slot:item.custo_operacional="{ item }">
              {{ cotacaoSelecionada(item)?.custo_operacional ?? '—' }}
            </template>
            <template v-slot:item.mensal_imp="{ item }">
              {{ cotacaoSelecionada(item)?.mensal_imp ?? '—' }}
            </template>
            <template v-slot:item.mensal_fornecedor="{ item }">
              {{ cotacaoSelecionada(item)?.mensal_fornecedor ?? '—' }}
            </template>
            <template v-slot:item.custo_instalacao_imp="{ item }">
              {{ cotacaoSelecionada(item)?.custo_instalacao_imp ?? '—' }}
            </template>
            <template v-slot:item.prazo_instalacao="{ item }">
              {{ cotacaoSelecionada(item)?.prazo_instalacao ?? '—' }}
            </template>
            <template v-slot:item.prazo_instalacao_fornecedor="{ item }">
              {{ cotacaoSelecionada(item)?.prazo_instalacao_fornecedor ?? '—' }}
            </template>
            <template v-slot:item.imposto_mensal="{ item }">
              {{ cotacaoSelecionada(item)?.imposto_mensal ?? '—' }}
            </template>
            <template v-slot:item.imposto_adesao="{ item }">
              {{ cotacaoSelecionada(item)?.imposto_adesao ?? '—' }}
            </template>
            <template v-slot:item.lucro_adesao="{ item }">
              {{ cotacaoSelecionada(item)?.lucro_adesao ?? '—' }}
            </template>
            <template v-slot:item.lucro_liquido="{ item }">
              {{ cotacaoSelecionada(item)?.lucro_liquido ?? '—' }}
            </template>
            <template v-slot:item.custo_fixo="{ item }">
              {{ cotacaoSelecionada(item)?.custo_fixo ?? '—' }}
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
  <v-dialog v-model="dialogFormSite" max-width="500">
      <v-card
          :title="`${isEditingSite ? 'Editar' : 'Cadastrar'} Site`"
      >
      <template v-slot:text>
          <v-form @submit.prevent="submitFormSite" ref="form">
              <v-row>
                  <v-col cols="12" class="py-0 my-0">
                      <v-text-field 
                          v-model="formSite.nome" 
                          label="Nome" 
                          variant="outlined"
                          density="comfortable"
                          class="no-border-radius-right"
                          :rules="[v => !!v || 'Nome é obrigatório']"
                          required
                          autocomplete="off"
                      ></v-text-field>
                  </v-col>
                  
                  <v-col cols="12" class="py-0 my-0">
                      <v-checkbox
                          v-model="formSite.is_subestacao"
                          label="Subestação"
                          variant="outlined"
                          density="comfortable"
                          class="py-0 my-0"
                          hide-details
                      />
                  </v-col>

                  <v-col cols="12" class="py-0 my-0">
                      <v-text-field 
                          v-model="formSite.id_instalacao" 
                          label="ID da instalação" 
                          variant="outlined"
                          density="comfortable"
                          class="no-border-radius-right"
                          autocomplete="off"
                      ></v-text-field>
                  </v-col>

                  <v-col cols="12" class="py-0 my-0">
                      <v-text-field 
                          v-model="formSite.endereco" 
                          label="Endereço" 
                          variant="outlined"
                          density="comfortable"
                          class="no-border-radius-right"
                          autocomplete="off"
                      ></v-text-field>
                  </v-col>

                  <v-col cols="12" md="6" class="py-0 my-0">
                      <v-text-field
                          v-model="formSite.latitude" 
                          label="Latitude" 
                          variant="outlined"
                          density="comfortable"
                          class="no-border-radius-right"
                          autocomplete="off"
                      ></v-text-field>
                  </v-col>

                  <v-col cols="12" md="6" class="py-0 my-0">
                      <v-text-field
                          v-model="formSite.longitude" 
                          label="Longitude" 
                          variant="outlined"
                          density="comfortable"
                          class="no-border-radius-right"
                          autocomplete="off"
                      ></v-text-field>
                  </v-col>
                  <v-col cols="12" class="py-0 my-0">
                      <v-autocomplete
                          v-model="formSite.cidade_id"
                          :items="props.cidades"
                          item-title="nome"
                          item-value="id"
                          label="Pesquisar cidade"
                          :return-object="false"
                          variant="outlined"
                          density="comfortable"
                          class="no-border-radius-right"
                          :rules="[v => !!v || 'Cidade é obrigatório']"
                          required
                      />
                  </v-col>
                  <v-col cols="12" sm="12">
                      <div class="d-flex">
                        <v-number-input
                          v-model="formSite.vel_solicitada_down"
                          :min="0"
                          placeholder="Mbps"
                          label="Down"
                          variant="outlined"
                          density="comfortable"
                          hide-details
                          control-variant="hidden"
                          class="no-border-radius-right"
                          autocomplete="off"
                          :error-messages="formSite.errors.vel_solicitada_down"
                        />

                        <v-number-input
                          v-model="formSite.vel_solicitada_up"
                          :min="0"
                          placeholder="Mbps"
                          label="Up"
                          variant="outlined"
                          density="comfortable"
                          hide-details
                          control-variant="hidden"
                          class="no-border-radius"
                          autocomplete="off"
                          :error-messages="formSite.errors.vel_solicitada_up"
                        />

                        <v-number-input
                          v-model="formSite.barra"
                          :min="0"
                          :max="32"
                          label="/"
                          variant="outlined"
                          density="comfortable"
                          hide-details
                          class="no-border-radius-left"
                          control-variant="hidden"
                          autocomplete="off"
                          :error-messages="formSite.errors.barra"
                        />
                      </div>
                  </v-col>
                  <v-col cols="12" md="12">
                      <v-autocomplete
                        v-model="formSite.servicos"
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
                        :error-messages="formSite.errors.servicos"
                      />
                  </v-col>
              </v-row>
          </v-form>
      </template>

      <v-card-actions class="bg-surface-light">
          <v-btn text="Fechar" variant="plain" @click="dialogFormSite = false"></v-btn>

          <v-spacer></v-spacer>

          <v-btn text="Salvar" @click="submitFormSite"></v-btn>
      </v-card-actions>
      </v-card>
  </v-dialog>
  <DialogFormCotacao
    ref="dialogCotacao"
    :fornecedores="fornecedores"
    :tecnologia-opcoes="tecnologiaOpcoes"
    :status-opcoes="statusOpcoes"
    :pode-precificar="podePrecificar"
  />
</template>

<script setup>
import { ref, shallowRef, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import DialogFormCotacao from './DialogFormCotacao.vue'

const showSuccess = ref(false);
const showError = ref(false);
const errorMessage = ref('');
const dialogFormSite = shallowRef(false);
const props = defineProps({
  orcamento: Object,
  sitesOrcamentoArray: Array,
  servicos: Array,
  fornecedores: Array,
  tecnologiaOpcoes: Array,
  statusOpcoes: Array,
  podePrecificar: Boolean,
});

const sitesOrcamento = ref(
  props.sitesOrcamentoArray.map(site => ({
    ...site,
    cotacao_selecionada_id: site.cotacoes?.[0]?.id ?? null // seleciona a primeira por padrão
  }))
)

function cotacaoSelecionada(site) {
  return site.cotacoes?.find(c => c.id === site.cotacao_selecionada_id)
}
// const headers = ref([
//   { title: 'Nome',    key: 'nome'       },
//   { title: 'Endereco',  key: 'endereco'  },
//   { title: 'Cidade', key: 'cidade' },
//   { title: 'Estado',      key: 'estado'      },
//   { title: 'Lat/Lng',     key: 'coords'     },
//   { title: 'Down',     key: 'vel_solicitada_down'     },
//   { title: 'Up',     key: 'vel_solicitada_up'     },
//   { title: 'Barra',     key: 'barra'     },
// ])

// Colunas da tabela pai (sites)
const headers = ref([
  { title: 'Nome',   key: 'nome'               },
  { title: 'Cidade', key: 'cidade'              },
  { title: 'Estado', key: 'estado'              },
  { title: 'Down',   key: 'vel_solicitada_down' },
  { title: 'Up',     key: 'vel_solicitada_up'   },
  { title: 'Fornecedor',     key: 'fornecedor'   },
  { title: 'Custo Ativação',     key: 'custo_ativacao'   },
  { title: 'Velocidade',     key: 'velocidade'   },
  { title: 'Tecnologia',     key: 'tecnologia'   },
  { title: 'Adesão',     key: 'adesao_fornecedor'   },
  { title: 'Custo Operacional',     key: 'custo_operacional'   },
  { title: 'Mensalidade + imposto',     key: 'mensal_imp'   },
  { title: 'Mensalidade do Fornecedor',     key: 'mensal_fornecedor'   },
  { title: 'Custo de instalação + imposto',     key: 'custo_instalacao_imp'   },
  { title: 'Prazo de instalação',     key: 'prazo_instalacao'   },
  { title: 'Prazo de instalação do Fornecedor',     key: 'prazo_instalacao_fornecedor'   },
  { title: 'Imposto Mensal',     key: 'imposto_mensal'   },
  { title: 'Imposto da Adesão',     key: 'imposto_adesao'   },
  { title: 'Lucro da Adesão',     key: 'lucro_adesao'   },
  { title: 'Lucro Líquido',     key: 'lucro_liquido'   },
  { title: 'Custo Fixo',     key: 'custo_fixo'   },
])

// Colunas da tabela filha (pontas/cotações)
const headersCotacoes = ref([
  { title: '',   key: 'selecionar'               },
  { title: 'ID',   key: 'id'               },
  { title: 'Fornecedor',    key: 'fornecedor'      },
  { title: 'Custo Ativação', key: 'custo_ativacao' },
  { title: 'Velocidade',     key: 'velocidade'   },
  { title: 'Tecnologia',     key: 'tecnologia'   },
  { title: 'Adesão',     key: 'adesao_fornecedor'   },
  { title: 'Custo Operacional',     key: 'custo_operacional'   },
  { title: 'Mensalidade + imposto',     key: 'mensal_imp'   },
  { title: 'Mensalidade do Fornecedor',     key: 'mensal_fornecedor'   },
  { title: 'Custo de instalação + imposto',     key: 'custo_instalacao_imp'   },
  { title: 'Prazo de instalação',     key: 'prazo_instalacao'   },
  { title: 'Prazo de instalação do Fornecedor',     key: 'prazo_instalacao_fornecedor'   },
  { title: 'Imposto Mensal',     key: 'imposto_mensal'   },
  { title: 'Imposto da Adesão',     key: 'imposto_adesao'   },
  { title: 'Lucro da Adesão',     key: 'lucro_adesao'   },
  { title: 'Lucro Líquido',     key: 'lucro_liquido'   },
  { title: 'Custo Fixo',     key: 'custo_fixo'   },
])


const formSite = useForm({
    id: null,
    nome: '',
    endereco: '',
    cidade_id: null,
    latitude: '',
    longitude: '',
    id_instalacao: '',
    is_subestacao: false,
    vel_solicitada_down: null,
    vel_solicitada_up: null,
    barra: null,
    servicos: []
})

const isEditingSite = computed(() => !!formSite.id)

function add() {
    formSite.id = null;
    formSite.nome = null;
    formSite.endereco = null;
    formSite.latitude = null;
    formSite.longitude = null;
    formSite.cidade_id = null;
    formSite.is_subestacao = false;
    formSite.vel_solicitada_down = null;
    formSite.vel_solicitada_up = null;
    formSite.barra = null;
    formSite.servicos = [];
    dialogFormSite.value = true
}


async function submitFormSite() {
    const valid = await form.value.validate()
    if (!valid) {
        console.log('Validação do frontend falhou')
        return
    }

    if (formSite.id) {
        formSite.put(route('site.table.update', formSite.id), {
            onSuccess: (response) => {
                loadData({ page: page.value, itemsPerPage: itemsPerPage.value })
                dialog.value = false
                showSuccess.value = true
                msgSuccess.value = response.props.flash.success
            },
            onError: tratarErros
        })
    } else {
        formSite.post(route('site.table.store'), {
            onSuccess: (response) => {
                loadData({ page: page.value, itemsPerPage: itemsPerPage.value })
                dialog.value = false
                showSuccess.value = true
                msgSuccess.value = response.props.flash.success
            },
            onError: tratarErros
        })
    }
}
</script>