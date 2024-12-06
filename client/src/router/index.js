import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Dashboard from '../views/Dashboard.vue';
import ProjectsView from '../views/dashboard/ProjectsView.vue';
import TasksView from '../views/dashboard/TasksView.vue';

const AdminDashboard = () => import('../views/admin/AdminDashboard.vue');
const AdminProjectsView = () => import('../views/admin/AdminProjectsView.vue');
const AdminTasksView = () => import('../views/admin/AdminTasksView.vue');
const WorkersView = () => import('../views/admin/WorkersView.vue');

const routes = [
  {
    path: '/login',
    name: 'login',
    component: Login,
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: Dashboard,
    meta: { requiresAuth: true },
    children: [
      {
        path: 'projects',
        name: 'projects',
        component: ProjectsView,
      },
      {
        path: 'projects/:id',
        name: 'project-details',
        component: () => import('../views/dashboard/ProjectDetailsView.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'tasks',
        name: 'tasks',
        component: TasksView,
      },
      {
        path: '/tasks/:id',
        name: 'task-details',
        component: () => import('../views/dashboard/TaskDetailsView.vue'),
      }
    ],
  },
  {
    path: '/admin-dashboard',
    name: 'AdminDashboard',
    component: AdminDashboard,
    meta: { requiresAuth: true, isAdmin: true },
    children: [
      {
        path: 'projects',
        name: 'admin-projects',
        component: AdminProjectsView,
      },
      {
        path: 'tasks',
        name: 'admin-tasks',
        component: AdminTasksView,
      },
      {
        path: 'workers',
        name: 'admin-workers',
        component: WorkersView,
      },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');

  if (to.matched.some((record) => record.meta.requiresAuth) && !token) {
    return next({ name: 'login' });
  }

  if (to.name === 'login' && token) {
    return next({ name: 'dashboard' });
  }

  next();
});

export default router;
