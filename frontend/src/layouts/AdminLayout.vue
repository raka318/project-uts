<template>
  <div class="admin-layout">

    <!-- SIDEBAR -->
    <aside
      class="admin-sidebar"
      :class="{ collapsed: sidebarCollapsed }"
    >

      <!-- BRAND -->
      <div class="brand">

        <div class="brand-icon">
          $
        </div>

        <span class="brand-name">
          MoneyFlow
        </span>

        <button
          class="sidebar-toggle"
          @click="toggleSidebar"
          :aria-label="
            sidebarCollapsed
              ? 'Expand sidebar'
              : 'Collapse sidebar'
          "
        >
          {{ sidebarCollapsed ? '›' : '‹' }}
        </button>

      </div>


      <!-- NAVIGATION -->
      <nav class="sidebar-nav">

        <div class="section-title">
          ADMIN PANEL
        </div>


        <!-- DASHBOARD -->
        <RouterLink
          to="/admin"
          class="nav-item"
          :class="{
            active: route.path === '/admin'
          }"
        >

          <svg viewBox="0 0 24 24">
            <path d="M3 10.5L12 3l9 7.5"></path>
            <path d="M5 9.5V21h14V9.5"></path>
            <path d="M9 21v-7h6v7"></path>
          </svg>

          <span>
            Dashboard
          </span>

        </RouterLink>


        <!-- USERS -->
        <RouterLink
          to="/admin/users"
          class="nav-item"
          :class="{
            active:
              route.path.startsWith('/admin/users')
          }"
        >

          <svg viewBox="0 0 24 24">
            <circle
              cx="9"
              cy="8"
              r="4"
            ></circle>

            <path
              d="M2 21c0-4 3-7 7-7s7 3 7 7"
            ></path>

            <path
              d="M17 11c2.8 0 5 2 5 5"
            ></path>

            <path
              d="M17 4a4 4 0 0 1 0 8"
            ></path>
          </svg>

          <span>
            User Management
          </span>

        </RouterLink>


        <!-- TRANSACTIONS -->
        <RouterLink
          to="/admin/transactions"
          class="nav-item"
          :class="{
            active:
              route.path.startsWith('/admin/transactions')
          }"
        >

          <svg viewBox="0 0 24 24">

            <rect
              x="5"
              y="3"
              width="14"
              height="18"
              rx="2"
            ></rect>

            <path d="M8 8h8"></path>
            <path d="M8 12h8"></path>
            <path d="M8 16h5"></path>

          </svg>

          <span>
            Transaction Management
          </span>

        </RouterLink>


        <!-- CATEGORIES -->
        <RouterLink
          to="/admin/categories"
          class="nav-item"
          :class="{
            active:
              route.path.startsWith('/admin/categories')
          }"
        >

          <svg viewBox="0 0 24 24">

            <path d="M4 5h16"></path>
            <path d="M4 12h16"></path>
            <path d="M4 19h16"></path>

            <circle
              cx="9"
              cy="5"
              r="2"
            ></circle>

            <circle
              cx="15"
              cy="12"
              r="2"
            ></circle>

            <circle
              cx="10"
              cy="19"
              r="2"
            ></circle>

          </svg>

          <span>
            Category Management
          </span>

        </RouterLink>

      </nav>


      <!-- BOTTOM -->
      <div class="sidebar-bottom">

        <div class="admin-divider"></div>


        <!-- BACK TO USER APP -->
        <RouterLink
          to="/"
          class="back-user"
        >

          <span>
            ←
          </span>

          <span>
            Back to User App
          </span>

        </RouterLink>


        <!-- LOGOUT -->
        <button
          class="logout-button"
          @click="logout"
        >

          <span>
            ↪
          </span>

          <span>
            Log out
          </span>

        </button>

      </div>

    </aside>


    <!-- MAIN -->
    <main class="admin-main">

      <!-- TOPBAR -->
      <header class="admin-topbar">

        <div class="topbar-title">

          <span class="admin-eyebrow">
            MONEYFLOW
          </span>

          <strong>
            Administration
          </strong>

        </div>


        <!-- ADMIN USER -->
        <div class="admin-user">

          <div class="admin-avatar">
            A
          </div>

          <div class="admin-user-info">

            <strong>
              Administrator
            </strong>

            <span>
              Admin Account
            </span>

          </div>

        </div>

      </header>


      <!-- PAGE -->
      <section class="admin-content">

        <RouterView />

      </section>

    </main>

  </div>
</template>


<script setup>
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const sidebarCollapsed = ref(false)

const toggleSidebar = () => {
  sidebarCollapsed.value =
    !sidebarCollapsed.value
}


const logout = () => {

  localStorage.removeItem('auth_token')
  localStorage.removeItem('token')
  localStorage.removeItem('access_token')
  localStorage.removeItem('sanctum_token')
  localStorage.removeItem('user')
  localStorage.removeItem('is_logged_in')
  localStorage.removeItem('remember')

  router.push('/login')
}
</script>


<style scoped>
* {
  box-sizing: border-box;
}

.admin-layout {
  min-height: 100vh;

  background: #f7f8fc;
  color: #20283a;

  font-family:
    Inter,
    -apple-system,
    BlinkMacSystemFont,
    "Segoe UI",
    sans-serif;
}


/* SIDEBAR */

.admin-sidebar {
  position: fixed;

  inset: 0 auto 0 0;

  width: 270px;

  display: flex;
  flex-direction: column;

  background: #ffffff;

  border-right: 1px solid #edf0f6;

  z-index: 100;

  transition:
    width .25s ease;
}

