<template>
  <el-aside width="200px" class="sidebar">
    <el-menu :default-active="activeMenu" class="el-menu-vertical">
      <el-menu-item index="dashboard">
        <router-link v-if="isAdmin" to="/admin-dashboard">Главная</router-link>
        <router-link v-else to="/dashboard">Главная</router-link>
      </el-menu-item>

      <!-- Общие маршруты для всех пользователей -->
      <template v-if="isAdmin">
        <!-- Маршруты для администратора -->
        <el-menu-item index="admin-projects">
          <router-link to="/admin-dashboard/projects">Все проекты</router-link>
        </el-menu-item>
        <el-menu-item index="admin-tasks">
          <router-link to="/admin-dashboard/tasks">Все задачи</router-link>
        </el-menu-item>
        <el-menu-item index="admin-workers">
          <router-link to="/admin-dashboard/workers">Список работников</router-link>
        </el-menu-item>
      </template>
      <template v-else>
        <!-- Маршруты для обычного пользователя -->
        <el-menu-item index="projects">
          <router-link to="/dashboard/projects">Мои проекты</router-link>
        </el-menu-item>
        <el-menu-item index="tasks">
          <router-link to="/dashboard/tasks">Мои задачи</router-link>
        </el-menu-item>
      </template>
      <!-- Кнопка выхода -->
      <el-menu-item index="logout" @click="confirmLogout">
        Выйти
      </el-menu-item>
    </el-menu>
  </el-aside>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'; // Добавлен импорт onMounted
import { useRouter } from 'vue-router';

const router = useRouter();
const activeMenu = ref('dashboard');

// Проверка роли пользователя (админ или обычный пользователь)
const isAdmin = computed(() => {
  const userRole = localStorage.getItem('userRole'); // Предполагается, что роль сохраняется в localStorage
  return userRole === 'admin';
});

// Восстановление данных "Запомнить меня"
const form = ref({
  email: '',
  password: '',
});
const rememberMe = ref(false);
onMounted(() => {
  const savedEmail = localStorage.getItem('rememberMeEmail');
  const savedPassword = localStorage.getItem('rememberMePassword');
  if (savedEmail && savedPassword) {
    form.value.email = savedEmail;
    form.value.password = savedPassword;
    rememberMe.value = true;
  }
});

// Функция выхода
const confirmLogout = () => {
  const confirmed = window.confirm('Вы действительно хотите выйти?');
  if (confirmed) {
    localStorage.removeItem('token');
    localStorage.removeItem('userRole'); // Удаляем сохранённую роль
    router.push('/login');
  }
};
</script>

<style scoped>
.sidebar {
  width: 200px;
  height: 100%;
  flex-shrink: 0;
  background-color: #f5f5f5;
  border-right: 1px solid #ddd;
}

.el-menu-vertical {
  height: 100%;
  border: none;
}

.el-menu-item {
  font-size: 16px;
}
</style>
