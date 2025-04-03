<template>
  <div class="register-form">
    <h2>Registrar Novo Usuário</h2>
    <form @submit.prevent="register">
      <div>
        <label for="username">Usuário:</label>
        <input v-model="username" id="username" type="text" placeholder="Digite seu nome de usuário" />
      </div>
      <div class="password-field">
        <label for="password">Senha:</label>
        <input 
          v-model="password" 
          :type="showPassword ? 'text' : 'password'" 
          id="password" 
          placeholder="Digite sua senha" 
        />
        <button type="button" class="toggle-password" @click="togglePasswordVisibility">
          {{ showPassword ? '🙈' : '👁️' }}
        </button>
      </div>
      <p v-if="error" class="error">{{ error }}</p>
      <p v-if="success" class="success">{{ success }}</p>
      <button type="submit">Registrar</button>
    </form>
    <p class="back-link">
      <router-link to="/login">Voltar para Login</router-link>
    </p>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      username: '',
      password: '',
      showPassword: false, // Controla a visibilidade da senha
      error: '',
      success: ''
    };
  },
  methods: {
    async register() {
      try {
        this.error = '';
        this.success = '';
        const response = await axios.post('http://localhost:9001/register', {
          username: this.username,
          password: this.password
        });
        this.success = response.data.message;
        this.username = '';
        this.password = '';
      } catch (err) {
        this.error = err.response?.data?.error || 'Erro ao registrar.';
      }
    },
    togglePasswordVisibility() {
      this.showPassword = !this.showPassword; // Alterna entre exibir/ocultar senha
    }
  }
};
</script>

<style>
.error {
  color: red;
  font-size: 0.9em;
}
.success {
  color: green;
  font-size: 0.9em;
}
.password-field {
  display: flex;
  align-items: center;
}
.password-field input {
  flex-grow: 1;
}
.toggle-password {
  margin-left: 10px;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1.2em;
}
.back-link a {
  color: #3498db;
  text-decoration: none;
}
.back-link a:hover {
  text-decoration: underline;
}
</style>
