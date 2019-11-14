<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Change Password</div>

                    <div class="card-body">

                        <form>
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" v-model="data.email" required  autocomplete="email" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" v-model="data.password" required autocomplete="password" autofocus>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>
                                <div class="col-md-6">
                                    <input id="new_password" type="password" class="form-control" v-model="data.new_password" autocomplete="new-password" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="confirm_password" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                                <div class="col-md-6">
                                    <input id="confirm_password" type="password" class="form-control" v-model="data.confirm_password"  autocomplete="confirm_password" required>
                                </div>
                            </div>
                        </form>
                        <div class="form-group row mb-2">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary" @click="submitForm">
                                    Send Password
                                </button>
                            </div>
                        </div>
                        <div v-show="alert.show" :class="alert.type" role="alert">
                            {{ alert.msg }}
                            <button type="button" class="close" @click="alert.show = false">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapGetters,  mapActions } from 'vuex';

    export default{
        name: 'security',
        data() {
            return {
                alert: {
                    show: false,
                    msg: '',
                    type: ''
                },
                validationError: false,
                data: {
                    email: '',
                    password: '',
                    new_password: '',
                    confirm_password: ''
                }
            }
        },
        computed: {
            ...mapState('USERS', {
                activeUser: 'active'
            }),
        },
        watch: {
            activeUser: function ($user) {
                if(_.isEmpty($user)) return;

                this.data.email = $user.email;
            }
        },
        methods: {
            submitForm () {
                if(this.validateForm()) {
                    let self = this;

                    let formData = new FormData();

                    Object.keys(this.data).map(function(key) {
                        formData.append(key, self.data[key]);
                    });

                    let url = '/api/v1/security/password/confirm';

                    axios.post(url, formData)
                    .then((response) => {
                        this.alert = {
                            show: true,
                            msg: response.data.msg,
                            type: 'alert alert-' + ((response.data.error) ? 'danger' : 'primary')
                        };
                    })
                    .catch((response) => {
                        console.log('wrong: ', response);
                    })
                }
            },
            validateForm () {
                let self = this;

                Object.keys(this.data).map(function(key) {
                    if(_.isEmpty(self.data[key])) {
                        $('#' + key).addClass('is-invalid');
                        self.validationError = true;
                    }else {
                        $('#'+ key).removeClass('is-invalid');
                        self.validationError = false;
                    }
                });

                if(this.validationError) return false;

                if(this.data.confirm_password != this.data.new_password) {
                    $('#confirm_password').addClass('is-invalid');
                    return false;
                }else {
                    $('#confirm_password').removeClass('is-invalid');
                }
                return false;
            }

        },
        mounted() {
            this.data.email = this.activeUser.email;
        }
    }
</script>
