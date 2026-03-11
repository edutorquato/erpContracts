<script setup>

    import AppLayout from '@/Layouts/AppLayoutAdmin.vue'
    import { Head } from '@inertiajs/vue3'

    defineProps({
        user: Object,
        users: Array
    })

    const headers = [
        { title: 'ID', key: 'id' },
        { title: 'Nome', key: 'name' },
        { title: 'Email', key: 'email' },
        { title: 'Criado em', key: 'created_at' },
        { title: 'Ações', key: 'actions', sortable: false },
    ]

    const formatDate = (date) => {
        return new Date(date).toLocaleDateString('pt-BR')
    }

</script>

<template>

    <Head title="Usuários" />
    
    <AppLayout>

        <div class="mb-6">
            <h1 class="text-h5 font-weight-medium">
                usuários
            </h1>
        </div>

        <v-card rounded="lg" class="pa-8">

            <v-data-table
            :items="users"
            :headers="headers"
            item-value="id"
            >

            <template v-slot:item.created_at="{ item }">
                {{ formatDate(item.created_at) }}
            </template>

            <template v-slot:item.actions="{ item }">

                <v-btn
                    color="primary"
                    size="small"
                    :href="`/admin/users/${item.id}`"
                >
                    Ver
                </v-btn>

            </template>

</v-data-table>

</v-card>

</AppLayout>

</template>