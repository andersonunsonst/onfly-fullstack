import { defineStore } from 'pinia'
import { api } from 'boot/axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
    initialized: false,
  }),

  getters: {
    isAuthenticated: (state) => !!state.user,
  },

  actions: {
    async login(credentials) {
      const { data } = await api.post('/login', credentials)

      this.token = data.access_token
      localStorage.setItem('token', this.token)
      api.defaults.headers.common['Authorization'] = `Bearer ${this.token}`

      const me = await api.get('/me')
      this.user = me.data
    },

    async logout() {

      await api.post('/logout')

      this.user = null
      this.token = null
      localStorage.removeItem('token')
      delete api.defaults.headers.common['Authorization']
    },

    async restoreSessionOnce() {
      if (this.initialized) return
      this.initialized = true

      const token = localStorage.getItem('token')
      if (!token) return

      this.token = token
      api.defaults.headers.common['Authorization'] = `Bearer ${token}`

      try {
        const { data } = await api.get('/me')
        this.user = data
      } catch {
        this.logout()
      }
    },
  },
})
