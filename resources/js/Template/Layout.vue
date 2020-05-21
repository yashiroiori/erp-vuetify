<template>
    <v-app id="inspire">
        <sidebar-component :drawer="drawer" />
        <v-app-bar :clipped-left="$vuetify.breakpoint.lgAndUp" app color="blue darken-3" dark dense>
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title style="width: 300px" class="ml-0 pl-4">
                <span class="hidden-sm-and-down">Sistema Administrativo</span>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon><v-icon>mdi-apps</v-icon></v-btn>
            <v-btn icon><v-icon>mdi-bell</v-icon></v-btn>
            <v-menu offset-y origin="center center" :nudge-bottom="10" transition="scale-transition">
                <template v-slot:activator="{ on }">
                    <v-btn dark icon v-on="on">
                        <v-avatar size="42px">
                            <img :src="$page.auth.user.avatar"/>
                        </v-avatar>
                    </v-btn>
                </template>
                <v-card max-width="200" width="200" tile>
                    <v-list nav dense>
                        <v-subheader>{{$page.auth.user.full_name}}</v-subheader>
                        <v-list-item-group>
                            <v-list-item>
                                <v-list-item-icon>
                                    <v-icon>mdi-account</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>Mi Perfil</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-divider class="mb-1"></v-divider>
                            <v-list-item>
                                <v-list-item-icon>
                                    <v-icon>mdi-logout</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>Cerrar sesión</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                </v-card>
            </v-menu>
        </v-app-bar>
        <v-content>
            <v-container fluid class="pl-0 pr-0 pt-0">
                <slot></slot>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>

import SideBar from './Section/SideBar'

export default {
    components: {
        'sidebar-component': SideBar,
    },
    data: () => ({
        drawer: true,
    }),
}
</script>