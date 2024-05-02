import { createRouter, createWebHistory } from 'vue-router'
const authGuard = async (to: any, from: any, next: any) => {
  const unProtectedRoutes = ['/login']

  const token = JSON.parse(localStorage.getItem('token') || '{}')
  const isLoggedIn = token && token.value && token.value.length > 0

  if (!unProtectedRoutes.includes(to.path) && !isLoggedIn) {
    return next({
      path: '/login'
    })
  } else if (unProtectedRoutes.includes(to.path) && isLoggedIn) {
    return next({ name: 'home' })
  }
  return next()
}

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      redirect: '/task',
    },
    {
      path:'/task',
      name: 'task',
      component: import('../views/Task.vue'),
      beforeEnter: authGuard
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../components/Login.vue')
    },
    {
      path:'/register',
      name: 'register',
      component: () => import('../components/Register.vue')
    }
  ]
})


export default router
