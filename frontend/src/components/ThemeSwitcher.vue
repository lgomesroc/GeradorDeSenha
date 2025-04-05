<template>
    <div class="theme-switcher">
      <label for="theme">Modo:</label>
      <select id="theme" v-model="theme" @change="toggleTheme">
        <option value="light">Claro</option>
        <option value="dark">Escuro</option>
      </select>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        theme: 'light', // Tema padrão
      };
    },
    methods: {
      toggleTheme() {
        document.body.style.backgroundColor =
          this.theme === 'dark' ? '#121212' : '#FFFFFF';
        document.body.style.color =
          this.theme === 'dark' ? '#FFFFFF' : '#000000';
  
        // Opcional: Persistir tema no localStorage
        localStorage.setItem('theme', this.theme);
      },
    },
    mounted() {
      // Recuperar tema do localStorage (se disponível)
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme) {
        this.theme = savedTheme;
        this.toggleTheme();
      }
    },
  };
  </script>
  
  <style>
  .theme-switcher {
    margin-top: 10px;
  }
  .theme-switcher select {
    padding: 5px;
    border-radius: 4px;
  }
  </style>
  