<template>
  <div class="auth-page">

    <!-- =====================================================
         PURPLE BRANDING
    ====================================================== -->
    <section class="brand-side">

      <!-- Decorative circles -->
      <div class="circle circle-top"></div>
      <div class="circle circle-bottom"></div>
      <div class="circle circle-middle"></div>

      <div class="brand-content">

        <div class="brand-icon">
          $
        </div>

        <div class="brand-text">
          <h1>MoneyFlow</h1>

          <p class="brand-description">
            Manage your finances simply,<br />
            clearly, and confidently.
          </p>
        </div>

        <!-- Desktop features -->
        <div class="feature-list">

          <div class="feature">
            <div class="feature-icon">✓</div>

            <div>
              <strong>Stay organized</strong>
              <span>Keep everything in one place.</span>
            </div>
          </div>

          <div class="feature">
            <div class="feature-icon">▣</div>

            <div>
              <strong>Track your progress</strong>
              <span>See your personal overview anytime.</span>
            </div>
          </div>

          <div class="feature">
            <div class="feature-icon">⌁</div>

            <div>
              <strong>Simple and secure</strong>
              <span>Designed to make things easier.</span>
            </div>
          </div>

        </div>

      </div>

      <div class="copyright">
        © 2026 MoneyFlow
      </div>

    </section>


    <!-- =====================================================
         AUTH SIDE
    ====================================================== -->
    <section class="auth-side">

      <div
        class="flip-container"
        :class="{ flipped: isFlipped }"
      >

        <div class="flip-card">


          <!-- =================================================
               LOGIN
          ================================================== -->
          <div class="card-face card-front">

            <div class="auth-header">
              <h2>Welcome back 👋</h2>

              <p>
                Sign in to continue to your account.
              </p>
            </div>


            <!-- EMAIL -->
            <div class="form-group">

              <label>Email address</label>

              <div class="input-wrapper">

                <span class="input-icon">@</span>

                <input
                  type="email"
                  placeholder="you@example.com"
                  v-model="loginEmail"
                />

              </div>

            </div>


            <!-- PASSWORD -->
            <div class="form-group">

              <div class="password-label">

                <label>Password</label>

                <button
                  type="button"
                  class="forgot-password"
                >
                  Forgot password?
                </button>

              </div>

              <div class="input-wrapper">

                <span class="input-icon">•••</span>

                <input
                  :type="showLoginPassword ? 'text' : 'password'"
                  placeholder="Enter your password"
                  v-model="loginPassword"
                />

                <button
                  type="button"
                  class="show-password"
                  @click="showLoginPassword = !showLoginPassword"
                >
                  {{ showLoginPassword ? 'Hide' : 'Show' }}
                </button>

              </div>

            </div>


            <!-- REMEMBER -->
            <div class="remember-row">

              <label class="remember-label">

                <input
                  type="checkbox"
                  v-model="remember"
                />

                <span>Remember me</span>

              </label>

            </div>


            <!-- LOGIN BUTTON -->
            <button
              type="button"
              class="primary-button"
              @click="login"
            >
              <span>Sign in</span>
              <span class="arrow">→</span>
            </button>


            <!-- DIVIDER -->
            <div class="divider">

              <span></span>

              <p>or continue with</p>

              <span></span>

            </div>


            <!-- SOCIAL -->
            <div class="social-buttons">

              <button
                type="button"
                class="social-button"
              >
                <span class="google-icon">G</span>
                Google
              </button>

              <button
                type="button"
                class="social-button"
              >
                <span class="apple-icon">●</span>
                Apple
              </button>

            </div>


            <!-- SWITCH TO REGISTER -->
            <div class="switch-auth">

              <span>
                Don't have an account?
              </span>

              <button
                type="button"
                @click="flipToRegister"
              >
                Create an account
              </button>

            </div>

          </div>


          <!-- =================================================
               REGISTER
          ================================================== -->
          <div class="card-face card-back">

            <div class="auth-header">

              <h2>Create account ✨</h2>

              <p>
                Get started with your MoneyFlow account.
              </p>

            </div>


            <!-- NAME -->
            <div class="form-group">

              <label>Full name</label>

              <div class="input-wrapper">

                <span class="input-icon">◯</span>

                <input
                  type="text"
                  placeholder="John Doe"
                  v-model="registerName"
                />

              </div>

            </div>


            <!-- EMAIL -->
            <div class="form-group">

              <label>Email address</label>

              <div class="input-wrapper">

                <span class="input-icon">@</span>

                <input
                  type="email"
                  placeholder="you@example.com"
                  v-model="registerEmail"
                />

              </div>

            </div>


            <!-- PASSWORD -->
            <div class="form-group">

              <label>Password</label>

              <div class="input-wrapper">

                <span class="input-icon">•••</span>

                <input
                  :type="showRegisterPassword ? 'text' : 'password'"
                  placeholder="Create a password"
                  v-model="registerPassword"
                />

                <button
                  type="button"
                  class="show-password"
                  @click="showRegisterPassword = !showRegisterPassword"
                >
                  {{ showRegisterPassword ? 'Hide' : 'Show' }}
                </button>

              </div>

            </div>


            <!-- CONFIRM PASSWORD -->
            <div class="form-group">

              <label>Confirm password</label>

              <div class="input-wrapper">

                <span class="input-icon">•••</span>

                <input
                  :type="showConfirmPassword ? 'text' : 'password'"
                  placeholder="Confirm your password"
                  v-model="confirmPassword"
                />

                <button
                  type="button"
                  class="show-password"
                  @click="showConfirmPassword = !showConfirmPassword"
                >
                  {{ showConfirmPassword ? 'Hide' : 'Show' }}
                </button>

              </div>

            </div>


            <!-- TERMS -->
            <div class="terms-row">

              <label class="remember-label">

                <input
                  type="checkbox"
                  v-model="agreeTerms"
                />

                <span>
                  I agree to the Terms & Privacy Policy
                </span>

              </label>

            </div>


            <!-- REGISTER BUTTON -->
            <button
              type="button"
              class="primary-button"
              @click="register"
            >
              <span>Create account</span>
              <span class="arrow">→</span>
            </button>


            <!-- SWITCH TO LOGIN
                 IMPORTANT:
                 This is INSIDE the card.
            -->
            <div class="switch-auth register-switch">

              <span>
                Already have an account?
              </span>

              <button
                type="button"
                @click="flipToLogin"
              >
                Sign in
              </button>

            </div>

          </div>

        </div>

      </div>

    </section>

  </div>
