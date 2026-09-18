<template>
  <div class="help-page">
    <div class="help-content">

      <!-- =========================================
           HEADER
      ========================================== -->
      <div class="page-header">
        <div>
          <span class="eyebrow">HELP & SUPPORT</span>

          <h1>How can we help?</h1>

          <p>
            Find answers and learn how to use MoneyFlow.
          </p>
        </div>
      </div>

      <!-- =========================================
           SEARCH
      ========================================== -->
      <section class="search-panel">
        <div class="search-icon">
          🔍
        </div>

        <div class="search-content">
          <span class="search-label">
            SEARCH HELP
          </span>

          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search for a question or topic..."
          />
        </div>

        <button
          v-if="searchQuery"
          class="clear-button"
          @click="searchQuery = ''"
        >
          Clear
        </button>
      </section>

      <!-- =========================================
           SEARCH RESULTS
      ========================================== -->
      <section
        v-if="searchQuery"
        class="panel search-results-panel"
      >
        <div class="panel-header">
          <div>
            <h3>Search Results</h3>

            <p>
              {{ filteredFaqs.length }}
              result{{ filteredFaqs.length === 1 ? '' : 's' }}
              found.
            </p>
          </div>
        </div>

        <div
          v-if="filteredFaqs.length"
          class="faq-list"
        >
          <div
            v-for="faq in filteredFaqs"
            :key="faq.id"
            class="faq-item"
            :class="{ active: openFaq === faq.id }"
          >
            <button
              class="faq-question"
              @click="toggleFaq(faq.id)"
            >
              <div class="faq-question-left">
                <span class="faq-category">
                  {{ faq.category }}
                </span>

                <span class="faq-title">
                  {{ faq.question }}
                </span>
              </div>

              <span class="faq-toggle">
                {{ openFaq === faq.id ? '−' : '+' }}
              </span>
            </button>

            <div
              v-if="openFaq === faq.id"
              class="faq-answer"
            >
              {{ faq.answer }}
            </div>
          </div>
        </div>

        <div
          v-else
          class="empty-search"
        >
          <div class="empty-icon">
            ?
          </div>

          <h4>No results found</h4>

          <p>
            Try searching for another topic such as
            transactions, wallets, budgets, savings, or reports.
          </p>

          <button
            class="reset-search"
            @click="searchQuery = ''"
          >
            Clear Search
          </button>
        </div>
      </section>

      <!-- =========================================
           QUICK HELP
      ========================================== -->
      <section
        v-if="!searchQuery"
        class="quick-help-section"
      >
        <div class="section-heading">
          <div>
            <h2>Quick Help</h2>

            <p>
              Learn about the main features of MoneyFlow.
            </p>
          </div>
        </div>

        <div class="quick-help-grid">

          <!-- TRANSACTIONS -->
          <button
            class="help-card"
            @click="selectCategory('Transactions')"
          >
            <div class="help-card-icon purple">
              ↔
            </div>

            <div class="help-card-content">
              <h3>Transactions</h3>

              <p>
                Learn how to record income and expenses.
              </p>

              <span class="learn-more">
                Learn more →
              </span>
            </div>
          </button>

          <!-- WALLETS -->
          <button
            class="help-card"
            @click="selectCategory('Wallets')"
          >
            <div class="help-card-icon green">
              $
            </div>

            <div class="help-card-content">
              <h3>Wallets</h3>

              <p>
                Understand your wallets and balances.
              </p>

              <span class="learn-more">
                Learn more →
              </span>
            </div>
          </button>

          <!-- BUDGETS -->
          <button
            class="help-card"
            @click="selectCategory('Budgets')"
          >
            <div class="help-card-icon orange">
              ◷
            </div>

            <div class="help-card-content">
              <h3>Budgets</h3>

              <p>
                Learn how to create and manage budgets.
              </p>

              <span class="learn-more">
                Learn more →
              </span>
            </div>
          </button>

          <!-- SAVINGS -->
          <button
            class="help-card"
            @click="selectCategory('Savings')"
          >
            <div class="help-card-icon blue">
              ★
            </div>

            <div class="help-card-content">
              <h3>Savings</h3>

              <p>
                Track your savings goals and progress.
              </p>

              <span class="learn-more">
                Learn more →
              </span>
            </div>
          </button>

          <!-- REPORTS -->
          <button
            class="help-card"
            @click="selectCategory('Reports')"
          >
            <div class="help-card-icon red">
              ▤
            </div>

            <div class="help-card-content">
              <h3>Reports</h3>

              <p>
                Understand your reports and export data.
              </p>

              <span class="learn-more">
                Learn more →
              </span>
            </div>
          </button>

        </div>
      </section>

      <!-- =========================================
           FAQ
      ========================================== -->
      <section
        v-if="!searchQuery"
        class="panel faq-panel"
      >
        <div class="panel-header faq-panel-header">
          <div>
            <h3>Frequently Asked Questions</h3>

            <p>
              Common questions about using MoneyFlow.
            </p>
          </div>

          <button
            v-if="selectedCategory"
            class="category-reset"
            @click="selectedCategory = ''"
          >
            Show all
          </button>
        </div>

        <!-- CATEGORY FILTER -->
        <div class="category-filter">
          <button
            class="category-button"
            :class="{ selected: selectedCategory === '' }"
            @click="selectedCategory = ''"
          >
            All
          </button>

          <button
            v-for="category in categories"
            :key="category"
            class="category-button"
            :class="{
              selected: selectedCategory === category
            }"
            @click="selectedCategory = category"
          >
            {{ category }}
          </button>
        </div>

        <!-- FAQ LIST -->
        <div class="faq-list">
          <div
            v-for="faq in displayedFaqs"
            :key="faq.id"
            class="faq-item"
            :class="{ active: openFaq === faq.id }"
          >
            <button
              class="faq-question"
              @click="toggleFaq(faq.id)"
            >
              <div class="faq-question-left">
                <span class="faq-category">
                  {{ faq.category }}
                </span>

                <span class="faq-title">
                  {{ faq.question }}
                </span>
              </div>

              <span class="faq-toggle">
                {{ openFaq === faq.id ? '−' : '+' }}
              </span>
            </button>

            <div
              v-if="openFaq === faq.id"
              class="faq-answer"
            >
              {{ faq.answer }}
            </div>
          </div>
        </div>

        <!-- EMPTY FAQ -->
        <div
          v-if="!displayedFaqs.length"
          class="empty-faq"
        >
          <p>
            No questions available for this category.
          </p>
        </div>
      </section>

      <!-- =========================================
           STILL NEED HELP
      ========================================== -->
      <section class="support-panel">
        <div class="support-icon">
          ?
        </div>

        <div class="support-content">
          <h3>Still need help?</h3>

          <p>
            If you cannot find what you're looking for,
            contact your administrator for assistance.
          </p>
        </div>

        <button
          class="support-button"
          @click="showContactInfo"
        >
          Contact Admin
          <span>→</span>
        </button>
      </section>

      <!-- =========================================
           CONTACT INFO
      ========================================== -->
      <div
        v-if="contactVisible"
        class="contact-panel"
      >
        <div class="contact-icon">
          ✉
        </div>

        <div>
          <h4>Contact your administrator</h4>

          <p>
            Your MoneyFlow administrator can help with
            account issues, application problems, or other
            questions that are not covered here.
          </p>
        </div>

        <button
          class="close-contact"
          @click="contactVisible = false"
        >
          ×
        </button>
      </div>

      <!-- =========================================
           FOOTER
      ========================================== -->
      <div class="help-footer">
        <div>
          <strong>MoneyFlow</strong>
          <span>Help & Support</span>
        </div>

        <span>
          Manage your finances simply and clearly.
        </span>
      </div>

    </div>
  </div>
