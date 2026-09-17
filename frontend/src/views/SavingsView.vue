<template>
  <div class="page">
    <!-- PAGE HEADER -->
    <div class="page-header">
      <div>
        <span class="eyebrow">PERSONAL ACCOUNT</span>
        <h1>Savings</h1>
        <p>Keep working toward your financial goals.</p>
      </div>

      <button
        class="primary-button"
        @click="addGoal"
        :disabled="saving"
      >
        <span>+</span>
        {{ saving ? 'Saving...' : 'Add Savings Goal' }}
      </button>
    </div>

    <!-- TOTAL SAVED -->
    <div class="summary-card">
      <div class="summary-icon">Rp</div>

      <div>
        <span>Total Saved</span>

        <strong>
          {{ goals.length ? formatRupiah(totalSaved) : '—' }}
        </strong>

        <p>
          {{
            goals.length
              ? 'Across all your savings goals'
              : 'No savings data yet'
          }}
        </p>
      </div>
    </div>

    <!-- SAVINGS GOALS HEADER -->
    <div class="section-header">
      <div>
        <h2>Savings Goals</h2>
        <p>Track your progress toward your goals.</p>
      </div>
    </div>

    <!-- LOADING SKELETON -->
    <div v-if="loading" class="skeleton-container">
      
      <!-- Summary Skeleton -->
      <div class="summary-card skeleton-summary">
        <div class="skeleton skeleton-summary-icon"></div>

        <div class="skeleton-summary-content">
          <div class="skeleton skeleton-label"></div>
          <div class="skeleton skeleton-total"></div>
          <div class="skeleton skeleton-description"></div>
        </div>
      </div>

      <!-- Section Header Skeleton -->
      <div class="skeleton-section-header">
        <div class="skeleton skeleton-title"></div>
        <div class="skeleton skeleton-subtitle"></div>
      </div>

      <!-- Goal Cards Skeleton -->
      <div class="goals-grid">
        <div
          v-for="n in 4"
          :key="n"
          class="goal-card skeleton-goal-card"
        >
          <div class="goal-header">
            <div class="skeleton skeleton-goal-icon"></div>
            <div class="skeleton skeleton-more"></div>
          </div>

          <div class="skeleton skeleton-goal-title"></div>

          <div class="goal-content">
            <!-- Donut Skeleton -->
            <div class="skeleton skeleton-donut"></div>

            <!-- Goal Info Skeleton -->
            <div class="skeleton-goal-info">
              <div class="skeleton skeleton-amount"></div>
              <div class="skeleton skeleton-target"></div>
              <div class="skeleton skeleton-date"></div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- LOAD ERROR -->
    <div
      v-else-if="error && !showGoalModal"
      class="empty-state"
    >
      <div class="empty-icon">!</div>

      <strong>Unable to load savings goals.</strong>

      <span>{{ error }}</span>

      <button
        class="primary-button retry-button"
        @click="fetchGoals"
      >
        Try Again
      </button>
    </div>

    <!-- NO GOALS -->
    <div
      v-else-if="goals.length === 0"
      class="empty-state"
    >
      <div class="empty-icon">🎯</div>

      <strong>No savings goals yet.</strong>

      <span>
        Create a savings goal to start tracking your progress.
      </span>
    </div>

    <!-- GOALS -->
    <div v-else class="goals-grid">
      <div
        v-for="goal in goals"
        :key="goal.id"
        class="goal-card"
      >
        <div class="goal-header">
          <div class="goal-icon">
            {{ goal.icon }}
          </div>

          <button
            class="more-button"
            type="button"
            title="Savings goal options"
          >
            ⋮
          </button>
        </div>

        <h3>{{ goal.name }}</h3>

        <div class="goal-content">
          <!-- PROGRESS DONUT -->
          <div
            class="donut"
            :style="{
              '--progress':
                Math.min(Number(goal.percent || 0), 100) + '%'
            }"
          >
            <div class="donut-inner">
              <strong>
                {{ Math.min(Number(goal.percent || 0), 100) }}%
              </strong>

              <span>Complete</span>
            </div>
          </div>

          <!-- GOAL INFO -->
          <div class="goal-info">
            <strong>
              {{ formatRupiah(goal.saved) }}
            </strong>

            <span>
              of {{ formatRupiah(goal.target) }}
            </span>

            <p>
              Target: {{ goal.date }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- ADD SAVINGS GOAL MODAL -->
    <div
      v-if="showGoalModal"
      class="modal-overlay"
      @click.self="closeGoalModal"
    >
      <div class="modal-card">

        <!-- MODAL HEADER -->
        <div class="modal-header">
          <div>
            <span class="eyebrow">SAVINGS</span>

            <h2>Add Savings Goal</h2>

            <p>
              Create a new goal and track your progress.
            </p>
          </div>

          <button
            type="button"
            class="close-button"
            @click="closeGoalModal"
            :disabled="saving"
          >
            ×
          </button>
        </div>

        <!-- FORM -->
        <form @submit.prevent="submitGoal">

          <!-- GOAL NAME -->
          <div class="form-group">
            <label for="goal-name">
              Goal Name
            </label>

            <input
              id="goal-name"
              v-model="goalForm.nama_tujuan"
              type="text"
              placeholder="e.g. New Laptop"
              maxlength="150"
              required
            />
          </div>

          <!-- AMOUNTS -->
          <div class="form-row">

            <!-- TARGET -->
            <div class="form-group">
              <label for="goal-target">
                Target Amount
              </label>

              <div class="amount-input">
                <span>Rp</span>

                <input
                  id="goal-target"
                  :value="goalForm.target_jumlah"
                  @input="
                    goalForm.target_jumlah =
                      formatRupiahInput($event.target.value)
                  "
                  type="text"
                  inputmode="numeric"
                  autocomplete="off"
                  placeholder="0"
                  required
                />
              </div>
            </div>

            <!-- CURRENT SAVED -->
            <div class="form-group">
              <label for="goal-saved">
                Current Saved
              </label>

              <div class="amount-input">
                <span>Rp</span>

                <input
                  id="goal-saved"
                  :value="goalForm.jumlah_terkumpul"
                  @input="
                    goalForm.jumlah_terkumpul =
                      formatRupiahInput($event.target.value)
                  "
                  type="text"
                  inputmode="numeric"
                  autocomplete="off"
                  placeholder="0"
                />
              </div>
            </div>

          </div>

          <!-- DEADLINE -->
          <div class="form-group">
            <label for="goal-deadline">
              Deadline
            </label>

            <input
              id="goal-deadline"
              v-model="goalForm.deadline"
              type="date"
            />

            <span class="input-help">
              Optional. Choose when you want to reach this goal.
            </span>
          </div>

          <!-- ERROR -->
          <div
            v-if="error"
            class="form-error"
          >
            {{ error }}
          </div>

          <!-- BUTTONS -->
          <div class="modal-actions">

            <button
              type="button"
              class="secondary-button"
              @click="closeGoalModal"
              :disabled="saving"
            >
              Cancel
            </button>

            <button
              type="submit"
              class="primary-button"
              :disabled="saving"
            >
              {{ saving ? 'Saving...' : 'Add Savings Goal' }}
            </button>

          </div>
        </form>
      </div>
    </div>
  </div>
</template>


<script setup>
import { computed, onMounted, ref } from 'vue'


/* =========================================================
   API
========================================================= */

const API_BASE =
  import.meta.env.VITE_API_BASE_URL ||
  'http://127.0.0.1:8000'

const API_URL = `${API_BASE}/api`


/* =========================================================
   STATE
========================================================= */

const goals = ref([])
const loading = ref(false)
const saving = ref(false)
const error = ref('')
const showGoalModal = ref(false)


/* =========================================================
   FORM
========================================================= */

const goalForm = ref({
  nama_tujuan: '',
  target_jumlah: '',
  jumlah_terkumpul: '0',
  deadline: ''
})


/* =========================================================
   AUTH
========================================================= */

const getToken = () => {
  return (
    localStorage.getItem('token') ||
    localStorage.getItem('auth_token') ||
    localStorage.getItem('access_token') ||
    localStorage.getItem('sanctum_token') ||
    ''
  )
}


const getHeaders = (includeJson = false) => {
  const token = getToken()

  return {
    Accept: 'application/json',

    ...(includeJson
      ? {
          'Content-Type': 'application/json'
        }
      : {}),

    ...(token
      ? {
          Authorization: `Bearer ${token}`
        }
      : {})
  }
}


/* =========================================================
   RUPIAH HELPERS
========================================================= */

/*
 * Formats a number for display.
 *
 * Example:
 * 1000       -> Rp1.000
 * 1000000    -> Rp1.000.000
 */
const formatRupiah = (value) => {
  const number = Number(value || 0)

  return `Rp${number.toLocaleString('id-ID')}`
}


/*
 * Formats the value while the user is typing.
 *
 * Example:
 * 1000       -> 1.000
 * 1000000    -> 1.000.000
 *
 * Non-numeric characters are removed.
 */
const formatRupiahInput = (value) => {
  const digits = String(value || '').replace(/\D/g, '')

  if (!digits) {
    return ''
  }

  return Number(digits).toLocaleString('id-ID')
}


/*
 * Converts the formatted input back into a normal number
 * before sending it to the backend.
 *
 * Example:
 * "1.000"       -> 1000
 * "1.000.000"   -> 1000000
 */
const parseRupiah = (value) => {
  const cleaned = String(value || '')
    .replace(/\./g, '')
    .replace(/,/g, '')
    .replace(/\D/g, '')

  if (!cleaned) {
    return 0
  }

  return Number(cleaned)
}


/* =========================================================
   DATE
========================================================= */

const formatDate = (date) => {
  if (!date) {
    return 'No deadline'
  }

  const parsed = new Date(date)

  if (Number.isNaN(parsed.getTime())) {
    return date
  }

  return parsed.toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}


/* =========================================================
   MAP BACKEND DATA
========================================================= */

const mapGoal = (goal) => {
  const saved = Number(goal.jumlah_terkumpul || 0)
  const target = Number(goal.target_jumlah || 0)

  const percent =
    target > 0
      ? Math.round((saved / target) * 100)
      : 0

  return {
    id: goal.id_tujuan,
    name: goal.nama_tujuan,
    saved,
    target,

    percent: Math.min(percent, 100),

    date: goal.deadline
      ? formatDate(goal.deadline)
      : 'No deadline',

    icon: '🎯',

    backendData: goal
  }
}


/* =========================================================
   FETCH SAVINGS GOALS
========================================================= */

const fetchGoals = async () => {
  loading.value = true
  error.value = ''

  try {
    const token = getToken()

    if (!token) {
      throw new Error(
        'Authentication token not found. Please log in again.'
      )
    }

    const response = await fetch(
      `${API_URL}/tujuan-tabungan`,
      {
        method: 'GET',
        headers: getHeaders(true)
      }
    )

    const result = await response.json()

    if (!response.ok) {
      if (response.status === 401) {
        throw new Error(
          'Your login session has expired. Please log in again.'
        )
      }

      throw new Error(
        result.message ||
        'Failed to load savings goals.'
      )
    }

    goals.value = Array.isArray(result.data)
      ? result.data.map(mapGoal)
      : []

    } catch (err) {
      console.error('Fetch savings goals error:', err)
      error.value = err.message || 'Failed to load savings goals.'
    } finally {
      loading.value = false
    }
}


/* =========================================================
   TOTAL SAVED
========================================================= */

const totalSaved = computed(() => {
  return goals.value.reduce(
    (total, goal) =>
      total + Number(goal.saved || 0),
    0
  )
})


/* =========================================================
   FORM RESET
========================================================= */

const resetGoalForm = () => {
  goalForm.value = {
    nama_tujuan: '',
    target_jumlah: '',
    jumlah_terkumpul: '0',
    deadline: ''
  }
}


/* =========================================================
   OPEN MODAL
========================================================= */

const addGoal = () => {
  resetGoalForm()

  error.value = ''

  showGoalModal.value = true
}


/* =========================================================
   CLOSE MODAL
========================================================= */

const closeGoalModal = () => {
  if (saving.value) {
    return
  }

  showGoalModal.value = false
  error.value = ''
}


/* =========================================================
   SUBMIT GOAL
========================================================= */

const submitGoal = async () => {

  /* ---------------------------------------------
     Goal name
  --------------------------------------------- */

  const name =
    goalForm.value.nama_tujuan.trim()


  /* ---------------------------------------------
     Convert formatted Rupiah to numbers
  --------------------------------------------- */

  const target =
    parseRupiah(
      goalForm.value.target_jumlah
    )

  const saved =
    parseRupiah(
      goalForm.value.jumlah_terkumpul
    )


  /* ---------------------------------------------
     Validation
  --------------------------------------------- */

  if (!name) {
    error.value =
      'Please enter a savings goal name.'

    return
  }


  if (
    !Number.isFinite(target) ||
    target <= 0
  ) {
    error.value =
      'Please enter a valid target amount.'

    return
  }


  if (
    !Number.isFinite(saved) ||
    saved < 0
  ) {
    error.value =
      'Please enter a valid saved amount.'

    return
  }


  /*
   * The amount already saved cannot be greater
   * than the target.
   */
  if (saved > target) {
    error.value =
      'Current saved amount cannot exceed the target.'

    return
  }


  /* ---------------------------------------------
     Start saving
  --------------------------------------------- */

  saving.value = true
  error.value = ''


  try {
    const token = getToken()

    if (!token) {
      throw new Error(
        'Authentication token not found. Please log in again.'
      )
    }


    /* -------------------------------------------
       Send data to backend
    ------------------------------------------- */

    const response = await fetch(
      `${API_URL}/tujuan-tabungan`,
      {
        method: 'POST',

        headers: getHeaders(true),

        body: JSON.stringify({
          nama_tujuan: name,
          target_jumlah: target,
          jumlah_terkumpul: saved,
          deadline:
            goalForm.value.deadline || null
        })
      }
    )


    const result =
      await response.json()


    /* -------------------------------------------
       Handle errors
    ------------------------------------------- */

    if (!response.ok) {

      if (response.status === 401) {
        throw new Error(
          'Your login session has expired. Please log in again.'
        )
      }


      if (response.status === 422) {
        throw new Error(
          result.message ||
          'The savings goal data is invalid.'
        )
      }


      throw new Error(
        result.message ||
        'Failed to create savings goal.'
      )
    }


    /* -------------------------------------------
       Add new goal to the page
    ------------------------------------------- */

    if (result.data) {
      goals.value.unshift(
        mapGoal(result.data)
      )
    } else {
      await fetchGoals()
    }


    /* -------------------------------------------
       Close and reset
    ------------------------------------------- */

    showGoalModal.value = false

    resetGoalForm()

  } catch (err) {
    console.error('Create savings goal error:', err)
    error.value = err.message || 'Failed to create savings goal.'
  } finally {
    saving.value = false
  }
}


/* =========================================================
   INITIAL LOAD
========================================================= */

onMounted(() => {
  fetchGoals()
})
</script>


<style scoped>

* {
  box-sizing: border-box;
}


/* =========================================================
   PAGE
========================================================= */

.page {
  padding: 32px 42px 50px;
  color: #172033;
}


/* =========================================================
   PAGE HEADER
========================================================= */

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
  font-size: 28px;
  letter-spacing: -0.7px;
}


