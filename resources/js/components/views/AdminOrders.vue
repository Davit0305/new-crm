<template>
    <div class="moveToArchive">
    <h1>Перемещение заказов в архив</h1>
        <div class="description">Процедура переместит все записи с датой и временем заказа ранее указанных и статусом<br>из таблицы <i>orders</i> в таблицу <i>archive</i></div>
        <div class="beforeConsole" id="beforeConsole">
            <div class="label">Дата и время, раньше которых все заказы попадут в архив</div>
            <div class="action">
                <div>
                    <date-picker v-model="time1" valueType="format"></date-picker>
                </div>
                <div class="button move" @click="moveToArchive(this)" id="moveOrdersToArchive">
                    <span class="text">Переместить</span>
                </div>
            </div>
        </div>

    </div>
</template>

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
        moveToArchive(e) {
            if(confirm('Продолжить перемещение ?')) {
                axios.post('/api/admin_orders',
                    {
                        date: this.time1,
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
.moveToArchive {
    max-width: 600px;
    margin: 0 auto;
}
.moveToArchive h1 {
    font-weight: bold;
    text-align: center;
    margin: 10px 0;
    font-size: 22px;
}
.moveToArchive .description {
    text-align: center;
    margin-bottom: 50px;
    font-size: 12px;
    line-height: 1.5;
}
.moveToArchive .label {
    margin: 30px auto 5px;
    width: 400px;
}
.moveToArchive .action {
    display: flex;
    flex-direction: column;
    width: 400px;
    margin: auto;
    height: 120px;
}
.moveToArchive .action .button:hover {
    text-decoration: underline;
}
.moveToArchive .action .button {
    width: 400px;
    margin-top: 0px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    background: #58c174;
    color: #fff;
    border: none;
    text-decoration: none;
}
.moveToArchive .action > * {
    flex: 1;
}
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
