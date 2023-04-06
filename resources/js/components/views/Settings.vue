<template>
    <div class="adminBlocks">
        <div class="menu">
            <ul class="head-name">
                <li class="active">
                    <router-link to="/settings" title="Настройки системы">
                        <a href="javascript:void(0)">Настройки системы</a>
                    </router-link>
                </li>
            </ul>
        </div>
        <div class="breadcrumbs">
            <ul>
                <li>
                    <router-link to="/admin" title="Переместить заказы в архив">
                        <a href="javascript:void(0)">Администрирование</a>
                    </router-link>
                </li>
                <li class="devide">·</li>
                <li>Настройки системы</li>
            </ul>
        </div>
        <div class="body">
            <div v-for="(setting, index) in settings">
                <p>
                    <label v-if="setting.value == 1 || setting.value == 0">
                        <input type="checkbox" v-model="setting.value" :true-value="1" :false-value="0">
                        {{ setting.name }}
                    </label>
                    <label v-else>
                        {{ setting.name }}
                        <input type="text" v-model="setting.value">
                    </label>
                </p>
            </div>
            <div class="actionLine">
                <input type="submit" name="submit" @click="saveSettings(settings)" value="Сохранить настройки"
                       class="greenButton">
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            settings: [],
        };
    },
    created() {
        $('#container').attr('class', 'cont')
        this.getSettings();
    },
    methods: {
        getSettings() {
            axios.get('/api/settings')
                .then(res => {
                    console.log(res);
                    this.settings = res.data;
                })
                .catch(err => {
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
        saveSettings(e) {
                axios.post('/api/settings/save',
                    {
                        data: this.settings,
                    }
                )
                    .then(res => {
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
                        this.ordersLoaded = true;
                        window.swal.fire({
                            title: 'Произошла ошибка!',
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
</style>
