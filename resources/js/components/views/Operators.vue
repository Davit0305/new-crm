<template>
    <div>
        <div id="header">
            <div class="login">
                <form class="mainform" id="search">
                    <div class="inner2">
                        <div>
                            <label for="addUser">Добавить или найти диспетчера</label>
                            <input v-model="filter.search" type="text" id="addUser" name="SearchNom" size="10"
                                   class="text add-point">
                        </div>
                        <div style="display: flex;flex-wrap: nowrap;justify-content: space-evenly;">
                            <div>
                                <input @click="addNewUser()" type="button" class="button" value="Добавить">
                            </div>
                            <div>
                                <input @click="saveFilter()" type="button" class="button" value="Найти">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="id_user my-5">
            <div v-if="usersLoaded === false" id="preloader"></div>
            <table v-else id="maintable" border="0" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <td style="border:none; font-size:12px; position: relative;" colspan="13">
                        <div style="margin: -15px 0 -15px 0;">
                                <span style="position: absolute;bottom: 2px;">
                                    <span class="countOrders">{{ sortedUsers.length }} из {{
                                            users.length
                                        }} диспетчера</span>
                                    <span v-if="pageSize > 1" class="pagelinks">
                                        <span style="margin-right: 10px;">|</span>
                                        Страницы:
                                        <select v-model="currentPage" name="_pageSize">
                                            <option v-for="pageNumber in pageSize" :selected="currentPage == pageNumber"
                                                    :value="pageNumber">{{ pageNumber }}</option>
                                        </select>
                                    </span>
                                </span>
                            <span class="pageSize">
                                    на странице
                                    <select v-model="countPage" name="_pageSize">
                                        <option :selected="countPage === 50" value="50">50</option>
                                        <option :selected="countPage === 70" value="70">70</option>
                                        <option :selected="countPage === 100" value="100">100</option>
                                    </select>
                                </span>
                        </div>
                    </td>
                </tr>
                <tr class="maintr">
                    <td style="width:45px"><h3>№</h3></td>
                    <td style="width:318px"><h3>Сотрудник</h3></td>
                    <td><h3>Логин</h3></td>
                    <td><h3>Пароль</h3></td>
                    <td><h3>Активен (статус)</h3></td>
                    <td><h3>Диспетчер (статус)</h3></td>
                    <td><h3>Показывать свободные заказы</h3></td>
                    <td title="Если <Диспетчер> то отображается фото на сайте(до 4х диспетчеров)"><h3>На сайте
                        (статус)</h3></td>
                    <td title="Если <Диспетчер> то видим раздел заказов только для чтения"><h3>Бухгалтер (статус)</h3>
                    </td>
                    <td><h3>Авто диспетчер (статус)</h3></td>
                    <td><h3>Админ (статус)</h3></td>
                    <td><h3>Диспетчер (раздел)</h3></td>
                    <td><h3>Номера (раздел)</h3></td>
                    <td><h3>Аренда (раздел)</h3></td>
                    <td><h3>ТМЦ<br>(раздел)</h3></td>
                    <td><h3>Приказы (раздел)</h3></td>
                    <td><h3>Точки (раздел)</h3></td>
                    <td><h3>Курьеры (раздел)</h3></td>
                    <td><h3>Сотрудники (раздел)</h3></td>
                    <td><h3>Отчеты (раздел)</h3></td>
                    <td><h3>Курьеры (редакт.)</h3></td>
                    <td><h3>Цветы (раздел)</h3></td>
                    <td><h3>Букеты (раздел)</h3></td>
                    <td><h3>Задания курьерам (раздел)</h3></td>
                    <td><h3>Вакансии (раздел)</h3></td>
                    <td class="tsave"></td>
                    <td class="tdelete"></td>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(user, k) in sortedUsers"
                    :title="'Последняя активность: ' + user.online">
                    <td @change="onChange(user.id)" class="text-center"><label>{{ user.id }}</label></td>
                    <td>
                        <label class="sotNameLabel" style="min-width:225px !important;height:28px;">
                            <select class="operator" v-model="user.worker_id" @change="onChange(user.id)"
                                    style="width:100%;height:100%"
                                    name="userInfo">
                                <option v-for="worker in workers" v-bind:value="worker.id">
                                    {{ worker.fio }}</option>
                            </select>
                        </label>
                    </td>
                    <td>
                        <input class="auto no-outline" @change="onChange(user.id)" v-model="user.login">
                    </td>
                    <td>
                        <input class="auto no-outline" :id="'pass'+user.id" @change="onChange(user.id)">
                    </td>
                    <!--                    Активен ли диспетчер-->
                    <td style="width: 47px;">
                        <div style="display: flex;width:41px;">
                            <label class="sotNameLabel"><input type="checkbox" @change="onChange(user.id)"
                                                               v-model="user.enabled_dis"></label> <span>Да</span>
                        </div>
                    </td>
                    <!--                    Показывать свободные заказы-->
                    <td style="width: 47px;">
                        <div style="display: flex;width:41px;">
                            <label class="sotNameLabel"><input type="checkbox" @change="onChange(user.id)"
                                                               v-model="user.r_den"></label> <span>Да</span>
                        </div>
                    </td>

                    <!--                    Доступ к главному разделу-->
                    <td style="width: 47px;">
                        <div style="display: flex;width:41px;">
                            <label class="sotNameLabel"><input type="checkbox" @change="onChange(user.id)"
                                                               v-model="user.r_hfo"></label> <span>Да</span>
                        </div>

                    </td>
                    <!--                    Бухгалтер ли диспетчер-->
                    <td style="width: 47px;">
                        <div style="display: flex;width:41px;">
                            <label class="sotNameLabel"><input type="checkbox" @change="onChange(user.id)"
                                                               v-model="user.r_shw"></label> <span>Да</span>
                        </div>

                    </td>
                    <!--                    Автодиспетчер диспетчер-->
                    <td style="width: 47px;">
                        <div style="display: flex;width:41px;">
                            <label class="sotNameLabel"><input type="checkbox" @change="onChange(user.id)"
                                                               v-model="user.r_buh"></label> <span>Да</span>
                        </div>

                    </td>
                    <!--                    Показывать ли диспетчера на сайте-->
                    <td style="width: 47px;">
                        <div style="display: flex;width:41px;">
                            <label class="sotNameLabel"><input type="checkbox" @change="onChange(user.id)"
                                                               v-model="user.r_aut"></label> <span>Да</span>
                        </div>

                    </td>
                    <!--                    Доступ администратора-->
                    <td style="width: 47px;">
                        <div style="display: flex;width:41px;">
                            <label class="sotNameLabel"><input type="checkbox" @change="onChange(user.id)"
                                                               v-model="user.admin"></label> <span>Да</span>
                        </div>

                    </td>
                    <!--                    Доступ к разделу Диспетчера-->
                    <td style="width: 47px;">
                        <div style="display: flex;width:41px;">
                            <label class="sotNameLabel"><input type="checkbox" @change="onChange(user.id)"
                                                               v-model="user.r_dis"></label> <span>Да</span>
                        </div>

                    </td>
                    <!--                    Доступ к разделу Диспетчера-->
                    <td style="width: 47px;">
                        <div style="display: flex;width:41px;">
                            <label class="sotNameLabel"><input type="checkbox" @change="onChange(user.id)"
                                                               v-model="user.r_nom"></label> <span>Да</span>
                        </div>

                    </td>
                    <!--                    Доступ к разделу аренда-->
                    <td style="width: 47px;">
                        <div style="display: flex;width:41px;">
                            <label class="sotNameLabel"><input type="checkbox" @change="onChange(user.id)"
                                                               v-model="user.r_are"></label> <span>Да</span>
                        </div>

                    </td>

                    <!--                    Доступ к разделу ТМЦ-->
                    <td style="width: 47px;">
                        <div style="display: flex;width:41px;">
                            <label class="sotNameLabel"><input type="checkbox" @change="onChange(user.id)"
                                                               v-model="user.r_tmc"></label> <span>Да</span>
                        </div>

                    </td>
                    <!--                    Доступ к разделу Приказы-->
                    <td style="width: 47px;">
                        <div style="display: flex;width:41px;">
                            <label class="sotNameLabel"><input type="checkbox" @change="onChange(user.id)"
                                                               v-model="user.r_prz"></label> <span>Да</span>
                        </div>

                    </td>
                    <!--                    Доступ к разделу точки-->
                    <td style="width: 47px;">
                        <div style="display: flex;width:41px;">
                            <label class="sotNameLabel"><input type="checkbox" @change="onChange(user.id)"
                                                               v-model="user.r_toc"></label> <span>Да</span>
                        </div>

                    </td>
                    <!--                    Доступ к разделу курьеры-->
                    <td style="width: 47px;">
                        <div style="display: flex;width:41px;">
                            <label class="sotNameLabel"><input type="checkbox" @change="onChange(user.id)"
                                                               v-model="user.r_kur"></label> <span>Да</span>
                        </div>

                    </td>
                    <!--                    Доступ к разделу сотрудники-->
                    <td style="width: 47px;">
                        <div style="display: flex;width:41px;">
                            <label class="sotNameLabel"><input type="checkbox" @change="onChange(user.id)"
                                                               v-model="user.r_sot"></label> <span>Да</span>
                        </div>

                    </td>
                    <!--                    Доступ к разделу отчеты-->
                    <td style="width: 47px;">
                        <div style="display: flex;width:41px;">
                            <label class="sotNameLabel"><input type="checkbox" @change="onChange(user.id)"
                                                               v-model="user.r_otc"></label> <span>Да</span>
                        </div>

                    </td>
                    <!--                    назначение курьеров-->
                    <td style="width: 47px;">
                        <div style="display: flex;width:41px;">
                            <label class="sotNameLabel"><input type="checkbox" @change="onChange(user.id)"
                                                               v-model="user.r_nku"></label> <span>Да</span>
                        </div>

                    </td>
                    <!--                    Доступ к разделу цветы-->
                    <td style="width: 47px;">
                        <div style="display: flex;width:41px;">
                            <label class="sotNameLabel"><input type="checkbox" @change="onChange(user.id)"
                                                               v-model="user.r_buk"></label> <span>Да</span>
                        </div>

                    </td>
                    <!--                    Доступ к разделу букеты-->
                    <td style="width: 47px;">
                        <div style="display: flex;width:41px;">
                            <label class="sotNameLabel"><input type="checkbox" @change="onChange(user.id)"
                                                               v-model="user.r_adb"></label> <span>Да</span>
                        </div>

                    </td>
                    <!--                    Доступ к разделу задания курьерам-->
                    <td style="width: 47px;">
                        <div style="display: flex;width:41px;">
                            <label class="sotNameLabel"><input type="checkbox" @change="onChange(user.id)"
                                                               v-model="user.r_bar"></label> <span>Да</span>
                        </div>

                    </td>

                    <td style="width: 47px;">
                        <div style="display: flex;width:41px;">
                            <label class="sotNameLabel"><input type="checkbox" @change="onChange(user.id)"
                                                               v-model="user.r_vak"></label> <span>Да</span>
                        </div>

                    </td>
                    <td class="tsave">
                        <i type="submit" @click="saveUser(user)" :id="'save_botton_' + user.id"
                           title="Сохранить" class="fa fa-floppy-o" style="display: none" aria-hidden="true"></i>
                    </td>
                    <td class="tsave">
                        <i @click="deleteUser(user,user.id)" :id="'delete_botton_' + user.id"
                           title="Удалить" class="fa fa-ban" aria-hidden="true"></i>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<style>
