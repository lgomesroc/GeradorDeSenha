<template>
  <div class="register-form" @contextmenu.prevent>
    <h2>Registrar Novo Usuário</h2>
    <form @submit.prevent="register" autocomplete="off">
      <div>
        <label for="username">Usuário:</label>
        <input 
          v-model="username" 
          id="username" 
          type="text" 
          placeholder="Digite seu nome de usuário" 
          autocomplete="off" 
          @copy.prevent 
          @paste.prevent 
          @cut.prevent 
        />
      </div>
      <div class="password-field" @contextmenu.prevent>
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
        />
        <button type="button" class="toggle-password" @click="togglePasswordVisibility">
          {{ showPassword ? '🙈' : '👁️' }}
        </button>
      </div>
      <p v-if="error" class="error">{{ error }}</p>
      <p v-if="success" class="success">{{ success }}</p>

      <!-- Linha adicionada para o aviso de sucesso -->
      <p v-if="successNotification" class="success-notification">Usuário cadastrado com sucesso!</p>

      <button type="submit">Registrar</button>
      
      <!-- Alternância entre Modo Claro/Escuro -->
      <div class="theme-switcher">
        <label for="theme">Modo:</label>
        <select id="theme" v-model="theme" @change="toggleTheme">
          <option value="light">Claro</option>
          <option value="dark">Escuro</option>
        </select>
      </div>
      
      <p class="back-link">
        <router-link to="/login">Voltar para Login</router-link>
      </p>
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
      showPassword: false, // Controla a visibilidade da senha
      error: '',
      success: '',
      successNotification: false, // Controle para exibir o aviso de sucesso
      theme: 'light', // Tema inicial padrão
    };
  },
  methods: {
    async register() {
      try {
        this.error = '';
        this.success = '';
        this.successNotification = false;

        // Verifica se os campos de usuário e senha foram preenchidos (bloquear salvar vazio)
        if (!this.username || !this.password) {
          this.error = 'Usuário e senha são obrigatórios.';
          return;
        }

        // Regras de validação no frontend (para avisar antes de enviar ao backend)
        const passwordError = this.validatePassword(this.password);
        if (passwordError) {
          this.error = passwordError; // Mostra o erro ao usuário
          return;
        }

        // Faz a solicitação ao backend para verificar se o usuário já existe
        const userCheck = await axios.post('http://localhost:9000/user-check', {
          username: this.username,
        });

        if (userCheck.data.exists) {
          this.error = 'Este usuário já está cadastrado.';
          return;
        }

        // Faz a solicitação ao backend para registrar o usuário
        const response = await axios.post('http://localhost:9000/register', {
          username: this.username,
          password: this.password,
        });

        this.success = response.data.message;
        this.successNotification = true; // Ativa a notificação de sucesso
        setTimeout(() => {
          this.successNotification = false; // Remove a notificação após alguns segundos
        }, 5000);

        this.username = '';
        this.password = '';
      } catch (err) {
        this.error = err.response?.data?.error || 'Erro ao registrar.';
      }
    },
    validatePassword(password) {
      if (password.length < 8) {
        return 'A senha deve ter no mínimo 8 caracteres.';
      }
      if (!/\d/.test(password)) {
        return 'A senha deve conter pelo menos 1 número.';
      }
      if (!/[a-z]/.test(password)) {
        return 'A senha deve conter pelo menos 1 letra minúscula.';
      }
      if (!/[A-Z]/.test(password)) {
        return 'A senha deve conter pelo menos 1 letra maiúscula.';
      }
      if (!/[\W_]/.test(password)) {
        return 'A senha deve conter pelo menos 1 caractere especial.';
      }
      if (/(\w)\1{2,}/.test(password)) {
        return 'A senha não pode ter 3 caracteres consecutivos iguais.';
      }
      return null;
    },
    togglePasswordVisibility() {
      this.showPassword = !this.showPassword; // Alterna entre exibir/ocultar senha
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
    // Recuperar tema do localStorage ao carregar o componente
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
      this.theme = savedTheme;
      this.toggleTheme();
    }

    // Desativa o botão direito globalmente nesta tela
    document.addEventListener('contextmenu', (event) => event.preventDefault());
  },
  beforeUnmount() {
    // Remove o listener para evitar conflitos
    document.removeEventListener('contextmenu', (event) => event.preventDefault());
  },
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
.password-field input::-webkit-input-placeholder {
  color: #aaa;
  font-style: italic;
}
.password-field input:-moz-placeholder {
  color: #aaa;
  font-style: italic;
}
.password-field input::placeholder {
  color: #aaa;
  font-style: italic;
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
.theme-switcher {
  margin-top: 20px;
}
.theme-switcher select {
  padding: 5px;
  border-radius: 4px;
}
.success-notification {
  color: white;
  background-color: green;
  padding: 10px;
  border-radius: 4px;
  text-align: center;
  margin: 10px 0;
}
</style>
