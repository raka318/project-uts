<template>
  <section class="page">
    <div class="page-header">
      <div>
        <span class="eyebrow">PERSONAL ACCOUNT</span>
        <h1>Wallets</h1>
        <p>Manage the accounts and wallets you use for your money.</p>
      </div>

      <button class="primary-button" @click="openCreate">
        <span>+</span>
        Add Wallet
      </button>
    </div>

    <div v-if="error" class="alert">{{ error }}</div>

    <div v-if="loading" class="state-card">
      <div class="state-icon">↻</div>
      <strong>Loading wallets...</strong>
      <span>Please wait while your wallets are loaded.</span>
    </div>

    <div v-else-if="wallets.length" class="wallet-grid">
      <article v-for="wallet in wallets" :key="wallet.id_dompet" class="wallet-card">
        <div class="wallet-top">
          <div class="wallet-icon">{{ walletIcon(wallet.jenis) }}</div>
          <div class="wallet-actions">
            <button class="icon-button" title="Edit" @click="openEdit(wallet)">✎</button>
            <button class="icon-button danger" title="Delete" @click="deleteWallet(wallet)">×</button>
          </div>
        </div>

        <div class="wallet-name">{{ wallet.nama_dompet }}</div>
        <div class="wallet-type">{{ formatType(wallet.jenis) }}</div>

        <div class="wallet-balance">
          <span>Balance</span>
          <strong>{{ formatMoney(wallet.saldo) }}</strong>
        </div>

        <div class="currency">{{ wallet.mata_uang || 'IDR' }}</div>
      </article>
    </div>

    <div v-else class="state-card">
      <div class="state-icon">▣</div>
      <strong>No wallets yet</strong>
      <span>Create your first wallet so you can select it when adding transactions.</span>
      <button class="secondary-button" @click="openCreate">Create a wallet</button>
    </div>

    <div v-if="showModal" class="modal-backdrop" @click.self="closeModal">
      <div class="modal">
        <button class="close-button" @click="closeModal">×</button>

        <div class="modal-header">
          <span class="eyebrow">WALLET</span>
          <h2>{{ editingId ? 'Edit Wallet' : 'Add Wallet' }}</h2>
          <p>{{ editingId ? 'Update your wallet details.' : 'Add an account where you keep your money.' }}</p>
        </div>

        <form @submit.prevent="saveWallet">
          <label>
            Wallet name
            <input v-model="form.nama_dompet" type="text" placeholder="Example: BCA" maxlength="100" required />
          </label>

          <label>
            Type
            <select v-model="form.jenis" required>
              <option value="cash">Cash</option>
              <option value="bank">Bank</option>
              <option value="ewallet">E-Wallet</option>
              <option value="tabungan">Savings</option>
            </select>
          </label>

          <label>
            Balance
            <input v-model="form.saldo" type="number" min="0" step="0.01" placeholder="0" required />
          </label>

          <label>
            Currency
            <input v-model="form.mata_uang" type="text" maxlength="10" placeholder="IDR" required />
          </label>

          <p v-if="formError" class="form-error">{{ formError }}</p>

          <div class="modal-actions">
            <button type="button" class="cancel-button" @click="closeModal">Cancel</button>
            <button type="submit" class="primary-button" :disabled="saving">
              {{ saving ? 'Saving...' : editingId ? 'Save Changes' : 'Add Wallet' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import api from '../utils/api.js'

const wallets = ref([])
const loading = ref(true)
const saving = ref(false)
const error = ref('')
const formError = ref('')
const showModal = ref(false)
const editingId = ref(null)

const emptyForm = () => ({
  nama_dompet: '',
  jenis: 'cash',
  saldo: 0,
  mata_uang: 'IDR',
})

const form = reactive(emptyForm())

function resetForm() {
  Object.assign(form, emptyForm())
}

function getErrorMessage(err, fallback) {
  return err?.response?.data?.message || err?.response?.data?.errors
    ? JSON.stringify(err.response.data.errors)
    : err?.message || fallback
}

async function loadWallets() {
  loading.value = true
  error.value = ''

  try {
    const response = await api.get('/dompet')
    wallets.value = Array.isArray(response.data?.data)
      ? response.data.data
      : []
  } catch (err) {
    if (err?.response?.status === 401) {
      window.location.href = '/login'
      return
    }

    wallets.value = []
    error.value = getErrorMessage(err, 'Could not load wallets.')
  } finally {
    loading.value = false
  }
}

function openCreate() {
  editingId.value = null
  resetForm()
  formError.value = ''
  showModal.value = true
}

function openEdit(wallet) {
  editingId.value = wallet.id_dompet
  Object.assign(form, {
    nama_dompet: wallet.nama_dompet || '',
    jenis: wallet.jenis || 'cash',
    saldo: Number(wallet.saldo || 0),
    mata_uang: wallet.mata_uang || 'IDR',
  })
  formError.value = ''
  showModal.value = true
}

function closeModal() {
  if (saving.value) return
  showModal.value = false
}

async function saveWallet() {
  saving.value = true
  formError.value = ''
  error.value = ''

  const payload = {
    nama_dompet: form.nama_dompet.trim(),
    jenis: form.jenis,
    saldo: Number(form.saldo),
    mata_uang: form.mata_uang.trim() || 'IDR',
  }

  try {
    if (editingId.value) {
      const response = await api.put(`/dompet/${editingId.value}`, payload)
      const updated = response.data?.data

      if (updated) {
        const index = wallets.value.findIndex(w => w.id_dompet === editingId.value)
        if (index !== -1) wallets.value[index] = updated
      } else {
        await loadWallets()
      }
    } else {
      const response = await api.post('/dompet', payload)
      const created = response.data?.data

      if (created) {
        wallets.value.unshift(created)
      } else {
        await loadWallets()
      }
    }

    closeModal()
  } catch (err) {
    if (err?.response?.status === 401) {
      window.location.href = '/login'
      return
    }
    formError.value = getErrorMessage(err, 'Could not save this wallet.')
  } finally {
    saving.value = false
  }
}

async function deleteWallet(wallet) {
  if (!window.confirm(`Delete "${wallet.nama_dompet}"?`)) return

  error.value = ''

  try {
    await api.delete(`/dompet/${wallet.id_dompet}`)
    wallets.value = wallets.value.filter(w => w.id_dompet !== wallet.id_dompet)
  } catch (err) {
    if (err?.response?.status === 401) {
      window.location.href = '/login'
      return
    }
    error.value = getErrorMessage(err, 'Could not delete this wallet.')
  }
}

function formatMoney(value) {
  const amount = Number(value || 0)
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    maximumFractionDigits: 2,
  }).format(amount)
}

