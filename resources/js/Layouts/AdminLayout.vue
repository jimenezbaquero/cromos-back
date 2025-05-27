<template>
  <div class="min-h-screen flex flex-col sm:grid sm:grid-cols-[160px_1fr]">
    <!-- Mobile Sidebar (slide over) -->
    <transition name="fade">
      <aside
          v-if="sidebarOpen"
          class="fixed inset-0 z-50 flex sm:hidden"
      >
        <div class="w-40 bg-white border-r border-gray-200 p-4 space-y-4">
          <div class="flex justify-between items-center mb-6">
            <button @click="sidebarOpen = false" class="text-gray-500 hover:text-gray-700">&times;</button>
          </div>
          <nav class="flex flex-col space-y-2">
            <Link :href="route('users.index')" class="text-gray-700 hover:text-blue-500">Usuarios</Link>
            <Link :href="route('dashboard')" class="text-gray-700 hover:text-blue-500">Colecciones</Link>
            <Link :href="route('dashboard')" class="text-gray-700 hover:text-blue-500">Marcas</Link>
          </nav>
        </div>
        <div class="flex-1 bg-black bg-opacity-25" @click="sidebarOpen = false"></div>
      </aside>
    </transition>

    <!-- Desktop Sidebar -->
    <aside class="w-40 hidden sm:block bg-white border-r border-gray-200 p-4 space-y-4">

      <nav class="flex flex-col space-y-2">
        <Link :href="route('users.index')" class="text-gray-700 hover:text-blue-500">Usuarios</Link>
        <Link :href="route('dashboard')" class="text-gray-700 hover:text-blue-500">Colecciones</Link>
        <Link :href="route('dashboard')" class="text-gray-700 hover:text-blue-500">Marcas</Link>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex flex-col min-h-screen overflow-hidden">
      <!-- Top Navigation -->
      <nav class="bg-white border-b border-gray-100 px-4 sm:px-6 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
          <!-- Mobile Hamburger -->
          <button @click="sidebarOpen = true" class="sm:hidden text-gray-600 hover:text-gray-800">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
          </button>
        </div>

        <div class="flex items-center space-x-4">
          <LanguageSelect/>
          <Dropdown align="right" width="48">
            <template #trigger>
              <button
                  class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none"
              >
                {{ $page.props.auth.user.name }}
                <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7"/>
                </svg>
              </button>
            </template>

            <template #content>
              <DropdownLink :href="route('profile.edit')">
                Perfil
              </DropdownLink>
              <DropdownLink :href="route('logout')" method="post" as="button">
                Cerrar sesión
              </DropdownLink>
            </template>
          </Dropdown>
        </div>
      </nav>

      <!-- Page Header -->
      <header v-if="$slots.header" class="bg-white shadow px-4 sm:px-6 py-4">
        <slot name="header" />
      </header>

      <!-- Page Content -->
      <main class="flex-grow px-4 sm:px-6 py-4 overflow-auto">
        <slot />
      </main>
    </div>
  </div>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import LanguageSelect from "@/Components/LanguageSelect.vue";


const sidebarOpen = ref(false);
</script>
