<template>
    <div class="dashboard-page">
        <h1>Личный кабинет</h1>
        <button @click="logout" class="logout-btn">Выйти</button>
        <button @click="createOrder" class="primary-btn">Создать заказ</button>

        <section>
            <h2>Заказы</h2>
            <table>
                <thead>
                <tr>
                    <th>Uuid</th>
                    <th>Статус</th>
                    <th>Дата изменения</th>
                    <th>Дата создания</th>
                    <th>Цена</th>
                    <th>Налоги</th>
                    <th>Без налогов</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="order in orders" :key="order.uuid">
                    <td>{{ order.uuid }}</td>
                    <td>
                        <div
                            :class="{
                                'status status--draft': order.status === 0,
                                'status status--processing': order.status === 1,
                                'status status--done': order.status === 2,
                                'status status--canceled': order.status === 3,
                            }"
                        >
                            {{ statusMapper(order.status) }}
                        </div>
                    </td>
                    <td>{{ dateFormatter(order.updated_at) }}</td>
                    <td>{{ dateFormatter(order.created_at) }}</td>
                    <td>{{ order.price }}</td>
                    <td>{{ order.tax }}</td>
                    <td>{{ order.gross }}</td>
                    <td class="btns-container">
                        <button
                            class="primary-btn"
                            v-if="order.status === 0"
                            @click="startProcessing(order.uuid)"
                        >
                            Отправить на обработку
                        </button>
                        <button
                            class="patch-btn"
                            v-if="order.status === 0"
                            @click="updateOrder(order.uuid)"
                        >
                            Редактировать
                        </button>
                        <button
                            class="delete-btn"
                            v-if="order.status === 0"
                            @click="deleteOrder(order.uuid)"
                        >
                            Удалить
                        </button>
                        <button
                            class="delete-btn"
                            v-if="order.status === 1"
                            @click="cancelOrder(order.uuid)"
                        >
                            Отменить
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </section>

        <section>
            <h2>Услуги</h2>
            <table>
                <thead>
                <tr>
                    <th>Uuid</th>
                    <th>Название</th>
                    <th>Описание</th>
                    <th>Цена</th>
                    <th>Налог</th>
                    <th>Без налогов</th>
                    <th>Картинка</th>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="service in services" :key="service.uuid">
                        <td>{{ service.uuid }}</td>
                        <td>{{ service.name }}</td>
                        <td>{{ service.description }}</td>
                        <td>{{ service.price }}</td>
                        <td>{{ service.tax }}</td>
                        <td>{{ service.gross }}</td>
                        <td><img :alt="service.name" :src="service.image" height="40" width="60" /></td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { api } from '../api.js'
import { useRouter } from 'vue-router'

const router = useRouter()

const orders = ref([])
const services = ref([])

const tokenConfig = {
    headers: {
        Authorization: 'Bearer ' + localStorage.getItem('token')
    }
}

const loadOrders = async () => {
    const res = await api.get('/orders', tokenConfig)
    orders.value = res.data.data
}

const loadServices = async () => {
    const res = await api.get('/services?page=1', tokenConfig)
    services.value = res.data.data
}

const startProcessing = async (orderId) => {
    await api.post(
        `/orders/${orderId}/start-processing`,
        { },
        tokenConfig
    )

    await loadOrders()
}

const logout = async () => {
    await api.post('/auth/logout', {}, tokenConfig)
    localStorage.removeItem('token')
    router.push('/')
}

const createOrder = () => {
    router.push('/order/create')
}

const updateOrder = (uuid) => {
    router.push('/order/update/' + uuid)
}

const deleteOrder = async (orderId) => {
    await api.delete(
        '/orders/' + orderId,
        tokenConfig
    )
    await loadOrders()
}

const cancelOrder = async (orderId) => {
    await api.patch(
        `/orders/${orderId}/cancel`,
        {},
        tokenConfig
    )
    await loadOrders()
}

const statusMapper = (statusId) => {
    switch (statusId) {
        case 0:
            return 'Черновик'
        case 1:
            return 'На обработке'
        case 2:
            return 'Готово'
        case 3:
            return 'Отменён'
        default:
            return 'Неизвестно'
    }
}

const dateFormatter = (date) => {
    const newDate = new Date(date)

    return newDate.toLocaleString('ru-RU', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

onMounted(() => {
    loadOrders()
    loadServices()
    setInterval(loadOrders, 30000);
})
</script>


<style scoped>
.dashboard-page {
    max-width: 900px;
    margin: 40px auto;
    padding: 20px;
}

.logout-btn {
    margin-bottom: 20px;
    padding: 6px 12px;
    background: #f44336;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.logout-btn:hover {
    background-color: #c82333;
}

.delete-btn {
    padding: 8px 16px;
    background-color: #f44336;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.delete-btn:hover {
    background-color: #c82333;
}


.primary-btn {
    padding: 8px 16px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.primary-btn:hover {
    background-color: #0056b3;
}

.patch-btn {
    padding: 8px 16px;
    background-color: #8400ff;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.patch-btn:hover {
    background-color: #6600ff;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
}

th, td {
    border: 1px solid #ccc;
    padding: 6px 10px;
    text-align: left;
}

button {
    margin-right: 6px;
    padding: 4px 8px;
    cursor: pointer;
}

.btns-container {
    min-height: 76px;
    display: flex;
}

.status {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    display: inline-block;
    color: #fff;
}

.status--draft {
    background-color: #6c757d; /* серый */
}

.status--processing {
    background-color: #0d6efd; /* синий */
}

.status--done {
    background-color: #198754; /* зелёный */
}

.status--canceled {
    background-color: #dc3545; /* красный */
}
</style>
