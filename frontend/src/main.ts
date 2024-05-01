import './assets/main.css'
import '../node_modules/flowbite-vue/dist/index.css'
import Vue3Toasity from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(Vue3Toasity, {
  position: "top-right",
  timeout: 3000,
  closeOnClick: true,
});

app.mount('#app')
