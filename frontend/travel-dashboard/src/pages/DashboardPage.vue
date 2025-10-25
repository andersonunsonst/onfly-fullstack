<template>
  <q-page padding>
    <!-- 🔹 Filtros -->
    <div class="row items-end q-col-gutter-md q-mb-md">
      <q-input v-model="filters.destination" label="Destino" dense outlined class="col-3" />

      <q-select v-model="filters.status" label="Status" :options="statusOptions" option-value="value"
        option-label="label" emit-value map-options dense outlined clearable class="col-3" />


      <q-input v-model="filters.start_date" type="date" label="Data inicial" dense outlined class="col-2" />

      <q-input v-model="filters.end_date" type="date" label="Data final" dense outlined class="col-2" />

      <div class="col-auto q-gutter-sm flex items-center justify-end">
        <q-btn color="primary" label="Filtrar" icon="search" @click="loadTravels" />
        <q-btn color="grey-7" label="Limpar" flat icon="clear" @click="resetFilters" />
      </div>
    </div>

    <!-- 🔹 Tabela -->
    <TravelTable :travels="travels" :loading="loading" @update-status="updateStatus" />
  </q-page>
</template>

<script setup>
import { ref } from 'vue'
import { api } from 'boot/axios'
import { Notify } from 'quasar'
import TravelTable from 'src/components/TravelTable.vue'

const travels = ref([])
const loading = ref(false)
const filters = ref({
  destination: '',
  status: '',
  start_date: '',
  end_date: ''
})

const statusOptions = [
  { label: 'Solicitado', value: 'requested' },
  { label: 'Aprovado', value: 'approved' },
  { label: 'Cancelado', value: 'canceled' }
]

const loadTravels = async () => {
  try {
    loading.value = true
    const { data } = await api.get('/travel-requests', { params: filters.value })
    travels.value = data.data || []
  } catch (err) {
    console.error(err)
    Notify.create({ message: 'Erro ao carregar viagens', color: 'negative' })
  } finally {
    loading.value = false
  }
}


const resetFilters = () => {
  filters.value = {
    destination: '',
    status: '',
    start_date: '',
    end_date: ''
  }
  loadTravels()
}


const updateStatus = async (id, status) => {
  try {
    await api.patch(`/travel-requests/${id}/status`, { status })
    Notify.create({ message: 'Status atualizado!', color: 'positive' })
    loadTravels()
  } catch (err) {
    console.error(err)
    Notify.create({ message: 'Erro ao atualizar status', color: 'negative' })
  }
}

loadTravels()
</script>