.admin-sidebar.collapsed {
  width: 78px;
}


/* BRAND */

.brand {
  height: 100px;

  display: flex;
  align-items: center;

  gap: 12px;

  padding: 0 25px;

  position: relative;
}

.brand-icon {
  width: 35px;
  height: 35px;

  display: flex;
  align-items: center;
  justify-content: center;

  flex-shrink: 0;

  border-radius: 10px;

  background: #6c63ff;
  color: white;

  font-size: 18px;
  font-weight: 700;
}

.brand-name {
  font-size: 21px;
  font-weight: 750;

  white-space: nowrap;
}

.sidebar-toggle {
  position: absolute;

  right: 12px;

  width: 30px;
  height: 30px;

  border: 0;
  border-radius: 8px;

  background: transparent;
  color: #8f96a8;

  font-size: 25px;

  cursor: pointer;
}

.sidebar-toggle:hover {
  background: #f1efff;
  color: #6655e9;
}


/* COLLAPSED */

.admin-sidebar.collapsed .brand {
  justify-content: center;
  padding: 0;
}

.admin-sidebar.collapsed .brand-name {
  display: none;
}

.admin-sidebar.collapsed .sidebar-toggle {
  right: 8px;
  transform: translateX(50%);
}

.admin-sidebar.collapsed .nav-item {
  justify-content: center;
  padding: 0;
}

.admin-sidebar.collapsed .nav-item span,
.admin-sidebar.collapsed .section-title {
  display: none;
}

.admin-sidebar.collapsed .back-user span:last-child,
.admin-sidebar.collapsed .logout-button span:last-child {
  display: none;
}


/* NAVIGATION */

.sidebar-nav {
  padding: 25px 15px;
}

.section-title {
  padding: 0 13px;

  margin-bottom: 13px;

  color: #a3a9b8;

  font-size: 9px;
  font-weight: 800;

  letter-spacing: 1.2px;
}

.nav-item {
  height: 50px;

  display: flex;
  align-items: center;

  gap: 14px;

  padding: 0 13px;

  margin-bottom: 6px;

  border-radius: 10px;

  color: #667085;

  text-decoration: none;

  font-size: 13px;
  font-weight: 550;

  transition: .2s ease;
}

.nav-item svg {
  width: 18px;
  height: 18px;

  flex-shrink: 0;

  fill: none;
  stroke: currentColor;

  stroke-width: 1.7;

  stroke-linecap: round;
  stroke-linejoin: round;
}

.nav-item:hover {
  background: #f6f5ff;
  color: #655df5;
}

.nav-item.active {
  background: #efedff;
  color: #6259f5;

  font-weight: 700;
}


/* BOTTOM */

.sidebar-bottom {
  margin-top: auto;

  padding: 15px;
}

.admin-divider {
  height: 1px;

  margin-bottom: 12px;

  background: #edf0f6;
}

.back-user,
.logout-button {
  width: 100%;
  height: 44px;

  display: flex;
  align-items: center;

  gap: 12px;

  padding: 0 12px;

  border: 0;
  border-radius: 10px;

  background: transparent;

  color: #667085;

  text-decoration: none;

  font-family: inherit;
  font-size: 13px;

  cursor: pointer;
}

.back-user:hover,
.logout-button:hover {
  background: #f6f5ff;
  color: #6259f5;
}


/* MAIN */

.admin-main {
  min-height: 100vh;

  margin-left: 270px;

  transition:
    margin-left .25s ease;
}

.admin-sidebar.collapsed ~ .admin-main {
  margin-left: 78px;
}


/* TOPBAR */

.admin-topbar {
  height: 86px;

  display: flex;
  align-items: center;
  justify-content: space-between;

  padding: 0 32px;

  background: white;

  border-bottom: 1px solid #edf0f6;
}

.admin-eyebrow {
  display: block;

  margin-bottom: 3px;

  color: #8e96a6;

  font-size: 9px;
  font-weight: 800;

  letter-spacing: 1.3px;
}

.admin-topbar strong {
  font-size: 16px;
}

.admin-user {
  display: flex;
  align-items: center;

  gap: 10px;
}

.admin-user-info {
  display: flex;
  flex-direction: column;

  gap: 3px;
}

.admin-user-info strong {
  font-size: 12px;
}

.admin-user-info span {
  font-size: 9px;

  color: #9da4b2;
}

.admin-avatar {
  width: 36px;
  height: 36px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 50%;

  background: #6c63ff;
  color: white;

  font-size: 12px;
  font-weight: 700;
}


/* CONTENT */

.admin-content {
  padding: 32px;
}


/* RESPONSIVE */

@media (max-width: 800px) {

  .admin-sidebar {
    width: 78px;
  }

  .admin-sidebar .brand-name,
  .admin-sidebar .nav-item span,
  .admin-sidebar .section-title,
  .admin-sidebar .back-user span:last-child,
  .admin-sidebar .logout-button span:last-child {
    display: none;
  }

  .admin-sidebar .brand,
  .admin-sidebar .nav-item {
    justify-content: center;

    padding-left: 0;
    padding-right: 0;
  }

  .admin-main {
    margin-left: 78px;
  }

  .admin-topbar {
    padding: 0 20px;
  }

  .admin-content {
    padding: 20px;
  }

  .admin-user-info {
    display: none;
  }
}


@media (max-width: 550px) {

  .admin-sidebar {
    display: none;
  }

  .admin-main {
    margin-left: 0;
  }

  .admin-content {
    padding: 18px 14px;
  }
}
</style>