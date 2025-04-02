<template>
  <div class="login-page">
    <div v-if="!isAuthenticated" class="login-form">
      <h2>Login</h2>
      <form @submit.prevent="login">
        <div>
          <label for="username">Usuário:</label>
          <input v-model="username" id="username" type="text" placeholder="Digite seu usuário" />
        </div>
        <div>
          <label for="password">Senha:</label>
          <input v-model="password" id="password" type="password" placeholder="Digite sua senha" />
        </div>
        <p v-if="error" class="error">{{ error }}</p>
        <button type="submit">Entrar</button>
      </form>
    </div>
    
    <div v-else>
      <PasswordGenerator />
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import PasswordGenerator from './PasswordGenerator.vue';

export default {
  components: {
    PasswordGenerator
  },
  data() {
    return {
      username: '',
      password: '',
      error: '',
      isAuthenticated: false
    };
  },
  methods: {
    async login() {
      try {
        this.error = '';
        const response = await axios.post('http://localhost:9000/login', {
          username: this.username,
          password: this.password
        });
        const token = response.data.token;
        localStorage.setItem('token', token);
        this.isAuthenticated = true;
        alert('Login realizado com sucesso!');
      } catch (err) {
        this.error = err.response?.data?.error || 'Erro ao fazer login.';
      }
    }
  },
  mounted() {
    const token = localStorage.getItem('token');
    if (token) {
      this.isAuthenticated = true; // Verifica se o usuário já está autenticado
    }
  }
};
</script>

<style>
.error {
  color: red;
  font-size: 0.9em;
}
</style>
