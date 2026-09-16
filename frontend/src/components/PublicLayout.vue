<template>
  <div class="app-layout" :class="{ 'sidebar-collapsed': sidebarCollapsed }">

    <!-- =====================================================
         DESKTOP SIDEBAR
    ====================================================== -->
    <aside
      class="desktop-sidebar"
      :class="{ collapsed: sidebarCollapsed }"
    >

      <!-- ================= BRAND ================= -->
      <div class="brand">
        <div class="brand-icon">$</div>

        <span class="brand-name">
          MoneyFlow
        </span>

        <button
          class="sidebar-toggle"
          type="button"
          @click="toggleSidebar"
          :aria-label="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'"
          :title="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'"
        >
          <span :class="{ rotated: sidebarCollapsed }">‹</span>
        </button>
      </div>


      <!-- ================= NAVIGATION ================= -->
      <nav class="sidebar-nav">

        <div class="nav-section-title nav-section-label">
          MENU
        </div>


        <!-- DASHBOARD -->
        <RouterLink
          to="/"
          class="nav-item"
          :class="{ active: route.path === '/' }"
        >
          <svg viewBox="0 0 24 24">
            <path d="M3 10.5L12 3l9 7.5"></path>
            <path d="M5 9.5V21h14V9.5"></path>
            <path d="M9 21v-7h6v7"></path>
          </svg>

          <span class="nav-label">Dashboard</span>
        </RouterLink>


        <!-- TRANSACTIONS -->
        <RouterLink
          to="/transactions"
          class="nav-item"
          :class="{ active: route.path.startsWith('/transactions') }"
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

          <span class="nav-label">Transactions</span>
        </RouterLink>


        <!-- BUDGETS -->
        <RouterLink
          to="/budgets"
          class="nav-item"
          :class="{ active: route.path.startsWith('/budgets') }"
        >
          <svg viewBox="0 0 24 24">
            <path d="M4 19V5"></path>
            <path d="M4 19h16"></path>
            <path d="M7 16v-4"></path>
            <path d="M11 16V8"></path>
            <path d="M15 16v-6"></path>
            <path d="M19 16V5"></path>
          </svg>

          <span class="nav-label">Budgets</span>
        </RouterLink>


        <!-- SAVINGS -->
        <RouterLink
          to="/savings"
          class="nav-item"
          :class="{ active: route.path.startsWith('/savings') }"
        >
          <svg viewBox="0 0 24 24">
            <path d="M12 2v20"></path>

            <path
              d="M17 6.5c-.8-1.2-2.2-2-4.5-2-2.7 0-4.5 1.3-4.5 3.2 0 5.1 9.5 2.4 9.5 7.1 0 2-1.8 3.6-4.8 3.6-2.4 0-4.1-.8-5.2-2.3"
            ></path>
          </svg>

          <span class="nav-label">Savings</span>
        </RouterLink>


        <!-- ================= PERSONAL ================= -->
        <div class="nav-section-title nav-section-label personal-title">
          PERSONAL
        </div>


        <!-- PROFILE -->
        <RouterLink
          to="/profile"
          class="nav-item"
          :class="{ active: route.path.startsWith('/profile') }"
        >
          <svg viewBox="0 0 24 24">
            <circle
              cx="12"
              cy="8"
              r="4"
            ></circle>

            <path
              d="M4 21c0-4.2 3.6-7 8-7s8 2.8 8 7"
            ></path>
          </svg>

          <span class="nav-label">Profile</span>
        </RouterLink>

      </nav>


      <!-- =================================================
           SIDEBAR BOTTOM
      ================================================== -->
      <div class="sidebar-bottom">

        <!-- HELP -->
        <div class="help-card">

          <div class="help-icon">
            ?
          </div>

          <div class="help-text">
            <strong>Need help?</strong>
            <span>We're here for you.</span>
          </div>

          <span class="help-arrow">
            →
          </span>

        </div>


        <!-- =================================================
             CLICKABLE SIDEBAR PROFILE
        ================================================== -->
        <RouterLink
          to="/profile"
          class="sidebar-user"
          :class="{ 'sidebar-user-active': route.path.startsWith('/profile') }"
        >

          <div class="avatar">
            {{ profile.initials }}
          </div>

          <div class="sidebar-user-info">
            <strong>{{ profile.name || 'User' }}</strong>
            <span>Personal Account</span>
          </div>

          <span class="logout-arrow">
            →
          </span>

        </RouterLink>

      </div>

    </aside>


    <!-- =====================================================
         MOBILE HEADER
    ====================================================== -->
    <header class="mobile-header">

      <!-- LOGO -->
      <div class="mobile-brand">

        <div class="brand-icon">
          $
        </div>

        <span>
          MoneyFlow
        </span>

      </div>


      <!-- MOBILE PROFILE -->
      <button
        class="mobile-profile-button"
        @click="toggleProfileMenu"
        aria-label="Open profile menu"
      >
        <div class="avatar">
          {{ profile.initials }}
        </div>
      </button>

    </header>


    <!-- =====================================================
         MOBILE PROFILE MENU
    ====================================================== -->
    <Transition name="profile-menu">

      <div
        v-if="profileMenuOpen"
        class="profile-dropdown mobile-profile-dropdown"
      >

        <!-- ACCOUNT HEADER -->
        <div class="dropdown-user">

          <div class="avatar large">
            {{ profile.initials }}
          </div>

          <div class="dropdown-user-info">

            <strong>
              {{ profile.name || 'User' }}
            </strong>

            <span>
              Personal Account
            </span>

          </div>

        </div>


        <div class="dropdown-divider"></div>


        <!-- PROFILE -->
        <RouterLink
          to="/profile"
          class="dropdown-item"
          @click="closeProfileMenu"
        >

          <svg viewBox="0 0 24 24">
            <circle
              cx="12"
              cy="8"
              r="4"
            ></circle>

            <path
              d="M4 21c0-4.2 3.6-7 8-7s8 2.8 8 7"
            ></path>
          </svg>

          <span>
            Profile
          </span>

        </RouterLink>


        <!-- SETTINGS -->
        <RouterLink
          to="/settings"
          class="dropdown-item"
          @click="closeProfileMenu"
        >

          <svg viewBox="0 0 24 24">
            <circle
              cx="12"
              cy="12"
              r="3"
            ></circle>

            <path
              d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1-1.8 1.8-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.6V20h-2.5v-.1a1.7 1.7 0 0 0-1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1-1.8-1.8.1-.1A1.7 1.7 0 0 0 8.1 15a1.7 1.7 0 0 0-1.6-1H6v-2.5h.1a1.7 1.7 0 0 0 1.6-1A1.7 1.7 0 0 0 7.4 8.6l-.1-.1 1.8-1.8.1.1a1.7 1.7 0 0 0 1.9.3 1.7 1.7 0 0 0 1-1.6V5h2.5v.1a1.7 1.7 0 0 0 1 1.6 1.7 1.7 0 0 0 1.9-.3l.1-.1 1.8 1.8-.1.1a1.7 1.7 0 0 0-.3 1.9 1.7 1.7 0 0 0-.3 1.9 1.7 1.7 0 0 0 1.6 1h.1V14h-.1a1.7 1.7 0 0 0-1.6 1z"
            ></path>
          </svg>

          <span>
            Settings
          </span>

        </RouterLink>


        <div class="dropdown-divider"></div>


        <!-- LOGOUT -->
        <button
          class="dropdown-item logout-item"
          @click="logout"
        >

          <svg viewBox="0 0 24 24">
            <path d="M10 17l5-5-5-5"></path>
            <path d="M15 12H3"></path>
            <path d="M21 3v18"></path>
          </svg>

          <span>
            Log out
          </span>

        </button>

      </div>

    </Transition>


    <!-- =========================
         MOBILE BOTTOM NAVIGATION
    ========================== -->
    <nav class="mobile-bottom-nav">

      <!-- Dashboard -->
      <RouterLink
        to="/"
        class="mobile-bottom-item"
        exact-active-class="active"
      >
        <svg viewBox="0 0 24 24">
          <path d="M3 10.5L12 3l9 7.5"></path>
          <path d="M5 9.5V21h14V9.5"></path>
          <path d="M9 21v-7h6v7"></path>
        </svg>

        <span>Dashboard</span>
      </RouterLink>


      <!-- Transactions -->
      <RouterLink
        to="/transactions"
        class="mobile-bottom-item"
        active-class="active"
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

        <span>Transactions</span>
      </RouterLink>


      <!-- Budgets -->
      <RouterLink
        to="/budgets"
        class="mobile-bottom-item"
        active-class="active"
      >
        <svg viewBox="0 0 24 24">
          <path d="M4 19V5"></path>
          <path d="M4 19h16"></path>
          <path d="M7 16v-4"></path>
          <path d="M11 16V8"></path>
          <path d="M15 16v-6"></path>
          <path d="M19 16V5"></path>
        </svg>

        <span>Budgets</span>
      </RouterLink>


      <!-- Savings -->
      <RouterLink
        to="/savings"
        class="mobile-bottom-item"
        active-class="active"
      >
        <svg viewBox="0 0 24 24">
          <path d="M12 2v20"></path>

          <path
            d="M17 6.5c-.8-1.2-2.2-2-4.5-2-2.7 0-4.5 1.3-4.5 3.2 0 5.1 9.5 2.4 9.5 7.1 0 2-1.8 3.6-4.8 3.6-2.4 0-4.1-.8-5.2-2.3"
          ></path>
        </svg>

        <span>Savings</span>
      </RouterLink>

    </nav>


    <!-- =====================================================
         MAIN AREA
    ====================================================== -->
    <main class="main-area">


      <!-- =================================================
           DESKTOP TOP BAR
      ================================================== -->
      <header class="desktop-topbar">

        <div></div>


        <div class="topbar-right">


          <!-- NOTIFICATION -->
          <button class="notification-button">

            <svg viewBox="0 0 24 24">
              <path
                d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"
              ></path>

              <path d="M10 21h4"></path>
            </svg>

            <span class="notification-dot"></span>

          </button>


          <!-- =================================================
               DESKTOP PROFILE BUTTON
          ================================================== -->
          <button
            class="desktop-user"
            @click="toggleProfileMenu"
          >

            <div class="avatar">
              {{ profile.initials }}
            </div>

            <div class="desktop-user-info">

              <strong>
                {{ profile.name || 'User' }}
              </strong>

              <span>
                Personal
              </span>

            </div>

            <span
              class="chevron"
              :class="{ rotated: profileMenuOpen }"
            >
              ⌄
            </span>

          </button>


          <!-- =================================================
               DESKTOP PROFILE DROPDOWN
          ================================================== -->
          <Transition name="profile-menu">

            <div
              v-if="profileMenuOpen"
              class="profile-dropdown desktop-profile-dropdown"
            >

              <!-- ACCOUNT -->
              <div class="dropdown-user">

                <div class="avatar large">
                  {{ profile.initials }}
                </div>

                <div class="dropdown-user-info">

                  <strong>
                    {{ profile.name || 'User' }}
                  </strong>

                  <span>
                    Personal Account
                  </span>

                </div>

              </div>


              <div class="dropdown-divider"></div>


              <!-- PROFILE -->
              <RouterLink
                to="/profile"
                class="dropdown-item"
                @click="closeProfileMenu"
              >

                <svg viewBox="0 0 24 24">
                  <circle
                    cx="12"
                    cy="8"
                    r="4"
                  ></circle>

                  <path
                    d="M4 21c0-4.2 3.6-7 8-7s8 2.8 8 7"
                  ></path>
                </svg>

                <span>
                  Profile
                </span>

              </RouterLink>


              <!-- SETTINGS -->
              <RouterLink
                to="/settings"
                class="dropdown-item"
                @click="closeProfileMenu"
              >

                <svg viewBox="0 0 24 24">
                  <circle
                    cx="12"
                    cy="12"
                    r="3"
                  ></circle>

                  <path
                    d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1-1.8 1.8-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.6V20h-2.5v-.1a1.7 1.7 0 0 0-1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1-1.8-1.8.1-.1A1.7 1.7 0 0 0 8.1 15a1.7 1.7 0 0 0-1.6-1H6v-2.5h.1a1.7 1.7 0 0 0 1.6-1A1.7 1.7 0 0 0 7.4 8.6l-.1-.1 1.8-1.8.1.1a1.7 1.7 0 0 0 1.9.3 1.7 1.7 0 0 0 1.9-.3l.1-.1 1.8 1.8-.1.1a1.7 1.7 0 0 0-.3 1.9 1.7 1.7 0 0 0 1.6 1h.1V14h-.1a1.7 1.7 0 0 0-1.6 1z"
                  ></path>
                </svg>

                <span>
                  Settings
                </span>

              </RouterLink>


              <div class="dropdown-divider"></div>


              <!-- LOGOUT -->
              <button
                class="dropdown-item logout-item"
                @click="logout"
              >

                <svg viewBox="0 0 24 24">
                  <path d="M10 17l5-5-5-5"></path>
                  <path d="M15 12H3"></path>
                  <path d="M21 3v18"></path>
                </svg>

                <span>
                  Log out
                </span>

              </button>

            </div>

          </Transition>

        </div>

      </header>


      <!-- =================================================
           PAGE CONTENT
      ================================================== -->
      <section class="page-content">

        <RouterView />

      </section>

    </main>

  </div>
