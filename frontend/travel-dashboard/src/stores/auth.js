import { defineStore } from 'pinia'
import { api } from 'boot/axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('token') || null,
    user: null
  }),

  actions: {
    async login(credentials) {
      const { data } = await api.post('/login', credentials)
      this.token = data.access_token
      localStorage.setItem('token', this.token)
      await this.getUser()
    },

    async getUser() {
      const { data } = await api.get('/me')
      this.user = data
    },

    logout() {
      this.token = null
      this.user = null
      localStorage.removeItem('token')
    }
  }
})