</template>


<script setup>
import { ref } from 'vue'


/* =========================================================
   FLIP
========================================================= */

const isFlipped = ref(false)

const flipToRegister = () => {
  isFlipped.value = true
}

const flipToLogin = () => {
  isFlipped.value = false
}


/* =========================================================
   LOGIN
========================================================= */

const loginEmail = ref('')
const loginPassword = ref('')

const remember = ref(false)

const showLoginPassword = ref(false)

const login = () => {

  console.log('Login:', {
    email: loginEmail.value,
    password: loginPassword.value,
    remember: remember.value
  })

}


/* =========================================================
   REGISTER
========================================================= */

const registerName = ref('')
const registerEmail = ref('')
const registerPassword = ref('')
const confirmPassword = ref('')

const agreeTerms = ref(false)

const showRegisterPassword = ref(false)
const showConfirmPassword = ref(false)


const register = () => {

  if (!registerName.value) {
    console.log('Please enter your name')
    return
  }

  if (!registerEmail.value) {
    console.log('Please enter your email')
    return
  }

  if (!registerPassword.value) {
    console.log('Please create a password')
    return
  }

  if (registerPassword.value !== confirmPassword.value) {
    console.log('Passwords do not match')
    return
  }

  if (!agreeTerms.value) {
    console.log('Please agree to the terms')
    return
  }

  console.log('Register:', {
    name: registerName.value,
    email: registerEmail.value,
    password: registerPassword.value
  })

}
</script>


<style scoped>

/* =========================================================
   RESET
========================================================= */

* {
  box-sizing: border-box;
}


/* =========================================================
   PAGE
========================================================= */

.auth-page {
  width: 100%;
  min-height: 100vh;

  display: flex;

  background: #f7f8fc;

  font-family:
    Inter,
    -apple-system,
    BlinkMacSystemFont,
    "Segoe UI",
    sans-serif;

  overflow: hidden;
}


/* =========================================================
   PURPLE BRAND SIDE
========================================================= */

.brand-side {
  position: relative;

  width: 50%;
  min-height: 100vh;

  display: flex;
  align-items: center;

  overflow: hidden;

  color: white;

  background:
    linear-gradient(
      145deg,
      #5f50e9 0%,
      #6955ee 45%,
      #8068ef 100%
    );
}