</template>

<script setup>
import {
  computed,
  ref
} from 'vue'

/* =========================================
   STATE
========================================= */

const searchQuery = ref('')

const selectedCategory = ref('')

const openFaq = ref(null)

const contactVisible = ref(false)

/* =========================================
   CATEGORIES
========================================= */

const categories = [
  'Transactions',
  'Wallets',
  'Budgets',
  'Savings',
  'Reports'
]

/* =========================================
   FAQ DATA
========================================= */

const faqs = [
  {
    id: 1,
    category: 'Transactions',
    question: 'How do I add a transaction?',
    answer:
      'Open the Transactions page and choose the option to add a new transaction. Select whether it is income or an expense, choose the wallet and category, enter the amount and date, then save the transaction.'
  },

  {
    id: 2,
    category: 'Transactions',
    question: 'What is the difference between income and expenses?',
    answer:
      'Income represents money coming into your account, while expenses represent money leaving your account. MoneyFlow uses these values to calculate your daily activity and financial summaries.'
  },

  {
    id: 3,
    category: 'Transactions',
    question: 'Can I assign a transaction to a wallet?',
    answer:
      'Yes. When creating a transaction, you can select the wallet associated with it. This helps MoneyFlow keep track of where your money is being recorded.'
  },

  {
    id: 4,
    category: 'Transactions',
    question: 'Why is my transaction not appearing in the Daily Report?',
    answer:
      'The Daily Report shows transactions based on their recorded date. Check that the transaction date matches the date selected on the report page.'
  },

  {
    id: 5,
    category: 'Wallets',
    question: 'What is a wallet?',
    answer:
      'A wallet represents a place where you keep or manage money, such as cash, a bank account, or another account you want to track.'
  },

  {
    id: 6,
    category: 'Wallets',
    question: 'Why does my wallet balance change?',
    answer:
      'Wallet balances are affected by transactions associated with that wallet. Income generally increases the balance, while expenses generally decrease it.'
  },

  {
    id: 7,
    category: 'Wallets',
    question: 'Can I have multiple wallets?',
    answer:
      'Yes. You can create multiple wallets to separate different sources of money, such as cash, savings, or bank accounts.'
  },

  {
    id: 8,
    category: 'Wallets',
    question: 'What should I do if my wallet balance looks incorrect?',
    answer:
      'Check the transactions assigned to that wallet and make sure the transaction amounts, types, and dates are correct. Incorrect or missing transactions can affect the displayed balance.'
  },

  {
    id: 9,
    category: 'Budgets',
    question: 'What is a budget?',
    answer:
      'A budget lets you set a spending limit for a particular category or period. It can help you compare your planned spending with your actual expenses.'
  },

  {
    id: 10,
    category: 'Budgets',
    question: 'How does MoneyFlow calculate budget spending?',
    answer:
      'Budget spending is based on the expenses that are associated with the relevant budget category and period.'
  },

  {
    id: 11,
    category: 'Budgets',
    question: 'What happens when I reach my budget limit?',
    answer:
      'Your budget progress shows how much of the planned amount has been used. Reaching or exceeding the limit indicates that your recorded spending has reached or passed the budget amount.'
  },

  {
    id: 12,
    category: 'Budgets',
    question: 'Can I change a budget after creating it?',
    answer:
      'Yes. If your application provides editing controls for budgets, you can update the budget information from the Budgets page.'
  },

  {
    id: 13,
    category: 'Savings',
    question: 'What is a savings goal?',
    answer:
      'A savings goal represents something you are saving money toward. You can set a target amount and track your progress toward that target.'
  },

  {
    id: 14,
    category: 'Savings',
    question: 'How does savings progress work?',
    answer:
      'Savings progress compares the amount saved toward a goal with its target amount. As the saved amount increases, your progress toward the goal increases as well.'
  },

  {
    id: 15,
    category: 'Savings',
    question: 'Can I have more than one savings goal?',
    answer:
      'Yes. You can use multiple goals to keep track of different things you want to save for.'
  },

  {
    id: 16,
    category: 'Savings',
    question: 'What happens when I reach my savings target?',
    answer:
      'When the saved amount reaches the target amount, the goal is considered complete. You can then keep it as a record or manage it according to the options available in your Savings page.'
  },

  {
    id: 17,
    category: 'Reports',
    question: 'What is the Daily Report?',
    answer:
      'The Daily Report provides a summary of your financial activity for a selected date. It includes income, expenses, net result, transactions, expense categories, and a daily summary.'
  },

  {
    id: 18,
    category: 'Reports',
    question: 'How do I view another date in the Daily Report?',
    answer:
      'Use the previous and next buttons on the Daily Report, or select a specific date using the date picker.'
  },

  {
    id: 19,
    category: 'Reports',
    question: 'What does Net Result mean?',
    answer:
      'Net Result is calculated by subtracting your total expenses from your total income for the selected day.'
  },

  {
    id: 20,
    category: 'Reports',
    question: 'How can I export my financial data to Excel?',
    answer:
      'Use the Export Excel option in the MoneyFlow navigation. The exported file can be used to review or work with your financial records outside the application.'
  }
]

