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
      redirect(to) {
        return { name: 'dashboard' }
      }
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: import('../views/Dashboard.vue'),
      beforeEnter: authGuard
    }, {
      path: '/tasks/:id',
      name: 'task-detail',
      component: import('../views/TaskDetail.vue')
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/Login.vue')
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('../views/Register.vue')
    },
    {
      'path': '/verify-email',
      name: 'verify-email',
      component: () => import('../views/VerifyEmail.vue')
    }
  ]
})


export default router
