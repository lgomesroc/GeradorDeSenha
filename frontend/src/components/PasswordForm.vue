<template>
  <div class="password-form" @contextmenu.prevent>
    <h2>Salvar Senha</h2>
    <form @submit.prevent="validateAndSubmit" autocomplete="off">
      <div>
        <label for="password">Senha:</label>
        <input 
          v-model="password" 
          id="password" 
          :type="showPassword ? 'text' : 'password'" 
          placeholder="Digite sua senha" 
          autocomplete="off" 
          @copy.prevent 
          @paste.prevent 
          @cut.prevent 
        />
        <button type="button" class="toggle-password" @click="togglePasswordVisibility">
          {{ showPassword ? '🙈' : '👁️' }}
        </button>
        <p v-if="error" class="error">{{ error }}</p>
      </div>
      <button type="submit">Salvar</button>
      <p v-if="success" class="success">{{ success }}</p>

      <!-- Alternância de Tema -->
      <div class="theme-switcher">
        <label for="theme">Modo:</label>
        <select id="theme" v-model="theme" @change="toggleTheme">
          <option value="light">Claro</option>
          <option value="dark">Escuro</option>
        </select>
      </div>
    </form>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      password: "",
      showPassword: false, // Controla a visibilidade da senha
      error: "",
      success: "",
      theme: "light", // Tema inicial padrão
    };
  },
  methods: {
    validateAndSubmit() {
      // Validação
      if (this.password.length < 8) {
        this.error = "A senha deve conter pelo menos 8 caracteres.";
        return;
      }
      this.error = "";

      // Envio ao backend
      axios
        .post("http://localhost:9000/save-password", { password: this.password })
        .then((response) => {
          this.success = response.data.message;
          this.password = ""; // Limpa o campo após salvar
        })
        .catch((err) => {
          this.error = err.response?.data?.error || "Erro inesperado.";
        });
    },
    togglePasswordVisibility() {
      this.showPassword = !this.showPassword; // Alterna entre exibir/ocultar senha
    },
    toggleTheme() {
      document.body.style.backgroundColor =
        this.theme === "dark" ? "#121212" : "#FFFFFF";
      document.body.style.color =
        this.theme === "dark" ? "#FFFFFF" : "#000000";

      localStorage.setItem("theme", this.theme); // Persistir o tema
    },
  },
  mounted() {
    // Recuperar tema do localStorage ao carregar o componente
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme) {
      this.theme = savedTheme;
      this.toggleTheme();
    }

    // Desativa o botão direito globalmente nesta página
    document.addEventListener("contextmenu", (event) => event.preventDefault());
  },
  beforeUnmount() {
    // Remove o listener de botão direito ao desmontar o componente
    document.removeEventListener("contextmenu", (event) => event.preventDefault());
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
.toggle-password {
  margin-left: 10px;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1.2em;
}
.theme-switcher {
  margin-top: 20px;
}
.theme-switcher select {
  padding: 5px;
  border-radius: 4px;
}
</style>
