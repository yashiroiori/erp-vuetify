<template>
    <dialog-full-screen title="Nuevo Usuario" title-edit="Editar Usuario" resource="user" :items="{}">
        <template slot="form-content" slot-scope="data">
            <v-text-field
                label="Nombre(s)"
                v-model="data.form.name"
                filled
                counter
                :error-messages="$page.errors.name != undefined ? $page.errors.name[0] : ''">
            </v-text-field>
            <v-text-field
                label="Apellido(s)"
                v-model="data.form.last_name"
                filled
                counter
                :error-messages="$page.errors.last_name != undefined ? $page.errors.last_name[0] : ''">
            </v-text-field>
            <v-text-field
                label="Correo electrónico"
                v-model="data.form.email"
                filled
                counter
                :error-messages="$page.errors.email != undefined ? $page.errors.email[0] : ''">
            </v-text-field>
            <v-select
                v-model="data.form.roles"
                :items="roles"
                label="Roles asignados"
                filled
                chips
                multiple
                clearable>
            </v-select>
            <v-checkbox
                v-model="data.form.send_recovery_email"
                label="Enviar correo para reseteo de contraseña"
                type="checkbox"
                color="info"
                class="mt-0">
            </v-checkbox>
        </template>
    </dialog-full-screen>
</template>

<script>

export default {
    props: {
        roles: Array,
    },
}

</script>