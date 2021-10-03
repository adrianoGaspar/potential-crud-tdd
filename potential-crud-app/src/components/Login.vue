<template>
 <div id="app" class="container-fluid">
   <router-view/>
    <div class="row no-gutter">
        <div class="col-md-6 d-none d-md-flex bg-image"></div>
        <div class="col-md-6 bg-light">
            <div class="login d-flex align-items-center py-5">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-10 col-xl-7 mx-auto">
                            <h3 class="display-4">Potential Crud</h3>
                            <p class="text-muted mb-4">Crud básico com Laravel 8, TDD e Vuejs 2.</p>
                            <form>
                                <div class="form-group mb-3">
                                    <input id="inputEmail" type="email" v-model="email" :placeholder="email" required="" autofocus="" class="form-control rounded-pill border-0 shadow-sm px-4">
                                </div>
                                <div class="form-group mb-3">
                                    <input id="inputPassword" type="password" v-model="password" placeholder="Password" required="" class="form-control rounded-pill border-0 shadow-sm px-4 text-primary">
                                </div>
                                <div v-if="unauthorized" class="mb-3">
                                    <small style="color: red"><em>Credenciais Inválidas</em></small>
                                </div>
                                <button type="button" @click="login" class="btn btn-primary btn-block text-uppercase mb-2 rounded-pill shadow-sm">Entrar</button>
                                <div class="text-center d-flex justify-content-between mt-4"><p>Code by <a href="#" class="font-italic text-muted"> 
                                        <u>Adriano Gaspar da Silva</u></a></p></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>

export default {
  data() {
    return {
      password: 'admin',
      email: 'admin@crud.com',
      unauthorized: false
    }
  },
  methods: {
    async login() {
      try {
        const result = await this.$http.post(`${process.env.VUE_APP_API_URL}/login`, {
          "email": this.email,
          "password": this.password,
        })
        if (result) {
          localStorage.setItem('accessToken', JSON.stringify(result.data.accessToken))
          this.$router.push({ name: 'Home' })
        } else this.unauthorized = true
      } catch (e) {
        // console.log(e)
      }
    }
  }
}
</script>

<style scoped>
  .login,
  .image {
    min-height: 100vh;
  }
  .bg-image {
    background-image: url('../assets/login.jpg');
    background-size: cover;
    background-position: center center;
  }
</style>