/* =========================================
   SEARCH
========================================= */

const filteredFaqs = computed(() => {
  const query =
    searchQuery.value
      .trim()
      .toLowerCase()

  if (!query) {
    return faqs
  }

  return faqs.filter(faq => {
    return (
      faq.question
        .toLowerCase()
        .includes(query) ||

      faq.answer
        .toLowerCase()
        .includes(query) ||

      faq.category
        .toLowerCase()
        .includes(query)
    )
  })
})

/* =========================================
   CATEGORY
========================================= */

const displayedFaqs = computed(() => {
  if (!selectedCategory.value) {
    return faqs
  }

  return faqs.filter(
    faq =>
      faq.category === selectedCategory.value
  )
})

/* =========================================
   ACTIONS
========================================= */

function toggleFaq(id) {
  openFaq.value =
    openFaq.value === id
      ? null
      : id
}

function selectCategory(category) {
  selectedCategory.value = category

  openFaq.value = null

  setTimeout(() => {
    const faqPanel =
      document.querySelector('.faq-panel')

    faqPanel?.scrollIntoView({
      behavior: 'smooth',
      block: 'start'
    })
  }, 50)
}

function showContactInfo() {
  contactVisible.value = true

  setTimeout(() => {
    const element =
      document.querySelector('.contact-panel')

    element?.scrollIntoView({
      behavior: 'smooth',
      block: 'center'
    })
  }, 50)
}
</script>

