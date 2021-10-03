<template>
  <div>
    <b-modal :title="titleModal" v-model="modalVisible" @hidden="resetModal" size="lg" title-tag="h4">
      <div class="row">
          <div class="col-lg-12">
              <b-form-group label="Nome">
                  <b-form-input v-model="dev.name" placeholder="Nome..." :state="!!dev.name"></b-form-input>
              </b-form-group>
          </div>
      </div>
      <div class="row">
          <div class="col-lg-4">
              <b-form-group label="Sexo">
                  <b-form-select class="form-control" v-model="dev.gender" :options="[
                                                                  { value: 'male', text: 'Masculino' },
                                                                  { value: 'feminine', text: 'Feminino' }]">
                      <template v-slot:first>
                          <b-form-select-option value="undefined">Nenhum</b-form-select-option>
                      </template>
                  </b-form-select>
              </b-form-group>
          </div>
          <div class="col-lg-4">
              <b-form-group label="Nascimento">
                  <b-form-input @change="calcAge" type="date" v-model="dev.birthday"></b-form-input>
              </b-form-group>
          </div>
          <div class="col-lg-4">
              <b-form-group label="Idade">
                  <input type="text" disabled class="form-control" v-model="dev.age">
              </b-form-group>
          </div>
      </div>
      <div class="row">
          <div class="col-lg-12">
              <b-form-group label="Hobby">
                  <b-textarea v-model="dev.hobby"></b-textarea>
              </b-form-group>
          </div>
      </div>

      <template v-slot:modal-footer="{ cancel }">
          <b-button @click="cancel()">Cancelar</b-button>
          <b-button variant="primary" :disabled="!dev.name" @click="save">Salvar</b-button>
      </template>
    </b-modal>

    <b-navbar type="dark" variant="dark">
      <b-navbar-brand href="#">Potential Crud</b-navbar-brand>
      <b-navbar-nav class="ml-auto">
        <b-nav-item href="#" right @click.prevent="logout">Sair</b-nav-item>
      </b-navbar-nav>
    </b-navbar>

    <b-card>
        <div class="row">
          <div class="col-lg-2">
            <b-button class="mt-4" id="handle-new-dev" @click="handleNewDev" variant="success">Novo Desenvolvedor</b-button>
          </div>
          <div class="col-lg-10">
              <b-form-group label="Filtrar por nome">
                  <b-form-input v-model="filter" @input="filterDev" placeholder="Filtrar por nome..."></b-form-input>
              </b-form-group>
          </div>
        </div>
    </b-card>

    <b-card>
      <b-table :fields="fields" :items="items" foot-clone show-empty>
        <template #cell(gender)="data">
          {{ data.item.gender === 'undefined' ? 'Indefinido' : (data.item.gender === 'male' ? 'Masculino' : 'Feminino') }}
        </template>
        <template #cell(birthday)="data">
          {{ formatDate(data.item.birthday) }}
        </template>
        <template #cell(actions)="data">
          <b-dropdown
            variant="link"
            no-caret
          >
            <template #button-content>
               <b-icon-three-dots-vertical />
            </template>
            <b-dropdown-item @click="handleEditDev(data)">
              <b-icon-pencil-square />
              <span> Editar</span>
            </b-dropdown-item>
            <b-dropdown-item @click="handleDeleteDev(data)">
              <b-icon-trash />
              <span> Excluir</span>
            </b-dropdown-item>
          </b-dropdown>
        </template>
        <template #empty>
          <h4>Nenhum dado a ser mostrado. Comece cadastrando um novo Dev.</h4>
        </template>
      </b-table>
        <b-row>
          <b-col
            cols="12"
            sm="6"
            class="d-flex align-items-center justify-content-center justify-content-sm-start"
          >
            <span class="text-muted">Exibindo {{ from }} a {{ to }} de {{ totalDevelopers }} desenvolvedores</span>
          </b-col>
          <!-- Pagination -->
          <b-col
            cols="12"
            sm="6"
            class="d-flex align-items-center justify-content-center justify-content-sm-end"
          >
            <b-pagination
              v-model="currentPage"
              :total-rows="totalDevelopers"
              :per-page="perPage"
              first-number
              last-number
              @change="changePage"
              class="mb-0 mt-1 mt-sm-0"
              prev-class="prev-item"
              next-class="next-item"
            >
            </b-pagination>
          </b-col>
        </b-row>
    </b-card>
  </div>
</template>

<script>

import moment from 'moment'

