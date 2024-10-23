<template>
  <div id="dropin-wrapper">
    <div id="dropin-container"></div>
    <button @click="submitPayment" id="submit-button">Paga Ora</button>

    <p v-if="message">{{ message }}</p>
  </div>
</template>

  <script>
export default {
  name: "PaymentComponent",
  data() {
    return {
      message: "",
      dropinInstance: null,
    };
  },
  mounted() {
    this.getClientToken();
  },
  methods: {
    getClientToken() {
      fetch("/braintree/token")
        .then((response) => response.json())
        .then((data) => {
          braintree.dropin.create(
            {
              authorization: data.clientToken,
              container: "#dropin-container",
            },
            (err, instance) => {
              if (err) {
                console.error("Errore nella creazione del Drop-in UI:", err);
              } else {
                this.dropinInstance = instance;
              }
            }
          );
        });
    },
    submitPayment() {
      this.dropinInstance.requestPaymentMethod((err, payload) => {
        if (err) {
          console.error("Errore nella richiesta del metodo di pagamento:", err);
          return;
        }
        fetch("/braintree/checkout", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
              .querySelector('meta[name="csrf-token"]')
              .getAttribute("content"),
          },
          body: JSON.stringify({
            paymentMethodNonce: payload.nonce,
          }),
        })
          .then((response) => response.json())
          .then((result) => {
            if (result.success) {
              this.message = "Transazione riuscita!";
            } else {
              this.message =
                "Errore nella transazione. Vedi console per i dettagli.";
              console.error(result);
            }
          });
      });
    },
  },
};
</script>
