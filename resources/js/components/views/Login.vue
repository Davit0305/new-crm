<template>
    <div id="auth">
        <div class="row justify-content-center">
            <div>
                <div class="login loginForm">
                    <div class="login-body">
                        <span class="invalid" role="alert">
                            <strong></strong>
                        </span>
                        <form>
                            <div>
                                <label for="login">Логин</label>
                                <div class="col-md-12">
                                    <input v-model="login" id="login" type="text" name="login" required autofocus>
                                </div>
                            </div>

                            <div>
                                <label for="password">Пароль</label>
                                <div class="col-md-12">
                                    <input v-model="password" id="password" type="password" name="password" required>
                                </div>
                            </div>

                            <div>
                                <div>
                                    <button @click="loginUser()" type="button"  class="login-btn">
                                        Вход
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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
