import { createRouter, createWebHistory } from 'vue-router'

// Layout
import PublicLayout from '../components/PublicLayout.vue'

// Existing views
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import TransactionsView from '../views/TransactionsView.vue'
import BudgetsView from '../views/BudgetsView.vue'
import SavingsView from '../views/SavingsView.vue'

// New views
import ProfileView from '../views/ProfileView.vue'
import SettingsView from '../views/SettingsView.vue'

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


        // =================================================
        // SETTINGS
        // =================================================

        {
          path: 'settings',
          name: 'settings',
          component: SettingsView
        }

      ]
    }

  ]

})

export default router