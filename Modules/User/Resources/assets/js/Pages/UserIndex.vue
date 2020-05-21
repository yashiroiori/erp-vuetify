<template>
    <layout>
        <resource-index :items="users" :modelData="$page.userModelData" :filters="filters" />
        <user-create-modal :roles="roles" />
    </layout>
</template>

<script>

import UserCreateModal from './UserCreateModal'

export default {
    props: {
        users: Object,
        filters: Object,
        roles: Array,
    },
    components: {
        'user-create-modal': UserCreateModal,
    },
}

</script>