</template>


<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

const API_BASE = import.meta.env.VITE_API_BASE_URL || ''

/* =========================================
   SIDEBAR
========================================= */

const SIDEBAR_STORAGE_KEY = 'moneyflow_sidebar_collapsed'

const sidebarCollapsed = ref(
  localStorage.getItem(SIDEBAR_STORAGE_KEY) === 'true'
)

const toggleSidebar = () => {
  sidebarCollapsed.value = !sidebarCollapsed.value

  localStorage.setItem(
    SIDEBAR_STORAGE_KEY,
    String(sidebarCollapsed.value)
  )
}


/* =========================================
   PROFILE
========================================= */

const profileMenuOpen = ref(false)
const loadingProfile = ref(false)

const profile = ref({
  name: '',
  email: '',
  initials: 'U',
})

/* =========================================
   PROFILE
========================================= */

const getInitials = (name) => {
  if (!name) return 'U'

  const parts = name.trim().split(/\s+/).filter(Boolean)

  if (parts.length === 1) {
    return parts[0].slice(0, 2).toUpperCase()
  }

  return (parts[0][0] + parts[1][0]).toUpperCase()
}

const clearAuth = () => {
  localStorage.removeItem('auth_token')
  localStorage.removeItem('remember')
}

const fetchProfile = async () => {
  const token = localStorage.getItem('auth_token')

  if (!token) {
    return
  }

  loadingProfile.value = true

  try {
    const response = await axios.get(`${API_BASE}/api/profile`, {
      headers: {
        Authorization: `Bearer ${token}`,
        Accept: 'application/json',
      },
    })

    const user = response.data?.data

    if (user) {
      profile.value = {
        name: user.name || 'User',
        email: user.email || '',
        initials: getInitials(user.name),
      }
    }
  } catch (error) {
    if (error.response?.status === 401) {
      clearAuth()
      router.push('/login')
    }
  } finally {
    loadingProfile.value = false
  }
}


