<template>
  <div class="app-layout">

    <!-- =========================
         DESKTOP SIDEBAR
    ========================== -->
    <aside class="desktop-sidebar">

      <!-- Logo -->
      <div class="brand">
        <div class="brand-icon">$</div>
        <span>MoneyFlow</span>
      </div>

      <!-- Navigation -->
      <nav class="sidebar-nav">

        <div class="nav-section-title">
          MENU
        </div>

        <RouterLink
          to="/"
          class="nav-item"
          :class="{ active: isActive('/') }"
        >
          <svg viewBox="0 0 24 24">
            <path d="M3 10.5L12 3l9 7.5"></path>
            <path d="M5 9.5V21h14V9.5"></path>
            <path d="M9 21v-7h6v7"></path>
          </svg>

          <span>Dashboard</span>
        </RouterLink>

        <RouterLink
          to="/transactions"
          class="nav-item"
          :class="{ active: isActive('/transactions') }"
        >
          <svg viewBox="0 0 24 24">
            <rect x="5" y="3" width="14" height="18" rx="2"></rect>
            <path d="M8 8h8"></path>
            <path d="M8 12h8"></path>
            <path d="M8 16h5"></path>
          </svg>

          <span>Transactions</span>
        </RouterLink>

        <RouterLink
          to="/budgets"
          class="nav-item"
          :class="{ active: isActive('/budgets') }"
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

        <RouterLink
          to="/savings"
          class="nav-item"
          :class="{ active: isActive('/savings') }"
        >
          <svg viewBox="0 0 24 24">
            <path d="M12 2v20"></path>
            <path d="M17 6.5c-.8-1.2-2.2-2-4.5-2-2.7 0-4.5 1.3-4.5 3.2 0 5.1 9.5 2.4 9.5 7.1 0 2-1.8 3.6-4.8 3.6-2.4 0-4.1-.8-5.2-2.3"></path>
          </svg>

          <span>Savings</span>
        </RouterLink>

        <div class="nav-section-title personal-title">
          PERSONAL
        </div>

        <RouterLink
          to="/profile"
          class="nav-item"
          :class="{ active: isActive('/profile') }"
        >
          <svg viewBox="0 0 24 24">
            <circle cx="12" cy="8" r="4"></circle>
            <path d="M4 21c0-4.2 3.6-7 8-7s8 2.8 8 7"></path>
          </svg>

          <span>Profile</span>
        </RouterLink>

        <RouterLink
          to="/settings"
          class="nav-item"
          :class="{ active: isActive('/settings') }"
        >
          <svg viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="3"></circle>
            <path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1-1.8 1.8-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.6V20h-2.5v-.1a1.7 1.7 0 0 0-1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1-1.8-1.8.1-.1A1.7 1.7 0 0 0 8.1 15a1.7 1.7 0 0 0-1.6-1H6v-2.5h.1a1.7 1.7 0 0 0 1.6-1A1.7 1.7 0 0 0 7.4 8.6l-.1-.1 1.8-1.8.1.1a1.7 1.7 0 0 0 1.9.3 1.7 1.7 0 0 0 1-1.6V5h2.5v.1a1.7 1.7 0 0 0 1 1.6 1.7 1.7 0 0 0 1.9-.3l.1-.1 1.8 1.8-.1.1a1.7 1.7 0 0 0-.3 1.9 1.7 1.7 0 0 0 1.6 1h.1V14h-.1a1.7 1.7 0 0 0-1.6 1z"></path>
          </svg>

          <span>Settings</span>
        </RouterLink>

      </nav>

      <!-- Help -->
      <div class="sidebar-bottom">

        <div class="help-card">
          <div class="help-icon">?</div>

          <div class="help-text">
            <strong>Need help?</strong>
            <span>We're here for you.</span>
          </div>

          <span class="help-arrow">→</span>
        </div>

        <div class="sidebar-user">
          <div class="avatar">
            JD
          </div>

          <div class="sidebar-user-info">
            <strong>John Doe</strong>
            <span>Personal Account</span>
          </div>

          <span class="logout-arrow">→|</span>
        </div>

      </div>
    </aside>


    <!-- =========================
         MOBILE HEADER
    ========================== -->
    <header class="mobile-header">

      <!-- Logo touches left side -->
      <div class="mobile-brand">
        <div class="brand-icon">$</div>
        <span>MoneyFlow</span>
      </div>

      <!-- Profile button -->
      <button
        class="mobile-profile-button"
        @click="mobileMenuOpen = !mobileMenuOpen"
        aria-label="Open profile menu"
      >
        <div class="avatar">
          JD
        </div>
      </button>

    </header>


    <!-- =========================
         MOBILE PROFILE MENU
    ========================== -->
    <Transition name="mobile-menu">
      <div
        v-if="mobileMenuOpen"
        class="mobile-menu"
      >

        <div class="mobile-user-header">
          <div class="avatar large">
            JD
          </div>

          <div>
            <strong>John Doe</strong>
            <span>Personal Account</span>
          </div>
        </div>

        <div class="mobile-divider"></div>

        <RouterLink
          to="/"
          class="mobile-nav-item"
          @click="closeMobileMenu"
        >
          <span>⌂</span>
          Dashboard
        </RouterLink>

        <RouterLink
          to="/transactions"
          class="mobile-nav-item"
          @click="closeMobileMenu"
        >
          <span>▤</span>
          Transactions
        </RouterLink>

        <RouterLink
          to="/budgets"
          class="mobile-nav-item"
          @click="closeMobileMenu"
        >
          <span>▥</span>
          Budgets
        </RouterLink>

        <RouterLink
          to="/savings"
          class="mobile-nav-item"
          @click="closeMobileMenu"
        >
          <span>$</span>
          Savings
        </RouterLink>

        <div class="mobile-divider"></div>

        <RouterLink
          to="/profile"
          class="mobile-nav-item"
          @click="closeMobileMenu"
        >
          <span>♙</span>
          Profile
        </RouterLink>

        <RouterLink
          to="/settings"
          class="mobile-nav-item"
          @click="closeMobileMenu"
        >
          <span>⚙</span>
          Settings
        </RouterLink>

      </div>
    </Transition>


    <!-- =========================
         MAIN CONTENT
    ========================== -->
    <main class="main-area">

      <!-- Desktop Top Bar -->
      <header class="desktop-topbar">

        <div></div>

        <div class="topbar-right">

          <!-- Notification -->
          <button class="notification-button">
            <svg viewBox="0 0 24 24">
              <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"></path>
              <path d="M10 21h4"></path>
            </svg>

            <span class="notification-dot"></span>
          </button>

          <!-- Profile -->
          <div class="desktop-user">

            <div class="avatar">
              JD
            </div>

            <div class="desktop-user-info">
              <strong>John Doe</strong>
              <span>Personal</span>
            </div>

            <span class="chevron">⌄</span>

          </div>

        </div>

      </header>


      <!-- Actual page -->
      <section class="page-content">
        <RouterView /> 
      </section>

    </main>

  </div>