<style scoped>
/* =========================================
   PAGE
========================================= */

.help-page {
  min-height: 100vh;
  padding: 28px 30px 35px;
  background: #f7f8fc;
  color: #25263a;
}

.help-content {
  width: 100%;
  max-width: 1220px;
  margin: 0 auto;
}

/* =========================================
   HEADER
========================================= */

.page-header {
  margin-bottom: 20px;
}

.eyebrow {
  display: block;
  margin-bottom: 5px;
  color: #6c5ce7;
  font-size: 9px;
  font-weight: 800;
  letter-spacing: 1.2px;
}

.page-header h1 {
  margin: 0;
  color: #14213d;
  font-size: 25px;
  line-height: 1.2;
  font-weight: 750;
  letter-spacing: -0.5px;
}

.page-header p {
  margin: 6px 0 0;
  color: #9299aa;
  font-size: 11px;
  line-height: 1.5;
}

/* =========================================
   SEARCH
========================================= */

.search-panel {
  display: flex;
  align-items: center;
  gap: 14px;
  min-height: 84px;
  margin-bottom: 20px;
  padding: 18px;
  border: 1px solid #e8eaf1;
  border-radius: 13px;
  background: #ffffff;
  box-shadow:
    0 8px 25px rgba(30, 40, 80, 0.025);
}

.search-icon {
  display: flex;
  flex-shrink: 0;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 9px;
  background: #eeebff;
  color: #6c5ce7;
  font-size: 15px;
}

.search-content {
  flex: 1;
  min-width: 0;
}

.search-label {
  display: block;
  margin-bottom: 5px;
  color: #a0a6b5;
  font-size: 8px;
  font-weight: 800;
  letter-spacing: 0.8px;
}

.search-content input {
  width: 100%;
  padding: 0;
  border: none;
  outline: none;
  background: transparent;
  color: #30354a;
  font-family: inherit;
  font-size: 11px;
  font-weight: 600;
}

.search-content input::placeholder {
  color: #b0b5c1;
  font-weight: 500;
}

.clear-button {
  min-height: 30px;
  padding: 0 10px;
  border: 1px solid #e3e5ec;
  border-radius: 7px;
  background: #ffffff;
  color: #7d8495;
  font-family: inherit;
  font-size: 8px;
  font-weight: 700;
  cursor: pointer;
}