.page-header p {
  margin: 0;
  color: #9299aa;
  font-size: 13px;
}


/* =========================================================
   PRIMARY BUTTON
========================================================= */

.primary-button {
  height: 44px;

  display: flex;
  align-items: center;
  justify-content: center;

  gap: 8px;

  padding: 0 20px;

  border: 0;
  border-radius: 9px;

  background: #6655e9;
  color: white;

  font-size: 12px;
  font-weight: 700;

  cursor: pointer;

  box-shadow:
    0 8px 20px
    rgba(102, 85, 233, 0.2);

  transition: 0.2s ease;
}


.primary-button:hover:not(:disabled) {
  background: #5746dc;
  transform: translateY(-1px);
}


.primary-button:disabled {
  opacity: 0.65;
  cursor: not-allowed;
  transform: none;
}


.retry-button {
  margin-top: 15px;
}


/* =========================================================
   SUMMARY CARD
========================================================= */

.summary-card {
  display: flex;
  align-items: center;

  gap: 16px;

  padding: 24px;

  margin-bottom: 32px;

  border: 1px solid #e8eaf0;
  border-radius: 14px;

  background: white;
}


.summary-icon {
  width: 48px;
  height: 48px;

  display: flex;
  align-items: center;
  justify-content: center;

  flex-shrink: 0;

  border-radius: 12px;

  background: #eeebff;
  color: #6655e9;

  font-size: 12px;
  font-weight: 700;
}


