import Vue from 'vue'
import App from './App.vue'
import router from './router'
import axios from 'axios'

import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)

Vue.prototype.$http = axios
Vue.config.productionTip = false

axios.interceptors.request.use(
  config => {
    // Get token from localStorage
    const accessToken = localStorage.getItem('accessToken')

    // If token is present add it to request's Authorization Header
    if (accessToken) {
      // eslint-disable-next-line no-param-reassign
      config.headers.Authorization = `Bearer ${accessToken.replace(/['"]+/g, '')}`
    }
    return config
  },
  error => Promise.reject(error),
)

// axios.defaults.withCredentials = false;
axios.interceptors.response.use(undefined, function (error) {
  if (error) {
    const originalRequest = error.config;
    if (error.response.status === 401 && !originalRequest._retry) {
        originalRequest._retry = true;
        localStorage.removeItem('accessToken');        
    }
  }
})

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
