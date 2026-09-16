<template>
  <div class="profile-page">

    <!-- HEADER -->
    <div class="page-header">
      <div>
        <span class="eyebrow">PERSONAL ACCOUNT</span>

        <h1>
          Hi, {{ firstName || 'there' }} 👋
        </h1>

        <p>
          Your account, your money, your way.
        </p>
      </div>
    </div>

    <!-- LOADING -->
    <div
      v-if="loading"
      class="loading-card"
    >
      <div class="loading-spinner"></div>

      <span>
        Loading your profile...
      </span>
    </div>

    <!-- ERROR -->
    <div
      v-else-if="errorMessage"
      class="error-card"
    >
      <div class="error-icon">
        !
      </div>

      <div>
        <strong>
          Couldn't load your profile
        </strong>

        <p>
          {{ errorMessage }}
        </p>

        <button
          class="retry-button"
          type="button"
          @click="loadProfile"
        >
          Try again
        </button>
      </div>
    </div>

    <!-- PROFILE -->
    <div
      v-else
      class="profile-content"
    >

      <!-- PERSONAL HERO -->
      <div class="profile-card">
        <div class="profile-avatar">
          {{ userInitials }}
        </div>

        <div class="profile-main">
          <span class="profile-label">
            YOUR MONEYFLOW ACCOUNT
          </span>

          <h2>
            {{ user.name || 'User' }}
          </h2>

          <p>
            {{ user.email || 'No email available' }}
          </p>

          <div class="profile-badges">
            <span class="profile-badge">
              ✦ Personal Account
            </span>

            <span
              v-if="formattedCreatedAt !== '—'"
              class="profile-badge soft"
            >
              Member since {{ shortCreatedAt }}
            </span>
          </div>
        </div>
      </div>


      <!-- QUICK PERSONAL MESSAGE -->
      <div class="personal-note">
        <div class="note-icon">
          💜
        </div>

        <div>
          <strong>
            Keep your finances personal.
          </strong>

          <p>
            Your profile keeps your account information in one place,
            while your wallets, budgets, transactions, and savings
            stay connected to your account.
          </p>
        </div>
      </div>


      <!-- PERSONAL INFORMATION -->
      <section class="settings-section">

        <div class="section-header">
          <div>
            <h2>
              Personal Information
            </h2>

            <p>
              The information connected to your account.
            </p>
          </div>
        </div>

        <div class="information-card">

          <div class="information-row">
            <div class="information-icon">
              👤
            </div>

            <div class="information-content">
              <span class="information-label">
                Full name
              </span>

              <strong>
                {{ user.name || 'Not provided' }}
              </strong>
            </div>
          </div>

          <div class="row-divider"></div>

          <div class="information-row">
            <div class="information-icon">
              @
            </div>

            <div class="information-content">
              <span class="information-label">
                Email address
              </span>

              <strong>
                {{ user.email || 'Not provided' }}
              </strong>
            </div>
          </div>

          <div class="row-divider"></div>

          <div class="information-row">
            <div class="information-icon">
              #
            </div>

            <div class="information-content">
              <span class="information-label">
                Account ID
              </span>

              <strong>
                {{ user.id || '—' }}
              </strong>
            </div>
          </div>

          <div class="row-divider"></div>

          <div class="information-row">
            <div class="information-icon">
              ◷
            </div>

            <div class="information-content">
              <span class="information-label">
                Member since
              </span>

              <strong>
                {{ formattedCreatedAt }}
              </strong>
            </div>
          </div>

        </div>
      </section>


      <!-- ACCOUNT PREFERENCES -->
      <section class="settings-section">

        <div class="section-header">
          <div>
            <h2>
              Account Preferences
            </h2>

            <p>
              Simple settings that make MoneyFlow feel like yours.
            </p>
          </div>
        </div>

        <div class="settings-card">

          <div class="setting-row">
            <div class="setting-icon">
              🔔
            </div>

            <div class="setting-content">
              <strong>
                Notifications
              </strong>

              <span>
                Keep reminders and important account updates enabled.
              </span>
            </div>

            <button
              type="button"
              class="toggle"
              :class="{ active: notifications }"
              @click="toggleNotifications"
              :aria-pressed="notifications"
            >
              <span></span>
            </button>
          </div>

          <div class="row-divider"></div>

          <button
            type="button"
            class="setting-row action-row"
            @click="changePassword"
          >
            <div class="setting-icon">
              🔒
            </div>

            <div class="setting-content">
              <strong>
                Change password
              </strong>

              <span>
                Update your account password.
              </span>
            </div>

            <span class="setting-arrow">
              →
            </span>
          </button>

        </div>
      </section>


      <!-- ACCOUNT ACTIONS -->
      <section class="logout-section">

        <div class="logout-card">

          <div class="logout-icon">
            ↪
          </div>

          <div class="logout-content">
            <strong>
              Sign out
            </strong>

            <span>
              Sign out of your MoneyFlow account on this device.
            </span>
          </div>

          <button
            type="button"
            class="logout-button"
            @click="logout"
          >
            Log out
          </button>

        </div>
      </section>

    </div>
  </div>
