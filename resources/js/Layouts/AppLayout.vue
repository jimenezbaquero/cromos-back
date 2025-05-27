<!-- resources/js/Layouts/AppLayout.vue -->
<template>
  <div class="min-h-screen flex flex-col bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
    <AppHeader :canLogin="$page.props.canLogin" :canRegister="$page.props.canRegister" />
    <main class="flex-grow flex items-center justify-center">
      <slot />
    </main>
    <AppFooter />
  </div>
</template>

<script setup>
import AppHeader from '@/Components/Header.vue'
import AppFooter from '@/Components/Footer.vue'
</script>