function formatType(type) {
  return {
    cash: 'Cash',
    bank: 'Bank',
    ewallet: 'E-Wallet',
    tabungan: 'Savings',
  }[type] || type
}

function walletIcon(type) {
  return {
    cash: '💵',
    bank: '🏦',
    ewallet: '📱',
    tabungan: '🎯',
  }[type] || '▣'
}

onMounted(loadWallets)
</script>

<style scoped>
.page {
  width: 100%;
  max-width: 1180px;
  margin: 0 auto;
  padding: 34px 34px 48px;
  box-sizing: border-box;
  color: #172033;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  gap: 24px;
  margin-bottom: 28px;
}

.eyebrow {
  display: block;
  color: #6c5ce7;
  font-size: 11px;
  font-weight: 800;
  letter-spacing: .12em;
  margin-bottom: 7px;
}

h1, h2, p { margin: 0; }

h1 {
  font-size: 30px;
  line-height: 1.1;
  margin-bottom: 7px;
}

.page-header p,
.modal-header p {
  color: #7b8498;
  font-size: 14px;
}

.primary-button,
.secondary-button,
.cancel-button {
  border: 0;
  border-radius: 10px;
  min-height: 42px;
  padding: 0 17px;
  font-weight: 700;
  cursor: pointer;
}

