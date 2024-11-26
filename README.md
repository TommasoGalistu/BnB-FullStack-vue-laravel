Descrizione del progetto

Questo progetto Full Stack è stato sviluppato in gruppo e replica una piattaforma di prenotazione online, simile a quelle utilizzate per BnB, ma focalizzata esclusivamente su appartamenti in Italia. La piattaforma consente agli utenti di visualizzare, cercare e gestire appartamenti in modo semplice ed efficace. Grazie all'uso integrato di Laravel e Vue.js, il progetto offre una separazione tra front-end e back-end, con il front-end interamente sviluppato in Vue.js, mentre Laravel si occupa della gestione delle operazioni di back-end, come l'autenticazione e la gestione dei dati tramite API.

La piattaforma è composta da una Home page accessibile anche agli utenti non registrati, dove gli appartamenti disponibili sono visibili. È presente una pagina di ricerca che consente agli utenti di filtrare i risultati, mostrando gli appartamenti in due colonne: una per gli appartamenti stessi e l'altra per la geolocalizzazione. Gli utenti registrati possono accedere a una dashboard personale, dove possono gestire i propri appartamenti, modificarli o eliminarli, con la possibilità di mantenere comunque i dati nel sistema per fini statistici. Inoltre, gli utenti possono visualizzare in anteprima i loro appartamenti e interagire con i messaggi ricevuti, oltre a sponsorizzare i propri immobili per aumentarne la visibilità.
Il mio ruolo

Questo progetto mi ha dato l'opportunità di affrontare sfide tecniche complesse e consolidare le mie competenze. In qualità di sviluppatore, mi sono concentrato principalmente sulla creazione dello scaffolding del progetto e sull'integrazione di Vue.js con Laravel. Il front-end è stato completamente sviluppato in Vue.js, una scelta che ha permesso di rendere l'interfaccia altamente interattiva e reattiva, mentre Laravel ha gestito tutto il back-end, comprese le operazioni di autenticazione, gestione dei dati e comunicazione tramite API.

Ho deciso di inserire Vue all'interno della cartella resources di Laravel, personalizzando alcune impostazioni di base e montando l'applicazione tramite una pagina Blade. Ho implementato il sistema di autenticazione utilizzando Sanctum e Fortify per gestire la registrazione, il login e la protezione delle rotte API, consentendo una gestione sicura e scalabile degli utenti.

Un'altra parte significativa del mio lavoro è stata l'integrazione delle API di TomTom per la gestione della ricerca degli indirizzi. Ho creato una funzionalità di ricerca con una dropdown dinamica che appariva solo al termine della digitazione, suggerendo indirizzi in base alle parole inserite. Inoltre, mi sono occupato di sviluppare i filtri della pagina di ricerca, curando il layout e lo stile della pagina per garantire un'esperienza utente ottimale.

Nel progetto è stata implementata una funzionalità di pagamenti online tramite Braintree. Ho configurato e integrato il sistema di pagamento, permettendo agli utenti di sponsorizzare i propri appartamenti per farli apparire in cima ai risultati. Inoltre, ho gestito la creazione di statistiche relative agli appartamenti, visualizzabili tramite Chart.js.

Infine, grazie alla mia attitudine alla risoluzione dei problemi, sono stato incaricato di affrontare e risolvere i bug più complessi che si presentavano durante lo sviluppo, cercando soluzioni ottimali per ogni difficoltà tecnica.
Problemi riscontrati e soluzioni adottate

Questo progetto mi ha messo duramente alla prova e mi ha permesso di migliorare le mie capacità di problem solving. La prima grande sfida è stata integrare Vue.js con Laravel e gestire l'autenticazione utente tramite Sanctum e Fortify. Nonostante avessi esperienza con Laravel Breeze, mi sono reso conto che non era la soluzione ideale per il nostro progetto. Ho quindi deciso di integrare Vue direttamente dentro Laravel, creando una comunicazione fluida tra il front-end e il back-end tramite API gestite da Laravel. Dopo aver studiato la documentazione, sono riuscito a far funzionare correttamente il sistema di autenticazione e a far comunicare le rotte API tra Laravel e Vue, con molta soddisfazione al termine del processo.

La seconda sfida importante è stata l'integrazione del sistema di pagamenti con Braintree. La documentazione inizialmente mi sembrava complessa e poco chiara, ma dopo aver eseguito diverse ricerche e visionato video tutorial, sono riuscito a implementare correttamente il flusso di pagamento. La comunicazione tra Vue e Laravel avviene tramite una chiamata API, ottenendo un token di pagamento da Braintree che viene poi utilizzato da Vue per avviare il modulo di pagamento. Una volta che l'utente inserisce i dati della carta, il token viene verificato da Braintree, che restituisce un altro token di conferma, passando quindi a Laravel per completare l'operazione. È stato un processo complesso, ma sono riuscito a implementarlo con successo in tempi stretti.
Tecnologie utilizzate

    Vue.js (Framework JavaScript per il front-end interattivo e reattivo)
    Laravel (Framework PHP per il back-end e gestione delle API)
    Sanctum (Sistema di autenticazione API per la gestione dei token)
    Fortify (Gestione avanzata dell'autenticazione, registrazione e login)
    TomTom API (Geolocalizzazione e ricerca indirizzi)
    Braintree (Sistema di pagamento online per sponsorizzare appartamenti)
    Chart.js (Visualizzazione delle statistiche relative agli appartamenti)
    Blade (Motore di template Laravel per la gestione delle viste)
    MySQL (Database relazionale per la gestione dei dati)

Conclusione

Questo progetto mi ha dato l'opportunità di affrontare e risolvere problemi complessi, migliorando significativamente le mie competenze in full-stack development. Grazie all'adozione di Vue.js per il front-end e Laravel per il back-end, sono riuscito a creare una piattaforma altamente interattiva, sicura e scalabile. La gestione delle operazioni CRUD tramite API, insieme all'integrazione di funzionalità avanzate come la geolocalizzazione, il sistema di pagamento e le statistiche, ha reso il progetto estremamente completo e stimolante. Questo lavoro mi ha anche insegnato l'importanza di una comunicazione chiara e di un lavoro collaborativo, fattori che sono stati fondamentali per il successo del progetto.
