<template>
  <q-page class="flex flex-center">
    <q-card class="q-pa-lg" style="min-width: 350px">
      <q-card-section>
        <div class="text-h6">Login</div>
      </q-card-section>

      <q-card-section>
        <q-input filled v-model="email" label="Email" />
        <q-input filled v-model="password" type="password" label="Senha" class="q-mt-md" />
      </q-card-section>

      <q-card-actions align="right">
        <q-btn :loading="loading" label="Entrar" color="primary" @click="login" />
      </q-card-actions>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from 'stores/auth'
import { Notify } from 'quasar'

const router = useRouter()
const auth = useAuthStore()
const email = ref('')
const password = ref('')
const loading = ref(false)

const login = async () => {
  try {
    loading.value = true
    await auth.login({ email: email.value, password: password.value })
    Notify.create({ message: 'Login realizado com sucesso!', color: 'positive' })

    // Aguarda garantir que o store foi atualizado
    if (auth.token) {
      router.push('/dashboard')
    } else {
      Notify.create({ message: 'Erro ao autenticar', color: 'negative' })
    }

  } catch {
    Notify.create({ message: 'Credenciais inválidas', color: 'negative' })
  } finally {
    loading.value = false
  }
}
</script>
