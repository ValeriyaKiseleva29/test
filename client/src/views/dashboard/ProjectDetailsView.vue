<template>
  <div>
    <h1 v-if="!project">Загрузка данных...</h1>
    <div v-else>
      <h2>Проект: {{ project.name }}</h2>
      <p><strong>Описание:</strong> {{ project.description }}</p>
      <p><strong>Дата начала:</strong> {{ project.start_date }}</p>
      <p><strong>Дата завершения:</strong> {{ project.end_date }}</p>
      <p><strong>Главный:</strong> {{ project.leader?.name || 'Не указан' }}</p>
      <h3>Добавленные файлы</h3>
      <ul>
        <li v-for="file in project.files" :key="file.id">
          <a href="#" @click.prevent="downloadItem(file)">{{ file.name }}</a>
        </li>
      </ul>
      <h3>Список участников</h3>
      <ul>
        <li v-for="participant in project.participants" :key="participant.id">
          {{ participant.name }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const project = ref(null);

onMounted(async () => {
  try {

    const response = await axios.get(`http://localhost/api/projects/${route.params.id}`, {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
    });
    console.log('Данные проекта:', response.data); // Лог данных для отладки
    project.value = response.data;
  } catch (error) {
    console.error('Ошибка загрузки проекта:', error.response?.data || error.message);
  }
});

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
</script>
