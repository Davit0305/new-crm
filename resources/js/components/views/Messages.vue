<template>
    <div class="adminBlocks">
        <div class="menu">
            <ul class="head-name">
                <li class="active">
                    <router-link to="/messages" title="Отправка сообщений">
                        Отправка сообщений
                    </router-link>
                </li>
            </ul>
        </div>
        <div class="breadcrumbs">
            <ul>
                <li>
                    <router-link to="/admin" title="Переместить заказы в архив">
                        Администрирование
                    </router-link>
                </li>
                <li class="devide">·</li>
                <li>Уведомления</li>
                <li class="devide">·</li>
                <li>Отправка сообщений</li>
            </ul>
        </div>
        <div class="body">
            <form id="noti_messages">

                <div id="container">
                    <div class="bort">
                        <span style="background: #fafafa;">Курьеры</span>
                        <div class="noti-type-once">
                            <div class="noti-name"></div>
                            <div class="noti-inputs">
                                <div class="input-once">SMS</div>
                                <div class="input-once">WhatsApp</div>
                                <div class="input-once">Telegram</div>
                                <div class="input-once">Не отправлять</div>
                            </div>
                        </div>
                        <div class="noti-type-once" v-for="(courier, index) in couriers">
                            <div class="noti-name">
                                {{ courier.name }}                       </div>
                            <div class="noti-inputs">
                                <div class="input-once"><input :name="courier.slug" type="radio" v-model="courier.noti_type" checked="" value="sms"></div>
                                <div class="input-once"><input :name="courier.slug" type="radio" v-model="courier.noti_type" value="whats_app"></div>
                                <div class="input-once"><input :name="courier.slug" type="radio" v-model="courier.noti_type" value="telegram"></div>
                                <div class="input-once" v-if="(courier.slug != 'couriers_auth')"><input :name="courier.slug" type="radio" v-model="courier.noti_type" checked="" value="mute"></div>
                            </div>
                        </div>
                    </div>

                    <div class="bort" style="margin-top:25px;">
                        <span style="background: #fafafa;">Интернет-магазины</span>
                        <div class="noti-type-once">
                            <div class="noti-name"></div>
                            <div class="noti-inputs">
                                <div class="input-once">SMS</div>
                                <div class="input-once">WhatsApp</div>
                                <div class="input-once">Telegram</div>
                                <div class="input-once">Не отправлять</div>
                            </div>
                        </div>
                        <div class="noti-type-once" v-for="(item, index) in internet">
                            <div class="noti-name">{{ item.name}}</div>
                            <div class="noti-inputs">
                                <div class="input-once"><input  v-bind:name="item.slug" v-model="item.noti_type" type="radio" value="sms"></div>
                                <div class="input-once"><input  v-bind:name="item.slug" v-model="item.noti_type" type="radio" value="whats_app"></div>
                                <div class="input-once"><input  v-bind:name="item.slug" v-model="item.noti_type" type="radio" value="telegram"></div>
                                <div class="input-once"><input  v-bind:name="item.slug" v-model="item.noti_type" type="radio" checked="" value="mute"></div>
                            </div>
                        </div>
                    </div>

                    <div class="bort" style="margin-top:25px;">
                        <span style="background: #fafafa;">Торговые точки</span>
                        <div class="noti-type-once">
                            <div class="noti-name"></div>
                            <div class="noti-inputs">
                                <div class="input-once">SMS</div>
                                <div class="input-once">WhatsApp</div>
                                <div class="input-once">Telegram</div>
                                <div class="input-once">Не отправлять</div>
                            </div>
                        </div>
                        <div class="noti-type-once" v-for="(shop, index) in shops">
                            <div class="noti-name">{{ shop.name }}</div>
                            <div class="noti-inputs">
                                <div class="input-once"><input v-bind:name="shop.slug" v-model="shop.noti_type" type="radio" value="sms"></div>
                                <div class="input-once"><input v-bind:name="shop.slug" v-model="shop.noti_type" type="radio" value="whats_app"></div>
                                <div class="input-once"><input v-bind:name="shop.slug" v-model="shop.noti_type" type="radio" value="telegram"></div>
                                <div class="input-once"><input v-bind:name="shop.slug" v-model="shop.noti_type" type="radio" checked="" value="mute"></div>
                            </div>
                        </div>
                    </div>

                    <div class="actionLine">
                        <input type="button" name="submit" @click="saveNotiMessageSettings()" value="Сохранить" class="greenButton">
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            couriers: [],
            internet: [],
            shops: [],
        };
    },
    created() {
        this.getMessageNotiSettings();
    },

    methods: {
        saveNotiMessageSettings() {
            axios.post('/api/notifications/save',
                {
                    couriers: this.couriers,
                    internet: this.internet,
                    shops: this.shops,
                }
            )
                .then(res => {
                    console.log(res);
                    window.swal.fire({
                        title: 'Успешно Сохранено!',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1000,
                        customClass: {
                            container: 'z-index-max'
                        }
                    });

                })
                .catch(err => {
                    window.swal.fire({
                        title: 'Произошла ошибка!',
                        // text: err.response.data.message,
                        icon: 'error',
                        customClass: {
                            container: 'z-index-max',
                        }
                    })
                })
        },

        getMessageNotiSettings() {
            axios.get('/api/notifications')
                .then(res => {
                    let data = res.data;
                    for (let i = 0; i< data.length; i++){
                        console.log(data[i]);

                        if(data[i]['type'] == 'couriers' ){
                            console.log(data[i]);
                            this.couriers.push(data[i])
                        }
                        if(data[i]['type'] == 'internet' ){
                            this.internet.push(data[i])
                        }
                        if(data[i]['type'] == 'shops' ){
                            this.shops.push(data[i])
                        }
                    }
                    // this.items = data;
                })
                .catch(err => {
                    window.swal.fire({
                        title: 'Произошла ошибка!',
                        // text: err.response.data.message,
                        icon: 'error',
                        customClass: {
                            container: 'z-index-max',
                        }
                    })
                })
        },
    }
};
</script>