/* =========================================================
   CIRCLES
========================================================= */

.circle {
  position: absolute;

  border-radius: 50%;

  border: 1px solid rgba(255, 255, 255, 0.15);

  pointer-events: none;
}

.circle-top {
  width: 550px;
  height: 550px;

  top: -335px;
  right: -100px;
}

.circle-bottom {
  width: 600px;
  height: 600px;

  bottom: -410px;
  left: -270px;
}

.circle-middle {
  width: 300px;
  height: 300px;

  bottom: -145px;
  right: -120px;
}


/* =========================================================
   BRAND CONTENT
========================================================= */

.brand-content {
  position: relative;
  z-index: 2;

  width: min(500px, 75%);

  margin: 0 auto;
}


.brand-icon {
  width: 56px;
  height: 56px;

  display: flex;
  align-items: center;
  justify-content: center;

  margin-bottom: 22px;

  border-radius: 14px;

  background: rgba(255, 255, 255, 0.12);

  border: 1px solid rgba(255, 255, 255, 0.2);

  font-size: 30px;
  font-weight: 700;
}


.brand-content h1 {
  margin: 0;

  font-size: clamp(42px, 4vw, 58px);

  line-height: 1;

  letter-spacing: -2px;
}


.brand-description {
  margin: 24px 0 38px;

  font-size: 17px;

  line-height: 1.7;

  color: rgba(255, 255, 255, 0.8);
}


/* =========================================================
   FEATURES
========================================================= */

.feature-list {
  display: flex;
  flex-direction: column;

  gap: 13px;
}


.feature {
  display: flex;
  align-items: center;

  gap: 14px;

  padding: 14px;

  border-radius: 10px;

  background: rgba(255, 255, 255, 0.10);

  border: 1px solid rgba(255, 255, 255, 0.14);

  backdrop-filter: blur(8px);
}


.feature-icon {
  width: 36px;
  height: 36px;

  flex-shrink: 0;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 8px;

  background: rgba(255, 255, 255, 0.14);

  font-size: 16px;
}


.feature strong {
  display: block;

  margin-bottom: 3px;

  font-size: 13px;
}


.feature span {
  display: block;

  font-size: 10px;

  color: rgba(255, 255, 255, 0.65);
}


/* =========================================================
   COPYRIGHT
========================================================= */

.copyright {
  position: absolute;

  left: 6.5%;
  bottom: 26px;

  z-index: 2;

  font-size: 11px;

  color: rgba(255, 255, 255, 0.55);
}


/* =========================================================
   RIGHT SIDE
========================================================= */

.auth-side {
  width: 50%;
  min-height: 100vh;

  display: flex;
  align-items: center;
  justify-content: center;

  padding: 45px;
}


/* =========================================================
   FLIP CONTAINER
========================================================= */

.flip-container {
  width: min(450px, 100%);

  height: 630px;

  perspective: 1400px;
}


/* =========================================================
   FLIP CARD
========================================================= */

.flip-card {
  position: relative;

  width: 100%;
  height: 100%;

  transform-style: preserve-3d;

  transition:
    transform 0.75s cubic-bezier(
      0.4,
      0.2,
      0.2,
      1
    );
}


.flip-container.flipped .flip-card {
  transform: rotateY(180deg);
}


/* =========================================================
   CARD FACES
========================================================= */

.card-face {
  position: absolute;

  inset: 0;

  width: 100%;
  height: 100%;

  padding: 40px;

  border-radius: 20px;

  background: white;

  border: 1px solid #e8e9f0;

  box-shadow:
    0 25px 70px rgba(30, 25, 80, 0.10);

  backface-visibility: hidden;

  -webkit-backface-visibility: hidden;

  overflow: hidden;
}


.card-front {
  transform: rotateY(0deg);
}


.card-back {
  transform: rotateY(180deg);
}


/* =========================================================
   HEADER
========================================================= */

.auth-header {
  margin-bottom: 28px;
}


.auth-header h2 {
  margin: 0 0 8px;

  color: #10182b;

  font-size: 28px;

  line-height: 1.2;

  letter-spacing: -0.7px;
}


.auth-header p {
  margin: 0;

  color: #8a91a5;

  font-size: 13px;

  line-height: 1.5;
}


