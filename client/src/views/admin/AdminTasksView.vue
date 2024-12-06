<template>
  <div>
    <h2>Все задачи компании</h2>
    <el-table :data="tasks" style="width: 100%">
      <!-- Название задачи -->
      <el-table-column prop="name" label="Название задачи" />

      <!-- Описание задачи -->
      <el-table-column prop="description" label="Описание" />

      <!-- Дата начала -->
      <el-table-column prop="start_date" label="Дата начала" />

      <!-- Дата завершения -->
      <el-table-column prop="end_date" label="Дата завершения" />

      <!-- Приоритет -->
      <el-table-column prop="priority" label="Приоритет" />

      <!-- Проект -->
      <el-table-column prop="project.name" label="Проект" />

      <!-- Главный -->
      <el-table-column prop="leader.name" label="Главный" />

      <!-- Участники -->
      <el-table-column label="Список участников">
        <template #default="scope">
          <ul>
            <li v-for="participant in scope.row.users" :key="participant.id">
              {{ participant.name }}
            </li>
          </ul>
        </template>
      </el-table-column>

      <!-- Прикрепленные файлы -->
      <el-table-column label="Файлы">
        <template #default="scope">
          <ul>
            <li v-for="file in scope.row.files" :key="file.id">
              <a href="#" @click.prevent="downloadItem(file)">{{ file.name }}</a>
            </li>
          </ul>
        </template>
      </el-table-column>

      <!-- Действия -->
      <el-table-column label="Действия">
        <template #default="scope">
          <el-button size="mini" @click="viewTask(scope.row.id)">Просмотр</el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Массив задач
const tasks = ref([]);

// Функция для загрузки всех задач
const fetchTasks = async () => {
  try {
    const response = await axios.get('http://localhost/api/tasks', {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
    });
    tasks.value = response.data;
  } catch (error) {
    console.error('Ошибка загрузки задач:', error.response?.data || error.message);
  }
};

// Функция для скачивания файлов
const downloadItem = async ({ url, name }) => {
  try {
    const response = await axios.get(url, {
      responseType: 'blob',
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
    });
    const blob = new Blob([response.data], { type: response.headers['content-type'] });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = name;
    link.click();
    URL.revokeObjectURL(link.href);
  } catch (error) {
    console.error('Ошибка при скачивании файла:', error.response?.data || error.message);
  }
};

// Логика для просмотра задачи (заглушка)
const viewTask = (taskId) => {
  console.log(`Открыть задачу с ID: ${taskId}`);
};

onMounted(fetchTasks);
</script>

<style scoped>
/* Добавьте стили, если нужно */
</style>
