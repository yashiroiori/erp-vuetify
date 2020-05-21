<template>
    <v-navigation-drawer v-model="drawer" :clipped="$vuetify.breakpoint.lgAndUp" app>
        <v-list dense>
            <v-list-item key="admin.dashboard" link :href="route('admin.dashboard')">
                <v-list-item-action>
                    <v-icon>mdi-home</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>Dashboard</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <template v-for="module in modules">
                <v-subheader>{{module.name}}</v-subheader>
                <v-list-item v-for="children in module.children" :key="`module_${children.id}`" link :href="route(children.slug+'.index')">
                    <v-list-item-action>
                        <v-icon>{{children.icon}}</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>{{children.name}}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </template>
        </v-list>
    </v-navigation-drawer>
</template>

<script>

export default {
    props: {
        drawer: {
            type: Boolean,
            default: true,
        }
    },
    computed: {
        modules(){
            return this.$page.auth.user.menu
        },
    },
}

</script>