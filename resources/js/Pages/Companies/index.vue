<template>
<AuthenticatedLayout>
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Companies</h2>
    </template>

    <div class="p-6">
        <a-button type="primary" @click="openCreate" class="mb-4">Add Company</a-button>

        <div v-if="flash?.success" class="mb-4 text-green-600">{{ flash.success }}</div>

        <a-table
            :columns="columns"
            :data-source="companies.data"
            :row-key="'id'"
            :pagination="{ current: companies.current_page, total: companies.total, pageSize: companies.per_page }"
        />

        <!-- Modal -->
        <a-modal
            :title="editing ? 'Edit Company' : 'Add Company'"
            v-model:open="showModal"
            @ok="submit"
            @cancel="showModal = false"
        >
            <a-form layout="vertical">
                <a-form-item label="Name">
                    <a-input v-model:value="form.name" />
                </a-form-item>
                <a-form-item label="Email">
                    <a-input v-model:value="form.email" type="email" />
                </a-form-item>
                <a-form-item label="Website">
                    <a-input v-model:value="form.website" />
                </a-form-item>
                <a-form-item label="Logo">
                    <input type="file" @change="e => form.logo = e.target.files[0]" />
                </a-form-item>
            </a-form>
        </a-modal>
    </div>
</AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    companies: Object,
    flash: Object,
})

const showModal = ref(false)
const editing = ref(false)
const form = useForm({ id: null, name: '', email: '', website: '', logo: null })

function openCreate() {
    editing.value = false
    form.reset()
    showModal.value = true
}

function openEdit(company) {
    editing.value = true
    form.id = company.id
    form.name = company.name
    form.email = company.email
    form.website = company.website
    form.logo = null
    showModal.value = true
}

function submit() {
    if (editing.value) {
        form.post(route('companies.update', form.id), { forceFormData: true, _method: 'put', onSuccess: () => showModal.value = false })
    } else {
        form.post(route('companies.store'), { forceFormData: true, onSuccess: () => showModal.value = false })
    }
}

function destroy(id) {
    if (confirm('Delete this company?')) router.delete(route('companies.destroy', id))
}

const columns = [
    { title: 'Logo', dataIndex: 'logo_url', key: 'logo', customRender: ({ text }) => text ? `<img src="${text}" class="h-10"/>` : '' },
    { title: 'Name', dataIndex: 'name', key: 'name' },
    { title: 'Email', dataIndex: 'email', key: 'email' },
    { title: 'Website', dataIndex: 'website', key: 'website' },
    { title: 'Action', key: 'action', customRender: ({ record }) =>
        `<button class="text-blue-600" @click="openEdit(${record})">Edit</button>
         <button class="text-red-600" @click="destroy(${record.id})">Delete</button>`
    },
]
</script>
