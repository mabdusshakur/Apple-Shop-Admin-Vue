<script setup>
import { router } from '@inertiajs/vue3'

async function Login() {
    let email = document.getElementById('email').value;
    if (email.length === 0) {
        alert("Email Required!");
    } else {
        let res = await axios.post("/api/auth/login", {
            email: email
        });

        if (res.data.success === true) {
            sessionStorage.setItem('email', email);
            router.visit('/verify-otp');
        } else {
            alert("Something Went Wrong");
        }
    }

}
</script>

<template>
    <div class="d-flex justify-content-center">
        <div class="col-xl-6 col-md-10">
            <div class="bg-white p-5 shadow-sm rounded">
                <h3>Login</h3>
                <div class="form-group mb-3">
                    <input class="form-control" id="email" name="email" type="text" required=""
                        placeholder="Your Email">
                </div>
                <div class="form-group mb-3">
                    <button class="btn btn-outline-dark btn-block" name="login" type="submit"
                        v-on:click="Login">Next</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>