.summary-card span {
  color: #969cab;
  font-size: 11px;
}


.summary-card strong {
  display: block;

  margin: 5px 0;

  font-size: 24px;
}


.summary-card p {
  margin: 0;

  color: #a0a6b5;
  font-size: 10px;
}


/* =========================================================
   SECTION HEADER
========================================================= */

.section-header {
  margin-bottom: 18px;
}


.section-header h2 {
  margin: 0 0 5px;

  font-size: 17px;
}


.section-header p {
  margin: 0;

  color: #969cab;
  font-size: 11px;
}

/* =========================================================
   LOADING SKELETON
========================================================= */

.skeleton-container {
  width: 100%;
}


/* Base skeleton */

.skeleton {
  position: relative;
  overflow: hidden;

  background: #eef0f4;
  border-radius: 8px;
}

.skeleton::after {
  content: '';

  position: absolute;
  inset: 0;

  transform: translateX(-100%);

  background: linear-gradient(
    90deg,
    transparent,
    rgba(255, 255, 255, 0.7),
    transparent
  );

  animation: skeleton-loading 1.4s infinite;
}

@keyframes skeleton-loading {
  100% {
    transform: translateX(100%);
  }
}


/* =========================================================
   SUMMARY SKELETON
========================================================= */

.skeleton-summary {
  display: flex;
  align-items: center;
  gap: 16px;
}

