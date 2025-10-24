<template>
  <q-table
    :rows="travels"
    :columns="columns"
    row-key="id"
    flat
    bordered
    :loading="loading"
  >
    <template v-slot:body-cell-status="props">
      <q-chip :color="statusColor(props.row.status)" text-color="white">
        {{ props.row.status }}
      </q-chip>
    </template>

    <template v-slot:body-cell-actions="props">
      <q-btn
        v-if="props.row.status === 'requested'"
        color="green"
        label="Aprovar"
        size="sm"
        @click="$emit('update-status', props.row.id, 'approved')"
      />
      <q-btn
        color="red"
        label="Cancelar"
        size="sm"
        class="q-ml-sm"
        @click="$emit('update-status', props.row.id, 'canceled')"
      />
    </template>
  </q-table>
</template>

<script setup>
defineProps(['travels', 'loading'])

const columns = [
  { name: 'id', label: 'ID', field: 'id', align: 'left' },
  { name: 'destination', label: 'Destino', field: 'destination', align: 'left' },
  { name: 'departure_date', label: 'Ida', field: 'departure_date' },
  { name: 'return_date', label: 'Volta', field: 'return_date' },
  { name: 'status', label: 'Status', field: 'status' },
  { name: 'actions', label: 'Ações', field: 'actions' },
]

const statusColor = (status) => ({
  requested: 'grey',
  approved: 'green',
  canceled: 'red'
}[status])
</script>
