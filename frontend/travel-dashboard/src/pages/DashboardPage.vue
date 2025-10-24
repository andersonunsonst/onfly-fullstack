<template>
  <q-page padding>
    <div class="row items-center justify-between q-mb-md">
      <div class="text-h5">Pedidos de Viagem</div>
      <div class="row q-gutter-sm items-center">
        <q-select
          v-model="statusFilter"
          :options="['requested', 'approved', 'canceled']"
          label="Filtrar por status"
          dense
          @update:model-value="loadTravels"
        />
        <q-btn color="primary" icon="add" label="Novo Pedido" @click="openForm = true" />
      </div>
    </div>

    <TravelTable
      :travels="travels"
      :loading="loading"
      @update-status="updateStatus"
    />

    <q-dialog v-model="openForm">
      <TravelForm @created="loadTravels" @close="openForm = false" />
    </q-dialog>
  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { api } from 'boot/axios'
import { Notify } from 'quasar'
import TravelTable from 'components/TravelTable.vue'
import TravelForm from 'components/TravelForm.vue'

const travels = ref([])
const loading = ref(false)
const statusFilter = ref('')
const openForm = ref(false)

const loadTravels = async () => {
  loading.value = true
  const { data } = await api.get('/travel-requests', { params: { status: statusFilter.value } })
  travels.value = data
  loading.value = false
}

const updateStatus = async (id, status) => {
  try {
    const { data } = await api.patch(`/travel-requests/${id}/status`, { status })
    Notify.create({ message: data.message, color: 'positive' })
    await loadTravels()
  } catch {
    Notify.create({ message: 'Erro ao atualizar status', color: 'negative' })
  }
}

onMounted(loadTravels)
</script>
