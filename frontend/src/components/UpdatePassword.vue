<template>
  <div class="update-password">
    <h2>Alterar Senha</h2>
    <form @submit.prevent="updatePassword">
      <div class="password-field">
        <label for="current-password">Senha Atual:</label>
        <input 
          v-model="currentPassword" 
          :type="showCurrentPassword ? 'text' : 'password'" 
          id="current-password" 
          placeholder="Digite sua senha atual" 
        />
        <button type="button" class="toggle-password" @click="toggleCurrentPasswordVisibility">
          {{ showCurrentPassword ? '🙈' : '👁️' }}
        </button>
      </div>
      <div class="password-field">
        <label for="new-password">Nova Senha:</label>
        <input 
          v-model="newPassword" 
          :type="showNewPassword ? 'text' : 'password'" 
          id="new-password" 
          placeholder="Digite a nova senha" 
        />
        <button type="button" class="toggle-password" @click="toggleNewPasswordVisibility">
          {{ showNewPassword ? '🙈' : '👁️' }}
        </button>
      </div>
      <p v-if="error" class="error">{{ error }}</p>
      <p v-if="success" class="success">{{ success }}</p>
      <button type="submit">Atualizar Senha</button>
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
      currentPassword: '',
      newPassword: '',
      showCurrentPassword: false, // Controla a visibilidade da senha atual
      showNewPassword: false, // Controla a visibilidade da nova senha
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

        const response = await axios.put('http://localhost:9001/update-password', {
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
      }
    },
    toggleCurrentPasswordVisibility() {
      this.showCurrentPassword = !this.showCurrentPassword; // Alterna visibilidade da senha atual
    },
    toggleNewPasswordVisibility() {
      this.showNewPassword = !this.showNewPassword; // Alterna visibilidade da nova senha
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
