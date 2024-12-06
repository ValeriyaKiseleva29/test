<template>
  <div v-if="!task">
    <h1>Загрузка задачи...</h1>
  </div>
  <div v-else>
    <h2>Задача: {{ task.name }}</h2>
    <p><strong>Описание:</strong> {{ task.description }}</p>
    <p><strong>Дата начала:</strong> {{ task.start_date }}</p>
    <p><strong>Дата завершения:</strong> {{ task.end_date }}</p>
    <p><strong>Приоритет:</strong> {{ task.priority }}</p>
    <h3>Файлы</h3>
    <ul>
      <li v-for="file in task.files" :key="file.id">
        <a href="#" @click.prevent="downloadFile(file)">{{ file.name }}</a>
      </li>
    </ul>
    <h3>Участники</h3>
    <ul>
      <li v-for="user in task.users" :key="user.id">
        {{ user.name }}
      </li>
    </ul>
    <p><strong>Проект:</strong> {{ task.project?.name || 'Не указан' }}</p>
    <p><strong>Главный:</strong> {{ task.leader?.name || 'Не указан' }}</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const task = ref(null);

onMounted(async () => {
  try {
    const response = await axios.get(`http://localhost/api/tasks/${route.params.id}`, {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
    });
    task.value = response.data;
  } catch (error) {
    console.error('Ошибка загрузки задачи:', error.response?.data || error.message);
  }
});

const downloadFile = async ({ url, name }) => {
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
</script>
