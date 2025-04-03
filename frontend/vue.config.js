const { defineConfig } = require('@vue/cli-service');
module.exports = defineConfig({
  transpileDependencies: true,
  devServer: {
    hot: true, // Ativar Hot Module Replacement (HMR)
    port: 8081, // Garante que o servidor estará na porta 8081
    allowedHosts: 'all' // Permite conexões de qualquer host
  }
});