.clear-button:hover {
  border-color: #d7d2fb;
  background: #faf9ff;
  color: #6c5ce7;
}

/* =========================================
   QUICK HELP
========================================= */

.quick-help-section {
  margin-bottom: 20px;
}

.section-heading {
  margin-bottom: 12px;
}

.section-heading h2 {
  margin: 0;
  color: #25263a;
  font-size: 13px;
  font-weight: 750;
}

.section-heading p {
  margin: 4px 0 0;
  color: #a0a6b5;
  font-size: 9px;
  line-height: 1.4;
}

.quick-help-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 17px;
}

/* =========================================
   HELP CARDS
========================================= */

.help-card {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  min-height: 157px;
  padding: 18px;
  border: 1px solid #e9ebf2;
  border-radius: 13px;
  outline: none;
  background: #ffffff;
  box-shadow:
    0 8px 25px rgba(30, 40, 80, 0.025);
  font-family: inherit;
  text-align: left;
  cursor: pointer;
  transition:
    transform 0.18s ease,
    border-color 0.18s ease,
    box-shadow 0.18s ease;
}

.help-card:hover {
  border-color: #ddd9fa;
  box-shadow:
    0 10px 28px rgba(30, 40, 80, 0.05);
  transform: translateY(-2px);
}

.help-card:active {
  transform: translateY(0);
}

.help-card-icon {
  display: flex;
  flex-shrink: 0;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 9px;
  font-size: 14px;
  font-weight: 800;
}

.help-card-icon.purple {
  background: #eeebff;
  color: #6c5ce7;
}

.help-card-icon.green {
  background: #e8f8f0;
  color: #19a566;
}

.help-card-icon.orange {
  background: #fff5e8;
  color: #e99a35;
}

.help-card-icon.blue {
  background: #eaf3ff;
  color: #5d8fdc;
}

.help-card-icon.red {
  background: #fff0f0;
  color: #e95f65;
}

.help-card-content {
  min-width: 0;
  padding-top: 1px;
}

.help-card-content h3 {
  margin: 0 0 7px;
  color: #30354a;
  font-size: 12px;
  font-weight: 750;
}

.help-card-content p {
  max-width: 190px;
  margin: 0 0 15px;
  color: #999faf;
  font-size: 9px;
  line-height: 1.55;
}

.learn-more {
  color: #6c5ce7;
  font-size: 8px;
  font-weight: 750;
}

/* =========================================
   PANELS
========================================= */

.panel {
  overflow: hidden;
  margin-bottom: 20px;
  border: 1px solid #e8eaf1;
  border-radius: 13px;
  background: #ffffff;
  box-shadow:
    0 8px 25px rgba(30, 40, 80, 0.025);
}

.panel-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 15px;
  padding: 19px 19px 14px;
}

.panel-header h3 {
  margin: 0;
  color: #25263a;
  font-size: 13px;
  font-weight: 750;
}

.panel-header p {
  margin: 5px 0 0;
  color: #a0a6b5;
  font-size: 9px;
  line-height: 1.4;
}

/* =========================================
   FAQ CATEGORY FILTER
========================================= */

.category-filter {
  display: flex;
  flex-wrap: wrap;
  gap: 7px;
  padding: 0 19px 16px;
}

.category-button {
  min-height: 28px;
  padding: 0 10px;
  border: 1px solid #e4e6ed;
  border-radius: 7px;
  background: #ffffff;
  color: #7d8495;
  font-family: inherit;
  font-size: 8px;
  font-weight: 700;
  cursor: pointer;
  transition:
    background 0.18s ease,
    border-color 0.18s ease,
    color 0.18s ease;
}

.category-button:hover {
  border-color: #d9d5fa;
  color: #6c5ce7;
}

.category-button.selected {
  border-color: #6c5ce7;
  background: #6c5ce7;
  color: #ffffff;
}

.category-reset {
  min-height: 28px;
  padding: 0 10px;
  border: 1px solid #ddd9fa;
  border-radius: 7px;
  background: #f7f5ff;
  color: #6c5ce7;
  font-family: inherit;
  font-size: 8px;
  font-weight: 700;
  cursor: pointer;
}

