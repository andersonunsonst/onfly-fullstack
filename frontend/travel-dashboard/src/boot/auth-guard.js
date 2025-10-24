import { useAuthStore } from 'stores/auth'

export default ({ router }) => {
  router.beforeEach((to, from, next) => {
    const auth = useAuthStore()

    if (to.meta.requiresAuth && !auth.token) {
      next('/login')
    } else if (to.path === '/login' && auth.token) {
      next('/dashboard')
    } else {
      next()
    }
  })
}
