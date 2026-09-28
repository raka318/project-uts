import { createRouter, createWebHistory } from 'vue-router'

// =====================================================
// USER LAYOUT
// =====================================================

import PublicLayout from '../components/PublicLayout.vue'

// =====================================================
// USER VIEWS
// =====================================================

import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import TransactionsView from '../views/TransactionsView.vue'
import BudgetsView from '../views/BudgetsView.vue'
import SavingsView from '../views/SavingsView.vue'
import CategoriesView from '../views/CategoriesView.vue'
import LaporanView from '../views/LaporanView.vue'
import HelpView from '../views/HelpView.vue'
import ProfileView from '../views/ProfileView.vue'
import WalletsView from '../views/WalletsView.vue'

// =====================================================
// ADMIN LAYOUT & VIEWS
// =====================================================

import AdminLayout from '../layouts/AdminLayout.vue'
import AdminDashboard from '../views/admin/AdminDashboard.vue'
import AdminUsers from '../views/admin/AdminUsers.vue'
import AdminTransactions from '../views/admin/AdminTransactions.vue'
import AdminCategories from '../views/admin/AdminCategories.vue'


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
    // MAIN USER APPLICATION
    // =====================================================

    {
      path: '/',
      component: PublicLayout,

      children: [

        // Dashboard
        {
          path: '',
          name: 'home',
          component: HomeView
        },

        // Transactions
        {
          path: 'transactions',
          name: 'transactions',
          component: TransactionsView
        },

        // Budgets
        {
          path: 'budgets',
          name: 'budgets',
          component: BudgetsView
        },

        // Savings
        {
          path: 'savings',
          name: 'savings',
          component: SavingsView
        },

        // Profile
        {
          path: 'profile',
          name: 'profile',
          component: ProfileView
        },

        // Wallets
        {
          path: 'wallets',
          name: 'wallets',
          component: WalletsView
        },

        // Categories
        {
          path: 'categories',
          name: 'categories',
          component: CategoriesView
        },

        // Laporan
        {
          path: 'laporan',
          name: 'laporan',
          component: LaporanView
        },

        // Help
        {
          path: 'help',
          name: 'help',
          component: HelpView
        }

      ]
    },


    // =====================================================
    // ADMIN APPLICATION
    // =====================================================

    {
      path: '/admin',
      component: AdminLayout,

      children: [

        // Admin Dashboard
        {
          path: '',
          name: 'admin-dashboard',
          component: AdminDashboard
        },

        // User Management
        {
          path: 'users',
          name: 'admin-users',
          component: AdminUsers
        },

        // Transaction Management
        {
          path: 'transactions',
          name: 'admin-transactions',
          component: AdminTransactions
        },

        // Category Management
        {
          path: 'categories',
          name: 'admin-categories',
          component: AdminCategories
        }

      ]
    }

  ]
})


// =====================================================
// ROUTE GUARD
// =====================================================

router.beforeEach((to, from, next) => {

  const hasToken = Boolean(
    localStorage.getItem('token') ||
    localStorage.getItem('auth_token') ||
    localStorage.getItem('access_token')
  )

  // Login doesn't require authentication
  const isLoginPage = to.path === '/login'

  // Any page except login requires authentication
  if (!isLoginPage && !hasToken) {
    next('/login')
    return
  }

  next()
})


export default router