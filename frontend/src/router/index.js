import { createRouter, createWebHistory } from 'vue-router';
import LoginForm from '../components/LoginForm.vue';
import RegisterUser from '../components/RegisterUser.vue';
import UpdateUser from '../components/UpdateUser.vue';
import UpdatePassword from '../components/UpdatePassword.vue';
import DeleteUser from '../components/DeleteUser.vue';
import PasswordGenerator from '../components/PasswordGenerator.vue';
import RecoverPassword from '../components/RecoverPassword.vue';

const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', name: 'Login', component: LoginForm },
  { path: '/register', name: 'Register', component: RegisterUser },
  { 
    path: '/password-generator', 
    name: 'PasswordGenerator', 
    component: PasswordGenerator,
    meta: { requiresAuth: true } // Protege a rota com autenticação
  },
  { path: '/update-user', name: 'UpdateUser', component: UpdateUser },
  { path: '/update-password', name: 'UpdatePassword', component: UpdatePassword },
  { path: '/delete-user', name: 'DeleteUser', component: DeleteUser },
  { path: '/recover-password', component: RecoverPassword }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

// Middleware para proteger a rota
router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth) {
    const token = localStorage.getItem('token');
    if (!token) {
      alert('Você precisa estar logado para acessar o Dashboard!');
      return next('/login'); // Redireciona para login se não houver token
    }
  }
  next();
});

export default router;
