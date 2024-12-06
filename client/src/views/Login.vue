<template>
  <div class="login-container">
    <el-form
        :model="form"
        :rules="rules"
        ref="loginForm"
        label-position="top"
        class="login-form"
    >
      <h1>Войти</h1>
      <el-form-item label="Email" prop="email">
        <el-input v-model="form.email" placeholder="Введите email" />
      </el-form-item>
      <el-form-item label="Пароль" prop="password">
        <el-input v-model="form.password" type="password" placeholder="Введите пароль" />
      </el-form-item>
      <el-form-item>
        <el-checkbox v-model="rememberMe">Запомнить меня</el-checkbox>
      </el-form-item>
      <el-button type="primary" @click="handleLogin">Войти</el-button>
      <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
    </el-form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();

// Данные формы
const form = ref({
  email: '',
  password: '',
});

const rememberMe = ref(false);

// Валидация формы
const rules = {
  email: [
    { required: true, message: 'Введите email', trigger: 'blur' },
    { type: 'email', message: 'Некорректный email', trigger: ['blur', 'change'] },
  ],
  password: [
    { required: true, message: 'Введите пароль', trigger: 'blur' },
    { min: 8, message: 'Пароль должен быть не менее 8 символов', trigger: 'blur' },
  ],
};

const errorMessage = ref('');
const loginForm = ref(null);

// Загрузка сохранённых данных "Запомнить меня"
onMounted(() => {
  const savedEmail = localStorage.getItem('rememberMeEmail');
  const savedPassword = localStorage.getItem('rememberMePassword');

  if (savedEmail && savedPassword) {
    form.value.email = savedEmail;
    form.value.password = savedPassword;
    rememberMe.value = true;
  }
});

// Логика логина
const handleLogin = async () => {
  loginForm.value.validate(async (valid) => {
    if (!valid) return;

    try {
      const response = await axios.post('http://localhost/api/login', {
        email: form.value.email,
        password: form.value.password,
      });

      const token = response.data.access_token;
      const role = response.data.role;

      localStorage.setItem('token', token);
      localStorage.setItem('userRole', role);

      if (rememberMe.value) {
        localStorage.setItem('rememberMeEmail', form.value.email);
        localStorage.setItem('rememberMePassword', form.value.password);
      } else {
        localStorage.removeItem('rememberMeEmail');
        localStorage.removeItem('rememberMePassword');
      }

      if (role === 'admin') {
        router.push('/admin-dashboard');
      } else {
        router.push('/dashboard');
      }
    } catch (error) {
      errorMessage.value = 'Неверный логин или пароль';
    }
  });
};
</script>

<style scoped>
.login-container {
  background: linear-gradient(to bottom right, #d9e4f5, #f4d9f5);
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

.login-form {
  max-width: 400px;
  width: 100%;
  padding: 20px;
  background-color: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

h1 {
  text-align: center;
  margin-bottom: 20px;
}

.error {
  color: red;
  text-align: center;
  margin-top: 10px;
}
</style>
