<template>
  <q-card class="q-pa-md" style="min-width: 400px">
    <q-card-section>
      <div class="text-h6">Novo Pedido de Viagem</div>
    </q-card-section>

    <q-card-section>
      <q-input filled v-model="form.destination" label="Destino" />
      <q-input filled type="date" v-model="form.departure_date" label="Data de Ida" class="q-mt-md" />
      <q-input filled type="date" v-model="form.return_date" :min="form.departure_date" label="Data de Volta" class="q-mt-md" />
    </q-card-section>

    <q-card-actions align="right">
      <q-btn flat label="Cancelar" color="grey" @click="emit('close')" />
      <q-btn color="primary" label="Salvar" :loading="loading" @click="submit" />
    </q-card-actions>
  </q-card>
</template>

<script setup>
import { ref } from 'vue'
import { api } from 'boot/axios'
import { Notify } from 'quasar'

const emit = defineEmits(['created', 'close'])

const form = ref({
  destination: '',
  departure_date: '',
  return_date: ''
})

const loading = ref(false)

const submit = async () => {
  try {
    loading.value = true
    if (form.value.return_date < form.value.departure_date) {
      Notify.create({ message: 'A data de volta não pode ser anterior à data de ida.', color: 'negative' })
      return
    }
    const { data } = await api.post('/travel-requests', form.value)

    Notify.create({ message: data.message, color: 'positive' })
    form.value = { destination: '', departure_date: '', return_date: '' }

    emit('created') 
    emit('close')

  } catch {
    Notify.create({ message: 'Erro ao criar pedido', color: 'negative' })
  } finally {
    loading.value = false
  }
}
</script>
