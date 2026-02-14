<template>
    <div class="login-page">
        <h1>Вход</h1>
        <form @submit.prevent="submitLogin">
            <input type="email" v-model="email" placeholder="Email" required />
            <input type="password" v-model="password" placeholder="Пароль" required />
            <button type="submit">Войти</button>
        </form>
        <p v-if="error" class="error">{{ error }}</p>
    </div>
</template>

<script>
import { api } from '../api.js';

export default {
    data() {
        return {
            email: '',
            password: '',
            error: null,
        };
    },
    methods: {
        async submitLogin() {
            this.error = null
            try {
                const response = await api.post('/auth/login', { email: this.email, password: this.password })
                const data = response.data.data;

                localStorage.setItem('token', data.access_token)
                window.location.href = '/dashboard';
            } catch (err) {
                this.error = err.response?.data?.message || 'Ошибка входа'
            }
        },
    },
};
</script>

<style scoped>
.login-page {
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    background: #f5f5f5;
    border-radius: 8px;
}
input {
    display: block;
    width: 100%;
    margin-bottom: 10px;
    padding: 8px;
}
button {
    padding: 8px 16px;
}
.error {
    color: red;
    margin-top: 10px;
}
</style>