/* =========================================================
   FORM
========================================================= */

.form-group {
  margin-bottom: 16px;
}


.form-group label {
  display: block;

  margin-bottom: 8px;

  color: #182238;

  font-size: 12px;

  font-weight: 600;
}


.password-label {
  display: flex;

  align-items: center;
  justify-content: space-between;
}


.password-label label {
  margin-bottom: 8px;
}


.forgot-password {
  padding: 0;

  border: 0;

  background: transparent;

  color: #6354e9;

  font-size: 11px;

  font-weight: 600;

  cursor: pointer;
}


/* =========================================================
   INPUT
========================================================= */

.input-wrapper {
  position: relative;

  display: flex;
  align-items: center;

  height: 46px;

  border: 1px solid #dfe2ea;

  border-radius: 8px;

  background: white;

  transition: 0.2s ease;
}


.input-wrapper:focus-within {
  border-color: #6b5bea;

  box-shadow:
    0 0 0 3px rgba(107, 91, 234, 0.10);
}


.input-wrapper input {
  width: 100%;
  height: 100%;

  padding:
    0
    65px
    0
    38px;

  border: 0;

  outline: 0;

  background: transparent;

  color: #172033;

  font-size: 13px;
}


.input-wrapper input::placeholder {
  color: #b4bac8;
}


.input-icon {
  position: absolute;

  left: 13px;

  color: #aeb5c5;

  font-size: 12px;

  pointer-events: none;
}


/* =========================================================
   PASSWORD
========================================================= */

.show-password {
  position: absolute;

  right: 12px;

  padding: 0;

  border: 0;

  background: transparent;

  color: #6354e9;

  font-size: 11px;

  font-weight: 600;

  cursor: pointer;
}


/* =========================================================
   REMEMBER / TERMS
========================================================= */

.remember-row,
.terms-row {
  margin: 4px 0 17px;
}


.remember-label {
  display: flex !important;

  align-items: center;

  gap: 7px;

  margin: 0 !important;

  color: #8a91a5 !important;

  font-size: 11px !important;

  font-weight: 400 !important;

  cursor: pointer;
}


.remember-label input {
  width: 13px;
  height: 13px;

  margin: 0;

  accent-color: #6757e9;

  cursor: pointer;
}


/* =========================================================
   PRIMARY BUTTON
========================================================= */

.primary-button {
  width: 100%;
  height: 46px;

  display: flex;

  align-items: center;
  justify-content: center;

  gap: 10px;

  border: 0;

  border-radius: 8px;

  background: #6655e9;

  color: white;

  font-size: 12px;

  font-weight: 700;

  cursor: pointer;

  box-shadow:
    0 8px 20px rgba(102, 85, 233, 0.20);

  transition:
    transform 0.2s ease,
    box-shadow 0.2s ease,
    background 0.2s ease;
}


.primary-button:hover {
  background: #5847dc;

  transform: translateY(-1px);

  box-shadow:
    0 11px 25px rgba(102, 85, 233, 0.27);
}


.primary-button:active {
  transform: translateY(0);
}


.arrow {
  font-size: 17px;
}


/* =========================================================
   DIVIDER
========================================================= */

.divider {
  display: flex;

  align-items: center;

  gap: 10px;

  margin: 21px 0;
}


.divider span {
  flex: 1;

  height: 1px;

  background: #e7e8ee;
}


.divider p {
  margin: 0;

  color: #a4a9b7;

  font-size: 10px;

  white-space: nowrap;
}


/* =========================================================
   SOCIAL
========================================================= */

.social-buttons {
  display: grid;

  grid-template-columns: 1fr 1fr;

  gap: 10px;
}


.social-button {
  height: 42px;

  display: flex;

  align-items: center;
  justify-content: center;

  gap: 8px;

  border: 1px solid #e0e2e9;

  border-radius: 8px;

  background: white;

  color: #1c2538;

  font-size: 11px;

  font-weight: 600;

  cursor: pointer;

  transition: 0.2s ease;
}


.social-button:hover {
  background: #f8f8fb;

  border-color: #d2d4df;
}


.google-icon {
  color: #4285f4;

  font-size: 15px;

  font-weight: 800;
}


.apple-icon {
  color: #111;

  font-size: 10px;
}


/* =========================================================
   SWITCH
========================================================= */

