<template>
    <div>
        <v-toolbar dense dark color="blue darken-2">
            <v-toolbar-title>{{modelData.titles.index}}</v-toolbar-title>
            <template v-slot:extension>
                <v-spacer></v-spacer>
                <v-tooltip top v-if="selected.length">
                    <template v-slot:activator="{ on }">
                        <v-btn icon color="warning" v-on:click.prevent="confirmRestoreSelected" v-on="on"><v-icon>mdi-delete-restore</v-icon></v-btn>
                    </template>
                    <span>Restaurar</span>
                </v-tooltip>
                <v-tooltip top v-if="selected.length">
                    <template v-slot:activator="{ on }">
                        <v-btn icon color="error" v-on:click.prevent="confirmDeleteSelected" v-on="on"><v-icon>mdi-delete</v-icon></v-btn>
                    </template>
                    <span>Eliminar</span>
                </v-tooltip>
                <v-tooltip top v-if="selected.length">
                    <template v-slot:activator="{ on }">
                        <v-btn icon color="error" v-on:click.prevent="confirmDeleteForeverSelected" v-on="on"><v-icon>mdi-delete-forever</v-icon></v-btn>
                    </template>
                    <span>Eliminar definitivamente</span>
                </v-tooltip>
                <v-tooltip top>
                    <template v-slot:activator="{ on }">
                        <v-btn icon v-on:click.prevent="fetch" v-on="on"><v-icon>mdi-database-refresh</v-icon></v-btn>
                    </template>
                    <span>Actualizar</span>
                </v-tooltip>
                <v-tooltip top v-if="modelData.actions.includes('add')">
                    <template v-slot:activator="{ on }">
                        <v-btn icon v-on:click.prevent="$root.openDialog(modelData.resource)" v-on="on"><v-icon>mdi-plus</v-icon></v-btn>
                    </template>
                    <span>Agregar</span>
                </v-tooltip>
                <modal-query-builder :query.sync="query" :rules="modelData.rules" :page.sync="searchData.page" v-bind:filter="filter" :resource="modelData.resource" />
            </template>
        </v-toolbar>
        <v-card style="border-radius: 0px;">
            <!--
            <v-expansion-panels>
                <v-expansion-panel>
                    <v-expansion-panel-header>Filtros</v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <v-row>
                            <template v-for="header in modelData.columns_table" v-if="header.searchable">
                                <v-col cols="4">
                                    <v-combobox
                                        :label="header.text"
                                        v-model="searchData.fields[header.value]"
                                        hide-selected
                                        multiple
                                        persistent-hint
                                        small-chips
                                        deletable-chips
                                        dense
                                        filled
                                        hide-detail
                                        clearable>
                                    </v-combobox>
                                </v-col>
                            </template>
                        </v-row>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
            -->
            <v-data-table
                    v-model="selected"
                    :loading="loading"
                    :headers="modelData.columns_table"
                    :items="items.data"
                    item-key="name"
                    show-select
                    :server-items-length="modelData.total"
                    loading-text="Procesando..."
                    no-data-text="No se han registrado datos"
                    no-results-text="No se encontraron coincidencias"
                    :footer-props="{
                        itemsPerPageOptions: [5,10,20,30,50,100,200,500,-1],
                        itemsPerPageAllText: 'Todos',
                        itemsPerPageText: 'Mostrar por página:',
                    }">
                <template v-slot:item.status_text="{ item }">
                    <v-chip small label :color="item.status_text.color" style="color: #fff;">{{item.status_text.text}}</v-chip>
                </template>
                <template v-slot:item.action="{ item }">
                    <v-tooltip top v-if="!item.is_deleted">
                        <template v-slot:activator="{ on }">
                            <v-btn icon x-small v-on:click.prevent="$root.openDialogEdit(modelData.resource,item.id)" v-on="on"><v-icon>mdi-pencil</v-icon></v-btn>
                        </template>
                        <span>Editar</span>
                    </v-tooltip>
                    <v-tooltip top v-if="item.is_deleted">
                        <template v-slot:activator="{ on }">
                            <v-btn icon x-small v-on:click.prevent="confirmRestoreItem(item)" v-on="on"><v-icon>mdi-delete-restore</v-icon></v-btn>
                        </template>
                        <span>Restaurar</span>
                    </v-tooltip>
                    <v-tooltip top v-if="!item.is_deleted">
                        <template v-slot:activator="{ on }">
                            <v-btn icon x-small v-on:click.prevent="confirmDeleteItem(item)" v-on="on"><v-icon>mdi-delete</v-icon></v-btn>
                        </template>
                        <span>Eliminar</span>
                    </v-tooltip>
                    <v-tooltip top v-if="item.is_deleted">
                        <template v-slot:activator="{ on }">
                            <v-btn icon x-small v-on:click.prevent="confirmDeleteItem(item)" v-on="on"><v-icon>mdi-delete-forever</v-icon></v-btn>
                        </template>
                        <span>Eliminar definitivamente</span>
                    </v-tooltip>
                </template>
            </v-data-table>
        </v-card>
        <v-dialog v-model="dialogDelete" max-width="400" persistent>
            <v-card>
                <v-card-title class="headline">Eliminar registro</v-card-title>
                <v-card-text>¿Seguro que desea eliminar el registro: <strong>{{delete_item_action.name}}</strong>?</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click="dialogDelete = false" :disabled="loading_action">Cancelar</v-btn>
                    <v-btn color="error" text @click="deleteItem" :disabled="loading_action">Eliminar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="dialogRestore" max-width="400" persistent>
            <v-card>
                <v-card-title class="headline">Restaurar registro</v-card-title>
                <v-card-text>¿Seguro que desea restaurar el registro: <strong>{{delete_item_action.name}}</strong>?</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click="dialogRestore = false" :disabled="loading_action">Cancelar</v-btn>
                    <v-btn color="warning" text @click="restoreItem" :disabled="loading_action">Restaurar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="dialogRestoreSelected" max-width="400" persistent>
            <v-card>
                <v-card-title class="headline">Restauración masiva</v-card-title>
                <v-card-text>¿Seguro que desea restaurar masivamente: <strong>{{selected.length}} registro(s)</strong>?</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click="dialogRestoreSelected = false" :disabled="loading_action">Cancelar</v-btn>
                    <v-btn color="warning" text @click="restoreSelected" :disabled="loading_action">Restaurar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDeleteSelected" max-width="400" persistent>
            <v-card>
                <v-card-title class="headline">Eliminación masiva</v-card-title>
                <v-card-text>
                    ¿Seguro que desea eliminar masivamente: <strong>{{selected.length}} registro(s)</strong>?<br>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click="dialogDeleteSelected = false" :disabled="loading_action">Cancelar</v-btn>
                    <v-btn color="error" text @click="deleteSelected" :disabled="loading_action">Eliminar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDeleteForeverSelected" max-width="400" persistent>
            <v-card>
                <v-card-title class="headline">Eliminación definitiva masiva</v-card-title>
                <v-card-text>
                    ¿Seguro que desea eliminar masivamente de manera definitiva: <strong>{{selected.length}} registro(s)</strong>?<br>
                    <v-alert
                            class=" mt-4 mb-0"
                            prominent
                            dark
                            color="warning">
                        Se borrarán definitivamente los registros seleccionados que se encuentren eliminados de modo lógico y no se podrán restaurar
                    </v-alert>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click="dialogDeleteForeverSelected = false" :disabled="loading_action">Cancelar</v-btn>
                    <v-btn color="error" text @click="deleteForeverSelected" :disabled="loading_action">Eliminar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>

