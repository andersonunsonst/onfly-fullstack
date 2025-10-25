<template>
  <q-page padding>
    <div class="row items-center justify-between q-mb-md">
      <div class="text-h5">Pedidos de Viagem</div>
      <q-btn color="primary" icon="add" label="Novo Pedido" @click="openForm = true" />
    </div>

    <TravelTable
      :travels="travels"
      :loading="loading"
      @update-status="updateStatus"
    />

    <!-- Diálogo controlado -->
    <q-dialog v-model="openForm" persistent>
      <TravelForm
        @created="loadTravels"
        @close="handleClose"
      />
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
const openForm = ref(false)

const loadTravels = async () => {
  loading.value = true
  const { data } = await api.get('/travel-requests')
  travels.value = data
  loading.value = false
}

const updateStatus = async (id, status) => {
  const { data } = await api.patch(`/travel-requests/${id}/status`, { status })
  Notify.create({ message: data.message, color: 'positive' })
  await loadTravels()
}

const handleClose = () => {
  openForm.value = false
}

onMounted(loadTravels)
</script>