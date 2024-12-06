<template>
  <div>
    <h2>Мои задачи</h2>
    <el-table :data="tasks" style="width: 100%">
      <el-table-column prop="name" label="Название задачи" />
      <el-table-column prop="description" label="Описание" />
      <el-table-column prop="start_date" label="Дата начала" />
      <el-table-column prop="end_date" label="Дата завершения" />
      <el-table-column prop="priority" label="Приоритет" />

      <el-table-column label="Список файлов">
        <template #default="scope">
          <ul>
            <li v-for="file in scope.row.files" :key="file.id">
              <a href="#" @click.prevent="downloadItem(file)">{{ file.name }}</a>
            </li>
          </ul>
        </template>
      </el-table-column>

      <!-- Проект -->
      <el-table-column prop="project.name" label="Проект" />

      <!-- Участники -->
      <el-table-column label="Участники">
        <template #default="scope">
          <ul>
            <li v-for="user in scope.row.users" :key="user.id">{{ user.name }}</li>
          </ul>
        </template>
      </el-table-column>
      <el-table-column prop="leader.name" label="Главный" />

      <!-- Действия -->
      <el-table-column label="Действия">
        <template #default="scope">
          <el-button
              type="primary"
              size="small"
              @click="goToTask(scope.row.id)"
          >
            Перейти
          </el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router'; // Импорт useRouter
import axios from 'axios';

const tasks = ref([]);
const router = useRouter(); // Инициализация маршрутизатора

onMounted(async () => {
  try {
    const response = await axios.get('http://localhost/api/tasks/mine', {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
    });
    tasks.value = response.data;
  } catch (error) {
    console.error('Ошибка загрузки задач:', error.response?.data || error.message);
  }
});

const goToTask = (taskId) => {
  if (!taskId) {
    console.error('ID задачи не найден');
    return;
  }
  router.push({ name: 'task-details', params: { id: taskId } }); // Перенаправление с использованием маршрутизатора
};

const downloadItem = async ({url, name}) => {
  try {
    const response = await axios.get(url, {
      responseType: 'blob',
      headers: {Authorization: `Bearer ${localStorage.getItem('token')}`},
    });
    const blob = new Blob([response.data], {type: response.headers['content-type']});
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