import pickBy from 'lodash/pickBy'
import ModalQueryBuilder from './ModalQueryBuilder'

export default {
    props: {
        items: Object,
        filters: Object,
        modelData: Object,
    },
    components: {
        'modal-query-builder': ModalQueryBuilder,
    },
    data () {
        return {
            selected: [],
            loading: false,
            searchData: {
                page: 1,
                per_page: 15,
                sort: 'name',
                total: 0,
                filter: {
                    status: 'active',
                },
                fields: {},
            },
            dialogDelete: false,
            dialogRestore: false,
            dialogDeleteSelected: false,
            dialogDeleteForeverSelected: false,
            dialogRestoreSelected: false,
            delete_item_action: {
                name: '',
            },
            loading_action: false,
            query: {},
            searchData: {
                per_page: this.filters != undefined && this.filters.per_page ? this.filters.per_page : 15,
                sort: this.filters != undefined && this.filters.sort ? this.filters.sort : this.sortDefault,
                page: this.filters != undefined && this.filters.page ? this.filters.page : 1,
            },
        }
    },
    methods: {
        filter(){
            // this.selectedRows = []
            this.searchData.query = this.query
            let query = pickBy(this.searchData)
            this.$inertia.replace(route(this.modelData.resource+'.index', Object.keys(query).length ? query : { remember: 'forget' }))
        },
        confirmDeleteSelected(){
            this.dialogDeleteSelected = true
        },
        confirmDeleteForeverSelected(){
            this.dialogDeleteForeverSelected = true
        },
        confirmRestoreSelected(){
            this.dialogRestoreSelected = true
        },
        confirmDeleteItem(item){
            this.delete_item_action = item
            this.dialogDelete = true
        },
        confirmRestoreItem(item){
            this.delete_item_action = item
            this.dialogRestore = true
        },
        deleteItem(){
            this.loading_action = true
            this.$inertia.post(route(this.modelData.resource+'.destroy',this.delete_item_action.id))
                .then(response => {
                    this.loading_action = false
                    if(Object.keys(this.$page.errors).length === 0) {
                        this.dialogDelete = false
                        this.fetch()
                    }
                })
                .catch(({response}) => {
                    this.loading_action = false
                })
        },
        restoreItem(){
            this.loading_action = true
            this.$inertia.post(route(this.modelData.resource+'.restore',this.delete_item_action.id), {_method: 'PATCH'})
                .then(response => {
                    this.loading_action = false
                    if(Object.keys(this.$page.errors).length === 0) {
                        this.dialogRestore = false
                        this.fetch()
                    }
                })
                .catch(err => {
                    this.loading_action = false
                })
        },
        deleteSelected(){
            let selected_list = []
            this.selected.forEach(selected => {
                selected_list.push(selected.id)
            })
            this.selected = []
            this.loading_action = true
            this.$inertia.post(route(this.modelData.resource+'.batch_action'), {items: selected_list, action: 'delete'})
                .then(response => {
                    this.loading_action = false
                    if(Object.keys(this.$page.errors).length === 0) {
                        this.dialogDeleteSelected = false
                        this.fetch()
                    }
                })
                .catch(err => {
                    this.loading_action = false
                })
        },
        deleteForeverSelected(){
            let selected_list = []
            this.selected.forEach(selected => {
                selected_list.push(selected.id)
            })
            this.selected = []
            this.loading_action = true
            this.$inertia.post(route(this.modelData.resource+'.batch_action'), {items: selected_list, action: 'force-delete'})
                .then(response => {
                    this.loading_action = false
                    if(Object.keys(this.$page.errors).length === 0) {
                        this.dialogDeleteForeverSelected = false
                        this.fetch()
                    }
                })
                .catch(err => {
                    this.loading_action = false
                })
        },
        restoreSelected(){
            let selected_list = []
            this.selected.forEach(selected => {
                selected_list.push(selected.id)
            })
            this.selected = []
            this.loading_action = true
            this.$inertia.post(route(this.modelData.resource+'.batch_action'), {items: selected_list, action: 'restore'})
                .then(response => {
                    this.loading_action = false
                    if(Object.keys(this.$page.errors).length === 0) {
                        this.dialogRestoreSelected = false
                        this.fetch()
                    }
                })
                .catch(err => {
                    this.loading_action = false
                })

        },
    },
}

</script>