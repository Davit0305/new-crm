<template>
    <div>
        <div v-if="this.$route.name !== 'login'">
            <div v-if="this.user !== ''">
                <div class="helo"><strong>{{ user.fio }}</strong></div>
                <div class="helo" style="margin-top:25px;"><a class="logout" @click="logout()">Выход</a></div>
            </div>

            <div id="pan">
                <router-link to="/" title="Заказы">
                    <img src="/icons/home.png" width="24" height="24">
                </router-link>
                <router-link to="/tasks" title="Задания курьеров">
                    <img src="/icons/bar.png" width="24" height="24">
                </router-link>
                <router-link to="/workers" title="Сотрудники">
                    <img src="/icons/sot.jpg" width="24" height="24">
                </router-link>
                <router-link to="/drivers" title="Курьеры">
                    <img src="/icons/drv.png" width="24" height="24">
                </router-link>
                <router-link to="/inventories" title="Товарно-материальные ценности">
                    <img src="/icons/tmc.png" width="24" height="24">
                </router-link>
                <router-link to="/sims" title="Сим-карты">
                    <img src="/icons/sim.png" width="24" height="24">
                </router-link>
                <router-link to="/operators" title="Диспетчера">
                    <img src="/icons/dis.png" width="24" height="24">
                </router-link>
                <router-link to="/points" title="Торговые точки">
                    <img src="/icons/toc.png" width="24" height="24">
                </router-link>
                <router-link to="/documents" title="Документы">
                    <img src="/icons/prz.png" width="24" height="24">
                </router-link>
                <router-link to="/reports" title="Отчёты">
                    <img src="/icons/otc.png" width="24" height="24">
                </router-link>
                <router-link to="/admin" title="Администрирование">
                    <img src="/icons/admin.png" width="24" height="24">
                </router-link>
            </div>
        </div>

        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    export default {
        name: "App",
        data: function () {
            return {
                user: '',
            };
        },
        created() {
            if(this.user === '') {
                if (this.$store.getters.GET_USER != null) {
                    this.user = this.$store.getters.GET_USER;
                } else {
                    this.getUser();
                }
            }
            // const oktell_user = {
            //     username: "301",
            //     password: "7ieFsfH0YUIv9Jpt",
            //     domain: "ooossa.cloud.oktell.studio",
            //     server: "https://cloud.oktell.studio/",
            // };
            // OKTELLPhone.authorize(oktell_user);
        },
        watch: {
            $route() {
                console.log(this.$route);
            },
        },
        methods: {
            logout() {
                axios.get('/sanctum/csrf-cookie').then(response => {
                    axios.post('/logout')
                        .then(r => {
                            this.$router.push({name: 'login'});
                        })
                        .catch(err => {
                            this.ordersLoaded = true;
                            console.log(err.response);
                            window.swal.fire({
                                title: 'Произошла ошибка!',
                                text: err.response.data.message,
                                icon: 'error',
                                customClass: {
                                    container: 'z-index-max',
                                }
                            })
                        })
                })
            },
            getUser() {
                axios.post('/api/getUser')
                    .then(res => {
                        this.user = res.data;
                        this.$store.commit('SET_USER', this.user);
                    })
                    .catch(err => {
                        this.ordersLoaded = true;
                        console.log(err.response);
                        window.swal.fire({
                            title: 'Произошла ошибка!',
                            text: err.response.data.message,
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

<style>
    body {
        background: #fafafa;
        color: #333;
        font: 11px/14px Verdana,Arial,sans-serif;
    }
    #pan {
        width: max-content;
        margin: 20px auto;
        position: relative;
        z-index: 2;
    }
    #pan a {
        opacity: 0.5;
        margin: 0 4px;
        width: 24px;
        height: 24px;
        display: inline-block;
    }
    .helo {
        margin: 10px;
        position: absolute;
        right: 0;
        top: -20px;
    }
</style>
