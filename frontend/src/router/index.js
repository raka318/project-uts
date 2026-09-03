import { createRouter, createWebHistory } from 'vue-router'

import PublicLayout from '../components/PublicLayout.vue'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'

const router = createRouter({

  history: createWebHistory(),

  routes: [

    // LOGIN
    {
      path: '/login',
      component: LoginView
    },

    // MAIN APP
    {
      path: '/',
      component: PublicLayout,

      children: [

        {
          path: '',
          component: HomeView
        }

      ]
    }

  ]

})

export default router