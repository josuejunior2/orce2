<template>
    <v-container fluid>
      <v-text-field
        v-model="search"
        label="Pesquisar"
        prepend-icon="mdi-magnify"
      />
  
    <v-data-table
        :headers="headers"
        :items="orcamentos"
        :search="search"
        v-model:page="page"
        :items-per-page="itemsPerPage"
        class="elevation-1"
        show-current-page
        show-first-last-page
        v-model:search="search"
    >
        <template #item.created_at="{ item }">
          {{ new Intl.DateTimeFormat('pt-BR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
          }).format(new Date(item.created_at)) }}
        </template>
  
        <template #item.tipo_link="{ item }">
          {{ item.tipo_link === 'ld' ? 'Link Dedicado' : item.tipo_link === 'l2l' ? 'Lan 2 Lan' : 'Outro' }}
        </template>
  
        <template #item.status="{ item }">
          <v-chip :color="statusColor(item.status)" text-color="white" small>
            {{ item.status }}
          </v-chip>
        </template>
        <!-- Coluna: ações -->
        <template #item.actions="{ item }">
          <v-btn
            icon
            :href="`/orcamento/${item.id}`"
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
        </template>
    </v-data-table>
  </v-container>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  orcamentos: Array,
});

const itemsPerPage = 10;
const page = ref(1);
const search = ref('');

const headers = [
  { title: 'Título', key: 'titulo' },
  { title: 'Cliente', key: 'nome' },
  { title: 'Data de criação', key: 'created_at' },
  { title: 'Link', key: 'tipo_link' },
  { title: 'Tempo do contrato', key: 'tempo_contrato' },
  { title: 'Qtd. de sites', key: 'quantidade_sites' },
  { title: 'Status', key: 'status' },
  { title: 'Ações', key: 'actions', sortable: false },
];

function statusColor(status) {
  switch (status) {
    case 'Sem viabilidade': return 'red';
    case 'Em cotação': return 'orange';
    case 'Enviado': return 'blue';
    case 'Aprovado': return 'green';
    default: return 'grey';
  }
}
</script>