</template>


<script setup>
import {
  computed,
  onMounted,
  ref
} from 'vue'

import { useRouter } from 'vue-router'

import api from '../utils/api.js'


const router = useRouter()


const user = ref({})

const loading = ref(true)

const errorMessage = ref('')

const notifications = ref(
  localStorage.getItem('moneyflow_notifications') !== 'false'
)


const loadProfile = async () => {
  loading.value = true
  errorMessage.value = ''

  try {
    const response = await api.get('/profile')

    user.value =
      response.data?.data || {}

    localStorage.setItem(
      'user',
      JSON.stringify(user.value)
    )

  } catch (error) {
    console.error(
      'Profile error:',
      error
    )

    if (error.response?.status === 401) {
      clearAuth()
      router.push('/login')
      return
    }

    if (error.response) {
      errorMessage.value =
        error.response.data?.message ||
        'Unable to load your profile.'
    } else if (error.request) {
      errorMessage.value =
        'Cannot connect to the Laravel server. Make sure your backend is running.'
    } else {
      errorMessage.value =
        error.message ||
        'Something went wrong while loading your profile.'
    }

  } finally {
    loading.value = false
  }
}


const firstName = computed(() => {
  const name =
    String(user.value?.name || '')
      .trim()

  return name
    ? name.split(/\s+/)[0]
    : ''
})


const userInitials = computed(() => {
  const name =
    String(user.value?.name || 'User')
      .trim()

  if (!name) {
    return 'U'
  }

  const parts =
    name
      .split(/\s+/)
      .filter(Boolean)

  if (parts.length === 1) {
    return parts[0]
      .substring(0, 2)
      .toUpperCase()
  }

  return (
    parts[0][0] +
    parts[parts.length - 1][0]
  ).toUpperCase()
})


const formattedCreatedAt = computed(() => {
  if (!user.value?.created_at) {
    return '—'
  }

  const date =
    new Date(user.value.created_at)

  if (Number.isNaN(date.getTime())) {
    return '—'
  }

  return date.toLocaleDateString(
    'en-US',
    {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    }
  )
})


const shortCreatedAt = computed(() => {
  if (!user.value?.created_at) {
    return ''
  }

  const date =
    new Date(user.value.created_at)

  if (Number.isNaN(date.getTime())) {
    return ''
  }

  return date.toLocaleDateString(
    'en-US',
    {
      year: 'numeric',
      month: 'short'
    }
  )
})


const toggleNotifications = () => {
  notifications.value =
    !notifications.value

  localStorage.setItem(
    'moneyflow_notifications',
    String(notifications.value)
  )
}


const changePassword = () => {
  window.alert(
    'Password change is not connected yet.'
  )
}


const clearAuth = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('auth_token')
  localStorage.removeItem('access_token')
  localStorage.removeItem('is_logged_in')
  localStorage.removeItem('user')
  localStorage.removeItem('remember')
}


const logout = async () => {
  const confirmed =
    window.confirm(
      'Are you sure you want to log out?'
    )

  if (!confirmed) {
    return
  }

  try {
    await api.post('/logout')
  } catch (error) {
    console.error(
      'Logout error:',
      error
    )
  } finally {
    clearAuth()
    router.push('/login')
  }
}


