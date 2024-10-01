<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

const otp_code = ref(null);
const email = sessionStorage.getItem('email');

async function verify() {
    if (otp_code.value.length === 0) {
        alert("Code Required!");
    } else {
        axios.post("/api/auth/verify-otp", {
            email: email,
            otp: otp_code.value
        }).then(res => {
            if (res.data.success === true) {
                sessionStorage.setItem('is_auth', true);
                router.visit('/')
            } else {
                alert("Something Went Wrong");
            }
        }).catch(e => {
            alert(e.response.data.message);
        })
    }
}
</script>

<template>
    <div class="d-flex justify-content-center">
        <div class="col-xl-6 col-md-10">
            <div class="bg-white p-5 shadow-sm rounded">
                <h3>Verification</h3>
                <div class="form-group mb-3">
                    <input class="form-control" id="otp_code" name="otp_code" type="text" required=""
                        placeholder="Verification Code" v-model="otp_code">
                </div>
                <div class="form-group mb-3">
                    <button class="btn btn-outline-dark btn-block" name="login" type="submit"
                        v-on:click="verify">Confirm</button>
                </div>
            </div>
        </div>
    </div>

</template>

<style scoped></style>
