<template>
    <div class="adminBlocks">

        <div class="orderBlock block">
            <div class="title">Заказы</div>
            <div class="info">
            </div>
            <div class="subMenu">
                <router-link to="/admin_orders" title="Переместить заказы в архив">
                   Переместить заказы в архив
                </router-link>
            </div>
        </div>
        <div class="orderBlock block">
            <div class="title">Настройки системы</div>
            <div class="info">
            </div>
            <div class="subMenu">
                <router-link to="/settings" title="Посмотреть / изменить настройки">
                Посмотреть / изменить настройки
                </router-link>
            </div>
        </div>
        <div class="orderBlock block">
            <div class="title">Опоздания</div>
            <div class="info">
            </div>
            <div class="subMenu"
                 style="display: flex; flex-wrap: nowrap; justify-content: space-around;" >
                <a id="detailDelayReport" @click="sendMailMonth1" href="#">Детализация</a>
                <a id="totalPenaltyOffice" @click="sendMailMonth3" href="#">Приказ(офис)</a>
                <a id="totalPenaltyShop" @click="sendMailMonth10" href="#">Приказы(магазины)</a>
            </div>
        </div>
        <div class="orderBlock block">
            <div class="title">Тарифная сетка курьеров</div>
            <div class="info">
            </div>
            <div class="subMenu">
                <router-link to="/couriers" title="Посмотреть / изменить настройки">
                    Изменить
                </router-link>
            </div>
        </div>
        <div class="orderBlock block">
            <div class="title">Уведомления</div>
            <div class="info">
            </div>
            <div class="subMenu">
                <router-link to="/messages" title="Посмотреть / изменить настройки">
                    Отправка сообщений
                </router-link>
                <router-link to="/push_test" title="Посмотреть / изменить настройки">
                   Тест пуш-уведомлений
                </router-link>
            </div>
        </div>
    </div>
</template>
<style>
.adminBlocks {
    display: flex;
    justify-content: space-between;
    align-items: stretch;
    flex-wrap: wrap;
    max-width: 900px;
    margin: 0 auto;
}
.adminBlocks .block > .title {
    padding: 0 0 5px 0;
    text-decoration: none;
    color: #2a93cf;
    font-size: 13px;
    font-weight: bold;
    text-align: center;
    height: 20px;
}
.adminBlocks .block > .subMenu {
    text-align: center;
    height: 32px;
    margin: 20px 0 0 0;
}
.adminBlocks .block > .info {
    flex: 1;
    display: flex;
    justify-content: space-around;
    align-items: stretch;
}
.adminBlocks .block .subMenu a {
    display: inline-block;
    padding: 0 20px;
    line-height: 31px;
    height: 32px;
    cursor: pointer;
    background: #58c174;
    color: #fff;
    border: none;
    text-decoration: none;
}
.adminBlocks .block {
    display: flex;
    flex-direction: column;
    width: 46%;
    padding: 10px;
    outline: 1px solid #dddddd;
    text-decoration: none;
    margin-bottom: 35px;
}
.adminBlocks .block > .title {
    padding: 0 0 5px 0;
    text-decoration: none;
    color: #2a93cf;
    font-size: 13px;
    font-weight: bold;
    text-align: center;
    height: 20px;
}
</style>
<script>

import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';

export default {
    components: { DatePicker },
    data() {
        return {
            time1: null,
        };
    },
    methods: {
        sendMailMonth1(e) {
            if(confirm('Продолжить перемещение ?')) {
                axios.post('/api/send-mail1',
                    {
                        date:'',
                    }
                )
                    .then(res => {
                        window.swal.fire({
                            title: 'Успешно Перемещено!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000,
                            customClass: {
                                container: 'z-index-max'
                            }
                        });
                    })
                    .catch(err => {
                        this.ordersLoaded = true;
                        window.swal.fire({
                            title: 'Произошла ошибка!',
                            icon: 'error',
                            customClass: {
                                container: 'z-index-max',
                            }
                        })
                    })
            }
        },
        sendMailMonth3(e) {
            if(confirm('Продолжить перемещение ?')) {
                axios.post('/api/send-mail3',
                    {
                        date:'',
                    }
                )
                    .then(res => {
                        window.swal.fire({
                            title: 'Успешно Перемещено!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000,
                            customClass: {
                                container: 'z-index-max'
                            }
                        });
                    })
                    .catch(err => {
                        this.ordersLoaded = true;
                        window.swal.fire({
                            title: 'Произошла ошибка!',
                            icon: 'error',
                            customClass: {
                                container: 'z-index-max',
                            }
                        })
                    })
            }
        },
        sendMailMonth10(e) {
            if(confirm('Продолжить перемещение ?')) {
                axios.post('/api/send-mail10',
                    {
                        date:'',
                    }
                )
                    .then(res => {
                        window.swal.fire({
                            title: 'Успешно Перемещено!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000,
                            customClass: {
                                container: 'z-index-max'
                            }
                        });
                    })
                    .catch(err => {
                        this.ordersLoaded = true;
                        window.swal.fire({
                            title: 'Произошла ошибка!',
                            icon: 'error',
                            customClass: {
                                container: 'z-index-max',
                            }
                        })
                    })
            }
        },
    }
};
</script>

<style scoped>
label select {
    border: none;
}

.link_to_cart {
    background: white url('../images/save.png') no-repeat top;
    border: 0;
    cursor: pointer;
    display: block;
    height: 24px;
    margin-left: 1px;
    margin-top: 1px;
    width: 24px;
}

.phoneInSot {
    border: 1px solid transparent;
    float: left;
    padding: 2px;
    text-align: center;
}

.auto {
    text-align: center;
    border-top-style: hidden;
    border-right-style: hidden;
    border-left-style: hidden;
    border-bottom-style: hidden;
}

.no-outline:focus {
    outline: none;
}

.add-raion {
    background-color: #f3f3f3;
    border: 1px solid #a6b7c7;
    box-shadow: 0 0 50px rgb(50 50 50 / 80%);
    margin-left: 0;
    margin-top: -15px;
    padding: 20px 15px 15px 15px;
    position: absolute;
    width: 260px;
    z-index: 9;
}

.add-city {
    background-color: #f3f3f3;
    border: 1px solid #a6b7c7;
    box-shadow: 0 0 50px rgb(50 50 50 / 80%);
    margin-left: 0;
    margin-top: -15px;
    padding: 20px 15px 15px 15px;
    position: absolute;
    width: 260px;
    z-index: 9;
}

.btn-plus {
    font-size: 10px;
    border: 1px solid #63809b;
    color: #63809b;
    margin-left: 5px;
    padding: 1px 3px;
    cursor: pointer;
}

.head-td {
    display: flex;
    justify-content: center;
    align-items: center;
}

.btn-plus:hover {
    color: #fafafa;
    background-color: #63809b;
}
</style>
