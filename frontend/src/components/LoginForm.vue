<template>
  <div class="login-page" @contextmenu.prevent>
    <div class="login-form">
      <h2>Login</h2>
      <form @submit.prevent="login" autocomplete="off">
        <div>
          <label for="username">Usuário:</label>
          <input 
            v-model="username" 
            id="username" 
            type="text" 
            placeholder="Digite seu usuário" 
            autocomplete="off" 
            @copy.prevent 
            @paste.prevent 
            @cut.prevent 
            @selectstart.prevent 
          />
        </div>
        <div class="password-field">
          <label for="password">Senha:</label>
          <input 
            v-model="password" 
            :type="showPassword ? 'text' : 'password'" 
            id="password" 
            placeholder="Digite sua senha" 
            autocomplete="off" 
            @copy.prevent 
            @paste.prevent 
            @cut.prevent 
            @selectstart.prevent 
          />
          <button type="button" class="toggle-password" @click="togglePasswordVisibility">
            {{ showPassword ? '🙈' : '👁️' }}
          </button>
        </div>
        <p v-if="error" class="error">{{ error }}</p>
        <button type="submit" :disabled="isBlocked">Entrar</button>
        <p v-if="isBlocked" class="blocked-message">Usuário bloqueado por 1 hora.</p>
      </form>

      <!-- Tema Claro/Escuro -->
      <div class="theme-switcher">
        <label for="theme">Modo:</label>
        <select id="theme" v-model="theme" @change="toggleTheme">
          <option value="light">Claro</option>
          <option value="dark">Escuro</option>
        </select>
      </div>

      <!-- Links para as outras telas -->
      <p class="navigation-links">
        <router-link to="/register">Cadastrar Usuário</router-link> |
        <router-link to="/update-user">Atualizar Usuário</router-link> |
        <router-link to="/update-password">Atualizar Senha</router-link> |
        <router-link to="/delete-user">Deletar Usuário</router-link> |
        <router-link to="/recover-password">Esqueceu a Senha?</router-link>
      </p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      username: '',
      password: '',
      showPassword: false,
      error: '',
      isBlocked: false,
      attemptsLeft: 3,
      theme: 'light', // Tema inicial padrão
    };
  },
  methods: {
    async login() {
      if (this.isBlocked) {
        this.error = 'Usuário bloqueado. Aguarde o tempo de desbloqueio.';
        return;
      }

      try {
        this.error = '';

        // Previne que o navegador salve o usuário e a senha automaticamente
        if ('credentials' in navigator) {
          navigator.credentials.preventSilentAccess();
        }

        const response = await fetch('http://localhost:9000/login', {
          method: 'POST',
          body: JSON.stringify({
            username: this.username,
            password: this.password,
          }),
          headers: { 'Content-Type': 'application/json' },
        });

        const data = await response.json();
        if (!data.token) throw new Error('Token não gerado.');

        localStorage.setItem('token', data.token);
        alert('Login realizado com sucesso!');
        this.$router.push('/password-generator');
      } catch (err) {
        this.attemptsLeft--;

        // Adiciona verificação para evitar erro de mensagem genérica
        if (err.response && err.response.status === 401) {
          this.error = 'Usuário ou senha incorretos.';
        } else if (this.attemptsLeft > 0) {
          this.error = `Erro ao fazer login. Você tem mais ${this.attemptsLeft} tentativa(s).`;
        } else {
          this.error = 'Você excedeu o limite de tentativas. Usuário bloqueado por 1 hora.';
          this.isBlocked = true;

          setTimeout(() => {
            this.isBlocked = false;
            this.attemptsLeft = 3;
          }, 3600000);
        }
      }
    },
    togglePasswordVisibility() {
      this.showPassword = !this.showPassword;
    },
    toggleTheme() {
      document.body.style.backgroundColor =
        this.theme === 'dark' ? '#121212' : '#FFFFFF';
      document.body.style.color =
        this.theme === 'dark' ? '#FFFFFF' : '#000000';

      localStorage.setItem('theme', this.theme); // Persistir o tema
    },
  },
  mounted() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
      this.theme = savedTheme;
      this.toggleTheme();
    }
  },
};
</script>

<style>
.error {
  color: red;
}
.blocked-message {
  color: orange;
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
.navigation-links {
  margin-top: 20px;
  font-size: 0.9em;
}
.navigation-links a {
  color: #3498db;
  text-decoration: none;
}
.navigation-links a:hover {
  text-decoration: underline;
}
.theme-switcher {
  margin-top: 20px;
}
.theme-switcher select {
  padding: 5px;
  border-radius: 4px;
}
</style>
