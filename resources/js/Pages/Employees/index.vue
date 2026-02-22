<script setup>
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, router, usePage } from '@inertiajs/vue3'

const props = defineProps({
    employees: Object,
    companies: Array
})

const page = usePage()
const flash = page.props.flash || {}

const showModal = ref(false)
const editing = ref(false)

const form = useForm({
    id: null,
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    company_id: ''
})

function openCreate() {
    editing.value = false
    form.reset()
    showModal.value = true
}

function openEdit(employee) {
    editing.value = true
    form.id = employee.id
    form.first_name = employee.first_name
    form.last_name = employee.last_name
    form.email = employee.email
    form.phone = employee.phone
    form.company_id = employee.company_id
    showModal.value = true
}

function submit() {
    if (editing.value) {
        form.put(route('employees.update', form.id), { onSuccess: () => showModal.value = false })
    } else {
        form.post(route('employees.store'), { onSuccess: () => showModal.value = false })
    }
}

function destroy(id) {
    if (confirm('Delete this employee?')) {
        router.delete(route('employees.destroy', id))
    }
}
</script>

<template>
<AuthenticatedLayout>
    <template #header>
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Employees</h2>
    </template>

    <div class="p-6 bg-white shadow rounded">
        <button @click="openCreate" class="bg-blue-600 text-white px-4 py-2 rounded mb-4">
            Add Employee
        </button>

        <div v-if="flash.success" class="mb-4 text-green-600">
            {{ flash.success }}
        </div>

        <table class="w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2">Name</th>
                    <th class="p-2">Company</th>
                    <th class="p-2">Email</th>
                    <th class="p-2">Phone</th>
                    <th class="p-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="employee in employees.data" :key="employee.id" class="border-t">
                    <td class="p-2">{{ employee.first_name }} {{ employee.last_name }}</td>
                    <td class="p-2">{{ employee.company?.name }}</td>
                    <td class="p-2">{{ employee.email }}</td>
                    <td class="p-2">{{ employee.phone }}</td>
                    <td class="p-2 space-x-2">
                        <button @click="openEdit(employee)" class="text-blue-600">Edit</button>
                        <button @click="destroy(employee.id)" class="text-red-600">Delete</button>
                    </td>
                </tr>
                <tr v-if="employees.data.length === 0">
                    <td colspan="5" class="text-center p-4 text-gray-500">
                        No employees found.
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4 flex gap-2">
            <button
                v-for="link in employees.links"
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
                <h2 class="text-lg font-bold mb-4">{{ editing ? 'Edit Employee' : 'Add Employee' }}</h2>
                <form @submit.prevent="submit">
                    <input v-model="form.first_name" placeholder="First Name" class="border p-2 w-full mb-2" required />
                    <input v-model="form.last_name" placeholder="Last Name" class="border p-2 w-full mb-2" />
                    <input v-model="form.email" placeholder="Email" class="border p-2 w-full mb-2" type="email" />
                    <input v-model="form.phone" placeholder="Phone" class="border p-2 w-full mb-2" />

                    <select v-model="form.company_id" class="border p-2 w-full mb-2" required>
                        <option value="">Select Company</option>
                        <option v-for="company in companies" :key="company.id" :value="company.id">
                            {{ company.name }}
                        </option>
                    </select>

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