/* =========================================
   PROFILE MENU
========================================= */

const toggleProfileMenu = async () => {
  profileMenuOpen.value = !profileMenuOpen.value

  // Refresh the profile whenever the menu opens.
  // This also picks up changes made on the Profile page.
  if (profileMenuOpen.value) {
    await fetchProfile()
  }
}

const closeProfileMenu = () => {
  profileMenuOpen.value = false
}


/* =========================================
   LOGOUT
========================================= */

const logout = async () => {
  profileMenuOpen.value = false

  const token = localStorage.getItem('auth_token')

  try {
    if (token) {
      await axios.post(
        `${API_BASE}/api/logout`,
        {},
        {
          headers: {
            Authorization: `Bearer ${token}`,
            Accept: 'application/json',
          },
        },
      )
    }
  } catch (error) {
    // Clear the local token even if the API request fails.
  } finally {
    clearAuth()
    router.push('/login')
  }
}


/* =========================================
   CLOSE MENU WITH ESC
========================================= */

const handleEscape = (event) => {
  if (event.key === 'Escape') {
    closeProfileMenu()
  }
}


/* =========================================
   START
========================================= */

onMounted(() => {
  fetchProfile()
  document.addEventListener('keydown', handleEscape)
})

onBeforeUnmount(() => {
  document.removeEventListener('keydown', handleEscape)
})
</script>


