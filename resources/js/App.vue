<script>
import { store } from "./store/store.js";
import Header from "./partials/Header.vue";
import Footer from "./partials/Footer.vue";
import PaymentComponent from "./pages/components/PaymentComponent.vue";
export default {
  name: "App",
  data() {
    return {};
  },
  components: {
    Header,
    Footer,
    PaymentComponent,
  },
  methods: {
    fillData() {
      // fill data userApartment, allApartments, user, userName

      //   all apartment
      axios
        .get("/api/home", {
          headers: {
            Accept: "application/json",
            "Cache-Control": "no-cache", // Disabilita il caching
          },
          params: {
            t: Date.now(), // Aggiungi un timestamp per evitare il caching
          },
        })
        .then((res) => {
          console.log("app- all apartment:ok", res.data);
          store.allApartments = res.data;
        })
        .catch((err) => {
          console.log("app- all apartment: ", err.message);
        });

      //   name and data of user logged
      axios
        .get("/api/user")
        .then((response) => {
          store.is_logged = true;
          store.user = response.data;
          store.userName = response.data.name;
          console.log("app- user e user name: ok");
        })
        .catch((err) => {
          store.is_logged = false;
          console.log("app- user e user name:", err.message);
        });

      if (store.is_logged) {
        // userApartment
        axios
          .get("api/user/utente/dashboard")
          .then((response) => {
            store.userApartment = response.data;
            console.log("app- user apartment");
          })
          .catch((err) => {
            console.log(err);
          });
      }
    },
  },
  mounted() {
    this.fillData();

    // window.addEventListener("load", function () {
    //   this.fillData();
    // });
    // window.addEventListener("load", () => {
    //   // get a data of user
    //   //   axios
    //   //     .get("api/home")
    //   //     .then((res) => {})
    //   //     .catch((err) => {
    //   //       console.log(err);
    //   //     });
    // });
  },
};
</script>
<template>
  <Header />
  <main>
    <PaymentComponent />
    <router-view></router-view>
  </main>

  <!-- <Footer /> -->
</template>
<style lang="scss" scoped>
/* // @use 'path' as *; */
main {
  padding-top: 9rem;
}

@media screen and (max-width: 632px) {
  main {
    padding-top: 5.5rem;
  }
}
</style>