/* =========================================
   FAQ
========================================= */

.faq-list {
  padding: 0 19px 19px;
}

.faq-item {
  border-bottom: 1px solid #f0f1f5;
}

.faq-item:last-child {
  border-bottom: none;
}

.faq-question {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 15px;
  width: 100%;
  min-height: 68px;
  padding: 13px 0;
  border: none;
  outline: none;
  background: transparent;
  font-family: inherit;
  text-align: left;
  cursor: pointer;
}

.faq-question-left {
  display: flex;
  flex-direction: column;
  gap: 5px;
  min-width: 0;
}

.faq-category {
  color: #6c5ce7;
  font-size: 7px;
  font-weight: 800;
  letter-spacing: 0.7px;
  text-transform: uppercase;
}

.faq-title {
  color: #4d5365;
  font-size: 10px;
  font-weight: 650;
  line-height: 1.45;
}

.faq-toggle {
  display: flex;
  flex-shrink: 0;
  align-items: center;
  justify-content: center;
  width: 26px;
  height: 26px;
  border: 1px solid #e5e7ed;
  border-radius: 7px;
  background: #ffffff;
  color: #6c5ce7;
  font-size: 15px;
  font-weight: 500;
}

.faq-item:hover .faq-toggle {
  border-color: #d9d5fa;
  background: #faf9ff;
}

.faq-item.active .faq-toggle {
  border-color: #d9d5fa;
  background: #f3f1ff;
}

.faq-item.active .faq-title {
  color: #30354a;
  font-weight: 750;
}

.faq-answer {
  max-width: 900px;
  padding: 0 50px 18px 0;
  color: #8c93a4;
  font-size: 9px;
  line-height: 1.7;
}

.empty-faq {
  padding: 30px 19px 35px;
  color: #a0a6b5;
  font-size: 9px;
  text-align: center;
}

/* =========================================
   SEARCH RESULTS
========================================= */

.search-results-panel {
  margin-bottom: 20px;
}

/* =========================================
   EMPTY SEARCH
========================================= */

.empty-search {
  padding: 45px 20px 50px;
  text-align: center;
}

.empty-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 42px;
  height: 42px;
  margin: 0 auto 12px;
  border-radius: 10px;
  background: #eeebff;
  color: #6c5ce7;
  font-size: 16px;
  font-weight: 800;
}

.empty-search h4 {
  margin: 0 0 6px;
  color: #34394d;
  font-size: 13px;
}

.empty-search p {
  max-width: 400px;
  margin: 0 auto 15px;
  color: #a0a6b5;
  font-size: 9px;
  line-height: 1.6;
}

.reset-search {
  min-height: 31px;
  padding: 0 12px;
  border: 1px solid #ddd9fa;
  border-radius: 7px;
  background: #f5f3ff;
  color: #6c5ce7;
  font-family: inherit;
  font-size: 8px;
  font-weight: 750;
  cursor: pointer;
}

/* =========================================
   SUPPORT
========================================= */

.support-panel {
  display: flex;
  align-items: center;
  gap: 14px;
  min-height: 105px;
  margin-bottom: 0;
  padding: 18px 19px;
  border: 1px solid #ddd9fa;
  border-radius: 13px;
  background: #faf9ff;
}

.support-icon {
  display: flex;
  flex-shrink: 0;
  align-items: center;
  justify-content: center;
  width: 38px;
  height: 38px;
  border-radius: 9px;
  background: #eeebff;
  color: #6c5ce7;
  font-size: 15px;
  font-weight: 800;
}

.support-content {
  flex: 1;
  min-width: 0;
}

.support-content h3 {
  margin: 0 0 5px;
  color: #30354a;
  font-size: 12px;
  font-weight: 750;
}

.support-content p {
  max-width: 700px;
  margin: 0;
  color: #949bab;
  font-size: 9px;
  line-height: 1.55;
}

