<template>
  <div class="min-h-screen flex flex-col">
    <!-- Top Navigation -->
    <nav class="bg-white border-b border-gray-100 px-4 sm:px-6 py-4 flex justify-between items-center">
      <div class="flex items-center space-x-4">
        <button @click="mobileMenu = !mobileMenu" class="sm:hidden text-gray-600 hover:text-gray-800">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
        <Link :href="route('dashboard')" class="hidden sm:block">
          <img src="/img/logo.png" alt="Logo" class="w-16 h-auto"/>
        </Link>
        <div class="hidden sm:flex space-x-4">
          <Link
            :href="route('client.collections.index')"
            class="text-gray-700 hover:text-blue-500 px-2 py-1 rounded"
            :class="{'bg-blue-100 text-blue-700': isActive('/client/collections')}"
          >
            {{ $t('collections') }}
          </Link>
          <!-- Más enlaces aquí -->
        </div>
      </div>
      
      <!-- Right controls -->
      <div class="flex items-center space-x-4">
        <LanguageSelect/>
        <!-- Profile Dropdown -->
        <Dropdown align="right" width="48">
          <template #trigger>
            <button class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900">
              {{ user.name }}
              <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>
          </template>
          <template #content>
            <DropdownLink :href="route('profile.edit')">{{ $t('profile') }}</DropdownLink>
            <DropdownLink :href="route('logout')" method="post" as="button">{{ $t('logout') }}</DropdownLink>
          </template>
        </Dropdown>
        <!-- Balance Dropdown -->
        <Dropdown align="right" width="48">
          <template #trigger>
            <button class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900">
              {{ $t('balance') }}: {{ balance }}
              <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>
          </template>
          <template #content>
            <DropdownLink :href="route('profile.edit')">{{ $t('add_credit') }}</DropdownLink>
            <DropdownLink :href="route('profile.edit')">{{ $t('view_transactions') }}</DropdownLink>
          </template>
        </Dropdown>
      </div>
    </nav>
    
    <!-- Mobile Menu Slide Over -->
    <transition name="fade">
      <aside v-if="mobileMenu" class="fixed inset-0 z-50 flex sm:hidden">
        <div class="w-48 bg-white border-r border-gray-200 p-4">
          <div class="flex justify-between mb-4">
            <button @click="mobileMenu = false" class="text-gray-500 hover:text-gray-700">&times;</button>
          </div>
          <nav class="flex flex-col space-y-2">
            <Link
              :href="route('client.collections.index')"
              class="block text-gray-700 hover:text-blue-500 px-2 py-1 rounded"
              @click="mobileMenu = false"
            >{{ $t('collections') }}</Link>
            <!-- Más enlaces -->
          </nav>
        </div>
        <div class="flex-1 bg-black bg-opacity-25" @click="mobileMenu = false"></div>
      </aside>
    </transition>
    
    <!-- Page Header -->
    <header v-if="$slots.header" class="bg-white shadow px-4 sm:px-6 py-4">
      <slot name="header"/>
    </header>
    
    <!-- Main Content -->
    <main class="flex-grow px-4 sm:px-6 py-4 overflow-auto">
      <slot/>
    </main>
    
    <!-- Footer -->
    <AppFooter/>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import LanguageSelect from '@/Components/LanguageSelect.vue';
import AppFooter from '@/Components/Footer.vue';

const mobileMenu = ref(false);
const page = usePage();

const currentUrl = computed(() => page.url);
const user = computed(() => page.props.auth.user);
const balance = computed(() => page.props.auth.user.balance ?? 0);
const isActive = (path) => currentUrl.value.startsWith(path);
</script>


