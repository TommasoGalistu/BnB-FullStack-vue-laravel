<script>
export default {
  name: "Routinglist",
  methods: {
    logout() {
      axios.get("http://127.0.0.1:8000/sanctum/csrf-cookie").then(() => {
        axios
          .post("http://127.0.0.1:8000/api/logout")
          .then((response) => {
            axios
              .get("http://127.0.0.1:8000/api/user")
              .then((response) => {
                store.user = response.data;
                if (response.data.name) {
                  store.userName = response.data.name;
                } else {
                  store.userName = "Profilo";
                }
                console.log("app- user e user name: ok");
              })
              .catch((err) => {
                store.is_logged = false;
                store.userName = "Accedi";
                console.log("app- user e user name:", err.message);
              });
            this.$router.push({ name: "login" });
          })
          .catch((err) => {
            console.log("Errore nel logout:", err);
          });
      });
    },
  },
  props: {
    visible: {
      typeof: Boolean,
    },
  },
};
</script>

<template>
  <div class="mt-2 container-fluid">
    <ul class="d-flex flex-column justify-content-center">
      <li class="py-3 d-flex align-items-center" v-if="visible">
        <router-link
          class="link d-flex align-items-center gap-2"
          :to="{ name: 'apartments' }"
        >
          <i class="fa-solid fa-list"></i>
          <span class="d-none d-xl-inline d-lg-inline"
            >I miei appartamenti</span
          ></router-link
        >
      </li>
      <li class="py-3 d-flex align-items-center">
        <router-link
          class="link d-flex align-items-center gap-2"
          :to="{ name: 'createApartment' }"
        >
          <i class="fa-solid fa-circle-plus"></i>
          <span class="d-none d-xl-inline d-lg-inline"
            >Nuovo appartamento</span
          ></router-link
        >
      </li>
      <li @click="logout" class="py-3 d-flex align-items-center">
        <font-awesome-icon :icon="['fas', 'arrow-right-from-bracket']" />
      </li>
      <!-- <li class="py-3 d-flex align-items-center"> -->
      <!-- <router-link -->
      <!-- class="link d-flex align-items-center gap-2" -->
      <!-- :to="{ name: 'messages' }" -->
      <!-- > -->
      <!-- <i class="fa-solid fa-envelope"></i> -->
      <!-- <span class="d-none d-xl-inline d-lg-inline" -->
      <!-- >Messaggi</span -->
      <!-- ></router-link -->
      <!-- > -->
      <!-- </li> -->
      <!-- <li class="py-3 d-flex align-items-center"> -->
      <!-- <router-link -->
      <!-- class="link d-flex align-items-center gap-2" -->
      <!-- :to="{ name: 'sponsor' }" -->
      <!-- > -->
      <!-- <i class="fa-solid fa-money-check"></i> -->
      <!-- <span class="d-none d-xl-inline d-lg-inline" -->
      <!-- >Sponsorizzazione</span -->
      <!-- ></router-link -->
      <!-- > -->
      <!-- </li> -->
    </ul>
  </div>
</template>

<style lang="scss" scoped>
@use "../../../../scss/dashboard" as *;
</style>
