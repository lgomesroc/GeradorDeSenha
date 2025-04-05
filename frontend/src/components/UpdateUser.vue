<template>
  <div class="update-user" @contextmenu.prevent>
    <h2>Atualizar Informações do Usuário</h2>
    <form @submit.prevent="updateUser" autocomplete="off">
      <div>
        <label for="name">Novo Nome:</label>
        <input 
          v-model="name" 
          id="name" 
          type="text" 
          placeholder="Digite seu novo nome" 
          autocomplete="off" 
          @copy.prevent 
          @paste.prevent 
          @cut.prevent 
        />
      </div>
      <div>
        <label for="email">Novo Email:</label>
        <input 
          v-model="email" 
          id="email" 
          type="email" 
          placeholder="Digite seu novo email" 
          autocomplete="off" 
          @copy.prevent 
          @paste.prevent 
          @cut.prevent 
        />
      </div>
      <p v-if="error" class="error">{{ error }}</p>
      <p v-if="success" class="success">{{ success }}</p>
      <button type="submit">Atualizar</button>
      
      <!-- Alternância de Tema -->
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
      name: '',
      email: '',
      error: '',
      success: '',
      theme: 'light', // Tema inicial padrão
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
          email: this.email,
        }, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        this.success = response.data.message;
        this.name = '';
        this.email = '';
      } catch (err) {
        this.error = err.response?.data?.error || 'Erro ao atualizar.';
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
    // Remove o listener de botão direito ao desmontar o componente
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
</style>
