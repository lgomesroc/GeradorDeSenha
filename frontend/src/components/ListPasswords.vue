<template>
    <div class="passwords-list">
      <h2>Senhas Salvas</h2>
      <div v-if="loading">Buscando senhas salvas...</div>
      <div v-else-if="error" class="error">
        <p>{{ error }}</p>
        <button @click="fetchPasswords" class="retry-button">Tentar novamente</button>
      </div>
      <div v-else-if="passwords.length === 0" class="empty-message">
        Nenhuma senha encontrada.
      </div>
      <ul v-else class="password-items">
        <li v-for="password in passwords" :key="password.id" class="password-item">
          <div class="password-content">
            <span class="password-value">{{ password.password }}</span>
            <span class="password-date">{{ formatDate(password.created_at) }}</span>
          </div>
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: "ListPasswords",
    data() {
      return {
        passwords: [],
        loading: true,
        error: null
      };
    },
    mounted() {
      this.fetchPasswords();
      
      // Usar evento global para atualizar a lista quando uma nova senha for gerada
      this.$root.$on('password-generated', this.handlePasswordGenerated);
    },
    beforeUnmount() {
      this.$root.$off('password-generated', this.handlePasswordGenerated);
    },
    methods: {
      handlePasswordGenerated() {
        console.log("Evento de senha gerada recebido, atualizando lista...");
        this.fetchPasswords();
      },
      
      async fetchPasswords() {
        this.loading = true;
        this.error = null;
        
        try {
          console.log("Buscando senhas salvas...");
          
          const response = await axios.get('http://localhost:9001/list-passwords', {
            // Configurações adicionais para ajudar com problemas de CORS
            withCredentials: false,
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            }
          });
          
          console.log("Resposta recebida:", response.data);
          
          // Processar os dados da resposta
          if (response.data && response.data.passwords) {
            this.passwords = response.data.passwords;
          } else {
            this.passwords = [];
          }
        } catch (error) {
          console.error("Erro ao buscar senhas:", error);
          this.error = "Não foi possível carregar as senhas. Verifique se o servidor está rodando.";
        } finally {
          this.loading = false;
        }
      },
      
      formatDate(dateString) {
        if (!dateString) return '';
        
        try {
          const date = new Date(dateString);
          return date.toLocaleString();
        } catch (e) {
          return dateString;
        }
      }
    }
  };
  </script>
  
  <style scoped>
  .passwords-list {
    margin: 20px;
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  
  h2 {
    color: #2c3e50;
    margin-bottom: 15px;
  }
  
  .password-items {
    list-style-type: none;
    padding: 0;
  }
  
  .password-item {
    background-color: #fff;
    border-radius: 5px;
    margin-bottom: 10px;
    padding: 12px 15px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: transform 0.2s;
  }
  
  .password-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
  }
  
  .password-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  
  .password-value {
    font-family: monospace;
    font-size: 16px;
    background-color: #f1f1f1;
    padding: 4px 8px;
    border-radius: 4px;
  }
  
  .password-date {
    color: #6c757d;
    font-size: 14px;
  }
  
  .error {
    color: #721c24;
    padding: 15px;
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    border-radius: 5px;
    margin-bottom: 10px;
  }
  
  .retry-button {
    margin-top: 10px;
    padding: 6px 12px;
    background-color: #dc3545;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  
  .retry-button:hover {
    background-color: #c82333;
  }
  
  .empty-message {
    padding: 20px;
    background-color: #e9ecef;
    border-radius: 5px;
    color: #6c757d;
    font-style: italic;
  }
  </style>