<template>
    <div>
        <v-tooltip top>
            <template v-slot:activator="{ on }">
                <v-btn icon v-on:click.prevent="dialog = !dialog" v-on="on"><v-icon>mdi-filter</v-icon></v-btn>
            </template>
            <span>Filtros</span>
        </v-tooltip>
        <v-dialog id="query_builder_dialog" v-model="dialog" fullscreen transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar dense dark color="primary">
                    <v-btn icon dark @click="dialog = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Filtros</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn dark text @click="cleanFilter">
                            <v-icon>mdi-filter-remove</v-icon> Limpiar
                        </v-btn>
                    </v-toolbar-items>
                    <v-toolbar-items>
                        <v-btn dark text @click="submit">
                            <v-icon>mdi-filter</v-icon> Filtrar
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-form>
                    <v-container>
                        <vue-query-builder :rules="rules" v-model="queryBuilder" class="w-100"></vue-query-builder>
                    </v-container>
                </v-form>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>

export default {
    props: {
        rules: Array,
        query: Object,
        page: null,
        filter: null,
        showExport: false,
        resource: '',
    },
    data(){
        return {
            dialog: false,
            queryBuilder: null,
        };
    },
    mounted(){
        if(this.$page.filters.query != undefined){
            this.queryBuilder = this.$page.filters.query
        }
    },
    methods: {
        submit(){
            this.$emit('update:query', this.queryBuilder)
            this.filter()
            this.dialog = false
        },
        cleanFilter(){
            this.$emit('update:query', {})
            this.$emit('update:page', 1)
            this.filter()
            this.dialog = false
        },
    },
}

</script>