.support-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 7px;
  flex-shrink: 0;
  min-height: 34px;
  padding: 0 13px;
  border: none;
  border-radius: 8px;
  background: #6c5ce7;
  color: #ffffff;
  font-family: inherit;
  font-size: 9px;
  font-weight: 750;
  cursor: pointer;
  transition:
    background 0.18s ease,
    transform 0.18s ease;
}

.support-button:hover {
  background: #5d4ed3;
}

.support-button:active {
  transform: translateY(1px);
}

.support-button span {
  font-size: 12px;
}

/* =========================================
   CONTACT
========================================= */

.contact-panel {
  position: relative;
  display: flex;
  align-items: flex-start;
  gap: 13px;
  margin-top: 14px;
  padding: 17px 45px 17px 17px;
  border: 1px solid #e8eaf1;
  border-radius: 12px;
  background: #ffffff;
  box-shadow:
    0 8px 25px rgba(30, 40, 80, 0.025);
}

.contact-icon {
  display: flex;
  flex-shrink: 0;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border-radius: 8px;
  background: #e8f8f0;
  color: #19a566;
  font-size: 13px;
}

.contact-panel h4 {
  margin: 1px 0 5px;
  color: #30354a;
  font-size: 11px;
  font-weight: 750;
}

.contact-panel p {
  max-width: 750px;
  margin: 0;
  color: #969dac;
  font-size: 9px;
  line-height: 1.55;
}

.close-contact {
  position: absolute;
  top: 13px;
  right: 13px;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 25px;
  height: 25px;
  border: 1px solid #e6e8ee;
  border-radius: 6px;
  background: #ffffff;
  color: #8d94a3;
  font-size: 15px;
  line-height: 1;
  cursor: pointer;
}

.close-contact:hover {
  color: #6c5ce7;
  border-color: #d9d5fa;
  background: #faf9ff;
}

/* =========================================
   FOOTER
========================================= */

.help-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 15px;
  padding: 20px 2px 0;
  color: #a2a8b5;
  font-size: 8px;
}

.help-footer div {
  display: flex;
  align-items: center;
  gap: 7px;
}

.help-footer strong {
  color: #6c5ce7;
  font-size: 9px;
}

.help-footer span {
  color: #a2a8b5;
}

/* =========================================
   TABLET
========================================= */

@media (max-width: 1100px) {
  .quick-help-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

/* =========================================
   MOBILE
========================================= */

@media (max-width: 700px) {
  .help-page {
    padding: 17px 14px 90px;
  }

  .page-header {
    margin-bottom: 16px;
  }

  .page-header h1 {
    font-size: 22px;
  }

  .search-panel {
    min-height: 75px;
    padding: 15px;
  }

  .search-icon {
    width: 33px;
    height: 33px;
  }

  .quick-help-grid {
    grid-template-columns: 1fr;
    gap: 14px;
  }

  .help-card {
    min-height: 140px;
  }

  .panel-header {
    align-items: flex-start;
    flex-direction: column;
    padding: 17px 15px 13px;
  }

  .category-reset {
    align-self: flex-start;
  }

  .category-filter {
    padding: 0 15px 14px;
  }

  .faq-list {
    padding: 0 15px 17px;
  }

  .faq-question {
    min-height: 64px;
  }

  .faq-answer {
    padding-right: 25px;
  }

  .support-panel {
    align-items: flex-start;
    flex-wrap: wrap;
    min-height: 120px;
    padding: 16px;
  }

  .support-content {
    flex: 1 1 calc(100% - 52px);
  }

  .support-button {
    width: 100%;
    margin-top: 3px;
  }

  .help-footer {
    align-items: flex-start;
    flex-direction: column;
    padding-top: 17px;
  }
}

/* =========================================
   SMALL MOBILE
========================================= */

@media (max-width: 400px) {
  .help-page {
    padding-left: 11px;
    padding-right: 11px;
  }

  .page-header h1 {
    font-size: 20px;
  }

  .search-content input {
    font-size: 10px;
  }

  .help-card {
    min-height: 135px;
    padding: 16px;
  }

  .help-card-content h3 {
    font-size: 11px;
  }

  .help-card-content p {
    font-size: 8px;
  }

  .faq-title {
    font-size: 9px;
  }

  .faq-answer {
    font-size: 8px;
  }
}

</style>