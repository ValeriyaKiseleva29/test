<template>
  <el-container>
    <Sidebar />
    <el-container>
      <el-main>
        <div v-if="$route.path === '/admin-dashboard'">
          <h1 class="welcome-text">Добро пожаловать в админку!</h1>
          <el-calendar class="calendar" />
        </div>
        <router-view v-else />
      </el-main>
    </el-container>
  </el-container>
</template>

<script setup>
import Sidebar from '../../components/Sidebar.vue';

</script>

<style scoped>
.el-container {
  display: flex;
  height: 100vh;
}

.el-main {
  flex: 1;
  padding: 20px;
  background: linear-gradient(to bottom right, #f4d9f5, #d9e4f5);
  overflow-y: auto;
}

.welcome-text {
  font-size: 2.5rem;
  margin: 0 0 20px 20px;
  color: #333;
}

.calendar {
  width: 600px;
  height: 600px;
  margin-left: 20px;
  background-color: #fff0f5;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  padding: 10px;
}
</style>