<style scoped>

/* =========================================================
   GLOBAL
========================================================= */

* {
  box-sizing: border-box;
}

.app-layout {
  min-height: 100vh;
  background: #f7f8fc;
  color: #1d2433;
  font-family:
    Inter,
    -apple-system,
    BlinkMacSystemFont,
    "Segoe UI",
    sans-serif;
}


/* =========================================================
   DESKTOP SIDEBAR
========================================================= */

.desktop-sidebar {
  position: fixed;
  top: 0;
  left: 0;

  width: 270px;
  height: 100vh;

  display: flex;
  flex-direction: column;

  background: #ffffff;
  border-right: 1px solid #edf0f6;

  z-index: 100;

  overflow: hidden;

  transition: width 0.25s ease;
}

.desktop-sidebar.collapsed {
  width: 76px;
}


/* =========================================================
   BRAND
========================================================= */

.brand {
  height: 100px;

  display: flex;
  align-items: center;

  padding: 0 28px;

  gap: 12px;

  font-size: 21px;
  font-weight: 750;

  color: #172033;

  position: relative;

  transition: padding 0.25s ease, justify-content 0.25s ease;
}

.brand-icon {
  width: 34px;
  height: 34px;

  display: flex;
  align-items: center;
  justify-content: center;

  flex-shrink: 0;

  background: #6c63ff;
  color: white;

  border-radius: 10px;

  font-size: 18px;
  font-weight: 700;
}

