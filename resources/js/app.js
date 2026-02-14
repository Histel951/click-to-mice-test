import './bootstrap';
import { createApp } from 'vue';
import Login from './Pages/Login.vue';
import Dashboard from './Pages/Dashboard.vue';
import { createRouter, createWebHistory } from 'vue-router';
import App from "@/Pages/App.vue";
import CreateOrder from "@/Pages/CreateOrder.vue";
import UpdateOrder from "@/Pages/UpdateOrder.vue";

const routes = [
    { path: '/', component: Login },
    { path: '/dashboard', component: Dashboard },
    { path: '/order/create', component: CreateOrder },
    { path: '/order/update/:uuid', component: UpdateOrder }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

createApp(App).use(router).mount('#app');
