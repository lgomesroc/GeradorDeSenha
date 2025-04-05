<template>
  <div class="dashboard" @contextmenu.prevent>
    <h2>Dashboard - Gerador de Senhas</h2>
    <button @click="generatePassword">Gerar Senha</button>
    <ul v-if="passwords.length > 0" class="password-list" @contextmenu.prevent>
      <li 
        v-for="password in passwords" 
        :key="password.id" 
        class="password-item" 
        @mousedown.prevent
      >
        {{ password }}
      </li>
    </ul>
    <p v-else>Nenhuma senha gerada até agora.</p>
    <p v-if="error" class="error">{{ error }}</p>
    <button @click="logout" class="logout-btn">Sair</button>

    <!-- Tema Claro/Escuro -->
    <div class="theme-switcher">
      <label for="theme">Modo:</label>
      <select id="theme" v-model="theme" @change="toggleTheme">
        <option value="light">Claro</option>
        <option value="dark">Escuro</option>
      </select>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      passwords: [],
      error: '',
      logoutTimer: null, // Timer de logout automático
      theme: 'light', // Tema inicial padrão
    };
  },
  methods: {
    async validateToken() {
      try {
        const token = localStorage.getItem('token');
        if (!token) throw new Error('Usuário não autenticado.');

        const response = await axios.get('http://localhost:9000/validate-token', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.data.message !== 'Token is valid!') {
          throw new Error('Token inválido ou expirado.');
        }
      } catch (err) {
        this.error = err.response?.data?.error || 'Erro ao validar o token.';
        this.logout('Token inválido ou expirado');
      }
    },
    async generatePassword() {
      try {
        this.error = '';
        const token = localStorage.getItem('token');
        if (!token) throw new Error('Usuário não autenticado.');

        const response = await axios.get('http://localhost:9000/generate-password', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        this.passwords.push(response.data.password);
      } catch (err) {
        this.error = err.response?.data?.error || 'Erro ao gerar senha.';
      }
    },
    logout(reason = 'Desconhecido') {
      localStorage.removeItem('token');
      alert(`Você foi deslogado. Motivo: ${reason}`);
      this.$router.push('/login');
    },
    startLogoutTimer() {
      clearTimeout(this.logoutTimer); // Cancela qualquer timer anterior

      // Tempo ajustado para garantir 30 minutos exatos
      this.logoutTimer = setTimeout(() => {
        this.logout('Inatividade por 30 minutos');
      }, 30 * 60 * 1000); // 30 minutos em milissegundos
    },
    resetLogoutTimer() {
      clearTimeout(this.logoutTimer); // Reinicia o timer ao detectar atividade
      this.startLogoutTimer();
    },
    toggleTheme() {
      document.body.style.backgroundColor =
        this.theme === 'dark' ? '#121212' : '#FFFFFF';
      document.body.style.color =
        this.theme === 'dark' ? '#FFFFFF' : '#000000';

      localStorage.setItem('theme', this.theme); // Persistir o tema
    },
  },
  async created() {
    try {
      await this.validateToken(); // Valida o token ao carregar o componente

      const token = localStorage.getItem('token');
      if (token) {
        const response = await axios.get('http://localhost:9000/list-passwords', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        this.passwords = response.data.passwords || [];
      }
    } catch (err) {
      this.error = err.response?.data?.error || 'Erro ao carregar o dashboard.';
    }
    this.startLogoutTimer(); // Inicia o timer de logout automático

    // Recupera o tema do localStorage ao carregar o componente
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
      this.theme = savedTheme;
      this.toggleTheme();
    }
  },
  mounted() {
    document.addEventListener('mousemove', this.resetLogoutTimer); // Reinicia o timer ao mover o mouse
    document.addEventListener('keydown', this.resetLogoutTimer); // Reinicia o timer ao pressionar teclas

    // Impede a seleção de texto na página
    document.addEventListener('selectstart', (event) => event.preventDefault());
  },
  beforeUnmount() {
    clearTimeout(this.logoutTimer);
    document.removeEventListener('mousemove', this.resetLogoutTimer);
    document.removeEventListener('keydown', this.resetLogoutTimer);

    // Remove o bloqueio de seleção de texto
    document.removeEventListener('selectstart', (event) => event.preventDefault());
  },
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
.password-list {
  list-style: none;
  padding: 0;
}
.password-item {
  user-select: none; /* Impede seleção de texto */
  cursor: default; /* Remove o cursor interativo */
}
.theme-switcher {
  margin-top: 20px;
}
.theme-switcher select {
  padding: 5px;
  border-radius: 4px;
}
</style>
