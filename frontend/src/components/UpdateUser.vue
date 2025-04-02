<template>
    <div class="update-user">
      <h2>Atualizar Informações do Usuário</h2>
      <form @submit.prevent="updateUser">
        <div>
          <label for="name">Novo Nome:</label>
          <input v-model="name" id="name" type="text" placeholder="Digite seu novo nome" />
        </div>
        <div>
          <label for="email">Novo Email:</label>
          <input v-model="email" id="email" type="email" placeholder="Digite seu novo email" />
        </div>
        <p v-if="error" class="error">{{ error }}</p>
        <p v-if="success" class="success">{{ success }}</p>
        <button type="submit">Atualizar</button>
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
        error: '',
        success: ''
      };
    },
    methods: {
      async updateUser() {
        try {
          this.error = '';
          const token = localStorage.getItem('token');
          if (!token) throw new Error('Usuário não autenticado.');
  
          const response = await axios.put('http://localhost:9000/update-user', {
            name: this.name,
            email: this.email
          }, {
            headers: {
              Authorization: `Bearer ${token}`
            }
          });
  
          this.success = response.data.message;
          this.name = '';
          this.email = '';
        } catch (err) {
          this.error = err.response?.data?.error || 'Erro ao atualizar.';
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
  