.brand-name {
  white-space: nowrap;
  opacity: 1;
  overflow: hidden;

  transition:
    opacity 0.18s ease,
    width 0.25s ease;
}

.sidebar-toggle {
  position: absolute;

  right: 12px;

  width: 30px;
  height: 30px;

  display: flex;
  align-items: center;
  justify-content: center;

  padding: 0;

  border: 0;
  border-radius: 8px;

  background: transparent;

  color: #8f96a8;

  font-size: 25px;
  line-height: 1;

  cursor: pointer;

  transition:
    background 0.2s ease,
    color 0.2s ease,
    right 0.25s ease,
    transform 0.25s ease;
}

.sidebar-toggle:hover {
  background: #f1efff;
  color: #6655e9;
}

.sidebar-toggle span {
  display: block;

  transition: transform 0.25s ease;
}

.sidebar-toggle span.rotated {
  transform: rotate(180deg);
}

.sidebar-toggle:focus-visible {
  outline: 2px solid #6655e9;
  outline-offset: 2px;
}


/* =========================================================
   COLLAPSED SIDEBAR
========================================================= */

.desktop-sidebar.collapsed .brand {
  justify-content: center;
  padding-left: 10px;
  padding-right: 10px;
}

.desktop-sidebar.collapsed .brand-name {
  width: 0;
  margin: 0;
  opacity: 0;
}

.desktop-sidebar.collapsed .sidebar-toggle {
  right: 50%;
  transform: translateX(50%);
}

.desktop-sidebar.collapsed .nav-item {
  justify-content: center;
  gap: 0;
  padding-left: 0;
  padding-right: 0;
}

.desktop-sidebar.collapsed .nav-label {
  width: 0;
  margin: 0;
  opacity: 0;
  overflow: hidden;
}

.desktop-sidebar.collapsed .nav-section-label {
  height: 0;
  margin: 0;
  padding-top: 0;
  padding-bottom: 0;
  opacity: 0;
  overflow: hidden;
}

.desktop-sidebar.collapsed .help-card {
  justify-content: center;
  padding-left: 0;
  padding-right: 0;
}

.desktop-sidebar.collapsed .help-card .help-text,
.desktop-sidebar.collapsed .help-card .help-arrow {
  display: none;
}

.desktop-sidebar.collapsed .sidebar-user {
  justify-content: center;
  padding-left: 0;
  padding-right: 0;
}

.desktop-sidebar.collapsed .sidebar-user-info,
.desktop-sidebar.collapsed .logout-arrow {
  display: none;
}


/* =========================================================
   SIDEBAR NAV
========================================================= */

.sidebar-nav {
  padding: 25px 15px;
}

.nav-section-title {
  padding: 0 15px;

  margin: 0 0 13px;

  font-size: 9px;
  font-weight: 800;

  letter-spacing: 1.2px;

  color: #a3a9b8;

  white-space: nowrap;

  transition:
    opacity 0.15s ease,
    height 0.25s ease,
    margin 0.25s ease,
    padding 0.25s ease;
}

.personal-title {
  margin-top: 38px;
}


/* =========================================================
   NAV ITEM
========================================================= */

