const routes = [
  {
    path: '/',
    children: [
      {
        path: '/login',
        component: () => import('layouts/AuthLayout.vue'),
        children: [{ path: '', component: () => import('pages/LoginPage.vue') }],
      },
      {
        path: '/dashboard',
        component: () => import('layouts/MainLayout.vue'),
        children: [{ path: '', component: () => import('pages/DashboardPage.vue') }],
      },
    ],
  },
]

export default routes
