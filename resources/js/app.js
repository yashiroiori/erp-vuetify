require('./bootstrap')
import Vue from 'vue'
window.eventBus = new Vue();
import Vuetify from 'vuetify';
Vue.use(Vuetify);
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import '@mdi/font/css/materialdesignicons.css'
import { InertiaApp } from '@inertiajs/inertia-vue'

Vue.config.productionTip = false

Vue.mixin({
    methods: {
        route: (name, params, absolute) => route(name, params, absolute, Ziggy),
        getFormData(formData, data, previousKey) {
            var formData = new FormData();
            if (data instanceof Object) {
                Object.keys(data).forEach(key => {
                    const value = data[key];
                    if(value instanceof File){
                        formData.append(key, value);
                    }else if (value instanceof Object && !Array.isArray(value)) {
                        return this.getFormData(formData, value, key);
                    }
                    if (previousKey) {
                        key = `${previousKey}[${key}]`;
                    }
                    if (Array.isArray(value)) {
                        value.forEach(val => {
                            formData.append(`${key}[]`, val);
                        });
                    } else {
                        formData.append(key, value);
                    }
                });
            }
            return formData
        },
    }
})
Vue.use(InertiaApp)

import Vue2Filters from 'vue2-filters';
Vue.use(Vue2Filters);

let app = document.getElementById('app')

import Layout from './Template/Layout'
Vue.component('layout',Layout)
import ResourceIndex from './Components/ResourceIndex'
Vue.component('resource-index',ResourceIndex)
import DialogFullScreen from './Components/DialogFullScreen'
Vue.component('dialog-full-screen',DialogFullScreen)
// import ActionButtonsResource from './Components/ActionButtonsResource'
// Vue.component('action-buttons-resource',ActionButtonsResource)

new Vue({
    vuetify: new Vuetify({
        icons: {
          iconfont: 'mdi', // default - only for display purposes
        },
    }),
    methods: {
        openDialog(resource){
            eventBus.$emit(resource+'_create')
        },
        openDialogEdit(resource,item_id){
            eventBus.$emit(resource+'_edit',item_id)
        },
    },
    render: h => h(InertiaApp, {
        props: {
            initialPage: JSON.parse(app.dataset.page),
            resolveComponent: (component) => {
                let parts = component.split('/')
                let type = parts[0]
                let module_name = parts[1]
                if(type == 'Module'){
                    let name = parts[2]
                    return import(`~/${module_name}/Resources/assets/js/Pages/${name}.vue`).then(module => module.default)
                }
                if(type == 'Package'){
                    let package_name = parts[2]
                    let name = parts[3]
                    return import(`../../vendor/${module_name}/${package_name}/resources/js/Pages/${name}.vue`).then(module => module.default)
                }
                let name = parts[2]
                return import(`@/Pages/${module_name}/${name}`).then(module => module.default)
            }
        },
    }),
}).$mount(app)
