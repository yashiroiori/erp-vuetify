<template>
    <layout>
        <resource-index :items="roles" :modelData="$page.roleModelData" :filters="filters" />
        <role-create-modal :users="users" :modulesPermission="modulesPermission" />
    </layout>
</template>

<script>

import RoleCreateModal from './RoleCreateModal'

export default {
    props: {
        roles: Object,
        filters: Object,
        users: Array,
        modulesPermission: Array,
    },
    components: {
        'role-create-modal': RoleCreateModal,
    },
}

</script>