<style scoped>
.menu ul li.active {
    background: #eff5fd;
}

.menu {
    width: 100%;
    background: #e3ecf7;
}

.head-name {
    display: flex;
    justify-content: center;
}

ul {
    display: block;
    list-style-type: disc;
    margin-block-start: 1em;
    margin-block-end: 1em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    padding-inline-start: 40px;
}

.menu ul li {
    display: inline-block;
    padding: 0 15px;
}

.menu ul li a {
    line-height: 50px;
    display: inline-block;
    font-size: 14px;
    text-decoration: none;
}

.menu ul li.active a {
    color: #689ab7;
}

body .cont {
    margin: 0 !important;
    width: 100% !important;
    padding: 0 !important;
    display: block !important;
    max-width: 100% !important;
}

.breadcrumbs {
    width: 100%;
}

.breadcrumbs ul {
    max-width: 900px;
    margin: 0 auto;
    padding: 15px 0;
    list-style: none;
}

.breadcrumbs ul li {
    display: inline-block;
}

.breadcrumbs ul li.devide {
    margin: 0 10px;
}

.actionLine {
    text-align: center;
    padding: 20px 0;
}

.greenButton {
    display: inline-block;
    padding: 0 20px;
    line-height: 32px;
    border: none;
    background: #58c174;
    color: #fff;
    text-decoration: none;
    cursor: pointer;
    transition: background ease .2s;
}

.body {
    padding-top: 20px;
    max-width: 900px;
}

.bort {
    border: 1px solid #a6b7c7;
    padding: 10px 5px 5px 5px;
}


.bort span {
    background: #f3f3f3;
    float: left;
    margin-top: -20px;
    padding: 1px;
}
.noti-type-once {
    display: flex;
    margin: 10px;
}
.noti-type-once .noti-name {
    width: 100px;
    font-size: 12px;
}

.noti-type-once .noti-inputs {
    display: flex;
}
.input-once {
    width: 100px;
    text-align: center;
}
.input-once input {
    float: unset !important;
    width: 15px;
    height: 15px;
}
</style>