.skeleton-summary-icon {
  width: 48px;
  height: 48px;

  flex-shrink: 0;

  border-radius: 12px;
}

.skeleton-summary-content {
  flex: 1;
}

.skeleton-label {
  width: 70px;
  height: 10px;

  margin-bottom: 8px;
}

.skeleton-total {
  width: 150px;
  height: 24px;

  margin-bottom: 8px;
}

.skeleton-description {
  width: 190px;
  height: 9px;
}


/* =========================================================
   SECTION HEADER SKELETON
========================================================= */

.skeleton-section-header {
  margin-bottom: 18px;
}

.skeleton-title {
  width: 130px;
  height: 17px;

  margin-bottom: 7px;
}

.skeleton-subtitle {
  width: 220px;
  height: 10px;
}


/* =========================================================
   GOAL CARD SKELETON
========================================================= */

.skeleton-goal-card {
  min-height: 230px;
}

.skeleton-goal-icon {
  width: 42px;
  height: 42px;

  border-radius: 10px;
}

.skeleton-more {
  width: 20px;
  height: 20px;

  border-radius: 5px;
}

.skeleton-goal-title {
  width: 150px;
  height: 14px;

  margin-bottom: 22px;
}

.skeleton-donut {
  width: 115px;
  height: 115px;

  flex-shrink: 0;

  border-radius: 50%;
}