</template>


<script setup>
import { ref } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

const mobileMenuOpen = ref(false)

const isActive = (path) => {
  if (path === '/') {
    return route.path === '/'
  }

  return route.path.startsWith(path)
}

const closeMobileMenu = () => {
  mobileMenuOpen.value = false
}
</script>


<style scoped>

/* ========================================
   GLOBAL LAYOUT
======================================== */

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


/* ========================================
   DESKTOP SIDEBAR
======================================== */

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
}


/* ========================================
   BRAND
======================================== */

.brand {
  height: 100px;

  display: flex;
  align-items: center;

  padding: 0 28px;

  gap: 12px;

  font-size: 21px;
  font-weight: 750;
  color: #172033;
}

.brand-icon {
  width: 34px;
  height: 34px;

  display: flex;
  align-items: center;
  justify-content: center;

  background: #6c63ff;
  color: white;

  border-radius: 10px;

  font-size: 18px;
  font-weight: 700;
}


/* ========================================
   SIDEBAR NAV
======================================== */

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
}

.personal-title {
  margin-top: 38px;
}


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

  transition: 0.2s ease;
}

.nav-item svg {
  width: 17px;
  height: 17px;

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


/* ========================================
   SIDEBAR BOTTOM
======================================== */

.sidebar-bottom {
  margin-top: auto;
  padding: 15px;
}


/* Help */

.help-card {
  min-height: 70px;

  display: flex;
  align-items: center;

  gap: 11px;

  padding: 12px;

  border-radius: 12px;

  background: #f5f3ff;
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


/* User */

.sidebar-user {
  display: flex;
  align-items: center;

  gap: 12px;

  margin-top: 14px;
  padding: 15px 7px 5px;

  border-top: 1px solid #eceef4;
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


/* ========================================
   DESKTOP TOP BAR
======================================== */

.main-area {
  min-height: 100vh;
  margin-left: 270px;
}

.desktop-topbar {
  height: 86px;

  display: flex;
  align-items: center;
  justify-content: space-between;

  padding: 0 30px;

  background: #ffffff;
  border-bottom: 1px solid #edf0f6;
}

.topbar-right {
  display: flex;
  align-items: center;

  gap: 22px;
}


/* Notification */

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


/* Desktop user */

.desktop-user {
  display: flex;
  align-items: center;

  gap: 11px;

  cursor: pointer;
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
}


/* ========================================
   PAGE
======================================== */

.page-content {
  padding: 32px;
}


/* ========================================
   MOBILE HEADER
======================================== */

.mobile-header {
  display: none;
}


/* ========================================
   MOBILE MENU
======================================== */

.mobile-menu {
  display: none;
}


/* ========================================
   TRANSITION
======================================== */

.mobile-menu-enter-active,
.mobile-menu-leave-active {
  transition:
    opacity 0.2s ease,
    transform 0.2s ease;
}

.mobile-menu-enter-from,
.mobile-menu-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}


/* ========================================
   TABLET
======================================== */

@media (max-width: 900px) {

  .desktop-sidebar {
    width: 220px;
  }

  .main-area {
    margin-left: 220px;
  }

  .brand {
    padding: 0 20px;
  }

  .page-content {
    padding: 25px;
  }

}


/* ========================================
   MOBILE
======================================== */

@media (max-width: 700px) {

  /* Hide desktop navigation */
  .desktop-sidebar {
    display: none;
  }

  .desktop-topbar {
    display: none;
  }

  /* Main goes full width */
  .main-area {
    margin-left: 0;
    padding-top: 64px;
  }

  /* Mobile header */
  .mobile-header {
    position: fixed;

    top: 0;
    left: 0;
    right: 0;

    height: 64px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    background: white;

    border-bottom: 1px solid #edf0f6;

    z-index: 1000;
  }

  /*
    IMPORTANT:
    Logo touches the left edge.
  */
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

  /* Profile button */

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


  /* Profile menu */

  .mobile-menu {
    position: fixed;

    top: 72px;
    right: 10px;

    width: min(300px, calc(100vw - 20px));

    display: block;

    padding: 15px;

    background: white;

    border: 1px solid #edf0f6;
    border-radius: 15px;

    box-shadow:
      0 15px 45px rgba(25, 32, 52, 0.14);

    z-index: 999;
  }

  .mobile-user-header {
    display: flex;
    align-items: center;

    gap: 12px;

    padding: 7px;
  }

  .mobile-user-header .large {
    width: 42px;
    height: 42px;

    font-size: 12px;
  }

  .mobile-user-header div:last-child {
    display: flex;
    flex-direction: column;

    gap: 4px;
  }

  .mobile-user-header strong {
    font-size: 13px;
    color: #20283a;
  }

  .mobile-user-header span {
    font-size: 10px;
    color: #999fad;
  }

  .mobile-divider {
    height: 1px;
    background: #edf0f5;

    margin: 10px 0;
  }

  .mobile-nav-item {
    min-height: 45px;

    display: flex;
    align-items: center;

    gap: 13px;

    padding: 0 10px;

    border-radius: 9px;

    color: #5f687b;

    text-decoration: none;

    font-size: 13px;
    font-weight: 550;
  }

  .mobile-nav-item span {
    width: 20px;

    text-align: center;

    color: #7067f7;

    font-size: 16px;
  }

  .mobile-nav-item:hover {
    background: #f4f2ff;
    color: #6259f5;
  }

  .page-content {
    padding: 20px 15px 30px;
  }

}


/* ========================================
   SMALL PHONE
======================================== */

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