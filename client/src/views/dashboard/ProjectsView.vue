<template>
  <div>
    <h2>Мои проекты</h2>
    <!-- Проверка на наличие данных -->
    <el-table v-if="projects.length" :data="projects" style="width: 100%">
      <!-- Столбцы таблицы -->
      <el-table-column prop="name" label="Название проекта" />
      <el-table-column prop="description" label="Описание" />
      <el-table-column prop="start_date" label="Дата начала" />
      <el-table-column prop="end_date" label="Дата завершения" />
      <el-table-column prop="leader.name" label="Главный" />
      <el-table-column label="Добавленные файлы">
        <template #default="scope">
          <ul>
            <li v-for="file in scope.row.files" :key="file.id">
              <a href="#" @click.prevent="downloadItem(file)">{{ file.name }}</a>
            </li>
          </ul>
        </template>
      </el-table-column>

      <el-table-column label="Список участников">
        <template #default="scope">
          <ul>
            <li v-for="participant in scope.row.participants" :key="participant.id">
              {{ participant.name }}
            </li>
          </ul>
        </template>
      </el-table-column>

      <!-- Добавленный столбец для кнопки "Перейти" -->
      <el-table-column label="Действия">
        <template #default="scope">
          <el-button
              type="primary"
              size="small"
              @click="goToProject(scope.row.id)"
          >
            Перейти
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <p v-else>Загрузка данных или проектов нет.</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';


const projects = ref([]);


const router = useRouter();

onMounted(async () => {
  try {
    const response = await axios.get('http://localhost/api/projects/mine', {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
    });
    projects.value = response.data || []; // Убедитесь, что данные существуют
  } catch (error) {
    console.error('Ошибка загрузки проектов:', error.response?.data || error.message);
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

// Метод для перехода на страницу проекта
const goToProject = (projectId) => {

  if (!projectId) {
    console.error('ID проекта не найден');
    return;
  }
  router.push({ name: 'project-details', params: { id: projectId } });
};
</script>
