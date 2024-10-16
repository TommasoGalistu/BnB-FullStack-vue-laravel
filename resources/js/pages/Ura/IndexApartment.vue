<script>
import { RouterLink } from 'vue-router';
import Routinglist from './partials/Routinglist.vue';
import axios from 'axios';

export default {
  name: "IndexApartment",
  components:{
    Routinglist
  },
  data() {
    return {
      name: "",
      apartments: [],
      message:"",
      messageType:""
    };
  },
    methods: {
    deleteApartment(apartment) {
        if (confirm(`Sei sicuro di voler eliminare l'appartamento "${apartment.title}"?`)) {
        axios
            .post('/api/user/utente/dashboard', { data: apartment })  // Simula DELETE usando POST e passando l'oggetto
            .then((res) => {
            // Rimuovi l'appartamento dalla lista locale
            this.apartments = this.apartments.filter(a => a.id !== apartment.id);
            // Messaggio di successo
            this.message = `Appartamento "${apartment.title}" eliminato con successo`;
            this.messageType = 'success';
            })
            .catch((err) => {
            // Messaggio di errore
            console.error(err);
            this.message = `Errore durante l'eliminazione dell'appartamento "${apartment.title}"`;
            this.messageType = 'error';
            });
        }
    }
    },
  mounted() {
    axios
      .get("/api/user")
      .then((res) => {
        this.name = res.data.name;
      })
      .catch((err) => {
        console.log(err);
      });

    axios
      .get("api/user/utente/dashboard")
      .then((response) => {
        this.apartments = response.data;
      })
      .catch((err) => {
        console.log(err);
      });
  },
};
</script>
<template>
  <div class="wrapper d-flex">
    <aside>
        <Routinglist />
    </aside>
    <div class="dashboard-box">
      <div class="container-fluid my-3">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <h2 class="mb-3">I miei appartamenti</h2>

                <div v-if="message" :class="['alert', messageType === 'success' ? 'alert-success' : 'alert-danger']">
                    {{ message }}
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#id</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Messaggi</th>
                                <th scope="col">Statistiche</th>
                                <th scope="col">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="apartment in apartments" :key="apartment.id">
                            <td scope="row">
                                {{ apartment.id }}
                            </td>
                            <td scope="row">
                                {{ apartment.title }}
                            </td>
                            <td scope="row">
                                <i class="fa-solid fa-envelope"></i>
                            </td>
                            <td scope="row">
                                <i class="fa-solid fa-chart-simple"></i>
                            </td>
                            <td scoper="row" class="actions d-flex gap-2">
                                <RouterLink class="link" :to="{ name: 'showApartment' }">
                                    <div class="btn btn-primary">
                                        <i class="fa-solid fa-eye"></i>
                                    </div>
                                </RouterLink>
                                <a href="#" class="btn btn-warning">
                                <i class="fa-solid fa-pencil"></i>
                                </a>
                                <a href="#" class="btn btn-danger" @click.prevent="deleteApartment(apartment)">
                                <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>

        </div>
      </div>
    </div>
  </div>
</template>
<style lang='scss' scoped>

@use '../../../scss/variables' as *;
@use '../../../scss/dashboard' as *;

// @use 'path' as *;
</style>
