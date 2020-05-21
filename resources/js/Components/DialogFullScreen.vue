<template>
    <v-dialog :id="`dialog_full_string_${resource}`" :name="`dialog_full_string_${resource}`" :key="`dialog_full_string_${resource}`" v-model="dialog" fullscreen transition="dialog-bottom-transition">
        <v-card>
            <v-toolbar dense dark :color="form.id == undefined ? `primary`: `warning`">
                <v-btn icon dark @click="dialog = false">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title v-if="form.id == undefined">{{title}}</v-toolbar-title>
                <v-toolbar-title v-else>{{titleEdit}}</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn dark text @click="submit" :loading="loading">
                        <v-icon>mdi-content-save</v-icon> Guardar
                    </v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-form>
                <v-container>
                    <slot name="form-content" v-bind:form="form"></slot>
                </v-container>
            </v-form>
        </v-card>
    </v-dialog>
</template>

<script>

export default {
    props: {
        title: {
            type: String,
            default: 'Agregar',
        },
        titleEdit: {
            type: String,
            default: 'Editar',
        },
        resource: {
            type: String,
            default: '',
        },
    },
    data () {
        return {
            dialog: false,
            form: {
                _method: 'POST',
            },
            url: null,
            loading: false,
            open_all: false,
        }
    },
    mounted(){
        this.form = {
            _method: 'POST',
        };
        let vm = this
        eventBus.$on(this.resource+'_create', function(data) { 
            vm.dialog = true
            vm.form = {
                _method: 'POST',
            }
        });
        eventBus.$on(this.resource+'_edit', function(item_id) { 
            vm.dialog = true
            vm.form = {
                _method: 'PUT',
            }
            vm.getResourceData(item_id)
        });
    },
    methods: {
        getResourceData(item_id){
            let vm = this
            axios.get(route(this.resource+'.show', {id: item_id}))
                .then(({data}) => {
                    vm.form = Object.assign(vm.form, data)
                    vm.form._method = 'PUT'
                    vm.$forceUpdate();
                })
        },
        submit(){
            this.loading = true
            this.url = route(this.resource+'.store')
            if(this.form.id != undefined){
                this.url = route(this.resource+'.update',{id: this.form.id})
            }
            this.$inertia.post(this.url, this.form)
                .then(response => {
                    this.loading = false
                    if(Object.keys(this.$page.errors).length === 0) {
                        eventBus.$emit(this.resource + '_table_refresh')
                        this.dialog = false
                        this.form = {}
                    }
                })
                .catch(err => {
                    console.log('catch',err)
                    this.loading = false
                    // if(err.response.data.errors != undefined){
                        
                    // }
                })
        },
    },
}

</script>