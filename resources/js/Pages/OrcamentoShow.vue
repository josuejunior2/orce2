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
            <v-col col="12" md="1">
              <v-menu :close-on-content-click="false">
                <template v-slot:activator="{ props }">
                  <v-btn
                    v-bind="props"
                    prepend-icon="mdi-eye-outline"
                    rounded="lg"
                    text="Colunas"
                    variant="outlined"
                  />
                </template>
                <v-list density="compact">
                  <v-list-item>
                    <v-checkbox
                      :model-value="colunasVisiveis.length === colunasDisponiveis.length"
                      :indeterminate="colunasVisiveis.length > 0 && colunasVisiveis.length < colunasDisponiveis.length"
                      label="Todos"
                      hide-details
                      density="compact"
                      @update:model-value="val => colunasVisiveis = val ? colunasDisponiveis.map(c => c.key) : []"
                    />
                  </v-list-item>
                  <v-list-item
                    v-for="col in colunasDisponiveis"
                    :key="col.key"
                  >
                    <v-checkbox
                      v-model="colunasVisiveis"
                      :label="col.title"
                      :value="col.key"
                      hide-details
                      density="compact"
                    />
                  </v-list-item>
                </v-list>
              </v-menu>
            </v-col>
            <v-col col="12" md="1" class="d-flex justify-md-end">
              <v-btn
                prepend-icon="mdi-plus"
                rounded="lg"
                text="Novo Site"
                color="primary"
                @click="dialogSite.abrirCadastro()"
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
                          @click="$refs.dialogCotacao.abrirCadastro(item.id, item.cidade_id)"
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

  <DialogFormSiteOrcamento
    ref="dialogSite"
    :cidades="cidades"
    :servicos="servicos"
    :orcamento="orcamento"
  />
  <DialogFormCotacao
    ref="dialogCotacao"
    :tecnologia-opcoes="tecnologiaOpcoes"
    :status-opcoes="statusOpcoes"
    :pode-precificar="podePrecificar"
    :servicos="servicos"
    :gear-noc="gearNoc"
    :custo-fixo-percent="custoFixoPercent"
    :imposto="orcamento.imposto"
  />
</template>

<script setup>
import { ref, shallowRef, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import DialogFormCotacao from './DialogFormCotacao.vue'
import DialogFormSiteOrcamento from './DialogFormSiteOrcamento.vue'

const showSuccess = ref(false);
const showError = ref(false);
const errorMessage = ref('');
const dialogSite = ref(null)
const props = defineProps({
  orcamento: Object,
  sitesOrcamentoArray: Array,
  servicos: Array,
  tecnologiaOpcoes: Array,
  statusOpcoes: Array,
  podePrecificar: Boolean,
  cidades: Array,
  gearNoc: Number,
  custoFixoPercent: Number,
});

const sitesOrcamento = ref(
  props.sitesOrcamentoArray.map(site => ({
    ...site,
    cotacao_selecionada_id: site.cotacoes?.[0]?.id ?? null
  }))
)

function cotacaoSelecionada(site) {
  return site.cotacoes?.find(c => c.id === site.cotacao_selecionada_id)
}

// Colunas da tabela pai (sites)
const headersFixos = [
  { title: 'Nome',   key: 'nome'               },
  { title: 'Cidade', key: 'cidade'              },
  { title: 'Estado', key: 'estado'              },
  { title: 'Down',   key: 'vel_solicitada_down' },
  { title: 'Up',     key: 'vel_solicitada_up'   },
]
const colunasDisponiveis = [
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
]

const colunasVisiveis = ref(colunasDisponiveis.map(c => c.key))

const headers = computed(() => [
  ...headersFixos,
  ...colunasDisponiveis.filter(c => colunasVisiveis.value.includes(c.key)),
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