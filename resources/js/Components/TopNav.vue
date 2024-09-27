<script setup>
import { router } from '@inertiajs/vue3';

function logout() {
    axios.post("/api/auth/logout");
    router.visit('/login')
}
</script>
<template>
    <nav class="navbar fixed-top bg-white px-0 shadow-sm">
        <div class="container-fluid">

            <Link class="navbar-brand" href="#">
            <img class="nav-logo mx-2" src="/public/admin/images/logo.png" alt="logo" />
            </Link>

            <div class="d-flex float-right h-auto">
                <div class="user-dropdown">
                    <img class="icon-nav-img" src="/public/admin/images/user.webp" alt="" />
                    <div class="user-dropdown-content">
                        <div class="mt-4 text-center">
                            <img class="icon-nav-img" src="/public/admin/images/user.webp" alt="" />
                            <h6>User Name</h6>
                            <hr class="user-dropdown-divider p-0" />
                        </div>
                        <Link class="side-bar-item" href="/profile">
                        <span class="side-bar-item-caption">Profile</span>
                        </Link>
                        <Link class="side-bar-item" v-on:click="logout">
                        <span class="side-bar-item-caption">Logout</span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<style scoped></style>