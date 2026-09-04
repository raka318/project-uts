<template>
  <div class="page">

    <!-- HEADER -->
    <div class="page-header">

      <div>
        <span class="eyebrow">PERSONAL ACCOUNT</span>

        <h1>Savings</h1>

        <p>
          Keep working toward your financial goals.
        </p>
      </div>

      <button
        class="primary-button"
        @click="addGoal"
      >
        <span>+</span>
        Add Savings Goal
      </button>

    </div>


    <!-- TOTAL SAVINGS -->
    <div class="summary-card">

      <div class="summary-icon">
        $
      </div>

      <div>
        <span>Total Saved</span>

        <strong>
          $3,420
        </strong>

        <p>
          Across all your savings goals
        </p>
      </div>

    </div>


    <!-- GOALS -->
    <div class="section-header">

      <div>
        <h2>Savings Goals</h2>

        <p>
          Track your progress toward your goals.
        </p>
      </div>

    </div>


    <div class="goals-grid">

      <div
        v-for="goal in goals"
        :key="goal.id"
        class="goal-card"
      >

        <div class="goal-header">

          <div class="goal-icon">
            {{ goal.icon }}
          </div>

          <button class="more-button">
            ⋮
          </button>

        </div>


        <h3>
          {{ goal.name }}
        </h3>


        <div class="goal-content">

          <!-- DONUT -->
          <div
            class="donut"
            :style="{
              '--progress': goal.percent + '%'
            }"
          >

            <div class="donut-inner">
              <strong>
                {{ goal.percent }}%
              </strong>

              <span>
                Complete
              </span>
            </div>

          </div>


          <div class="goal-info">

            <strong>
              ${{ goal.saved }}
            </strong>

            <span>
              of ${{ goal.target }}
            </span>

            <p>
              Target: {{ goal.date }}
            </p>

          </div>

        </div>

      </div>

    </div>

  </div>
</template>


<script setup>
import { ref } from 'vue'


const goals = ref([
  {
    id: 1,
    name: 'New Laptop',
    saved: '1,360',
    target: '2,000',
    percent: 68,
    date: 'December 2026',
    icon: '💻'
  },
  {
    id: 2,
    name: 'Emergency Fund',
    saved: '1,200',
    target: '3,000',
    percent: 40,
    date: 'March 2027',
    icon: '🛡️'
  },
  {
    id: 3,
    name: 'Vacation',
    saved: '650',
    target: '1,500',
    percent: 43,
    date: 'June 2027',
    icon: '✈️'
  },
  {
    id: 4,
    name: 'New Phone',
    saved: '210',
    target: '1,000',
    percent: 21,
    date: 'January 2027',
    icon: '📱'
  }
])


const addGoal = () => {

  goals.value.push({
    id: Date.now(),
    name: 'New Goal',
    saved: '0',
    target: '1,000',
    percent: 0,
    date: '2027',
    icon: '🎯'
  })

}
</script>


<style scoped>

* {
  box-sizing: border-box;
}

.page {
  padding: 32px 42px 50px;

  color: #172033;
}


/* HEADER */

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

  letter-spacing: -.7px;
}

.page-header p {
  margin: 0;

  color: #9299aa;

  font-size: 13px;
}


/* BUTTON */

.primary-button {
  height: 44px;

  display: flex;
  align-items: center;

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
    0 8px 20px rgba(102, 85, 233, .2);
}


/* SUMMARY */

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

  border-radius: 12px;

  background: #eeebff;

  color: #6655e9;

  font-size: 20px;
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


/* SECTION */

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


/* GOALS */

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


/* GOAL CONTENT */

.goal-content {
  display: flex;
  align-items: center;

  gap: 24px;
}


/* DONUT */

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


/* INFO */

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


/* MOBILE */

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

}

</style>