export default {
  data() {
    return {
      filter: null,
      titleModal: '',
      modalVisible: false,
      dev: {
        'id': null,
        'name': '',
        'gender': 'undefined',
        'age': '',
        'hobby': '',
        'birthday': '',
      },
      fields: [
          { key: 'name', label: 'Nome' },
          { key: 'gender', label: 'Sexo' },
          { key: 'age', label: 'Idade' },
          { key: 'hobby', label: 'Hobby' },
          { key: 'birthday', label: 'Nascimento' },
          { key: 'actions', label: 'Ações' },
        ],
        items: [],
        perPage: 10,
        totalDevelopers: 0,
        currentPage: 1,
        from: 0,
        to: 0,
    }
  },

  methods: {
    async changePage(page=1) {
      try {
        const result = await this.$http.get(`${process.env.VUE_APP_API_URL}/developers?page=${page}`)
        this.items = result.data.data
        this.perPage = result.data.per_page,
        this.totalDevelopers = result.data.total,
        this.to = result.data.to || 0,
        this.from = result.data.from || 0
      } catch (e) {
        // console.log(e)
      }
    },

    formatDate(date) {
      if(date) return moment(date).format('DD/MM/YYYY')
    },

    async logout() {
      try {
        await this.$http.post(`${process.env.VUE_APP_API_URL}/logout`)
        localStorage.removeItem('accessToken');
        this.$router.push({ name: 'Login' })
      } catch (e) {
        // console.log(e)
      }
    },

    handleNewDev() {
      this.titleModal = 'Cadastrar Desenvolvedor'
      this.modalVisible = true
    },

    handleEditDev(dev) {
      this.titleModal = 'Alterar Desenvolvedor'
      // JÁ TENHO OS DADOS DO DEV QUE VOU ALTERAR, POR ISSO NÃO FAÇO A REQUEST, 
      // PORÉM A ROTA Route::get('/developers/{id?}', [DeveloperController::class, 'get']); NA API
      // CONTEMPLA O REQUISITO GET /developers/{id} Codes 200 / 404
      this.dev = JSON.parse(JSON.stringify(dev.item))
      if(this.dev.birthday) this.dev.birthday =  moment(this.dev.birthday).format('YYYY-MM-DD')
      this.modalVisible = true
    },
    
    async save() {
      try {
        if (this.dev.id) await this.$http.put(`${process.env.VUE_APP_API_URL}/developers/${this.dev.id}`, this.dev)
        else await this.$http.post(`${process.env.VUE_APP_API_URL}/developers`, this.dev)
        this.changePage(this.currentPage)
        this.$bvToast.toast('Dados salvos com sucesso', {
          title: 'Sucesso',
          autoHideDelay: 3500,
          appendToast: false,
          variant: 'success',
          solid: true
        })
        this.modalVisible = false
      } catch (e) {
        // console.log(e)
      }
    },

    handleDeleteDev(dev) {
      this.titleModal = 'Cadastrar Desenvolvedor'
      this.$bvModal
        .msgBoxConfirm(`Deseja Excluir o Dev ${dev.item.name}?`, {
          title: 'Por Favor Confirme esta Ação',
          size: 'lg',
          okVariant: 'warning',
          okTitle: 'Sim',
          cancelTitle: 'Não',
          cancelVariant: 'outline-secondary',
          hideHeaderClose: false,
          centered: true,
        })
        .then(value => {
          if (value) this.deleteDev(dev)
        })
    },

    async deleteDev(dev) {
      try {
        await this.$http.delete(`${process.env.VUE_APP_API_URL}/developers/${dev.item.id}`)
        if(this.items.length === 1 && this.currentPage > 1) this.currentPage = this.currentPage - 1
        this.changePage(this.currentPage)
        this.$bvToast.toast('Dados excluídos com sucesso', {
          title: 'Sucesso',
          autoHideDelay: 3500,
          appendToast: false,
          variant: 'success',
          solid: true
        })
      } catch (e) {
        // console.log(e)
      }
    },

    resetModal() {
      this.dev = {
        'id': null,
        'name': '',
        'gender': 'undefined',
        'age': '',
        'hobby': '',
        'birthday': '',
      }
    },

    // O correto aqui é utilizar o lodash debounce com pelo menos 600ms de atraso
    async filterDev() {
      try {
        const result = await this.$http.get(`${process.env.VUE_APP_API_URL}/developers?search=${this.filter}`)
        this.items = result.data.data
        this.perPage = result.data.per_page,
        this.totalDevelopers = result.data.total,
        this.to = result.data.to || 0,
        this.from = result.data.from || 0
      } catch (e) {
        // console.log(e)
      }
    },

    calcAge() {
      if (this.dev.birthday && this.dev.birthday.length === 10) {
        if (moment(this.dev.birthday, 'YYYY-MM-DD').isValid()) {
          const years = moment().diff(this.dev.birthday, 'years')
          this.dev.age = years >= 0 ? years : 0
        }
      }
    }
  },

  async mounted () {
    this.changePage(1)
  }
}
</script>