.nav-item {
  height: 48px;

  display: flex;
  align-items: center;

  gap: 15px;

  padding: 0 13px;

  margin-bottom: 5px;

  border-radius: 10px;

  color: #667085;

  text-decoration: none;

  font-size: 14px;
  font-weight: 550;

  transition:
    background 0.2s ease,
    color 0.2s ease,
    padding 0.25s ease,
    gap 0.25s ease;
}

.nav-label {
  white-space: nowrap;
  opacity: 1;

  transition:
    opacity 0.15s ease,
    width 0.25s ease;
}

.nav-item svg {
  width: 17px;
  height: 17px;

  fill: none;
  stroke: currentColor;

  stroke-width: 1.7;

  stroke-linecap: round;
  stroke-linejoin: round;

  flex-shrink: 0;
}

.nav-item:hover {
  background: #f6f5ff;
  color: #655df5;
}


/* =========================================================
   ACTIVE NAV
========================================================= */

.nav-item.active {
  background: #efedff;
  color: #6259f5;
  font-weight: 700;
}


/* =========================================================
   SIDEBAR BOTTOM
========================================================= */

.sidebar-bottom {
  margin-top: auto;
  padding: 15px;
}


/* =========================================================
   HELP
========================================================= */

.help-card {
  min-height: 70px;

  display: flex;
  align-items: center;

  gap: 11px;

  padding: 12px;

  border-radius: 12px;

  background: #f5f3ff;

  transition:
    padding 0.25s ease,
    justify-content 0.25s ease;
}

.help-icon {
  width: 31px;
  height: 31px;

  display: flex;
  align-items: center;
  justify-content: center;

  flex-shrink: 0;

  border-radius: 50%;

  background: white;

  color: #6259f5;

  font-weight: 800;
}

.help-text {
  display: flex;
  flex-direction: column;

  gap: 3px;

  flex: 1;
}

.help-text strong {
  font-size: 11px;
  color: #222a3a;
}

.help-text span {
  font-size: 9px;
  color: #a0a5b3;
}

.help-arrow {
  color: #6259f5;
  font-size: 15px;
}


/* =========================================================
   SIDEBAR USER
========================================================= */

.sidebar-user {
  display: flex;
  align-items: center;

  gap: 12px;

  margin-top: 14px;

  padding: 15px 7px 5px;

  border-top: 1px solid #eceef4;

  text-decoration: none;

  cursor: pointer;

  transition:
    opacity 0.2s ease,
    padding 0.25s ease,
    justify-content 0.25s ease;
}

.sidebar-user:hover {
  opacity: 0.75;
}

.sidebar-user-active {
  opacity: 1;
}

.avatar {
  width: 35px;
  height: 35px;

  flex-shrink: 0;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 50%;

  background: #7067f7;

  color: white;

  font-size: 11px;
  font-weight: 750;
}

.sidebar-user-info {
  display: flex;
  flex-direction: column;

  flex: 1;

  gap: 3px;

  min-width: 0;
}

.sidebar-user-info strong {
  font-size: 11px;
  color: #222a3a;
}

.sidebar-user-info span {
  font-size: 9px;
  color: #a0a5b3;
}

.logout-arrow {
  color: #98a0b0;
  font-size: 14px;
}


/* =========================================================
   MAIN AREA
========================================================= */

.main-area {
  min-height: 100vh;

  margin-left: 270px;

  transition: margin-left 0.25s ease;
}

.app-layout.sidebar-collapsed .main-area {
  margin-left: 76px;
}


/* =========================================================
   DESKTOP TOP BAR
========================================================= */

.desktop-topbar {
  height: 86px;

  position: relative;

  display: flex;
  align-items: center;
  justify-content: space-between;

  padding: 0 30px;

  background: #ffffff;

  border-bottom: 1px solid #edf0f6;

  z-index: 200;
}

.topbar-right {
  display: flex;
  align-items: center;

  gap: 22px;

  position: relative;
}


/* =========================================================
   NOTIFICATION
========================================================= */

.notification-button {
  position: relative;

  width: 38px;
  height: 38px;

  display: flex;
  align-items: center;
  justify-content: center;

  border: none;

  background: transparent;

  cursor: pointer;
}

.notification-button svg {
  width: 19px;
  height: 19px;

  fill: none;

  stroke: #858da0;

  stroke-width: 1.6;

  stroke-linecap: round;
  stroke-linejoin: round;
}

