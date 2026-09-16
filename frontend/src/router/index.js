import { createRouter, createWebHistory } from 'vue-router'

// Layout
import PublicLayout from '../components/PublicLayout.vue'

// Existing views
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import TransactionsView from '../views/TransactionsView.vue'
import BudgetsView from '../views/BudgetsView.vue'
import SavingsView from '../views/SavingsView.vue'
import WalletsView from '../views/WalletsView.vue'
import CategoriesView from '../views/CategoriesView.vue'

// New views
import ProfileView from '../views/ProfileView.vue'

const router = createRouter({
  history: createWebHistory(),

  routes: [

    // =====================================================
    // LOGIN
    // =====================================================

    {
      path: '/login',
      name: 'login',
      component: LoginView
    },


    // =====================================================
    // MAIN APPLICATION
    // PublicLayout contains:
    // - Desktop sidebar
    // - Desktop top-right profile
    // - Mobile header
    // - Mobile profile menu
    // =====================================================

    {
      path: '/',
      component: PublicLayout,

      children: [

        // =================================================
        // DASHBOARD
        // =================================================

        {
          path: '',
          name: 'home',
          component: HomeView
        },


        // =================================================
        // TRANSACTIONS
        // =================================================

        {
          path: 'transactions',
          name: 'transactions',
          component: TransactionsView
        },


        // =================================================
        // BUDGETS
        // =================================================

        {
          path: 'budgets',
          name: 'budgets',
          component: BudgetsView
        },


        // =================================================
        // SAVINGS
        // =================================================

        {
          path: 'savings',
          name: 'savings',
          component: SavingsView
        },


        // =================================================
        // PROFILE
        // =================================================

        {
          path: 'profile',
          name: 'profile',
          component: ProfileView
        },

        {
          path: 'wallets',
          name: 'wallets',
          component: WalletsView
        },

        {
          path: 'categories',
          name: 'categories',
          component: CategoriesView
        }

      ]
    }

  ]

})

router.beforeEach((to, from, next) => {
  const hasToken = Boolean(
    localStorage.getItem('token') ||
    localStorage.getItem('auth_token') ||
    localStorage.getItem('access_token')
  )

  const isProtectedRoute = to.path !== '/login'

  if (isProtectedRoute && !hasToken) {
    next('/login')
    return
  }

  next()
})

export default router