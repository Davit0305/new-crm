<template>
    <div>
        <div id="header">
            <div class="login">
                <form class="mainform" id="search">
                    <div class="inner2">
                        <div>
                            <label for="addPoint">Найти курьера</label>
                            <input v-model="filter.search" type="text" id="addPoint" name="SearchNom" size="10"
                                   class="text add-point">
                        </div>
                        <div style="display: flex;flex-wrap: nowrap;justify-content: space-evenly;">
                            <div>
                                <input @click="addNewPoint()" type="button" class="button" value="Добавить">
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
            <div v-if="pointsLoaded === false" id="preloader"></div>
            <table v-else id="maintable" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td style="border:none; font-size:12px; position: relative;" colspan="13">
                        <div style="margin: -15px 0 -15px 0;">
                                <span style="position: absolute;bottom: 2px;">
                                    <span class="countOrders">{{ sortedPoints.length }} из {{
                                            points.length
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
                    <td style="width:45px"><h3>№</h3></td>
                    <td style="width:318px"><h3>Название</h3></td>
                    <td>
                        <div class="head-td" ref="city_wrap">
                            <h3>Город</h3>
                            <v-btn type="submit" class="btn-plus" title="Добавить город" color="green darken-1" large @click="addCityView">+</v-btn>
                        </div>
                        <div class="add-city" v-show="showAddCity">
                            <i type="submit" @click="hideCity()" id="hideCity"
                               style="font-size: 16px;margin-left: 10px; position: absolute;top: 5px; left: 226px;"
                               title="Закрыть" class="fa fa-close" aria-hidden="true"></i>
                            <div class="bort">
                                <span>Добавить город</span>
                                <input class="bort_textinput tochka-no-border" id="city_name" type="text" name="city_name">
                                <i type="submit" @click="saveCity(point)" id="saveBottonCity" style="font-size: 16px;margin-left: 10px;" title="Сохранить" class="fa fa-floppy-o" aria-hidden="true"></i>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="head-td">
                            <h3>Район</h3>
                            <v-btn class="btn-plus" @click="showAddCity = !showAddCity" title="Добавить город"
                                   color="green darken-1" large
                                   v-on:click="addRaionView()">+
                            </v-btn>
                        </div>
                        <div class="add-raion" style="display:none;">
                            <i type="submit" @click="hideRaion()" id="hideRaion"
                               style="font-size: 16px;margin-left: 10px; position: absolute;top: 5px; left: 226px;"
                               title="Закрыть" class="fa fa-close" aria-hidden="true"></i>
                            <div class="bort">
                                <span>Добавить район</span>
                                <input class="bort_textinput tochka-no-border" id="raion_name" type="text"
                                       name="raion_name">
                                <i type="submit" @click="saveRaion()" id="save_botton_city"
                                   style="font-size: 16px;margin-left: 10px;"
                                   title="Сохранить" class="fa fa-floppy-o" aria-hidden="true"></i>
                                <select class="operator"
                                        style="width: 100px;" name="shtat" id="newRaion">
                                    <option v-for="city in cities" v-bind:value="city.id">{{ city.name }}</option>
                                </select>
                            </div>
                        </div>
                    </td>
                    <td><h3>Адрес<br>для сайта</h3></td>
                    <td><h3>Адрес<br>для смс</h3></td>
                    <td><h3>Телефон</h3></td>
                    <td><h3>Телефон<br>для смс</h3></td>
                    <td><h3>Короткий номер</h3></td>
                    <td><h3>1C номер</h3></td>
                    <td><h3>На карте</h3></td>
                    <td><h3>Панорамы</h3></td>
                    <td><h3>Скидка %</h3></td>
                    <td class="tstatd"><h3>Бонус</h3></td>
                    <td class="tstatd"><h3>Работает</h3></td>
                    <td><h3>Бренд</h3></td>
                    <td class="tsave"></td>
                    <td class="tdelete"></td>
                </tr>
                <tr v-for="(point, k) in sortedPoints"
                    :title="point.modified_user.name + ' ' + point.modified_user.middlename + ' ' + point.modified_user.surname ">
                    <td @change="onChange(point.id)" class="text-center">{{ point.i }}</td>
                    <td>
                        <input class="auto no-outline" @change="onChange(point.id)" v-model="point.name">
                    </td>
                    <td>
                        <label class="sotNameLabel">
                            <select class="operator" v-model="point.city_id" @change="onChange(point.id)"
                                    style="width: 100px;" name="shtat">
                                <option v-for="city in cities" v-bind:value="city.id">{{ city.name }}</option>
                            </select>
                        </label>
                    </td>
                    <td>
                        <label class="sotNameLabel">
                            <select class="operator" v-model="point.raion" @change="onChange(point.id)"
                                    style="width: 100px;" name="shtat">
                                <option v-for="raion in raiony" v-bind:value="raion.id">{{ raion.name }}</option>
                            </select>
                        </label>
                    </td>
                    <td>
                        <input class="auto no-outline" @change="onChange(point.id)" v-model="point.adres">
                    </td>
                    <td>
                        <input class="auto no-outline" @change="onChange(point.id)" v-model="point.sms_adres">
                    </td>
                    <td>
                        <the-mask @change="onChange(point.id)" @input="showSaveButton(point.id, point.phone)"
                                  v-model="point.phone"
                                  :mask="['+7(###)###-##-##']" :id="'1phone' + point.id" class="phoneInSot"/>
                    </td>
                    <td>
                        <the-mask @change="onChange(point.id)" @input="showSaveButton(point.id, point.sms_phone)"
                                  v-model="point.sms_phone"
                                  :mask="['+7(###)###-##-##']" :id="'2phone' + point.id" class="phoneInSot"/>
                    </td>
                    <td>
                        <input class="auto no-outline" @change="onChange(point.id)" v-model="point.short_number">
                    </td>
                    <td>
                        <input class="auto no-outline" @change="onChange(point.id)" v-model="point.gu_id">
                    </td>
                    <td>
                        <input class="auto no-outline" @change="onChange(point.id)" v-model="point.yandex">
                    </td>
                    <td>
                        <input class="auto no-outline" @change="onChange(point.id)" v-model="point.pano">
                    </td>
                    <td>
                        <input type="number" class="auto no-outline" @change="onChange(point.id)"
                               v-model="point.discount">
                    </td>
                    <td>
                        <label class="sotNameLabel"><input type="checkbox" @change="onChange(point.id)"
                                                           v-model="point.bonus"></label>
                    </td>
                    <td style="display:flex;width: 47px;">
                        <label class="sotNameLabel"><input type="checkbox" @change="onChange(point.id)"
                                                           v-model="point.enabled"></label> <span>Да</span>
                    </td>
                    <td>
                        <label class="sotNameLabel">
                            <select class="operator" @change="onChange(point.id)" v-model="point.enabled"
                                    style="width: 100px;" name="operator">
                                <option value="0">Уволен</option>
                                <option value="1">Активен</option>
                            </select>

                        </label>
                    </td>
                    <td class="tsave">
                        <i type="submit" @click="savePoint(point)" :id="'save_botton_' + point.id"
                           title="Сохранить" class="fa fa-floppy-o" style="display: none" aria-hidden="true"></i>
                    </td>
                    <td class="tsave">
                        <i @click="deletePoint(point,point.id,k)" :id="'delete_botton_' + point.id"
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
    name: "points",
    // components: {
    //     PointSearchModal
    // },
    data() {
        return {
            inputValue: '',
            showAddCity: false,
            filter: {
                search: this.$cookies.get("pointsFilter_search") ? this.$cookies.get("pointsFilter_search") : ''
            },
            used_component: '',
            component_method: '',
            component_data: '',
            points: [],
            cities: [],
            raiony: [],
            pointsLoaded: false,
            issetFilter: false,
            pageSize: 1,
            countPage: 50,
            currentPage: 1,
        }
    },
    created() {
        this.clearFilterCookie();
        this.getCities();
        this.getRaiony();
        this.getPoints(true);
        this.$cookies.get("pointsFilter_search") ? this.$cookies.get("pointsFilter_search") : ''
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
        savePoint(point) {
            point.phone = $('#1phone' + point.id)[0]._value;
            point.sms_phone = $('#2phone' + point.id)[0]._value;
            axios.post('/api/points/save',
                {
                    point: point,
                }
            )
                .then(res => {
                    $('#save_botton_' + point.id).hide();
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
        deletePoint(point, id) {
            if (confirm('Удалить ?')) {
                axios.post('/api/points/delete',
                    {
                        id: id,
                    }
                )
                    .then(res => {
                        let index = this.points.indexOf(point)
                        this.points.splice(index, 1);
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
            this.getPoints(true);
            this.$root.$emit('clear-point-search-form');
        },
        getSearchModal() {
            // this.used_component = 'PointsSearchModal';
            this.component_method = (clear) => this.getPoints(clear);
            $('.window-overlay').show();
            $('body').css('overflow', 'hidden');
        },

        getPoints(clear = false) {
            if (clear === true) {
                this.clearFilterCookie();
                this.issetFilter = false;
                this.filter.search = '';
            } else {
                let issetFilter = this.checkFilter();
                this.filter.search = this.$cookies.get("pointsFilter_search") ? this.$cookies.get("pointsFilter_search") : '';
                this.issetFilter = issetFilter === 1;
            }
            this.pointsLoaded = false;
            axios.post('/api/points/get', {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            })
                .then(res => {
                    this.points = res.data;
                    this.pointsLoaded = true;

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
        getRaiony() {
            axios.get('/api/points/raiony', {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            })
                .then(res => {
                    this.raiony = res.data;
                })
                .catch(err => {
                })
        },
        clearFilterCookie() {
            this.$cookies.remove("pointsFilter_search");
        },
        checkFilter() {
            let search = this.$cookies.get("pointsFilter_search");
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
        addNewPoint() {
            const addPoint = document.getElementById("addPoint").value;
            axios.post('/api/points/addNewPoint',
                {
                    addPoint: addPoint,
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
        saveRaion() {
            const newRaionId = document.getElementById("newRaion").value;
            const newRaionName = document.getElementById("raion_name").value;
            axios.post('/api/points/saveNewRaion',
                {
                    newRaionId: newRaionId,
                    newRaionName: newRaionName,
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
        saveCity() {
            const newCity = document.getElementById("city_name").value;

            axios.post('/api/points/saveNewCity',
                {
                    newCity: newCity,
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
                this.$cookies.set("pointsFilter_search", this.filter.search);
            } else {
                this.$cookies.remove("pointsFilter_search");
            }
            this.used_component = '';
            this.getPoints();
        },
        hideCity(){
            $(".add-city").hide();
        },
        hideRaion(){
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
        sortedPoints: function () {
            if (this.pointsLoaded === true) {
                this.pageSize = Math.ceil(this.points.length / this.countPage);
                if (this.currentPage > this.pageSize) {
                    this.currentPage = 1;
                }
                return this.points.filter((row, index) => {
                    let start = (this.currentPage - 1) * this.countPage;
                    let end = this.currentPage * this.countPage;
                    row.i = index + 1;
                    if (index >= start && index < end) return true;
                });
            } else {
                return this.points;
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