.switch-auth {
  width: 100%;

  display: flex;

  align-items: center;
  justify-content: center;

  gap: 4px;

  margin-top: 18px;

  color: #969cab;

  font-size: 11px;

  text-align: center;
}


.switch-auth button {
  padding: 0;

  border: 0;

  background: transparent;

  color: #6354e9;

  font-size: 11px;

  font-weight: 700;

  cursor: pointer;
}


.switch-auth button:hover {
  text-decoration: underline;
}


/*
  Slightly more room specifically for
  the register switch.
*/

.register-switch {
  margin-top: 15px;
}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 950px) {

  .brand-side {
    width: 44%;
  }

  .auth-side {
    width: 56%;

    padding: 30px;
  }

  .brand-content {
    width: 78%;
  }

  .brand-content h1 {
    font-size: 42px;
  }

  .brand-description {
    font-size: 14px;
  }

  .card-face {
    padding: 34px;
  }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 700px) {

  .auth-page {
    display: block;

    min-height: 100vh;

    overflow-y: auto;

    background: #f5f6fb;
  }


  /* =====================================================
     COMPACT PURPLE MOBILE HEADER
  ====================================================== */

  .brand-side {
    width: 100%;

    height: 190px;
    min-height: 190px;

    padding: 30px 25px;

    display: block;
  }


  .brand-content {
    width: 100%;

    margin: 0;

    display: flex;

    align-items: center;

    gap: 15px;
  }


  .brand-icon {
    width: 48px;
    height: 48px;

    flex-shrink: 0;

    margin: 0;

    border-radius: 12px;

    font-size: 25px;
  }


  .brand-text {
    min-width: 0;
  }


  .brand-content h1 {
    margin: 0;

    font-size: 29px;

    letter-spacing: -1px;
  }


  .brand-description {
    margin: 7px 0 0;

    font-size: 12px;

    line-height: 1.45;
  }


  /* Hide desktop feature boxes */
  .feature-list {
    display: none;
  }


  .copyright {
    display: none;
  }


  /* =====================================================
     MOBILE CIRCLES
  ====================================================== */

  .circle-top {
    width: 330px;
    height: 330px;

    top: -240px;
    right: -120px;
  }


  .circle-bottom {
    width: 300px;
    height: 300px;

    bottom: -240px;
    left: -180px;
  }


  .circle-middle {
    display: none;
  }


  /* =====================================================
     AUTH CARD
  ====================================================== */

  .auth-side {
    width: 100%;

    min-height: auto;

    padding: 0 14px 30px;

    display: block;

    position: relative;

    margin-top: -45px;

    z-index: 5;
  }


  .flip-container {
    width: 100%;

    height: 650px;

    perspective: 1400px;
  }


  .flip-card {
    width: 100%;

    height: 100%;
  }


  .card-face {
    width: 100%;
    height: 100%;

    padding: 29px 22px;

    border-radius: 18px;

    box-shadow:
      0 15px 45px rgba(30, 25, 80, 0.10);
  }


  .auth-header {
    margin-bottom: 25px;
  }


  .auth-header h2 {
    font-size: 24px;

    letter-spacing: -0.5px;
  }


  .auth-header p {
    font-size: 12px;
  }


  .form-group {
    margin-bottom: 15px;
  }


  .input-wrapper {
    height: 46px;
  }


  .primary-button {
    height: 46px;
  }


  .divider {
    margin: 20px 0;
  }


  .switch-auth {
    margin-top: 17px;

    flex-wrap: wrap;
  }

}


/* =========================================================
   SMALL MOBILE
========================================================= */

@media (max-width: 380px) {

  .brand-side {
    height: 175px;
    min-height: 175px;

    padding: 25px 20px;
  }


  .brand-icon {
    width: 43px;
    height: 43px;

    font-size: 22px;
  }


  .brand-content h1 {
    font-size: 26px;
  }


  .brand-description {
    font-size: 11px;
  }


  .auth-side {
    padding:
      0
      10px
      25px;

    margin-top: -35px;
  }


  .flip-container {
    height: 660px;
  }


  .card-face {
    padding: 25px 18px;
  }


  .auth-header h2 {
    font-size: 22px;
  }


  .social-buttons {
    grid-template-columns: 1fr 1fr;
  }


  .switch-auth {
    font-size: 10px;
  }


  .switch-auth button {
    font-size: 10px;
  }

}

</style>