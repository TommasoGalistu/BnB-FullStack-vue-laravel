<script>
import { store } from "../store/store.js";
import { checkAdress } from "../store/store";
// import { throttle } from "lodash";

export default {
  name: "Header",
  data() {
    return {
      //   // name user
      //   name: store.user.name,
      //   auto compiled v model input
      searchQuery: "",
      //   data of all adress
      address: [],
      //   timeout for bettere request after user write
      debounceTimeout: null,

      isThrottled: false,
      isClose: false,
      previousScrollPosition: window.scrollY,
      isScrollingDown: false,
    };
  },
  methods: {
    // call axios for logout
    logout() {
      axios.get("/sanctum/csrf-cookie").then(() => {
        axios
          .post("/api/logout")
          .then((response) => {
            localStorage.setItem("userName", "Accedi");
            store.userName = localStorage.getItem("userName");
            this.$router.push({ name: "home" });
          })
          .catch((err) => {
            console.log("Errore nel logout:", err);
          });
      });
    },
    openDrop() {
      store.is_open = store.is_open ? false : true;
    },
    debouncedSearch() {
      // Cancella il timeout precedente (se esiste)
      clearTimeout(this.debounceTimeout);

      // Imposta un nuovo timeout
      this.debounceTimeout = setTimeout(() => {
        this.performSearch(); // Esegui la chiamata dopo un ritardo di 300ms
      }, 300); // Cambia questo valore per regolare il ritardo
    },
    performSearch() {
      if (!this.searchQuery.trim()) {
        return;
      }
      let urlRequest = checkAdress(this.searchQuery);
      this.isClose = true;
      axios
        .get("http://127.0.0.1:8000/proxy-tomtom", {
          params: { url: urlRequest },
        })
        .then((response) => {
          this.address = response.data.results;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    sendAdress(addressObj) {
      this.isClose = false;
      store.inputValue = this.searchQuery;
      this.$router.push({
        name: "apartmentsMap",
        query: {
          input: this.searchQuery,
          lon: addressObj.position.lon,
          lat: addressObj.position.lat,
        },
      });
    },
  },
  mounted() {
    window.addEventListener("click", () => {
      if (this.isClose) {
        this.isClose = false;
      }
    });
    window.addEventListener("scroll", () => {
      const currentScrollPosition = window.scrollY;
      let firstRow = document.getElementById("firstRow");

      firstRow.style.transition = "all 0.5s";
      if (currentScrollPosition > this.previousScrollPosition) {
        firstRow.classList.add("hidden");
      } else {
        firstRow.classList.remove("hidden");
      }

      // Aggiorniamo la posizione precedente per il prossimo confronto
      this.previousScrollPosition = currentScrollPosition;
    });
  },
  computed: {
    isLogged() {
      //   boolean login control for show right link
      return store.is_logged;
    },
    isOpen() {
      //   boolean login control for show right link
      return store.is_open;
    },
    userName() {
      return store.userName;
    },
    loggedUserApartment() {
      return store.userName !== "Accedi";
    },
    suggestAdress() {
      return this.address;
    },
    urlRequest() {
      return this.urlSearchQuery;
    },
  },
};
</script>
<template>
  <header>
    <nav>
      <div class="container-fluid">
        <div
          class="row justify-content-between align-items-center"
          id="firstRow"
        >
          <div class="col">
            <router-link
              class="contLogo d-flex align-items-center"
              :to="{ name: 'home' }"
            >
              <img class="logo" src="Boolnb_circle.png" alt="airbnb photo" />
              <span><strong>BoolBnb</strong></span>
            </router-link>
          </div>

          <div class="col d-flex gap-2 justify-content-end">
            <!-- user click dropdown class -->
            <div class="contDropDown" @click.stop="openDrop()">
              <div
                class="profile d-flex justify-content-between align-items-center gap-1 m-0"
              >
                <p class="nameUser">
                  {{ userName !== "null" ? userName : "Profilo" }}
                </p>

                <font-awesome-icon :icon="['fas', 'caret-down']" />
              </div>
              <div class="dropDown" :class="isOpen ? 'active' : 'disactive'">
                <router-link class="link" :to="{ name: 'home' }"
                  >Home</router-link
                >
                <router-link
                  v-if="!isLogged"
                  class="link"
                  :to="{ name: 'login' }"
                  >Login</router-link
                >
                <router-link
                  v-if="!isLogged"
                  class="link"
                  :to="{ name: 'register' }"
                  >Register</router-link
                >
                <router-link class="link" :to="{ name: 'dashboard' }"
                  >Dashboard</router-link
                >
                <p v-if="isLogged" class="link" @click="logout">Logout</p>
              </div>
            </div>
          </div>
        </div>
        <div class="row justify-content-center align-items-center">
          <div class="col-sm-10 col-lg-8">
            <!-- search input for adress -->
            <div class="input-group stylish-input-group">
              <div class="contInput d-flex">
                <input
                  type="text"
                  class="inputCustom"
                  placeholder="Cerca appartamenti per indirizzo..."
                  v-model="searchQuery"
                  @input="debouncedSearch"
                />
                <span class="button">invia</span>

                <div class="contSuggest" v-if="isClose">
                  <ul class="suggest">
                    <li
                      v-for="(addressObj, index) in suggestAdress"
                      :key="index"
                    >
                      <div class="link" @click="sendAdress(addressObj)">
                        <span class="street"
                          >{{ addressObj.address.streetName }} <span> </span>
                        </span>
                        <span class="city"
                          >{{ addressObj.address.municipality }}
                        </span>
                        <span class="city"
                          >{{ addressObj.address.postalCode }}
                        </span>
                        <span class="city">
                          {{ addressObj.address.neighbourhood }}</span
                        >
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </header>
  <nav class="responsiveNavBar">navbar</nav>
</template>
<style lang="scss" scoped>
/* // @use 'path' as *; */
header {
  display: flex;
  gap: 1rem;
  z-index: 100;
  border-bottom: 1px solid black;
  padding: 0.5rem;
  position: fixed;
  width: 100%;
  background-color: white;
  padding: 1rem;

  nav {
    width: 100%;
    height: 100%;
    .profile,
    .link {
      padding: 0.5rem 1rem;
      margin-top: 0.5rem;
    }
    .contDropDown {
      position: relative;
      border-radius: 20px;
      cursor: pointer;
      display: block;
      z-index: 100;
      border: 1px solid black;
      z-index: 101;
      &:hover {
      }
      .profile {
        position: relative;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
      }

      .dropDown.active {
        background-color: white;
        position: absolute;
        left: -62px;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        border-radius: 20px;
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
        .link {
          border-radius: 20px;
          &:hover {
            background-color: rgb(241, 240, 240);
            color: black;
          }
        }
      }
      .dropDown.disactive {
        display: none;
      }
    }
    a.contLogo {
      color: #ff5757;

      img.logo {
        height: 50px;
        width: 50px;
      }

      strong {
        font-size: 20px;
      }
    }
  }
}

p,
.profile,
.link {
  margin: 0;
  padding: 0;
}

.disactive {
  display: none;
}
//#inputAdress
.contInput {
  width: 70%;
  height: 4.5rem;
  padding: 0.5rem;
  border-radius: 20px;
  position: relative;
  left: 50%;
  transform: translate(-50%);
  //   width: 70%;
  //   padding: 0.5rem;
  //   border-radius: 20px;
  //   border: 1px solid black;
  //   position: relative;
}
.button {
  display: none;
  background-color: white;
  border: 1px solid grey;
  padding: 0.5rem;
  border-top-left-radius: 10px;
  border-bottom-left-radius: 10px;
  border-top-right-radius: 20px;
  border-bottom-right-radius: 20px;
  margin-left: 5px;
  cursor: pointer;
  &:hover {
    background-color: black;
    color: white;
  }
}
.contSuggest {
  .suggest {
    position: absolute;
    width: 90%;
    top: 89%;
    left: 50%;
    transform: translate(-50%);
    background-color: white;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
    font-size: 0.75rem;
    box-shadow: rgba(140, 2, 2, 0.35) 0px 5px 15px;
    padding: 0;
    z-index: 10;

    li {
      width: 100%;

      .link {
        width: 100%;
        cursor: pointer;
        text-decoration: none;
        &:hover {
          background-color: rgba($color: rgb(255, 171, 171), $alpha: 0.1);
        }
        // &::before {
        //   content: "";
        //   background-image: url("/public/img/point.svg");
        //   background-size: contain;
        //   background-repeat: no-repeat;
        // }
        .street {
          font-size: 0.9rem;
        }
        .city {
          font-size: 0.75rem;
        }
      }
    }
  }
}
.inputCustom {
  width: 100%;
  height: 100%;
  border-radius: 20px;
  border: none;
  padding: 0.3rem;
  caret-color: black; // Cambia il colore del caret
  font-size: 1.2rem; // Aumenta la dimensione del testo, e di conseguenza la lunghezza del caret
  line-height: 1.5; // Modifica la linea del testo per allungare il caret
  box-shadow: rgba(140, 2, 2, 0.35) 0px 5px 15px;
  &:focus {
    border: none;
    outline: none;
  }
}
@media all and (max-width: 623px) {
  .responsiveNavBar {
    height: 4rem;
    width: 100%;
    background-color: red;
    position: fixed;
    bottom: 0;
    z-index: 100;
  }
  #firstRow {
    display: none;
  }
  .contInput {
    width: 100%;
  }
  .button {
    display: block;
  }
}
.hidden {
  height: 0;
  transition: all 0.3s;
}
</style>
