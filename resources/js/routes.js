import Orders from './components/views/Orders.vue';
import Login from './components/views/Login.vue';
import Workers from './components/views/Workers.vue';
import Tasks from './components/views/Tasks.vue';
import Drivers from './components/views/Drivers.vue';
import Admin from './components/views/Admin.vue';
import Documents from './components/views/Documents.vue';
import Inventories from './components/views/Inventories.vue';
import Operators from './components/views/Operators.vue';
import Points from './components/views/Points.vue';
import Reports from './components/views/Reports.vue';
import Sims from './components/views/Sims.vue';
import AdminOrders from "./components/views/AdminOrders";
import Settings from "./components/views/Settings";
import Couriers from "./components/views/Couriers";
import Messages from "./components/views/Messages";
import PushTest from "./components/views/PushTest";
export const routes = [
    {
        name: 'login',
        path: '/login',
        meta: { title: 'Авторизация' },
        component: Login
    },
    {
        name: 'orders',
        path: '/',
        meta: { title: 'Заказы' },
        component: Orders
    },
    {
        name: 'workers',
        path: '/workers',
        meta: { title: 'Сотрудники' },
        component: Workers
    },
    {
        name: 'tasks',
        path: '/tasks',
        meta: { title: 'Задания курьерам' },
        component: Tasks
    },
    {
        name: 'drivers',
        path: '/drivers',
        meta: { title: 'Курьеры' },
        component: Drivers
    },
    {
        name: 'admin',
        path: '/admin',
        meta: { title: 'Администрирование' },
        component: Admin
    },
    {
        name: 'documents',
        path: '/documents',
        meta: { title: 'Документы' },
        component: Documents
    },
    {
        name: 'inventories',
        path: '/inventories',
        meta: { title: 'Товарно-материальные ценности' },
        component: Inventories
    },
    {
        name: 'operators',
        path: '/operators',
        meta: { title: 'Диспетчера' },
        component: Operators
    },
    {
        name: 'points',
        path: '/points',
        meta: { title: 'Торговые точки' },
        component: Points
    },
    {
        name: 'reports',
        path: '/reports',
        meta: { title: 'Отчёты' },
        component: Reports
    },
    {
        name: 'sims',
        path: '/sims',
        meta: { title: 'Сим-карты' },
        component: Sims
    },
    {
        name: 'AdminOrders',
        path: '/admin_orders',
        meta: { title: 'заказов в архив' },
        component: AdminOrders
    },
    {
        name: 'Settings',
        path: '/settings',
        meta: { title: 'Настройки системы' },
        component: Settings
    },
    {
        name: 'Couriers',
        path: '/couriers',
        meta: { title: 'Тарифная сетка курьеров' },
        component: Couriers
    },
    {
        name: 'Messages',
        path: '/messages',
        meta: { title: 'Отправка сообщений' },
        component: Messages
    },
    {
        name: 'PushTest',
        path: '/push_test',
        meta: { title: 'Тест пуш-уведомлений' },
        component: PushTest
    },
];