.skeleton-goal-info {
  flex: 1;
}

.skeleton-amount {
  width: 120px;
  height: 19px;

  margin-bottom: 8px;
}

.skeleton-target {
  width: 100px;
  height: 11px;

  margin-bottom: 14px;
}

.skeleton-date {
  width: 90px;
  height: 10px;
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 700px) {

  .skeleton-goal-card {
    min-height: 220px;
  }

}


/* =========================================================
   EMPTY STATE
========================================================= */

.empty-state {
  min-height: 280px;

  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;

  padding: 40px 20px;

  border: 1px solid #e8eaf0;
  border-radius: 14px;

  background: white;

  text-align: center;

  color: #9aa1b2;
}


.empty-icon {
  width: 48px;
  height: 48px;

  display: flex;
  align-items: center;
  justify-content: center;

  margin-bottom: 12px;

  border-radius: 12px;

  background: #f0edff;

  font-size: 20px;
}


.empty-state strong {
  margin-bottom: 5px;

  color: #667085;
  font-size: 13px;
}


.empty-state span {
  max-width: 420px;

  color: #9aa1b2;

  font-size: 10px;
  line-height: 1.5;
}


/* =========================================================
   GOALS GRID
========================================================= */

.goals-grid {
  display: grid;

  grid-template-columns:
    repeat(2, 1fr);

  gap: 18px;
}


