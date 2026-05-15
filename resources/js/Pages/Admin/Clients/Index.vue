<script setup>

import AppLayout from '@/Layouts/AppLayoutAdmin.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import axios from 'axios'

import {
    formatCpfCnpj
} from '@/Utils/document'

import {
    isValidCpfCnpj,
    isValidEmail
} from '@/Validators/client'

const props = defineProps({
    clients: Array
})

const clients = ref([...props.clients])

const loading = ref(false)


const snackbar = ref(false)

const snackbarText = ref('')

const snackbarColor = ref('success')




const dialog = ref(false)

const errors = ref({})

const search = ref('')

const form = ref({
    id: null,
    name: '',
    document: '',
    email: '',
    status: 'active',
})

const headers = [
    { title: 'Nome', key: 'name' },
    { title: 'Documento', key: 'document' },
    { title: 'Email', key: 'email' },
    { title: 'Status', key: 'status' },
    { title: 'Ações', key: 'actions', sortable: false },
]

const showSnackbar = (
    text,
    color = 'success'
) => {

    snackbarText.value = text

    snackbarColor.value = color

    snackbar.value = true
}

const nameRules = [
    v => !!v || 'Nome obrigatório',
]

const emailRules = [
    v => !!v || 'Email obrigatório',
    v => isValidEmail(v) || 'Email inválido',
]

const documentRules = [
    v => !!v || 'CPF/CNPJ obrigatório',
    v => isValidCpfCnpj(v) || 'CPF/CNPJ inválido',
]

const openCreate = () => {

    errors.value = {}

    form.value = {
        id: null,
        name: '',
        document: '',
        email: '',
        status: 'active',
    }

    dialog.value = true
}

const edit = (client) => {

    errors.value = {}

    form.value = {
        ...client
    }

    dialog.value = true
}

const save = async () => {

    loading.value = true

    errors.value = {}

    try {

        let response

        // UPDATE

        if (form.value.id) {

            response = await axios.put(
                `/admin/clients/${form.value.id}`,
                form.value
            )

            const index = clients.value.findIndex(
                client => client.id == form.value.id
            )

            clients.value[index] = response.data.client

            showSnackbar(
                'Cliente atualizado com sucesso'
            )

        } else {

            // CREATE

            response = await axios.post(
                '/admin/clients',
                form.value
            )

            clients.value.unshift(
                response.data.client
            )

            showSnackbar(
                'Cliente criado com sucesso'
            )
        }

        dialog.value = false

    } catch (error) {

        if (error.response?.status === 422) {

            errors.value = error.response.data.errors
        }

        showSnackbar(
            'Erro ao salvar cliente',
            'error'
        )

    } finally {

        loading.value = false
    }
}

const remove = async (client) => {

    if (!confirm(
        'Deseja remover este cliente?'
    )) return

    try {

        await axios.delete(
            `/admin/clients/${client.id}`
        )

        clients.value = clients.value.filter(
            item => item.id !== client.id
        )

        showSnackbar(
            'Cliente removido com sucesso'
        )

    } catch {

        showSnackbar(
            'Erro ao remover cliente',
            'error'
        )
    }
}

</script>

<template>

<Head title="Clientes" />


<v-snackbar
    v-model="snackbar"
    :color="snackbarColor"
    timeout="3000"
>

    {{ snackbarText }}

</v-snackbar>


<AppLayout>

    <div class="d-flex justify-space-between align-center mb-6">

        <div>

            <h1 class="text-h5 font-weight-medium">
                Clientes
            </h1>

        </div>

        <v-btn
            color="primary"
            @click="openCreate"
        >
            Novo Cliente
        </v-btn>

    </div>

    <v-card rounded="lg">

        <v-text-field
    v-model="search"
    label="Buscar cliente"
    prepend-inner-icon="mdi-magnify"
    variant="outlined"
    density="comfortable"
    hide-details
    class="ma-4"
/>

<v-data-table
    :headers="headers"
    :items="clients"
    :search="search"
    :items-per-page="10"
>

            <template v-slot:item.status="{ item }">

                <v-chip
                    :color="
                        item.status == 'active'
                        ? 'success'
                        : 'error'
                    "
                    size="small"
                >
                    {{
                        item.status == 'active'
                        ? 'Ativo'
                        : 'Inativo'
                    }}
                </v-chip>

            </template>

            <template v-slot:item.actions="{ item }">

                <div class="d-flex ga-2">

                    <v-btn
                        size="small"
                        color="primary"
                        variant="tonal"
                        @click="edit(item)"
                    >
                        Editar
                    </v-btn>

                    <v-btn
                        size="small"
                        color="error"
                        variant="tonal"
                        @click="remove(item)"
                    >
                        Excluir
                    </v-btn>

                </div>

            </template>

        </v-data-table>

    </v-card>

    <v-dialog
        v-model="dialog"
        max-width="600"
    >

        <v-card rounded="lg">

            <v-card-title>
                {{
                    form.id
                    ? 'Editar Cliente'
                    : 'Novo Cliente'
                }}
            </v-card-title>

            <v-card-text>

                <v-text-field
                    v-model="form.name"
                    label="Nome"
                    :rules="nameRules"
                    :error-messages="errors.name"
                />

                <v-text-field
                    v-model="form.document"
                    label="CPF/CNPJ"
                    class="mt-4"
                    :rules="documentRules"
                    :error-messages="errors.document"
                    @update:modelValue="
                        form.document = formatCpfCnpj($event)
                    "
                />

                <v-text-field
                    v-model="form.email"
                    label="Email"
                    class="mt-4"
                    :rules="emailRules"
                    :error-messages="errors.email"
                />

                <v-select
                    v-model="form.status"
                    label="Status"
                    class="mt-4"
                    :items="[
                        {
                            title: 'Ativo',
                            value: 'active'
                        },
                        {
                            title: 'Inativo',
                            value: 'inactive'
                        }
                    ]"
                />

            </v-card-text>

            <v-card-actions>

                <v-spacer />

                <v-btn
                    variant="text"
                    @click="dialog = false"
                >
                    Cancelar
                </v-btn>

                <v-btn
                    color="primary"
                    :loading="loading"
                    @click="save"
                >
                    Salvar
                </v-btn>

            </v-card-actions>

        </v-card>

    </v-dialog>

</AppLayout>

</template>