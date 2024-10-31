<script>
import { RouterLink } from "vue-router";
import Routinglist from "./partials/Routinglist.vue";
import { store } from "../../store/store";

export default {
  name: "IndexApartment",
  components: {
    Routinglist,
  },
  data() {
    return {
      name: "",
      toastMessage: "",
      sponsorType: "nulla",
      apartments: "",
    };
  },
  methods: {
    getMessages(apartmentId) {
      const apartment = this.apartments.find((ap) => ap.id === apartmentId);
      if (apartment) {
        apartment.unreadMessages = 0;

        const readMessages =
          JSON.parse(localStorage.getItem("readMessages")) || {};

        readMessages[apartmentId] = apartment.messages.map((msg) => msg.id);

        localStorage.setItem("readMessages", JSON.stringify(readMessages));

        apartment.messages.forEach((message) => {
          message.read = true;
        });
      }
    },

    getUnreadMessages(apartment) {
      const readMessages =
        JSON.parse(localStorage.getItem("readMessages")) || {};
      const readMessageIds = readMessages[apartment.id] || [];

      return apartment.messages.filter(
        (msg) => !readMessageIds.includes(msg.id)
      ).length;
    },
    showToast(message, type = "success") {
      //metodo che permette di mostrare il toast

      this.toastMessage = message;
      const toastEl = this.$refs.liveToast;

      toastEl.classList.remove("text-bg-success", "text-bg-danger");

      if (type === "success") {
        toastEl.classList.add("text-bg-success");
      } else {
        toastEl.classList.add("text-bg-danger");
      }
      const toast = new bootstrap.Toast(toastEl);

      toast.show();

      setTimeout(() => {
        toast.hide();
      }, 5000);
    },
    hideToast() {
      //metodo che permette di nascondere il toast

      const toastEl = this.$refs.liveToast;

      const toast = new bootstrap.Toast(toastEl);

      toast.hide();
    },
    deleteApartment(apartment) {
      axios
        .delete(`/api/user/utente/dashboard/${apartment.id}`) //chiamata API al backend con DELETE
        .then((res) => {
          //   //elimina l'appartamento dalla lista locale (frontend)
          //   this.apartments = this.apartments.filter(
          //     (a) => a.id !== apartment.id
          //   );
          //   //aggiorna il localStorage e lo store
          //   store.userApartment = store.userApartment.filter(
          //     (a) => a.id !== apartment.id
          //   );
          //   localStorage.setItem(
          //     "apartments",
          //     JSON.stringify(store.userApartment)
          //   );

          this.showToast(
            `Appartamento "${apartment.title}" eliminato con successo`,
            "success"
          );
          axios
            .get("api/user/utente/dashboard")
            .then((response) => {
              store.userApartment = response.data;
            })
            .catch((err) => {
              console.log(err);
            });
        })
        .catch((err) => {
          console.error(err);
          this.showToast(
            `Errore durante l'eliminazione dell'appartamento "${apartment.title}"`,
            "error"
          );
        });
    },

    imageUrl(path) {
      return `http://127.0.0.1:8000/${path}`; // URL completo dell'immagine
    },
    formatDate(dateCarbon) {
      let date = dateCarbon.split(" ")[0].split("-").reverse().join("/");
      let hour = dateCarbon.split(" ")[1].split(":").slice(0, -1).join(":");
      let string = date + " " + hour;
      return string;
    },
  },
  //   detailApartment(id) {},

  mounted() {
    // console.log("user apartment", store.userApartment[0].sponsors[0].category);
    axios
      .get("/api/user")
      .then((res) => {
        this.name = res.data.name;
      })
      .catch((err) => {
        console.log(err);
      });

    // axios
    //   .get("api/user/utente/dashboard")
    //   .then((response) => {
    //
    //     this.apartments = response.data;
    //   })
    //   .catch((err) => {
    //     console.log(err);
    //   });

    axios
      .get("api/user/utente/dashboard")
      .then((response) => {
        store.userApartment = response.data;
        this.apartments = store.userApartment;
      })
      .catch((err) => {
        console.log(err);
      });

    if (this.$route.query.toastMessage) {
      this.showToast(
        this.$route.query.toastMessage,
        this.$route.query.toastType || "success"
      );
    }

    //evita che al refresh della pagina continui ad apparire la notifica toast
    this.$router.replace({
      name: this.$route.name,
      params: this.$route.params,
      query: {},
    });
  },
  computed: {
    isPremium() {
      return store.userApartment.filter(
        (apartment) => apartment.sponsors.length > 0
      ).length;
    },
    apartmentFiltred() {
      return store.userApartment;
    },
    lengthArrayApartment() {
      return store.userApartment.length;
    },
  },
};
</script>
<template>
  <div class="wrapper d-flex">
    <div class="container-fluid my-3">
      <router-link :to="{ name: 'dashboard' }">
        <font-awesome-icon
          class="ms-2 iconArrow"
          :icon="['fas', 'circle-arrow-left']"
        />
      </router-link>
      <div class="row" v-if="lengthArrayApartment">
        <div class="col-lg-12 col-md-12">
          <h2 class="my-4">I miei appartamenti: {{ lengthArrayApartment }}</h2>

          <!-- <h4 class="my-5">Appartamenti totali: {{ apartments.length }}</h4> -->

          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">#id</th>
                  <th scope="col" class="d-none d-sm-table-cell">Immagine</th>
                  <th scope="col">Nome</th>
                  <th scope="col">Sponsor</th>
                  <th scope="col" class="d-none d-sm-table-cell">
                    Disponibilità
                  </th>
                  <th scope="col">Messaggi</th>
                  <th scope="col">Statistiche</th>
                  <th scope="col">Azioni</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="apartment in apartments" :key="apartment.id">
                  <td scope="row" class="align-middle">
                    {{ apartment.id }}
                  </td>
                  <td class="img-container d-none d-sm-table-cell" scope="row">
                    <img
                      class="img-fluid"
                      :src="imageUrl(apartment.image)"
                      alt=""
                    />
                  </td>
                  <td scope="row" class="align-middle">
                    {{ apartment.title }}
                  </td>
                  <td scope="row" class="align-middle">
                    <router-link
                      v-if="!isPremium"
                      class="sponsor"
                      :to="{
                        name: 'payment',
                        params: { id: apartment.id, title: apartment.slug },
                      }"
                      >Sponsorizza</router-link
                    >
                    <span class="sponsor" v-if="apartment?.sponsors[0]">{{
                      formatDate(apartment.sponsors[0].pivot.end_at)
                    }}</span>
                    <span v-if="!apartment?.sponsors[0] && isPremium">-</span>
                  </td>
                  <td scope="row" class="align-middle d-none d-sm-table-cell">
                    <p
                      class="badge text-bg-success"
                      v-if="apartment.is_visible === 1"
                    >
                      Visibile
                    </p>
                    <p class="badge text-bg-danger" v-else>Non visibile</p>
                  </td>

                  <td scope="row" class="align-middle">
                    <router-link
                      :to="{
                        name: 'messages',
                        params: {
                          id: apartment.id,
                        },
                      }"
                      @click="getMessages(apartment.id)"
                    >
                      <span class="position-relative">
                        <span
                          v-if="getUnreadMessages(apartment) > 0"
                          class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                        >
                          {{ getUnreadMessages(apartment) }}
                        </span>
                        <i class="fa-solid fa-envelope"> </i>
                      </span>
                    </router-link>
                  </td>
                  <td scope="row" class="align-middle">
                    <router-link
                      :to="{
                        name: 'statistic',
                        params: { id: apartment.id, title: apartment.title },
                      }"
                    >
                      <i class="fa-solid fa-chart-simple"></i>
                    </router-link>
                  </td>

                  <td scope="row" class="actions align-middle">
                    <!-- td grandezza normale -->
                    <div class="d-none d-sm-table-cell">
                      <router-link
                        :to="{
                          name: 'showApartment',
                          params: { slug: apartment.slug, id: apartment.id },
                        }"
                      >
                        <div class="btn btn-primary">
                          <i class="fa-solid fa-eye"></i>
                        </div>
                      </router-link>
                      <router-link
                        class="link"
                        :to="{
                          name: 'EditApartment',
                          params: { id: apartment.id },
                        }"
                      >
                        <div class="btn btn-warning">
                          <i class="fa-solid fa-pencil"></i>
                        </div>
                      </router-link>

                      <div class="d-inline-block">
                        <button
                          type="button"
                          class="btn btn-danger"
                          id="liveToastBtn"
                          data-bs-toggle="modal"
                          data-bs-target="#mioModale"
                        >
                          <i class="fa-solid fa-trash"></i>
                        </button>
                      </div>
                    </div>
                    <!-- Modale -->
                    <div
                      class="modal fade"
                      id="mioModale"
                      tabindex="-1"
                      aria-labelledby="mioModaleLabel"
                      aria-hidden="true"
                    >
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="mioModaleLabel">
                              Sei sicuro di voler eliminare l'appartamento?
                            </h5>
                            <button
                              type="button"
                              class="btn-close"
                              data-bs-dismiss="modal"
                              aria-label="Chiudi"
                            ></button>
                          </div>
                          <div class="modal-body">
                            In questo modo
                            <strong>{{ apartment.title }}</strong> non sarà più
                            disponibile
                          </div>
                          <div class="modal-footer">
                            <button
                              type="button"
                              class="btn btn-secondary"
                              data-bs-dismiss="modal"
                            >
                              Chiudi
                            </button>
                            <button
                              type="button"
                              @click="deleteApartment(apartment)"
                              data-bs-dismiss="modal"
                              class="btn btn-danger"
                            >
                              Elimina
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- td responsive -->
                    <div class="d-block d-sm-none">
                      <div class="btn-group dropup">
                        <button
                          type="button"
                          class="btn btn-light dropdown-toggle"
                          data-bs-toggle="dropdown"
                          aria-expanded="false"
                        >
                          <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                          <li>
                            <router-link
                              :to="{
                                name: 'showApartment',
                                params: {
                                  slug: apartment.slug,
                                  id: apartment.id,
                                },
                              }"
                            >
                              <div class="btn btn-primary m-1">
                                <i class="fa-solid fa-eye"></i>
                              </div>
                            </router-link>
                          </li>
                          <li>
                            <router-link
                              class="link"
                              :to="{
                                name: 'EditApartment',
                                params: { id: apartment.id },
                              }"
                            >
                              <div class="btn btn-warning m-1">
                                <i class="fa-solid fa-pencil"></i>
                              </div>
                            </router-link>
                          </li>

                          <li>
                            <div class="d-inline-block">
                              <button
                                type="button"
                                class="btn btn-danger m-1"
                                id="liveToastBtn"
                                data-bs-toggle="modal"
                                data-bs-target="#mioModale"
                              >
                                <i class="fa-solid fa-trash"></i>
                              </button>
                            </div>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div>
        <h2 class="pt-5">
          Non hai appartamenti, torna indietro nella
          <router-link
            class="link badge text-bg-dark"
            :to="{ name: 'dashboard' }"
            >dashboard</router-link
          >
        </h2>
      </div>
    </div>

    <!-- codice del toast -->
    <div
      ref="liveToast"
      class="toast align-items-center text-bg-success position-fixed bottom-0 end-0 p-2 m-3"
      role="alert"
      aria-live="assertive"
      aria-atomic="true"
      style="z-index: 1050"
    >
      <div class="d-flex">
        <div class="toast-body">
          {{ toastMessage }}
        </div>
        <button
          type="button"
          class="btn-close btn-close-white me-2 m-auto"
          @click="hideToast"
          aria-label="Close"
        ></button>
      </div>
    </div>
  </div>
</template>
<style lang='scss' scoped>
@use "../../../scss/variables" as *;
@use "../../../scss/dashboard" as *;

td {
  height: 100px;
}

.img-container {
  width: 5%;
}

.btn {
  margin-right: 10px;
}

.toast {
  position: fixed;
  bottom: 0;
  right: 0;
  z-index: 1050;
}

a {
  color: black;
}
.sponsor {
  height: 0.3rem;
  width: 0.3rem;
  padding: 0.2rem;
  border-radius: 20px;
  margin-left: 0.5rem;
  background-color: rgb(255, 215, 0);
}
.sponsor.bronzo {
  background-color: rgb(205, 127, 50);
}
.sponsor.bronzo {
  background-color: rgb(192, 192, 192);
}

.fa-envelope,
.fa-chart-simple {
  font-size: 25px;
}
.disable {
  display: none;
}
.iconArrow {
  font-size: 2rem;
}

.dropdown-menu {
  --bs-dropdown-min-width: none;
}

// @use 'path' as *;
</style>




