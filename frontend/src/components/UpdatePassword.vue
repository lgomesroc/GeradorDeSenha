<template>
    <div class="update-password">
      <h2>Alterar Senha</h2>
      <form @submit.prevent="updatePassword">
        <div>
          <label for="current-password">Senha Atual:</label>
          <input v-model="currentPassword" id="current-password" type="password" placeholder="Digite sua senha atual" />
        </div>
        <div>
          <label for="new-password">Nova Senha:</label>
          <input v-model="newPassword" id="new-password" type="password" placeholder="Digite a nova senha" />
        </div>
        <p v-if="error" class="error">{{ error }}</p>
        <p v-if="success" class="success">{{ success }}</p>
        <button type="submit">Atualizar Senha</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        currentPassword: '',
        newPassword: '',
        error: '',
        success: ''
      };
    },
    methods: {
      async updatePassword() {
        try {
          this.error = '';
          const token = localStorage.getItem('token');
          if (!token) throw new Error('Usuário não autenticado.');
  
          const response = await axios.put('http://localhost:9000/update-password', {
            currentPassword: this.currentPassword,
            newPassword: this.newPassword
          }, {
            headers: {
              Authorization: `Bearer ${token}`
            }
          });
  
          this.success = response.data.message;
          this.currentPassword = '';
          this.newPassword = '';
        } catch (err) {
          this.error = err.response?.data?.error || 'Erro ao atualizar a senha.';
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
  