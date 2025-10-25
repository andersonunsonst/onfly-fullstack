<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated class="bg-primary text-white">
      <q-toolbar>
        <q-toolbar-title>Travel Dashboard</q-toolbar-title>

        <div v-if="auth.user" class="row items-center q-gutter-sm">
          <div class="text-subtitle2">{{ auth.user.name }}</div>

          <q-btn
            flat
            round
            dense
            icon="logout"
            color="white"
            @click="handleLogout"
          >
            <q-tooltip>Sair</q-tooltip>
          </q-btn>
        </div>
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { useAuthStore } from 'src/stores/auth'
import { useRouter } from 'vue-router'
import { Notify } from 'quasar'

const auth = useAuthStore()
const router = useRouter()

const handleLogout = async () => {
  try {
    await auth.logout()
    Notify.create({ message: 'Logout realizado com sucesso!', color: 'positive' })
    await router.push('/login')
  } catch (error) {
    console.error(error)
    Notify.create({ message: 'Erro ao sair', color: 'negative' })
  }
}
</script>
