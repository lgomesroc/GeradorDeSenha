<template>
  <div class="delete-user" @contextmenu.prevent>
    <h2>Deletar Usuário</h2>
    <p>Tem certeza de que deseja deletar sua conta e todas as senhas associadas? Esta ação não pode ser desfeita.</p>
    <p v-if="error" class="error">{{ error }}</p>
    <p v-if="success" class="success">{{ success }}</p>
    <button @click="deleteUser">Confirmar</button>
    <button @click="cancel" class="cancel">Cancelar</button>

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
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      error: '',
      success: '',
      theme: 'light', // Tema inicial padrão
    };
  },
  methods: {
    async deleteUser() {
      try {
        this.error = '';
        const token = localStorage.getItem('token');
        if (!token) throw new Error('Usuário não autenticado.');

        const response = await axios.delete('http://localhost:9000/delete-user', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        this.success = response.data.message;
        localStorage.removeItem('token');
        alert('Conta deletada com sucesso!');
        window.location.reload();
      } catch (err) {
        this.error = err.response?.data?.error || 'Erro ao deletar o usuário.';
        this.success = '';
      }
    },
    cancel() {
      this.error = '';
      this.success = '';
      alert('Ação cancelada.');
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

    // Desativa o botão direito na página
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
.cancel {
  background-color: gray;
  color: white;
  margin-left: 10px;
  padding: 5px 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
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
