<template>
  <div>
    <h2>Список сотрудников компании</h2>
    <el-table :data="workers" style="width: 100%">
      <el-table-column prop="name" label="Имя" />
      <el-table-column prop="email" label="Email" />
      <el-table-column prop="role" label="Роль" />
      <el-table-column label="Действия">
        <template #default="scope">
          <el-button size="mini" @click="viewWorker(scope.row.id)">Просмотр</el-button>
          <el-button type="danger" size="mini" @click="deleteWorker(scope.row.id)">Удалить</el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const workers = ref([]);

const fetchWorkers = async () => {
  try {
    const response = await axios.get('http://localhost/api/workers', {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
    });
    workers.value = response.data;
  } catch (error) {
    console.error('Ошибка загрузки сотрудников:', error);
  }
};

const viewWorker = (workerId) => {
  console.log(`Открыть профиль пользователя с ID: ${workerId}`);
  // Логика для перехода на профиль пользователя
};

const deleteWorker = async (workerId) => {
  try {
    await axios.delete(`http://localhost/api/workers/${workerId}`, {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
    });
    workers.value = workers.value.filter((worker) => worker.id !== workerId);
    console.log('Сотрудник удалён');
  } catch (error) {
    console.error('Ошибка при удалении сотрудника:', error);
  }
};

onMounted(fetchWorkers);
</script>

<style scoped>
/* Добавьте стили, если нужно */
</style>