.goal-card {
  padding: 24px;

  border: 1px solid #e8eaf0;
  border-radius: 14px;

  background: white;
}


.goal-header {
  display: flex;
  justify-content: space-between;

  margin-bottom: 16px;
}


.goal-icon {
  width: 42px;
  height: 42px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 10px;

  background: #f0edff;

  font-size: 18px;
}


.more-button {
  border: 0;
  background: transparent;

  color: #9ca3b3;

  font-size: 18px;

  cursor: pointer;
}


.goal-card h3 {
  margin: 0 0 22px;

  font-size: 14px;
}


.goal-content {
  display: flex;
  align-items: center;

  gap: 24px;
}


/* =========================================================
   DONUT
========================================================= */

.donut {
  width: 115px;
  height: 115px;

  flex-shrink: 0;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 50%;

  background:
    conic-gradient(
      #6655e9 var(--progress),
      #e9eaf0 var(--progress)
    );
}


.donut-inner {
  width: 87px;
  height: 87px;

  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;

  border-radius: 50%;

  background: white;
}


.donut-inner strong {
  font-size: 20px;
}


.donut-inner span {
  margin-top: 2px;

  color: #9ca2b2;

  font-size: 9px;
}


/* =========================================================
   GOAL INFO
========================================================= */

.goal-info strong {
  display: inline;

  font-size: 19px;
}


.goal-info > span {
  color: #9ca2b2;

  font-size: 11px;
}


.goal-info p {
  margin-top: 10px;

  color: #6655e9;

  font-size: 10px;
  font-weight: 600;
}


/* =========================================================
   MODAL
========================================================= */

.modal-overlay {
  position: fixed;

  inset: 0;

  z-index: 1000;

  display: flex;
  align-items: center;
  justify-content: center;

  padding: 20px;

  background:
    rgba(23, 32, 51, 0.35);

  backdrop-filter: blur(3px);
}


.modal-card {
  width: 100%;
  max-width: 500px;

  padding: 26px;

  border: 1px solid #e8eaf0;
  border-radius: 16px;

  background: white;

  box-shadow:
    0 20px 50px
    rgba(23, 32, 51, 0.15);
}


/* =========================================================
   MODAL HEADER
========================================================= */

.modal-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;

  margin-bottom: 24px;
}


.modal-header h2 {
  margin: 0 0 5px;

  color: #172033;

  font-size: 20px;
  letter-spacing: -0.4px;
}


.modal-header p {
  margin: 0;

  color: #969cab;

  font-size: 11px;
}


/* =========================================================
   CLOSE BUTTON
========================================================= */

.close-button {
  width: 32px;
  height: 32px;

  display: flex;
  align-items: center;
  justify-content: center;

  flex-shrink: 0;

  border: 0;
  border-radius: 8px;

  background: #f4f3f8;
  color: #7d8393;

  font-size: 21px;
  line-height: 1;

  cursor: pointer;

  transition: 0.2s ease;
}


