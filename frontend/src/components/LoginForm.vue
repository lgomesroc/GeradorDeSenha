<template>
    <div class="login-form">
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
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        username: '',
        password: '',
        error: ''
      };
    },
    methods: {
        async login() {
            try {
                this.error = '';
                const response = await axios.post('http://localhost:9000/login', { // Certifique-se de que essa rota está correta
                username: this.username,
                password: this.password
                });
                const token = response.data.token;
                localStorage.setItem('token', token); // Armazena o token no localStorage para autenticação futura
                alert('Login realizado com sucesso!');
            } catch (err) {
                this.error = err.response?.data?.error || 'Erro ao fazer login.';
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
  </style>
  