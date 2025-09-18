<template>
    <v-container fluid>
        <v-text-field
            v-model="search"
            label="Pesquisar"
            prepend-icon="mdi-magnify"
        />
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
                                        <th>Vel Down</th>
                                        <th>Vel Up</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                    v-for="(site, i) in item.site_orcamentos"
                                    :key="i"
                                    >
                                    <td>{{ site.vel_solicitada_down }}</td>
                                    <td>{{ site.vel_solicitada_up }}</td>
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