.primary-button {
  color: white;
  background: #6655e9;
  box-shadow: 0 7px 18px rgba(102, 85, 233, .18);
}

.primary-button span { margin-right: 5px; font-size: 18px; }

.primary-button:disabled { opacity: .65; cursor: not-allowed; }

.secondary-button,
.cancel-button {
  color: #5c6476;
  background: #f0f1f6;
}

.alert,
.form-error {
  color: #b42318;
  background: #fff1f0;
  border: 1px solid #ffd5d1;
  border-radius: 10px;
  padding: 11px 13px;
  font-size: 13px;
  margin-bottom: 18px;
}

.wallet-grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 18px;
}

.wallet-card,
.state-card {
  background: white;
  border: 1px solid #e8eaf0;
  border-radius: 16px;
  box-shadow: 0 8px 28px rgba(26, 32, 55, .05);
}

.wallet-card {
  padding: 21px;
}

.wallet-top {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.wallet-icon,
.state-icon {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  display: grid;
  place-items: center;
  background: #f0edff;
  font-size: 20px;
}

.wallet-actions {
  display: flex;
  gap: 5px;
}

.icon-button {
  width: 32px;
  height: 32px;
  border: 0;
  border-radius: 8px;
  background: #f5f6fa;
  color: #667085;
  cursor: pointer;
}

.icon-button.danger:hover {
  background: #fff0f0;
  color: #d92d20;
}

.wallet-name {
  font-size: 18px;
  font-weight: 800;
  margin-top: 19px;
}

.wallet-type {
  color: #8a91a1;
  font-size: 12px;
  margin-top: 4px;
}

.wallet-balance {
  margin-top: 27px;
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.wallet-balance span,
.currency {
  color: #8a91a1;
  font-size: 12px;
}

.wallet-balance strong {
  font-size: 23px;
}

.currency {
  margin-top: 13px;
}

.state-card {
  min-height: 300px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 35px;
}

.state-card .state-icon { margin-bottom: 16px; }

.state-card strong {
  font-size: 18px;
  margin-bottom: 7px;
}

.state-card span {
  color: #8a91a1;
  font-size: 13px;
  max-width: 430px;
  line-height: 1.6;
  margin-bottom: 18px;
}

.modal-backdrop {
  position: fixed;
  inset: 0;
  z-index: 100;
  display: grid;
  place-items: center;
  padding: 20px;
  background: rgba(21, 25, 39, .48);
}

.modal {
  position: relative;
  width: min(480px, 100%);
  max-height: 90vh;
  overflow-y: auto;
  background: white;
  border-radius: 18px;
  padding: 30px;
  box-sizing: border-box;
  box-shadow: 0 24px 70px rgba(20, 25, 45, .2);
}

.close-button {
  position: absolute;
  right: 20px;
  top: 18px;
  border: 0;
  background: transparent;
  color: #9299aa;
  font-size: 25px;
  cursor: pointer;
}

.modal-header { margin-bottom: 24px; padding-right: 25px; }
.modal-header h2 { font-size: 24px; margin-bottom: 6px; }

form {
  display: flex;
  flex-direction: column;
  gap: 17px;
}

label {
  display: flex;
  flex-direction: column;
  gap: 7px;
  color: #424a5b;
  font-size: 12px;
  font-weight: 700;
}

input, select {
  width: 100%;
  height: 42px;
  box-sizing: border-box;
  border: 1px solid #dfe2e9;
  border-radius: 9px;
  padding: 0 12px;
  outline: none;
  color: #252b39;
  background: white;
  font: inherit;
}

input:focus, select:focus {
  border-color: #8c80ed;
  box-shadow: 0 0 0 3px rgba(108, 92, 231, .1);
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 5px;
}

@media (max-width: 900px) {
  .wallet-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}

@media (max-width: 650px) {
  .page { padding: 25px 16px 35px; }
  .page-header { align-items: flex-start; flex-direction: column; }
  .primary-button { width: 100%; }
  .wallet-grid { grid-template-columns: 1fr; }
  .modal { padding: 24px 20px; }
}
</style>
