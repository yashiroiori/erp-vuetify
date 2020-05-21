<template>
    <dialog-full-screen title="Nuevo Role" title-edit="Editar Role" resource="role" :items="{}">
        <template slot="form-content" slot-scope="data">
            <v-text-field
                label="Nombre"
                v-model="data.form.name"
                filled
                counter
                :error-messages="$page.errors.name != undefined ? $page.errors.name[0] : ''">
            </v-text-field>
            <v-select
                v-model="data.form.users"
                :items="users"
                label="Usuarios asignados"
                filled
                chips
                multiple
                clearable>
            </v-select>
            <h3>Permisos</h3>
            <v-treeview
                    v-model="data.form.permission"
                    selectable
                    dense
                    hoverable
                    :open-all="data.open_all"
                    :items="modulesPermission">
                <template v-slot:prepend="{ item }">
                    <v-icon
                        v-if="item.children && item.icon"
                        v-text="`${item.icon}`">
                    </v-icon>
                    <v-icon
                        v-else-if="!item.children && item.icon"
                        v-text="`${item.icon}`">
                    </v-icon>
                </template>
            </v-treeview>
        </template>
    </dialog-full-screen>
</template>

<script>

export default {
    props: {
        users: Array,
        modulesPermission: Array,
    },
    data(){
        return {
            roles: [],
        };
    },
}

</script>