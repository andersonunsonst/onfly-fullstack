<template>
  <q-table
    :rows="travels"
    :columns="columns"
    row-key="id"
    flat
    bordered
    :loading="loading"
    v-model:pagination="pagination"
  >
    <!-- Corpo manual: cada coluna em seu <q-td> -->
    <template v-slot:body="props">
      <q-tr :props="props">
        <q-td key="id" :props="props">
          {{ props.row.id }}
        </q-td>

        <q-td key="destination" :props="props">
          {{ props.row.destination }}
        </q-td>

        <q-td key="departure_date" :props="props">
          {{ formatDate(props.row.departure_date) }}
        </q-td>

        <q-td key="return_date" :props="props">
          {{ formatDate(props.row.return_date) }}
        </q-td>

        <q-td key="status" :props="props">
          <q-chip :color="statusColor(props.row.status)" text-color="white" class="text-capitalize">
            {{ statusLabel(props.row.status) }}
          </q-chip>
        </q-td>

         <q-td key="actions" :props="props">
          <div class="flex justify-center q-gutter-sm">
            <q-btn
              v-if="isAdmin && props.row.status === 'approved'"
              color="red"
              label="Cancelar"
              size="sm"
              :loading="loadingId == props.row.id"
              @click="handleAction(props.row.id, 'canceled')"
            >
              <q-tooltip v-if="!isAdmin">Apenas administradores podem aprovar</q-tooltip>
            </q-btn>
            <q-btn
              v-else-if="isAdmin && props.row.status === 'canceled'"
              color="green"
              label="Aprovar"
              size="sm"
              :loading="loadingId == props.row.id"
              @click="handleAction(props.row.id, 'approved')"
            />
            <q-btn
              v-else-if="isAdmin && props.row.status === 'requested'"
              color="green"
              label="Aprovar"
              size="sm"
              :disable="!isAdmin"
              :loading="loadingId == props.row.id"
              @click="handleAction(props.row.id, 'approved')"
            />
          </div>
        </q-td>
      </q-tr>
    </template>
  </q-table>
</template>

<script setup>
import { date, Notify } from 'quasar'
import { ref } from 'vue'
import { api } from 'boot/axios'
import { computed } from 'vue'
import { useAuthStore } from 'src/stores/auth'

const { travels, loading } = defineProps(['travels', 'loading'])

const emit = defineEmits(['update-status'])
const auth = useAuthStore()
const isAdmin = computed(() => auth.user?.role === 'admin')

const pagination = ref({
  page: 1,
  rowsPerPage: 30, 
})


const columns = [
  { name: 'id', label: 'ID', field: 'id', align: 'left', sortable: true },
  { name: 'destination', label: 'Destino', field: 'destination', align: 'left', sortable: true },
  { name: 'departure_date', label: 'Ida', field: 'departure_date', align: 'center' },
  { name: 'return_date', label: 'Volta', field: 'return_date', align: 'center' },
  { name: 'status', label: 'Status', field: 'status', align: 'center' },
  { name: 'actions', label: 'Ações', field: row => row.id, align: 'center', sortable: false },
]

const statusColor = (status) => ({
  requested: 'grey',
  approved: 'green',
  canceled: 'red'
}[status] || 'grey')

const formatDate = (iso) => {
  if (!iso) return ''
  return date.formatDate(iso, 'DD/MM/YYYY')
}

const statusLabel = (status) => ({
  requested: 'Solicitado',
  approved: 'Aprovado',
  canceled: 'Cancelado'
}[status] || status)

const loadingId = ref(null)

const handleAction = async (id, status) => {
  try {
    loadingId.value = id
    const { data } = await api.patch(`/travel-requests/${id}/status`, { status })
    Notify.create({ message: data.message, color: 'positive' })
    emit('update-status')
  } catch {
    Notify.create({ message: 'Erro ao atualizar status', color: 'negative' })
  } finally {
    loadingId.value = null
  }
}

</script>
