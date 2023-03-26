<template>
    <div>
        <div id="header">
            <div class="login">
                <form class="mainform" id="search">
                    <div v-if="issetFilter === true">
                        <div @click="getSearchModal()" id="allsearch" class="active">Расширенный поиск</div>
                        <div @click="clearFilter()" class="allSearchActiveClose">×</div>
                    </div>
                    <div v-else @click="getSearchModal()" id="allsearch">Расширенный поиск</div>
                    <label for="number">Найти сотрудника</label>
                    <input v-model="filter.search" type="text" id="number" name="SearchNom" size="10" class="text">
                    <input @click="saveFilter()" type="button" class="button" value="Найти">
                </form>
            </div>
        </div>

        <div class="id_user my-5">
            <div v-if="workersLoaded === false" id="preloader"></div>
            <table v-else id="maintable" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <td style="border:none; font-size:12px; position: relative;" colspan="13">
                            <div style="margin: -15px 0 -15px 0;">
                                <span style="position: absolute;bottom: 2px;">
                                    <span class="countOrders">{{ sortedWorkers.length }} из {{ workers.length }} заказа</span>
                                    <span v-if="pageSize > 1" class="pagelinks">
                                        <span style="margin-right: 10px;">|</span>
                                        Страницы:
                                        <select v-model="currentPage" name="_pageSize">
                                            <option v-for="pageNumber in pageSize" :selected="currentPage == pageNumber" :value="pageNumber">{{ pageNumber }}</option>
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
                        <td><h3>Номер телефона</h3></td>
                        <td><h3>Должность</h3></td>
                        <td><h3>Отдел</h3></td>
                        <td class="tsave"></td>
                    </tr>
                    <tr v-for="worker in sortedWorkers">
                        <td class="text-center">{{ worker.i }}</td>
                        <td>
                            <label class="sotNameLabel">{{ worker.surname + ' ' + worker.name + ' ' + worker.middlename }}</label>
                        </td>
                        <td>
                            <the-mask @input="showSaveButton(worker.id, worker.phone)" v-model="worker.phone" :mask="['+7(###)###-##-##']" class="phoneInSot" />
                        </td>
                        <td>
                            <label class="sotNameLabel">{{ worker.job_name }}</label>
                        </td>
                        <td>
                            <label class="sotNameLabel">{{ worker.department_name }}</label>
                        </td>
                        <td class="tsave">
                            <i @click="saveWorker(worker.id, worker.phone)" :id="'save_botton_' + worker.id" title="Сохранить" style="display:none;" class="fa fa-floppy-o" aria-hidden="true"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <component @component_method="component_method" :is="used_component" :data="component_data"></component>
    </div>
</template>

<script>
    import WorkerSearchModal from "../modals/WorkerSearchModal";
    export default {
        name: "Workers",
        components: {
            WorkerSearchModal
        },
        data() {
            return {
                filter: {
                    search: this.$cookies.get("workerFilter_search") ? this.$cookies.get("workerFilter_search") : ''
                },
                used_component: '',
                component_method: '',
                component_data: '',
                workers: [],
                workersLoaded: false,
                issetFilter: false,
                pageSize: 1,
                countPage: 50,
                currentPage: 1
            }
        },
        created() {
            this.getWorkers();
            this.countPage = this.$cookies.get("countPage") ? this.$cookies.get("countPage") : 50;
            this.currentPage = this.$cookies.get("currentPage") ? this.$cookies.get("currentPage") : 1;
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
        computed:{
            sortedWorkers:function() {
                if(this.workersLoaded === true) {
                    this.pageSize = Math.ceil(this.workers.length / this.countPage);
                    if(this.currentPage > this.pageSize) {
                        this.currentPage = 1;
                    }
                    return this.workers.filter((row, index) => {
                        let start = (this.currentPage-1)*this.countPage;
                        let end = this.currentPage*this.countPage;
                        row.i = index + 1;
                        if(index >= start && index < end) return true;
                    });
                } else {
                    return this.workers;
                }
            }
        },
        methods: {
            showSaveButton(id, phone) {
                $('.fa-floppy-o').hide();
                if(phone.length === 10) {
                    $('#save_botton_' + id).show();
                }
            },
            saveWorker(id, phone) {
                if(phone.length !== 10) {
                    alert('Введите корректный номер телефона!');
                    return false;
                }
                axios.post('/api/worker/saveWorker', {id: id, phone: phone})
                    .then( res => {
                        console.log(res);
                        $('#save_botton_' + id).hide();
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
            clearFilter() {
                this.getWorkers(true);
                this.$root.$emit('clear-worker-search-form');
            },
            getSearchModal() {
                this.used_component = 'WorkerSearchModal';
                this.component_method = (clear) => this.getWorkers(clear);
                $('.window-overlay').show();
                $('body').css('overflow', 'hidden');
            },
            getWorkers(clear = false) {
                if(clear === true) {
                    this.clearFilterCookie();
                    this.issetFilter = false;
                    this.filter.search = '';
                } else {
                    let issetFilter = this.checkFilter();
                    this.filter.search = this.$cookies.get("workerFilter_search") ? this.$cookies.get("workerFilter_search") : '';
                    this.issetFilter = issetFilter === 1;
                }
                this.workersLoaded = false;
                axios.post('/api/worker/getWorkers')
                    .then( res => {
                        // console.log(res);
                        this.workers = res.data;
                        this.workersLoaded = true;
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
            clearFilterCookie() {
                this.$cookies.remove("workerFilter_search");
                this.$cookies.remove("workerFilter_enabled");
                this.$cookies.remove("workerFilter_job");
                this.$cookies.remove("workerFilter_dep");
            },
            checkFilter() {
                let search = this.$cookies.get("workerFilter_search");
                let enabled = this.$cookies.get("workerFilter_enabled");
                let job = this.$cookies.get("workerFilter_job");
                let dep = this.$cookies.get("workerFilter_dep");
                if(
                    search ||
                    enabled ||
                    job ||
                    dep )
                {
                    return 1;
                } else {
                    return 0;
                }
            },
            showSotPhone(id) {
                $("#ldSotPhone_" + id).css("display","none");
                $("#ldInSot_" + id).css("display","inline");
                $(".link_to_cart").css("display","none");
                $("#save_" + id).css("display","inline");
            },
            saveFilter() {
                if(this.filter.search != '') {
                    this.$cookies.set("workerFilter_search", this.filter.search);
                } else {
                    this.$cookies.remove("workerFilter_search");
                }
                this.used_component = '';
                this.getWorkers();
            },
        }
    }
</script>

<style scoped>
    .phoneInSot {
        border: 1px solid transparent;
        float: left;
        padding: 2px;
        text-align: center;
    }
</style>
