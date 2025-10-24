import { useAuthStore } from 'stores/auth'

export default ({ router }) => {
  router.beforeEach((to, from, next) => {
    const auth = useAuthStore()
    if (to.meta.requiresAuth && !auth.token) next('/login')
    else next()
  })
}
