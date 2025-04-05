<template>
  <div class="recover-password-page" @contextmenu.prevent>
    <div class="recover-password-form">
      <h2>Recuperação de Senha</h2>
      <form @submit.prevent="recoverPassword" autocomplete="off">
        <div>
          <label for="email">E-mail:</label>
          <input 
            v-model="email" 
            id="email" 
            type="email" 
            placeholder="Digite seu e-mail de recuperação" 
            autocomplete="off" 
            @copy.prevent 
            @paste.prevent 
            @cut.prevent 
          />
        </div>
        <p v-if="error" class="error">{{ error }}</p>
        <p v-if="success" class="success">{{ success }}</p>
        <button type="submit">Enviar</button>

        <!-- Alternância de Tema -->
        <div class="theme-switcher">
          <label for="theme">Modo:</label>
          <select id="theme" v-model="theme" @change="toggleTheme">
            <option value="light">Claro</option>
            <option value="dark">Escuro</option>
          </select>
        </div>

        <!-- Link para voltar ao login -->
        <p class="back-link">
          <router-link to="/login">Voltar para Login</router-link>
        </p>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      email: '', // Campo para armazenar o e-mail inserido pelo usuário
      error: '', // Mensagens de erro
      success: '', // Mensagens de sucesso
      theme: 'light', // Tema inicial padrão
      logoutTimer: null, // Timer para logout automático
    };
  },
  methods: {
    async recoverPassword() {
      try {
        if (!this.email) {
          this.error = 'O e-mail é obrigatório.';
          return;
        }
        this.error = '';
        const response = await axios.post('http://localhost:9000/recover-password', {
          email: this.email,
        });
        this.success = response.data.message || 'E-mail de recuperação enviado!';
        this.email = ''; // Limpa o campo de e-mail
      } catch (err) {
        this.error = err.response?.data?.error || 'Erro ao enviar o e-mail de recuperação.';
        this.success = '';
      }
    },
    toggleTheme() {
      document.body.style.backgroundColor =
        this.theme === 'dark' ? '#121212' : '#FFFFFF';
      document.body.style.color =
        this.theme === 'dark' ? '#FFFFFF' : '#000000';

      localStorage.setItem('theme', this.theme); // Persistir o tema
    },
    startLogoutTimer() {
      // Cancela qualquer timer ativo
      clearTimeout(this.logoutTimer);
      // Configura o timer para 30 minutos
      this.logoutTimer = setTimeout(() => {
        this.logout('Inatividade por 30 minutos');
      }, 30 * 60 * 1000); // 30 minutos em milissegundos
    },
    resetLogoutTimer() {
      // Reinicia o timer ao detectar atividade do usuário
      this.startLogoutTimer();
    },
    logout(reason) {
      alert(`Você foi deslogado devido a: ${reason}`);
      this.$router.push('/login'); // Redireciona para a tela de login
    },
  },
  mounted() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
      this.theme = savedTheme; // Aplica o tema salvo
      this.toggleTheme();
    }

    // Configura os eventos para resetar o timer de logout
    document.addEventListener('mousemove', this.resetLogoutTimer);
    document.addEventListener('keydown', this.resetLogoutTimer);

    // Inicia o timer de logout
    this.startLogoutTimer();

    // Previne o uso do botão direito do mouse
    document.addEventListener('contextmenu', (event) => event.preventDefault());
  },
  beforeUnmount() {
    // Cancela o timer ao sair do componente
    clearTimeout(this.logoutTimer);

    // Remove os eventos adicionados
    document.removeEventListener('mousemove', this.resetLogoutTimer);
    document.removeEventListener('keydown', this.resetLogoutTimer);
    document.removeEventListener('contextmenu', (event) => event.preventDefault());
  },
};
</script>

<style>
.error {
  color: red;
  font-size: 0.9em;
  margin-top: 10px;
}
.success {
  color: green;
  font-size: 0.9em;
  margin-top: 10px;
}
.recover-password-page {
  max-width: 400px;
  margin: 0 auto;
  padding: 20px;
  text-align: center;
}
.recover-password-form {
  background-color: var(--background-color, #fff);
  color: var(--text-color, #000);
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
.theme-switcher {
  margin-top: 20px;
}
.theme-switcher select {
  padding: 5px;
  border-radius: 4px;
}
.back-link a {
  color: #3498db;
  text-decoration: none;
}
.back-link a:hover {
  text-decoration: underline;
}
</style>