onMounted(() => {
  loadProfile()
})
</script>


<style scoped>
* {
  box-sizing: border-box;
}

.profile-page {
  min-height: 100%;
  padding: 32px 42px 50px;
  color: #172033;
  font-family:
    Inter,
    -apple-system,
    BlinkMacSystemFont,
    "Segoe UI",
    sans-serif;
}

.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 28px;
}

.eyebrow {
  display: block;
  margin-bottom: 5px;
  color: #969caf;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 1.3px;
}

.page-header h1 {
  margin: 0 0 6px;
  color: #172033;
  font-size: 28px;
  font-weight: 700;
  letter-spacing: -.7px;
}

.page-header p {
  margin: 0;
  color: #9299aa;
  font-size: 13px;
}

.loading-card {
  min-height: 220px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 12px;
  border: 1px solid #e8eaf0;
  border-radius: 14px;
  background: white;
  color: #9299aa;
  font-size: 11px;
}

.loading-spinner {
  width: 25px;
  height: 25px;
  border: 3px solid #eceafc;
  border-top-color: #6655e9;
  border-radius: 50%;
  animation: spin .7s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.error-card {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  padding: 20px;
  border: 1px solid #f1d7d7;
  border-radius: 14px;
  background: #fff8f8;
}

.error-icon {
  width: 36px;
  height: 36px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
  background: #ffe7e7;
  color: #d54b4b;
  font-size: 15px;
  font-weight: 800;
}

.error-card strong {
  display: block;
  margin-bottom: 5px;
  color: #4b2630;
  font-size: 13px;
}

.error-card p {
  margin: 0 0 10px;
  color: #9a6870;
  font-size: 11px;
  line-height: 1.5;
}

.retry-button {
  padding: 7px 12px;
  border: 0;
  border-radius: 7px;
  background: #6655e9;
  color: white;
  font-size: 10px;
  font-weight: 700;
  cursor: pointer;
}

.profile-content {
  width: 100%;
  max-width: 1050px;
}

.profile-card {
  display: flex;
  align-items: center;
  gap: 20px;
  padding: 26px 28px;
  margin-bottom: 18px;
  border: 1px solid #e8eaf0;
  border-radius: 14px;
  background: white;
  box-shadow: 0 4px 15px rgba(30, 25, 80, .025);
}

.profile-avatar {
  width: 76px;
  height: 76px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 20px;
  background: linear-gradient(145deg, #6655e9, #8068ef);
  color: white;
  font-size: 23px;
  font-weight: 700;
  box-shadow: 0 8px 20px rgba(102, 85, 233, .20);
}

.profile-main {
  min-width: 0;
}

.profile-label {
  display: block;
  margin-bottom: 5px;
  color: #a0a6b5;
  font-size: 9px;
  font-weight: 700;
  letter-spacing: 1.1px;
}

.profile-main h2 {
  margin: 0 0 4px;
  color: #172033;
  font-size: 22px;
  letter-spacing: -.3px;
}

.profile-main p {
  margin: 0 0 11px;
  color: #9299aa;
  font-size: 12px;
  overflow-wrap: anywhere;
}

.profile-badges {
  display: flex;
  flex-wrap: wrap;
  gap: 7px;
}

.profile-badge {
  display: inline-flex;
  align-items: center;
  min-height: 25px;
  padding: 0 9px;
  border-radius: 20px;
  background: #f0edff;
  color: #6655e9;
  font-size: 9px;
  font-weight: 700;
}

.profile-badge.soft {
  background: #f6f7f9;
  color: #7b8393;
}

.personal-note {
  display: flex;
  align-items: flex-start;
  gap: 13px;
  padding: 17px 19px;
  margin-bottom: 32px;
  border: 1px solid #e7e2ff;
  border-radius: 14px;
  background: #fbfaff;
}

.note-icon {
  width: 38px;
  height: 38px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
  background: #f0edff;
  font-size: 15px;
}

.personal-note strong {
  display: block;
  margin-bottom: 4px;
  color: #302b54;
  font-size: 12px;
}

.personal-note p {
  margin: 0;
  color: #77728c;
  font-size: 10px;
  line-height: 1.6;
}

.settings-section {
  margin-bottom: 32px;
}

.section-header {
  margin-bottom: 15px;
}

.section-header h2 {
  margin: 0 0 5px;
  color: #172033;
  font-size: 17px;
  letter-spacing: -.2px;
}

.section-header p {
  margin: 0;
  color: #969cab;
  font-size: 11px;
}

.information-card,
.settings-card {
  overflow: hidden;
  border: 1px solid #e8eaf0;
  border-radius: 14px;
  background: white;
}

.information-row {
  min-height: 72px;
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 14px 20px;
}

.information-icon,
.setting-icon {
  width: 38px;
  height: 38px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
  background: #f0edff;
  color: #6655e9;
  font-size: 14px;
  font-weight: 700;
}

.information-content,
.setting-content {
  min-width: 0;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.information-label {
  color: #969cab;
  font-size: 10px;
}

.information-content strong {
  color: #263047;
  font-size: 12px;
  font-weight: 600;
  overflow-wrap: anywhere;
}

.row-divider {
  width: calc(100% - 40px);
  height: 1px;
  margin: 0 auto;
  background: #eef0f4;
}

.setting-row {
  width: 100%;
  min-height: 75px;
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 14px 20px;
  border: 0;
  background: white;
  text-align: left;
}

.action-row {
  cursor: pointer;
  transition: background .2s ease;
}

.action-row:hover {
  background: #fafaff;
}

.setting-content {
  flex: 1;
}

.setting-content strong {
  color: #263047;
  font-size: 12px;
  font-weight: 600;
}

.setting-content span {
  color: #969cab;
  font-size: 10px;
  line-height: 1.4;
}

.setting-arrow {
  color: #a3a9b8;
  font-size: 16px;
  transition: transform .2s ease;
}

.action-row:hover .setting-arrow {
  transform: translateX(3px);
  color: #6655e9;
}

.toggle {
  width: 42px;
  height: 23px;
  flex-shrink: 0;
  position: relative;
  padding: 0;
  border: 0;
  border-radius: 20px;
  background: #dfe2ea;
  cursor: pointer;
  transition: background .2s ease;
}

.toggle span {
  position: absolute;
  top: 3px;
  left: 3px;
  width: 17px;
  height: 17px;
  border-radius: 50%;
  background: white;
  box-shadow: 0 2px 5px rgba(0, 0, 0, .15);
  transition: transform .2s ease;
}

.toggle.active {
  background: #6655e9;
}

.toggle.active span {
  transform: translateX(19px);
}

.logout-section {
  margin-bottom: 20px;
}

.logout-card {
  min-height: 82px;
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 16px 20px;
  border: 1px solid #eee1e4;
  border-radius: 14px;
  background: #fffafb;
}

.logout-icon {
  width: 38px;
  height: 38px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
  background: #fff0f2;
  color: #d65a6b;
  font-size: 17px;
}

.logout-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.logout-content strong {
  color: #3b2630;
  font-size: 12px;
}

.logout-content span {
  color: #a17e86;
  font-size: 10px;
}

.logout-button {
  height: 36px;
  padding: 0 15px;
  border: 1px solid #e9cbd0;
  border-radius: 8px;
  background: white;
  color: #c64f60;
  font-size: 10px;
  font-weight: 700;
  cursor: pointer;
  transition: .2s ease;
}

.logout-button:hover {
  background: #fff0f2;
  border-color: #dfb3ba;
}

@media (max-width: 700px) {
  .profile-page {
    padding: 22px 16px 35px;
  }

  .page-header h1 {
    font-size: 25px;
  }

  .profile-card {
    align-items: flex-start;
    padding: 20px;
  }

  .profile-avatar {
    width: 58px;
    height: 58px;
    border-radius: 15px;
    font-size: 18px;
  }

  .profile-main h2 {
    font-size: 17px;
  }

  .information-row,
  .setting-row {
    padding: 13px 15px;
  }

  .row-divider {
    width: calc(100% - 30px);
  }

  .logout-card {
    align-items: flex-start;
    flex-wrap: wrap;
    padding: 17px 15px;
  }

  .logout-button {
    width: calc(100% - 53px);
    margin-left: 53px;
  }
}
</style>
