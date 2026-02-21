<script setup>
import { ref, computed } from 'vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import NavLink from '@/Components/NavLink.vue'
import { Link, usePage } from '@inertiajs/vue3'

const showingNavigationDropdown = ref(false)
const page = usePage()

const user = computed(() => page.props.auth.user)
const isAdmin = computed(() => user.value?.role === 'admin')

// Helper untuk menentukan tab aktif (desktop & mobile)
const isActive = (path) => page.url === path || page.url.startsWith(path)
</script>

<template>
<div class="min-h-screen bg-gray-100">
    <!-- NAVBAR -->
    <nav class="border-b border-gray-100 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 justify-between">
                <!-- LEFT SIDE -->
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex shrink-0 items-center">
                        <Link :href="route('dashboard')">
                            <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                        </Link>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <!-- Dashboard -->
                        <NavLink
                            :href="route('dashboard')"
                            :class="isActive('/dashboard') ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-700 hover:text-gray-900'"
                        >
                            Dashboard
                        </NavLink>

                        <!-- Companies (admin only) -->
                        <NavLink
                            v-if="isAdmin"
                            :href="route('companies.index')"
                            :class="isActive('/companies') ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-700 hover:text-gray-900'"
                        >
                            Companies
                        </NavLink>

                        <!-- Employees (admin only) -->
                        <NavLink
                            v-if="isAdmin"
                            :href="route('employees.index')"
                            :class="isActive('/employees') ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-700 hover:text-gray-900'"
                        >
                            Employees
                        </NavLink>
                    </div>
                </div>

                <!-- RIGHT SIDE -->
                <div class="hidden sm:ms-6 sm:flex sm:items-center">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button
                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700"
                            >
                                {{ user.name }}
                                <svg class="ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                            <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                        </template>
                    </Dropdown>
                </div>

                <!-- Hamburger (mobile) -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button
                        @click="showingNavigationDropdown = !showingNavigationDropdown"
                        class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none"
                    >
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- MOBILE MENU -->
        <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
            <div class="space-y-1 pb-3 pt-2">
                <Link
                    :href="route('dashboard')"
                    class="block px-3 py-2 rounded-md text-base font-medium"
                    :class="isActive('/dashboard') ? 'bg-gray-200 text-blue-600' : 'text-gray-700 hover:bg-gray-100'"
                >
                    Dashboard
                </Link>

                <Link
                    v-if="isAdmin"
                    :href="route('companies.index')"
                    class="block px-3 py-2 rounded-md text-base font-medium"
                    :class="isActive('/companies') ? 'bg-gray-200 text-blue-600' : 'text-gray-700 hover:bg-gray-100'"
                >
                    Companies
                </Link>

                <Link
                    v-if="isAdmin"
                    :href="route('employees.index')"
                    class="block px-3 py-2 rounded-md text-base font-medium"
                    :class="isActive('/employees') ? 'bg-gray-200 text-blue-600' : 'text-gray-700 hover:bg-gray-100'"
                >
                    Employees
                </Link>
            </div>

            <div class="border-t border-gray-200 pb-1 pt-4">
                <div class="px-4">
                    <div class="text-base font-medium text-gray-800">{{ user.name }}</div>
                    <div class="text-sm font-medium text-gray-500">{{ user.email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <Link
                        :href="route('profile.edit')"
                        class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100"
                    >
                        Profile
                    </Link>
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100"
                    >
                        Log Out
                    </Link>
                </div>
            </div>
        </div>
    </nav>

    <!-- PAGE HEADER -->
    <header class="bg-white shadow" v-if="$slots.header">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <slot name="header" />
        </div>
    </header>

    <!-- PAGE CONTENT -->
    <main>
        <slot />
    </main>
</div>
</template>
