<template>
    <div class="edit-order">
        <h1 class="title">Редактирование заказа</h1>
        <button @click="locationDashboard" class="primary-btn">Дашборд</button>

        <div v-if="loading" class="loading">Загрузка...</div>

        <div v-else>
            <div class="services-list">
                <div
                    v-for="service in services"
                    :key="service.uuid"
                    class="service-card"
                >
                    <img :src="service.image" class="service-image" />

                    <div class="service-info">
                        <h3>{{ service.name }}</h3>
                        <p>{{ service.description }}</p>
                        <div class="prices">
                            <span>Цена: {{ service.price }} ₽</span>
                            <span>Налог: {{ service.tax }} ₽</span>
                            <span>Итого: {{ service.gross }} ₽</span>
                        </div>
                    </div>

                    <button
                        class="btn"
                        :class="isSelected(service.uuid) ? 'btn-danger' : 'btn-primary'"
                        @click="toggleService(service)"
                    >
                        {{ isSelected(service.uuid) ? 'Убрать' : 'Добавить' }}
                    </button>
                </div>
            </div>

            <div class="summary" v-if="selectedServices.length">
                <h2>Итого</h2>
                <p>Цена: {{ totalPrice }} ₽</p>
                <p>Налог: {{ totalTax }} ₽</p>
                <p><strong>Итого с налогом: {{ totalGross }} ₽</strong></p>
            </div>

            <div v-if="successMessage" class="alert success">
                {{ successMessage }}
            </div>

            <div v-if="errorMessage" class="alert error">
                {{ errorMessage }}
            </div>

            <div class="actions">
                <button
                    class="btn btn-success"
                    :disabled="!selectedServices.length || saving"
                    @click="updateOrder"
                >
                    {{ saving ? 'Сохранение...' : 'Сохранить изменения' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import {api} from "@/api.js";

const route = useRoute()
const router = useRouter()

const services = ref([])
const selectedServices = ref([])
const loading = ref(false)
const saving = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

const orderId = route.params.uuid

const authConfig = {
    headers: {
        Authorization: "Bearer " + localStorage.getItem('token')
    }
}

const fetchServices = async () => {
    const res = await axios.get('/api/services', authConfig)
    services.value = res.data.data
}

const fetchOrder = async () => {
    const res = await axios.get(`/api/orders/${orderId}`, authConfig)
    const order = res.data.data

    selectedServices.value = services.value.filter(service =>
        order.external_services_uuids.includes(service.uuid)
    )
}

const toggleService = (service) => {
    const index = selectedServices.value.findIndex(
        s => s.uuid === service.uuid
    )

    if (index === -1) {
        selectedServices.value.push(service)
    } else {
        selectedServices.value.splice(index, 1)
    }
}

const isSelected = (uuid) => {
    return selectedServices.value.some(s => s.uuid === uuid)
}

const totalPrice = computed(() =>
    selectedServices.value.reduce((sum, s) => sum + s.price, 0)
)

const totalTax = computed(() =>
    selectedServices.value.reduce((sum, s) => sum + s.tax, 0)
)

const totalGross = computed(() =>
    selectedServices.value.reduce((sum, s) => sum + s.gross, 0)
)

const updateOrder = async () => {
    try {
        saving.value = true
        successMessage.value = ''
        errorMessage.value = ''

        const payload = {
            services: selectedServices.value.map(s => s.uuid)
        }

        const response = await api.patch(`/orders/${orderId}/services`, payload, authConfig)

        successMessage.value = response.data.message || 'Заказ обновлён'

        setTimeout(() => {
            successMessage.value = ''
        }, 3000)

    } catch (e) {
        errorMessage.value =
            e.response?.data?.message || 'Ошибка обновления заказа'

        setTimeout(() => {
            errorMessage.value = ''
        }, 3000)
    } finally {
        saving.value = false
    }
}

const locationDashboard = () => {
    router.push('/dashboard')
}

onMounted(async () => {
    try {
        loading.value = true
        await fetchServices()
        await fetchOrder()
    } finally {
        loading.value = false
    }
})
</script>

<style scoped>
.edit-order {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
}

.title {
    margin-bottom: 20px;
}

.services-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.service-card {
    display: flex;
    gap: 16px;
    align-items: center;
    padding: 16px;
    border: 1px solid #e5e5e5;
    border-radius: 8px;
}

.service-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 6px;
}

.service-info {
    flex: 1;
}

.prices {
    display: flex;
    gap: 12px;
    font-size: 14px;
    margin-top: 8px;
}

.summary {
    margin-top: 24px;
    padding: 16px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #fafafa;
}

.actions {
    margin-top: 20px;
    text-align: right;
}

.alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 16px;
    color: #fff;
}

.success {
    background-color: #28a745;
}

.error {
    background-color: #dc3545;
}

.btn {
    padding: 8px 14px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    color: #fff;
}

.btn-primary {
    background-color: #007bff;
}

.btn-danger {
    background-color: #dc3545;
}

.btn-success {
    background-color: #28a745;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.loading {
    text-align: center;
    padding: 20px;
}

.alert {
    margin-top: 10px;
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 16px;
    color: #fff;
}

.success {
    background-color: #2cc340;
}

.error {
    background-color: #e86775;
}

.primary-btn {
    margin-bottom: 10px;
    padding: 8px 16px;
    background-color: #007bff; /* синий */
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.primary-btn:hover {
    background-color: #0056b3;
}
</style>
