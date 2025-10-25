import { defineStore } from 'pinia'
import { api } from 'boot/axios'
import { Notify } from 'quasar'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
    initialized: false,
  }),

  getters: {
    /** Retorna true se o usuário estiver autenticado */
    isAuthenticated: (state) => !!state.user && !!state.token,
    /** Retorna true se o usuário for admin */
    isAdmin: (state) => state.user?.role === 'admin',
  },

  actions: {
    /**
     * Realiza login com email/senha
     */
    async login(credentials) {
      try {
        const { data } = await api.post('/login', credentials)

        this.token = data.access_token
        localStorage.setItem('token', this.token)
        api.defaults.headers.common['Authorization'] = `Bearer ${this.token}`

        const { data: user } = await api.get('/me')
        this.user = user

        Notify.create({ message: 'Login realizado com sucesso!', color: 'positive' })
      } catch (error) {
        console.error('Erro no login:', error)
        this.logout(false)
        Notify.create({ message: 'Credenciais inválidas ou erro de conexão', color: 'negative' })
        throw error
      }
    },

    /**
     * Faz logout limpando token local e do backend
     */
    async logout(notify = true) {
      try {
        if (this.token) await api.post('/logout')
      } catch (error) {
        console.warn('Erro ao desconectar do servidor (ignorado):', error)
      } finally {
        this.user = null
        this.token = null
        this.initialized = false
        localStorage.removeItem('token')
        delete api.defaults.headers.common['Authorization']

        if (notify) {
          Notify.create({ message: 'Logout realizado com sucesso!', color: 'positive' })
        }
      }
    },

    /**
     * Tenta restaurar sessão existente apenas uma vez
     */
    async restoreSessionOnce() {
      if (this.initialized) return
      this.initialized = true

      const token = localStorage.getItem('token')
      if (!token) return

      this.token = token
      api.defaults.headers.common['Authorization'] = `Bearer ${token}`

      try {
        const { data: user } = await api.get('/me')
        this.user = user
      } catch (error) {
        console.warn('Sessão inválida, limpando token...', error)
        await this.logout(false)
      }
    },
  },
})