.notification-dot {
  position: absolute;

  top: 7px;
  right: 7px;

  width: 5px;
  height: 5px;

  border-radius: 50%;

  background: #6c63ff;
}


/* =========================================================
   DESKTOP USER BUTTON
========================================================= */

.desktop-user {
  display: flex;
  align-items: center;

  gap: 11px;

  padding: 5px;

  border: none;

  background: transparent;

  cursor: pointer;

  text-align: left;

  border-radius: 10px;

  transition: background 0.2s ease;
}

.desktop-user:hover {
  background: #f7f6ff;
}

.desktop-user-info {
  display: flex;
  flex-direction: column;

  gap: 3px;
}

.desktop-user-info strong {
  font-size: 12px;
  color: #20283a;
}

.desktop-user-info span {
  font-size: 9px;
  color: #9da4b2;
}

.chevron {
  margin-left: 5px;

  color: #8e96a6;

  font-size: 14px;

  transition: transform 0.2s ease;
}

.chevron.rotated {
  transform: rotate(180deg);
}


/* =========================================================
   PROFILE DROPDOWN
========================================================= */

.profile-dropdown {
  position: absolute;

  width: 245px;

  padding: 10px;

  background: #ffffff;

  border: 1px solid #edf0f6;

  border-radius: 14px;

  box-shadow:
    0 18px 45px rgba(25, 32, 52, 0.14);

  z-index: 2000;
}


/* Desktop */

.desktop-profile-dropdown {
  top: 58px;
  right: 0;
  display: block;
}


/* Mobile dropdown is hidden on desktop.
   The desktop and mobile menus share the same
   reactive state, so only the correct one is visible. */

.mobile-profile-dropdown {
  top: 72px;
  right: 10px;
  display: none;
}


/* =========================================================
   DROPDOWN USER
========================================================= */

.dropdown-user {
  display: flex;
  align-items: center;

  gap: 12px;

  padding: 8px;
}

.avatar.large {
  width: 42px;
  height: 42px;

  font-size: 12px;
}

.dropdown-user-info {
  display: flex;
  flex-direction: column;

  gap: 4px;
}

.dropdown-user-info strong {
  font-size: 13px;
  color: #20283a;
}

.dropdown-user-info span {
  font-size: 10px;
  color: #999fad;
}


/* =========================================================
   DROPDOWN DIVIDER
========================================================= */

.dropdown-divider {
  height: 1px;

  margin: 8px 0;

  background: #edf0f5;
}


/* =========================================================
   DROPDOWN ITEM
========================================================= */

.dropdown-item {
  width: 100%;
  min-height: 43px;

  display: flex;
  align-items: center;

  gap: 12px;

  padding: 0 10px;

  border: none;

  border-radius: 9px;

  background: transparent;

  color: #5f687b;

  text-decoration: none;

  font-family: inherit;

  font-size: 13px;
  font-weight: 550;

  cursor: pointer;

  transition:
    background 0.2s ease,
    color 0.2s ease;
}

.dropdown-item svg {
  width: 17px;
  height: 17px;

  fill: none;

  stroke: currentColor;

  stroke-width: 1.7;

  stroke-linecap: round;
  stroke-linejoin: round;
}

.dropdown-item:hover {
  background: #f4f2ff;
  color: #6259f5;
}


/* Logout */

.logout-item {
  color: #687184;
}


/* =========================================================
   PROFILE MENU TRANSITION
========================================================= */

.profile-menu-enter-active,
.profile-menu-leave-active {
  transition:
    opacity 0.18s ease,
    transform 0.18s ease;
}

.profile-menu-enter-from,
.profile-menu-leave-to {
  opacity: 0;

  transform: translateY(-7px);
}


/* =========================================================
   PAGE CONTENT
========================================================= */

.page-content {
  padding: 32px;
}


/* =========================================================
   MOBILE HEADER
========================================================= */

/*
   IMPORTANT:
   Hidden by default.
   Only shown inside max-width: 700px.
*/

.mobile-header {
  display: none;
}


/* =========================================================
   MOBILE BOTTOM NAVIGATION
========================================================= */

/*
   IMPORTANT:
   Hidden by default.
   This prevents it from appearing on desktop.
*/

