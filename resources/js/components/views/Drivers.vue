<template>
    <div>
        <div id="header">
            <div class="login">
                <form class="mainform" id="search">
                    <div class="inner2">
                        <div style="position:absolute;width:415px;margin: -10px;">
                            <div style="float:left">
                                <input type="radio" name="allstat" v-model="filter.shtat" value="1"/>
                                <span>Все штатные</span></div>
                            <div style="float:left;margin-left: 15px;">
                                <input type="radio" name="allstat" v-model="filter.shtat" value="2"/> <span>Все внештатные</span>
                            </div>
                            <div style="float:left;margin-left: 15px;">
                                <input type="radio" name="allstat" v-model="filter.shtat"
                                       value="3"/><span>Все такси</span></div>
                            <div>
                                <input type="radio" name="allstat" v-model="filter.shtat" value="4"/>
                                <span>Все службы</span>
                            </div>
                        </div>
                        <label for="number">Найти курьера</label>
                        <input v-model="filter.search" type="text" id="number" name="SearchNom" size="10"
                               class="text">
                        <input @click="saveFilter()" type="button" class="button" value="Найти">
                    </div>
                </form>
            </div>
        </div>

        <div class="id_user my-5">
            <div v-if="driversLoaded === false" id="preloader"></div>
            <table v-else id="maintable" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td style="border:none; font-size:12px; position: relative;" colspan="13">
                        <div style="margin: -15px 0 -15px 0;">
                                <span style="position: absolute;bottom: 2px;">
                                    <span class="countOrders">{{ sortedDrivers.length }} из {{
                                            drivers.length
                                        }} заказа</span>
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
                    <td style="width:45px"><h3>№ П/П</h3></td>
                    <td style="width:318px"><h3>Фамилия Имя Отчество</h3></td>
                    <td><h3>МАРКА МАШИНЫ</h3></td>
                    <td><h3>ГОС.НОМЕР</h3></td>
                    <td><h3>ГОРОД</h3></td>
                    <td><h3>Номер телефона</h3></td>
                    <td><h3>ШТАТ</h3></td>
                    <td><h3>СТАТУС</h3></td>
                    <td><h3>СМС</h3></td>
                    <td class="tsave"></td>
                    <td class="tdelete"></td>
                </tr>
                <tr v-for="(driver, k) in sortedDrivers">
                    <td class="text-center">{{ driver.i }}</td>
                    <td>
                        <label class="sotNameLabel">{{
                                driver.surname + ' ' + driver.name + ' ' + driver.middlename
                            }}</label>
                    </td>
                    <td>
                        <input class="auto no-outline" @change="onChange(driver.id)" v-model="driver.avto">
                    </td>
                    <td>
                        <the-mask @change="onChange(driver.id)"
                                  v-model="driver.nomer"
                                  :mask="['A###AA ###RUS']"
                                  :change="onChange(driver.id)"
                                  style="text-transform: uppercase"
                                  :tokens="{
                                      '#':{pattern: /\d/},
                                      'A': {pattern: /[А-Яа-яЁё]/,transform: v => v.toLocaleUpperCase()}
                                   }"
                                  class="auto no-outline"/>
                    </td>
                    <td>
                        <label class="sotNameLabel">
                            <select class="operator" v-model="driver.city_id" @change="onChange(driver.id)"
                                    style="width: 100px;" name="shtat">
                                <option v-for="city in cities" v-bind:value="city.id">{{ city.name }}</option>
                            </select>
                        </label>
                    </td>
                    <td>
                        <the-mask @change="onChange(driver.id)" @input="showSaveButton(driver.id, driver.phone)"
                                  v-model="driver.phone"
                                  :mask="['+7(###)###-##-##']" :id="'phone' + driver.id" class="phoneInSot"/>
                    </td>
                    <td>
                        <label class="sotNameLabel">
                            <select class="operator" v-model="driver.shtat" @change="onChange(driver.id)"
                                    style="width: 100px;" name="shtat">
                                <option value="1">Штатный</option>
                                <option value="2">Внештатный</option>
                                <option value="3">Такси</option>
                                <option value="4">Служба</option>
                            </select>

                        </label>
                    </td>
                    <td>
                        <label class="sotNameLabel">
                            <select class="operator" @change="onChange(driver.id)" v-model="driver.enabled"
                                    style="width: 100px;" name="operator">
                                <option value="0">Уволен</option>
                                <option value="1">Активен</option>
                            </select>

                        </label>
                    </td>
                    <td>
                        <label class="sotNameLabel"><input type="checkbox" v-model="driver.allow_send_sms"></label>
                    </td>
                    <td class="tsave">
                        <i type="submit" @click="saveDriver(driver)" :id="'save_botton_' + driver.id"
                           title="Сохранить" class="fa fa-floppy-o" style="display: none" aria-hidden="true"></i>
                    </td>
                    <td class="tsave">
                        <i @click="deleteDriver(driver,driver.id,k)" :id="'delete_botton_' + driver.id"
                           title="Удалить" class="fa fa-ban" aria-hidden="true"></i>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>


