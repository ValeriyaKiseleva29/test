<template>
  <el-container>
    <Sidebar />
    <el-container>
      <el-main>
        <div v-if="$route.path === '/dashboard'">
          <h1 class="welcome-text">Добро пожаловать!</h1>
          <el-calendar class="calendar" />
        </div>
        <router-view v-else />
      </el-main>
    </el-container>
  </el-container>
</template>

<script setup>
import Sidebar from '../components/Sidebar.vue';
</script>

<style scoped>
.el-container {
  display: flex;
  height: 100vh;
}

.el-main {
  flex: 1;
  padding: 20px;
  background: linear-gradient(to bottom right, #bbcfec, #7ca8ec);
  overflow-y: auto;
}

.welcome-text {
  font-size: 2.5rem;
  margin: 0 0 20px 20px;
  color: #ffffff;
}

.calendar {
  width: 600px;
  height: 600px;
  margin-left: 20px;
  background-color: #cec6f3;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  padding: 10px;
}
</style>
