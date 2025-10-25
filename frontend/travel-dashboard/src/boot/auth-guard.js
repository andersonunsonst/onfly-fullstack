import { boot } from 'quasar/wrappers'
import { useAuthStore } from 'src/stores/auth'

export default boot(async ({ router }) => {
  const auth = useAuthStore()

  await auth.restoreSessionOnce()

  router.beforeEach((to, from, next) => {
    const requiresAuth = to.meta?.requiresAuth === true

    if (requiresAuth && !auth.isAuthenticated) {
      next('/login')
    } else if (to.path === '/login' && auth.isAuthenticated) {
      next('/dashboard')
    } else {
      next()
    }
  })
})
