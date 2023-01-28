<template>
  <div>
    <div class="mb-3 form-check">
      <input
        class="form-check-input"
        type="checkbox"
        name="has_qr_code_in_credential"
        v-model="checked"
        value="yes"
      />
      <label class="form-check-label" for="flexCheckDefault">
        Deseja QRCode v-card na credencial?
      </label>
    </div>
    <div class="mb-3" v-if="checked">
      <label for="credentialExpireDate" class="form-label">Validade</label>
      <input
        type="date"
        class="form-control"
        id="credentialExpireDate"
        name="credential_expiration_date"
        aria-describedby="credentialExpireDate"
      />
      <div id="credentialExpireDate" class="form-text">
        Quando vence o QRCode da credencial?
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      checked: false,
    };
  },
};
</script>
