<template>
  <div class="login-page">
    <div class="login-form">
      <h2>Login</h2>
      <form @submit.prevent="login">
        <div>
          <label for="username">Usuário:</label>
          <input v-model="username" id="username" type="text" placeholder="Digite seu usuário" />
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
        <button type="submit">Entrar</button>
      </form>
      <p class="register-link">
        Não tem uma conta? <router-link to="/register">Cadastre-se aqui</router-link>.
      </p>
      <p class="extra-links">
        <router-link to="/update-user">Atualizar Usuário</router-link> | 
        <router-link to="/update-password">Alterar Senha</router-link> | 
        <router-link to="/delete-user">Deletar Conta</router-link>
      </p>
    </div>
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
      error: ''
    };
  },
  methods: {
    async login() {
      try {
        this.error = '';
        const response = await axios.post('http://localhost:9001/login', {
          username: this.username,
          password: this.password
        });
        const token = response.data.token;
        localStorage.setItem('token', token); // Salva o token no localStorage
        alert('Login realizado com sucesso!');
        this.$router.push('/password-generator'); // Redireciona para o dashboard
      } catch (err) {
        this.error = err.response?.data?.error || 'Erro ao fazer login.';
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
.register-link,
.extra-links {
  margin-top: 20px;
  font-size: 0.9em;
}
.register-link a,
.extra-links a {
  color: #3498db;
  text-decoration: none;
}
.register-link a:hover,
.extra-links a:hover {
  text-decoration: underline;
}
</style>
