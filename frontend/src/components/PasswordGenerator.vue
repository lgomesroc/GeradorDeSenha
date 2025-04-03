<template>
  <div class="dashboard">
    <h2>Dashboard - Gerador de Senhas</h2>
    <button @click="generatePassword">Gerar Senha</button>
    <ul v-if="passwords.length > 0">
      <li v-for="password in passwords" :key="password.id">
        {{ password }}
      </li>
    </ul>
    <p v-else>Nenhuma senha gerada até agora.</p>
    <p v-if="error" class="error">{{ error }}</p>
    <button @click="logout" class="logout-btn">Sair</button>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      passwords: [],
      error: '',
      logoutTimer: null
    };
  },
  methods: {
    async validateToken() {
      try {
        const token = localStorage.getItem('token');
        if (!token) throw new Error('Usuário não autenticado.');

        await axios.get('http://localhost:9001/validate-token', { // Porta ajustada para 9001
          headers: {
            Authorization: `Bearer ${token}`
          }
        });
      } catch (err) {
        this.error = err.response?.data?.error || 'Erro ao validar o token.';
        this.logout();
      }
    },
    async generatePassword() {
      try {
        this.error = '';
        const token = localStorage.getItem('token');
        if (!token) throw new Error('Usuário não autenticado.');

        const response = await axios.post('http://localhost:9001/generate-password', {}, { // Porta ajustada para 9001
          headers: {
            Authorization: `Bearer ${token}`
          }
        });

        this.passwords.push(response.data.password);
      } catch (err) {
        this.error = err.response?.data?.error || 'Erro ao gerar senha.';
      }
    },
    logout() {
      localStorage.removeItem('token');
      alert('Você foi deslogado.');
      this.$router.push('/login');
    },
    startLogoutTimer() {
      this.logoutTimer = setTimeout(() => {
        this.logout();
      }, 30 * 60 * 1000);
    },
    resetLogoutTimer() {
      clearTimeout(this.logoutTimer);
      this.startLogoutTimer();
    }
  },
  async created() {
    try {
      await this.validateToken();

      const token = localStorage.getItem('token');
      if (token) {
        const response = await axios.get('http://localhost:9001/list-passwords', { // Porta ajustada para 9001
          headers: {
            Authorization: `Bearer ${token}`
          }
        });
        this.passwords = response.data.passwords || [];
      }
    } catch (err) {
      this.error = err.response?.data?.error || 'Erro ao carregar o dashboard.';
    }
    this.startLogoutTimer();
  },
  mounted() {
    document.addEventListener('mousemove', this.resetLogoutTimer);
    document.addEventListener('keydown', this.resetLogoutTimer);
  },
  beforeUnmount() {
    clearTimeout(this.logoutTimer);
    document.removeEventListener('mousemove', this.resetLogoutTimer);
    document.removeEventListener('keydown', this.resetLogoutTimer);
  }
};
</script>

<style>
.error {
  color: red;
  font-size: 0.9em;
}
.logout-btn {
  margin-top: 20px;
  background-color: #e74c3c;
  color: white;
  padding: 10px 15px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
.logout-btn:hover {
  background-color: #c0392b;
}
</style>
