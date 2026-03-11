<script setup>

	import AppLayout from '@/Layouts/AppLayoutAdmin.vue'
	import { Head, router } from '@inertiajs/vue3'
	import { formatDocument } from '@/utils/format'
	import { formatPhone } from '@/utils/format'
	import { Link } from '@inertiajs/vue3'

	const props = defineProps({
		user: Object
	})

	const formatDate = (date) => {
		return new Date(date).toLocaleDateString('pt-BR')
	}

	const statusLabel = (status) => {
	    if (status === 0) return 'Pendente de aprovação'
	    if (status === 1) return 'Ativo'
	    return 'Desconhecido'
	}

	const statusColor = (status) => {
	    if (status === 0) return 'orange'
	    if (status === 1) return 'green'
	    return 'grey'
	}

	const editUser = () => {
		router.visit(`/admin/users/${props.user.id}/edit`)
	}

	const suspendUser = () => {
		if (confirm('Deseja suspender este usuário?')) {
			router.post(`/admin/users/${props.user.id}/suspend`)
		}
	}

	const deleteUser = () => {
		if (confirm('Deseja excluir este usuário?')) {
			router.delete(`/admin/users/${props.user.id}`)
		}
	}

</script>

<template>

<Head title="Detalhes do Usuário" />

<AppLayout>

<v-container>

	    <!-- VOLTAR -->
    <div class="mb-4 d-flex align-center">

        <v-btn
            variant="text"
            icon="mdi-arrow-left"
            @click="$inertia.visit('/admin/approvals')"
        ></v-btn>

        <span class="ml-2 text-subtitle-1">
            Voltar
        </span>

    </div>

    <!-- TÍTULO -->
    <div class="mb-6">
        <h1 class="text-h4 font-weight-bold">
            Detalhes do Usuário
        </h1>

        <div class="text-grey">
            Informações completas e ações disponíveis
        </div>
    </div>

    <!-- HEADER -->
    <v-card class="pa-6 mb-6" rounded="lg">

        <div class="d-flex align-center">

            <v-avatar size="80" color="grey">
                {{ user.name.substring(0,2).toUpperCase() }}
            </v-avatar>

            <div class="ml-4">
                <h2 class="text-h6 font-weight-bold">
                    {{ user.data?.full_name }}
                </h2>

                <v-chip
                :color="statusColor(user.status)"
                size="small"
                >
                {{ statusLabel(user.status) }}
            </v-chip>
            </div>

        </div>

    </v-card>


    <!-- INFORMAÇÕES -->
    <v-card class="pa-6 mb-6" rounded="lg">

        <h3 class="text-subtitle-1 font-weight-bold mb-4">
            Informações
        </h3>

        <v-row>

            <v-col cols="12" md="6">
                <div class="info-box">
                    <div class="label">E-mail</div>
                    <div class="value">{{ user.email }}</div>
                </div>
            </v-col>

            <v-col cols="12" md="6">
                <div class="info-box">
                    <div class="label">Telefone</div>
                    <div class="value">
                    	{{ formatPhone(user.data?.phone) }}
                    </div>
                </div>
            </v-col>

            <v-col cols="12" md="6">
                <div class="info-box">
                    <div class="label">Documento</div>
                    <div class="value">
                    	{{ formatDocument(user.data?.document, user.data?.person_type) }}
                    </div>
                </div>
            </v-col>

            <v-col cols="12" md="6">
                <div class="info-box">
                    <div class="label">Data de Cadastro</div>
                    <div class="value">
                        {{ formatDate(user.created_at) }}
                    </div>
                </div>
            </v-col>

        </v-row>

    </v-card>


    <!-- DOCUMENTAÇÃO -->
    <v-card class="pa-6 mb-6" rounded="lg">

        <h3 class="text-subtitle-1 font-weight-bold mb-4">
            Documentação
        </h3>

        <v-list>

            <v-list-item
                v-if="user.data?.cnh_file"
                :href="`/storage/${user.data.cnh_file}`"
                target="_blank"
            >
                CNH Definitiva
            </v-list-item>

            <v-list-item
                v-if="user.data?.instructor_credential"
                :href="`/storage/${user.data.instructor_credential}`"
                target="_blank"
            >
                Credencial de Instrutor de Trânsito
            </v-list-item>

            <v-list-item
                v-if="user.data?.criminal_record"
                :href="`/storage/${user.data.criminal_record}`"
                target="_blank"
            >
                Certidão Negativa de Antecedentes
            </v-list-item>

            <v-list-item
                v-if="user.data?.face_photo"
                :href="`/storage/${user.data.face_photo}`"
                target="_blank"
            >
                Foto de Rosto (Selfie)
            </v-list-item>

        </v-list>

    </v-card>

    <v-card class="pa-6">

    <h3 class="mb-4">Ações do Usuário</h3>

    <div class="d-flex gap-4">

        <!-- editar -->
        <Link :href="route('admin.users.edit', user.id)">
    <v-btn color="primary">
        Editar usuário
    </v-btn>
</Link>

        <!-- suspender -->
<Link
    as="button"
    method="post"
    :href="route('admin.users.suspend', user.id)"
>
    <v-btn color="warning">
        Suspender usuário
    </v-btn>
</Link>

        <!-- excluir -->
<Link
    as="button"
    method="delete"
    :href="route('admin.users.destroy', user.id)"
>
    <v-btn color="error">
        Excluir usuário
    </v-btn>
</Link>

    </div>

</v-card>

</v-container>

</AppLayout>

</template>