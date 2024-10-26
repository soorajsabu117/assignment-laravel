import { createApp } from "vue";
import App from "./components/App.vue";
import { createRouter, createWebHistory } from "vue-router";
import Signup from "./components/Signup.vue";
import Login from "./components/Login.vue"; // Create this file later

const routes = [
    { path: "/signup", component: Signup },
    { path: "/login", component: Login },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

const app = createApp(App);
app.use(router);
app.mount("#app");
