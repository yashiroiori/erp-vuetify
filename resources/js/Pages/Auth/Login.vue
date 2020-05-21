<template>
    <v-app id="inspire">
        <v-content>
            <v-container class="fill-height" fluid>
                <v-row align="center" justify="center">
                    <v-col cols="12" sm="8" md="4">
                        <v-card class="elevation-12">
                            <v-toolbar color="primary" dark flat>
                                <v-toolbar-title>Ingrese sus credenciales</v-toolbar-title>
                            </v-toolbar>
                            <v-card-text>
                                <v-form>
                                    <v-text-field label="Correo electrónico" name="login" prepend-icon="person" type="text" v-model="form.email" :error-messages="$page.errors.email != undefined ? $page.errors.email[0] : ''"></v-text-field>
                                    <v-text-field label="Contraseña" name="password" prepend-icon="lock" type="password" v-model="form.password" :error-messages="$page.errors.password != undefined ? $page.errors.password[0] : ''"></v-text-field>
                                </v-form>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn @click.prevent="onSubmit" color="primary" :loading="loading">Iniciar sesión</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>

export default {
    data(){
        return {
            form:{
                email: 'admin@admin.com',
                password: 'secret',
            },
            loading: false,
        };
    },
    methods: {
        onSubmit(){
            this.loading = true
            this.$inertia.post(route('admin.login.post'), this.form)
                .then(() => {
                    this.loading = false
                })
        },
    },
}

</script>