.helo {
    top: 0px !important;
}

.container {
    display: flex;
    flex-direction: row-reverse;
    flex-wrap: wrap;
    justify-content: center;
}
</style>
<script>

export default {
    name: "users",
    data() {
        return {
            inputValue: '',
            showAddCity: false,
            filter: {
                search: this.$cookies.get("usersFilter_search") ? this.$cookies.get("usersFilter_search") : ''
            },
            used_component: '',
            component_method: '',
            component_data: '',
            users: [],
            raiony: [],
            workers: [],
            usersLoaded: false,
            issetFilter: false,
            pageSize: 1,
            countPage: 50,
            currentPage: 1,
        }
    },
    created() {
        this.clearFilterCookie();
        this.getWorkers()
        this.getUsers(true);
        this.$cookies.get("usersFilter_search") ? this.$cookies.get("usersFilter_search") : ''
    },
    methods: {
        myEvent(e) {
            if (!this.$refs.city_wrap.contains(e.target)) {
                this.showAddCity = false;
            }
        },
        onChange(id) {
            $('#save_botton_' + id).show();
        },
        showSaveButton(id, phone) {
            $('.fa-floppy-o').hide();
            if (phone.length === 10) {
                $('#save_botton_' + id).show();
            }
        },
        saveUser(user) {
            let pass = $('#pass'+user.id).val();
            axios.post('/api/dispatcher/save',
                {
                    pass:pass,
                    user: user
                }
            )
                .then(res => {
                    $('#save_botton_' + user.id).hide();
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
                        text: err.response.data.message,
                        icon: 'error',
                        customClass: {
                            container: 'z-index-max',
                        }
                    })
                })
        },
        deleteUser(user, id) {
            if (confirm('Удалить ?')) {
                axios.post('/api/dispatcher/delete',
                    {
                        id: id,
                    }
                )
                    .then(res => {
                        let index = this.users.indexOf(user)
                        this.users.splice(index, 1);
                        window.swal.fire({
                            title: 'Успешно удалено!',
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
                            text: err.response.data.message,
                            icon: 'error',
                            customClass: {
                                container: 'z-index-max',
                            }
                        })
                    })
            }
        },
        clearFilter() {
            this.getUsers(true);
            this.$root.$emit('clear-point-search-form');
        },
        getSearchModal() {
            // this.used_component = 'PointsSearchModal';
            this.component_method = (clear) => this.getUsers(clear);
            $('.window-overlay').show();
            $('body').css('overflow', 'hidden');
        },

        getUsers(clear = false) {
            if (clear === true) {
                this.clearFilterCookie();
                this.issetFilter = false;
                this.filter.search = '';
            } else {
                let issetFilter = this.checkFilter();
                this.filter.search = this.$cookies.get("usersFilter_search") ? this.$cookies.get("usersFilter_search") : '';
                this.issetFilter = issetFilter === 1;
            }
            this.usersLoaded = false;
            axios.post('/api/dispatcher/get', {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            })
                .then(res => {
                    this.users = res.data;
                    this.usersLoaded = true;

                })
                .catch(err => {
                    this.ordersLoaded = true;
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
        getWorkers(){
            axios.post('/api/worker/getWorkers', {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            })
                .then(res => {
                    this.workers = res.data;
                })
                .catch(err => {
                })
        },
        clearFilterCookie() {
            this.$cookies.remove("usersFilter_search");
        },
        checkFilter() {
            let search = this.$cookies.get("usersFilter_search");
            if (
                search ||
                enabled ||
                job ||
                dep) {
                return 1;
            } else {
                return 0;
            }
        },
        addRaionView() {
            $(".add-raion").show();
        },
        addCityView() {
            $(".add-city").show();
        },
        showSotPhone(id) {
            $("#ldSotPhone_" + id).css("display", "none");
            $("#ldInSot_" + id).css("display", "inline");
            $(".link_to_cart").css("display", "none");
            $("#save_" + id).css("display", "inline");
        },
        addNewUser() {
            const addUser = document.getElementById("addUser").value;
            axios.post('/api/dispatcher/newUser',
                {
                    addUser: addUser,
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
                    window.location.reload();
                })
                .catch(err => {
                    this.ordersLoaded = true;
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
        saveFilter() {
            if (this.filter.search != '') {
                this.$cookies.set("usersFilter_search", this.filter.search);
            } else {
                this.$cookies.remove("usersFilter_search");
            }
            this.used_component = '';
            this.getUsers();
        },
        hideCity() {
            $(".add-city").hide();
        },
        hideRaion() {
            $(".add-raion").hide();
        },
        hideAddCity(event) {
            // Get a reference to the addCity element using the $refs object
            const addCity = this.$refs.addCity;

            // Check if the clicked element is inside the addCity element or not
            if (!addCity.contains(event.target)) {
                this.showAddCity = false;
            }
        },
    },
    watch: {
        'countPage': function (newVal, oldVal) {
            this.$cookies.set("countPage", newVal)
        },
        'currentPage': function (newVal, oldVal) {
            this.$cookies.set("currentPage", newVal)
        }
    },
    mounted() {
        document.addEventListener('click', this.myEvent);
    },
    beforeDestroy() {
        document.removeEventListener('click', this.myEvent);
    },
    computed: {
        sortedUsers: function () {
            if (this.usersLoaded === true) {
                this.pageSize = Math.ceil(this.users.length / this.countPage);
                if (this.currentPage > this.pageSize) {
                    this.currentPage = 1;
                }
                return this.users.filter((row, index) => {
                    let start = (this.currentPage - 1) * this.countPage;
                    let end = this.currentPage * this.countPage;
                    row.i = index + 1;
                    if (index >= start && index < end) return true;
                });
            } else {
                return this.users;
            }
        }
    }
}
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
