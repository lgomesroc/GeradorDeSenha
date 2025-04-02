<template>
    <div class="register-form">
      <h2>Registrar Novo Usuário</h2>
      <form @submit.prevent="register">
        <div>
          <label for="name">Nome:</label>
          <input v-model="name" id="name" type="text" placeholder="Digite seu nome" />
        </div>
        <div>
          <label for="email">Email:</label>
          <input v-model="email" id="email" type="email" placeholder="Digite seu email" />
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
        name: '',
        email: '',
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
            name: this.name,
            email: this.email,
            password: this.password
          });
          this.success = response.data.message;
          this.name = '';
          this.email = '';
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
  