<template>
    <div class="password-form">
      <h2>Salvar Senha</h2>
      <form @submit.prevent="validateAndSubmit">
        <div>
          <label for="password">Senha:</label>
          <input
            v-model="password"
            id="password"
            type="password"
            placeholder="Digite sua senha"
          />
          <p v-if="error" class="error">{{ error }}</p>
        </div>
        <button type="submit">Salvar</button>
        <p v-if="success" class="success">{{ success }}</p>
      </form>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  
  export default {
    data() {
      return {
        password: "",
        error: "",
        success: "",
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
          })
          .catch((err) => {
            this.error = err.response.data.error || "Erro inesperado.";
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
  