.mobile-bottom-nav {
  display: none;
}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 900px) and (min-width: 701px) {

  .desktop-sidebar {
    width: 220px;
  }

  .desktop-sidebar.collapsed {
    width: 76px;
  }

  .main-area {
    margin-left: 220px;
  }

  .app-layout.sidebar-collapsed .main-area {
    margin-left: 76px;
  }

  .brand {
    padding: 0 20px;
  }

  .page-content {
    padding: 25px;
  }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 700px) {

  /* =======================================================
     HIDE DESKTOP SIDEBAR
  ======================================================= */

  .desktop-sidebar {
    display: none;
  }


  /* =======================================================
     HIDE DESKTOP TOPBAR
  ======================================================= */

  .desktop-topbar {
    display: none;
  }


  /* =======================================================
     MAIN AREA
  ======================================================= */

  .main-area {
    margin-left: 0 !important;

    padding-top: 64px;

    /*
      Leave room for the bottom navigation.
      This prevents the last content from being hidden.
    */
    padding-bottom: 80px;
  }


  /* =======================================================
     MOBILE HEADER
  ======================================================= */

  .mobile-header {
    position: fixed;

    top: 0;
    left: 0;
    right: 0;

    height: 64px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    background: #ffffff;

    border-bottom: 1px solid #edf0f6;

    z-index: 1000;
  }


  /* =======================================================
     MOBILE LOGO
  ======================================================= */

  .mobile-brand {
    display: flex;
    align-items: center;

    gap: 9px;

    padding-left: 10px;

    font-size: 18px;

    font-weight: 750;

    color: #172033;
  }

  .mobile-brand .brand-icon {
    width: 32px;
    height: 32px;

    border-radius: 9px;
  }


  /* =======================================================
     MOBILE PROFILE BUTTON
  ======================================================= */

  .mobile-profile-button {
    width: 50px;
    height: 64px;

    display: flex;
    align-items: center;
    justify-content: center;

    border: none;

    background: transparent;

    cursor: pointer;
  }

  .mobile-profile-button .avatar {
    width: 35px;
    height: 35px;
  }


  /* =======================================================
     MOBILE PROFILE DROPDOWN
  ======================================================= */

  /* Only show the mobile dropdown on mobile. */
  .desktop-profile-dropdown {
    display: none;
  }

  .mobile-profile-dropdown {
    display: block;

    width: min(
      300px,
      calc(100vw - 20px)
    );

    position: fixed;

    top: 72px;
    right: 10px;
  }


  /* =======================================================
     MOBILE PAGE
  ======================================================= */

  .page-content {
    padding: 20px 15px;
  }


  /* =======================================================
     MOBILE BOTTOM NAVIGATION
  ======================================================= */

  .mobile-bottom-nav {
    display: grid;

    position: fixed;

    left: 0;
    right: 0;
    bottom: 0;

    height: 64px;

    background: #ffffff;

    border-top: 1px solid #e8eaf0;

    z-index: 1000;

    grid-template-columns: repeat(4, 1fr);
  }


  /* =======================================================
     MOBILE BOTTOM NAV ITEM
  ======================================================= */

  .mobile-bottom-item {
    position: relative;

    display: flex;
    flex-direction: column;

    align-items: center;
    justify-content: center;

    gap: 5px;

    color: #8b93a5;

    text-decoration: none;

    font-size: 10px;

    font-weight: 600;

    transition:
      color 0.2s ease,
      background 0.2s ease;
  }


  .mobile-bottom-item svg {
    width: 19px;
    height: 19px;

    fill: none;

    stroke: currentColor;

    stroke-width: 1.7;

    stroke-linecap: round;
    stroke-linejoin: round;
  }


  /* Active */

  .mobile-bottom-item.active {
    color: #6259f5;
  }


  .mobile-bottom-item.active::before {
    content: "";

    position: absolute;

    top: 0;

    width: 38px;
    height: 3px;

    background: #6259f5;

    border-radius:
      0 0 5px 5px;
  }


  .mobile-bottom-item:hover {
    color: #6259f5;
  }

}


/* =========================================================
   SMALL PHONE
========================================================= */

@media (max-width: 400px) {

  .mobile-brand span {
    font-size: 16px;
  }

  .mobile-brand {
    padding-left: 7px;
  }

  .page-content {
    padding-left: 12px;
    padding-right: 12px;
  }

}

</style>
