<template>
  <div class="token-validation-form" @contextmenu.prevent>
    <h2>Validar Token</h2>
    <form @submit.prevent="validateToken" autocomplete="off">
      <div>
        <label for="token">Token:</label>
        <input
          v-model="token"
          id="token"
          type="text"
          placeholder="Digite o token"
          autocomplete="off"
          @copy.prevent
          @paste.prevent
          @cut.prevent
        />
        <p v-if="error" class="error">{{ error }}</p>
      </div>
      <button type="submit">Validar</button>
      <p v-if="success" class="success">{{ success }}</p>
    </form>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      token: "",
      error: "",
      success: "",
    };
  },
  methods: {
    async validateToken() {
      try {
        if (!this.token) {
          this.error = "O token é obrigatório.";
          return;
        }

        this.error = "";
        const response = await axios.get("http://localhost:9000/validate-token", {
          headers: {
            Authorization: `Bearer ${this.token}`,
          },
        });

        this.success = response.data.message;
        this.error = "";
      } catch (err) {
        this.error = err.response?.data?.error || "Erro inesperado.";
        this.success = "";
      }
    },
  },
  mounted() {
    // Desativa o botão direito globalmente nesta tela
    document.addEventListener("contextmenu", (event) => event.preventDefault());
  },
  beforeUnmount() {
    // Remove o listener para evitar conflitos
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
</style>