import DriverSearchModal from "../modals/DriversSearchModal";
export default {
    name: "drivers",
    components: {
        DriverSearchModal
    },
    data() {
        return {
            inputValue: '',
            filter: {
                shtat: this.$cookies.get("driverFilter_shtat") ? this.$cookies.get("driverFilter_shtat") : '',
                search: this.$cookies.get("driverFilter_search") ? this.$cookies.get("driverFilter_search") : ''
            },
            used_component: '',
            component_method: '',
            component_data: '',
            drivers: [],
            cities: [],
            driversLoaded: false,
            issetFilter: false,
            pageSize: 1,
            countPage: 50,
            currentPage: 1,
        }
    },
    created() {
        this.clearFilterCookie();
        this.getCities();
        this.getDrivers(true);
        this.$cookies.get("driverFilter_shtat") ? this.$cookies.get("driverFilter_shtat") : '',
        this.$cookies.get("driverFilter_search") ? this.$cookies.get("driverFilter_search") : ''
    },
    methods: {
        onChange(id) {
            $('#save_botton_' + id).show();
        },
        showSaveButton(id, phone) {
            $('.fa-floppy-o').hide();
            if (phone.length === 10) {
                $('#save_botton_' + id).show();
            }
        },
        saveDriver(driver) {
            driver.phone = $('#phone' + driver.id)[0]._value;
            axios.post('/api/drivers/save',
                {
                    driver: driver,
                }
            )
                .then(res => {
                    $('#save_botton_' + driver.id).hide();
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
        deleteDriver(driver,id) {
            if(confirm('Удалить ?')) {
                axios.post('/api/drivers/delete',
                    {
                        id: id,
                    }
                )
                    .then(res => {
                        let index = this.drivers.indexOf(driver)
                        this.drivers.splice(index,1);
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
            this.getDrivers(true);
            this.$root.$emit('clear-driver-search-form');
        },
        getSearchModal() {
            this.used_component = 'DriverSearchModal';
            this.component_method = (clear) => this.getDrivers(clear);
            $('.window-overlay').show();
            $('body').css('overflow', 'hidden');
        },

        getDrivers(clear = false) {
            if (clear === true) {
                this.clearFilterCookie();
                this.filter.shtat = '';
                this.issetFilter = false;
                this.filter.search = '';
            } else {
                let issetFilter = this.checkFilter();
                this.filter.shtat = this.$cookies.get("driverFilter_shtat") ? this.$cookies.get("driverFilter_shtat") : '';
                this.filter.search = this.$cookies.get("driverFilter_search") ? this.$cookies.get("driverFilter_search") : '';
                this.issetFilter = issetFilter === 1;
            }
            this.driversLoaded = false;
            axios.post('/api/drivers/get', {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            })
                .then(res => {
                    // console.log(res);
                    this.drivers = res.data;
                    this.driversLoaded = true;

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
        getCities() {
            axios.get('/api/drivers/cities', {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            })
                .then(res => {
                    this.cities = res.data;
                })
                .catch(err => {
                })
        },
        clearFilterCookie() {
            this.$cookies.remove("driverFilter_search");
            this.$cookies.remove("driverFilter_shtat");
            this.$cookies.remove("driverFilter_enabled");
            this.$cookies.remove("driverFilter_job");
            this.$cookies.remove("driverFilter_dep");
        },
        checkFilter() {
            let search = this.$cookies.get("driverFilter_search");
            let shtat = this.$cookies.get("driverFilter_search");
            let enabled = this.$cookies.get("driverFilter_enabled");
            let job = this.$cookies.get("driverFilter_job");
            let dep = this.$cookies.get("driverFilter_dep");
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
        showSotPhone(id) {
            $("#ldSotPhone_" + id).css("display", "none");
            $("#ldInSot_" + id).css("display", "inline");
            $(".link_to_cart").css("display", "none");
            $("#save_" + id).css("display", "inline");
        },
        saveFilter() {
            if (this.filter.shtat != '')
                this.$cookies.set("driverFilter_shtat", this.filter.shtat);
            else
                this.$cookies.remove("driverFilter_shtat");

            if (this.filter.search != '') {
                this.$cookies.set("driverFilter_search", this.filter.search);
            } else {
                this.$cookies.remove("driverFilter_search");
            }
            this.used_component = '';
            this.getDrivers();
        }
        ,
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
        //
    },
    computed: {
        sortedDrivers: function () {
            if (this.driversLoaded === true) {
                this.pageSize = Math.ceil(this.drivers.length / this.countPage);
                if (this.currentPage > this.pageSize) {
                    this.currentPage = 1;
                }
                return this.drivers.filter((row, index) => {
                    let start = (this.currentPage - 1) * this.countPage;
                    let end = this.currentPage * this.countPage;
                    row.i = index + 1;
                    if (index >= start && index < end) return true;
                });
            } else {
                return this.drivers;
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
</style>
