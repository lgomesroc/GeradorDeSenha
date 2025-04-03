<template>
    <div class="token-validation-form">
      <h2>Validar Token</h2>
      <form @submit.prevent="validateToken">
        <div>
          <label for="token">Token:</label>
          <input
            v-model="token"
            id="token"
            type="text"
            placeholder="Digite o token"
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
      validateToken() {
        if (!this.token) {
          this.error = "O token é obrigatório.";
          return;
        }
        this.error = "";
  
        axios
          .get("http://localhost:9001/validate-token", {
            headers: {
              Authorization: `Bearer ${this.token}`,
            },
          })
          .then((response) => {
            this.success = response.data.message;
            this.error = "";
          })
          .catch((err) => {
            this.error = err.response.data.error || "Erro inesperado.";
            this.success = "";
          });
      },
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
  