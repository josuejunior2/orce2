<template>
    <v-container fluid>
        <v-row class="align-center mb-3">
            <v-col cols="12" md="10">
                <v-text-field
                v-model="search"
                label="Pesquisar"
                prepend-icon="mdi-magnify"
                clearable
                density="comfortable"
                hide-details
                />
            </v-col>

            <v-col cols="auto">
                <v-btn
                class="me-2"
                prepend-icon="mdi-plus"
                rounded="lg"
                text="Novo Site"
                color="primary"
                @click="add"
                />
            </v-col>
        </v-row>       
        <v-data-table-server
            v-model:page="page"
            v-model:items-per-page="itemsPerPage"
            :items-length="total"
            :headers="headers"
            :items="sites"
            :search="search"
            item-value="id"
            show-expand
            @update:options="loadData"
        >
            <!-- Coluna de actions com botão expandir -->
            <template v-slot:item.data-table-expand="{ internalItem, isExpanded, toggleExpand }">
            <v-btn
                size="small"
                variant="text"
                color="primary"
                :append-icon="isExpanded(internalItem) ? 'mdi-chevron-up' : 'mdi-chevron-down'"
                :text="isExpanded(internalItem) ? 'Fechar' : 'Sites'"
                @click="toggleExpand(internalItem)"
            />
            </template>

            <!-- Conteúdo expandido -->
            <template v-slot:expanded-row="{ item, columns }">
                <tr>
                    <td :colspan="columns.length" class="py-2">
                        <v-sheet border rounded="lg" class="pa-2">
                            <v-table density="compact">
                                <thead>
                                    <tr>
                                        <th>Orcamento</th>
                                        <th>Cliente</th>
                                        <th>Velocidade de Download</th>
                                        <th>Velocidade de Upload</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                    v-for="(site, i) in item.sites_orcamento"
                                    :key="i"
                                    >
                                    <td>{{ site.orcamento.titulo }}</td>
                                    <td>{{ site.orcamento.cliente.nome }}</td>
                                    <td>{{ site.vel_solicitada_down ? site.vel_solicitada_down + ' Mbps' : '' }}</td>
                                    <td>{{ site.vel_solicitada_up ? site.vel_solicitada_up + ' Mbps' : '' }}</td>
                                    <td>
                                        <v-btn
                                            icon
                                            :href="`/orcamento/${site.orcamento.id}`"
                                            color="primary"
                                            variant="flat"
                                            size="small"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye me-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M12 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                <path d="M22 12c-2.667 -5.333 -6.667 -8 -10 -8s-7.333 2.667 -10 8c2.667 5.333 6.667 8 10 8s7.333 -2.667 10 -8" />
                                            </svg>
                                        </v-btn>                                        
                                    </td>
                                    </tr>
                                </tbody>
                            </v-table>
                        </v-sheet>
                    </td>
                </tr>
            </template>
        </v-data-table-server>
    </v-container>
</template>

<script setup>
import { ref } from 'vue'

const page = ref(1)
const itemsPerPage = ref(10)
const total = ref(0)
const sites = ref([])
const search = ref(null);

const headers = [
  { title: 'ID', key: 'id' },
  { title: 'Nome', key: 'nome' },
  { title: 'Cidade', key: 'cidade.nome' },
  { title: 'Estado', key: 'cidade.estado.nome' },
  { title: 'Endereço', key: 'endereco' },
  { title: 'Latitude', key: 'latitude' },
  { title: 'Longitude', key: 'longitude' },
  { key: 'data-table-expand', width: 50, sortable: false }, // botão expandir
]

const loadData = async ({ page, itemsPerPage, sortBy }) => {
  // chamada server-side (Laravel/Inertia)
  const response = await axios.get('/getTableSites', {
    params: {
        search: search.value,
        page,
        per_page: itemsPerPage,
        sortBy
    }
  })
console.log(response.data, response.data.data);
  sites.value = response.data.items
  total.value = response.data.total
}
</script>
