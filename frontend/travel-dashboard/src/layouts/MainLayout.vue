<template>
  <q-layout view="lHh Lpr lFf">
    <!-- HEADER -->
    <q-header elevated class="bg-primary text-white">
      <q-toolbar>
        <q-toolbar-title>Travel Dashboard</q-toolbar-title>

        <div class="row items-center q-gutter-sm">
          <!-- Mostra nome do usuário -->
          <div v-if="auth.user" class="text-subtitle2">
          {{ auth.user.name }}
        </div>

          <!-- BOTÃO DE LOGOUT -->
          <q-btn
            v-if="auth.user"
            flat
            dense
            round
            color="white"
            icon="logout"
            @click="confirmLogout = true"
          >
            <q-tooltip>Encerrar sessão</q-tooltip>
          </q-btn>
        </div>
      </q-toolbar>
    </q-header>

    <!-- DIALOGO DE CONFIRMAÇÃO -->
    <q-dialog v-model="confirmLogout" persistent>
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <q-icon name="logout" color="primary" size="md" class="q-mr-sm" />
          <div class="text-h6">Sair do sistema</div>
        </q-card-section>

        <q-card-section>
          Deseja realmente encerrar a sessão e sair do sistema?
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Cancelar" color="primary" v-close-popup />
          <q-btn
            color="negative"
            label="Sair"
            :loading="loading"
            @click="logout"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <!-- CONTEÚDO -->
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from 'src/stores/auth'
import { useRouter } from 'vue-router'
import { Notify } from 'quasar'

const auth = useAuthStore()
const router = useRouter()
const loading = ref(false)

const logout = async () => {
  try {
    loading.value = true
    await auth.logout() 
    Notify.create({ message: 'Logout realizado com sucesso!', color: 'positive' })
    router.push('/login') // redireciona para a tela de login
  } catch {
    Notify.create({ message: 'Erro ao sair.', color: 'negative' })
  } finally {
    loading.value = false
  }
}
</script>
