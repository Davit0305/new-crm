<template>
    <div class="adminBlocks">
        <div class="menu">
            <ul class="head-name">
                <li class="active">
                    <router-link to="/push_test" title="Тест пуш-уведомлений">
                        Тест пуш-уведомлений
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
                <li>Тест пуш-уведомлений</li>
            </ul>
        </div>
        <div class="body">

            <form id="test_noti_push">
                <div id="container">
                    <select name="push_token" v-model="selectedUser" v-for="(user, index) in users" class="sel_date_search" style="width: 140px;height: 27px;">
                        <option value="0">--</option>
                        <option :value="user">{{ user.surname + ' ' + user.name}}</option>
                    </select>
                    <input name="push_title" v-model="push_title" placeholder="Заголовок" style="width: 140px;height: 25px;" type="text">
                    <input name="push_desc" v-model="push_desc" placeholder="Описание" style="width: 140px;height: 25px;" type="text">
                    <div class="actionLine">
                        <input type="button" name="submit" @click="sendTestPush(user, push_title,push_desc)" value="Отправить" class="greenButton">
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
            users: [],
            selectedUser: null,
            push_title: '',
            push_desc: '',
        };
    },
    created() {
        this.getMessageNotiSettings();
    },

    methods: {
        sendTestPush() {
            if (this.selectedUser == null) {
                window.swal.fire({
                    title: 'Выберите Пользователя!',
                    text: '',
                    icon: 'error',
                    customClass: {
                        container: 'z-index-max',
                    },
                });
                return;
            }
            axios.post('/api/notifications/send_push',
                {
                    push_title: this.push_title,
                    push_desc: this.push_desc,
                    user: this.selectedUser,
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
            axios.get('/api/notifications/get_push_user')
                .then(res => {
                    this.users = res.data;
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

#add_new_item {
    width: 112px;
    border-radius: unset;
    border: none;
    background-color: #88d1eb;
    color: #fafafa;
    font: 11px/14px Verdana, Arial, sans-serif;
}

.bort {
    border: 1px solid #a6b7c7;
    padding: 10px 5px 5px 5px;
}

.distance {
    height: 19px;
    margin-left: 3px;
    padding: 2px;
    text-align: center;
    width: 50px;
}

.distance_summa {
    height: 19px;
    margin-left: 3px;
    padding: 2px;
    text-align: center;
    width: 50px;
}
.distance.error{
    border: 1px solid #f50d0d !important;
}
.remove {
    text-decoration: none;
    float: right;
    margin-top: 5px;
    margin-right: 10px;
    cursor: pointer;
}

.bort span {
    background: #f3f3f3;
    float: left;
    margin-top: -20px;
    padding: 1px;
}
</style>
