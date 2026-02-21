<script setup>
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, router } from '@inertiajs/vue3'

const props = defineProps({
    companies: Object,
    flash: Object
})

const showModal = ref(false)
const editing = ref(false)

const form = useForm({
    id: null,
    name: '',
    email: '',
    website: '',
    logo: null
})

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
        form.post(route('companies.update', form.id), {
            forceFormData: true,
            onSuccess: () => showModal.value = false,
            _method: 'put'
        })
    } else {
        form.post(route('companies.store'), {
            forceFormData: true,
            onSuccess: () => showModal.value = false
        })
    }
}

function destroy(id) {
    if (confirm('Delete this company?')) {
        router.delete(route('companies.destroy', id))
    }
}
</script>

<template>
<AuthenticatedLayout>
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Companies</h2>
    </template>

    <div class="p-6">
        <button @click="openCreate" class="bg-blue-600 text-white px-4 py-2 rounded mb-4">
            Add Company
        </button>

        <div v-if="flash?.success" class="mb-4 text-green-600">{{ flash.success }}</div>

        <table class="w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2">Logo</th>
                    <th class="p-2">Name</th>
                    <th class="p-2">Email</th>
                    <th class="p-2">Website</th>
                    <th class="p-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="company in companies.data" :key="company.id" class="border-t">
                    <td class="p-2">
                        <img v-if="company.logo" :src="`/storage/${company.logo}`" class="h-10" />
                    </td>
                    <td class="p-2">{{ company.name }}</td>
                    <td class="p-2">{{ company.email }}</td>
                    <td class="p-2">{{ company.website }}</td>
                    <td class="p-2 space-x-2">
                        <button @click="openEdit(company)" class="text-blue-600">Edit</button>
                        <button @click="destroy(company.id)" class="text-red-600">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4 flex gap-2">
            <button
                v-for="link in companies.links"
                :key="link.label"
                v-html="link.label"
                :disabled="!link.url"
                @click="router.visit(link.url)"
                class="px-3 py-1 border rounded"
            />
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">
            <div class="bg-white p-6 rounded w-96">
                <h2 class="text-lg font-bold mb-4">{{ editing ? 'Edit Company' : 'Add Company' }}</h2>
                <form @submit.prevent="submit">
                    <input v-model="form.name" placeholder="Name" class="border p-2 w-full mb-2" />
                    <input v-model="form.email" placeholder="Email" class="border p-2 w-full mb-2" />
                    <input v-model="form.website" placeholder="Website" class="border p-2 w-full mb-2" />
                    <input type="file" @change="e => form.logo = e.target.files[0]" class="mb-2" />
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showModal = false" class="px-3 py-1 border">Cancel</button>
                        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</AuthenticatedLayout>
</template>