.close-button:hover:not(:disabled) {
  background: #eeebff;
  color: #6655e9;
}


.close-button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}


/* =========================================================
   FORM
========================================================= */

.form-group {
  display: flex;
  flex-direction: column;

  margin-bottom: 18px;
}


.form-group label {
  margin-bottom: 7px;

  color: #4d5568;

  font-size: 11px;
  font-weight: 700;
}


.form-group input {
  width: 100%;
  height: 42px;

  padding: 0 13px;

  border: 1px solid #dfe2e9;
  border-radius: 9px;

  outline: none;

  background: #fff;
  color: #172033;

  font-family: inherit;
  font-size: 12px;

  transition: 0.2s ease;
}


.form-group input::placeholder {
  color: #b0b5c1;
}


.form-group input:focus {
  border-color: #6655e9;

  box-shadow:
    0 0 0 3px
    rgba(102, 85, 233, 0.08);
}


/* =========================================================
   RUPIAH INPUT
========================================================= */

.amount-input {
  position: relative;

  display: flex;
  align-items: center;
}


.amount-input > span {
  position: absolute;

  left: 13px;

  color: #667085;

  font-size: 12px;
  font-weight: 600;

  z-index: 1;

  pointer-events: none;
}


.amount-input input {
  padding-left: 38px !important;
}


/* =========================================================
   FORM ROW
========================================================= */

.form-row {
  display: grid;

  grid-template-columns:
    1fr 1fr;

  gap: 14px;
}


.input-help {
  margin-top: 6px;

  color: #9aa1b2;

  font-size: 9px;
  line-height: 1.4;
}


/* =========================================================
   FORM ERROR
========================================================= */

.form-error {
  margin: -4px 0 16px;

  padding: 10px 12px;

  border: 1px solid #f0d5d5;
  border-radius: 8px;

  background: #fff6f6;
  color: #c44f4f;

  font-size: 10px;
  line-height: 1.4;
}


/* =========================================================
   MODAL ACTIONS
========================================================= */

.modal-actions {
  display: flex;

  justify-content: flex-end;

  gap: 10px;

  margin-top: 24px;

  padding-top: 20px;

  border-top: 1px solid #eef0f4;
}


.modal-actions
.primary-button,
.modal-actions
.secondary-button {
  height: 42px;

  min-width: 130px;

  display: flex;
  align-items: center;
  justify-content: center;

  padding: 0 18px;

  border-radius: 9px;

  font-family: inherit;

  font-size: 11px;
  font-weight: 700;

  cursor: pointer;

  transition: 0.2s ease;
}


/* =========================================================
   SECONDARY BUTTON
========================================================= */

.secondary-button {
  border: 1px solid #dfe2e9;

  background: white;
  color: #667085;
}


.secondary-button:hover:not(:disabled) {
  background: #f7f7fa;

  border-color: #d4d6df;
}


.secondary-button:disabled {
  opacity: 0.6;

  cursor: not-allowed;
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 700px) {

  .page {
    padding: 22px 16px 35px;
  }


  .page-header {
    align-items: flex-start;

    flex-direction: column;

    gap: 18px;
  }


  .primary-button {
    width: 100%;

    justify-content: center;
  }


  .goals-grid {
    grid-template-columns: 1fr;
  }


  .goal-content {
    gap: 18px;
  }


  .empty-state {
    min-height: 240px;
  }


  .modal-overlay {
    align-items: flex-end;

    padding: 0;
  }


  .modal-card {
    max-width: none;

    padding: 22px;

    border-radius:
      16px 16px 0 0;
  }


  .form-row {
    grid-template-columns: 1fr;

    gap: 0;
  }


  .modal-actions {
    flex-direction: column-reverse;
  }


  .modal-actions
  .primary-button,
  .modal-actions
  .secondary-button {
    width: 100%;
  }
}

</style>