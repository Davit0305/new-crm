<template>
    <div>
        <div id="header">
            <div class="login">
                <form class="mainform" id="search" action="sot.php" method="POST">
                    <div id="allsearch">Расширенный поиск</div>
                    <label for="number">Найти сотрудника</label>
                    <input type="text" id="number" name="SearchNom" size="10" class="text">
                    <input type="hidden" name="enabled" value="1">
                    <input type="submit" class="button" value="Найти">
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Login",
        data() {
            return {
                login: null,
                password: null
            }
        },
        methods: {
            loginUser() {
                axios.get('/sanctum/csrf-cookie').then(response => {
                    axios.post('/login', {login: this.login, password: this.password})
                        .then(r => {
                            this.$router.push({name: 'orders'});
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
            }
        }
    }
</script>

<style scoped>

</style>
