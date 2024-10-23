import './bootstrap';

import '../scss/_general.scss'
// Includo il file CSS e JavaScript di Bootstrap
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import 'bootstrap';  // Importa JavaScript di Bootstrap
import '../scss/_variables.scss';
import '../scss/_dashboard.scss';
import PaymentComponent from './pages/components/PaymentComponent.vue';
import router from './router/index';
import App from './App.vue'


// Importa i core di FontAwesome
import { library } from '@fortawesome/fontawesome-svg-core';

// Importa il componente di FontAwesome
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

// Importa specifiche icone (in questo caso la caret-down)
import { faCaretDown } from '@fortawesome/free-solid-svg-icons';

// Aggiungi le icone alla libreria di Font Awesome
library.add(faCaretDown);


import { createApp } from 'vue';
const app = createApp(App)
app.component('payment-component', PaymentComponent)
app.component('font-awesome-icon', FontAwesomeIcon)
app.use(router)
app.mount('#app')
