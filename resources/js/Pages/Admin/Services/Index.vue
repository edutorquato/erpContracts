<script setup>

    import AppLayout from '@/Layouts/AppLayoutAdmin.vue'
    import { Head } from '@inertiajs/vue3'
    import { ref } from 'vue'
    import axios from 'axios'

    const props = defineProps({
        services: Array
    })

    const services = ref([...props.services])

    const loading = ref(false)

    const dialog = ref(false)



    const snackbar = ref(false)

    const snackbarText = ref('')

    const snackbarColor = ref('success')

    const showSnackbar = (
        text,
        color = 'success'
        ) => {

        snackbarText.value = text

        snackbarColor.value = color

        snackbar.value = true
    }

    const search = ref('')

    const form = ref({
        id: null,
        name: '',
        monthly_base_value: '',
    })

    const headers = [
        { title: 'Nome', key: 'name' },
        { title: 'Valor Base', key: 'monthly_base_value' },
        { title: 'Ações', key: 'actions', sortable: false },
    ]

    const openCreate = () => {

        form.value = {
            id: null,
            name: '',
            monthly_base_value: '',
        }

        dialog.value = true
    }

    const edit = (service) => {

        form.value = {
            ...service
        }

        dialog.value = true
    }


    const save = async () => {

        loading.value = true

        errors.value = {}

        try {

            let response

            if (form.value.id) {

                response = await axios.put(
            `/admin/services/${form.value.id}`,
            form.value
            )

                const index = services.value.findIndex(
                    service => service.id == form.value.id
                    )

                services.value[index] = response.data.service

                showSnackbar(
                    'Serviço atualizado com sucesso'
                    )

            } else {

                response = await axios.post(
                    '/admin/services',
                    form.value
                    )

                services.value.unshift(
                    response.data.service
                    )

                showSnackbar(
                    'Serviço criado com sucesso'
                    )
            }

            dialog.value = false

        } catch (error) {

            if (error.response?.status === 422) {

                errors.value = error.response.data.errors
            }

            showSnackbar(
                'Erro ao salvar serviço',
                'error'
                )

        } finally {

            loading.value = false
        }
    }


    const remove = async (service) => {

        if (!confirm(
            'Deseja remover este serviço?'
            )) return

            try {

                await axios.delete(
            `/admin/services/${service.id}`
            )

                services.value = services.value.filter(
                    item => item.id !== service.id
                    )

                showSnackbar(
                    'Serviço removido com sucesso'
                    )

            } catch {

                showSnackbar(
                    'Erro ao remover serviço',
                    'error'
                    )
            }
        }

        const currency = (value) => {

            return Number(value).toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            })
        }

    </script>

    <template>

        <Head title="Serviços" />

        <AppLayout>

            <div class="d-flex justify-space-between align-center mb-6">

                <h1 class="text-h5 font-weight-medium">
                    Serviços
                </h1>

                <v-btn
                color="primary"
                @click="openCreate"
                >
                Novo Serviço
            </v-btn>

        </div>

        <v-card rounded="lg">

            <v-text-field
            v-model="search"
            label="Buscar serviço"
            prepend-inner-icon="mdi-magnify"
            variant="outlined"
            density="comfortable"
            hide-details
            class="ma-4"
            />


            <v-data-table
            :headers="headers"
            :items="services"
            :search="search"
            :items-per-page="10"
            >

            <template v-slot:item.monthly_base_value="{ item }">

                {{ currency(item.monthly_base_value) }}

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
max-width="500"
>

<v-card rounded="lg">

    <v-card-title>
        {{
            form.id
            ? 'Editar Serviço'
            : 'Novo Serviço'
        }}
    </v-card-title>

    <v-card-text>

        <v-text-field
        v-model="form.name"
        label="Nome"
        />

        <v-text-field
        v-model="form.monthly_base_value"
        label="Valor Base Mensal"
        type="number"
        class="mt-4"
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


<v-snackbar
v-model="snackbar"
:color="snackbarColor"
timeout="3000"
>

{{ snackbarText }}

</v-snackbar>


</template>