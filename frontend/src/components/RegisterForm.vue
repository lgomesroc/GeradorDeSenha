<template>
    <div class="register-form">
      <h2>Registrar</h2>
      <form @submit.prevent="register">
        <div>
          <label for="username">Usuário:</label>
          <input v-model="username" id="username" type="text" placeholder="Digite seu usuário" />
        </div>
        <div>
          <label for="password">Senha:</label>
          <input v-model="password" id="password" type="password" placeholder="Digite sua senha" />
        </div>
        <p v-if="error" class="error">{{ error }}</p>
        <p v-if="success" class="success">{{ success }}</p>
        <button type="submit">Registrar</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        username: '',
        password: '',
        error: '',
        success: ''
      };
    },
    methods: {
      async register() {
        try {
          this.error = '';
          const response = await axios.post('http://localhost:9000/register', {
            username: this.username,
            password: this.password
          });
          this.success = response.data.message;
          this.username = '';
          this.password = '';
        } catch (err) {
          this.error = err.response?.data?.error || 'Erro ao registrar.';
          this.success = '';
        }
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
  </style>
  