<template>
  <div>
    <h2>Все проекты компании</h2>
    <el-table :data="projects" style="width: 100%">
      <!-- Название проекта -->
      <el-table-column prop="name" label="Название проекта" />

      <!-- Описание -->
      <el-table-column prop="description" label="Описание" />

      <!-- Дата начала -->
      <el-table-column prop="start_date" label="Дата начала" />

      <!-- Дата завершения -->
      <el-table-column prop="end_date" label="Дата завершения" />

      <!-- Главный -->
      <el-table-column prop="leader.name" label="Главный" />

      <!-- Участники -->
      <el-table-column label="Участники">
        <template #default="scope">
          <ul>
            <li v-for="participant in scope.row.participants" :key="participant.id">
              {{ participant.name }}
            </li>
          </ul>
        </template>
      </el-table-column>

      <!-- Прикрепленные файлы -->
      <el-table-column label="Прикрепленные файлы">
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
          <el-button size="mini" @click="viewProject(scope.row.id)">Просмотр</el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Список проектов
const projects = ref([]);

// Загрузка всех проектов компании
const fetchProjects = async () => {
  try {
    const response = await axios.get('http://localhost/api/projects', {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
    });
    projects.value = response.data;
  } catch (error) {
    console.error('Ошибка загрузки проектов:', error.response?.data || error.message);
  }
};

// Логика для скачивания файла
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

// Логика для просмотра проекта (заглушка)
const viewProject = (projectId) => {
  console.log(`Открыть проект с ID: ${projectId}`);
};

onMounted(fetchProjects);
</script>

<style scoped>
/* Добавьте стили, если нужно */
</style>
